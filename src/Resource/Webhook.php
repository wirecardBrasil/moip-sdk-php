<?php

namespace Moip\Resource;

use stdClass;

class Webhook extends MoipResource
{
    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
    }

    /**
     * Get webhook id.
     *
     * @return string The webhook id.
     */
    public function getId()
    {
        return $this->getIfSet('id');
    }

    /**
     * Get resource id.
     *
     * @return string The webhook id.
     */
    public function getResourceId()
    {
        return $this->getIfSet('resourceId');
    }

    /**
     * Get event.
     *
     * @return string event.
     */
    public function getEvent()
    {
        return $this->getIfSet('event');
    }

    /**
     * Get url.
     *
     * @return string url.
     */
    public function getUrl()
    {
        return $this->getIfSet('url');
    }

    /**
     * Get webhook status.
     *
     * @return string webhook status.
     */
    public function getStatus()
    {
        return $this->getIfSet('status');
    }

    /**
     * Mount structure of Webhook.
     *
     * @param \stdClass $response
     *
     * @return \Moip\Resource\Webhook Webhook
     */
    protected function populate(stdClass $response)
    {
        $webhook = clone $this;
        $webhook->data = new stdClass();

        $webhook->data->id = $response->id;
        $webhook->data->event = $response->event;
        $webhook->data->url = $response->url;
        $webhook->data->resourceId = $response->resourceId;
        $webhook->data->status = $response->status;

        return $webhook;
    }
}
