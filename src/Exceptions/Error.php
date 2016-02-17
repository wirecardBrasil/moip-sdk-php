<?php

namespace Moip\Exceptions;

/**
 * Class Error.
 *
 * Represents an error returned by the API.
 */
class Error
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $description;

    /**
     * Error constructor.
     *
     * Represents an error return by the API. Commonly used by {@see \Moip\Exceptions\ValidationException}
     *
     * @param string $code        unique error identifier.
     * @param string $path        represents the field where the error ocurred.
     * @param string $description error description.
     */
    public function __construct($code, $path, $description)
    {
        $this->code = $code;
        $this->path = $path;
        $this->description = $description;
    }

    /**
     * Returns the unique alphanumeric identifier of the error, ie.: "API-1".
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns the dotted string representing the field where the error ocurred, ie.: "customer.birthDate".
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the error description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}