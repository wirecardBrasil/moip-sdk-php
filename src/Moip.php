<?php

namespace Moip;

use Moip\Resource\Entry;
use Moip\Resource\Orders;
use Moip\Resource\Payment;
use Moip\Resource\Customer;
use Moip\Http\HTTPConnection;
use Moip\Resource\Multiorders;

class Moip
{
    /**
     * endpoint of production.
     *
     * @const string
     */
    const PRODUCTION_ENDPOINT = 'moip.com.br';

    /**
     * endpoint of sandbox.
     *
     * @const string
     */
    const SANDBOX_ENDPOINT = 'test.moip.com.br';

    /**
     * Authentication that will be added to the header of request.
     *
     * @var \Moip\MoipAuthentication
     */
    private $moipAuthentication;

    /**
     * Endpoint of request.
     *
     * @var \Moip\Moip::PRODUCTION_ENDPOINT
     */
    private $endpoint = self::PRODUCTION_ENDPOINT;

    /**
     * Create a new aurhentication with the endpoint.
     *
     * @param \Moip\MoipAuthentication        $moipAuthentication
     * @param \Moip\Moip::PRODUCTION_ENDPOINT $endpoint
     */
    public function __construct(MoipAuthentication $moipAuthentication, $endpoint = self::PRODUCTION_ENDPOINT)
    {
        $this->moipAuthentication = $moipAuthentication;
        $this->endpoint = $endpoint;
    }

    /**
     * Create a new api connection instance.
     *
     * @return \Moip\Http\HTTPConnection
     */
    public function createConnection()
    {
        $httpConnection = new HTTPConnection('Moip SDK');
        $httpConnection->initialize($this->endpoint, true);
        $httpConnection->addHeader('Accept', 'application/json');
        $httpConnection->setAuthenticator($this->moipAuthentication);

        return $httpConnection;
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
}
