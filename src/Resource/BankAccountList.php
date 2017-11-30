<?php

namespace Moip\Resource;

use stdClass;

class BankAccountList extends MoipResource
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
    }

    /**
     * Get bank accounts.
     *
     * @return array
     */
    public function getBankAccounts()
    {
        return $this->data->bankAccounts;
    }

    /**
     * Get a bank accounts list.
     *
     * @param string Account id.
     *
     * @return stdClass
     */
    public function get($account_id)
    {
        return $this->getByPath(sprintf('/%s/%s/%s/%s', MoipResource::VERSION, self::PATH_ACCOUNT, $account_id, self::PATH));
    }

    protected function populate(stdClass $response)
    {
        $bankAccountsList = clone $this;

        $bankAccountsList->data = new stdClass();

        $bankAccountsList->data->bankAccounts = $response;

        return $bankAccountsList;
    }
}
