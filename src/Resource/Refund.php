<?php

namespace Moip\Resource;

use ArrayIterator;
use Requests;
use stdClass;

/**
 * Class Refund.
 */
class Refund extends MoipResource
{
    /**
     * @const string
     */
    const PATH = 'refunds';

    /**
     * Refunds means.
     *
     * @const string
     */
    const METHOD_CREDIT_CARD = 'CREDIT_CARD';

    /**
     * Refunds means.
     *
     * @const string
     */
    const METHOD_BANK_ACCOUNT = 'BANK_ACCOUNT';

    /**
     * Refunds means.
     *
     * @const string
     */
    const METHOD_MONEY_ORDER = 'MONEY_ORDER';

    /**
     * @var \Moip\Resource\Orders
     */
    private $order;

    /**
     * @var \Moip\Resource\Payment
     */
    private $payment;

    /**
     * Initializes new instances.
     */
    public function initialize()
    {
        $this->data = new stdClass();
    }

    /**
     * Mount refund structure.
     *
     * @param \stdClass $response
     *
     * @return $this
     */
    protected function populate(stdClass $response)
    {
        $refund = clone $this;

        $refund->data->id = $this->getIfSet('id', $response);

        if (isset($response->amount)) {
            $refund->data->amount = new stdClass();
            $refund->data->amount->total = $this->getIfSet('total', $response->amount);
            $refund->data->amount->discounted = $this->getIfSet('discounted', $response->amount);
            $refund->data->amount->currency = $this->getIfSet('currency', $response->amount);
        }

        $refund->data->fee = $this->getIfSet('fee', $response);
        $refund->data->createdAt = $this->getIfSet('createdAt', $response);

        if (isset($response->refundingInstrument)) {
            $refund->data->refundingInstrument = new stdClass();
            $refund->data->refundingInstrument->method = $this->getIfSet('method', $response->refundingInstrument);

            if (isset($response->refundingInstrument->bankAccount)) {
                $refund->data->refundingInstrument->bankAccount = new stdClass();
                $refund->data->refundingInstrument->bankAccount->bankNumber = $this->getIfSet('bankNumber', $response->refundingInstrument->bankAccount);
                $refund->data->refundingInstrument->bankAccount->bankName = $this->getIfSet('bankName', $response->refundingInstrument->bankAccount);
                $refund->data->refundingInstrument->bankAccount->agencyNumber = $this->getIfSet('agencyNumber', $response->refundingInstrument->bankAccount);
                $refund->data->refundingInstrument->bankAccount->agencyCheckNumber = $this->getIfSet('agencyCheckNumber', $response->refundingInstrument->bankAccount);
                $refund->data->refundingInstrument->bankAccount->accountNumber = $this->getIfSet('accountNumber', $response->refundingInstrument->bankAccount);
                $refund->data->refundingInstrument->bankAccount->accountCheckNumber = $this->getIfSet('accountCheckNumber', $response->refundingInstrument->bankAccount);
                $refund->data->refundingInstrument->bankAccount->agencyCheckNumber = $this->getIfSet('agencyCheckNumber', $response->refundingInstrument->bankAccount);
                $refund->data->refundingInstrument->bankAccount->type = $this->getIfSet('type', $response->refundingInstrument->bankAccount);
            }
        }

        $refund->data->status = $this->getIfSet('status', $response);
        $refund->data->method = $this->getIfSet('method', $response);
        $refund->data->createdAt = $this->getIfSet('createdAt', $response);
        $refund->data->_links = $this->getIfSet('_links', $response);

        return $refund;
    }

    /**
     * Get id MoIP refund.
     *
     *
     * @return string
     */
    public function getId()
    {
        return $this->getIfSet('id');
    }

    /**
     * Create a new refund in api MoIP.
     *
     * @param stdClass $data
     *
     * @return $this
     */
    private function execute(stdClass $data = null)
    {
        $body = empty($data) ? new stdClass() : $data;
        $response = $this->httpRequest($this->getPath(), Requests::POST, $body);

        return $this->populate($response);
    }

    /**
     * Checks path that will be the request.
     *
     * @return string
     */
    private function getPath()
    {
        if ($this->order !== null) {
            return sprintf('/%s/%s/%s/%s', MoipResource::VERSION, Orders::PATH, $this->order->getId(), self::PATH);
        }

        return sprintf('/%s/%s/%s/%s', MoipResource::VERSION, Payment::PATH, $this->payment->getId(), self::PATH);
    }

    /**
     * Bank account is the bank address of a particular vendor or a customer.
     *
     * @param string                  $type               Kind of bank account. possible values: CHECKING, SAVING.
     * @param string                  $bankNumber         Bank number. possible values: 001, 237, 341, 041.
     * @param int                     $agencyNumber       Branch number.
     * @param int                     $agencyCheckNumber  Checksum of the agency.
     * @param int                     $accountNumber      Account number.
     * @param int                     $accountCheckNumber Digit account checker.
     * @param \Moip\Resource\Customer $holder
     *
     * @return \stdClass
     */
    private function bankAccount($type, $bankNumber, $agencyNumber, $agencyCheckNumber, $accountNumber, $accountCheckNumber, Customer $holder)
    {
        $data = new stdClass();
        $data->refundingInstrument = new stdClass();
        $data->refundingInstrument->method = self::METHOD_BANK_ACCOUNT;
        $data->refundingInstrument->bankAccount = new stdClass();
        $data->refundingInstrument->bankAccount->type = $type;
        $data->refundingInstrument->bankAccount->bankNumber = $bankNumber;
        $data->refundingInstrument->bankAccount->agencyNumber = $agencyNumber;
        $data->refundingInstrument->bankAccount->agencyCheckNumber = $agencyCheckNumber;
        $data->refundingInstrument->bankAccount->accountNumber = $accountNumber;
        $data->refundingInstrument->bankAccount->accountCheckNumber = $accountCheckNumber;
        $data->refundingInstrument->bankAccount->holder = new stdClass();
        $data->refundingInstrument->bankAccount->holder->fullname = $holder->getFullname();
        $data->refundingInstrument->bankAccount->holder->taxDocument = new stdClass();
        $data->refundingInstrument->bankAccount->holder->taxDocument->type = $holder->getTaxDocumentType();
        $data->refundingInstrument->bankAccount->holder->taxDocument->number = $holder->getTaxDocumentNumber();

        return $data;
    }

    /**
     * Making a full refund to the bank account.
     *
     * @param string                  $type               Kind of bank account. possible values: CHECKING, SAVING.
     * @param string                  $bankNumber         Bank number. possible values: 001, 237, 341, 041.
     * @param int                     $agencyNumber       Branch number.
     * @param int                     $agencyCheckNumber  Checksum of the agency.
     * @param int                     $accountNumber      Account number.
     * @param int                     $accountCheckNumber Digit account checker.
     * @param \Moip\Resource\Customer $holder
     *
     * @return Refund
     */
    public function bankAccountFull($type, $bankNumber, $agencyNumber, $agencyCheckNumber, $accountNumber, $accountCheckNumber, Customer $holder)
    {
        $data = $this->bankAccount($type, $bankNumber, $agencyNumber, $agencyCheckNumber, $accountNumber, $accountCheckNumber, $holder);

        return $this->execute($data);
    }

    /**
     * Making a partial refund in the bank account.
     *
     * @param string                  $type               Kind of bank account. possible values: CHECKING, SAVING.
     * @param string                  $bankNumber         Bank number. possible values: 001, 237, 341, 041.
     * @param int                     $agencyNumber       Branch number.
     * @param int                     $agencyCheckNumber  Checksum of the agency.
     * @param int                     $accountNumber      Account number.
     * @param int                     $accountCheckNumber Digit account checker.
     * @param \Moip\Resource\Customer $holder
     *
     * @return Refund
     */
    public function bankAccountPartial($amount, $type, $bankNumber, $agencyNumber, $agencyCheckNumber, $accountNumber, $accountCheckNumber, Customer $holder)
    {
        $data = $this->bankAccount($type, $bankNumber, $agencyNumber, $agencyCheckNumber, $accountNumber, $accountCheckNumber, $holder);
        $data->amount = $amount;

        return $this->execute($data);
    }

    /**
     * Making a full refund in credit card.
     *
     * @return \Moip\Resource\Refund
     */
    public function creditCardFull()
    {
        return $this->execute();
    }

    /**
     * Making a partial refund in credit card.
     *
     * @param int|float $amount value of refund.
     *
     * @return \Moip\Resource\Refund
     */
    public function creditCardPartial($amount)
    {
        $data = new stdClass();
        $data->amount = $amount;

        return $this->execute($data);
    }

    /**
     * Get iterator.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        $refunds = [];
        $response = $this->httpRequest($this->getPath(), Requests::GET);
        foreach ($response->refunds as $refund) {
            $refunds[] = $this->populate($refund);
        }

        return new ArrayIterator($refunds);
    }

    /**
     * Set order.
     *
     * @param \Moip\Resource\Orders $order
     */
    public function setOrder(Orders $order)
    {
        $this->order = $order;
    }

    /**
     * Set payment.
     *
     * @param \Moip\Resource\Payment $payment
     */
    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;
    }
}
