<?php

namespace Moip\Resource;

use Moip\Helper\Filters;
use Moip\Helper\Pagination;
use Requests;
use stdClass;

/**
 * Class Transfers.
 */
class Transfers extends MoipResource
{
    /**
     * @const string
     */
    const PATH = 'transfers';

    /**
     * @const string
     */
    const METHOD_BKA = 'BANK_ACCOUNT';

    /**
     * @const string
     */
    const METHOD_MPA = 'MOIP_ACCOUNT';

    /**
     * @const string
     */
    const TYPE = 'CHECKING';

    /**
     * Initializes new instances.
     */
    protected function initialize()
    {
        $this->data = new stdClass();
        $this->data->transferInstrument = new stdClass();
        $this->data->transferInstrument->moipAccount = new stdClass();
        $this->data->transferInstrument->bankAccount = new stdClass();
        $this->data->transferInstrument->bankAccount->holder = new stdClass();
        $this->data->transferInstrument->bankAccount->holder->taxDocument = new stdClass();
    }

    /**
     * @param stdClass $response
     *
     * @return Transfers
     */
    protected function populate(stdClass $response)
    {
        $transfers = clone $this;

        $transfers->data->id = $this->getIfSet('id', $response);
        $transfers->data->ownId = $this->getIfSet('ownId', $response);
        $transfers->data->amount = $this->getIfSet('amount', $response);

        $transferInstrument = $this->getIfSet('transferInstrument', $response);
        $transfers->data->transferInstrument = new stdClass();
        $transfers->data->transferInstrument->method = $this->getIfSet('method', $transferInstrument);

        $moipAccount = $this->getIfSet('moipAccount', $transferInstrument);
        $transfers->data->transferInstrument->moipAccount = new stdClass();
        $transfers->data->transferInstrument->moipAccount->id = $this->getIfSet('id', $moipAccount);

        $bankAccount = $this->getIfSet('bankAccount', $transferInstrument);
        $transfers->data->transferInstrument->bankAccount = new stdClass();
        $transfers->data->transferInstrument->bankAccount->id = $this->getIfSet('id', $bankAccount);
        $transfers->data->transferInstrument->bankAccount->type = $this->getIfSet('type', $bankAccount);
        $transfers->data->transferInstrument->bankAccount->bankNumber = $this->getIfSet('bankNumber', $bankAccount);
        $transfers->data->transferInstrument->bankAccount->agencyNumber = $this->getIfSet('agencyNumber', $bankAccount);
        $transfers->data->transferInstrument->bankAccount->agencyCheckNumber = $this->getIfSet('agencyCheckNumber', $bankAccount);
        $transfers->data->transferInstrument->bankAccount->accountNumber = $this->getIfSet('accountNumber', $bankAccount);
        $transfers->data->transferInstrument->bankAccount->accountCheckNumber = $this->getIfSet('accountCheckNumber', $bankAccount);

        $holder = $this->getIfSet('holder', $bankAccount);
        $transfers->data->transferInstrument->bankAccount->holder = new stdClass();
        $transfers->data->transferInstrument->bankAccount->holder->fullname = $this->getIfSet('fullname', $holder);

        $tax_document = $this->getIfSet('taxDocument', $holder);
        $this->data->transferInstrument->bankAccount->holder->taxDocument = new stdClass();
        $this->data->transferInstrument->bankAccount->holder->taxDocument->type = $this->getIfSet('type', $tax_document);
        $this->data->transferInstrument->bankAccount->holder->taxDocument->number = $this->getIfSet('number', $tax_document);

        return $transfers;
    }

    /**
     * Set the amount of transfer.
     *
     * @param int $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->data->amount = $amount;

        return $this;
    }

    /**
     * Returns amount.
     * 
     * @return amount
     */
    public function getAmount()
    {
        return $this->data->amount;
    }

    /**
     * Set the bank accout transfer.
     *
     * @param string $bankNumber         Bank number. possible values: 001, 237, 341, 041.
     * @param int    $agencyNumber
     * @param int    $agencyCheckNumber
     * @param int    $accountNumber
     * @param int    $accountCheckNumber
     *
     * @return $this
     */
    public function transferToBankAccount(
        $bankNumber,
        $agencyNumber,
        $agencyCheckNumber,
        $accountNumber,
        $accountCheckNumber
    ) {
        $this->data->transferInstrument->method = self::METHOD_BKA;
        $this->data->transferInstrument->bankAccount->type = self::TYPE;
        $this->data->transferInstrument->bankAccount->bankNumber = $bankNumber;
        $this->data->transferInstrument->bankAccount->agencyNumber = $agencyNumber;
        $this->data->transferInstrument->bankAccount->agencyCheckNumber = $agencyCheckNumber;
        $this->data->transferInstrument->bankAccount->accountNumber = $accountNumber;
        $this->data->transferInstrument->bankAccount->accountCheckNumber = $accountCheckNumber;

        return $this;
    }

    /**
     * Set the ID of a saved bank account.
     *
     * @param string $bankAccountId Saved bank account ID (BKA-XXXXXXX).
     *
     * @return $this
     */
    public function transferWithBankAccountId($bankAccountId)
    {
        $this->data->transferInstrument->method = self::METHOD_BKA;
        $this->data->transferInstrument->bankAccount->id = $bankAccountId;

        return $this;
    }

    /**
     * Set the Moip Account ID to create a transfer to this account.
     * 
     * @param string $moipAccountId The Moip Account ID (MPA-XXXXXXX)
     */
    public function transferToMoipAccount($moipAccountId)
    {
        $this->data->transferInstrument->method = self::METHOD_MPA;
        $this->data->transferInstrument->moipAccount->id = $moipAccountId;

        return $this;
    }

    /**
     * Returns transfer.
     *
     * @return stdClass
     */
    public function getTransfers()
    {
        return $this->data;
    }

    /**
     * Get own request id. external reference.
     *
     * @param mixed $ownId id
     *
     * @return $this
     */
    public function setOwnId($ownId)
    {
        $this->data->ownId = $ownId;

        return $this;
    }

    /**
     * Set info of holder.
     *
     * @param string $fullname
     * @param int    $taxDocument
     *
     * @return $this
     */
    public function setHolder($fullname, $taxDocumentNumber, $taxDocumentType = 'CPF')
    {
        $this->data->transferInstrument->bankAccount->holder->fullname = $fullname;
        $this->data->transferInstrument->bankAccount->holder->taxDocument->type = $taxDocumentType;
        $this->data->transferInstrument->bankAccount->holder->taxDocument->number = $taxDocumentNumber;

        return $this;
    }

    /**
     * Returns transfer holder.
     *
     * @return stdClass
     */
    public function getHolder()
    {
        return $this->data->transferInstrument->bankAccount->holder;
    }

    /**
     * Execute Tranfers.
     *
     * @return Transfers
     */
    public function execute()
    {
        $path = sprintf('/%s/%s', MoipResource::VERSION, self::PATH);

        $response = $this->httpRequest($path, Requests::POST, $this);

        return $this->populate($response);
    }

    /**
     * Revert Tranfers.
     *
     * @param string $id Transfer id.
     *
     * @return Transfers
     */
    public function revert($id)
    {
        $path = sprintf('/%s/%s/%s/%s', MoipResource::VERSION, self::PATH, $id, 'reverse');

        $response = $this->httpRequest($path, Requests::POST, $this);

        return $this->populate($response);
    }

    /**
     * Get a Transfer.
     *
     * @param string $id Transfer id.
     *
     * @return stdClass
     */
    public function get($id)
    {
        return $this->getByPath(sprintf('/%s/%s/%s', MoipResource::VERSION, self::PATH, $id));
    }

    /**
     * Create a new Transfers list instance.
     *
     * @return \Moip\Resource\TransfersList
     */
    public function getList(Pagination $pagination = null, Filters $filters = null, $qParam = '')
    {
        $transfersList = new TransfersList($this->moip);

        return $transfersList->get($pagination, $filters, $qParam);
    }

    /**
     * Get MoIP Transfers id.
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
     * @return mixed
     */
    public function getOwnId()
    {
        return $this->getIfSet('ownId');
    }
}
