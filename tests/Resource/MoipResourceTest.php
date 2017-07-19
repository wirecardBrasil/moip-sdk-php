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
        
        $testCases[] = [
            null,
            null,
            null,
            sprintf('/%s/%s?%s', MoipResource::VERSION, OrdersList::PATH, '')
        ];
        
        $filter = new Filters();
        $filter->between('value', 1000, 10000);
        
        $testCases[] = [
            new Pagination(10, 0),
            $filter,
            null,
            sprintf('/%s/%s?%s', MoipResource::VERSION, OrdersList::PATH, 'limit=10&offset=0&filters='.urlencode('value::bt(1000,10000)'))
        ];
        
        $testCases[] = [
            new Pagination(10, 0),
            $filter,
            'jose augusto',
            sprintf('/%s/%s?%s', MoipResource::VERSION, OrdersList::PATH, 'limit=10&offset=0&filters='.urlencode('value::bt(1000,10000)&q='. urlencode('jose augusto')))
        ];
        
        return $testCases;
    }
}
