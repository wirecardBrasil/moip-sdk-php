<?php

namespace Moip\Tests\Resource;

use Moip\Helper\Filters;
use Moip\Helper\Pagination;
use Moip\Tests\TestCase;

class OrdersListTest extends TestCase
{
    public function testShouldGetOrderListNoFilter()
    {
        $this->mockHttpSession($this->body_order_list);

        $filter = new Filters();
        $filter->between('value', 1000, 10000);

        $orders = $this->moip->orders()->getList(new Pagination(10, 0), $filter, 'jose');

        $this->assertNotNull($orders->getOrders());
    }
}
