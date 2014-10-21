<?php
namespace Moip;

use Moip\Http\HTTPRequest;

class MoipBasicAuth implements MoipAuthentication
{
    private $token;
    private $key;
    
    public function __construct($token, $key)
    {
        $this->token = $token;
        $this->key = $key;
    }
    
    /**
     * Autentica uma requisição HTTP.
     *
     * @param HTTPRequest $httpRequest
     */
    public function authenticate(HTTPRequest $httpRequest)
    {
        $httpRequest->addRequestHeader('Authorization',
                                       'Basic ' . base64_encode($this->token . ':' . $this->key));
    }
}