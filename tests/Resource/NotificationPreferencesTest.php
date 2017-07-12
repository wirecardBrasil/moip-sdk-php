<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

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
}
