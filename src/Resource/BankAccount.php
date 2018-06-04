<?php

namespace Moip\Resource;

use stdClass;

/**
 * Class BankAccount.
 */
class BankAccount extends MoipResource
{
    /**
     * Path bank accounts API.
     *
     * @const string
     */
    const PATH = 'bankaccounts';

    /**
     * Path accounts API.
     *
     * @const string
     */
    const PATH_ACCOUNT = 'accounts';

    /**
     * Bank account type.
     *
     * @const string
     */
    const CHECKING = 'CHECKING';

    /**
     * Bank account type.
     *
     * @const string
     */
    const SAVING = 'SAVING';

    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
        $this->data->holder = new stdClass();
        $this->data->holder->taxDocument = new stdClass();
    }

    /**
     * Returns bank account id.
     *
     * @return stdClass
     */
    public function getId()
    {
        return $this->getIfSet('id');
    }

    /**
     * Set bank account type.
     *
     * @param string $type Bank account type (CHECKING or SAVING).
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->data->type = $type;

        return $this;
    }

    /**
     * Returns bank account type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->getIfSet('type');
    }

    /**
     * Set bank number.
     *
     * @param string $bank_number Bank number.
     *
     * @return $this
     */
    public function setBankNumber($bank_number)
    {
        $this->data->bankNumber = $bank_number;

        return $this;
    }

    /**
     * Returns bank number.
     *
     * @return string
     */
    public function getBankNumber()
    {
        return $this->getIfSet('bankNumber');
    }

    /**
     * Set bank account agency number.
     *
     * @param int $agency_number Bank account agency number.
     *
     * @return $this
     */
    public function setAgencyNumber($agency_number)
    {
        $this->data->agencyNumber = $agency_number;

        return $this;
    }

    /**
     * Returns bank account agency number.
     *
     * @return int
     */
    public function getAgencyNumber()
    {
        return $this->getIfSet('agencyNumber');
    }

    /**
     * Set bank account agency check number.
     *
     * @param int $agency_check_number Bank account agency check number.
     *
     * @return $this
     */
    public function setAgencyCheckNumber($agency_check_number)
    {
        $this->data->agencyCheckNumber = $agency_check_number;

        return $this;
    }

    /**
     * Returns bank account agency check number.
     *
     * @return int
     */
    public function getAgencyCheckNumber()
    {
        return $this->getIfSet('agencyCheckNumber');
    }

    /**
     * Set bank account number.
     *
     * @param int $account_number Bank account number.
     *
     * @return $this
     */
    public function setAccountNumber($account_number)
    {
        $this->data->accountNumber = $account_number;

        return $this;
    }

    /**
     * Returns bank account number.
     *
     * @return int
     */
    public function getAccountNumber()
    {
        return $this->getIfSet('accountNumber');
    }

    /**
     * Set bank account check number.
     *
     * @param int $account_check_number Bank account check number.
     *
     * @return $this
     */
    public function setAccountCheckNumber($account_check_number)
    {
        $this->data->accountCheckNumber = $account_check_number;

        return $this;
    }

    /**
     * Returns bank account check number.
     *
     * @return int
     */
    public function getAccountCheckNumber()
    {
        return $this->getIfSet('accountCheckNumber');
    }

    /**
     * Set holder.
     *
     * @param string $fullname Holder full name.
     * @param string $number   Document number.
     * @param string $type     Document type (CPF or CNPJ).
     *
     * @return $this
     */
    public function setHolder($fullname, $number, $type)
    {
        $this->data->holder->fullname = $fullname;

        $this->data->holder->taxDocument->type = $type;
        $this->data->holder->taxDocument->number = $number;

        return $this;
    }

    /**
     * Returns holder full name.
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->getIfSet('fullname', $this->data->holder);
    }

    /**
     * Get tax document type from customer.
     *
     * @return string Type of value: CPF and CNPJ
     */
    public function getTaxDocumentType()
    {
        return $this->getIfSet('type', $this->data->holder->taxDocument);
    }

    /**
     * Get tax document number from customer.
     *
     * @return string Document Number.
     */
    public function getTaxDocumentNumber()
    {
        return $this->getIfSet('number', $this->data->holder->taxDocument);
    }

    /**
     * Get a bank account.
     *
     * @param string $bank_account_id Bank account id.
     *
     * @return stdClass
     */
    public function get($bank_account_id)
    {
        return $this->getByPath(sprintf('/%s/%s/%s', MoipResource::VERSION, self::PATH, $bank_account_id));
    }

    /**
     * Create a new BankAccount List instance.
     *
     * @param string Account id.
     *
     * @return \Moip\Resource\BankAccountList
     */
    public function getList($account_id)
    {
        $bankAccountList = new BankAccountList($this->moip);

        return $bankAccountList->get($account_id);
    }

    /**
     * Create a new bank account.
     *
     * @param string Account id.
     *
     * @return stdClass
     */
    public function create($account_id)
    {
        return $this->createResource(sprintf('/%s/%s/%s/%s', MoipResource::VERSION, self::PATH_ACCOUNT, $account_id, self::PATH));
    }

    /**
     * Update a bank account.
     *
     * @param string|null $bank_account_id Bank account id.
     *
     * @return stdClass
     */
    public function update($bank_account_id = null)
    {
        $bank_account_id = (!empty($bank_account_id) ? $bank_account_id : $this->getId());

        return $this->updateByPath(sprintf('/%s/%s/%s', MoipResource::VERSION, self::PATH, $bank_account_id));
    }

    /**
     * Delete a bank account.
     *
     * @param string $bank_account_id Bank account id.
     *
     * @return mixed
     */
    public function delete($bank_account_id)
    {
        return $this->deleteByPath(sprintf('/%s/%s/%s', MoipResource::VERSION, self::PATH, $bank_account_id));
    }

    /**
     * Mount the bank account structure.
     *
     * @param stdClass $response
     *
     * @return \Moip\Resource\BankAccount
     */
    protected function populate(stdClass $response)
    {
        $bank_account = clone $this;
        $bank_account->data->id = $this->getIfSet('id', $response);
        $bank_account->data->agencyNumber = $this->getIfSet('agencyNumber', $response);
        $bank_account->data->accountNumber = $this->getIfSet('accountNumber', $response);
        $bank_account->data->status = $this->getIfSet('status', $response);
        $bank_account->data->accountCheckNumber = $this->getIfSet('accountCheckNumber', $response);
        $bank_account->data->bankName = $this->getIfSet('bankName', $response);
        $bank_account->data->type = $this->getIfSet('type', $response);
        $bank_account->data->agencyCheckNumber = $this->getIfSet('agencyCheckNumber', $response);
        $bank_account->data->bankNumber = $this->getIfSet('bankNumber', $response);

        $holder = $this->getIfSet('holder', $response);
        $bank_account->data->holder = new stdClass();
        $bank_account->data->holder->fullname = $this->getIfSet('fullname', $holder);

        $tax_document = $this->getIfSet('taxDocument', $holder);
        $bank_account->data->holder->taxDocument = new stdClass();
        $bank_account->data->holder->taxDocument->number = $this->getIfSet('number', $tax_document);
        $bank_account->data->holder->taxDocument->type = $this->getIfSet('type', $tax_document);

        $bank_account->data->_links = $this->getIfSet('_links', $response);
        $bank_account->data->createdAt = $this->getIfSet('createdAt', $response);

        return $bank_account;
    }
}
