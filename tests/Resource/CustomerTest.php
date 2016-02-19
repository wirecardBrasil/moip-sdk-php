<?php

namespace Moip\Tests\Resource;

use Moip\Tests\MoipTestCase;

/**
 * class CustomerTest.
 */
class CustomerTest extends MoipTestCase
{
    /**
     * Test if const \Moip\Resource\Customer::PATH is valid.
     */
    public function testShouldReceiveConstPathIsValid()
    {
        $path = 'customers';
        $const_path = constant('\Moip\Resource\Customer::PATH');

        $this->assertEquals($path, $const_path);
    }

    /**
     * Test if const \Moip\Resource\Customer::ADDRESS_BILLING is valid.
     */
    public function testShouldReceiveConstAddressBillingIsValid()
    {
        $expected = 'BILLING';
        $actual = constant('\Moip\Resource\Customer::ADDRESS_BILLING');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test if const \Moip\Resource\Customer::ADDRESS_SHIPPING is valid.
     */
    public function testShouldReceiveConstAddressShippingIsValid()
    {
        $expected = 'SHIPPING';
        $actual = constant('\Moip\Resource\Customer::ADDRESS_SHIPPING');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test if const \Moip\Resource\Customer::ADDRESS_COUNTRY is valid.
     */
    public function testShouldReceiveConstCountryIsValid()
    {
        $expected = 'BRA';
        $actual = constant('\Moip\Resource\Customer::ADDRESS_COUNTRY');

        $this->assertEquals($expected, $actual);
    }

    /**
     * Test if const \Moip\Resource\Customer::TAX_DOCUMENT is valid.
     */
    public function testShouldReceiveConstTaxDocumentIsValid()
    {
        $tax_document = 'CPF';
        $const_tax_document = constant('\Moip\Resource\Customer::TAX_DOCUMENT');

        $this->assertEquals($tax_document, $const_tax_document);
    }
}
