<?php

namespace Moip\Tests\Helper;

use Moip\Helper\Pagination;
use Moip\Tests\TestCase;

class PaginationTest extends TestCase
{
    public function testNoStringPagination()
    {
        $pagination = new Pagination();
        $pagination->setLimit(0);
        
        $this->assertEmpty($pagination->__toString());
    }
    
    public function testStringPaginationLimit()
    {
        $pagination = new Pagination();
        $this->assertEquals('limit=100', $pagination->__toString());
    }
    
    public function testStringPaginationComplete()
    {
        $pagination = new Pagination();
        $pagination->setOffset(20);
        $pagination->setLimit(50);
        $this->assertEquals('limit=50&offset=20', $pagination->__toString());
    }
}
