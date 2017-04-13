<?php

namespace Moip\Auth;

use Moip\Contracts\Authentication;
use Requests_Hooks;

/**
 * Class Connect
 */
class Connect implements Authentication
{
    const RESPONSE_TYPE = 'code';
    const RECEIVE_FUNDS = 'RECEIVE_FUNDS';
    const REFUND = 'REFUND';
    const MANAGE_ACCOUNT_INFO = 'MANAGE_ACCOUNT_INFO';
    const RETRIEVE_FINANCIAL_INFO = 'RETRIEVE_FINANCIAL_INFO';
    const TRANSFER_FUNDS = 'TRANSFER_FUNDS';
    const DEFINE_PREFERENCES = 'DEFINE_PREFERENCES';

    private $client_id;
    private $redirect_uri;
    private $scope = [];

    public function __construct()
    {
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * @param mixed $client_id
     *
     * @return \Moip\Auth\Connect
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }

    /**
     * @param mixed $redirect_uri
     *
     * @return \Moip\Auth\Connect
     */
    public function setRedirectUri($redirect_uri)
    {
        $this->redirect_uri = $redirect_uri;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param array|string $scope
     *
     * @return \Moip\Auth\Connect
     */
    public function setScope($scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Register hooks as needed
     *
     * This method is called in {@see Requests::request} when the user has set
     * an instance as the 'auth' option. Use this callback to register all the
     * hooks you'll need.
     *
     * @see Requests_Hooks::register
     * @param Requests_Hooks $hooks Hook system
     */
    public function register(Requests_Hooks &$hooks)
    {
        // TODO: Implement register() method.
    }
}
