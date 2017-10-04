<?php

namespace Moip\Tests;

use Moip\Auth\OAuth;
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
     * */
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
    protected $body_client;

    /**
     * @var string response from the order moip API.
     */
    protected $body_order;

    /**
     * @var string response from moip API.
     */
    protected $body_cc_pay_pci;

    /**
     * @var string response from moip API.
     */
    protected $body_cc_pay_pci_store;

    /**
     * @var string response from moip API.
     */
    protected $body_cc_pay_pci_escrow;

    /**
     * @var string response from moip API.
     */
    protected $body_release_escrow;

    /**
     * @var string response from moip API.
     */
    protected $body_billet_pay;

    /**
     * @var string response from moip API.
     */
    protected $body_refund_full_bankaccount;

    /**
     * @var string response from moip API.
     */
    protected $body_refund_partial_bankaccount;

    /**
     * @var string response from moip API.
     */
    protected $body_notification_preference;

    /**
     * @var string response from moip API.
     */
    protected $body_moip_account;

    /**
     * @var string response from moip API.
     */
    protected $body_order_list;

    /**
     * @var string response from moip API.
     */
    protected $body_notification_list;

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

    public function __construct()
    {
        parent::__construct();

        $this->body_client = $this->readJsonFile('jsons/customer/create');

        $this->body_order = $this->readJsonFile('jsons/order/create');

        $this->body_cc_pay_pci = $this->readJsonFile('jsons/payment/create_cc_pci');

        $this->body_cc_pay_pci_store = $this->readJsonFile('jsons/payment/create_cc_pci_store');

        $this->body_cc_pay_pci_escrow = $this->readJsonFile('jsons/payment/create_cc_pci_escrow');

        $this->body_release_escrow = $this->readJsonFile('jsons/escrow/release');

        $this->body_billet_pay = $this->readJsonFile('jsons/payment/create_billet');

        $this->body_billet_multipay = $this->readJsonFile('jsons/multipayment/create_billet');

        $this->body_cc_multipay = $this->readJsonFile('jsons/multipayment/create_cc');

        $this->body_refund_full_bankaccount = $this->readJsonFile('jsons/refund/full_bankaccount');

        $this->body_refund_partial_bankaccount = $this->readJsonFile('jsons/refund/partial_bankaccount');

        $this->body_notification_preference = $this->readJsonFile('jsons/notification/create');

        $this->body_moip_account = $this->readJsonFile('jsons/account/create');

        $this->body_order_list = $this->readJsonFile('jsons/order/get_list');

        $this->body_add_credit_card = $this->readJsonFile('jsons/customer/add_credit_card');

        $this->body_list_webhook_no_filter = $this->readJsonFile('jsons/webhooks/get_no_filter');

        $this->body_list_webhook_pagination = $this->readJsonFile('jsons/webhooks/get_pagination');

        $this->body_list_webhook_all_filters = $this->readJsonFile('jsons/webhooks/get_all_filters');

        $this->body_notification_list = $this->readJsonFile('jsons/notification/list');

        $this->body_multiorder = $this->readJsonFile('jsons/multiorder/create');

        $this->body_cc_delay_capture = $this->readJsonFile('jsons/payment/create_cc_delay_capture');

        $this->body_capture_pay = $this->readJsonFile('jsons/payment/capture');

        $this->body_capture_multipay = $this->readJsonFile('jsons/multipayment/capture');

        $this->body_cancel_pay = $this->readJsonFile('jsons/payment/cancel_pre_authorized');

        $this->body_cancel_multipay = $this->readJsonFile('jsons/multipayment/cancel_pre_authorized');

        $this->body_get_pay = $this->readJsonFile('jsons/payment/get');

        $this->body_get_multipay = $this->readJsonFile('jsons/multipayment/get');

        $this->body_keys = $this->readJsonFile('jsons/keys/get');
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        // check if we can run the request on sandbox
        $moip_access_token = getenv('MOIP_ACCESS_TOKEN');

        if ($moip_access_token) {
            $this->sandbox_mock = self::SANDBOX;
            $auth = new OAuth($moip_access_token);
        } else {
            $this->sandbox_mock = self::MOCK;
            $auth = $this->getMock('\Moip\Contracts\Authentication');
        }
        $this->moip = new Moip($auth, Moip::ENDPOINT_SANDBOX);
    }

    /**
     * Method to read JSON from a file.
     *
     * @param string $filename location of file
     */
    public function readJsonFile($filename)
    {
        return file_get_contents($filename.'.json', FILE_USE_INCLUDE_PATH);
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
            ->addAddress(Customer::ADDRESS_SHIPPING, 'Avenida Faria Lima', '2927', 'Itaim', 'Sao Paulo', 'SP', '01234000', '8');

        return $customer;
    }

    /**
     * Creates a account.
     *
     * @return Account
     */
    public function createAccount()
    {
        $moip = new Moip(new OAuth('1tldio91gi74r34zv30d4saz8yuuws5'), Moip::ENDPOINT_SANDBOX);

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
     * Creates a multiorder.
     *
     * @return Multiorders
     */
    public function createMultiorder()
    {
        if ($this->sandbox_mock == self::SANDBOX) {
            $this->last_ord_id = uniqid('MOR-');
        } else {
            $this->last_ord_id = 'meu_id_pedido';
        }

        $order = $this->moip->orders()->setOwnId(uniqid())
            ->addItem('bicicleta 1', 1, 'sku1', 10000)
            ->addItem('bicicleta 2', 1, 'sku2', 11000)
            ->addItem('bicicleta 3', 1, 'sku3', 12000)
            ->addItem('bicicleta 4', 1, 'sku4', 13000)
            ->setShippingAmount(3000)
            ->setAddition(1000)
            ->setDiscount(5000)
            ->setCustomer($this->createCustomer())
            ->addReceiver('MPA-VB5OGTVPCI52', 'PRIMARY', null);

        $order2 = $this->moip->orders()->setOwnId(uniqid())
            ->addItem('bicicleta 1', 1, 'sku1', 10000)
            ->addItem('bicicleta 2', 1, 'sku2', 11000)
            ->addItem('bicicleta 3', 1, 'sku3', 12000)
            ->setShippingAmount(3000)
            ->setAddition(1000)
            ->setDiscount(5000)
            ->setCustomer($this->createCustomer())
            ->addReceiver('MPA-IFYRB1HBL73Z', 'PRIMARY', null);

        $multiorder = $this->moip->multiorders()
                ->setOwnId(uniqid())
                ->addOrder($order)
                ->addOrder($order2);

        return $multiorder;
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
