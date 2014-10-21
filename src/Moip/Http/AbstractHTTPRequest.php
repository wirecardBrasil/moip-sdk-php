<?php
namespace Moip\Http;

use \InvalidArgumentException;

/**
 * Base para facilitar a implementação da interface HTTPRequest para uma
 * requisição HTTP que utiliza cURL.
 */
abstract class AbstractHTTPRequest implements HTTPRequest
{
    /**
     * @var string
     */
    protected $httpResponse;

    /**
     * @var boolean
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
     * @see HTTPRequest::addRequestHeader()
     */
    public function addRequestHeader($name, $value, $override = true)
    {
        if (is_scalar($name) && is_scalar($value)) {
            $key = strtolower($name);

            if ($override === true || !isset($this->requestHeader[$key])) {
                $this->requestHeader[$key] = array('name' => $name,
                    'value' => $value
                );

                return true;
            }

            return false;
        } else {
            throw new InvalidArgumentException('Name and value must be scalar');
        }
    }

    /**
     * Autentica uma requisição HTTP.
     *
     * @param HTTPAuthenticator $authenticator
     * @see HTTPRequest::authenticate()
     */
    public function authenticate(HTTPAuthenticator $authenticator)
    {
        $authenticator->authenticate($this);
    }

    /**
     * @see HTTPRequest::getResponse()
     */
    public function getResponse()
    {
        return $this->httpResponse;
    }

    /**
     * Define um parâmetro que será enviado com a requisição, um parâmetro é um
     * par nome-valor que será enviado como uma query string.
     *
     * @param string $name
     *            Nome do parâmetro.
     * @param string $value
     *            Valor do parâmetro.
     * @throws InvalidArgumentException Se o nome ou o valor do campo não forem
     *         valores scalar.
     * @see HTTPRequest::setParameter()
     */
    public function setParameter($name, $value)
    {
        $this->requestParameter[$name] = $value;
    }

    /**
     * @see HTTPRequest::setRequestBody()
     */
    public function setRequestBody($requestBody)
    {
        $this->requestBody = $requestBody;
    }
}