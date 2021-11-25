<?php

namespace Moip\Resource;

use Moip\Helper\Pagination;
use stdClass;

/**
 * Class Webhook.
 */
class WebhookList extends MoipResource
{
    /**
     * Path accounts API.
     *
     * @const string
     */
    const PATH = 'webhooks';

    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
        $this->data->webhooks = [];
    }

    /**
     * Get webhooks.
     *
     * @return array
     */
    public function getWebhooks()
    {
        return $this->getIfSet('webhooks');
    }

    /**
     * Get a webhook.
     *
     * @param Pagination $pagination
     * @param string     $resource_id
     * @param string     $event
     *
     * @return stdClass
     */
    public function get(Pagination $pagination = null, $resource_id = null, $event = null)
    {
        $params = [];

        if (!is_null($resource_id)) {
            $params['resourceId'] = $resource_id;
        }

        if (!is_null($event)) {
            $params['event'] = $event;
        }

        return $this->getByPath($this->generateListPath($pagination, null, $params));
    }

    /**
     * Mount structure of Webhook List.
     *
     * @param \stdClass $response
     *
     * @return \Moip\Resource\WebhookList Webhook List
     */
    protected function populate(stdClass $response)
    {
        $webhookList = clone $this;
        $webhookList->data = new stdClass();

        $webhookList->data->webhooks = $response->webhooks;

        return $webhookList;
    }
}
