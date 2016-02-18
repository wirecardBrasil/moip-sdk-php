<?php

namespace Moip\Exceptions;

class UnexpectedException extends \RuntimeException
{
    public function __construct($previous = null)
    {
        parent::__construct('Um erro inesperado aconteceu, por favor contate o moip', 500, $previous);
    }
}
