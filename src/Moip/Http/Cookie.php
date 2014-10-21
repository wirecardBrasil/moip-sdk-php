<?php
namespace Moip\Http;

use \InvalidArgumentException;

/**
 * Implementação de um cookie HTTP segundo a especificação RFC 2109.
 */
class Cookie
{
    /**
     * Comentário opcional do cookie
     *
     * @var string
     */
    protected $comment;

    /**
     * Domínio do cookie
     *
     * @var string
     */
    protected $domain;

    /**
     * Expiração do cookie (unix timestamp)
     *
     * @var integer
     */
    protected $expires;

    /**
     * Nome do cookie
     *
     * @var string
     */
    protected $name;

    /**
     * Caminho do cookie
     *
     * @var string
     */
    protected $path;

    /**
     * Ambiente seguro (HTTPS).
     * Indica se o User-Agent deve utilizar o cookie
     * apenas em ambiente seguro (HTTPS)
     *
     * @var boolean
     */
    protected $secure;

    /**
     * Valor do cookie
     *
     * @var string
     */
    protected $value;

    /**
     * Constroi um cookie
     *
     * @param string $name
     *            Nome do cookie
     * @param string $value
     *            Valor do cookie
     * @param string $domain
     *            Domínio do cookie
     * @param integer $expires
     *            Timestamp da expiração do cookie
     * @param string $path
     *            Caminho do cookie
     * @param boolean $secure
     *            Se o cookie é usado apenas em ambiente seguro.
     * @param string $comment
     *            Comentário do cookie
     * @throws InvalidArgumentException Se $expires não for um número
     */
    public function __construct($name, $value, $domain, $expires, $path = '/',
                                $secure = false, $comment = null)
    {
        $this->name = (string) $name;
        $this->value = (string) $value;
        $this->domain = (string) $domain;

        if (is_numeric($expires)) {
            $this->expires = (int) $expires;
        } else {
            throw new InvalidArgumentException('Invalid expiration');
        }

        $this->path = (string) $path;
        $this->secure = $secure === true;
        $this->comment = $comment;
    }

    /**
     * Retorna a representação do Cookie como uma string.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s=%s', $this->name, $this->value);
    }

    /**
     * Recupera o comentário do cookie.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Recupera o domínio do cookie.
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Recupera o timestamp da expiração do cookie.
     *
     * @return integer
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * Recupera o nome do cookie.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Recupera o caminho do cookie.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Recupera o valor do cookie.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Verifica ambiente seguro.
     * Verifica se o User-Agent deve utilizar o
     * cookie apenas em ambiente seguro.
     *
     * @return boolean
     */
    public function isSecure()
    {
        return $this->secure;
    }
}