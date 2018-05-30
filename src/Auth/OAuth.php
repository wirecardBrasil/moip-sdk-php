<?php

namespace Moip\Auth;

use Moip\Contracts\Authentication;
use Requests_Hooks;

/**
 * Class OAuth.
 */
class OAuth implements Authentication
{
    /**
     * Access Token.
     *
     * @var string
     */
    private $accessToken;

    /**
     * Create a new OAuth instance.
     *
     * @param string $accessToken
     */
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
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
        $headers['Authorization'] = 'OAuth '.$this->accessToken;
    }
}
