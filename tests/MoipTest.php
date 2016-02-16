<?php

namespace Moip\Tests;

use Moip\Exceptions;
use Moip\Moip;
use Requests_Exception;

/**
 * class MoipTest.
 */
class MoipTest extends MoipTestCase
{
    /**
     * Test if endpoint production is valid.
     */
    public function testShouldReceiveEndpointProductionIsValid()
    {
        $endpoint_production = 'api.moip.com.br';
        $const_endpoint_production = constant('\Moip\Moip::ENDPOINT_PRODUCTION');

        $this->assertEquals($endpoint_production, $const_endpoint_production);
    }

    /**
     * Test if endpoint sandbox is valid.
     */
    public function testShouldReceiveSandboxProductionIsValid()
    {
        $endpoint_sandbox = 'sandbox.moip.com.br';
        $const_endpoint_sandbox = constant('\Moip\Moip::ENDPOINT_SANDBOX');

        $this->assertEquals($endpoint_sandbox, $const_endpoint_sandbox);
    }

    /**
     * Test if const CLIENT is valid.
     */
    public function testShouldReceiveClientIsValid()
    {
        $client = 'Moip SDK';
        $const_client = constant('\Moip\Moip::CLIENT');

        $this->assertEquals($client, $const_client);
    }

    /**
     * test create connection.
     */
    public function testCreateConnection()
    {
        $http_connection = m::mock('\Moip\Http\HTTPConnection');
        $http_header_name = 'Accept';
        $http_header_value = 'application/json';

        $http_connection->shouldReceive('initialize')
            ->withArgs([$this->moip->getEndpoint(), true])
            ->once()
            ->andReturnNull();

        $http_connection->shouldReceive('addHeader')
            ->withArgs([$http_header_name, $http_header_value])
            ->once()
            ->andReturn(true);

        $http_connection->shouldReceive('setAuthenticator')
            ->once()
            ->andReturnNull();

        $this->assertEquals($http_connection, $this->moip->createConnection($http_connection));
    }

    /**
     * Test should return instance of \Moip\Resource\Customer.
     */
    public function testShouldReceiveInstanceOfCustomer()
    {
        $customer = new \Moip\Resource\Customer($this->moip);

        $this->assertEquals($customer, $this->moip->customers());
    }

    /**
     * Test should return instance of \Moip\Resource\Entry.
     */
    public function testShouldReceiveInstanceOfEntry()
    {
        $entry = new \Moip\Resource\Entry($this->moip);

        $this->assertEquals($entry, $this->moip->entries());
    }

    /**
     * Test should return instance of \Moip\Resource\Orders.
     */
    public function testShouldReceiveInstanceOfOrders()
    {
        $orders = new \Moip\Resource\Orders($this->moip);

        $this->assertEquals($orders, $this->moip->orders());
    }

    /**
     * Test should return instance of \Moip\Resource\Payment.
     */
    public function testShouldReceiveInstanceOfPayment()
    {
        $payment = new \Moip\Resource\Payment($this->moip);

        $this->assertEquals($payment, $this->moip->payments());
    }

    /**
     * Test should return instance of \Moip\Resource\Multiorders.
     */
    public function testShouldReceiveInstanceOfMultiorders()
    {
        $multiorders = new \Moip\Resource\Multiorders($this->moip);

        $this->assertEquals($multiorders, $this->moip->multiorders());
    }

    /**
     * Test if a \Moip\Exceptions\testShouldRaiseValidationException is thrown and is correctly constructed.
     */
    public function testShouldRaiseValidationException()
    {
        /*
         * WARNING FIXME:
         * The api has a bug right now it's return 'birthdateMatchesPattern' but thats wrong, it's supposed to return
         * customer.birthDate. I talked to the moip support team, they're aware of the bug and WILL be fixing this bug
         * wich means this test will eventually fail.
         */
        $body = '{"errors":[{"code":"CUS-007","path":"birthdateMatchesPattern","description":"O valor deve ser uma string"}]}';
        $model = json_decode($body);
        $error_model = $model->errors[0];
        $this->mockHttpSession($body, 400);
        try {
            $this->moip->customers()->setOwnId(uniqid())
                ->setFullname('Fulano teste')
                ->setEmail('teste@teste.com.br')
                ->setBirthDate('1111111')//invalid
                ->create();
        } catch (Exceptions\ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertCount(1, $errors);
            $error = $errors[0];
            $this->assertEquals($error_model->code, $error->getCode(), 'getCode didn\'t returned the expected value');
            $this->assertEquals($error_model->path, $error->getPath(), 'getPath didn\'t returned the expected value');

            return;
        }
        $this->fail('Exception testShouldRaiseValidationException not thrown');
    }

    /**
     * Test if \Moip\Exceptios\UnautorizedException is thrown.
     */
    public function testShouldRaiseUnautorizedException()
    {
        if ($this->sandbox_mock == self::SANDBOX) {
            $this->markTestSkipped('Only testable in Mock mode');

            return;
        }
        $this->setExpectedException('\Moip\Exceptions\UnautorizedException');
        $body = '{ "ERROR" : "Token or Key are invalids" }'; // the body is not processed in any way, i'm putting this for completeness
        $this->mockHttpSession($body, 401);
        $this->moip->orders()->get('ORD-1AWC30TWYZMX');
    }

    /**
     * Test if UnexpectedException is thrown when 500 http status code is returned.
     */
    public function testShouldRaiseUnexpectedException500()
    {
        if ($this->sandbox_mock == self::SANDBOX) {
            $this->markTestSkipped('Only testable in Mock mode');

            return;
        }
        $this->setExpectedException('\Moip\Exceptions\UnexpectedException');
        $this->mockHttpSession('error', 500); // the body isn't processed
        $this->moip->orders()->get('ORD-1AWC30TWYZMX');
    }

    /**
     * Test if UnexpectedException is thrown when a Requests_Exception is thrown.
     */
    public function testShouldRaiseUnexpectedExceptionNetworkError()
    {
        if ($this->sandbox_mock == self::SANDBOX) {
            $this->markTestSkipped('Only testable in Mock mode');

            return;
        }
        $sess = $this->getMock('\Requests_Session');
        $sess->expects($this->once())->method('request')->willThrowException(new Requests_Exception('test error',
            'test'));
        $this->moip->setSession($sess);
        try {
            $this->moip->orders()->get('ORD-1AWC30TWYZMX');
        } catch (Exceptions\UnexpectedException $e) {
            // test exception chaining
            $this->assertInstanceOf('Requests_Exception', $e->getPrevious());

            return;
        }
        $this->fail('Exception was not thrown');
    }

    /**
     * Test if we can connect to the API endpoints.
     * This is primarily to make user we are using HTTPS urls and the certification verification is ok.
     */
    public function testConnectEndPoints()
    {
        // create a valid session
        $this->moip->createNewSession();
        $sess = $this->moip->getSession();
        $requests = [['url' => Moip::ENDPOINT_PRODUCTION], ['url' => Moip::ENDPOINT_SANDBOX]];
        $resps = $sess->request_multiple($requests);
        $this->assertEquals('WELCOME', $resps[0]->body);
        $this->assertEquals('WELCOME', $resps[1]->body);
    }

    /**
     * Test the convertion from money to cents
     */

    public function testToCents(){

        $cases = [
            [6.9, 690],
            [6.99, 699],
            [10.32, 1032],
            [10.329, 1032],
            [10.93, 1093],
            [10.931, 1093],
        ];

        foreach($cases as $case){

            list($actual, $expected) = $case;
            $actual = Utils::toCents($actual);

            $this->assertEquals($expected, $actual);


        }


    }
}
