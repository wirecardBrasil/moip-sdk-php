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

    public function testShouldGetNotificationPreference()
    {
        $this->mockHttpSession($this->body_notification_preference);

        $notification = $this->moip->notifications()->get('NPR-N6QZE3223P98');
        $this->assertEquals('NPR-N6QZE3223P98', $notification->getId());
        $this->assertEquals('WEBHOOK', $notification->getMedia());
        $this->assertEquals('994e3ffae9214fbc806d01de2dd5d341', $notification->getToken());
        $this->assertEquals('http://requestb.in/1dhjesw1', $notification->getTarget());
        $this->assertEquals(['ORDER.*', 'PAYMENT.AUTHORIZED', 'PAYMENT.CANCELLED'], $notification->getEvents());
    }
}
