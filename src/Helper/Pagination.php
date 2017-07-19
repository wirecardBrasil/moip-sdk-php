<?php

namespace Moip\Helper;

class Pagination
{
    private $offset = 0;
    private $limit = 100;

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
    
    public function __toString()
    {
        if ($this->getLimit() == 0) {
            return '';
        }
            
        if ($this->getOffset() <= 0) {
            return sprintf('limit=%d', $this->getLimit()); 
        }
        
        return sprintf('limit=%d&offset=%d', $this->getLimit(), $this->getOffset());
    }
}
