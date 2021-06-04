<?php

namespace Moip\Resource;

use stdClass;

/**
 * Class Event.
 */
class Event extends MoipResource
{
    /**
     * @const string
     */
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
     * @return string possible values:
     *                ORDER.CREATED
     *                ORDER.PAID
     *                ORDER.NOT_PAID
     *                ORDER.PAID
     *                ORDER.REVERTED
     *                PAYMENT.AUTHORIZED
     *                PAYMENT.IN_ANALYSIS
     *                PAYMENT.CANCELLED
     *                ENTRY.SETTLED
     *                PLAN.CREATED
     *                PLAN.UPDATED
     *                PLAN.ACTIVATED
     *                PLAN.INACTIVATED
     *                CUSTOMER.CREATED
     *                CUSTOMER.UPDATED
     *                SUBSCRIPTION.CREATED
     *                SUBSCRIPTION.UPDATE
     *                SUBSCRIPTION.ACTIVATED
     *                SUBSCRIPTION.SUSPENDED
     *                SUBSCRIPTION.CANCELED
     *                INVOICE.CREATED
     *                INVOICE.UPDATED
     */
    public function getType()
    {
        return $this->data->type;
    }

    /**
     * Get creation date of the event.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        // todo: didn't find Events on documentation but i'm assuming it's a datetime, have to confirm it
        return $this->getIfSetDateTime('createdAt');
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
