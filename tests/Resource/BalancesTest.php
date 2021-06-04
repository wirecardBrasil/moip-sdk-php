<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

class BalancesTest extends TestCase
{
    public function testShouldGetBalances()
    {
        $this->mockHttpSession($this->body_balances);

        $balances = $this->moip->balances()->get();
        $current = $balances->getCurrent();
        $future = $balances->getFuture();
        $unavailable = $balances->getUnavailable();

        $this->assertNotEmpty($current);
        $this->assertNotEmpty($future);
        $this->assertNotEmpty($unavailable);
        $this->assertEquals(44592168, $current[0]->amount);
    }
}
