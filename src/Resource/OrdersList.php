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
     * Get an order list in MoIP.
     *
     * @param Pagination $pagination
     * @param Filters $filters
     * @param string $qParam Query a specific value.
     *
     * @return stdClass
     */
    public function get(Pagination $pagination = NULL, Filters $filters = NULL, $qParam = '')
    {
        if (is_null($pagination)) {
            $pagination = new Pagination();
        }
        
        $path = sprintf('/%s/%s?%s', MoipResource::VERSION, self::PATH, $pagination->__toString());
        
        if (!is_null($filters)) {
            $path = sprintf('/%s/%s?%s%s&q=%s', MoipResource::VERSION, self::PATH, $pagination->__toString(), $filters->__toString(), $qParam);
        }
        
        return $this->getByPath($path);
    }
    
    public function getOrders()
    {
        return $this->getIfSet('orders');
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
