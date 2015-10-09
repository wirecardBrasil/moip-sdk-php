<?php

namespace Moip;

use Moip\Http\HTTPConnection;
use Moip\Resource\Customer;
use Moip\Resource\Entry;
use Moip\Resource\Multiorders;
use Moip\Resource\Orders;
use Moip\Resource\Payment;

class Moip
{
    /**
     * endpoint of production.
     *
     * @const string
     */
    const ENDPOINT_PRODUCTION = 'api.moip.com.br';
    /**
     * endpoint of sandbox.
     *
     * @const string
     */
    const ENDPOINT_SANDBOX = 'sandbox.moip.com.br';

    /**
     * Client name.
     * 
     * @const string
     * 
     * @deprecated
     **/
    const CLIENT = 'Moip SDK';

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
     * Create a new aurhentication with the endpoint.
     *
     * @param \Moip\MoipAuthentication               $moipAuthentication
     * @param \Moip\Moip::ENDPOINT_PRODUCTION|string $endpoint
     */
    public function __construct(MoipAuthentication $moipAuthentication, $endpoint = self::ENDPOINT_PRODUCTION)
    {
        $this->moipAuthentication = $moipAuthentication;
        $this->endpoint = $endpoint;
    }

    /**
     * Create a new api connection instance.
     *
     * @return \Moip\Http\HTTPConnection
     */
    public function createConnection(HTTPConnection $http_connection)
    {
        $http_connection = $http_connection;
        $http_connection->initialize($this->endpoint, true);
        $http_connection->addHeader('Accept', 'application/json');
        $http_connection->setAuthenticator($this->moipAuthentication);

        return $http_connection;
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
