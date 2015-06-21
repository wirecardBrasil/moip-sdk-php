<?php

namespace Moip\Resource;

use stdClass;
use Moip\Http\HTTPRequest;

class Orders extends MoipResource
{
    public function addItem($product, $quantity, $detail, $price)
    {
        $item = new stdClass();
        $item->product = $product;
        $item->quantity = $quantity;
        $item->detail = $detail;
        $item->price = $price;

        $this->data->items[] = $item;

        return $this;
    }

    public function addReceiver($moipAccount, $type = 'PRIMARY')
    {
        $receiver = new stdClass();
        $receiver->moipAccount = new stdClass();
        $receiver->moipAccount->id = $moipAccount;
        $receiver->type = $type;

        $this->data->receivers[] = $receiver;

        return $this;
    }

    protected function initialize()
    {
        $this->data = new stdClass();
        $this->data->ownId = null;
        $this->data->amount = new stdClass();
        $this->data->amount->currency = 'BRL';
        $this->data->items = array();
        $this->data->receivers = array();
    }

    private function initializeSubtotals()
    {
        if (!isset($this->data->subtotals)) {
            $this->data->subtotals = new stdClass();
        }
    }

    protected function populate(stdClass $response)
    {
        $orders = clone $this;
        $orders->data->id = $response->id;
        $orders->data->amount->total = $response->amount->total;
        $orders->data->amount->fees = $response->amount->fees;
        $orders->data->amount->refunds = $response->amount->refunds;
        $orders->data->amount->liquid = $response->amount->liquid;
        $orders->data->amount->otherReceivers = $response->amount->otherReceivers;
        $orders->data->amount->subtotals = $response->amount->subtotals;
        $orders->data->customer = new Customer($orders->moip);
        $orders->data->customer->populate($response->customer);

        if (isset($response->payments)) {
            $orders->data->payments = array();

            foreach ($response->payments as $responsePayment) {
                $payment = new Payment($orders->moip);
                $payment->populate($responsePayment);
                $payment->setOrder($this);

                $orders->data->payments[] = $payment;
            }
        }

        if (isset($response->refunds)) {
            $orders->data->refunds = array();

            foreach ($response->refunds as $responseRefund) {
                $refund = new Refund($orders->moip);
                $refund->populate($responseRefund);

                $orders->data->refunds[] = $refund;
            }
        }

        if (isset($response->entries)) {
            $orders->data->entries = array();

            foreach ($response->entries as $responseEntry) {
                $entry = new Entry($orders->moip);
                $entry->populate($responseEntry);

                $orders->data->entries[] = $entry;
            }
        }

        if (isset($response->events)) {
            $orders->data->events = array();

            foreach ($response->events as $responseEvent) {
                $event = new Event($orders->moip);
                $event->populate($responseEvent);

                $orders->data->events[] = $event;
            }
        }

        $orders->data->items = $response->items;
        $orders->data->receivers = $response->receivers;
        $orders->data->createdAt = $response->createdAt;
        $orders->data->_links = $response->_links;

        return $orders;
    }

    public function create()
    {
        $body = json_encode($this);

        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');
        $httpConnection->addHeader('Content-Length', strlen($body));
        $httpConnection->setRequestBody($body);

        $httpResponse = $httpConnection->execute('/v2/orders', HTTPRequest::POST);

        if ($httpResponse->getStatusCode() != 201) {
            throw new \RuntimeException($httpResponse->getStatusMessage(), $httpResponse->getStatusCode());
        }

        return $this->populate(json_decode($httpResponse->getContent()));
    }

    public function get($id)
    {
        $body = '{}';

        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');
        $httpConnection->addHeader('Content-Length', strlen($body));
        $httpConnection->setRequestBody($body);

        $httpResponse = $httpConnection->execute('/v2/orders/'.$id, HTTPRequest::GET);

        if ($httpResponse->getStatusCode() != 200) {
            throw new \RuntimeException($httpResponse->getStatusMessage(), $httpResponse->getStatusCode());
        }

        return $this->populate(json_decode($httpResponse->getContent()));
    }

    public function getId()
    {
        return $this->getIfSet('id');
    }

    public function getOwnId()
    {
        return $this->getIfSet('ownId');
    }

    public function getAmountTotal()
    {
        return $this->getIfSet('total', $this->data->amount);
    }

    public function getAmountFees()
    {
        return $this->getIfSet('feed', $this->data->amount);
    }

    public function getAmountRefunds()
    {
        return $this->getIfSet('refunds', $this->data->amount);
    }

    public function getAmountLiquid()
    {
        return $this->getIfSet('liquid', $this->data->amount);
    }

    public function getAmountOtherReceivers()
    {
        return $this->getIfSet('otherReceivers', $this->data->amount);
    }

    public function getCurrenty()
    {
        return $this->getIfSet('currency', $this->data->amount);
    }

    public function getSubtotalShipping()
    {
        $this->initializeSubtotals();

        return $this->getIfSet('shipping', $this->data->amount->subtotals);
    }

    public function getSubtotalAddition()
    {
        $this->initializeSubtotals();

        return $this->getIfSet('addition', $this->data->amount->subtotals);
    }

    public function getSubtotalDiscount()
    {
        $this->initializeSubtotals();

        return $this->getIfSet('discount', $this->data->amount->subtotals);
    }

    public function getSubtotalItems()
    {
        $this->initializeSubtotals();

        return $this->getIfSet('items', $this->data->amount->subtotals);
    }

    public function getItemIterator()
    {
        return new \ArrayIterator($this->data->items);
    }

    public function getCustomer()
    {
        return $this->data->customer;
    }

    public function getPaymentIterator()
    {
        return new \ArrayIterator($this->data->payments);
    }

    public function getReceiverIterator()
    {
        return new \ArrayIterator($this->data->receivers);
    }

    public function getEventIterator()
    {
        return new \ArrayIterator($this->data->events);
    }

    public function getRefundIterator()
    {
        return new \ArrayIterator($this->data->refunds);
    }

    public function getStatus()
    {
        return $this->getIfSet('status');
    }

    public function getCreatedAt()
    {
        return $this->getIfSet('createdAt');
    }

    public function getUpdatedAt()
    {
        return $this->getIfSet('updatedAt');
    }

    public function getLinks()
    {
        return $this->getIfSet('_links');
    }

    public function payments()
    {
        $payment = new Payment($this->moip);
        $payment->setOrder($this);

        return $payment;
    }

    public function refunds()
    {
        $refund = new Refund($this->moip);
        $refund->setOrder($this);

        return $refund;
    }

    public function setAddition($value)
    {
        $this->data->subtotals->addition = (float) $value;

        return $this;
    }

    public function setCustomer(Customer $customer)
    {
        $this->data->customer = $customer;

        return $this;
    }

    public function setDiscont($value)
    {
        $this->data->subtotals->discont = (float) $value;

        return $this;
    }

    public function setOwnId($ownId)
    {
        $this->data->ownId = $ownId;

        return $this;
    }

    public function setShippingAmount($value)
    {
        if (!isset($this->data->amount->subtotals)) {
            $this->data->amount->subtotals = new stdClass();
        }

        $this->data->amount->subtotals->shipping = (float) $value;

        return $this;
    }
}
