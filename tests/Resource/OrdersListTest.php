<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

class OrdersListTest extends TestCase
{
    public function testShouldGetOrderListNoFilter()
    {
        $this->mockHttpSession($this->body_order_list);
        $orders = $this->moip->orders()->getList();
        
        $this->assertNotNull($orders->getOrders());
    }
}