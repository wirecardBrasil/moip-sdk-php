<?php

namespace Moip\Resource;

use JsonSerializable;
use Moip\Http\HTTPConnection;
use Moip\Http\HTTPRequest;
use Moip\Moip;
use RuntimeException;
use stdClass;

abstract class MoipResource implements JsonSerializable {
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
    public function __construct(Moip $moip) {
        $this->moip = $moip;
        $this->data = new stdClass();
        $this->initialize();
    }

    /**
     * Create a new connecttion.
     *
     * @return \Moip\Http\HTTPConnection
     */
    protected function createConnection() {
        return $this->moip->createConnection(new HTTPConnection());
    }

    /**
     * Get a key of an object if he exist.
     *
     * @param string $key
     * @param \stdClass|null $data
     *
     * @return mixed
     */
    protected function getIfSet($key, stdClass $data = null) {

        if(empty($data)){
            $data = $this->data;
        }

        if (isset($data->$key)) {
            return $data->$key;
        }

        return null;
    }

    protected function getIfSetDateFmt($key, $fmt, stdClass $data=null){
        $val = $this->getIfSet($key, $data);
        if (!empty($val)) {
            $dt = \DateTime::createFromFormat($fmt, $val);
            return $dt ? $dt : null;
        }
        return null;

    }

    /**
     * Get a key, representing a date (Y-m-d), of an object if it exists.
     * @param string $key
     * @param stdClass|null $data
     * @return \DateTime|null
     */
    protected function getIfSetDate($key, stdClass $data = null) {

        return $this->getIfSetDateFmt($key, 'Y-m-d', $data);

    }

    /**
     * Get a key representing a datetime (\Datetime::ATOM), of an object if it exists.
     * @param string $key
     * @param stdClass|null $data
     * @return \DateTime|null
     */

    protected function getIfSetDateTime($key, stdClass $data = null) {
        return $this->getIfSetDateFmt($key, \DateTime::ATOM, $data);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return \stdClass
     */
    public function jsonSerialize() {
        return $this->data;
    }

    /**
     * Find by path.
     *
     * @param string $path
     *
     * @return stdClass
     */
    public function getByPath($path) {
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
    public function createResource($path) {
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
