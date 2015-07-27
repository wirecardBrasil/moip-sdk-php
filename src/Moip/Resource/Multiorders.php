<?php

namespace Moip\Resource;

use Moip\Http\HTTPRequest;
use stdClass;
use ArrayIterator;
use RuntimeException;

class Multiorders extends MoipResource
{
    /**
     * Initializes new instances.
     */
    public function initialize()
    {
        $this->data = new stdClass();
        $this->data->ownId = null;
        $this->data->orders = array();
    }
    /**
     * Structure of order.
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
     * @return \Moip\Resource\Multiorder
     */
    public function create()
    {
        $body = json_encode($this, JSON_UNESCAPED_SLASHES);

        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');
        $httpConnection->addHeader('Content-Length', strlen($body));
        $httpConnection->setRequestBody($body);

        $httpResponse = $httpConnection->execute('/v2/multiorders', HTTPRequest::POST);

        if ($httpResponse->getStatusCode() != 201) {
            throw new RuntimeException($httpResponse->getStatusMessage(), $httpResponse->getStatusCode());
        }

        return $this->populate(json_decode($httpResponse->getContent()));
    }

    /**
     * Get an multiorder in MoIP.
     * 
     * @param string $id Id MoIP order id
     *
     * @return \Moip\Resource\Multiorder
     */
    public function get($id)
    {
        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');

        $httpResponse = $httpConnection->execute('/v2/multiorders/'.$id, HTTPRequest::GET);

        if ($httpResponse->getStatusCode() != 200) {
            throw new RuntimeException($httpResponse->getStatusMessage(), $httpResponse->getStatusCode());
        }

        return $this->populate(json_decode($httpResponse->getContent()));
    }

    /**
     * Get MoIP order id.
     * 
     * @return strign
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
        return $this->getIfSet('createdAt');
    }

    /**
     * Get date of last update.
     * 
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->getIfSet('updatedAt');
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
     * @return \stdClass
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
        $multiorders->data->orders = array();

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
     * @param $this
     */
    public function setOwnId($ownId)
    {
        $this->data->ownId = $ownId;

        return $this;
    }
}
