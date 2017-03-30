<?php

namespace Moip\Resource;

use stdClass;

/**
 * Class Entry.
 */
class Entry extends MoipResource
{
    /**
     * @const string
     */
    const PATH = 'entries';

    /**
     * Initializes new instances.
     */
    protected function initialize()
    {
        $this->data = new stdClass();
        $this->data->amount = new stdClass();
        $this->data->details = new stdClass();
        $this->data->parentPayments = new stdClass();
    }

    /**
     * Mount the entry.
     *
     * @param \stdClass $response
     *
     * @return Entry Entry information.
     */
    protected function populate(stdClass $response)
    {
        $entry = clone $this;

        $entry->data->id = $this->getIfSet('id', $response);
        $entry->data->status = $this->getIfSet('status', $response);
        $entry->data->operation = $this->getIfSet('operation', $response);

        if (isset($response->amount)) {
            $entry->data->amount->total = $this->getIfSet('total', $response->amount);
            $entry->data->amount->fee = $this->getIfSet('fee', $response->amount);
            $entry->data->amount->liquid = $this->getIfSet('liquid', $response->amount);
            $entry->data->amount->currency = $this->getIfSet('currency', $response->amount);
        }

        if (isset($response->details)) {
            $entry->data->details = $this->getIfSet('details', $response);
        }

        if (isset($response->{'parent'}) && isset($response->{'parent'}->payments)) {
            $payments = new Payment($entry->moip);
            $payments->populate($response->{'parent'}->payments);

            $entry->data->parentPayments = $payments;
        }

        return $entry;
    }

    /**
     * Get entry in api by id.
     *
     * @param string $id_moip Event ID that generated the launch.
     *
     * @return stdClass
     */
    public function get($id_moip)
    {
        return $this->getByPath(sprintf('/%s/%s/%s/', MoipResource::VERSION, self::PATH, $id_moip));
    }

    /**
     * Get id from entry.
     *
     * @return string Event ID that generated the launch.
     */
    public function getId()
    {
        return $this->getIfSet('id');
    }

    /**
     * Get status from entry.
     *
     * @return string Launch status. Possible values: SCHEDULED, SETTLED.
     */
    public function getStatus()
    {
        return $this->getIfSet('status');
    }

    public function getOperation()
    {
        return $this->getIfSet('operation');
    }

    /**
     * Get total value of order.
     *
     * @return int|float
     */
    public function getAmountTotal()
    {
        return $this->getIfSet('total', $this->data->amount);
    }

    /**
     * Get total value of MoIP rate.
     *
     * @return int|float
     */
    public function getAmountFee()
    {
        return $this->getIfSet('fee', $this->data->amount);
    }

    /**
     * Get net total value.
     *
     * @return int|float
     */
    public function getAmountLiquid()
    {
        return $this->getIfSet('liquid', $this->data->amount);
    }

    /**
     * Get currency used in the application. Possible values: BRL.
     *
     * @return string
     */
    public function getAmountCurrency()
    {
        return $this->getIfSet('currency', $this->data->amount);
    }

    /**
     * Get additional description.
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->getIfSet('details');
    }

    /**
     * Get parant payments.
     *
     * @return string
     */
    public function getParentPayments()
    {
        return $this->getIfSet('parentPayments');
    }

    /**
     * Get expected date of settlement.
     *
     * @return \DateTime
     */
    public function getScheduledFor()
    {
        return $this->getIfSetDateTime('scheduledFor');
    }

    /**
     * Get Settlement date;.
     *
     * @return \DateTime
     */
    public function getSettledAt()
    {
        return $this->getIfSetDateTime('settledAt');
    }

    /**
     * Get date of last update.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->getIfSetDateTime('updatedAt');
    }

    /**
     * Get creation date of launch.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->getIfSetDateTime('createdAt');
    }
}
