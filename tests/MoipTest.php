<?php

namespace Moip\Tests;

use Mockery as m;

/**
 * class MoipTest.
 */
class MoipTest extends MoipTestCase
{

    /**
     * Test should return instance of \Moip\Resource\Customer.
     */
    public function testShouldReceiveInstanceOfCustomer()
    {
        $customer = new \Moip\Resource\Customer($this->moip);

        $this->assertEquals($customer, $this->moip->customers());
    }

    /**
     * Test should return instance of \Moip\Resource\Entry.
     */
    public function testShouldReceiveInstanceOfEntry()
    {
        $entry = new \Moip\Resource\Entry($this->moip);

        $this->assertEquals($entry, $this->moip->entries());
    }

    /**
     * Test should return instance of \Moip\Resource\Orders.
     */
    public function testShouldReceiveInstanceOfOrders()
    {
        $orders = new \Moip\Resource\Orders($this->moip);

        $this->assertEquals($orders, $this->moip->orders());
    }

    /**
     * Test should return instance of \Moip\Resource\Payment.
     */
    public function testShouldReceiveInstanceOfPayment()
    {
        $payment = new \Moip\Resource\Payment($this->moip);

        $this->assertEquals($payment, $this->moip->payments());
    }

    /**
     * Test should return instance of \Moip\Resource\Multiorders.
     */
    public function testShouldReceiveInstanceOfMultiorders()
    {
        $multiorders = new \Moip\Resource\Multiorders($this->moip);

        $this->assertEquals($multiorders, $this->moip->multiorders());
    }
}
