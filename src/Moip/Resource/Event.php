<?php

namespace Moip\Resource;

use stdClass;

class Event extends MoipResource
{
    public function initialize()
    {
        $this->data = new stdClass();
        $this->data->type = null;
        $this->data->createdAt = null;
        $this->data->descriotion = null;
    }

    public function getType()
    {
        return $this->data->type;
    }

    public function getCreatedAt()
    {
        return $this->data->createdAt;
    }

    public function getDescription()
    {
        return $this->data->description;
    }

    protected function populate(stdClass $response)
    {
        $this->data = $response;
    }
}
