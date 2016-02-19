<?php

namespace Moip\Tests;

use Moip\Moip;
use Moip\Resource\Customer;
use PHPUnit_Framework_TestCase as TestCase;
use Requests_Response;

/**
 * class MoipTestCase.
 */
abstract class MoipTestCase extends TestCase
{
    /**
     * Intance of \Moip\Moip.
     *
     * @var \Moip\Moip
     **/
    protected $moip;

    /**
     * @var string current format for dates.
     */
    protected $date_format = 'Y-m-d';

    /**
     * @var string $date_string date used for testing.
     */
    protected $date_string = '1989-06-01';

    /**
     * @var string $body response from the client moip API.
     */
    protected $body_client = '{"id":"CUS-CFMKXQBZNJQQ","ownId":"meu_id_sandbox","fullname":"Jose Silva","email":"jose_silva0@email.com","phone":{"countryCode":"55","areaCode":"11","number":"66778899"},"birthDate":"1989-06-01","taxDocument":{"type":"CPF","number":"22222222222"},"shippingAddress":{"street":"Avenida Faria Lima","streetNumber":"2927","complement":"8","city":"Sao Paulo","state":"SP","country":"BRA","zipCode":"01234000"},"fundingInstruments":[],"createdAt":"2016-02-18T19:55:00.000-02","_links":{"self":{"href":"https://sandbox.moip.com.br/v2/customers/CUS-CFMKXQBZNJQQ"}}}';

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        $auth = $this->getMock('\Moip\MoipAuthentication');
        $this->moip = new Moip($auth);
    }

    /**
     * Mock a http request.
     *
     * @param string $body        what the request will return
     * @param int    $status_code what http code the request will return
     */
    public function mockHttpSession($body, $status_code = 200)
    {
        $resp = new Requests_Response();
        $resp->body = $body;
        $resp->status_code = $status_code;
        $sess = $this->getMock('\Requests_Session');
        $sess->expects($this->once())->method('request')->willReturn($resp);
        $this->moip->setSession($sess);
    }

    /**
     * Creates a customer.
     *
     * @return Customer
     */
    public function createCustomer()
    {
        $customer = $this->moip->customers()->setOwnId('meu_id_sandbox')
            ->setBirthDate(\DateTime::createFromFormat($this->date_format, $this->date_string))
            ->setFullname("Jose Silva")
            ->setEmail("jose_silva0@email.com")
            ->setTaxDocument("22222222222", 'CPF')
            ->setPhone(11, 66778899, 55)
            ->addAddress(Customer::ADDRESS_SHIPPING, "Avenida Faria Lima", "2927", "Itaim", "Sao Paulo",
                "SP", "01234000", "8");

        return $customer;
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        $this->moip = null;
    }
}
