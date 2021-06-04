<?php

namespace Moip\Resource;

use Moip\Helper\Filters;
use Moip\Helper\Pagination;
use stdClass;

class OrdersList extends MoipResource
{
    /**
     * @const string
     */
    const PATH = 'orders';

    /**
     * @const string
     */
    const CREATED_AT = 'createdAt';

    /**
     * @const string
     */
    const PAYMENT_METHOD = 'paymentMethod';

    /**
     * @const string
     */
    const VALUE = 'value';

    /**
     * @const string
     */
    const STATUS = 'status';

    public function initialize()
    {
        $this->data = new stdClass();
        $this->data->orders = [];
    }

    /**
     * Get orders.
     *
     * @return array
     */
    public function getOrders()
    {
        return $this->getIfSet('orders');
    }

    /**
     * Get an order list in MoIP.
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
        $ordersList = clone $this;
        $ordersList->data = new stdClass();

        $ordersList->data->orders = $response->orders;

        $ordersList->data->summary = $response->summary;
        $ordersList->_links = $response->_links;

        return $ordersList;
    }
}
