<?php

namespace Moip\Exceptions;

use RuntimeException;

/**
 * Class UnexpectedException
 * 
 * @package \Moip\Exceptions
 */
class UnexpectedException extends RuntimeException
{
    /**
     * UnexpectedException constructor.
     */
    public function __construct($previous = null)
    {
        parent::__construct('Um erro inesperado aconteceu, por favor contate o moip', 500, $previous);
    }
}
