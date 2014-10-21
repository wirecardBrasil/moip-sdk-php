<?php
namespace Moip;

use Moip\Http\HTTPRequest;

class MoipOAuth implements MoipAuthentication
{
    /**
     * @var string
     */
    private $accessToken;
    
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }
    
    /**
     * Autentica uma requisição HTTP.
     *
     * @param HTTPRequest $httpRequest
     */
    public function authenticate(HTTPRequest $httpRequest)
    {
        $httpRequest->addRequestHeader('Authorization',
                                       'OAuth ' . $this->accessToken);
    }
}