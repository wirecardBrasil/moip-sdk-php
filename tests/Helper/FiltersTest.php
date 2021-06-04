<?php

namespace Moip\Tests\Helper;

use Moip\Helper\Filters;
use Moip\Resource\OrdersList;
use Moip\Tests\TestCase;

class FiltersTest extends TestCase
{
    public function testGreaterThanFilter()
    {
        $filter = new Filters();
        $filter->greaterThan(OrdersList::CREATED_AT, '2017-08-17');
        $this->assertEquals(OrdersList::CREATED_AT.'::gt(2017-08-17)', $filter->__toString());
    }

    public function testGreaterEqualFilter()
    {
        $filter = new Filters();
        $filter->greaterThanOrEqual(OrdersList::CREATED_AT, '2017-08-17');
        $this->assertEquals('createdAt::ge(2017-08-17)', $filter->__toString());
    }

    public function testLessThanFilter()
    {
        $filter = new Filters();
        $filter->lessThan(OrdersList::VALUE, 100000);
        $this->assertEquals(OrdersList::VALUE.'::lt(100000)', $filter->__toString());
    }

    public function testInFilter()
    {
        $filter = new Filters();
        $filter->in(OrdersList::PAYMENT_METHOD, ['BOLETO', 'DEBIT_CARD', 'ONLINE_BANK_DEBIT']);
        $this->assertEquals(OrdersList::PAYMENT_METHOD.'::in(BOLETO,DEBIT_CARD,ONLINE_BANK_DEBIT)', $filter->__toString());
    }

    public function testBetweenFilter()
    {
        $filter = new Filters();
        $filter->between(OrdersList::CREATED_AT, '2017-08-10', '2017-08-17');
        $this->assertEquals(OrdersList::CREATED_AT.'::bt(2017-08-10,2017-08-17)', $filter->__toString());
    }

    public function testMultipleFilter()
    {
        $filters = new Filters();
        $filters->greaterThanOrEqual(OrdersList::CREATED_AT, '2017-08-17');
        $filters->in(OrdersList::PAYMENT_METHOD, ['BOLETO', 'DEBIT_CARD']);
        $filters->lessThan(OrdersList::VALUE, 100000);
        $this->assertEquals(OrdersList::CREATED_AT.'::ge(2017-08-17)|'.OrdersList::PAYMENT_METHOD.'::in(BOLETO,DEBIT_CARD)|'.OrdersList::VALUE.'::lt(100000)', $filters->__toString());
    }
}
