<?php

namespace Moip\Tests\Resource;

use Moip\Helper\Filters;
use Moip\Helper\Pagination;
use Moip\Resource\MoipResource;
use Moip\Resource\NotificationPreferences;
use Moip\Resource\OrdersList;
use Moip\Tests\TestCase;

class MoipResourceTest extends TestCase
{
    private $filter;
    private $pagination;

    public function __construct()
    {
        parent::__construct();
        $this->filter = new Filters();
        $this->filter->between('amount', 1000, 10000);
        $this->filter->in('status', ['NOT_PAID', 'WAITING']);
        $this->pagination = new Pagination(10, 0);
    }

    public function testEndpointGeneratePath()
    {
        $path = $this->moip->notifications()->generatePath('notifications', 'NPR-CQU74AQOIVCV');
        $expected = sprintf('%s/%s/%s/%s', MoipResource::VERSION, NotificationPreferences::PATH, 'notifications', 'NPR-CQU74AQOIVCV');
        $this->assertEquals($expected, $path);
    }

    public function testEndpointGenerateListPathNoParams()
    {
        $path = $this->moip->orders()->generateListPath();
        $this->assertEquals(sprintf('/%s/%s', MoipResource::VERSION, OrdersList::PATH), $path);
    }

    public function testEndpointGenerateListPaginationFilter()
    {
        $path = $this->moip->orders()->generateListPath($this->pagination, $this->filter, null);
        $this->assertEquals(sprintf('/%s/%s?%s', MoipResource::VERSION, OrdersList::PATH, 'limit=10&offset=0&filters='.urlencode('amount::bt(1000,10000)|status::in(NOT_PAID,WAITING)')), $path);
    }

    public function testEndpointGenerateListAllParams()
    {
        $path = $this->moip->orders()->generateListPath($this->pagination, $this->filter, ['q' => 'jose augusto']);
        $this->assertEquals(sprintf('/%s/%s?%s', MoipResource::VERSION, OrdersList::PATH, 'limit=10&offset=0&filters='.urlencode('amount::bt(1000,10000)|status::in(NOT_PAID,WAITING)').'&q='.urlencode('jose augusto')), $path);
    }
}
