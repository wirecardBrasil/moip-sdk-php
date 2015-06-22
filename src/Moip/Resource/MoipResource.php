<?php

namespace Moip\Resource;

use Moip\Moip;
use stdClass;

abstract class MoipResource implements \JsonSerializable
{
    /**
     * @var Moip\Moip
     */
    protected $moip;

    /**
     * @var \stdClass
     */
    protected $data;

    public function __construct(Moip $moip)
    {
        $this->moip = $moip;
        $this->data = new stdClass();
        $this->initialize();
    }

    protected function createConnection()
    {
        return $this->moip->createConnection();
    }

    protected function getIfSet($key, stdClass $data = null)
    {
        if ($data == null) {
            $data = $this->data;
        }

        if (isset($data->$key)) {
            return $data->$key;
        }
    }

    abstract protected function initialize();

    public function jsonSerialize()
    {
        return $this->data;
    }

    abstract protected function populate(stdClass $response);
}
