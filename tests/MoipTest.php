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
	 * Instance of \Moip\Moip.
	 *
	 * @var \Moip\Moip
	 **/
	private $moip;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
    	$this->moip = $this->getMoipIntance();
    }

    /**
     * Create a new \Moip\Moip instance.
     * 
     * @return \Moip\Moip
     */
	private function getMoipIntance()
	{
		return new Moip(m::mock(MoipAuthentication::Class));
	}

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
		$this->assertEquals('api.moip.com.br', constant('\Moip\Moip::ENDPOINT_PRODUCTION'));
	}

    /**
     * Testing assert equals const \Moip\Moip::ENDPOINT_SANDBOX.
     */
	public function testShouldConstEndpoinSandboxIsValid()
	{
		$this->assertEquals('sandbox.moip.com.br', constant('\Moip\Moip::ENDPOINT_SANDBOX'));
	}

    /**
     * Testing assert equals const \Moip\Moip::CLIENT.
     */
	public function testShouldConstClientIsValid()
	{
		$this->assertEquals('Moip SDK', constant('\Moip\Moip::CLIENT'));
	}

	/**
	 * Testing a new instance of \Moip\Resource\Customer class.
	 */
	public function testShouldCustomersIsValid()
	{
		$this->assertEquals(new Customer($this->moip), $this->moip->customers());	
	}

	/**
	 * Testing a new instance of \Moip\Resource\Entry class.
	 */
	public function testShouldEntrysIsValid()
	{
		$this->assertEquals(new Entry($this->moip), $this->moip->entries());	
	}

	/**
	 * Testing a new instance of \Moip\Resource\Orders class.
	 */
	public function testShouldOrdersIsValid()
	{
		$this->assertEquals(new Orders($this->moip), $this->moip->orders());	
	}

	/**
	 * Testing a new instance of \Moip\Resource\Payment class.
	 */
	public function testShouldPaymentsIsValid()
	{
		$this->assertEquals(new Payment($this->moip), $this->moip->payments());	
	}

	/**
	 * Testing a new instance of \Moip\Resource\Multiorders class.
	 */
	public function testShouldMultiordersIsValid()
	{
		$this->assertEquals(new Multiorders($this->moip), $this->moip->multiorders());	
	}
}