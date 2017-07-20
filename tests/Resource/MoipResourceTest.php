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
    public function testEndpointGeneratePath()
    {
        $path = $this->moip->notifications()->generatePath('notifications', 'NPR-CQU74AQOIVCV');
        $expected = sprintf('%s/%s/%s/%s', MoipResource::VERSION, NotificationPreferences::PATH, 'notifications', 'NPR-CQU74AQOIVCV');
        $this->assertEquals($expected, $path);
    }

    /**
     * @dataProvider provider
     */
    public function testEndpointGenerateListPath($pagination, $filter, $qParam, $expected)
    {
        $path = $this->moip->orders()->generateListPath($pagination, $filter, $qParam);
        $this->assertEquals($expected, $path);
    }

    public function provider()
    {
        $testCases = [];

        $filter = new Filters();
        $filter->between('amount', 1000, 10000);
        $filter->in('status', ['NOT_PAID', 'WAITING']);
        $pagination = new Pagination(10, 0);

        $testCases[] = [null, null, null, sprintf('/%s/%s?%s', MoipResource::VERSION, OrdersList::PATH, '')];
        $testCases[] = [$pagination, $filter, null, sprintf('/%s/%s?%s', MoipResource::VERSION, OrdersList::PATH, 'limit=10&offset=0&filters='.urlencode('amount::bt(1000,10000)|status::in(NOT_PAID,WAITING)'))];
        $testCases[] = [$pagination, $filter, 'jose augusto', sprintf('/%s/%s?%s', MoipResource::VERSION, OrdersList::PATH, 'limit=10&offset=0&filters='.urlencode('amount::bt(1000,10000)|status::in(NOT_PAID,WAITING)').'&q='.urlencode('jose augusto'))];

        return $testCases;
    }
}
