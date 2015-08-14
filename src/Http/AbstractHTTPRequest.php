<?php

namespace Moip\Http;

/**
 * Base para facilitar a implementação da interface HTTPRequest para uma
 * requisição HTTP que utiliza cURL.
 */
abstract class AbstractHTTPRequest extends AbstractHttp implements HTTPRequest
{
    /**
     * @var string
     */
    protected $httpResponse;

    /**
     * @var bool
     */
    protected $openned = false;

    /**
     * @var string
     */
    protected $requestBody;

    /**
     * @var array
     */
    protected $requestHeader = array();

    /**
     * @var array
     */
    protected $requestParameter = array();

    /**
     * Destroi o objeto e fecha a requisição se estiver aberta.
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Adiciona um campo de cabeçalho para ser enviado com a requisição.
     *
     * @param string $name     Nome do campo de cabeçalho.
     * @param string $value    Valor do campo de cabeçalho.
     * @param bool   $override Indica se o campo deverá ser sobrescrito caso já tenha sido definido.
     *
     * @return bool
     *
     * @throws \InvalidArgumentException Se o nome ou o valor do campo não forem valores scalar.
     * 
     * @see \Moip\Http\HTTPRequest::addRequestHeader()
     */
    public function addRequestHeader($name, $value, $override = true)
    {
        return $this->addHeaderRequest($name, $value, $override);
    }

    /**
     * Autentica uma requisição HTTP.
     *
     * @param \Moip\Http\HTTPAuthenticator $authenticator
     *
     * @see \Moip\Http\HTTPRequest::authenticate()
     */
    public function authenticate(HTTPAuthenticator $authenticator)
    {
        $authenticator->authenticate($this);
    }

    /**
     * Abre a requisição.
     *
     * 
     * @see \Moip\Http\HTTPRequest::getResponse()
     */
    public function getResponse()
    {
        return $this->httpResponse;
    }

    /**
     * Define um parâmetro que será enviado com a requisição, um parâmetro é um
     * par nome-valor que será enviado como uma query string.
     *
     * @param string $name  Nome do parâmetro.
     * @param string $value Valor do parâmetro.
     *
     * @throws \InvalidArgumentException Se o nome ou o valor do campo não forem valores scalar.
     *
     * @see \Moip\Http\HTTPRequest::setParameter()
     */
    public function setParameter($name, $value)
    {
        $this->requestParameter[$name] = $value;
    }

    /**
     * @see \Moip\Http\HTTPRequest::setRequestBody()
     *
     * @param string $requestBody
     */
    public function setRequestBody($requestBody)
    {
        $this->requestBody = $requestBody;
    }
}
