<?php

namespace Moip\Tests;

use Moip\Exceptions;
use Moip\Helper\Utils;
use Moip\Moip;
use Requests_Exception;

/**
 * class MoipTest.
 */
class MoipTest extends TestCase
{
    /**
     * MoipTest should return instance of \Moip\Resource\Customer.
     */
    public function testShouldReceiveInstanceOfCustomer()
    {
        $customer = new \Moip\Resource\Customer($this->moip);

        $this->assertEquals($customer, $this->moip->customers());
    }

    /**
     * MoipTest should return instance of \Moip\Resource\Account.
     */
    public function testShouldReceiveInstanceOfAccount()
    {
        $account = new \Moip\Resource\Account($this->moip);

        $this->assertEquals($account, $this->moip->accounts());
    }

    /**
     * MoipTest should return instance of \Moip\Resource\Entry.
     */
    public function testShouldReceiveInstanceOfEntry()
    {
        $entry = new \Moip\Resource\Entry($this->moip);

        $this->assertEquals($entry, $this->moip->entries());
    }

    /**
     * MoipTest should return instance of \Moip\Resource\Orders.
     */
    public function testShouldReceiveInstanceOfOrders()
    {
        $orders = new \Moip\Resource\Orders($this->moip);

        $this->assertEquals($orders, $this->moip->orders());
    }

    /**
     * MoipTest should return instance of \Moip\Resource\Payment.
     */
    public function testShouldReceiveInstanceOfPayment()
    {
        $payment = new \Moip\Resource\Payment($this->moip);

        $this->assertEquals($payment, $this->moip->payments());
    }

    /**
     * MoipTest should return instance of \Moip\Resource\Multiorders.
     */
    public function testShouldReceiveInstanceOfMultiorders()
    {
        $multiorders = new \Moip\Resource\Multiorders($this->moip);

        $this->assertEquals($multiorders, $this->moip->multiorders());
    }

    /**
     * MoipTest if a \Moip\Exceptions\testShouldRaiseValidationException is thrown and is correctly constructed.
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
     * MoipTest if \Moip\Exceptios\UnautorizedException is thrown.
     *
     * @expectedException Moip\Exceptions\UnautorizedException
     */
    public function testShouldRaiseUnautorizedException()
    {
        if ($this->sandbox_mock == self::SANDBOX) {
            $this->markTestSkipped('Only testable in Mock mode');

            return;
        }
        $body = '{ "ERROR" : "Token or Key are invalids" }'; // the body is not processed in any way, i'm putting this for completeness
        $this->mockHttpSession($body, 401);
        $this->moip->orders()->get('ORD-1AWC30TWYZMX');
    }

    /**
     * MoipTest if UnexpectedException is thrown when 500 http status code is returned.
     *
     * @expectedException Moip\Exceptions\UnexpectedException
     */
    public function testShouldRaiseUnexpectedException500()
    {
        if ($this->sandbox_mock == self::SANDBOX) {
            $this->markTestSkipped('Only testable in Mock mode');

            return;
        }
        $this->mockHttpSession('error', 500); // the body isn't processed
        $this->moip->orders()->get('ORD-1AWC30TWYZMX');
    }

    /**
     * MoipTest if UnexpectedException is thrown when a Requests_Exception is thrown.
     */
    public function testShouldRaiseUnexpectedExceptionNetworkError()
    {
        if ($this->sandbox_mock == self::SANDBOX) {
            $this->markTestSkipped('Only testable in Mock mode');

            return;
        }
        $sess = $this->getMockBuilder('\Requests_Session')->getMock();
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
     * MoipTest if we can connect to the API endpoints.
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
     * MoipTest the convertion from money to cents using floats.
     */
    public function testToCents()
    {
        $cases = [
            [6.9, 690],
            [6.99, 699],
            [10.32, 1032],
            [10.329, 1032],
            [10.93, 1093],
            [10.931, 1093],
            [10.01, 1001],
            [10.09, 1009],
            [.1, 10],
            [.01, 1],
            [9.999, 999],
        ];

        foreach ($cases as $case) {
            list($actual, $expected) = $case;
            $actual = Utils::toCents($actual);

            $this->assertEquals($expected, $actual);
        }
    }

    public function testShouldGetEndpoint()
    {
        $expected = constant('\Moip\Moip::ENDPOINT_SANDBOX');

        $this->assertEquals($expected, $this->moip->getEndpoint());
    }
}
