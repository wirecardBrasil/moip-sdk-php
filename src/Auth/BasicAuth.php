<?php

namespace Moip\Auth;

use Moip\Contracts\Authentication;
use Requests_Hooks;

/**
 * Class BasicAuth.
 */
class BasicAuth implements Authentication
{
    /**
     * Token.
     *
     * @var string
     */
    private $token;

    /**
     * Access key.
     *
     * @var string
     */
    private $key;

    public function __construct($token, $key)
    {
        $this->token = $token;
        $this->key = $key;
    }

    /**
     * Register hooks as needed.
     *
     * This method is called in {@see Requests::request} when the user has set
     * an instance as the 'auth' option. Use this callback to register all the
     * hooks you'll need.
     *
     * @see \Requests_Hooks::register
     *
     * @param \Requests_Hooks $hooks Hook system
     */
    public function register(Requests_Hooks &$hooks)
    {
        $hooks->register('requests.before_request', [&$this, 'before_request']);
    }

    /**
     * Sets the authentication header.
     *
     * @param string       $url
     * @param array        $headers
     * @param array|string $data
     * @param string       $type
     * @param array        $options
     */
    public function before_request(&$url, &$headers, &$data, &$type, &$options)
    {
        $headers['Authorization'] = 'Basic '.base64_encode($this->token.':'.$this->key);
    }
}
