<?php

namespace Moip\Tests\Resource;

use Moip\Tests\MoipTestCase;

/**
* class CustomerTest
*/
class CustomerTest extends MoipTestCase
{
	/**
	 * Test if const \Moip\Resource\Customer::PATH is valid.
	 */
	public function testShouldReceiveConstPathIsValid()
	{
		$path = 'customers';
		$const_path = constant('\Moip\Resource\Customer::PATH');

		$this->assertEquals($path, $const_path);
	}

	/**
	 * Test if const \Moip\Resource\Customer::ADDRESS_BILLING is valid.
	 */
	public function testShouldReceiveConstAddressBillingIsValid()
	{
		$address_billing = 'BILLING';
		$const_address_billing = constant('\Moip\Resource\Customer::ADDRESS_BILLING');

		$this->assertEquals($address_billing, $const_address_billing);
	}
}