<?php

namespace Moip\Resource;

use Moip\Resource\Entry;
use Moip\Resource\Event;
use Moip\Resource\Refund;
use Moip\Http\HTTPRequest;
use Moip\Resource\Payment;
use Moip\Resource\Customer;
use Moip\Resource\MoipResource;

use stdClass;
use ArrayIterator;
use RuntimeException;

class Orders extends MoipResource
{
    /**
     * Adds a new item to order.
     * 
     * @param string  $product  Name of the product.
     * @param int     $quantity Product Quantity.
     * @param string  $detail   Additional product description.
     * @param intefer $price    Initial value of the item.
     *
     * @return $this
     */
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

    /**
     *  Adds a new receiver to order.
     * 
     * @param string $moipAccount Id MoIP MoIP account that will receive payment values.
     * @param string $type        Define qual o tipo de recebedor do pagamento, valores possíveis: PRIMARY, SECONDARY.
     *
     * @return $this
     */
    public function addReceiver($moipAccount, $type = 'PRIMARY')
    {
        $receiver = new stdClass();
        $receiver->moipAccount = new stdClass();
        $receiver->moipAccount->id = $moipAccount;
        $receiver->type = $type;

        $this->data->receivers[] = $receiver;

        return $this;
    }

    /**
     * Initialize necessary used in some functions.
     */
    protected function initialize()
    {
        $this->data = new stdClass();
        $this->data->ownId = null;
        $this->data->amount = new stdClass();
        $this->data->amount->currency = 'BRL';
        $this->data->items = array();
        $this->data->receivers = array();
    }

    /**
     * Initialize necessary used in some functions.
     */
    private function initializeSubtotals()
    {
        if (!isset($this->data->subtotals)) {
            $this->data->subtotals = new stdClass();
        }
    }

    /**
     * Mount the structure of order.
     * 
     * @param \stdClass $response
     *
     * @return \stdClass Response order.
     */
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

    /**
     * Create a new order in MoIP.
     * 
     * @return \Moip\Resource\Order
     */
    public function create()
    {
        $body = json_encode($this, JSON_UNESCAPED_SLASHES);

        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');
        $httpConnection->addHeader('Content-Length', strlen($body));
        $httpConnection->setRequestBody($body);

        $httpResponse = $httpConnection->execute('/v2/orders', HTTPRequest::POST);

        if ($httpResponse->getStatusCode() != 201) {
            throw new RuntimeException($httpResponse->getStatusMessage(), $httpResponse->getStatusCode());
        }

        return $this->populate(json_decode($httpResponse->getContent()));
    }

    /**
     * Get an order in MoIP.
     * 
     * @param string $id Id MoIP order id
     *
     * @return \Moip\Resource\Order
     */
    public function get($id)
    {
        $body = '{}';

        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');
        $httpConnection->addHeader('Content-Length', strlen($body));
        $httpConnection->setRequestBody($body);

        $httpResponse = $httpConnection->execute('/v2/orders/'.$id, HTTPRequest::GET);

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
    public function getAmountFees()
    {
        return $this->getIfSet('feed', $this->data->amount);
    }

    /**
     * Get total amount of refunds.
     * 
     * @return int|float
     */
    public function getAmountRefunds()
    {
        return $this->getIfSet('refunds', $this->data->amount);
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
     * Get sum of amounts received by other recipients. Used in Marketplaces.
     * 
     * @return int|float
     */
    public function getAmountOtherReceivers()
    {
        return $this->getIfSet('otherReceivers', $this->data->amount);
    }

    /**
     * Get currency used in the application. Possible values: BRL.
     * 
     * @return string
     */
    public function getCurrenty()
    {
        return $this->getIfSet('currency', $this->data->amount);
    }

    /**
     * Get greight value of the item will be added to the value of the items.
     * 
     * @return int|float
     */
    public function getSubtotalShipping()
    {
        $this->initializeSubtotals();

        return $this->getIfSet('shipping', $this->data->amount->subtotals);
    }

    /**
     * Get Additional value to the item will be added to the value of the items.
     * 
     * @return int|float
     */
    public function getSubtotalAddition()
    {
        $this->initializeSubtotals();

        return $this->getIfSet('addition', $this->data->amount->subtotals);
    }

    /**
     * Get discounted value of the item will be subtracted from the total value of the items.
     * 
     * @return int|float
     */
    public function getSubtotalDiscount()
    {
        $this->initializeSubtotals();

        return $this->getIfSet('discount', $this->data->amount->subtotals);
    }

    /**
     * Get summing the values of all items.
     * 
     * @return int|float
     */
    public function getSubtotalItems()
    {
        $this->initializeSubtotals();

        return $this->getIfSet('items', $this->data->amount->subtotals);
    }

    /**
     * Ger structure item information request.
     * 
     * @return \ArrayIterator
     */
    public function getItemIterator()
    {
        return new ArrayIterator($this->data->items);
    }

    /**
     * Get Customer associated with the request.
     * 
     * @return \Moip\Resource\Customer
     */
    public function getCustomer()
    {
        return $this->data->customer;
    }

    /**
     * Get payments associated with the request.
     * 
     * @return \Moip\Resource\Payment
     */
    public function getPaymentIterator()
    {
        return new ArrayIterator($this->data->payments);
    }

    /**
     * Get recipient structure of payments.
     * 
     * @return \Moip\Resource\
     */
    public function getReceiverIterator()
    {
        return new ArrayIterator($this->data->receivers);
    }

    /**
     * Get releases associated with the request.
     * 
     * @return \stdClass
     */
    public function getEventIterator()
    {
        return new ArrayIterator($this->data->events);
    }

    /**
     * Get repayments associated with the request.
     * 
     * @return \Moip\Resource\Refund
     */
    public function getRefundIterator()
    {
        return new ArrayIterator($this->data->refunds);
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
     * Get date of resource creation.
     * 
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->getIfSet('createdAt');
    }

    /**
     * Get updated resource.
     * 
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->getIfSet('updatedAt');
    }

    /**
     * Get hypermedia link structure (HATEOAS) resource Orders.
     * 
     * @return \stdClass
     */
    public function getLinks()
    {
        return $this->getIfSet('_links');
    }

    /**
     * Structure of payment.
     * 
     * @return \Moip\Resource\Payment
     */
    public function payments()
    {
        $payment = new Payment($this->moip);
        $payment->setOrder($this);

        return $payment;
    }

    /**
     * Structure of refund.
     * 
     * @return \Moip\Resource\Refund
     */
    public function refunds()
    {
        $refund = new Refund($this->moip);
        $refund->setOrder($this);

        return $refund;
    }

    /**
     * Set additional value to the item will be added to the value of the items.
     * 
     * @param int|float $value additional value to the item.
     */
    public function setAddition($value)
    {
        $this->data->subtotals->addition = (float) $value;

        return $this;
    }

    /**
     * Set customer associated with the order.
     * 
     * @param \Moip\Resource\Customer $customer customer associated with the request.
     */
    public function setCustomer(Customer $customer)
    {
        $this->data->customer = $customer;

        return $this;
    }

    /**
     * Set discounted value of the item will be subtracted from the total value of the items.
     * 
     * @param int|float $value discounted value.
     */
    public function setDiscont($value)
    {
        $this->data->subtotals->discont = (float) $value;

        return $this;
    }

    /**
     * Set own request id. external reference.
     * 
     * @param string $ownId external reference.
     */
    public function setOwnId($ownId)
    {
        $this->data->ownId = $ownId;

        return $this;
    }

    /**
     * Set shipping Amount.
     * 
     * @param float $value shipping Amount.
     *
     * @return $this
     */
    public function setShippingAmount($value)
    {
        if (!isset($this->data->amount->subtotals)) {
            $this->data->amount->subtotals = new stdClass();
        }

        $this->data->amount->subtotals->shipping = (float) $value;

        return $this;
    }
}
