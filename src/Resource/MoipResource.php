<?php

namespace Moip\Resource;

use JsonSerializable;
use Moip\Http\HTTPConnection;
use Moip\Http\HTTPRequest;
use Moip\Moip;
use Moip\Exceptions;
use RuntimeException;
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
     * Create a new connecttion.
     *
     * @return \Moip\Http\HTTPConnection
     */
    protected function createConnection()
    {
        return $this->moip->createConnection(new HTTPConnection());
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

        return;
    }

    protected function getIfSetDateFmt($key, $fmt, stdClass $data = null)
    {
        $val = $this->getIfSet($key, $data);
        if (!empty($val)) {
            $dt = \DateTime::createFromFormat($fmt, $val);

            return $dt ? $dt : null;
        }

        return;
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
     *
     * Execute a http request. If payload == null no body will be sent. Empty body ('{}') is supported by sending a
     * empty stdClass
     *
     * @param               $path
     * @param               $method
     * @param mixed|null    $payload
     *
     * @throws Exceptions\ValidationException  if the API returns a 4xx http status code. Usually means invalid data was sent.
     * @throws Exceptions\UnautorizedException if the API returns a 401 http status code. Check API token and key
     * @throws Exceptions\UnexpectedException  if the API returns a 500 http status code, this is not suppose to happen. Please report the error to moip
     *
     * @return stdClass
     */
    protected function httpRequest($path, $method, $payload = null)
    {
        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');
        if ($payload !== null) {
            // if it's json serializable
            $body = json_encode($payload, JSON_UNESCAPED_SLASHES);
            if ($body) {
                $httpConnection->addHeader('Content-Length', strlen($body));
                $httpConnection->setRequestBody($body);
            }
        }

        /**
         * @var \Moip\Http\HTTPResponse $http_response
         */
        $http_response = $httpConnection->execute($path, $method);

        $code = $http_response->getStatusCode();

        if ($code >= 200 && $code < 299) {
            return json_decode($http_response->getContent());
        } elseif ($code == 401) {
            throw new Exceptions\UnautorizedException();
        } elseif ($code >= 400 && $code <= 499) {
            $error_obj = json_decode($http_response->getContent());
            $errors = [];
            if ($error_obj && isset($error_obj->errors)) { // just in case
                foreach ($error_obj->errors as $error) {
                    $errors[] = new Exceptions\Error($error->code, $error->path, $error->description);
                }
                throw new Exceptions\ValidationException($code, $http_response->getStatusMessage(), $errors);
            } else {
                throw new Exceptions\UnexpectedException();

            }
        } else {
            throw new Exceptions\UnexpectedException();
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
        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');

        $httpResponse = $httpConnection->execute($path, HTTPRequest::GET);

        if ($httpResponse->getStatusCode() != 200) {
            throw new RuntimeException($httpResponse->getStatusMessage(), $httpResponse->getStatusCode());
        }

        return $this->populate(json_decode($httpResponse->getContent()));
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
        $body = json_encode($this, JSON_UNESCAPED_SLASHES);

        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');
        $httpConnection->addHeader('Content-Length', strlen($body));
        $httpConnection->setRequestBody($body);

        $httpResponse = $httpConnection->execute($path, HTTPRequest::POST);

        if ($httpResponse->getStatusCode() != 201) {
            throw new RuntimeException($httpResponse->getStatusMessage(), $httpResponse->getStatusCode());
        }

        return $this->populate(json_decode($httpResponse->getContent()));
    }
}
