<?php

namespace Moip\Resource;

use stdClass;

/**
 * Description of NotificationPreferences.
 */
class NotificationPreferences extends MoipResource
{
    /**
     * Path accounts API.
     *
     * @const string
     */
    const PATH = 'preferences';

    /**
     * Notification media.
     *
     * @var string
     */
    const NOTIFICATION_MEDIA = 'WEBHOOK';

    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
        $this->data->events = [];
        $this->data->media = self::NOTIFICATION_MEDIA;
    }

    /**
     * Add a new address to the account.
     *
     * @param string $event Webhook.
     *
     * @return $this
     */
    public function addEvent($event)
    {
        $this->data->events[] = $event;

        return $this;
    }

    /**
     * Set target to notification.
     *
     * @param string $target Notification URL.
     *
     * @return $this
     */
    public function setTarget($target)
    {
        $this->data->target = $target;

        return $this;
    }

    /**
     * Returns target.
     *
     * @return stdClass
     */
    public function getTarget()
    {
        return $this->data->target;
    }

    /**
     * Returns notification id.
     *
     * @return stdClass
     */
    public function getId()
    {
        return $this->data->id;
    }

    /**
     * Returns notification token.
     *
     * @return stdClass
     */
    public function getToken()
    {
        return $this->data->token;
    }

    /**
     * Create a new notification preference.
     *
     * @return \stdClass
     */
    public function create()
    {
        return $this->createResource($this->generatePath('notifications'));
    }

    /**
     * Get a notification preference.
     *
     * @param string $notification_id Moip notification id.
     *
     * @return stdClass
     */
    public function get($notification_id)
    {
        return $this->getByPath($this->generatePath('notifications', $notification_id));
    }

    /**
     * Delete.
     *
     * @param $notification_id
     *
     * @return mixed
     */
    public function delete($notification_id)
    {
        return $this->deleteByPath($this->generatePath('notifications', $notification_id));
    }

    /**
     * Mount the notification preference structure.
     *
     * @param \stdClass $response
     *
     * @return \Moip\Resource\NotificationPreferences data
     */
    protected function populate(stdClass $response)
    {
        $account = clone $this;
        $account->data->events = $this->getIfSet('events', $response);
        $account->data->target = $this->getIfSet('target', $response);
        $account->data->media = $this->getIfSet('media', $response);
        $account->data->token = $this->getIfSet('token', $response);
        $account->data->id = $this->getIfSet('id', $response);

        return $account;
    }
}
