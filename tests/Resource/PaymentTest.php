<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

class PaymentTest extends TestCase
{
    //todo: credit card hash

    /**
     * MoipTest creating a credit card payment, passing all credit card data.
     */
    public function testCreditCardPCI()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $this->mockHttpSession($this->body_cc_pay_pci);
        $cc = '5555666677778884';
        $payment = $order->payments()->setCreditCard(5, 2018, $cc, 123, $this->createHolder())->execute();
        $this->assertNotEmpty($payment->getFundingInstrument()->creditCard);
        $first6 = $payment->getFundingInstrument()->creditCard->first6;
        $last4 = $payment->getFundingInstrument()->creditCard->last4;
        $this->assertEquals($first6, substr($cc, 0, 6));
        $this->assertEquals($last4, substr($cc, -4));
    }

    /**
     * MoipTest creating a billet payment.
     */
    public function testBillet()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $this->mockHttpSession($this->body_billet_pay);
        $payment = $order->payments()->setBoleto(new \DateTime('today +1day'), 'http://dev.moip.com.br/images/logo-header-moip.png')->execute();
        $this->assertNotEmpty($payment->getFundingInstrument()->boleto);
    }

    public function testCreditCardPCIStore()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $cc = '5555666677778884';
        $this->mockHttpSession($this->body_cc_pay_pci_store);
        $payment = $order->payments()->setCreditCard(5, 2018, $cc, 123, $this->createHolder(), false)->execute();
        $this->assertFalse($payment->getFundingInstrument()->creditCard->store);
        $this->assertNotEmpty($payment->getId());
    }

    public function testShouldCreateEscrowPaymentWithCreditCard()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $cc = '5555666677778884';
        $this->mockHttpSession($this->body_cc_pay_pci_escrow);
        $payment = $order->payments()
            ->setCreditCard(5, 2018, $cc, 123, $this->createHolder(), false)
            ->setEscrow('teste de descricao')
            ->execute();
        $this->assertEquals('teste de descricao', $payment->getEscrow()->description);
    }

    /**
     * MoipTest creating a credit card multipayment, passing all credit card data.
     */
    public function testMultipaymentCreditCardPCI()
    {
        $this->mockHttpSession($this->body_multiorder);
        $order = $this->createMultiorder()->create();
        $this->mockHttpSession($this->body_cc_multipay);
        $cc = '4012001037141112';
        $payment = $order->multipayments()->setCreditCard(5, 2018, $cc, 123, $this->createHolder())->execute();

        $first6 = $payment->getPayments()[0]->fundingInstrument->creditCard->first6;
        $last4 = $payment->getPayments()[0]->fundingInstrument->creditCard->last4;
        $this->assertEquals($first6, substr($cc, 0, 6));
        $this->assertEquals($last4, substr($cc, -4));
    }

    /**
     * MoipTest creating a billet multipayment.
     */
    public function testMultipaymentBillet()
    {
        $this->mockHttpSession($this->body_multiorder);
        $order = $this->createMultiorder()->create();
        $this->mockHttpSession($this->body_billet_multipay);
        $payment = $order->multipayments()->setBoleto(new \DateTime('today +1day'), 'http://dev.moip.com.br/images/logo-header-moip.png')->execute();
        $this->assertNotEmpty($payment->getFundingInstrument()->boleto);
    }

    public function testCapturePreAuthorizedPayment()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $this->mockHttpSession($this->body_cc_delay_capture);
        $payment = $order->payments()
            ->setCreditCard(5, 2018, '5555666677778884', 123, $this->createHolder(), false)
            ->setDelayCapture(true)
            ->execute();

        $this->mockHttpSession($this->body_capture_pay);
        $captured_payment = $payment->capture();

        $this->assertEquals('AUTHORIZED', $captured_payment->getStatus());
    }

    public function testCapturePreAuthorizedMultiPayment()
    {
        $this->mockHttpSession($this->body_multiorder);
        $order = $this->createMultiorder()->create();
        $this->mockHttpSession($this->body_cc_multipay);
        $payment = $order->multipayments()
            ->setCreditCard(5, 2018, '4012001037141112', 123, $this->createHolder())
            ->setDelayCapture(true)
            ->execute();

        $this->mockHttpSession($this->body_capture_multipay);
        $captured_payment = $payment->capture();

        $this->assertEquals('AUTHORIZED', $captured_payment->getStatus());
    }

    public function testCancelPreAuthorizedMultiPayment()
    {
        $this->mockHttpSession($this->body_multiorder);
        $order = $this->createMultiorder()->create();
        $this->mockHttpSession($this->body_cc_multipay);
        $payment = $order->multipayments()
            ->setCreditCard(5, 2018, '4012001037141112', 123, $this->createHolder())
            ->setDelayCapture(true)
            ->execute();

        $this->mockHttpSession($this->body_cancel_multipay);
        $cancelled_payment = $payment->cancel();

        $this->assertEquals('CANCELLED', $cancelled_payment->getStatus());
    }

    public function testCancelPreAuthorizedPayment()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $this->mockHttpSession($this->body_cc_delay_capture);
        $payment = $order->payments()
            ->setCreditCard(5, 2018, '5555666677778884', 123, $this->createHolder(), false)
            ->setDelayCapture(true)
            ->execute();

        $this->mockHttpSession($this->body_cancel_pay);
        $cancelled_payment = $payment->cancel();

        $this->assertEquals('CANCELLED', $cancelled_payment->getStatus());
    }

    public function testGetPayment()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $this->mockHttpSession($this->body_cc_pay_pci);
        $payment = $order->payments()->setCreditCard(5, 2018, '5555666677778884', 123, $this->createHolder())->execute();

        $this->mockHttpSession($this->body_get_pay);
        $payment_get = $this->moip->payments()->get($payment->getId());

        $this->assertEquals($payment_get->getAmount()->total, 102470);
        $this->assertEquals($payment_get->getFundingInstrument()->method, 'CREDIT_CARD');
        $this->assertEquals($payment_get->getInstallmentCount(), 1);
    }

    public function testGetMultiPayment()
    {
        $this->mockHttpSession($this->body_multiorder);
        $order = $this->createMultiorder()->create();
        $this->mockHttpSession($this->body_cc_multipay);
        $payment = $order->multipayments()->setCreditCard(5, 2018, '4012001037141112', 123, $this->createHolder())->execute();

        $this->mockHttpSession($this->body_get_multipay);
        $payment_get = $this->moip->payments()->get($payment->getId());

        $this->assertEquals($payment_get->getAmount()->total, 77000);
        $this->assertNotNull($payment_get->getPayments());
    }
}
