<?php

namespace Moip\Resource;

use stdClass;
use Moip\Http\HTTPRequest;

class Customer extends MoipResource
{
    /**
     * Initialize a new instance
     */
    public function initialize()
    {
        $this->data = new stdClass();
    }

    /**
     * Add a new address to the customer.
     * 
     * @param string $type       Type of values: SHIPPING and BILLING.
     * @param string $street     
     * @param string $number     
     * @param string $district   
     * @param string $city       
     * @param string $state      
     * @param string $zip        
     * @param string $complement 
     * @param string $country    
     * 
     * @return $this;
     */
    public function addAddress($type, $street, $number, $district, $city, $state, $zip, $complement = null, $country = 'BRA')
    {
        $address                 = new stdClass();
        $address->type           = $type;
        $address->street         = $street;
        $address->streetNumber   = $number;
        $address->complement     = $complement;
        $address->district       = $district;
        $address->city           = $city;
        $address->state          = $state;
        $address->country        = $country;
        $address->zipCode        = $zip;
        $this->data->addresses[] = $address;

        return $this;
    }

    /**
     * Create a new customer.
     * 
     * @return \stdClass Customer information
     */
    public function create()
    {
        $body = json_encode($this);

        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');
        $httpConnection->addHeader('Content-Length', strlen($body));
        $httpConnection->setRequestBody($body);

        $httpResponse = $httpConnection->execute('/v2/customers', HTTPRequest::POST);

        if ($httpResponse->getStatusCode() != 201) {
            throw new \RuntimeException($httpResponse->getStatusMessage(), $httpResponse->getStatusCode());
        }

        return $this->populate(json_decode($httpResponse->getContent()));
    }


    /**
     * Find a customer.
     * 
     * @param  string $id
     * @return \stdClass
     */
    public function get($id)
    {
        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');

        $httpResponse = $httpConnection->execute('/v2/customers/'.$id, HTTPRequest::GET);

        if ($httpResponse->getStatusCode() != 200) {
            throw new \RuntimeException($httpResponse->getStatusMessage(), $httpResponse->getStatusCode());
        }

        return $this->populate(json_decode($httpResponse->getContent()));
    }

    /**
     * Get customer id.
     * 
     * @return strign The buyer id.
     */
    public function getId()
    {
        return $this->getIfSet('id');
    }

    /**
     * Get customer fullname.
     * 
     * @return string Customer's full name.
     */
    public function getFullname()
    {
        return $this->getIfSet('fullname');
    }

    /**
     * Get funding instrument from customer.
     * 
     * @return \stdClass Structure that is the means of payment.
     */
    public function getFundingInstrument()
    {
        return $this->getIfSet('fundingInstrument');
    }

    /**
     * Get birth date from customer.
     * 
     * @return date Date of birth of the credit card holder.
     */
    public function getBirthDate()
    {
        return $this->getIfSet('birthDate');
    }

    /**
     * Get phone area code from customer.
     * 
     * @return insteger DDD telephone.
     */
    public function getPhoneAreaCode()
    {
        return $this->getIfSet('areaCode', $this->data->phone);
    }

    /**
     * Get phone country code from customer.
     * 
     * @return integer Country code.
     */
    public function getPhoneCountryCode()
    {
        return $this->getIfSet('countryCode', $this->data->phone);
    }

    /**
     * Get phone number from customer.
     * 
     * @return integer Telephone number.
     */
    public function getPhoneNumber()
    {
        return $this->getIfSet('number', $this->data->phone);
    }

    /**
     * Get tax document type from customer.
     * 
     * @return string Type of value: CPF and CNPJ
     */
    public function getTaxDocumentType()
    {
        return $this->getIfSet('type', $this->data->taxDocument);
    }

    /**
     * Get tax document number from customer.
     * 
     * @return string Document Number.
     */
    public function getTaxDocumentNumber()
    {
        return $this->getIfSet('number', $this->data->taxDocument);
    }

    /**
     * Get the address from customer.
     * 
     * @param  \stdClass $response
     * @return \stdClass Customer's address.
     */
    private function getAddresses(stdClass $response)
    {
        if (isset($response->shippingAddress)) {
            $response->addresses[] = $response->shippingAddress;
        }

        if (isset($response->billingAddress)) {
            $response->addresses[] = $response->billingAddress;
        }

        return $response->addresses;
    }

    /**
     * Mount the buyer structure from customer.
     * 
     * @param  \stdClass $response
     * @return \stdClass Customer information.
     */
    protected function populate(stdClass $response)
    {
        $customer                            = clone $this;
        $customer->data                      = new stdClass();
        $customer->data->id                  = $this->getIfSet('id', $response);
        $customer->data->ownId               = $this->getIfSet('ownId', $response);
        $customer->data->fullname            = $this->getIfSet('fullname', $response);
        $customer->data->email               = $this->getIfSet('email', $response);
        $customer->data->phone               = new stdClass();
        $customer->data->phone->countryCode  = $this->getIfSet('countryCode', $response->phone);
        $customer->data->phone->areaCode     = $this->getIfSet('areaCode', $response->phone);
        $customer->data->phone->number       = $this->getIfSet('number', $response->phone);
        $customer->data->birthDate           = $this->getIfSet('birthDate', $response);
        $customer->data->taxDocument         = new stdClass();
        $customer->data->taxDocument->type   = $this->getIfSet('type', $response->taxDocument);
        $customer->data->taxDocument->number = $this->getIfSet('number', $response->taxDocument);
        $customer->data->addresses           = array();

        $response->addresses = $this->getAddresses($response);

        foreach ($response->addresses as $responseAddress) {
            $address               = new stdClass();
            $address->type         = $this->getIfSet('type', $responseAddress);
            $address->street       = $this->getIfSet('street', $responseAddress);
            $address->streetNumber = $this->getIfSet('streetNumber', $responseAddress);
            $address->complement   = $this->getIfSet('complement', $responseAddress);
            $address->district     = $this->getIfSet('district', $responseAddress);
            $address->city         = $this->getIfSet('city', $responseAddress);
            $address->state        = $this->getIfSet('state', $responseAddress);
            $address->country      = $this->getIfSet('country', $responseAddress);
            $address->zipCode      = $this->getIfSet('zipCode', $responseAddress);

            $customer->data->addresses[] = $address;
        }

        $customer->data->_links = $this->getIfSet('_links', $response);

        if (isset($response->fundingInstrument)) {
            $customer->data->fundingInstrument = $response->fundingInstrument;
        }

        return $customer;
    }

    /**
     * Set Own id from customer.
     * 
     * @param strign $ownId Customer's own id. external reference.
     * @return $this
     */
    public function setOwnId($ownId)
    {
        $this->data->ownId = $ownId;

        return $this;
    }

    /**
     * Set fullname from customer.
     * 
     * @param string $fullname Customer's full name.
     * @return $this
     */
    public function setFullname($fullname)
    {
        $this->data->fullname = $fullname;

        return $this;
    }

    /**
     * Set e-mail from customer.
     * 
     * @param string $email Email customer.
     * @return $this
     */
    public function setEmail($email)
    {
        $this->data->email = $email;

        return $this;
    }

    /**
     * Set credit card from customer.
     * 
     * @param insteger      $expirationMonth Card expiration month.
     * @param insteger      $expirationYear  Year card expiration.
     * @param insteger      $number          Card number.
     * @param insteger      $cvc             Card Security Code.
     * @param Customer|null $holder          Cardholder.
     * @return $this
     */
    public function setCreditCard($expirationMonth, $expirationYear, $number, $cvc, Customer $holder = null)
    {
        if ($holder === null) {
            $holder = $this;
        }

        $this->data->fundingInstrument                                          = new stdClass();
        $this->data->fundingInstrument->method                                  = 'CREDIT_CARD';
        $this->data->fundingInstrument->creditCard                              = new stdClass();
        $this->data->fundingInstrument->creditCard->expirationMonth             = $expirationMonth;
        $this->data->fundingInstrument->creditCard->expirationYear              = $expirationYear;
        $this->data->fundingInstrument->creditCard->number                      = $number;
        $this->data->fundingInstrument->creditCard->cvc                         = $cvc;
        $this->data->fundingInstrument->creditCard->holder                      = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->fullname            = $holder->getFullname();
        $this->data->fundingInstrument->creditCard->holder->birthdate           = $holder->getBirthDate();
        $this->data->fundingInstrument->creditCard->holder->taxDocument         = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->taxDocument->type   = $holder->getTaxDocumentType();
        $this->data->fundingInstrument->creditCard->holder->taxDocument->number = $holder->getTaxDocumentNumber();
        $this->data->fundingInstrument->creditCard->holder->phone               = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->phone->countryCode  = $holder->getPhoneCountryCode();
        $this->data->fundingInstrument->creditCard->holder->phone->areaCode     = $holder->getPhoneAreaCode();
        $this->data->fundingInstrument->creditCard->holder->phone->number       = $holder->getPhoneNumber();

        return $this;
    }

    /**
     * Set birth date from customer.
     * 
     * @param date $birthDate Date of birth of the credit card holder.
     * @return  $this
     */
    public function setBirthDate($birthDate)
    {
        $this->data->birthDate = $birthDate;

        return $this;
    }

    /**
     * Set tax document from customer.
     * 
     * @param integer $number Document number.
     * @param string $type    Document type.
     * @return $this
     */
    public function setTaxDocument($number, $type = 'CPF')
    {
        $this->data->taxDocument = new stdClass();
        $this->data->taxDocument->type = $type;
        $this->data->taxDocument->number = $number;

        return $this;
    }

    /**
     * Set phone from customer
     * 
     * @param [type]  $areaCode    DDD telephone.
     * @param [type]  $number      Telephone number.
     * @param integer $countryCode Country code.
     * @return  $this
     */
    public function setPhone($areaCode, $number, $countryCode = 55)
    {
        $this->data->phone = new stdClass();
        $this->data->phone->countryCode = $countryCode;
        $this->data->phone->areaCode = $areaCode;
        $this->data->phone->number = $number;

        return $this;
    }
}
