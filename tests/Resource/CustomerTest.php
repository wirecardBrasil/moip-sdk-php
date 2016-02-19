<?php

namespace Moip\Tests\Resource;

use Moip\Resource\Customer;
use Moip\Tests\MoipTestCase;

/**
 * class CustomerTest.
 */
class CustomerTest extends MoipTestCase
{
    /**
     * @var string current format for dates.
     */
    private $date_format = 'Y-m-d';

    /**
     * @var string $date_string date used for testing.
     */
    private $date_string = '1989-06-01';

    /**
     * @var string $body response from the moip API.
     */
    private $body = '{"id":"CUS-CFMKXQBZNJQQ","ownId":"meu_id_sandbox","fullname":"Jose Silva","email":"jose_silva0@email.com","phone":{"countryCode":"55","areaCode":"11","number":"66778899"},"birthDate":"1989-06-01","taxDocument":{"type":"CPF","number":"22222222222"},"shippingAddress":{"street":"Avenida Faria Lima","streetNumber":"2927","complement":"8","city":"Sao Paulo","state":"SP","country":"BRA","zipCode":"01234000"},"fundingInstruments":[],"createdAt":"2016-02-18T19:55:00.000-02","_links":{"self":{"href":"https://sandbox.moip.com.br/v2/customers/CUS-CFMKXQBZNJQQ"}}}';

    /**
     * Test if the Customer object accepts a \DateTime object and correctly transforms it.
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
     * Test if the Customer object accepts a date string as argument.
     */
    public function testSetBirthDateString()
    {
        $customer = $this->moip->customers()->setBirthDate($this->date_string);
        $exp = "{\"birthDate\":\"$this->date_string\"}";
        $this->assertEquals($customer->getBirthDate()->format($this->date_format), $this->date_string);
        $this->assertJsonStringEqualsJsonString($exp, json_encode($customer));
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
     * Test customer creation.
     */
    public function testCustomerCreate()
    {
        $this->mockHttpSession($this->body);

        $customer_original = $this->createCustomer();
        /** @var Customer $customer */
        $customer = $customer_original->create();

        $this->assertEquals($customer_original->getFullname(), $customer->getFullname());
        $this->assertEquals($customer_original->getPhoneNumber(), $customer->getPhoneNumber());
        $this->assertEquals($customer_original->getBirthDate(), $customer->getBirthDate());
    }

    /**
     * Test customer shipping address
     */
    public function testShippingAddress()
    {
        $this->mockHttpSession($this->body);
        $customer_original = $this->createCustomer();
        $customer = $customer_original->create();
        /** @var Customer $customer */
        $this->assertEquals($customer_original->getShippingAddress()->street, $customer->getShippingAddress()->street);
        $this->assertEquals($customer_original->getShippingAddress()->streetNumber, $customer->getShippingAddress()->streetNumber);
        $this->assertEquals($customer_original->getShippingAddress()->complement, $customer->getShippingAddress()->complement);
        $this->assertEquals($customer_original->getShippingAddress()->city, $customer->getShippingAddress()->city);
        $this->assertEquals($customer_original->getShippingAddress()->state, $customer->getShippingAddress()->state);
        $this->assertEquals($customer_original->getShippingAddress()->country, $customer->getShippingAddress()->country);
        $this->assertEquals($customer_original->getShippingAddress()->zipCode, $customer->getShippingAddress()->zipCode);
    }
}
