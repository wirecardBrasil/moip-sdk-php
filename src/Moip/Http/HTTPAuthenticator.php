<?php

namespace Moip\Http;

use Moip\Http\HTTPRequest;

/**
 * Interface para definição de um autenticador HTTP.
 */
interface HTTPAuthenticator
{
    /**
     * Autentica uma requisição HTTP.
     *
     * @param \Moip\Http\HTTPRequest $httpRequest
     */
    public function authenticate(HTTPRequest $httpRequest);
}
