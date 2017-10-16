<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

class RefundTest extends TestCase
{
    public function setUp()
    {        
        $this->bankAccount = $this->moip->bankAccount()
            ->setType('CHECKING')
            ->setBankNumber('001')
            ->setAgencyNumber('4444444')
            ->setAgencyCheckNumber('2')
            ->setAccountNumber('1234')
            ->setAccountNumberCheckNumber('4');
    }

    private function paymentBoleto()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $this->mockHttpSession($this->body_billet_pay);
        $payment = $order->payments()
            ->setBoleto(new \DateTime('today +1day'),'http://dev.moip.com.br/images/logo-header-moip.png')
            ->execute();
        $this->mockHttpSession('');
        $payment->authorize();

        return $payment;
    }

    private function paymentCreditCard()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $this->mockHttpSession($this->body_cc_pay_pci);
        return $order->payments()->setCreditCard(5, 2018, '5555666677778884', 123, $this->createCustomer())->execute();
    }

    public function testRefundCreditCardFull()
    {   
        $payment = $this->paymentCreditCard();
        $this->mockHttpSession($this->body_refund_full_cc);
        $refund = $payment->refunds()
            ->creditCardFull();
        $this->assertNotEmpty($refund->getId());
    }

    public function testRefundCreditCardPartial()
    {
        $payment = $this->paymentCreditCard();
        $this->mockHttpSession($this->body_refund_partial_cc);
        $refund = $payment->refunds()
            ->creditCardPartial(5000);
        $this->assertNotEmpty($refund->getId());
    }

    public function testRefundBankAccountFull()
    {
        $payment = $this->paymentBoleto();
        $this->mockHttpSession($this->body_refund_full_bankaccount);
        $refund = $payment->refunds()
            ->bankAccountFull('CHECKING', '001', '1584', '9', '00210169', '6', $order->getCustomer());
        $this->assertNotEmpty($refund->getId());
    }

    public function testRefundBankAccountPartial()
    {
        $payment = $this->paymentBoleto();
        $this->mockHttpSession($this->body_refund_partial_bankaccount);
        $refund = $payment->refunds()
            ->bankAccountPartial(20000, 'SAVING', '001', '1584', '9', '00210169', '6', $order->getCustomer());
        $this->assertNotEmpty($refund->getId());
    }

    public function testRefundPaymentCCFull()
    {
        $payment = $this->paymentCreditCard();
        $this->mockHttpSession($this->body_refund_full_cc);
        $refund = $this->moip->refunds()->creditCard($payment->getId());
        $this->assertNotEmpty($refund->getId());
        $this->assertNotEmpty('FULL', $refund->getType());
    }

    public function testRefundPaymentCCPartial()
    {
        $payment = $this->paymentCreditCard();
        $this->mockHttpSession($this->body_refund_partial_cc);
        $refund = $this->moip->refunds()->creditCard($payment->getId(), 5000);
        $this->assertNotEmpty($refund->getId());
        $this->assertNotEmpty('PARTIAL', $refund->getType());
    }

    public function testRefundPaymentBankAccountFull()
    {
        $payment = $this->paymentBoleto();
        $this->mockHttpSession($this->body_refund_full_bankaccount);        
        $refund = $this->moip->refunds()->bankAccount($payment->getId(), $this->bankAccount);
        $this->assertNotEmpty($refund->getId());
        $this->assertNotEmpty('FULL', $refund->getType());
    }

    public function testRefundPaymentBankAccountPartial()
    {
        $payment = $this->paymentBoleto();
        $this->mockHttpSession($this->body_refund_partial_bankaccount);        
        $refund = $this->moip->refunds();
        echo 'teste';
        var_dump($refund);
        $refund = $refund->bankAccount($payment->getId(), $this->bankAccount, 5000);
        $this->assertNotEmpty($refund->getId());
        $this->assertNotEmpty('PARTIAL', $refund->getType());
    }
}
