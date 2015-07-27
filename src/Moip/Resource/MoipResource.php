<?php

namespace Moip\Resource;

use Moip\Moip;
use stdClass;
use JsonSerializable;

abstract class MoipResource implements JsonSerializable
{
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
     * @return \stdClass
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
     * @return \Moip\Moip
     */
    protected function createConnection()
    {
        return $this->moip->createConnection();
    }

    /**
     * Get a key of an object if he exist.
     * 
     * @param string         $key
     * @param \stdClass|null $data
     *
     * @return \stdClass|string|null
     */
    protected function getIfSet($key, stdClass $data = null)
    {
        if ($data == null) {
            $data = $this->data;
        }

        if (isset($data->$key)) {
            return $data->$key;
        }
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
}
