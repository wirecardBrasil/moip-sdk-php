<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

class BankAccountTest extends TestCase
{
    private function createBankAccount()
    {
        $this->mockHttpSession($this->body_bank_account_create);

        $account_id = 'MPA-3C5358FF2296';

        $bank_account = $this->moip->bank_accounts()
            ->setBankNumber('237')
            ->setAgencyNumber('12345')
            ->setAgencyCheckNumber('0')
            ->setAccountNumber('12345678')
            ->setAccountCheckNumber('7')
            ->setType('CHECKING')
            ->setTaxDocument('CPF', '622.134.533-22')
            ->setFullname('Demo Moip')
            ->create($account_id);

        return $bank_account;
    }

    public function testShouldCreateBankAccount()
    {
        $bank_account = $this->createBankAccount();
        $this->assertNotEmpty($bank_account->getId());
    }

    public function testShouldGetBankAccount()
    {
        $this->mockHttpSession($this->body_bank_account_create);

        $bank_account_id = 'BKA-DN6831N81J7K';

        $bank_account = $this->moip->bank_accounts()->get($bank_account_id);
        $this->assertEquals($bank_account_id, $bank_account->getId());
        $this->assertEquals('CHECKING', $bank_account->getType());
        $this->assertEquals('237', $bank_account->getBankNumber());
        $this->assertEquals('12345', $bank_account->getAgencyNumber());
        $this->assertEquals('0', $bank_account->getAgencyCheckNumber());
        $this->assertEquals('12345678', $bank_account->getAccountNumber());
        $this->assertEquals('7', $bank_account->getAccountCheckNumber());
        $this->assertEquals('Demo Moip', $bank_account->getFullname());

        $taxDocument = $bank_account->getTaxDocument();
        $this->assertEquals('CPF', $taxDocument->type);
        $this->assertEquals('622.134.533-22', $taxDocument->number);
    }

    public function testShouldUpdateBankAccount()
    {
        $this->mockHttpSession($this->body_bank_account_update);

        $bank_account_id = 'BKA-DN6831N81J7K';

        $bank_account = $this->moip->bank_accounts()
            ->setAccountCheckNumber('8')
            ->update($bank_account_id);

        $this->assertEquals('8', $bank_account->getAccountCheckNumber());
    }
}