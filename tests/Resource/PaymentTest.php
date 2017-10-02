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
        $payment = $order->payments()->setCreditCard(5, 2018, $cc, 123, $this->createCustomer())->execute();
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
        $payment = $order->payments()->setCreditCard(5, 2018, $cc, 123, $this->createCustomer(), false)->execute();
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
            ->setCreditCard(5, 2018, $cc, 123, $this->createCustomer(), false)
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
        $payment = $order->multipayments()->setCreditCard(5, 2018, $cc, 123, $this->createCustomer())->execute();

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
            ->setCreditCard(5, 2018, '5555666677778884', 123, $this->createCustomer(), false)
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
            ->setCreditCard(5, 2018, '4012001037141112', 123, $this->createCustomer())
            ->setDelayCapture(true)
            ->execute();

        $this->mockHttpSession($this->body_capture_multipay);
        $captured_payment = $payment->capture();

        $this->assertEquals('AUTHORIZED', $captured_payment->getStatus());
    }
}
