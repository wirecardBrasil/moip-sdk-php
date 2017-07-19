<?php

namespace Moip\Helper;

class Pagination
{
    private $offset = 0;
    private $limit = 100;
    
    public function __construct($limit = null, $offset = null)
    {
        if (!empty($limit)) {
            $this->limit = $limit;
        }
        
        if (!empty($offset)) {
            $this->offset = $offset;
        }
    }

    public function getOffset()
    {
        return $this->offset;
    }
    
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }
    
    public function getLimit()
    {
        return $this->limit;
    }
    
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }
}
