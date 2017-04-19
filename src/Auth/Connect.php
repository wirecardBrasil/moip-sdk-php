<?php

namespace Moip\Auth;

use Moip\Contracts\Authentication;
use Moip\Exceptions\InvalidArgumentException;
use Requests_Hooks;

/**
<<<<<<< HEAD
 * Class Connect
 *
 * For all requests involving more than one Moip Account directly, authentication through an OAuth token is required.
 * Using the OAuth 2.0 standard it is possible to authenticate to the Moip APIs and request the use of the APIs on behalf of another user.
 * In this way, another Moip user can grant you the most diverse permissions,
 * from receiving payments as a secondary receiver to even special actions like repayment of a payment.
=======
 * Class Connect.
>>>>>>> 1736c8b385e75d4282846132de335c9a9cfee7bc
 */
class Connect implements Authentication
{
    /**
<<<<<<< HEAD
     * @const string
     */
    const ENDPOINT_SANDBOX = 'https://connect-sandbox.moip.com.br';

    /**
     * @const string
     */
    const ENDPOINT_PRODUCTION = 'https://connect.moip.com.br';

    /**
     * @const string
     */
    const OAUTH_AUTHORIZE = '/oauth/authorize';

    /**
     * @const string
     */
    const OAUTH_TOKEN = '/oauth/token';


    /**
     * Define the type of response to be obtained. Possible values: CODE
=======
     * Define the type of response to be obtained. Possible values: CODE.
>>>>>>> 1736c8b385e75d4282846132de335c9a9cfee7bc
     *
     * @const string
     */
    const RESPONSE_TYPE = 'code';

    /**
     * Permission for creation and consultation of ORDERS, PAYMENTS, MULTI ORDERS, MULTI PAYMENTS, CUSTOMERS and consultation of LAUNCHES.
     *
     * @const string
     */
    const RECEIVE_FUNDS = 'RECEIVE_FUNDS';

    /**
     * Permission to create and consult reimbursements of ORDERS, PAYMENTS.
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
     * Client Redirect URI.
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
     * URI of oauth.
     *
     * @param $auth_endpoint
     *
     * @return string
     */
    public function getAuthUrl($auth_endpoint)
    {
        $query_string = [
            'response_type' => self::RESPONSE_TYPE,
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect_uri,
            'scope' => implode(',', $this->scope)
        ];

        return $auth_endpoint.self::OAUTH_AUTHORIZE.'?'.http_build_query($query_string);;
    }

    /**
     * @param bool $scope
     */
    public function setScodeAll($scope)
    {
        if (!is_bool($scope)) {
            throw new InvalidArgumentException('$scope deve ser boolean, foi passado '.gettype($scope));
        }

        if ($scope === false) {
            $this->scope = [];
        } else {
            $this->setReceiveFunds(true)
                ->setRefund(true)
                ->setManageAccountInfo(true)
                ->setRetrieveFinancialInfo(true)
                ->setTransferFunds(true)
                ->setDefinePreferences(true);
        }
    }

    /**
     * Permission for creation and consultation of ORDERS, PAYMENTS, MULTI ORDERS, MULTI PAYMENTS, CUSTOMERS and consultation of LAUNCHES.
     *
     * @param bool $receive_funds
     *
     * @throws \Moip\Exceptions\InvalidArgumentException
     *
     * @return \Moip\Auth\Connect $this
     */
    public function setReceiveFunds($receive_funds)
    {
        if (!is_bool($receive_funds)) {
            throw new InvalidArgumentException('$receive_funds deve ser boolean, foi passado '.gettype($receive_funds));
        }

        if ($receive_funds === true) {
            $this->setScope(self::RECEIVE_FUNDS);
        }

        return $this;
    }

    /**
     * Permission to create and consult reimbursements ofORDERS, PAYMENTS.
     *
     * @param bool $refund
     *
     * @throws \Moip\Exceptions\InvalidArgumentException
     *
     * @return \Moip\Auth\Connect $this
     */
    public function setRefund($refund)
    {
        if (!is_bool($refund)) {
            throw new InvalidArgumentException('$refund deve ser boolean, foi passado '.gettype($refund));
        }

        if ($refund === true) {
            $this->setScope(self::REFUND);
        }

        return $this;
    }

    /**
     * Permission to consult ACCOUNTS registration information.
     *
     * @param bool $manage_account_info
     *
     * @throws \Moip\Exceptions\InvalidArgumentException
     *
     * @return \Moip\Auth\Connect $this
     */
    public function setManageAccountInfo($manage_account_info)
    {
        if (!is_bool($manage_account_info)) {
            throw new InvalidArgumentException('$manage_account_info deve ser boolean, foi passado '.gettype($manage_account_info));
        }

        if ($manage_account_info === true) {
            $this->setScope(self::MANAGE_ACCOUNT_INFO);
        }

        return $this;
    }

    /**
     * Permission to query balance through the ACCOUNTS endpoint.
     *
     * @param bool $retrieve_financial_info
     *
     * @throws \Moip\Exceptions\InvalidArgumentException
     *
     * @return \Moip\Auth\Connect $this
     */
    public function setRetrieveFinancialInfo($retrieve_financial_info)
    {
        if (!is_bool($retrieve_financial_info)) {
            throw new InvalidArgumentException('$retrieve_financial_info deve ser boolean, foi passado '.gettype($retrieve_financial_info));
        }

        if ($retrieve_financial_info === true) {
            $this->setScope(self::RETRIEVE_FINANCIAL_INFO);
        }

        return $this;
    }

    /**
     * Permission for bank transfers or for Moip accounts through the TRANSFERS endpoint.
     *
     * @param bool $transfer_funds
     *
     * @throws \Moip\Exceptions\InvalidArgumentException
     *
     * @return \Moip\Auth\Connect $this
     */
    public function setTransferFunds($transfer_funds)
    {
        if (!is_bool($transfer_funds)) {
            throw new InvalidArgumentException('$transfer_funds deve ser boolean, foi passado '.gettype($transfer_funds));
        }

        if ($transfer_funds === true) {
            $this->setScope(self::TRANSFER_FUNDS);
        }

        return $this;
    }

    /**
     * Permission to create, change, and delete notification preferences through the PREFERENCES endpoint.
     *
     * @param bool $define_preferences
     *
     * @throws \Moip\Exceptions\InvalidArgumentException
     *
     * @return $this
     */
    public function setDefinePreferences($define_preferences)
    {
        if (!is_bool($define_preferences)) {
            throw new InvalidArgumentException('$define_preferences deve ser boolean, foi passado '.gettype($define_preferences));
        }

        if ($define_preferences === true) {
            $this->setScope(self::DEFINE_PREFERENCES);
        }

        return $this;
    }

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
        $this->scope[] = $scope;

        return $this;
    }

    /**
     * Register hooks as needed.
     *
     * This method is called in {@see Requests::request} when the user has set
     * an instance as the 'auth' option. Use this callback to register all the
     * hooks you'll need.
     *
     * @see Requests_Hooks::register
     *
     * @param Requests_Hooks $hooks Hook system
     */
    public function register(Requests_Hooks &$hooks)
    {
        // TODO: Implement register() method.
    }
}
