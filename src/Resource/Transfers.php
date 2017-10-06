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
        $transfers->data->amount = $this->getIfSet('amount', $response);

        $transfer_instrument = $this->getIfSet('transferInstrument', $response);
        $transfers->data->transferInstrument = new stdClass();
        $transfers->data->transferInstrument->method = $this->getIfSet('method', $transfer_instrument);

        $bank_account = $this->getIfSet('bankAccount', $transfer_instrument);
        $transfers->data->transferInstrument->bankAccount = new stdClass();
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
     * Initializes new transferInstrument instance if it doesn't exist.
     */
    private function intializeTransferInstrument()
    {
        if (!isset($this->data->transferInstrument)) {
            $this->data->transferInstrument = new stdClass();
        }
    }

    /**
     * Set info of transfers.
     *
     * @param integer $amount
     * @param string $bankNumber Bank number. possible values: 001, 237, 341, 041.
     * @param integer $agencyNumber
     * @param integer $agencyCheckNumber
     * @param integer $accountNumber
     * @param integer $accountCheckNumber
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
        $this->intializeTransferInstrument();
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
     * Set info of holder.
     *
     * @param string $fullname
     * @param integer $taxDocument
     *
     * @return $this
     */
    public function setHolder($fullname, $taxDocument)
    {
        $this->intializeTransferInstrument();
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
