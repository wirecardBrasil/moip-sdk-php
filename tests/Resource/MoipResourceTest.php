<?php

namespace Moip\Tests\Resource;

use Moip\Resource\MoipResource;
use Moip\Resource\NotificationPreferences;
use Moip\Tests\TestCase;

class MoipResourceTest extends TestCase
{
    public function testEndpointGeneratePath()
    {
        $path = $this->moip->notifications()->generatePath('notifications', 'NPR-CQU74AQOIVCV');
        $expected = sprintf('%s/%s/%s/%s', MoipResource::VERSION, NotificationPreferences::PATH, 'notifications', 'NPR-CQU74AQOIVCV');
        $this->assertEquals($expected, $path);
    }
}
