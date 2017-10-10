<?php

namespace Moip\Resource;

use stdClass;

/**
 * Class BankAccount.
 */
class BankAccount extends MoipResource
{
    /**
     * Path customers API.
     *
     * @const string
     */
    const PATH = 'bankaccounts';

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
    }

    /**
     * Set bank account type.
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->data->type = $type;
        
        return $this;
    }

    /**
     * Get bank account type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->getIfSet('type');
    }

    /**
     * Set bank account number.
     *
     * @return $this
     */
    public function setBankNumber($bankNumber)
    {
        $this->data->bankNumber = $bankNumber;
        
        return $this;
    }

    /**
     * Get bank account number.
     *
     * @return string
     */
    public function getBankNumber()
    {
        return $this->getIfSet('bankNumber');
    }

    /**
     * Set agency number.
     *
     * @return $this
     */
    public function setAgencyNumber($agencyNumber)
    {
        $this->data->agencyNumber = $agencyNumber;
        
        return $this;
    }

    /**
     * Get agency number.
     *
     * @return string
     */
    public function getAgencyNumber()
    {
        return $this->getIfSet('agencyNumber');
    }

    /**
     * Set agency check number.
     *
     * @return $this
     */
    public function setAgencyCheckNumber($agencyCheckNumber)
    {
        $this->data->agencyCheckNumber = $agencyCheckNumber;
        
        return $this;
    }

    /**
     * Get bank agency check number.
     *
     * @return string
     */
    public function getAgencyCheckNumber()
    {
        return $this->getIfSet('agencyCheckNumber');
    }

    /**
     * Set account number.
     *
     * @return $this
     */
    public function setAccountNumber($accountNumber)
    {
        $this->data->accountNumber = $accountNumber;
        
        return $this;
    }

    /**
     * Get account number.
     *
     * @return $this
     */
    public function getAccountNumber()
    {   
        return $this->getIfSet('accountNumber');
    }

    /**
     * Set account check number.
     *
     * @return $this
     */
    public function setAccountCheckNumber($accountCheckNumber)
    {
        $this->data->accountCheckNumber = $accountCheckNumber;
        
        return $this;
    }

    /**
     * Get account check number.
     *
     * @return $this
     */
    public function getAccountCheckNumber()
    {   
        return $this->getIfSet('accountCheckNumber');
    }

    /**
     * Get customer fullname.
     *
     * @return string Customer's full name.
     */
    public function getFullname()
    {
        return $this->getIfSet('fullname');
    }

    /**
     * Get tax document type from customer.
     *
     * @return string Type of value: CPF and CNPJ
     */
    public function getTaxDocumentType()
    {
        return $this->getIfSet('type', $this->data->taxDocument);
    }

    /**
     * Get tax document number from customer.
     *
     * @return string Document Number.
     */
    public function getTaxDocumentNumber()
    {
        return $this->getIfSet('number', $this->data->taxDocument);
    }

    public function setHolder($fullname, $taxDocument, $type)
    {
        $this->data->holder = new stdClass();
        $this->data->holder->fullname = $fullname;
        $this->data->holder->taxDocument = new stdClass();
        $this->data->holder->taxDocument->type = $type;
        $this->data->holder->taxDocument->number = $taxDocument;

        return $this;
    }

    /**
     * Mount the structure from bank account.
     *
     * @param \stdClass $response
     *
     * @return BankAccount.
     */
    protected function populate(stdClass $response)
    {
        $bankAccount = clone $this;
        $bankAccount->data = new stdClass();
        $bankAccount->data->id = $this->getIfSet('id', $response);
        $bankAccount->data->type = $this->getIfSet('type', $response);
        $bankAccount->data->agencyNumber = $this->getIfSet('agencyNumber', $response);
        $bankAccount->data->agencyCheckNumber = $this->getIfSet('agencyCheckNumber', $response);
        $bankAccount->data->accountNumber = $this->getIfSet('accountNumber', $response);
        $bankAccount->data->accountCheckNumber = $this->getIfSet('accountCheckNumber', $response);
        $bankAccount->data->bankNumber = $this->getIfSet('bankNumber', $response);
        $bankAccount->data->bankName = $this->getIfSet('bankName', $response);

        $bankAccount->data->holder->fullname = $this->getIfSet('fullname', $response);
        $bankAccount->data->holder->taxDocument = new stdClass();
        $bankAccount->data->holder->taxDocument->type = $this->getIfSet('type', $this->getIfSet('taxDocument', $response));
        $bankAccount->data->holder->taxDocument->number = $this->getIfSet('number', $this->getIfSet('taxDocument', $response));

        $bankAccount->data->_links = $this->getIfSet('_links', $response);

        return $bankAccount;
    }

}
