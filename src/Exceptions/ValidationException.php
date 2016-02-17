<?php

namespace Moip\Exceptions;

class ValidationException extends \RuntimeException
{
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $statusMessage;

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
     * @param string  $statusCode
     * @param int     $statusMessage
     * @param Error[] $errors
     */
    public function __construct($statusCode, $statusMessage, $errors)
    {
        $this->errors = $errors;
        $this->statusCode = $statusCode;
        $this->statusMessage = $statusMessage;
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
     * Returns the http status code description: ie.: 'Bad Request'.
     *
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->statusMessage;
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

    public function __toString()
    {
        $template = "[$this->statusMessage] The following errors ocurred:\n%s";
        $temp_list = "";
        foreach ($this->errors as $error){
            $path = $error->getPath();
            $desc = $error->getDescription();

            $temp_list .= "$path: $desc\n";
        }

        return sprintf($template, $temp_list);
    }
}