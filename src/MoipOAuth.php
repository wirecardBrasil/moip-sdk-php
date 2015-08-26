<?php

namespace Moip;

use Moip\Http\HTTPRequest;

class MoipOAuth implements MoipAuthentication
{
    /**
     * Access Token.
     *
     * @var string
     */
    private $accessToken;

    /**
     * Create a new MoipOAuth instance.
     *
     * @param string $accessToken
     */
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Authentication of a HTTP request.
     *
     * @param \Moip\Http\HTTPRequest $httpRequest
     */
    public function authenticate(HTTPRequest $httpRequest)
    {
        $httpRequest->addRequestHeader('Authorization', 'OAuth '.$this->accessToken);
    }
}
