<?php

namespace Moip\Resource;

use stdClass;

class EntriesList extends MoipResource
{
    /**
     * @const string
     */
    const PATH = 'entries';

    public function initialize()
    {
        $this->data = new stdClass();
        $this->data->summary = new stdClass();
        $this->data->_links = new stdClass();
        $this->data->entries = [];
    }

    /**
     * Get entries.
     *
     * @return array
     */
    public function getEntries()
    {
        return $this->getIfSet('entries');
    }

    /**
     * Get a entries list.
     *
     * @return stdClass
     */
    public function get()
    {
        return $this->getByPath(sprintf('/%s/%s', MoipResource::VERSION, self::PATH), [], true);
    }

    protected function populate(stdClass $response)
    {
        $entriesList = clone $this;

        $entriesList->data->summary->amount = $response->summary->amount;

        $entriesList->data->summary->count = $response->summary->count;

        $entriesList->data->_links->previous = new stdClass();
        $entriesList->data->_links->previous->href = $response->_links->previous->href;

        $entriesList->data->_links->next = new stdClass();
        $entriesList->data->_links->next->href = $response->_links->next->href;

        $entriesList->data->entries = $response->entries;

        return $entriesList;
    }
}
