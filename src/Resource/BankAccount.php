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
        return $this->data->id;
    }

    /**
     * Returns bank account type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->data->type;
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
     * Returns bank number.
     *
     * @return string
     */
    public function getBankNumber()
    {
        return $this->data->bankNumber;
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
     * Returns bank account agency number.
     *
     * @return int
     */
    public function getAgencyNumber()
    {
        return $this->data->agencyNumber;
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
     * Returns bank account agency check number.
     *
     * @return int
     */
    public function getAgencyCheckNumber()
    {
        return $this->data->agencyCheckNumber;
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
     * Returns bank account number.
     *
     * @return int
     */
    public function getAccountNumber()
    {
        return $this->data->accountNumber;
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
     * Returns bank account check number.
     *
     * @return int
     */
    public function getAccountCheckNumber()
    {
        return $this->data->accountCheckNumber;
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
     * Returns holder full name.
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->data->holder->fullname;
    }

    /**
     * Set holder full name.
     *
     * @param string $fullname Holder full name.
     *
     * @return $this
     */
    public function setFullname($fullname)
    {
        $this->data->holder->fullname = $fullname;

        return $this;
    }

    /**
     * Returns holder tax document.
     *
     * @return stdClass
     */
    public function getTaxDocument()
    {
        return $this->data->holder->taxDocument;
    }

    /**
     * Set holder tax document.
     *
     * @param string $type   Document type (CPF or CNPJ)
     * @param string $number Document number
     *
     * @return $this
     */
    public function setTaxDocument($type, $number)
    {
        $this->data->holder->taxDocument->type = $type;
        $this->data->holder->taxDocument->number = $number;

        return $this;
    }

    /**
     * Get a bank account.
     *
     * @param string $id Bank account id.
     *
     * @return stdClass
     */
    public function get($id)
    {
        return $this->getByPath(sprintf('/%s/%s/%s', MoipResource::VERSION, self::PATH, $id));
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
     * @param string|null $id Bank account id.
     *
     * @return stdClass
     */
    public function update($id = null)
    {
        $id = (!empty($id) ? $id : $this->getId());

        return $this->updateByPath(sprintf('/%s/%s/%s', MoipResource::VERSION, self::PATH, $id));
    }

    /**
     * Delete a bank account.
     *
     * @param string $id Bank account id.
     *
     * @return mixed
     */
    public function delete($id)
    {
        return $this->deleteByPath(sprintf('/%s/%s/%s', MoipResource::VERSION, self::PATH, $id));
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
