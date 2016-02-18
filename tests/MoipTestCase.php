<?php

namespace Moip\Tests;

use Mockery as m;
use Moip\Moip;
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
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        m::close();
        $this->moip = null;
    }
}
