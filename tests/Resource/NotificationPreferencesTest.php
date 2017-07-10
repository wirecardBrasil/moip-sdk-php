<?php

namespace Moip\Tests\Resource;

use Moip\Resource\NotificationPreferences;
use Moip\Tests\TestCase;

/**
 * Description of NotificationPreferencesTest.
 */
class NotificationPreferencesTest extends TestCase
{
    public function testShouldCreateNotificationPreference()
    {
        $this->mockHttpSession($this->body_notification_preference);
        $notification = $this->moip->notifications()->addEvent('ORDER.*')
            ->addEvent('PAYMENT.AUTHORIZED')
            ->setTarget('http://requestb.in/1dhjesw1')
            ->create();
        $this->assertNotEmpty($notification->getId());
    }
}
