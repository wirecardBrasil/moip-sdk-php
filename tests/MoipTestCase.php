<?php

namespace Moip\Tests;

use Moip\Moip;
use Moip\Resource\Customer;
use Moip\Resource\Orders;
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

    //todo: add the ability to use the play(https://github.com/rodrigosaito/mockwebserver-player) files from the jada sdk
    //the two responses below were based on the moip Java sdk's test files (https://github.com/moip/moip-sdk-java/)
    /**
     * @var string $body response from the client moip API.
     */
    protected $body_client = '{"id":"CUS-CFMKXQBZNJQQ","ownId":"meu_id_sandbox","fullname":"Jose Silva","email":"jose_silva0@email.com","phone":{"countryCode":"55","areaCode":"11","number":"66778899"},"birthDate":"1989-06-01","taxDocument":{"type":"CPF","number":"22222222222"},"shippingAddress":{"street":"Avenida Faria Lima","streetNumber":"2927","complement":"8","city":"Sao Paulo","state":"SP","country":"BRA","zipCode":"01234000"},"fundingInstruments":[],"createdAt":"2016-02-18T19:55:00.000-02","_links":{"self":{"href":"https://sandbox.moip.com.br/v2/customers/CUS-CFMKXQBZNJQQ"}}}';

    /**
     * @var string $body_order response from the order moip API.
     */
    protected $body_order = '{"id":"ORD-HG479ZEIB7LV","ownId":"meu_id_pedido","status":"CREATED","createdAt":"2016-02-19T12:24:55.849-02","updatedAt":"2016-02-19T12:24:55.849-02","amount":{"total":102470,"fees":0,"refunds":0,"liquid":0,"otherReceivers":0,"currency":"BRL","subtotals":{"shipping":1490,"addition":0,"discount":1000,"items":101980}},"items":[{"price":100000,"detail":"Mais info...","quantity":1,"product":"Nome do produto"},{"price":990,"detail":"Abacaxi de terra de areia","quantity":2,"product":"abacaxi"}],"customer":{"id":"CUS-7U5K9KWG8DBZ","ownId":"meu_id_saasdadadsnasdasddboxssssssssss","fullname":"Jose Silva","createdAt":"2016-02-18T20:03:28.000-02","birthDate":"1989-06-01T00:00:00.000-03","email":"jose_silva0@email.com","phone":{"countryCode":"55","areaCode":"11","number":"66778899"},"taxDocument":{"type":"CPF","number":"22222222222"},"shippingAddress":{"zipCode":"01234000","street":"Avenida Faria Lima","streetNumber":"2927","complement":"8","city":"Sao Paulo","district":"Itaim","state":"SP","country":"BRA"},"_links":{"self":{"href":"https://sandbox.moip.com.br/v2/customers/CUS-7U5K9KWG8DBZ"}}},"payments":[],"refunds":[],"entries":[],"events":[{"type":"ORDER.CREATED","createdAt":"2016-02-19T12:24:55.849-02","description":""}],"receivers":[{"moipAccount":{"id":"MPA-7ED9D2D0BC81","login":"ev@traca.com.br","fullname":"Carmen Elisabete de Menezes ME"},"type":"PRIMARY","amount":{"total":102470,"fees":0,"refunds":0}}],"shippingAddress":{"zipCode":"01234000","street":"Avenida Faria Lima","streetNumber":"2927","complement":"8","city":"Sao Paulo","district":"Itaim","state":"SP","country":"BRA"},"_links":{"self":{"href":"https://sandbox.moip.com.br/v2/orders/ORD-HG479ZEIB7LV"},"checkout":{"payOnlineBankDebitItau":{"redirectHref":"https://checkout-sandbox.moip.com.br/debit/itau/ORD-HG479ZEIB7LV"},"payCreditCard":{"redirectHref":"https://checkout-sandbox.moip.com.br/creditcard/ORD-HG479ZEIB7LV"},"payBoleto":{"redirectHref":"https://checkout-sandbox.moip.com.br/boleto/ORD-HG479ZEIB7LV"}}}}';

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
     * Creates an order.
     *
     * @return Orders
     */
    public function createOrder()
    {
        $order = $this->moip->orders()->setCustomer($this->createCustomer())
            ->addItem("Nome do produto", 1, "Mais info...", 100000)
            ->addItem("abacaxi", 2, "Abacaxi de terra de areia", 990)
            ->setDiscount(1000)
            ->setShippingAmount(1490)
            ->setOwnId('meu_id_pedido');
        return $order;
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
