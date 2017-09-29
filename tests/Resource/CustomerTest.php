<?php

namespace Moip\Tests\Resource;

use Moip\Resource\Customer;
use Moip\Tests\TestCase;

/**
 * class CustomerTest.
 */
class CustomerTest extends TestCase
{
    /**
     * MoipTest if the Customer object accepts a \DateTime object and correctly transforms it.
     */
    public function testSetBirthDateDateTime()
    {
        $dt = \DateTime::createFromFormat($this->date_format, $this->date_string);
        $customer = $this->moip->customers()->setBirthDate($dt);
        $this->assertEquals($dt, $customer->getBirthDate());
        $exp = "{\"birthDate\":\"$this->date_string\"}";
        $this->assertJsonStringEqualsJsonString($exp, json_encode($customer));
    }

    /**
     * MoipTest if the Customer object accepts a date string as argument.
     */
    public function testSetBirthDateString()
    {
        $customer = $this->moip->customers()->setBirthDate($this->date_string);
        $exp = "{\"birthDate\":\"$this->date_string\"}";
        $this->assertEquals($customer->getBirthDate()->format($this->date_format), $this->date_string);
        $this->assertJsonStringEqualsJsonString($exp, json_encode($customer));
    }

    /**
     * MoipTest customer creation.
     */
    public function testCustomerCreate()
    {
        $this->mockHttpSession($this->body_client);

        $customer_original = $this->createCustomer();
        /** @var Customer $customer */
        $customer = $customer_original->create();

        $this->assertEquals($customer_original->getFullname(), $customer->getFullname());
        $this->assertEquals($customer_original->getPhoneNumber(), $customer->getPhoneNumber());
        $this->assertEquals($customer_original->getBirthDate(), $customer->getBirthDate());
    }

    /**
     * MoipTest customer shipping address.
     */
    public function testShippingAddress()
    {
        $this->mockHttpSession($this->body_client);
        $customer_original = $this->createCustomer();
        $customer = $customer_original->create();
        /* @var Customer $customer */
        $this->assertEquals($customer_original->getShippingAddress()->street, $customer->getShippingAddress()->street);
        $this->assertEquals($customer_original->getShippingAddress()->streetNumber, $customer->getShippingAddress()->streetNumber);
        $this->assertEquals($customer_original->getShippingAddress()->complement, $customer->getShippingAddress()->complement);
        $this->assertEquals($customer_original->getShippingAddress()->city, $customer->getShippingAddress()->city);
        $this->assertEquals($customer_original->getShippingAddress()->state, $customer->getShippingAddress()->state);
        $this->assertEquals($customer_original->getShippingAddress()->country, $customer->getShippingAddress()->country);
        $this->assertEquals($customer_original->getShippingAddress()->zipCode, $customer->getShippingAddress()->zipCode);
    }

    /**
     * MoipTest create credit card for customer.
     */
    public function testCreateCreditCard()
    {
        $this->mockHttpSession($this->body_client);
        $customer = $this->createCustomer()->create();

        $this->mockHttpSession($this->body_add_credit_card);

        $creditCard = $this->moip->customers()->creditCard()
            ->setExpirationMonth('05')
            ->setExpirationYear(2018)
            ->setNumber('4012001037141112')
            ->setCVC('123')
            ->setFullName('Jose Portador da Silva')
            ->setBirthDate('1988-12-30')
            ->setTaxDocument('CPF', '33333333333')
            ->setPhone('55', '11', '66778899')
            ->create($customer->getId());

        $this->assertNotEmpty($creditCard->getId());
        $this->assertEquals($creditCard->getFirst6(), '401200');
        $this->assertEquals($creditCard->getLast4(), '1112');
    }
}
