<?php

namespace Moip\Exceptions;

use RuntimeException;

/**
 * Class ValidationException.
 */
class ValidationException extends RuntimeException
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var Error[]
     */
    private $errors;

    /**
     * ValidationException constructor.
     *
     * Exception thrown when the moip API returns a 4xx http code.
     * Indicates that an invalid value was passed.
     *
     * @param int     $statusCode
     * @param Error[] $errors
     */
    public function __construct($statusCode, $errors)
    {
        $this->errors = $errors;
        $this->statusCode = $statusCode;
    }

    /**
     * Returns the http status code ie.: 400.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Returns the list of errors returned by the API.
     *
     * @return Error[]
     *
     * @see \Moip\Exceptions\Error
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Convert error variables in string.
     *
     * @return string
     */
    public function __toString()
    {
        $template = "[$this->code] The following errors ocurred:\n%s";
        $temp_list = '';
        foreach ($this->errors as $error) {
            $path = $error->getPath();
            $desc = $error->getDescription();

            $temp_list .= "$path: $desc\n";
        }

        return sprintf($template, $temp_list);
    }
}
