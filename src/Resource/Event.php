<?php

namespace Moip\Resource;

use stdClass;

class Event extends MoipResource
{
    const PATH = 'events';
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
     * @return strign possible values:
     * ORDER.CREATED
     * ORDER.PAID
     * ORDER.NOT_PAID
     * ORDER.PAID
     * ORDER.REVERTED
     * PAYMENT.AUTHORIZED
     * PAYMENT.IN_ANALYSIS
     * PAYMENT.CANCELLED
     * ENTRY.SETTLED
     * PLAN.CREATED
     * PLAN.UPDATED
     * PLAN.ACTIVATED
     * PLAN.INACTIVATED
     * CUSTOMER.CREATED
     * CUSTOMER.UPDATED
     * SUBSCRIPTION.CREATED
     * SUBSCRIPTION.UPDATE
     * SUBSCRIPTION.ACTIVATED
     * SUBSCRIPTION.SUSPENDED
     * SUBSCRIPTION.CANCELED
     * INVOICE.CREATED
     * INVOICE.UPDATED
     */
    public function getType()
    {
        return $this->data->type;
    }

    /**
     * Get creation date of the event.
     * 
     * @return datetime 
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
