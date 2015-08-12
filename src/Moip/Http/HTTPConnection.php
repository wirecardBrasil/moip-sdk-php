<?php

namespace Moip\Http;

use BadMethodCallException;
use InvalidArgumentException;

/**
 * Implementação de um conector HTTP.
 */
class HTTPConnection extends AbstractHttp
{
    /**
     * Porta padrão de uma conexão HTTP não segura.
     *
     * @var string
     */
    const HTTP_PORT = 80;

    /**
     * Porta padrão de uma conexão HTTP segura.
     *
     * @var string
     */
    const HTTPS_PORT = 443;

    /**
     * @var HTTPAuthenticator
     */
    protected $httpAuthenticator;

    /**
     * @var CookieManager
     */
    protected $cookieManager;

    /**
     * @var int
     */
    protected $connectionTimeout;

    /**
     * @var string
     */
    protected $hostname;

    /**
     * @var bool
     */
    protected $initialized = false;

    /**
     * @var int
     */
    protected $port;

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
     * @var bool
     */
    protected $secure;

    /**
     * @var int
     */
    protected $timeout;

    /**
     * @var string
     */
    protected static $userAgent;

    /**
     * Constroi o objeto de conexão HTTP.
     *
     * @param string $client
     */
    public function __construct($client)
    {
        if (self::$userAgent == null) {
            $locale = setlocale(LC_ALL, null);

            if (function_exists('posix_uname')) {
                $uname = posix_uname();

                self::$userAgent = sprintf('Mozilla/4.0 (compatible; %s; PHP/%s %s; %s; %s; %s)',
                                            $client, PHP_SAPI, PHP_VERSION, $uname['sysname'], $uname['machine'], $locale);
            } else {
                self::$userAgent = sprintf('Mozilla/4.0 (compatible; %s; PHP/%s %s; %s; %s)',
                                            $client, PHP_SAPI, PHP_VERSION, PHP_OS, $locale);
            }
        }
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
    public function addHeader($name, $value, $override = true)
    {
        return $this->addHeaderRequest($name, $value, $override);
    }

    /**
     * Limpa os campos de cabeçalho e de requisição definidos anteriormente.
     *
     * @see \Moip\Http\HTTPConnection::clearHeaders()
     * @see \Moip\Http\HTTPConnection::clearParameters()
     */
    public function clear()
    {
        $this->clearHeaders();
        $this->clearParameters();
    }

    /**
     * Limpa os campos de cabeçalhos definidos anteriormente.
     */
    public function clearHeaders()
    {
        $this->requestHeader = array();
    }

    /**
     * Limpa os campos definidos anteriormente.
     */
    public function clearParameters()
    {
        $this->requestParameter = array();
    }

    /**
     * Fecha a conexão.
     *
     * @throws \BadMethodCallException Se não houver uma conexão inicializada.
     */
    public function close()
    {
        $this->initialized = false;
    }

    /**
     * Verifica se existe um header e retorna o seu valor.
     * 
     * @param string $key
     * 
     * @return string
     */
    private function getHeader($key)
    {
        if (isset($this->requestHeader[$key])) {
            $header = $this->requestHeader[$key]['value'];

            unset($this->requestHeader[$key]);
        } else {
            if ($key === 'host') {
                $header = $this->getHost();
            } elseif ($key === 'accept') {
                $header = '*/*';
            } elseif ($key === 'user-agent') {
                $header = self::$userAgent;
            }
        }

        return $header;
    }

    /**
     * Executa a requisição a requisição HTTP em um caminho utilizando um
     * método específico.
     *
     * @param string $path   Caminho da requisição.
     * @param string $method Método da requisição.
     *
     * @return string Resposta HTTP.
     *
     * @throws \BadMethodCallException Se não houver uma conexão inicializada ou se o objeto de requisição não for válido.
     */
    public function execute($path = '/', $method = HTTPRequest::GET)
    {
        $request = $this->newRequest();

        if ($request instanceof HTTPRequest) {
            $request->addRequestHeader('Host', $this->getHeader('host'));
            $request->addRequestHeader('Accept', $this->getHeader('accept'));
            $request->addRequestHeader('User-Agent', $this->getHeader('user-agent'));

            if ($this->httpAuthenticator != null) {
                $request->authenticate($this->httpAuthenticator);
            }

            foreach ($this->requestHeader as $header) {
                $request->addRequestHeader($header['name'], $header['value']);
            }

            $cookieManager = $this->getCookieManager();

            if ($cookieManager != null) {
                $cookies = $cookieManager->getCookie($this->getHostName(),
                    $this->isSecure(), $path);

                if (isset($this->requestHeader['cookie'])) {
                    $buffer = $this->requestHeader['cookie']['value'].'; '.
                            $cookies;
                } else {
                    $buffer = $cookies;
                }

                $request->addRequestHeader('Cookie', $buffer);
            }

            foreach ($this->requestParameter as $name => $value) {
                $request->setParameter($name, $value);
            }

            $request->setRequestBody($this->requestBody);

            if ($path == null || !is_string($path) || empty($path)) {
                $path = '/';
            } elseif (substr($path, 0, 1) != '/') {
                $path = '/'.$path;
            }

            if ($this->timeout != null) {
                $request->setTimeout($this->timeout);
            }

            if ($this->connectionTimeout != null) {
                $request->setConnectionTimeout($this->connectionTimeout);
            }

            $request->open($this);
            $request->execute($path, $method);

            return $request->getResponse();
        } else {
            throw new BadMethodCallException('Invalid request object.');
        }
    }

    /**
     * Recupera o timeout de conexão.
     *
     * @return int
     */
    public function getConnectionTimeout()
    {
        return $this->connectionTimeout;
    }

    /**
     * Recupera o gerenciador de Cookies.
     *
     * @return \Moip\Http\CookieManager
     */
    public function getCookieManager()
    {
        return $this->cookieManager;
    }

    /**
     * Recupera o host da conexão.
     *
     * @return string
     *
     * @throws \BadMethodCallException Se a conexão não tiver sido inicializada.
     */
    public function getHost()
    {
        if ($this->initialized) {
            $hostname = $this->getHostName();

            if (($this->secure && $this->port != self::HTTPS_PORT) ||
                 (!$this->secure && $this->port != self::HTTP_PORT)) {
                $hostname .= ':'.$this->port;
            }

            return $hostname;
        }

        throw new BadMethodCallException('Connection not initialized');
    }

    /**
     * Recupera o nome do host.
     *
     * @return string
     *
     * @throws \BadMethodCallException Se não houver uma conexão inicializada.
     */
    public function getHostName()
    {
        if ($this->initialized) {
            return $this->hostname;
        }

        throw new BadMethodCallException('Connection not initialized');
    }

    /**
     * Recupera a porta que será utilizada na conexão.
     *
     * @return int
     *
     * @throws \BadMethodCallException Se não houver uma conexão inicializada.
     */
    public function getPort()
    {
        if ($this->initialized) {
            return $this->port;
        }

        throw new BadMethodCallException('Connection not initialized');
    }

    /**
     * Recupera o timeout.
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Recupera a URI que será utilizada na conexão.
     *
     * @return string
     *
     * @throws \BadMethodCallException Se não houver uma conexão inicializada.
     */
    public function getURI()
    {
        if ($this->initialized) {
            return sprintf('%s://%s', $this->isSecure() ? 'https' : 'http',
                $this->getHost());
        }

        throw new BadMethodCallException('Connection not initialized');
    }

    /**
     * Inicializa a conexão HTTP.
     *
     * @param string $hostname          Servidor que receberá a requisição.
     * @param bool   $secure            Indica se a conexão será segura (https).
     * @param int    $port              Porta da requisição.
     * @param int    $connectionTimeout Timeout de conexão em segundos.
     * @param int    $timeout           Timeout de espera em segundos.
     */
    public function initialize($hostname, $secure = false,
                            $port = self::HTTP_PORT, $connectionTimeout = 0, $timeout = 0)
    {
        if ($this->initialized) {
            $this->close();
        }

        $this->initialized = true;
        $this->hostname = $hostname;
        $this->secure = $secure === true;

        if (func_num_args() == 2) {
            $this->port = $this->secure ? self::HTTPS_PORT : self::HTTP_PORT;
        } else {
            $this->port = (int) $port;
        }

        $this->connectionTimeout = (int) $connectionTimeout;
        $this->timeout = (int) $timeout;
    }

    /**
     * Verifica se é uma conexão segura.
     *
     * @return bool
     */
    public function isSecure()
    {
        return $this->secure === true;
    }

    /**
     * Cria uma instância de um objeto de requisição HTTP.
     *
     * @return \Moip\Http\HTTPRequest
     */
    public function newRequest()
    {
        return new CURL();
    }

    /**
     * Define um autenticador HTTP.
     *
     * @param \Moip\Http\HTTPAuthenticator $httpAuthenticator
     */
    public function setAuthenticator(HTTPAuthenticator $httpAuthenticator)
    {
        $this->httpAuthenticator = $httpAuthenticator;
    }

    /**
     * Define o timeout de conexão.
     *
     * @param int $connectionTimeout
     *
     * @throws \InvalidArgumentException Se $connectionTimeout não for um inteiro.
     */
    public function setConnectionTimeout($connectionTimeout)
    {
        if (is_integer($connectionTimeout)) {
            $this->connectionTimeout = $connectionTimeout;
        } else {
            throw new InvalidArgumentException(
                'Connection timeout must be specified in seconds.');
        }
    }

    /**
     * Define um gerenciador de cookies para essa conexão.
     *
     * @param \Moip\Http\CookieManager $cookieManager
     */
    public function setCookieManager(CookieManager $cookieManager)
    {
        $this->cookieManager = $cookieManager;
    }

    /**
     * Define um parâmetro que será enviado com a requisição, um parâmetro é um
     * par nome-valor que será enviado como uma query string.
     *
     * @param string $name  Nome do parâmetro.
     * @param string $value Valor do parâmetro.
     *
     * @throws \InvalidArgumentException Se o nome ou o valor do campo não forem valores scalar.
     */
    public function setParam($name, $value = null)
    {
        if (is_scalar($name) && (is_scalar($value) || is_null($value))) {
            $this->requestParameter[$name] = $value;
        } else {
            throw new InvalidArgumentException('Name and value MUST be scalar');
        }
    }

    /**
     * Define o corpo da requisição.
     *
     * @param string $requestBody
     */
    public function setRequestBody($requestBody)
    {
        $this->requestBody = $requestBody;
    }

    /**
     * Define o timeout.
     *
     * @param int $timeout
     *
     * @throws \InvalidArgumentException Se $timeout não for um inteiro.
     */
    public function setTimeout($timeout)
    {
        if (is_integer($timeout)) {
            $this->timeout = $timeout;
        } else {
            throw new InvalidArgumentException(
                'Timeout must be specified in seconds.');
        }
    }
}
