<?php

namespace Moip\Resource;

use ArrayIterator;
use stdClass;

/**
 * Class Multiorders.
 */
class Multiorders extends MoipResource
{
    /**
     * @const string
     */
    const PATH = 'multiorders';

    /**
     * Initializes new instances.
     */
    public function initialize()
    {
        $this->data = new stdClass();
        $this->data->ownId = null;
        $this->data->orders = [];
    }

    /**
     * Structure of order.
     *
     * @param Orders $order
     *
     * @return $this
     */
    public function addOrder(Orders $order)
    {
        $this->data->orders[] = $order;

        return $this;
    }

    /**
     * Create a new multiorder in MoIP.
     *
     * @return stdClass
     */
    public function create()
    {
        return $this->createResource(sprintf('/%s/%s', MoipResource::VERSION, self::PATH));
    }

    /**
     * Get an multiorder in MoIP.
     *
     * @param string $id_moip Id MoIP order id
     *
     * @return stdClass
     */
    public function get($id_moip)
    {
        return $this->getByPath(sprintf('/%s/%s/%s', MoipResource::VERSION, self::PATH, $id_moip));
    }

    /**
     * Get MoIP order id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->getIfSet('id');
    }

    /**
     * Get own request id. external reference.
     *
     * @return string
     */
    public function getOwnId()
    {
        return $this->getIfSet('ownId');
    }

    /**
     * Get order status.
     * Possible values: CREATED, WAITING, PAID, NOT_PAID, REVERTED.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->getIfSet('status');
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
     * Get currency used in the application. Possible values: BRL.
     *
     * @return string
     */
    public function getAmountCurrency()
    {
        return $this->getIfSet('currency', $this->data->amount);
    }

    /**
     * Get creation date of launch.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        // todo: didn't find createdAt type on documentation, assuming datetime. Have to confirm
        return $this->getIfSetDateTime('createdAt');
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
     * Get orders.
     *
     * @return \ArrayIterator
     */
    public function getOrderIterator()
    {
        return new ArrayIterator($this->getIfSet('orders'));
    }

    /**
     * Structure of multipayments.
     *
     * @return \Moip\Resource\Payment
     */
    public function multipayments()
    {
        $payments = new Payment($this->moip);
        $payments->setMultiorder($this);

        return $payments;
    }

    /**
     * Mount the structure of order.
     *
     * @param \stdClass $response
     *
     * @return Multiorders
     */
    protected function populate(stdClass $response)
    {
        $multiorders = clone $this;

        $multiorders->data->id = $response->id;
        $multiorders->data->ownId = $response->ownId;
        $multiorders->data->status = $response->status;
        $multiorders->data->amount = new stdClass();
        $multiorders->data->amount->total = $response->amount->total;
        $multiorders->data->amount->currency = $response->amount->currency;
        $multiorders->data->orders = [];

        foreach ($response->orders as $responseOrder) {
            $order = new Orders($multiorders->moip);
            $order->populate($responseOrder);

            $multiorders->data->orders[] = $order;
        }

        $multiorders->data->createdAt = $response->createdAt;
        $multiorders->data->updatedAt = $response->updatedAt;
        $multiorders->data->_links = $response->_links;

        return $multiorders;
    }

    /**
     * Set own request id. External reference.
     *
     * @param string $ownId
     *
     * @return $this
     */
    public function setOwnId($ownId)
    {
        $this->data->ownId = $ownId;

        return $this;
    }
}
