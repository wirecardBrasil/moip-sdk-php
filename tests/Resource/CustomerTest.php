<?php

namespace Moip\Tests\Resource;

use Moip\Tests\MoipTestCase;

/**
 * class CustomerTest.
 */
class CustomerTest extends MoipTestCase
{
    private $date_format = 'Y-m-d';
    /**
     * @var string $date_string date used for testing
     */
    private $date_string = '1988-18-01';

    /**
     * Test if the Customer object accepts a \DateTime object and correctly transforms it.
     */
    public function testSetBirthDateDateTime()
    {
        $dt = \DateTime::createFromFormat($this->date_format, $this->date_string);
        $customer = $this->moip->customers()->setBirthDate($dt);
        $this->assertEquals($dt, $customer->getBirthDate());
        $exp = "{'birthDate':'$this->date_string'}";
        $this->assertJsonStringEqualsJsonString($exp, json_encode($customer));
    }

    /**
     * Test if the Customer object accepts a date string as argument.
     */
    public function testSetBirthDateString()
    {
        $customer = $this->moip->customers()->setBirthDate($this->date_string);
        $exp = "{'birthDate':'$this->date_string'}";
        $this->assertEquals($customer->getBirthDate()->format($this->date_format), $this->date_string);
        $this->assertJsonStringEqualsJsonString($exp, json_encode($customer));
    }
}
