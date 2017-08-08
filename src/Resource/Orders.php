<?php

namespace Moip\Resource;

use ArrayIterator;
use Moip\Helper\Filters;
use Moip\Helper\Pagination;
use stdClass;

/**
 * Class Orders.
 */
class Orders extends MoipResource
{
    /**
     * @const string
     */
    const PATH = 'orders';

    /**
     * Defines what kind of payee as pripmary.
     *
     * @const string
     */
    const RECEIVER_TYPE_PRIMARY = 'PRIMARY';

    /**
     * Defines what kind of payee as secundary.
     *
     * @const string
     */
    const RECEIVER_TYPE_SECONDARY = 'SECONDARY';

    /**
     * Currency used in the application.
     *
     * @const string
     */
    const AMOUNT_CURRENCY = 'BRL';

    /**
     * @var \Moip\Resource\Orders
     **/
    private $orders;

    /**
     * Adds a new item to order.
     *
     * @param string $product  Name of the product.
     * @param int    $quantity Product Quantity.
     * @param string $detail   Additional product description.
     * @param int    $price    Initial value of the item.
     *
     * @return $this
     */
    public function addItem($product, $quantity, $detail, $price)
    {
        if (!is_int($price)) {
            throw new \UnexpectedValueException('Informe o valor do item como inteiro');
        }

        if (!is_int($quantity) || $quantity < 1) {
            throw new \UnexpectedValueException('A quantidade do item deve ser um valor inteiro maior que 0');
        }

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
     * @param int    $fixed       Value that the receiver will receive.
     * @param int    $percentual  Percentual value that the receiver will receive. Possible values: 0 - 100
     * @param bool   $feePayor    Flag to know if receiver is the payer of Moip tax.
     *
     * @return $this
     */
    public function addReceiver($moipAccount, $type, $fixed = null, $percentual = null, $feePayor = false)
    {
        $receiver = new stdClass();
        $receiver->moipAccount = new stdClass();
        $receiver->moipAccount->id = $moipAccount;
        if (!empty($fixed)) {
            $receiver->amount = new stdClass();
            $receiver->amount->fixed = $fixed;
        }
        if (!empty($percentual)) {
            $receiver->amount = new stdClass();
            $receiver->amount->percentual = $percentual;
        }
        $receiver->feePayor = $feePayor;
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
        $this->data->amount->currency = self::AMOUNT_CURRENCY;
        $this->data->amount->subtotals = new stdClass();
        $this->data->items = [];
        $this->data->receivers = [];
        $this->data->checkoutPreferences = new stdClass();
        $this->data->checkoutPreferences->redirectUrls = new stdClass();
        $this->data->checkoutPreferences->installments = [];
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
     * @return Orders Response order.
     */
    protected function populate(stdClass $response)
    {
        $this->orders = clone $this;
        $this->orders->data->id = $response->id;
        $this->orders->data->ownId = $response->ownId;
        $this->orders->data->amount->total = $response->amount->total;
        $this->orders->data->amount->fees = $response->amount->fees;
        $this->orders->data->amount->refunds = $response->amount->refunds;
        $this->orders->data->amount->liquid = $response->amount->liquid;
        $this->orders->data->amount->otherReceivers = $response->amount->otherReceivers;
        $this->orders->data->amount->subtotals = $response->amount->subtotals;

        $customer = new Customer($this->moip);
        $this->orders->data->customer = $customer->populate($response->customer);

        $this->orders->data->payments = $this->structure($response, Payment::PATH, Payment::class);
        $this->orders->data->refunds = $this->structure($response, Refund::PATH, Refund::class);
        $this->orders->data->entries = $this->structure($response, Entry::PATH, Entry::class);
        $this->orders->data->events = $this->structure($response, Event::PATH, Event::class);

        $this->orders->data->items = $response->items;
        $this->orders->data->receivers = $response->receivers;
        $this->orders->data->createdAt = $response->createdAt;
        $this->orders->data->status = $response->status;
        $this->orders->data->_links = $response->_links;

        return $this->orders;
    }

    /**
     * Structure resource.
     *
     * @param stdClass                                                                               $response
     * @param string                                                                                 $resource
     * @param \Moip\Resource\Payment|\Moip\Resource\Refund|\Moip\Resource\Entry|\Moip\Resource\Event $class
     *
     * @return array
     */
    private function structure(stdClass $response, $resource, $class)
    {
        $structures = [];

        foreach ($response->$resource as $responseResource) {
            $structure = new $class($this->orders->moip);
            $structure->populate($responseResource);

            $structures[] = $structure;
        }

        return $structures;
    }

    /**
     * Create a new order in MoIP.
     *
     * @return \Moip\Resource\Orders|stdClass
     */
    public function create()
    {
        return $this->createResource(sprintf('/%s/%s', MoipResource::VERSION, self::PATH));
    }

    /**
     * Get an order in MoIP.
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
        return $this->getIfSet('fees', $this->data->amount);
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
     * @return ArrayIterator
     */
    public function getPaymentIterator()
    {
        return new ArrayIterator($this->data->payments);
    }

    /**
     * Get recipient structure of payments.
     *
     * @return ArrayIterator
     */
    public function getReceiverIterator()
    {
        return new ArrayIterator($this->data->receivers);
    }

    /**
     * Get releases associated with the request.
     *
     * @return ArrayIterator
     */
    public function getEventIterator()
    {
        return new ArrayIterator($this->data->events);
    }

    /**
     * Get repayments associated with the request.
     *
     * @return ArrayIterator
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
        return $this->getIfSetDateTime('createdAt');
    }

    /**
     * Get updated resource.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->getIfSetDateTime('updatedAt');
    }

    /**
     * Get checkout preferences of the order.
     *
     * @return string
     */
    public function getCheckoutPreferences()
    {
        return $this->getIfSet('checkoutPreferences');
    }

    /**
     * Create a new Orders list instance.
     *
     * @return \Moip\Resource\OrdersList
     */
    public function getList(Pagination $pagination = null, Filters $filters = null, $qParam = '')
    {
        $orderList = new OrdersList($this->moip);

        return $orderList->get($pagination, $filters, $qParam);
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
     *
     * @return $this
     */
    public function setAddition($value)
    {
        if (!isset($this->data->amount->subtotals)) {
            $this->data->amount->subtotals = new stdClass();
        }
        $this->data->amount->subtotals->addition = (float) $value;

        return $this;
    }

    /**
     * Set customer associated with the order.
     *
     * @param \Moip\Resource\Customer $customer customer associated with the request.
     *
     * @return $this
     */
    public function setCustomer(Customer $customer)
    {
        $this->data->customer = $customer;

        return $this;
    }

    /**
     * Set customer id associated with the order.
     *
     * @param string $id Customer's id.
     *
     * @return $this
     */
    public function setCustomerId($id)
    {
        if (!isset($this->data->customer)) {
            $this->data->customer = new stdClass();
        }
        $this->data->customer->id = $id;

        return $this;
    }

    /**
     * Set discounted value of the item will be subtracted from the total value of the items.
     *
     * @param int|float $value discounted value.
     *
     * @return $this
     */
    public function setDiscount($value)
    {
        $this->data->amount->subtotals->discount = (float) $value;

        return $this;
    }

    /**
     * Set discounted value of the item will be subtracted from the total value of the items.
     *
     * @deprecated
     *
     * @param int|float $value discounted value.
     *
     * @return $this
     */
    public function setDiscont($value)
    {
        $this->setDiscount($value);

        return $this;
    }

    /**
     * Set own request id. external reference.
     *
     * @param string $ownId external reference.
     *
     * @return $this
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
        $this->data->amount->subtotals->shipping = (float) $value;

        return $this;
    }

    /**
     * Set URL for redirection in case of success.
     *
     * @param string $urlSuccess UrlSuccess.
     *
     * @return $this
     */
    public function setUrlSuccess($urlSuccess = '')
    {
        $this->data->checkoutPreferences->redirectUrls->urlSuccess = $urlSuccess;

        return $this;
    }

    /**
     * Set URL for redirection in case of failure.
     *
     * @param string $urlFailure UrlFailure.
     *
     * @return $this
     */
    public function setUrlFailure($urlFailure = '')
    {
        $this->data->checkoutPreferences->redirectUrls->urlFailure = $urlFailure;

        return $this;
    }

    /**
     * Set installment settings for checkout preferences.
     *
     * @param array $quantity
     * @param int   $discountValue
     * @param int   $additionalValue
     *
     * @return $this
     */
    public function addInstallmentCheckoutPreferences($quantity, $discountValue = 0, $additionalValue = 0)
    {
        $installmentPreferences = new stdClass();
        $installmentPreferences->quantity = $quantity;
        $installmentPreferences->discount = $discountValue;
        $installmentPreferences->addition = $additionalValue;

        $this->data->checkoutPreferences->installments[] = $installmentPreferences;

        return $this;
    }
}
