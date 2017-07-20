<?php

namespace Moip\Helper;

/**
 * Class Pagination.
 */
class Pagination
{
    /**
     * @var int
     **/
    private $offset = 0;

    /**
     * @var int
     **/
    private $limit = 100;

    /**
     * Pagination constructor.
     *
     * @param int $limit
     * @param int $offset
     */
    public function __construct($limit = null, $offset = null)
    {
        if (!empty($limit)) {
            $this->limit = $limit;
        }

        if (!empty($offset)) {
            $this->offset = $offset;
        }
    }

    /**
     * Get offset.
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Set offset.
     *
     * @param int $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * Get limit.
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set limit.
     *
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }
}
