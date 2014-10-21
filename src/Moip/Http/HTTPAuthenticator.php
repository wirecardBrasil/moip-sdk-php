<?php
namespace Moip\Http;

/**
 * Interface para definição de um autenticador HTTP.
 */
interface HTTPAuthenticator
{
    /**
     * Autentica uma requisição HTTP.
     *
     * @param HTTPRequest $httpRequest
     */
    public function authenticate(HTTPRequest $httpRequest);
}