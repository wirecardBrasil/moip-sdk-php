<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

class RefundTest extends TestCase
{
    public function testRefundBankAccountFull()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $this->mockHttpSession($this->body_billet_pay);
        $payment = $order->payments()->get('PAY-N2TXFLV4PP7Y');
        $this->mockHttpSession($this->body_refund_full_bankaccount);
        $refund = $payment->refunds()
            ->bankAccountFull('CHECKING', '001', '1584', '9', '00210169', '6', $order->getCustomer());
        $this->assertNotEmpty($refund->getId());
    }

    public function testRefundBankAccountPartial()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $this->mockHttpSession($this->body_billet_pay);
        $payment = $order->payments()->get('PAY-N2TXFLV4PP7Y');
        $this->mockHttpSession($this->body_refund_partial_bankaccount);
        $refund = $payment->refunds()
            ->bankAccountPartial(20000, 'SAVING', '001', '1584', '9', '00210169', '6', $order->getCustomer());
        $this->assertNotEmpty($refund->getId());
    }
}
