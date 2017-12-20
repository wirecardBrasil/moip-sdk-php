<?php

namespace Moip\Tests\Resource;

use Moip\Resource\Orders;
use Moip\Tests\TestCase;

class OrdersTest extends TestCase
{
    /**
     * Send http request.
     *
     * @param \Moip\Resource\Orders $order
     * @param string                $body
     *
     * @return \Moip\Resource\Orders
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
     * @const string
     */
    public function testAssertConstPath()
    {
        $this->assertEquals('orders', Orders::PATH);
    }

    /**
     * Tests if the primary receiver constant has the correct value.
     *
     * @const string
     */
    public function testAssertConstReceiverTypePrimary()
    {
        $this->assertEquals('PRIMARY', Orders::RECEIVER_TYPE_PRIMARY);
    }

    /**
     * Tests if the secondary receiver constant has the correct value.
     *
     * @const string
     */
    public function testAssertConstReceiverTypeSecpndary()
    {
        $this->assertEquals('SECONDARY', Orders::RECEIVER_TYPE_SECONDARY);
    }

    /**
     * Tests the currency in the order creation response.
     *
     * @const string
     */
    public function testAssertConstAmountCurrency()
    {
        $this->assertEquals('BRL', Orders::AMOUNT_CURRENCY);
    }

    /**
     * MoipTest creating an order.
     */
    public function testCreateOrder()
    {
        $order_created = $this->executeOrder();

        $this->assertEquals($this->last_ord_id, $order_created->getOwnId());
        $this->assertEquals('CREATED', $order_created->getStatus());
        $this->assertEquals(new \DateTime('2016-02-19T12:24:55.849-02'), $order_created->getCreatedAt());
        $this->assertEquals(new \DateTime('2016-02-19T12:24:55.849-02'), $order_created->getUpdatedAt());
    }

    /**
     * Tests if created items price are correct.
     */
    public function testItems()
    {
        $order_created = $this->executeOrder();
        $itens = $order_created->getItemIterator()->getArrayCopy();
        $this->assertEquals(100000, $itens[0]->price);
        $this->assertEquals(990, $itens[1]->price);
    }

    /**
     * Test if created order shipping address is correct.
     */
    public function testShippingAddress()
    {
        $order_created = $this->executeOrder();

        $this->assertEquals('01234000', $order_created->getCustomer()->getShippingAddress()->{'zipCode'});
        $this->assertEquals('Avenida Faria Lima', $order_created->getCustomer()->getShippingAddress()->{'street'});
        $this->assertEquals('2927', $order_created->getCustomer()->getShippingAddress()->{'streetNumber'});
        $this->assertEquals('8', $order_created->getCustomer()->getShippingAddress()->{'complement'});
        $this->assertEquals('Sao Paulo', $order_created->getCustomer()->getShippingAddress()->{'city'});
        $this->assertEquals('Itaim', $order_created->getCustomer()->getShippingAddress()->{'district'});
        $this->assertEquals('SP', $order_created->getCustomer()->getShippingAddress()->{'state'});
        $this->assertEquals('BRA', $order_created->getCustomer()->getShippingAddress()->{'country'});
    }

    /**
     *MoipTest if the total is correct.
     */
    public function testTotal()
    {
        $order = $this->executeOrder();

        $total = $order->getSubtotalItems() + $order->getSubtotalShipping() + $order->getSubtotalAddition() - $order->getSubtotalDiscount();
        $this->assertEquals($total, $order->getAmountTotal());
    }

    /**
     * MoipTest if the total is equal to the expected total.
     */
    public function testTotalConstant()
    {
        $order = $this->executeOrder();
        $expected = (100000 + 2 * 990 + 1490) - 1000;
        $total_calculated = $order->getSubtotalItems() + $order->getSubtotalShipping() + $order->getSubtotalAddition() - $order->getSubtotalDiscount();

        $this->assertEquals($expected, $total_calculated);
        $this->assertEquals($expected, $order->getAmountTotal());
    }

    /**
     * MoipTest if order is created with installment preferences.
     */
    public function testCreateOrderWithInstallmentPreferences()
    {
        $quantity = [1, 6];
        $discount = 0;
        $additional = 100;
        $order = $this->createOrder()->addInstallmentCheckoutPreferences($quantity, $discount, $additional);
        $returned_order = $this->executeOrder($order);
        $this->assertNotEmpty($returned_order->getId());
        $this->assertEquals([1, 6], $returned_order->getCheckoutPreferences()->installments[0]->quantity);
    }

    public function testCreateOrderAddingReceiverNoAmount()
    {
        $order = $this->createOrder()->addReceiver('MPA-7ED9D2D0BC81', 'PRIMARY');
        $receivers = $order->getReceiverIterator();
        $this->assertEquals('MPA-7ED9D2D0BC81', $receivers[0]->moipAccount->id);
    }

    public function testCreateOrderAddingReceiverAmountFixed()
    {
        $order = $this->createOrder()->addReceiver('MPA-7ED9D2D0BC81', 'PRIMARY', 30000);
        $receivers = $order->getReceiverIterator();
        $this->assertEquals(30000, $receivers[0]->amount->fixed);
    }

    public function testCreateOrderAddingReceiverAmountPercentual()
    {
        $order = $this->createOrder()->addReceiver('MPA-7ED9D2D0BC81', 'PRIMARY', null, 40);
        $receivers = $order->getReceiverIterator();
        $this->assertEquals(40, $receivers[0]->amount->percentual);
    }

    public function testCreateOrderAddingReceiverFeePayor()
    {
        $order = $this->createOrder()->addReceiver('MPA-7ED9D2D0BC81', 'PRIMARY', null, 40, true);
        $receivers = $order->getReceiverIterator();
        $this->assertEquals(40, $receivers[0]->amount->percentual);
        $this->assertTrue($receivers[0]->feePayor);
        $order2 = $this->createOrder()->addReceiver('MPA-7ED9D2D0BC81', 'PRIMARY', 30000, null, true);
        $receivers2 = $order2->getReceiverIterator();
        $this->assertEquals(30000, $receivers2[0]->amount->fixed);
        $this->assertTrue($receivers2[0]->feePayor);
    }
}
