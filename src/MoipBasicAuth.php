<?php

namespace Moip;

use Sostheblack\Http\HTTPRequest;

class MoipBasicAuth implements MoipAuthentication
{
    /**
     * Token.
     *
     * @var string
     */
    private $token;

    /**
     * Access key.
     *
     * @var string
     */
    private $key;

    /**
     * Create a new MoipBasic instance.
     *
     * @param string $token
     * @param string $key
     */
    public function __construct($token, $key)
    {
        $this->token = $token;
        $this->key = $key;
    }

    /**
     * Authentication of a HTTP request.
     *
     * @param \Sostheblack\Http\HTTPRequest $httpRequest
     */
    public function authenticate(HTTPRequest $httpRequest)
    {
        $httpRequest->addRequestHeader('Authorization', 'Basic '.base64_encode($this->token.':'.$this->key));
    }
}
