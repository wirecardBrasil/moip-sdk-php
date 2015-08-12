<?php

namespace Moip\Http;

use ArrayIterator;
use RuntimeException;

/**
 * Gerenciador de Cookies HTTP.
 * Implementação da interface CookieManager para
 * criação de um gerenciador de cookies que armazena os cookies em um arquivo
 * em disco.
 */
class HTTPCookieManager implements CookieManager
{
    /**
     * @var string
     */
    private $cookieFile;

    /**
     * @var array
     */
    private $cookies = array();

    /**
     * Constroi o gerenciador de cookies que grava as informações em um arquivo.
     *
     * @param string $dirname
     *                        Diretório onde os cookies serão gravados, caso
     *                        não informado o diretório temporário do sistema será
     *                        utilizado.
     */
    public function __construct($dirname = null)
    {
        if ($dirname == null) {
            $dirname = sys_get_temp_dir();
        }

        if (is_readable($dirname) && is_writable($dirname)) {
            $cookieFile = realpath($dirname).'/cookie.jar';

            if (!is_file($cookieFile)) {
                touch($cookieFile);
            } else {
                $cookieManager = unserialize(file_get_contents($cookieFile));

                if ($cookieManager instanceof self) {
                    $this->cookies = $cookieManager->cookies;
                }
            }

            $this->cookieFile = $cookieFile;
        } else {
            throw new RuntimeException('Permission denied at '.$dirname);
        }
    }

    /**
     * Destroi o objeto e salva os cookies armazenados.
     */
    public function __destruct()
    {
        if ($this->cookieFile != null) {
            file_put_contents($this->cookieFile, serialize($this));
        }
    }

    /**
     * @see CookieManager::addCookie()
     */
    public function addCookie(Cookie $cookie)
    {
        $cookieDomain = $cookie->getDomain();

        if (!isset($this->cookies[$cookieDomain])) {
            $this->cookies[$cookieDomain] = array();
        }

        $this->cookies[$cookieDomain][] = $cookie;
    }

    /**
     * @see CookieManager::getCookie()
     */
    public function getCookie($domain, $secure, $path)
    {
        return implode('; ', $this->getCookieArray($domain, $secure, $path));
    }

    /**
     * @param string $domain
     * @param bool   $secure
     * @param string $path
     */
    private function getCookieArray($domain, $secure, $path)
    {
        $cookies = array();
        $secure = $secure === true;

        if (isset($this->cookies[$domain])) {
            foreach ($this->cookies[$domain] as $cookie) {
                if ($cookie->isSecure() == $secure && $cookie->getPath() == $path) {
                    $cookies[] = $cookie;
                }
            }
        }

        return $cookies;
    }

    /**
     * @see CookieManager::getCookieIterator()
     */
    public function getCookieIterator($domain, $secure, $path)
    {
        return new ArrayIterator($this->getCookieArray($domain, $secure, $path));
    }

    /**
     * @see CookieManager::setCookie()
     */
    public function setCookie($setCookie, $domain = null)
    {
        if (is_array($setCookie)) {
            foreach ($setCookie as $setCookieItem) {
                $this->setCookie($setCookieItem);
            }
        } else {
            $matches = array();

            if (preg_match(
                '/(?<name>[^\=]+)\=(?<value>[^;]+)'.
                        '(; expires=(?<expires>[^;]+))?'.
                        '(; path=(?<path>[^;]+))?'.'(; domain=(?<domain>[^;]+))?'.
                        '(; (?<secure>secure))?'.'(; (?<httponly>httponly))?/',
                    $setCookie, $matches)) {
                $cookieName = null;
                $cookieValue = null;
                $cookieExpires = INF;
                $cookiePath = '/';
                $cookieDomain = $domain;
                $cookieSecure = false;

                foreach ($matches as $key => $value) {
                    if (!empty($value)) {
                        switch ($key) {
                            case 'name' :
                                $cookieName = $value;
                                break;
                            case 'value' :
                                $cookieValue = $value;
                                break;
                            case 'expires' :
                                $cookieExpires = strtotime($value);
                                break;
                            case 'path' :
                                $cookiePath = $value;
                                break;
                            case 'domain' :
                                $cookieDomain = $value;
                                break;
                            case 'secure' :
                                $cookieSecure = true;
                                break;
                        }
                    }
                }

                if (!isset($this->cookies[$cookieDomain])) {
                    $this->cookies[$cookieDomain] = array();
                }

                $this->cookies[$cookieDomain][] = new Cookie($cookieName,
                    $cookieValue, $cookieDomain, $cookieExpires, $cookiePath,
                    $cookieSecure);
            }
        }
    }

    /**
     * @see Serializable::serialize()
     */
    public function serialize()
    {
        return serialize($this->cookies);
    }

    /**
     * @see Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        $cookies = unserialize($serialized);

        if (is_array($cookies)) {
            $now = time();

            foreach ($cookies as $domain => $domainCookies) {
                foreach ($domainCookies as $cookie) {
                    if ($cookie instanceof Cookie) {
                        if ($cookie->getExpires() > $now) {
                            if (!isset($this->cookies[$domain])) {
                                $this->cookies[$domain] = array();
                            }

                            $this->cookies[$domain][] = $cookie;
                        }
                    }
                }
            }
        }
    }
}
