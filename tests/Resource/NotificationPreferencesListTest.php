<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

class NotificationPreferencesListTest extends TestCase
{
    public function testShouldGetNotificationsList()
    {
        $this->mockHttpSession($this->body_notification_list);

        $notifications = $this->moip->notifications()->getList();

        $this->assertNotNull($notifications->getNotifications());
    }
}
