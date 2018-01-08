<?php

namespace Moip\Resource;

use Moip\Helper\Filters;
use Moip\Helper\Pagination;
use stdClass;

class CustomerList extends MoipResource
{
    /**
     * @const string
     */
    const PATH = 'customers';

    /**
     * @const string
     */
    const CREATED_AT = 'createdAt';

    public function initialize()
    {
        $this->data = new stdClass();
        $this->data->customers = [];
    }

    /**
     * Get customers.
     *
     * @return array
     */
    public function getCustomers()
    {
        return $this->getIfSet('customers');
    }

    /**
     * Get an customer list in MoIP.
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
        $customerList = clone $this;
        $customerList->data = new stdClass();

        $customerList->data->customers = $response->customers;

        return $customerList;
    }
}
