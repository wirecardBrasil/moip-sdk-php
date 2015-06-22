<?php

namespace Moip\Http;

use RuntimeException;
use UnexpectedValueException;

/**
 * Requisição HTTP cURL.
 * Implementação da interface HTTPRequest para uma
 * requisição HTTP que utiliza cURL.
 */
class CURL extends AbstractHTTPRequest
{
    /**
     * @var resource
     */
    private $curlResource;

    /**
     * @var HTTPConnection
     */
    private $httpConnection;

    /**
     * @see HTTPRequest::close()
     */
    public function close()
    {
        if ($this->openned) {
            curl_close($this->curlResource);
            $this->openned = false;
        }
    }

    /**
     * @see HTTPRequest::execute()
     */
    public function execute($path = '/', $method = HTTPRequest::GET)
    {
        $targetURL = $this->httpConnection->getURI().$path;
        $hasParameters = count($this->requestParameter) > 0;
        $query = $hasParameters ? http_build_query($this->requestParameter) : null;

        switch ($method) {
            case HTTPRequest::PUT :
            case HTTPRequest::POST :
                if ($method != HTTPRequest::POST) {
                    curl_setopt($this->curlResource, CURLOPT_CUSTOMREQUEST,
                        $method);
                } else {
                    curl_setopt($this->curlResource, CURLOPT_POST, 1);
                }

                if (empty($this->requestBody)) {
                    curl_setopt($this->curlResource, CURLOPT_POSTFIELDS, $query);
                } else {
                    if ($hasParameters) {
                        $targetURL .= '?'.$query;
                    }

                    curl_setopt($this->curlResource, CURLOPT_POSTFIELDS,
                        $this->requestBody);
                }

                curl_setopt($this->curlResource, CURLOPT_URL, $targetURL);

                break;
            case HTTPRequest::DELETE :
            case HTTPRequest::HEAD :
            case HTTPRequest::OPTIONS :
            case HTTPRequest::TRACE :
                curl_setopt($this->curlResource, CURLOPT_CUSTOMREQUEST, $method);
            case HTTPRequest::GET :
                if ($hasParameters) {
                    $targetURL .= '?'.$query;
                }

                curl_setopt($this->curlResource, CURLOPT_URL, $targetURL);

                break;
            default :
                throw new UnexpectedValueException('Unknown method.');
        }

        $resp = curl_exec($this->curlResource);
        $errno = curl_errno($this->curlResource);
        $error = curl_error($this->curlResource);

        if ($errno != 0) {
            throw new RuntimeException($error, $errno);
        }

        $this->httpResponse = new HTTPResponse();
        $this->httpResponse->setRawResponse($resp);

        if ($this->httpResponse->hasResponseHeader('Set-Cookie')) {
            $cookieManager = $this->httpConnection->getCookieManager();

            if ($cookieManager != null) {
                $cookieManager->setCookie(
                    $this->httpResponse->getHeader('Set-Cookie'),
                    $this->httpConnection->getHostName());
            }
        }

        $statusCode = $this->httpResponse->getStatusCode();

        return $statusCode < 400;
    }

    /**
     * @see HTTPRequest::open()
     */
    public function open(HTTPConnection $httpConnection)
    {
        if (function_exists('curl_init')) {
            /*
             * Fechamos uma conexão existente antes de abrir uma nova
             */
            $this->close();

            $curl = curl_init();

            /*
             * Verificamos se o recurso CURL foi criado com êxito
             */
            if (is_resource($curl)) {
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // FIXME
                curl_setopt($curl, CURLOPT_HEADER, true);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLINFO_HEADER_OUT, true);

                if (($timeout = $httpConnection->getTimeout()) != null) {
                    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
                }

                if (($connectionTimeout = $httpConnection->getConnectionTimeout()) !=
                     null) {
                    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT,
                        $connectionTimeout);
                }

                $headers = array();

                foreach ($this->requestHeader as $header) {
                    $headers[] = sprintf('%s: %s', $header['name'],
                        $header['value']);
                }

                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                $this->curlResource = $curl;
                $this->httpConnection = $httpConnection;
                $this->openned = true;
            } else {
                throw new RuntimeException('cURL failed to start');
            }
        } else {
            throw new RuntimeException('cURL not found.');
        }
    }
}
