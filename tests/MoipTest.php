<?php

namespace Moip\Tests;

use Moip\Exceptions;
use Requests_Exception;

/**
 * class MoipTest.
 */
class MoipTest extends MoipTestCase
{

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
        $body = '{"errors":[{"code":"API-1","path":"customer.birthDate","description":"O valor deve ser uma string"}]}';
        $model = json_decode($body);
        $error_model = $model->errors[0];
        $this->mockHttpSession($body, 400);
        try {
            $this->moip->orders()->get('ORD-1AWC30TWYZMX'); //the id doesn't matter because this will return the mocked body
        } catch (Exceptions\ValidationException $e) {
            $errors = $e->getErrors();
            $this->assertCount(1, $errors);
            $error = $errors[0];
            $this->assertEquals($error_model->code, $error->getCode(), 'getCode didn\'t returned the expected value');
            $this->assertEquals($error_model->path, $error->getPath(), 'getPath didn\'t returned the expected value');
            $this->assertEquals($error_model->description, $error->getDescription(),
                'getDescription didn\'t returned the expected value');
            return;
        }
        $this->fail('Exception testShouldRaiseValidationException not thrown');
    }

    /**
     * Test if \Moip\Exceptios\UnautorizedException is thrown.
     */
    public function testShouldRaiseUnautorizedException()
    {
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
        $this->setExpectedException('\Moip\Exceptions\UnexpectedException');
        $this->mockHttpSession('error', 500); // the body isn't processed
        $this->moip->orders()->get('ORD-1AWC30TWYZMX');
    }

    /**
     * Test if UnexpectedException is thrown when a Requests_Exception is thrown.
     */
    public function testShouldRaiseUnexpectedExceptionNetworkError()
    {
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
}
