<?php

namespace Moip\Resource;

use JsonSerializable;
use Moip\Exceptions;
use Moip\Helper\Filters;
use Moip\Helper\Links;
use Moip\Helper\Pagination;
use Moip\Moip;
use Requests;
use Requests_Exception;
use stdClass;

/**
 * Class MoipResource.
 */
abstract class MoipResource implements JsonSerializable
{
    /**
     * Version of API.
     *
     * @const string
     */
    const VERSION = 'v2';

    /**
     * @var \Moip\Moip
     */
    protected $moip;

    /**
     * @var \stdClass
     */
    protected $data;

    /**
     * Initialize a new instance.
     */
    abstract protected function initialize();

    /**
     * Mount information of a determined object.
     *
     * @param \stdClass $response
     *
     * @return mixed
     */
    abstract protected function populate(stdClass $response);

    /**
     * Create a new instance.
     *
     * @param \Moip\Moip $moip
     */
    public function __construct(Moip $moip)
    {
        $this->moip = $moip;
        $this->data = new stdClass();
        $this->initialize();
    }

    /**
     * Get a key of an object if it exists.
     *
     * @param string         $key
     * @param \stdClass|null $data
     *
     * @return mixed
     */
    protected function getIfSet($key, stdClass $data = null)
    {
        if (empty($data)) {
            $data = $this->data;
        }

        if (isset($data->$key)) {
            return $data->$key;
        }
    }

    /**
     * @return \Moip\Helper\Links
     */
    public function getLinks()
    {
        $links = $this->getIfSet('_links');

        if ($links !== null) {
            return new Links($links);
        }
    }

    /**
     * @param $key
     * @param $fmt
     * @param stdClass|null $data
     *
     * @return bool|\DateTime|null
     */
    protected function getIfSetDateFmt($key, $fmt, stdClass $data = null)
    {
        $val = $this->getIfSet($key, $data);
        if (!empty($val)) {
            $dt = \DateTime::createFromFormat($fmt, $val);

            return $dt ? $dt : null;
        }
    }

    /**
     * Get a key, representing a date (Y-m-d), of an object if it exists.
     *
     * @param string        $key
     * @param stdClass|null $data
     *
     * @return \DateTime|null
     */
    protected function getIfSetDate($key, stdClass $data = null)
    {
        return $this->getIfSetDateFmt($key, 'Y-m-d', $data);
    }

    /**
     * Get a key representing a datetime (\Datetime::ATOM), of an object if it exists.
     *
     * @param string        $key
     * @param stdClass|null $data
     *
     * @return \DateTime|null
     */
    protected function getIfSetDateTime($key, stdClass $data = null)
    {
        $rawDateTime = $this->getIfSet($key, $data);

        $dateTime = null;
        if (!empty($rawDateTime)) {
            $dateTime = new \DateTime($rawDateTime);
        }

        return $dateTime;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return \stdClass
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * Generate URL to request.
     *
     * @param $action
     * @param $id
     *
     * @return string
     */
    public function generatePath($action, $id = null)
    {
        if (!is_null($id)) {
            return sprintf('%s/%s/%s/%s', self::VERSION, static::PATH, $action, $id);
        }

        return sprintf('%s/%s/%s', self::VERSION, static::PATH, $action);
    }

    /**
     * Generate URL to request a get list.
     *
     * @param Pagination $pagination
     * @param Filters    $filters
     * @param array      $params
     *
     * @return string
     */
    public function generateListPath(Pagination $pagination = null, Filters $filters = null, $params = [])
    {
        $queryParams = [];

        if (!is_null($pagination)) {
            if ($pagination->getLimit() != 0) {
                $queryParams['limit'] = $pagination->getLimit();
            }

            if ($pagination->getOffset() >= 0) {
                $queryParams['offset'] = $pagination->getOffset();
            }
        }

        if (!is_null($filters)) {
            $queryParams['filters'] = $filters->__toString();
        }

        if (!empty($params)) {
            $queryParams = array_merge($queryParams, $params);
        }

        if (!empty($queryParams)) {
            return sprintf('/%s/%s?%s', self::VERSION, static::PATH, http_build_query($queryParams));
        }

        return sprintf('/%s/%s', self::VERSION, static::PATH);
    }

    /**
     * Execute a http request. If payload == null no body will be sent. Empty body ('{}') is supported by sending a
     * empty stdClass.
     *
     * @param string     $path
     * @param string     $method
     * @param mixed|null $payload
     *
     * @throws Exceptions\ValidationException  if the API returns a 4xx http status code. Usually means invalid data was sent.
     * @throws Exceptions\UnautorizedException if the API returns a 401 http status code. Check API token and key.
     * @throws Exceptions\UnexpectedException  if the API returns a 500 http status code or something unexpected happens (ie.: Network error).
     *
     * @return stdClass
     */
    protected function httpRequest($path, $method, $payload = null)
    {
        $http_sess = $this->moip->getSession();
        $headers = [];
        $body = null;
        if ($payload !== null) {
            $body = json_encode($payload, JSON_UNESCAPED_SLASHES);
            if ($body) {// if it's json serializable
                $headers['Content-Type'] = 'application/json';
            } else {
                $body = null;
            }
        }

        try {
            $http_response = $http_sess->request($path, $headers, $body, $method);
        } catch (Requests_Exception $e) {
            throw new Exceptions\UnexpectedException($e);
        }

        $code = $http_response->status_code;
        $response_body = $http_response->body;
        if ($code >= 200 && $code < 300) {
            return json_decode($response_body);
        } elseif ($code == 401) {
            throw new Exceptions\UnautorizedException();
        } elseif ($code >= 400 && $code <= 499) {
            $errors = Exceptions\Error::parseErrors($response_body);

            throw new Exceptions\ValidationException($code, $errors);
        }

        throw new Exceptions\UnexpectedException();
    }

    /**
     * Find by path.
     *
     * @param string $path
     *
     * @return stdClass
     */
    public function getByPath($path)
    {
        $response = $this->httpRequest($path, Requests::GET);

        if (is_array($response)) {
            $response = (object) $response;
        }

        return $this->populate($response);
    }

    /**
     * Find by path with no populate method.
     *
     * @param string $path
     *
     * @return stdClass
     */
    public function getByPathNoPopulate($path)
    {
        return $this->httpRequest($path, Requests::GET);
    }

    /**
     * Create a new item in Moip.
     *
     * @param string $path
     *
     * @return stdClass
     */
    public function createResource($path)
    {
        $response = $this->httpRequest($path, Requests::POST, $this);

        return $this->populate($response);
    }

    /**
     * Delete a new item in Moip.
     *
     * @param $path
     *
     * @return mixed
     */
    public function deleteByPath($path)
    {
        return $this->httpRequest($path, Requests::DELETE);
    }
}
