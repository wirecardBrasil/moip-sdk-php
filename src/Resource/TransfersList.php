<?php

namespace Moip\Resource;

use Moip\Helper\Filters;
use Moip\Helper\Pagination;
use stdClass;

class TransfersList extends MoipResource
{
    /**
     * Path bank accounts API.
     *
     * @const string
     */
    const PATH = 'transfers';

    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
        $this->data->transfers = [];
    }

    /**
     * Get transfers.
     *
     * @return array
     */
    public function getTransfers()
    {
        return $this->getIfSet('transfers');
    }

    /**
     * Get transfer list.
     *
     * @param Pagination $pagination
     * @param Filters    $filters
     * @param string     $qParam     Query a specific value.
     *
     * @return stdClass
     */
    public function get(Pagination $pagination = null, Filters $filters = null, $qParam = '')
    {
        return $this->getByPath($this->generateListPath($pagination, $filters, ['q' => $qParam]));
    }

    protected function populate(stdClass $response)
    {
        $transfersList = clone $this;
        $transfersList->data = new stdClass();

        $transfersList->data->transfers = $response->transfers;

        $transfersList->data->summary = $response->summary;
        $transfersList->_links = $response->_links;

        return $transfersList;
    }
}
