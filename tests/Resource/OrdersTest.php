<?php

namespace Moip\Tests\Resource;

use Moip\Tests\MoipTestCase;
use Moip\Resource\Orders;

class OrdersTest extends MoipTestCase
{
    /**
     * Send http request.
     *
     * @param Orders $order
     * @param string $body
     *
     * @return Orders
     */
    private function executeOrder(Orders $order = null, $body = null)
    {
        if (empty($body)) {
            $body = $this->body_order;
        }
        if (empty($order)) {
            $order = $this->createOrder();
        }
        $this->mockHttpSession($body);

        return $order->create();
    }

    /**
     * Test creating an order.
     */
    public function testCreateOrder()
    {
        $order_created = $this->executeOrder();

        $this->assertEquals('ORD-HG479ZEIB7LV', $order_created->getId());
        $this->assertEquals('CREATED', $order_created->getStatus());
    }

    /**
     * Teste if created itens price is correct.
     */
    public function testItens()
    {
        $order_created = $this->executeOrder();
        $itens = $order_created->getItemIterator()->getArrayCopy();
        $this->assertEquals(100000, $itens[0]->price);
        $this->assertEquals(990, $itens[1]->price);
    }

    /**
     *Test if the total is correct.
     */
    public function testTotal()
    {
        $order = $this->executeOrder();

        $total = $order->getSubtotalItems() + $order->getSubtotalShipping() + $order->getSubtotalAddition() - $order->getSubtotalDiscount();
        $this->assertEquals($total, $order->getAmountTotal());
    }

    /**
     * Test if the total is equal to the expected total.
     */
    public function testTotalConstant()
    {
        $order = $this->executeOrder();
        $expected = (100000 + 2 * 990 + 1490) - 1000;
        $total_calculated = $total = $order->getSubtotalItems() + $order->getSubtotalShipping() + $order->getSubtotalAddition() - $order->getSubtotalDiscount();

        $this->assertEquals($expected, $total_calculated);
        $this->assertEquals($expected, $order->getAmountTotal());
    }
}
