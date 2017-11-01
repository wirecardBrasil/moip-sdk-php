<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

class BankAccountListTest extends TestCase
{
    public function testShouldGetBankAccountList()
    {
        $this->mockHttpSession($this->body_bank_account_list);

        $account_id = 'MPA-3C5358FF2296';

        $bank_accounts = $this->moip->bankaccount()->getList($account_id);

        $this->assertNotNull($bank_accounts->getBankAccounts());
    }
}
