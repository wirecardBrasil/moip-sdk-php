<?php

namespace Moip\Tests;

use Mockery as m;
use Moip\Moip;
use Moip\MoipAuthentication;
use PHPUnit_Framework_TestCase as TestCase;

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
        $this->moip = new Moip(m::mock(MoipAuthentication::class));
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
