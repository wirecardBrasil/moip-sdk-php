<?php

namespace Moip\Helper;

class Filters
{
    private $filters = [];
    
    public function greaterThan($field, $value)
    {
        $this->filters[] = sprintf('%s::gt(%s)', $field, $value);
    }
    
    public function greaterThanOrEqual($field, $value)
    {
        $this->filters[] = sprintf('%s::ge(%s)', $field, $value);
    }

    public function lessThan($field, $value)
    {
        $this->filters[] = sprintf('%s::lt(%s)', $field, $value);
    }

    public function between($field, $value1, $value2)
    {
        $this->filters[] = sprintf('%s::bt(%s,%s)', $field, $value1, $value2);
    }
    
    public function in($field, array $values)
    {
        $this->filters[] = sprintf('%s::in(%s)', $field, implode(',', $values));
    }
    
    public function __toString()
    {
        return implode('|', $this->filters);
    }
}
