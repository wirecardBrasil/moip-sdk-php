<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

class EscrowTest extends TestCase
{
    public function testShouldReleaseEscrow()
    {
        $this->mockHttpSession($this->body_order);
        $order = $this->createOrder()->create();
        $cc = '5555666677778884';
        $this->mockHttpSession($this->body_cc_pay_pci_escrow);
        $payment = $order->payments()
            ->setCreditCard(5, 2018, $cc, 123, $this->createCustomer(), false)
            ->setEscrow('teste de descricao')
            ->execute();
        $this->mockHttpSession($this->body_release_escrow);
        $escrow = $payment->escrows()->release();
        $this->assertEquals('RELEASED', $escrow->getStatus());
    }
}
