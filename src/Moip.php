<?php

namespace Moip;

use Moip\Contracts\Authentication;
use Moip\Resource\Account;
use Moip\Resource\Customer;
use Moip\Resource\Entry;
use Moip\Resource\Multiorders;
use Moip\Resource\NotificationPreferences;
use Moip\Resource\Orders;
use Moip\Resource\Payment;
use Moip\Resource\Transfers;
use Moip\Resource\WebhookList;
use Requests_Session;

/**
 * Class Moip.
 */
class Moip
{
    /**
     * endpoint of production.
     *
     * @const string
     */
    const ENDPOINT_PRODUCTION = 'https://api.moip.com.br';

    /**
     * endpoint of sandbox.
     *
     * @const string
     */
    const ENDPOINT_SANDBOX = 'https://sandbox.moip.com.br';

    /**
     * Client name.
     *
     * @const string
     * */
    const CLIENT = 'MoipPhpSDK';

    /**
     * Client Version.
     *
     * @const string
     */
    const CLIENT_VERSION = '1.3.2';

    /**
     * Authentication that will be added to the header of request.
     *
     * @var \Moip\MoipAuthentication
     */
    private $moipAuthentication;

    /**
     * Endpoint of request.
     *
     * @var \Moip\Moip::ENDPOINT_PRODUCTION|\Moip\Moip::ENDPOINT_SANDBOX
     */
    private $endpoint;

    /**
     * @var Requests_Session HTTP session configured to use the moip API.
     */
    private $session;

    /**
     * Create a new aurhentication with the endpoint.
     *
     * @param \Moip\Auth\MoipAuthentication $moipAuthentication
     * @param string                        $endpoint
     */
    public function __construct(Authentication $moipAuthentication, $endpoint = self::ENDPOINT_PRODUCTION)
    {
        $this->moipAuthentication = $moipAuthentication;
        $this->endpoint = $endpoint;
        $this->createNewSession();
    }

    /**
     * Creates a new Request_Session with all the default values.
     * A Session is created at construction.
     *
     * @param float $timeout         How long should we wait for a response?(seconds with a millisecond precision, default: 30, example: 0.01).
     * @param float $connect_timeout How long should we wait while trying to connect? (seconds with a millisecond precision, default: 10, example: 0.01)
     */
    public function createNewSession($timeout = 30.0, $connect_timeout = 30.0)
    {
        $user_agent = sprintf('%s/%s (+https://github.com/moip/moip-sdk-php/)', self::CLIENT, self::CLIENT_VERSION);
        $sess = new Requests_Session($this->endpoint);
        $sess->options['auth'] = $this->moipAuthentication;
        $sess->options['timeout'] = $timeout;
        $sess->options['connect_timeout'] = $connect_timeout;
        $sess->options['useragent'] = $user_agent;
        $this->session = $sess;
    }

    /**
     * Returns the http session created.
     *
     * @return Requests_Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Replace the http session by a custom one.
     *
     * @param Requests_Session $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * Create a new Customer instance.
     *
     * @return \Moip\Resource\Customer
     */
    public function customers()
    {
        return new Customer($this);
    }

    /**
     * Create a new Account instance.
     *
     * @return \Moip\Resource\Account
     */
    public function accounts()
    {
        return new Account($this);
    }

    /**
     * Create a new Entry instance.
     *
     * @return \Moip\Resource\Entry
     */
    public function entries()
    {
        return new Entry($this);
    }

    /**
     * Create a new Orders instance.
     *
     * @return \Moip\Resource\Orders
     */
    public function orders()
    {
        return new Orders($this);
    }

    /**
     * Create a new Payment instance.
     *
     * @return \Moip\Resource\Payment
     */
    public function payments()
    {
        return new Payment($this);
    }

    /**
     * Create a new Multiorders instance.
     *
     * @return \Moip\Resource\Multiorders
     */
    public function multiorders()
    {
        return new Multiorders($this);
    }

    /**
     * Create a new Transfers.
     *
     * @return \Moip\Resource\Transfers
     */

    /**
     * Create a new Transfers instance.
     *
     * @return Transfers
     */
    public function transfers()
    {
        return new Transfers($this);
    }

    /**
     * Create a new Notification Prefences instance.
     *
     * @return NotificationPreferences
     */
    public function notifications()
    {
        return new NotificationPreferences($this);
    }

    /**
     * Create a new WebhookList instance.
     *
     * @return WebhookList
     */
    public function webhooks()
    {
        return new WebhookList($this);
    }

    /**
     * Get the endpoint.
     *
     * @return \Moip\Moip::ENDPOINT_PRODUCTION|\Moip\Moip::ENDPOINT_SANDBOX
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }
}
