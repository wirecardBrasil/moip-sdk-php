<?php

namespace Moip;

use Moip\Resource\Customer;
use Moip\Resource\Entry;
use Moip\Resource\Multiorders;
use Moip\Resource\Orders;
use Moip\Resource\Payment;
use Sostheblack\Http\HTTPConnection;

class Moip
{
    /**
     * endpoint of production.
     *
     * @const string
     */
    const ENDPOINT_PRODUCTION = 'moip.com.br';
    /**
     * endpoint of sandbox.
     *
     * @const string
     */
    const ENDPOINT_SANDBOX = 'test.moip.com.br';

    /**
     * Client name.
     * 
     * @const string
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
     * @var string
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
     * @return \Sostheblack\Http\HTTPConnection
     */
    public function createConnection()
    {
        $httpConnection = new HTTPConnection(self::CLIENT);
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
