<?php

namespace Moip\Resource;

use JsonSerializable;
use Moip\Exceptions;
use Moip\Links;
use Moip\Moip;
use Requests;
use Requests_Exception;
use stdClass;

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
        return $this->getIfSetDateFmt($key, \DateTime::ATOM, $data);
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
     * Returns the HATEOAS structure, if any.
     *
     * @return null|Links
     */
    public function getLinks()
    {
        $obj = $this->getIfSet('_links');
        if ($obj) {
            return new Links($obj);
        }
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

        return $this->populate($response);
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
}
