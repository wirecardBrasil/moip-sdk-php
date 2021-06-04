<?php

namespace Moip\Resource;

use Requests;
use stdClass;

/**
 * Class Payment.
 */
class Payment extends MoipResource
{
    /**
     * @const string
     */
    const PATH = 'payments';

    /**
     * @const string
     */
    const MULTI_PAYMENTS_PATH = 'multipayments';

    /**
     * @const string
     */
    const SIMULATOR_PATH = 'simulador';

    /**
     * Payment means.
     *
     * @const string
     */
    const METHOD_CREDIT_CARD = 'CREDIT_CARD';

    /**
     * Payment means.
     *
     * @const string
     */
    const METHOD_BOLETO = 'BOLETO';

    /**
     * Payment means.
     *
     * @const string
     */
    const METHOD_ONLINE_DEBIT = 'ONLINE_DEBIT';

    /**
     * Payment means.
     *
     * @const string
     */
    const METHOD_WALLET = 'WALLET';

    /**
     * Payment means.
     *
     * @const string
     */
    const METHOD_ONLINE_BANK_DEBIT = 'ONLINE_BANK_DEBIT';

    /**
     * @var \Moip\Resource\Orders
     */
    private $order;

    /**
     * Just created, but not initialized yet.
     */
    const STATUS_CREATED = 'CREATED';

    /**
     * Waiting for the payment.
     */
    const STATUS_WAITING = 'WAITING';

    /**
     * On risk analysis, it may be automatic or manual.
     */
    const STATUS_IN_ANALYSIS = 'IN_ANALYSIS';

    /**
     * The amount was reserved on client credit card, it may be caught or discarded until 5 days.
     */
    const STATUS_PRE_AUTHORIZED = 'PRE_AUTHORIZED';

    /**
     * Payment confirmed by the bank institution.
     */
    const STATUS_AUTHORIZED = 'AUTHORIZED';

    /**
     * Payment cancelled.
     */
    const STATUS_CANCELLED = 'CANCELLED';

    /**
     * Payment refunded.
     */
    const STATUS_REFUNDED = 'REFUNDED';

    /**
     * Paymend reversed (it means that the payment may was not recognized by the client).
     */
    const STATUS_REVERSED = 'REVERSED';

    /**
     * Payment finalized, the amout is on your account.
     */
    const STATUS_SETTLED = 'SETTLED';

    /**
     * @var \Moip\Resource\Multiorders
     */
    private $multiorder;

    /**
     * Initializes new instances.
     */
    protected function initialize()
    {
        $this->data = new stdClass();
        $this->data->installmentCount = 1;
        $this->data->fundingInstrument = new stdClass();
    }

    /**
     * Create a new payment in api MoIP.
     *
     * @return $this
     */
    public function execute()
    {
        if ($this->order !== null) {
            $path = sprintf('/%s/%s/%s/%s', MoipResource::VERSION, Orders::PATH, $this->order->getId(), self::PATH);
        } else {
            $path = sprintf('/%s/%s/%s/%s', MoipResource::VERSION, Multiorders::PATH, $this->multiorder->getId(), self::MULTI_PAYMENTS_PATH);
        }

        $response = $this->httpRequest($path, Requests::POST, $this);

        return $this->populate($response);
    }

    /**
     * Get an payment and multipayment in MoIP.
     *
     * @param string $id_moip Id MoIP payment
     *
     * @return stdClass
     */
    public function get($id_moip)
    {
        if ($this->isMultipayment($id_moip)) {
            return $this->getByPath(sprintf('/%s/%s/%s', MoipResource::VERSION, self::MULTI_PAYMENTS_PATH, $id_moip));
        }

        return $this->getByPath(sprintf('/%s/%s/%s', MoipResource::VERSION, self::PATH, $id_moip));
    }

    /**
     * Get id MoIP payment.
     *
     *
     * @return \Moip\Resource\Payment
     */
    public function getId()
    {
        return $this->getIfSet('id');
    }

    /**
     * Mount payment structure.
     *
     * @param \stdClass $response
     *
     * @return Payment
     */
    protected function populate(stdClass $response)
    {
        $payment = clone $this;

        $payment->data->id = $this->getIfSet('id', $response);
        $payment->data->status = $this->getIfSet('status', $response);
        $payment->data->delayCapture = $this->getIfSet('delayCapture', $response);
        $payment->data->amount = new stdClass();
        $payment->data->amount->total = $this->getIfSet('total', $response->amount);
        $payment->data->amount->currency = $this->getIfSet('currency', $response->amount);
        $payment->data->installmentCount = $this->getIfSet('installmentCount', $response);
        $payment->data->fundingInstrument = $this->getIfSet('fundingInstrument', $response);
        $payment->data->payments = $this->getIfSet('payments', $response);
        $payment->data->escrows = $this->getIfSet('escrows', $response);
        $payment->data->fees = $this->getIfSet('fees', $response);
        $payment->data->refunds = $this->getIfSet('refunds', $response);
        $payment->data->_links = $this->getIfSet('_links', $response);
        $payment->data->createdAt = $this->getIfSetDateTime('createdAt', $response);
        $payment->data->updatedAt = $this->getIfSetDateTime('updatedAt', $response);

        return $payment;
    }

    /**
     * Refunds.
     *
     * @return Refund
     */
    public function refunds()
    {
        $refund = new Refund($this->moip);
        $refund->setPayment($this);

        return $refund;
    }

    /**
     * Escrows.
     *
     * @return Escrow
     */
    public function escrows()
    {
        $escrow = new Escrow($this->moip);
        $escrow->setId($this->getEscrow()->id);

        return $escrow;
    }

    /**
     * Get payment status.
     *
     * @return string Payment status. Possible values CREATED, WAITING, IN_ANALYSIS, PRE_AUTHORIZED, AUTHORIZED, CANCELLED, REFUNDED, REVERSED, SETTLED
     */
    public function getStatus()
    {
        return $this->getIfSet('status');
    }

    /**
     * get creation time.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->data->createdAt;
    }

    /**
     * Returns when the last update occurred.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->data->updatedAt;
    }

    /**
     * Returns the funding instrument.
     *
     * @return stdClass
     */
    public function getFundingInstrument()
    {
        //todo: return a funding instrument object
        return $this->data->fundingInstrument;
    }

    /**
     * Get href to Boleto
     * *.
     *
     * @return stdClass
     */
    public function getHrefBoleto()
    {
        return $this->getIfSet('_links')->payBoleto->redirectHref;
    }

    /**
     * Get LineCode to Boleto
     * *.
     *
     * @return stdClass
     */
    public function getLineCodeBoleto()
    {
        return $this->getIfSet('fundingInstrument')->boleto->lineCode;
    }

    /**
     * Get href from print to Boleto
     * *.
     *
     * @return stdClass
     */
    public function getHrefPrintBoleto()
    {
        return $this->getIfSet('_links')->payBoleto->printHref;
    }

    /**
     * Get Expirate Date to Boleto
     * *.
     *
     * @return stdClass
     */
    public function getExpirationDateBoleto()
    {
        return $this->getIfSet('fundingInstrument')->boleto->expirationDate;
    }

    /**
     * Returns payment amount.
     *
     * @return stdClass
     */
    public function getAmount()
    {
        return $this->data->amount;
    }

    /**
     * Returns escrow.
     *
     * @return stdClass
     */
    public function getEscrow()
    {
        return reset($this->data->escrows);
    }

    /**
     * Returns order.
     *
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Returns installment count.
     *
     * @return stdClass
     */
    public function getInstallmentCount()
    {
        return $this->data->installmentCount;
    }

    /**
     * Get payments.
     *
     * @return array
     */
    public function getPayments()
    {
        return $this->getIfSet('payments');
    }

    /**
     * Set means of payment.
     *
     * @param \stdClass $fundingInstrument
     *
     * @return $this
     */
    public function setFundingInstrument(stdClass $fundingInstrument)
    {
        $this->data->fundingInstrument = $fundingInstrument;

        return $this;
    }

    /**
     * Set billet.
     *
     * @param \DateTime|string $expirationDate   Expiration date of a billet.
     * @param string           $logoUri          Logo of billet.
     * @param array            $instructionLines Instructions billet.
     *
     * @return $this
     */
    public function setBoleto($expirationDate, $logoUri, array $instructionLines = [])
    {
        $keys = ['first', 'second', 'third'];

        if (empty($instructionLines)) {
            //Avoid warning in array_combine
            $instructionLines = ['', '', ''];
        }

        if ($expirationDate instanceof \DateTime) {
            $expirationDate = $expirationDate->format('Y-m-d');
        }

        $this->data->fundingInstrument->method = self::METHOD_BOLETO;
        $this->data->fundingInstrument->boleto = new stdClass();
        $this->data->fundingInstrument->boleto->expirationDate = $expirationDate;
        $this->data->fundingInstrument->boleto->instructionLines = array_combine($keys, $instructionLines);
        $this->data->fundingInstrument->boleto->logoUri = $logoUri;

        return $this;
    }

    /**
     * Set credit card holder.
     *
     * @param \Moip\Resource\Customer $holder
     */
    private function setCreditCardHolder(Holder $holder)
    {
        $birthdate = $holder->getBirthDate();
        if ($birthdate instanceof \DateTime) {
            $birthdate = $birthdate->format('Y-m-d');
        }
        $this->data->fundingInstrument->creditCard->holder = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->fullname = $holder->getFullname();
        $this->data->fundingInstrument->creditCard->holder->birthdate = $birthdate;
        $this->data->fundingInstrument->creditCard->holder->taxDocument = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->taxDocument->type = $holder->getTaxDocumentType();
        $this->data->fundingInstrument->creditCard->holder->taxDocument->number = $holder->getTaxDocumentNumber();
        $this->data->fundingInstrument->creditCard->holder->phone = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->phone->countryCode = $holder->getPhoneCountryCode();
        $this->data->fundingInstrument->creditCard->holder->phone->areaCode = $holder->getPhoneAreaCode();
        $this->data->fundingInstrument->creditCard->holder->phone->number = $holder->getPhoneNumber();
        $this->data->fundingInstrument->creditCard->holder->billingAddress = $holder->getBillingAddress();
    }

    /**
     * Set credit cardHash.
     *
     * @param string                  $hash   Credit card hash encripted using Moip.js
     * @param \Moip\Resource\Customer $holder
     * @param bool                    $store  Flag to know if credit card should be saved.
     *
     * @return $this
     */
    public function setCreditCardHash($hash, Holder $holder, $store = true)
    {
        $this->data->fundingInstrument->method = self::METHOD_CREDIT_CARD;
        $this->data->fundingInstrument->creditCard = new stdClass();
        $this->data->fundingInstrument->creditCard->hash = $hash;
        $this->data->fundingInstrument->creditCard->store = $store;
        $this->setCreditCardHolder($holder);

        return $this;
    }

    /**
     * Set credit card
     * Credit card used in a payment.
     * The card when returned within a parent resource is presented in its minimum representation.
     *
     * @param int                     $expirationMonth Card expiration month
     * @param int                     $expirationYear  Year of card expiration.
     * @param string                  $number          Card number.
     * @param int                     $cvc             Card Security Code.
     * @param \Moip\Resource\Customer $holder
     * @param bool                    $store           Flag to know if credit card should be saved.
     *
     * @return $this
     */
    public function setCreditCard($expirationMonth, $expirationYear, $number, $cvc, Holder $holder, $store = true)
    {
        $this->data->fundingInstrument->method = self::METHOD_CREDIT_CARD;
        $this->data->fundingInstrument->creditCard = new stdClass();
        $this->data->fundingInstrument->creditCard->expirationMonth = $expirationMonth;
        $this->data->fundingInstrument->creditCard->expirationYear = $expirationYear;
        $this->data->fundingInstrument->creditCard->number = $number;
        $this->data->fundingInstrument->creditCard->cvc = $cvc;
        $this->data->fundingInstrument->creditCard->store = $store;
        $this->setCreditCardHolder($holder);

        return $this;
    }

    /**
     * Sets data from a previously saved credit card
     * Credit card used in a payment.
     * Used when the credit card was saved with the customer and the payment made in a future date.
     *
     * @param string $creditCardId MoIP's Credit Card Id.
     * @param int    $cvc          Card Security Code.
     *
     * @return $this
     */
    public function setCreditCardSaved($creditCardId, $cvc)
    {
        $this->data->fundingInstrument = new stdClass();
        $this->data->fundingInstrument->method = self::METHOD_CREDIT_CARD;
        $this->data->fundingInstrument->creditCard = new stdClass();
        $this->data->fundingInstrument->creditCard->id = $creditCardId;
        $this->data->fundingInstrument->creditCard->cvc = $cvc;

        return $this;
    }

    /**
     * Set installment count.
     *
     * @param int $installmentCount
     *
     * @return $this
     */
    public function setInstallmentCount($installmentCount)
    {
        $this->data->installmentCount = $installmentCount;

        return $this;
    }

    /**
     * Set statement descriptor.
     *
     * @param string $statementDescriptor
     *
     * @return $this
     */
    public function setStatementDescriptor($statementDescriptor)
    {
        $this->data->statementDescriptor = $statementDescriptor;

        return $this;
    }

    /**
     * Set payment means made available by banks.
     *
     * @param string           $bankNumber     Bank number. Possible values: 001, 237, 341, 041.
     * @param \DateTime|string $expirationDate Date of expiration debit.
     * @param string           $returnUri      Return Uri.
     *
     * @return $this
     */
    public function setOnlineBankDebit($bankNumber, $expirationDate, $returnUri)
    {
        if ($expirationDate instanceof \DateTime) {
            $expirationDate = $expirationDate->format('Y-m-d');
        }
        $this->data->fundingInstrument->method = self::METHOD_ONLINE_BANK_DEBIT;
        $this->data->fundingInstrument->onlineBankDebit = new stdClass();
        $this->data->fundingInstrument->onlineBankDebit->bankNumber = $bankNumber;
        $this->data->fundingInstrument->onlineBankDebit->expirationDate = $expirationDate;
        $this->data->fundingInstrument->onlineBankDebit->returnUri = $returnUri;

        return $this;
    }

    /**
     * Set Multiorders.
     *
     * @param \Moip\Resource\Multiorders $multiorder
     *
     * @return $this
     */
    public function setMultiorder(Multiorders $multiorder)
    {
        $this->multiorder = $multiorder;

        return $this;
    }

    /**
     * Set order.
     *
     * @param \Moip\Resource\Orders $order
     *
     * @return $this
     */
    public function setOrder(Orders $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Turns on a delay on credit card payment capture (pre-authorization).
     *
     * @return $this
     */
    public function setDelayCapture()
    {
        $this->data->delayCapture = true;

        return $this;
    }

    /**
     * Set escrow to a payment.
     *
     * @param string $description
     *
     * @return $this
     */
    public function setEscrow($description)
    {
        $this->data->escrow = new stdClass();
        $this->data->escrow->description = $description;

        return $this;
    }

    /**
     * Capture a pre-authorized amount on a credit card payment.
     *
     * @throws \Exception
     *
     * @return Payment
     */
    public function capture()
    {
        $path = sprintf('/%s/%s/%s/%s', MoipResource::VERSION, self::PATH, $this->getId(), 'capture');
        if ($this->isMultipayment($this->getId())) {
            $path = sprintf('/%s/%s/%s/%s', MoipResource::VERSION, self::MULTI_PAYMENTS_PATH, $this->getId(), 'capture');
        }

        $response = $this->httpRequest($path, Requests::POST, $this);

        return $this->populate($response);
    }

    /**
     * Cancel a pre-authorized amount on a credit card payment.
     *
     * @throws \Exception
     *
     * @return Payment
     */
    public function cancel()
    {
        $path = sprintf('/%s/%s/%s/%s', MoipResource::VERSION, self::PATH, $this->getId(), 'void');
        if ($this->isMultipayment($this->getId())) {
            $path = sprintf('/%s/%s/%s/%s', MoipResource::VERSION, self::MULTI_PAYMENTS_PATH, $this->getId(), 'void');
        }

        $response = $this->httpRequest($path, Requests::POST, $this);

        return $this->populate($response);
    }

    /**
     * Cancel a pre-authorized amount on a credit card payment.
     *
     * @throws \Exception
     *
     * @return Payment
     */
    public function avoid()
    {
        trigger_error('The function \'avoid\' is deprecated, use \'cancel\' instead', E_USER_NOTICE);

        return $this->cancel();
    }

    /**
     * Authorize a payment (Available only in sandbox to credit card payment with status IN_ANALYSIS and billet payment with status WAITING).
     *
     * @return bool
     */
    public function authorize($amount = null)
    {
        if (is_null($amount)) {
            $amount = $this->getAmount()->total;
        }
        $path = sprintf('/%s/%s?payment_id=%s&amount=%s', self::SIMULATOR_PATH, 'authorize', $this->getId(), $amount);
        $response = $this->httpRequest($path, Requests::GET);

        if (empty($response)) {
            return true;
        }

        return false;
    }

    private function isMultipayment($paymentId)
    {
        return 0 === strpos($paymentId, 'MPY');
    }
}
