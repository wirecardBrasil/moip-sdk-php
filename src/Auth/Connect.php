<?php

namespace Moip\Auth;

use Moip\Contracts\Authentication;
use Requests_Hooks;

/**
 * Class Connect
 */
class Connect implements Authentication
{
    /**
     * Define the type of response to be obtained. Possible values: CODE
     * @const string
     */
    const RESPONSE_TYPE = 'code';

    /**
     * Permission for creation and consultation of
     * ORDERS, PAYMENTS, MULTI ORDERS, MULTI PAYMENTS, CUSTOMERS and consultation of LAUNCHES.
     *
     * @const string
     */
    const RECEIVE_FUNDS = 'RECEIVE_FUNDS';

    /**
     * Permission to create and consult reimbursements of
     * ORDERS, PAYMENTS.
     *
     * @const string
     */
    const REFUND = 'REFUND';

    /**
     * Permission to consult ACCOUNTS registration information.
     *
     * @const string
     */
    const MANAGE_ACCOUNT_INFO = 'MANAGE_ACCOUNT_INFO';

    /**
     * Permission to query balance through the ACCOUNTS endpoint.
     *
     * @const string
     */
    const RETRIEVE_FINANCIAL_INFO = 'RETRIEVE_FINANCIAL_INFO';

    /**
     * Permission for bank transfers or for Moip accounts through the TRANSFERS endpoint.
     *
     * @const string
     */
    const TRANSFER_FUNDS = 'TRANSFER_FUNDS';

    /**
     * Permission to create, change, and delete notification preferences through the PREFERENCES endpoint.
     *
     * @const string
     */
    const DEFINE_PREFERENCES = 'DEFINE_PREFERENCES';

    /**
     * Unique identifier of the application that will be carried out the request.
     *
     * @var string (16)
     */
    private $client_id;

    /**
     * Client Redirect URI
     *
     * @var string (255)
     */
    private $redirect_uri;

    /**
     * Permissions that you want (Possible values depending on the feature.).
     *
     * @var array
     */
    private $scope = [];

    /**
     * Unique identifier of the application that will be carried out the request.
     *
     * @return mixed
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * Unique identifier of the application that will be carried out the request.
     *
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
     * Client Redirect URI.
     *
     * @return mixed
     */
    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }

    /**
     * Client Redirect URI.
     *
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
     * Permissions that you want (Possible values depending on the feature.).
     *
     * @return mixed
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Permissions that you want (Possible values depending on the feature.).
     *
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
