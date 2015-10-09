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
}