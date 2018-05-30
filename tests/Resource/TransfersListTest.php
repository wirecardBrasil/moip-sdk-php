<?php

namespace Moip\Tests\Resource;

use Moip\Helper\Pagination;
use Moip\Tests\TestCase;

class TransfersListTest extends TestCase
{
    public function testShouldGetTransfersList()
    {
        $this->mockHttpSession($this->body_transfers_list);

        $transfers = $this->moip->transfers()->getList(new Pagination(10, 0));

        $this->assertNotNull($transfers->getTransfers());
    }
}
