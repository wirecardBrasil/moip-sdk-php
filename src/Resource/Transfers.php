<?php

namespace Moip\Resource;

use Requests;
use stdClass;

/**
 * Class Transfers.
 */
class Transfers extends MoipResource
{
    /**
     * @const strign
     */
    const PATH = 'transfers';

    /**
     * @const strign
     */
    const METHOD = 'BANK_ACCOUNT';

    /**
     * @const strign
     */
    const TYPE = 'CHECKING';

    /**
     * @const strign
     */
    const TYPE_HOLD = 'CPF';

    /**
     * Initializes new instances.
     */
    protected function initialize()
    {
        $this->data = new stdClass();
    }

    /**
     * @param stdClass $response
     *
     * @return Transfers
     */
    protected function populate(stdClass $response)
    {
        $transfers = clone $this;

        return $transfers;
    }

    /**
     * Set info of transfers.
     *
     * @param $amount
     * @param $bankNumber Bank number. possible values: 001, 237, 341, 041.
     * @param $agencyNumber
     * @param $agencyCheckNumber
     * @param $accountNumber
     * @param $accountCheckNumber
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
        $this->data->transferInstrument = new stdClass();
        $this->data->transferInstrument->method = self::METHOD;
        $this->data->transferInstrument->bankAccount = new stdClass();
        $this->data->transferInstrument->bankAccount->type = self::TYPE;
        $this->data->transferInstrument->bankAccount->bankNumber = $bankNumber;
        $this->data->transferInstrument->bankAccount->agencyNumber = $agencyNumber;
        $this->data->transferInstrument->bankAccount->agencyCheckNumber = $agencyCheckNumber;
        $this->data->transferInstrument->bankAccount->accountNumber = $accountNumber;
        $this->data->transferInstrument->bankAccount->accountCheckNumber = $accountCheckNumber;

        return $this;
    }

    /**
     * @param $fullname
     * @param $taxDocument
     *
     * @return $this
     */
    public function setHolder($fullname, $taxDocument)
    {
        $this->data->transferInstrument->bankAccount->holder = new stdClass();
        $this->data->transferInstrument->bankAccount->holder->fullname = $fullname;
        $this->data->transferInstrument->bankAccount->holder->taxDocument = new stdClass();
        $this->data->transferInstrument->bankAccount->holder->taxDocument->type = self::TYPE_HOLD;
        $this->data->transferInstrument->bankAccount->holder->taxDocument->number = $taxDocument;

        return $this;
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
     * Get MoIP Transfers id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->getIfSet('id');
    }
}
