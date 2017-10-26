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
        $this->mockHttpSession($this->body_moip_account_create);

        $account = $this->moip->accounts()
            ->setName('For tests')
            ->setLastName('Mine Customer Company')
            ->setEmail('fortestsminecustomercompany@mailinator.com')
            ->setIdentityDocument('144563480', 'SSP', '2017-10-25')
            ->setBirthDate('1990-01-01')
            ->setTaxDocument('02822921873')
            ->setType('MERCHANT')
            ->setPhone(11, 965213244, 55)
            ->addAlternativePhone(11, 912345678, 55)
            ->setTransparentAccount(false)
            ->addAddress('Av. Brigadeiro Faria Lima', 2927, 'Itaim', 'São Paulo', 'SP', '01234000', 'Apt. X', 'BRA')
            ->create();

        $this->assertNotEmpty($account->getId());
        $this->assertNotEmpty($account->getAccessToken());
        $this->assertNotEmpty($account->getchannelId());
        $this->assertNotEmpty($account->getCreatedAt());
        $this->assertEquals('144563480', $account->getIdentityDocumentNumber());
        $this->assertEquals('SSP', $account->getIdentityDocumentIssuer());
        $this->assertEquals('2017-10-25', $account->getIdentityDocumentIssueDate());
        $this->assertEquals('RG', $account->getIdentityDocumentType());
    }

    public function testShouldCreateAccountWithCompany()
    {
        $this->mockHttpSession($this->body_moip_account_create);

        $account = $this->moip->accounts()
            ->setName('For tests')
            ->setLastName('Mine Customer Company')
            ->setEmail('fortestsminecustomercompany@mailinator.com')
            ->setIdentityDocument('144563480', 'SSP', '2017-10-25')
            ->setBirthDate('1990-01-01')
            ->setTaxDocument('02822921873')
            ->setType('MERCHANT')
            ->setPhone(11, 965213244, 55)
            ->addAlternativePhone(11, 912345678, 55)
            ->setTransparentAccount(false)
            ->addAddress('Av. Brigadeiro Faria Lima', 2927, 'Itaim', 'São Paulo', 'SP', '01234000', 'Apt. X', 'BRA')
            ->setCompanyName('Mine Customer Company', 'Company Business')
            ->setCompanyOpeningDate('2011-01-01')
            ->setCompanyPhone(11, 987654321, 55)
            ->setCompanyTaxDocument('64893609000110')
            ->setCompanyAddress('R. Company', 321, 'Bairro Company', 'São Paulo', 'SP', '12345678', 'Ap. Y', 'BRA')
            ->setCompanyMainActivity('82.91-1/00', 'Test')
            ->create();

        $this->assertNotEmpty($account->getId());
        $this->assertNotEmpty($account->getAccessToken());
        $this->assertNotEmpty($account->getchannelId());
        $this->assertNotEmpty($account->getCreatedAt());
        $this->assertEquals('144563480', $account->getIdentityDocumentNumber());
        $this->assertEquals('SSP', $account->getIdentityDocumentIssuer());
        $this->assertEquals('RG', $account->getIdentityDocumentType());
        $this->assertEquals('912345678', $account->getAlternativePhones()[0]->number);
        $this->assertEquals('Company Business', $account->getCompany()->businessName);
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

    public function testShouldGetAccount()
    {
        $this->mockHttpSession($this->body_moip_account_get);
        $account_id = 'MPA-7E9B1F907512';
        $account = $this->moip->accounts()->get($account_id);

        $this->assertNotEmpty($account->getId());
        $this->assertNotEmpty($account->getCreatedAt());
        $this->assertEquals(false, $account->getTransparentAccount());
        $this->assertEquals('fortestsminecustomer@mailinator.com', $account->getLogin());
        $this->assertEquals(true, $account->getEmailConfirmed());
        $this->assertEquals('fortestsminecustomer@mailinator.com', $account->getEmailAddress());
        $this->assertEquals('794.663.228-26', $account->getTaxDocumentNumber());
    }
}
