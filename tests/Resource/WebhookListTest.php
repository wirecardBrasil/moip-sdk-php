<?php

namespace Moip\Tests\Resource;

use Moip\Helper\Pagination;
use Moip\Tests\TestCase;

class WebhookListTest extends TestCase
{
    public function testGetWebhookNoFilter()
    {
        $this->mockHttpSession($this->body_list_webhook_no_filter);
        $webhooks = $this->moip->webhooks()->get();

        $this->assertNotEmpty($webhooks->getWebhooks());
    }

    public function testGetWebhookPagination()
    {
        $this->mockHttpSession($this->body_list_webhook_pagination);
        $webhooks = $this->moip->webhooks()->get(new Pagination(100, 0));

        $this->assertNotEmpty($webhooks->getWebhooks());
    }

    public function testGetWebhookParams()
    {
        $this->mockHttpSession($this->body_list_webhook_all_filters);
        $webhooks = $this->moip->webhooks()->get(new Pagination(10, 0), 'ORD-EE5XP23RMLSS', 'ORDER.PAID');

        $this->assertNotEmpty($webhooks->getWebhooks());
    }
}
