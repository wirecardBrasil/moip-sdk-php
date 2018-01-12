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
    const METHOD = 'BANK_ACCOUNT';

    /**
     * @const string
     */
    const TYPE = 'CHECKING';

    /**
     * @const string
     */
    const TYPE_HOLD = 'CPF';

    /**
     * Initializes new instances.
     */
    protected function initialize()
    {
        $this->data = new stdClass();
        $this->data->transferInstrument = new stdClass();
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

        $transfer_instrument = $this->getIfSet('transferInstrument', $response);
        $transfers->data->transferInstrument = new stdClass();
        $transfers->data->transferInstrument->method = $this->getIfSet('method', $transfer_instrument);

        $bank_account = $this->getIfSet('bankAccount', $transfer_instrument);
        $transfers->data->transferInstrument->bankAccount = new stdClass();
        $transfers->data->transferInstrument->bankAccount->id = $this->getIfSet('id', $bank_account);
        $transfers->data->transferInstrument->bankAccount->type = $this->getIfSet('type', $bank_account);
        $transfers->data->transferInstrument->bankAccount->bankNumber = $this->getIfSet('bankNumber', $bank_account);
        $transfers->data->transferInstrument->bankAccount->agencyNumber = $this->getIfSet('agencyNumber', $bank_account);
        $transfers->data->transferInstrument->bankAccount->agencyCheckNumber = $this->getIfSet('agencyCheckNumber', $bank_account);
        $transfers->data->transferInstrument->bankAccount->accountNumber = $this->getIfSet('accountNumber', $bank_account);
        $transfers->data->transferInstrument->bankAccount->accountCheckNumber = $this->getIfSet('accountCheckNumber', $bank_account);

        $holder = $this->getIfSet('holder', $bank_account);
        $transfers->data->transferInstrument->bankAccount->holder = new stdClass();
        $transfers->data->transferInstrument->bankAccount->holder->fullname = $this->getIfSet('fullname', $holder);

        $tax_document = $this->getIfSet('taxDocument', $holder);
        $this->data->transferInstrument->bankAccount->holder->taxDocument = new stdClass();
        $this->data->transferInstrument->bankAccount->holder->taxDocument->type = $this->getIfSet('type', $tax_document);
        $this->data->transferInstrument->bankAccount->holder->taxDocument->number = $this->getIfSet('number', $tax_document);

        return $transfers;
    }

    /**
     * Set info of transfers.
     *
     * @param int    $amount
     * @param string $bankNumber         Bank number. possible values: 001, 237, 341, 041.
     * @param int    $agencyNumber
     * @param int    $agencyCheckNumber
     * @param int    $accountNumber
     * @param int    $accountCheckNumber
     *
     * @return $this
     */
    public function setTransfers(
        $amount,
        $bankNumber,
        $agencyNumber,
        $agencyCheckNumber,
        $accountNumber,
        $accountCheckNumber
    ) {
        $this->data->amount = $amount;
        $this->data->transferInstrument->method = self::METHOD;
        $this->data->transferInstrument->bankAccount->type = self::TYPE;
        $this->data->transferInstrument->bankAccount->bankNumber = $bankNumber;
        $this->data->transferInstrument->bankAccount->agencyNumber = $agencyNumber;
        $this->data->transferInstrument->bankAccount->agencyCheckNumber = $agencyCheckNumber;
        $this->data->transferInstrument->bankAccount->accountNumber = $accountNumber;
        $this->data->transferInstrument->bankAccount->accountCheckNumber = $accountCheckNumber;

        return $this;
    }

    /**
     * Set info of transfers to a saved bank account.
     *
     * @param int    $amount        Amount
     * @param string $bankAccountId Saved bank account id.
     *
     * @return $this
     */
    public function setTransfersToBankAccount($amount, $bankAccountId)
    {
        $this->data->amount = $amount;
        $this->data->transferInstrument->method = self::METHOD;
        $this->data->transferInstrument->bankAccount->id = $bankAccountId;

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
    public function setHolder($fullname, $taxDocument)
    {
        $this->data->transferInstrument->bankAccount->holder->fullname = $fullname;
        $this->data->transferInstrument->bankAccount->holder->taxDocument->type = self::TYPE_HOLD;
        $this->data->transferInstrument->bankAccount->holder->taxDocument->number = $taxDocument;

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
