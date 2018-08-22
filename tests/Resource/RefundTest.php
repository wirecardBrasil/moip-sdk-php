<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

class RefundTest extends TestCase
{
    public function testRefundOrderCreditCardFull()
    {
        $order = $this->paymentCreditCard(false);

        $this->mockHttpSession($this->body_order_refund_full_cc);
        $refund = $order->refunds()->creditCardFull();

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('FULL', $refund->getType());
    }

    public function testRefundOrderCreditCardPartial()
    {
        $order = $this->paymentCreditCard(false);

        $this->mockHttpSession($this->body_order_refund_partial_cc);
        $refund = $order->refunds()->creditCardPartial(5000);

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('PARTIAL', $refund->getType());
    }

    public function testRefundOrderBankAccountFull()
    {
        $order = $this->paymentBoleto(false);

        $this->mockHttpSession($this->body_order_refund_full_bankaccount);
        $refund = $order->refunds()
            ->bankAccountFull('CHECKING', '001', '1584', '9', '00210169', '6', $order->getCustomer());

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('FULL', $refund->getType());
    }

    public function testRefundOrderBankAccountPartial()
    {
        $order = $this->paymentBoleto(false);

        $this->mockHttpSession($this->body_payment_refund_partial_bankaccount);
        $refund = $order->refunds()
            ->bankAccountPartial(20000, 'SAVING', '001', '1584', '9', '00210169', '6', $order->getCustomer());

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('PARTIAL', $refund->getType());
    }

    public function testRefundPaymentCreditCardFull()
    {
        $payment = $this->paymentCreditCard();

        $this->mockHttpSession($this->body_payment_refund_full_cc);
        $refund = $payment->refunds()->creditCardFull();

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('FULL', $refund->getType());
    }

    public function testRefundPaymentCreditCardPartial()
    {
        $payment = $this->paymentCreditCard();

        $this->mockHttpSession($this->body_payment_refund_partial_cc);
        $refund = $payment->refunds()->creditCardPartial(5000);

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('PARTIAL', $refund->getType());
    }

    public function testRefundPaymentBankAccountFull()
    {
        $payment = $this->paymentBoleto();

        $this->mockHttpSession($this->body_payment_refund_full_bankaccount);
        $refund = $payment->refunds()
            ->bankAccountFull('CHECKING', '001', '1584', '9', '00210169', '6', $payment->getOrder()->getCustomer());

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('FULL', $refund->getType());
    }

    public function testRefundPaymentBankAccountPartial()
    {
        $payment = $this->paymentBoleto();

        $this->mockHttpSession($this->body_payment_refund_partial_bankaccount);
        $refund = $payment->refunds()
            ->bankAccountPartial(20000, 'SAVING', '001', '1584', '9', '00210169', '6', $payment->getOrder()->getCustomer());

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('PARTIAL', $refund->getType());
    }

    public function testRefundCCFullWithResourceId()
    {
        $payment = $this->paymentCreditCard();

        $this->mockHttpSession($this->body_payment_refund_full_cc);
        $refund = $this->moip->refunds()->creditCard($payment->getId());

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('FULL', $refund->getType());
    }

    public function testRefundCCPartialWithResourceId()
    {
        $payment = $this->paymentCreditCard();

        $this->mockHttpSession($this->body_payment_refund_partial_cc);
        $refund = $this->moip->refunds()->creditCard($payment->getId(), 5000);

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('PARTIAL', $refund->getType());
    }

    public function testRefundBankAccountFullWithResourceId()
    {
        $payment = $this->paymentBoleto();

        $this->mockHttpSession($this->body_payment_refund_full_bankaccount);
        $refund = $this->moip->refunds()->bankAccount($payment->getId(), $this->bankAccount());

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('FULL', $refund->getType());
    }

    public function testRefundBankAccountPartialWithResourceId()
    {
        $payment = $this->paymentBoleto();

        $this->mockHttpSession($this->body_payment_refund_partial_bankaccount);
        $refund = $this->moip->refunds()->bankAccount($payment->getId(), $this->bankAccount(), 5000);

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('PARTIAL', $refund->getType());
    }

    public function testRefundOrderCCFullWithResourceId()
    {
        $order = $this->paymentCreditCard(false);

        $this->mockHttpSession($this->body_order_refund_full_cc);
        $refund = $this->moip->refunds()->creditCard($order->getId());

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('FULL', $refund->getType());
    }

    public function testRefundOrderCCPartialWithResourceId()
    {
        $order = $this->paymentCreditCard(false);

        $this->mockHttpSession($this->body_order_refund_partial_cc);
        $refund = $this->moip->refunds()->creditCard($order->getId(), 5000);

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('PARTIAL', $refund->getType());
    }

    public function testRefundOrderBankAccountFullWithResourceId()
    {
        $order = $this->paymentBoleto(false);

        $this->mockHttpSession($this->body_order_refund_full_bankaccount);
        $refund = $this->moip->refunds()->bankAccount($order->getId(), $this->bankAccount());

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('FULL', $refund->getType());
    }

    public function testRefundOrderBankAccountPartialWithResourceId()
    {
        $order = $this->paymentBoleto(false);

        $this->mockHttpSession($this->body_order_refund_partial_bankaccount);
        $refund = $this->moip->refunds()->bankAccount($order->getId(), $this->bankAccount(), 5000);

        $this->assertNotEmpty($refund->getId());
        $this->assertEquals('PARTIAL', $refund->getType());
    }

    public function testShouldGetRefund()
    {
        $payment = $this->paymentCreditCard();

        $this->mockHttpSession($this->body_payment_refund_full_cc);
        $refund_id = $payment->refunds()->creditCardFull()->getId();

        $this->mockHttpSession($this->body_payment_refund_full_cc);
        $refund = $payment->refunds()->get($refund_id);

        $this->assertEquals($refund_id, $refund->getId());
        $this->assertEquals('FULL', $refund->getType());
        $this->assertEquals('COMPLETED', $refund->getStatus());
    }

    private function bankAccount()
    {
        return $this->moip->bankaccount()
            ->setType('CHECKING')
            ->setBankNumber('237')
            ->setAgencyNumber('12346')
            ->setAgencyCheckNumber('0')
            ->setAccountNumber('12345679')
            ->setAccountCheckNumber('7')
            ->setHolder('Jose Silva', '22222222222', 'CPF');
    }

    private function paymentBoleto($returnPayment = true)
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $this->mockHttpSession($this->body_billet_pay);
        $payment = $order->payments()
            ->setBoleto(new \DateTime('today +1day'), 'http://dev.moip.com.br/images/logo-header-moip.png')
            ->execute();
        $this->mockHttpSession('');
        $payment->authorize();

        return $returnPayment ? $payment : $order;
    }

    private function paymentCreditCard($returnPayment = true)
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $this->mockHttpSession($this->body_cc_pay_pci);
        $payment = ($order->payments()->setCreditCard(5, 2018, '5555666677778884', 123, $this->createHolder())->execute());

        return $returnPayment ? $payment : $order;
    }
}
