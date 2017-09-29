<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

/**
 * Description of AccountTest.
 */
class AccountTest extends TestCase
{
    public function testShouldCreateAccount()
    {
        $this->mockHttpSession($this->body_moip_account);

        $account = $this->moip->accounts()
            ->setName('Fulano')
            ->setLastName('De Tal')
            ->setEmail('fulano@email2.com')
            ->setIdentityDocument('4737283560', 'SSP', '2015-06-23')
            ->setBirthDate('1988-12-30')
            ->setTaxDocument('16262131000')
            ->setType('MERCHANT')
            ->setPhone(11, 66778899, 55)
            ->addAlternativePhone(11, 66448899, 55)
            ->addAlternativePhone(11, 66338899, 55)
            ->setTransparentAccount(true)
            ->addAddress('Rua de teste', 123, 'Bairro', 'Sao Paulo', 'SP', '01234567', 'Apt. 23', 'BRA')
            ->create();

        $this->assertNotEmpty($account->getId());
    }

    public function testShouldCreateAccountWithCompany()
    {
        $this->mockHttpSession($this->body_moip_account);

        $account = $this->moip->accounts()
            ->setName('Fulano')
            ->setLastName('De Tal')
            ->setEmail('fulano@email2.com')
            ->setIdentityDocument('4737283560', 'SSP', '2015-06-23')
            ->setBirthDate('1988-12-30')
            ->setTaxDocument('16262131000')
            ->setType('MERCHANT')
            ->setPhone(11, 66778899, 55)
            ->addAlternativePhone(11, 66448899, 55)
            ->addAlternativePhone(11, 66338899, 55)
            ->setTransparentAccount(true)
            ->addAddress('Rua de teste', 123, 'Bairro', 'Sao Paulo', 'SP', '01234567', 'Apt. 23', 'BRA')
            ->setCompanyName('Empresa Teste', 'Teste Empresa ME')
            ->setCompanyOpeningDate('2011-01-01')
            ->setCompanyPhone(11, 66558899, 55)
            ->setCompanyTaxDocument('69086878000198')
            ->setCompanyAddress('Rua de teste 2', 123, 'Bairro Teste', 'Sao Paulo', 'SP', '01234567', 'Apt. 23', 'BRA')
            ->setCompanyMainActivity('82.91-1/00', 'Atividades de cobranças e informações cadastrais')
            ->create();

        $this->assertNotEmpty($account->getId());
        $this->assertEquals('66448899', $account->getAlternativePhones()[0]->number);
        $this->assertEquals('Teste Empresa ME', $account->getCompany()->businessName);
    }

    public function testCheckExistingAccount()
    {
        $this->mockHttpSession('', 200);
        $this->assertTrue($this->moip->accounts()->checkExistence('123.456.798-91'));
    }

    public function testCheckNonExistingAccount()
    {
        $this->mockHttpSession('', 404);
        $this->assertFalse($this->moip->accounts()->checkExistence('412.309.725-10'));
    }
}
