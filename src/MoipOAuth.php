<?php

namespace Moip;

use Sostheblack\Http\HTTPRequest;

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
     * @param \Sostheblack\Http\HTTPRequest $httpRequest
     */
    public function authenticate(HTTPRequest $httpRequest)
    {
        $httpRequest->addRequestHeader('Authorization', 'OAuth '.$this->accessToken);
    }
}
