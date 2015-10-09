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
}