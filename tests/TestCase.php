<?php

namespace Moip\Tests;

use Moip\Auth\BasicAuth;
use Moip\Moip;
use Moip\Resource\Customer;
use Moip\Resource\Orders;
use PHPUnit_Framework_TestCase;
use Requests_Response;

/**
 * class TestCase.
 */
abstract class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Variables representing the test modes. On MOCK mode no http request will be made.
     * In SANDBOX mode HTTP requests will be made to the Moip::SANDBOX_ENDPOINT, the authentication information
     * is retrieved from the MOIP_TOKEN and MOIP_KEY environment variables.
     */
    const MOCK = 'mock';
    const SANDBOX = 'sandbox';

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
     * @var string date used for testing.
     */
    protected $date_string = '1989-06-01';

    //todo: add the ability to use the play(https://github.com/rodrigosaito/mockwebserver-player) files from the jada sdk
    //the two responses below were based on the moip Java sdk's test files (https://github.com/moip/moip-sdk-java/)
    /**
     * @var string response from the client moip API.
     */
    protected $body_client = '{"id":"CUS-CFMKXQBZNJQQ","ownId":"meu_id_sandbox","fullname":"Jose Silva","email":"jose_silva0@email.com","phone":{"countryCode":"55","areaCode":"11","number":"66778899"},"birthDate":"1989-06-01","taxDocument":{"type":"CPF","number":"22222222222"},"shippingAddress":{"street":"Avenida Faria Lima","streetNumber":"2927","complement":"8","city":"Sao Paulo","state":"SP","country":"BRA","zipCode":"01234000"},"fundingInstruments":[],"createdAt":"2016-02-18T19:55:00.000-02","_links":{"self":{"href":"https://sandbox.moip.com.br/v2/customers/CUS-CFMKXQBZNJQQ"}}}';

    /**
     * @var string response from the order moip API.
     */
    protected $body_order = '{"id":"ORD-HG479ZEIB7LV","ownId":"meu_id_pedido","status":"CREATED","createdAt":"2016-02-19T12:24:55.849-02","updatedAt":"2016-02-19T12:24:55.849-02","amount":{"total":102470,"fees":0,"refunds":0,"liquid":0,"otherReceivers":0,"currency":"BRL","subtotals":{"shipping":1490,"addition":0,"discount":1000,"items":101980}},"items":[{"price":100000,"detail":"Mais info...","quantity":1,"product":"Nome do produto"},{"price":990,"detail":"Abacaxi de terra de areia","quantity":2,"product":"abacaxi"}],"customer":{"id":"CUS-7U5K9KWG8DBZ","ownId":"meu_id_saasdadadsnasdasddboxssssssssss","fullname":"Jose Silva","createdAt":"2016-02-18T20:03:28.000-02","birthDate":"1989-06-01T00:00:00.000-03","email":"jose_silva0@email.com","phone":{"countryCode":"55","areaCode":"11","number":"66778899"},"taxDocument":{"type":"CPF","number":"22222222222"},"shippingAddress":{"zipCode":"01234000","street":"Avenida Faria Lima","streetNumber":"2927","complement":"8","city":"Sao Paulo","district":"Itaim","state":"SP","country":"BRA"},"_links":{"self":{"href":"https://sandbox.moip.com.br/v2/customers/CUS-7U5K9KWG8DBZ"}}},"payments":[],"refunds":[],"entries":[],"events":[{"type":"ORDER.CREATED","createdAt":"2016-02-19T12:24:55.849-02","description":""}],"receivers":[{"moipAccount":{"id":"MPA-7ED9D2D0BC81","login":"ev@traca.com.br","fullname":"Carmen Elisabete de Menezes ME"},"type":"PRIMARY","amount":{"total":102470,"fees":0,"refunds":0}}],"shippingAddress":{"zipCode":"01234000","street":"Avenida Faria Lima","streetNumber":"2927","complement":"8","city":"Sao Paulo","district":"Itaim","state":"SP","country":"BRA"},"_links":{"self":{"href":"https://sandbox.moip.com.br/v2/orders/ORD-HG479ZEIB7LV"},"checkout":{"payOnlineBankDebitItau":{"redirectHref":"https://checkout-sandbox.moip.com.br/debit/itau/ORD-HG479ZEIB7LV"},"payCreditCard":{"redirectHref":"https://checkout-sandbox.moip.com.br/creditcard/ORD-HG479ZEIB7LV"},"payBoleto":{"redirectHref":"https://checkout-sandbox.moip.com.br/boleto/ORD-HG479ZEIB7LV"}}}}';

    /**
     * @var string response from moip API.
     */
    protected $body_cc_pay_pci = '{"id":"PAY-L6J2NKS9OGYU","status":"IN_ANALYSIS","delayCapture":false,"amount":{"total":102470,"fees":5695,"refunds":0,"liquid":96775,"currency":"BRL"},"installmentCount":1,"fundingInstrument":{"creditCard":{"id":"CRC-2TJ13YB4Y1WU","brand":"MASTERCARD","first6":"555566","last4":"8884","holder":{"birthdate":"1989-06-01","birthDate":"1989-06-01","taxDocument":{"type":"CPF","number":"22222222222"},"fullname":"Jose Silva"}},"method":"CREDIT_CARD"},"fees":[{"type":"TRANSACTION","amount":5695}],"events":[{"type":"PAYMENT.IN_ANALYSIS","createdAt":"2016-02-19T18:18:54.535-02"},{"type":"PAYMENT.CREATED","createdAt":"2016-02-19T18:18:51.946-02"}],"_links":{"order":{"href":"https://sandbox.moip.com.br/v2/orders/ORD-8UDL4K9VRJTB","title":"ORD-8UDL4K9VRJTB"},"self":{"href":"https://sandbox.moip.com.br/v2/payments/PAY-L6J2NKS9OGYU"}},"createdAt":"2016-02-19T18:18:51.944-02","updatedAt":"2016-02-19T18:18:54.535-02"}';

    /**
     * @var string response from moip API.
     */
    protected $body_billet_pay = '{"id":"PAY-XNVIBO5MIQ9S","status":"WAITING","delayCapture":false,"amount":{"total":102470,"fees":3645,"refunds":0,"liquid":98825,"currency":"BRL"},"installmentCount":1,"fundingInstrument":{"boleto":{"expirationDate":"2016-05-21","lineCode":"23793.39126 60000.062608 32001.747909 7 68010000102470"},"method":"BOLETO"},"fees":[{"type":"TRANSACTION","amount":3645}],"events":[{"type":"PAYMENT.CREATED","createdAt":"2016-05-20T15:19:47.000-03"},{"type":"PAYMENT.WAITING","createdAt":"2016-05-20T15:19:47.000-03"}],"_links":{"order":{"href":"https://sandbox.moip.com.br/v2/orders/ORD-3KSQDBJSTIF6","title":"ORD-3KSQDBJSTIF6"},"payBoleto":{"redirectHref":"https://checkout-sandbox.moip.com.br/boleto/PAY-XNVIBO5MIQ9S"},"self":{"href":"https://sandbox.moip.com.br/v2/payments/PAY-XNVIBO5MIQ9S"}},"updatedAt":"2016-05-20T15:19:47.000-03","createdAt":"2016-05-20T15:19:47.000-03"}';

    /**
     * @var string holds the last generated customer ownId. In mock mode it'll be always the default, but it changes on sandbox mode.
     */
    protected $last_cus_id = 'meu_id_customer';

    /**
     * @var string same as `$last_cus_id` but for orders.
     *
     * @see $last_cus_id
     */
    protected $last_ord_id = 'meu_id_pedido';

    protected $sandbox_mock = self::MOCK;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        // check if we can run the request on sandbox
        $moip_key = getenv('MOIP_KEY');
        $moip_token = getenv('MOIP_TOKEN');

        if ($moip_key && $moip_token) {
            $this->sandbox_mock = self::SANDBOX;
            $auth = new BasicAuth($moip_token, $moip_key);
        } else {
            $this->sandbox_mock = self::MOCK;
            $auth = $this->getMock('\Moip\Contracts\Authentication');
        }
        $this->moip = new Moip($auth, Moip::ENDPOINT_SANDBOX);
    }

    /**
     * If in MOCK mode returns a mocked Requests_Sessesion if in SANDBOX mode, creates a new session.
     *
     * @param string $body        what the request will return
     * @param int    $status_code what http code the request will return
     */
    public function mockHttpSession($body, $status_code = 200)
    {
        if ($this->sandbox_mock == self::SANDBOX) {
            $this->moip->createNewSession();

            return;
        }
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
        if ($this->sandbox_mock == self::SANDBOX) {
            $this->last_cus_id = uniqid('CUS-');
        } else {
            $this->last_cus_id = 'meu_id_sandbox';
        }

        $customer = $this->moip->customers()->setOwnId($this->last_cus_id)
            ->setBirthDate(\DateTime::createFromFormat($this->date_format, $this->date_string))
            ->setFullname('Jose Silva')
            ->setEmail('jose_silva0@email.com')
            ->setTaxDocument('22222222222', 'CPF')
            ->setPhone(11, 66778899, 55)
            ->addAddress(Customer::ADDRESS_SHIPPING, 'Avenida Faria Lima', '2927', 'Itaim', 'Sao Paulo',
                'SP', '01234000', '8');

        return $customer;
    }

    /**
     * Creates a account.
     *
     * @return Account
     */
    public function createAccount()
    {
        $moip = new Moip(new MoipOAuth('1tldio91gi74r34zv30d4saz8yuuws5'), Moip::ENDPOINT_SANDBOX);

        $uniqEmail = 'fulano'.uniqid('MPA-').'@detal123.com.br';

        $account = $moip->accounts()
            ->setEmail($uniqEmail)
            ->setName('Fulano')
            ->setLastName('de Tal')
            ->setBirthDate('1987-11-27')
            ->setTaxDocument('22222222222')
            ->setPhone(11, 988888888)
            ->addAddress('Av. Ibirapuera', '2035', 'Moema', 'Sao Paulo', 'SP', '04078010')
            ->setIdentityDocument('411111115', 'SSP', '2000-05-06')
            ->create();

        return $account;
    }

    /**
     * Creates an order.
     *
     * @return Orders
     */
    public function createOrder()
    {
        if ($this->sandbox_mock == self::SANDBOX) {
            $this->last_ord_id = uniqid('ORD-');
        } else {
            $this->last_ord_id = 'meu_id_pedido';
        }

        $order = $this->moip->orders()->setCustomer($this->createCustomer())
            ->addItem('Nome do produto', 1, 'Mais info...', 100000)
            ->addItem('abacaxi', 2, 'Abacaxi de terra de areia', 990)
            ->setDiscount(1000)
            ->setShippingAmount(1490)
            ->setOwnId($this->last_ord_id);

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
