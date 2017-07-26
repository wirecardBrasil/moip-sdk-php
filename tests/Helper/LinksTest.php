<?php

namespace Moip\Tests\Helper;

use Moip\Tests\TestCase;

class LinksTest extends TestCase
{
    public function testGetLinkWithoutCheckout()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $this->mockHttpSession($this->body_billet_pay);
        $payment = $order->payments()->setBoleto(new \DateTime('today +1day'), 'http://dev.moip.com.br/images/logo-header-moip.png')->execute();

        $this->assertEquals('https://checkout-sandbox.moip.com.br/boleto/PAY-XNVIBO5MIQ9S', $payment->getLinks()->getLink('payBoleto'));
    }

    public function testGetCheckoutLink()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();

        $this->assertEquals('https://checkout-sandbox.moip.com.br/creditcard/ORD-HG479ZEIB7LV', $order->getLinks()->getCheckout('payCreditCard'));
    }

    public function testGetAllCheckoutLinks()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();

        $this->assertNotEmpty($order->getLinks()->getAllCheckout());
    }
}
