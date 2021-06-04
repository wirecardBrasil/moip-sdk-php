<?php

namespace Moip\Helper;

/**
 * Class Filters.
 */
class Filters
{
    /**
     * @var array
     **/
    private $filters = [];

    /**
     * Set filter to compare if field is greater than value.
     *
     * @param string   $field field to setup filter.
     * @param int|Date $value value to setup filter.
     */
    public function greaterThan($field, $value)
    {
        $this->filters[] = sprintf('%s::gt(%s)', $field, $value);
    }

    /**
     * Set filter to compare if field is greater than or equal value.
     *
     * @param string   $field field to setup filter.
     * @param int|Date $value value to setup filter.
     */
    public function greaterThanOrEqual($field, $value)
    {
        $this->filters[] = sprintf('%s::ge(%s)', $field, $value);
    }

    /**
     * Set filter to compare if field is less than value.
     *
     * @param string   $field field to setup filter.
     * @param int|Date $value value to setup filter.
     */
    public function lessThan($field, $value)
    {
        $this->filters[] = sprintf('%s::lt(%s)', $field, $value);
    }

    /**
     * Set filter to compare if field is between both values.
     *
     * @param string $field  field to setup filter.
     * @param string $value1 first value to setup filter.
     * @param string $value2 second value to setup filter.
     */
    public function between($field, $value1, $value2)
    {
        $this->filters[] = sprintf('%s::bt(%s,%s)', $field, $value1, $value2);
    }

    /**
     * Set filter to compare if field is in array.
     *
     * @param string $field  field to setup filter.
     * @param array  $values value to setup filter.
     */
    public function in($field, array $values)
    {
        $this->filters[] = sprintf('%s::in(%s)', $field, implode(',', $values));
    }

    /**
     * Join filters in one string.
     *
     * @return string
     */
    public function __toString()
    {
        return implode('|', $this->filters);
    }
}
