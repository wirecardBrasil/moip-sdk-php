<?php

namespace Moip\Resource;

use stdClass;

class NotificationPreferencesList extends MoipResource
{
    /**
     * @const string
     */
    const PATH = 'preferences';

    public function initialize()
    {
        $this->data = new stdClass();
    }

    /**
     * Get notifications.
     *
     * @return array
     */
    public function getNotifications()
    {
        return $this->data->notifications;
    }

    /**
     * Get a notification list.
     *
     * @return stdClass
     */
    public function get()
    {
        return $this->getByPath(sprintf('/%s/%s/%s', MoipResource::VERSION, self::PATH, 'notifications'));
    }

    protected function populate(stdClass $response)
    {
        $notificationsList = clone $this;

        $notificationsList->data = new stdClass();

        $notificationsList->data->notifications = $response;

        return $notificationsList;
    }
}
