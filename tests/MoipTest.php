<?php

namespace Moip\Test;

use Mockery as m;
use Moip\Http\HTTPConnection;
use Moip\Moip;
use Moip\MoipAuthentication;
use Moip\Resource\Customer;
use Moip\Resource\Entry;
use Moip\Resource\Multiorders;
use Moip\Resource\Orders;
use Moip\Resource\Payment;
use PHPUnit_Framework_TestCase as TestCase;

/**
* class \Moip\Test\MoipTest
*/
class MoipTest extends TestCase
{
    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Testing assert equals const \Moip\Moip::ENDPOINT_PRODUCTION.
     */
	public function testShouldConstEndpoinProductionIsValid()
	{
		$const_endpoint_production = Moip::ENDPOINT_PRODUCTION;
		$endpoint_production = 'api.moip.com.br';

		$this->assertEquals($endpoint_production, $const_endpoint_production);
	}

    /**
     * Testing assert equals const \Moip\Moip::ENDPOINT_SANDBOX.
     */
	public function testShouldConstEndpoinSandboxIsValid()
	{
		$const_endpoint_sandbox = Moip::ENDPOINT_SANDBOX;
		$endpoint_sandbox = 'sandbox.moip.com.br';

		$this->assertEquals($endpoint_sandbox, $const_endpoint_sandbox);
	}

    /**
     * Testing assert equals const \Moip\Moip::CLIENT.
     */
	public function testShouldConstClientIsValid()
	{
		$const_client = Moip::CLIENT;
		$client = 'Moip SDK';

		$this->assertEquals('Moip SDK', constant('\Moip\Moip::CLIENT'));
	}

	/**
	 * Testing a new instance of \Moip\Resource\Customer class.
	 */
	public function testShouldCustomersIsValid()
	{
		$moip = new Moip(m::mock(MoipAuthentication::Class));
		$customer = new Customer($moip);

		$this->assertEquals($customer, $moip->customers());	
	}

	/**
	 * Testing a new instance of \Moip\Resource\Entry class.
	 */
	public function testShouldEntrysIsValid()
	{
		$moip = new Moip(m::mock(MoipAuthentication::Class));
		$entry = new Entry($moip);

		$this->assertEquals($entry, $moip->entries());
	}

	/**
	 * Testing a new instance of \Moip\Resource\Orders class.
	 */
	public function testShouldOrdersIsValid()
	{
		$moip = new Moip(m::mock(MoipAuthentication::Class));
		$orders = new Orders($moip);

		$this->assertEquals($orders, $moip->orders());
	}

	/**
	 * Testing a new instance of \Moip\Resource\Payment class.
	 */
	public function testShouldPaymentsIsValid()
	{
		$moip = new Moip(m::mock(MoipAuthentication::Class));
		$payment = new Payment($moip);

		$this->assertEquals($payment, $moip->payments());
	}

	/**
	 * Testing a new instance of \Moip\Resource\Multiorders class.
	 */
	public function testShouldMultiordersIsValid()
	{
		$moip = new Moip(m::mock(MoipAuthentication::Class));
		$multiorders = new Multiorders($moip);

		$this->assertEquals($multiorders, $moip->multiorders());
	}
}