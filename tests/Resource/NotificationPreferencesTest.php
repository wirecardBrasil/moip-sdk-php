<?php

namespace Moip\Tests\Resource;

use Moip\Resource\MoipResource;
use Moip\Resource\NotificationPreferences;
use Moip\Tests\TestCase;

/**
 * Description of NotificationPreferencesTest.
 */
class NotificationPreferencesTest extends TestCase
{
    private function createNotification()
    {
        $this->mockHttpSession($this->body_notification_preference);
        $notification = $this->moip->notifications()->addEvent('ORDER.*')
            ->addEvent('PAYMENT.AUTHORIZED')
            ->setTarget('http://requestb.in/1dhjesw1')
            ->create();

        return $notification;
    }

    public function testShouldCreateNotificationPreference()
    {
        $notification = $this->createNotification();
        $this->assertNotEmpty($notification->getId());
    }

    public function testEndpointDeleteNotificationPreference()
    {
        $path = $this->moip->notifications()->generatePath('notifications', 'NPR-CQU74AQOIVCV');
        $expected = sprintf('%s/%s/%s/%s', MoipResource::VERSION, NotificationPreferences::PATH, 'notifications', 'NPR-CQU74AQOIVCV');
        $this->assertEquals($expected, $path);
    }
}
