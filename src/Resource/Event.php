<?php

namespace Moip\Resource;

use stdClass;

class Event extends MoipResource
{
    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
        $this->data->type = null;
        $this->data->createdAt = null;
        $this->data->descriotion = null;
    }

    /**
     * Get event Type.
     * 
     * @return strign ORDER.CREATED, ORDER.WAITING, ORDER.PAID, ORDER.NOT_PAID or ORDER.REVERTED
     */
    public function getType()
    {
        return $this->data->type;
    }

    /**
     * Get creation date of the event.
     * 
     * @return strign
     */
    public function getCreatedAt()
    {
        return $this->data->createdAt;
    }

    /**
     * Get event Description.
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->data->description;
    }

    /**
     * Populate Event.
     *
     * @param \stdClass $response
     *
     * @return \stdClass
     */
    protected function populate(stdClass $response)
    {
        $this->data = $response;
    }
}
