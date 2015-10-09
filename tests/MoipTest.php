<?php

namespace Moip\Tests;

use Moip\Tests\MoipTestCase;

/**
* class MoipTest
*/
class MoipTest extends MoipTestCase
{
	/**
	 * Test if endpoint production is valid.
	 */
	public function testShouldReceiveEndpointProductionIsValid()
	{
		$endpoint_production = 'api.moip.com.br';
		$const_endpoint_production = constant('\Moip\Moip::ENDPOINT_PRODUCTION');

		$this->assertEquals($endpoint_production, $const_endpoint_production);
	}

	/**
	 * Test if endpoint sandbox is valid.
	 */
	public function testShouldReceiveSandboxProductionIsValid()
	{
		$endpoint_sandbox = 'sandbox.moip.com.br';
		$const_endpoint_sandbox = constant('\Moip\Moip::ENDPOINT_SANDBOX');

		$this->assertEquals($endpoint_sandbox, $const_endpoint_sandbox);
	}

	/**
	 * Test if const CLIENT is valid.
	 */
	public function testShouldReceiveClientIsValid()
	{
		$client = 'Moip SDK';
		$const_client = constant('\Moip\Moip::CLIENT');

		$this->assertEquals($client, $const_client);
	}

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
}