<?php

namespace Moip;

use Moip\Http\HTTPConnection;
use Moip\Resource\Orders;
use Moip\Resource\Customer;
use Moip\Resource\Payment;
use Moip\Resource\Multiorders;
use Moip\Resource\Entry;

class Moip
{
    const PRODUCTION_ENDPOINT = 'moip.com.br';
    const SANDBOX_ENDPOINT = 'test.moip.com.br';

    /**
     * @var MoipAuthentication
     */
    private $moipAuthentication;

    /**
     * @var string
     */
    private $endpoint = self::PRODUCTION_ENDPOINT;

    public function __construct(MoipAuthentication $moipAuthentication,
                                $endpoint = self::PRODUCTION_ENDPOINT)
    {
        $this->moipAuthentication = $moipAuthentication;
        $this->endpoint = $endpoint;
    }

    public function createConnection()
    {
        $httpConnection = new HTTPConnection('Moip SDK');
        $httpConnection->initialize($this->endpoint, true);
        $httpConnection->addHeader('Accept', 'application/json');
        $httpConnection->setAuthenticator($this->moipAuthentication);

        return $httpConnection;
    }

    public function customers()
    {
        return new Customer($this);
    }

    public function entries()
    {
        return new Entry($this);
    }

    public function orders()
    {
        return new Orders($this);
    }

    public function payments()
    {
        return new Payment($this);
    }

    public function multiorders()
    {
        return new Multiorders($this);
    }
}
