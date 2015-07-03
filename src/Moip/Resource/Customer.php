<?php

namespace Moip\Resource;

use stdClass;
use Moip\Http\HTTPRequest;

class Customer extends MoipResource
{
    public function initialize()
    {
        $this->data = new stdClass();
    }

    /**
     * @deprecated Use setBillingAddress or setShippingAddress methods instead
     */
    public function addAddress($type, $street, $number, $district, $city, $state, $zip, $complement = null, $country = 'BRA')
    {
        $address = new stdClass();
        $address->street = $street;
        $address->streetNumber = $number;
        $address->complement = $complement;
        $address->district = $district;
        $address->city = $city;
        $address->state = $state;
        $address->country = $country;
        $address->zipCode = $zip;

        switch ($type) {
            case 'BILLING':
                $this->data->billingAddress = $address;
                break;
            case 'SHIPPING':
                $this->data->shippingAddress = $address;
                break;
            default:
                throw new \UnexpectedValueException(sprintf('%s não é um tipo de endereço válido', $type));
                break;
        }

        return $this;
    }

    public function create()
    {
        $body = json_encode($this, JSON_UNESCAPED_SLASHES);

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

    public function getId()
    {
        return $this->getIfSet('id');
    }

    public function getBillingAddress()
    {
        return $this->getIfSet('billingAddress');
    }

    public function getShippingAddress()
    {
        return $this->getIfSet('shippingAddress');
    }

    public function getFullname()
    {
        return $this->getIfSet('fullname');
    }

    public function getFundingInstrument()
    {
        return $this->getIfSet('fundingInstrument');
    }

    public function getBirthDate()
    {
        return $this->getIfSet('birthDate');
    }

    public function getPhoneAreaCode()
    {
        return $this->getIfSet('areaCode', $this->data->phone);
    }

    public function getPhoneCountryCode()
    {
        return $this->getIfSet('countryCode', $this->data->phone);
    }

    public function getPhoneNumber()
    {
        return $this->getIfSet('number', $this->data->phone);
    }

    public function getTaxDocumentType()
    {
        return $this->getIfSet('type', $this->data->taxDocument);
    }

    public function getTaxDocumentNumber()
    {
        return $this->getIfSet('number', $this->data->taxDocument);
    }

    protected function populate(stdClass $response)
    {
        $customer = clone $this;
        $customer->data = new stdClass();
        $customer->data->id = $this->getIfSet('id', $response);
        $customer->data->ownId = $this->getIfSet('ownId', $response);
        $customer->data->fullname = $this->getIfSet('fullname', $response);
        $customer->data->email = $this->getIfSet('email', $response);
        $customer->data->phone = new stdClass();

        $phone = $this->getIfSet('phone', $response);

        $customer->data->phone->countryCode = $this->getIfSet('countryCode', $phone);
        $customer->data->phone->areaCode = $this->getIfSet('areaCode', $phone);
        $customer->data->phone->number = $this->getIfSet('number', $phone);
        $customer->data->birthDate = $this->getIfSet('birthDate', $response);
        $customer->data->taxDocument = new stdClass();
        $customer->data->taxDocument->type = $this->getIfSet('type', $response->taxDocument);
        $customer->data->taxDocument->number = $this->getIfSet('number', $response->taxDocument);
        $customer->data->addresses = array();
        $customer->data->shippingAddress = $this->getIfSet('shippingAddress', $response);
        $customer->data->billingAddress = $this->getIfSet('billingAddress', $response);
        $customer->data->fundingInstrument = $this->getIfSet('fundingInstrument', $response);

        $customer->data->_links = $this->getIfSet('_links', $response);

        return $customer;
    }

    public function setOwnId($ownId)
    {
        $this->data->ownId = $ownId;

        return $this;
    }

    public function setBillingAddress($street, $number, $district, $city, $state, $zip, $complement = null, $country = 'BRA')
    {
        $this->data->billingAddress = new stdClass();
        $this->data->billingAddress->street = $street;
        $this->data->billingAddress->streetNumber = $number;
        $this->data->billingAddress->complement = $complement;
        $this->data->billingAddress->district = $district;
        $this->data->billingAddress->city = $city;
        $this->data->billingAddress->state = $state;
        $this->data->billingAddress->country = $country;
        $this->data->billingAddress->zipCode = $zip;

        return $this;
    }

    public function setShippingAddress($street, $number, $district, $city, $state, $zip, $complement = null, $country = 'BRA')
    {
        $this->data->shippingAddress = new stdClass();
        $this->data->shippingAddress->street = $street;
        $this->data->shippingAddress->streetNumber = $number;
        $this->data->shippingAddress->complement = $complement;
        $this->data->shippingAddress->district = $district;
        $this->data->shippingAddress->city = $city;
        $this->data->shippingAddress->state = $state;
        $this->data->shippingAddress->country = $country;
        $this->data->shippingAddress->zipCode = $zip;

        return $this;
    }

    public function setFullname($fullname)
    {
        $this->data->fullname = $fullname;

        return $this;
    }

    public function setEmail($email)
    {
        $this->data->email = $email;

        return $this;
    }

    public function setCreditCard($expirationMonth, $expirationYear, $number, $cvc, Customer $holder = null)
    {
        if ($holder === null) {
            $holder = $this;
        }

        $this->data->fundingInstrument = new stdClass();
        $this->data->fundingInstrument->method = 'CREDIT_CARD';
        $this->data->fundingInstrument->creditCard = new stdClass();
        $this->data->fundingInstrument->creditCard->expirationMonth = $expirationMonth;
        $this->data->fundingInstrument->creditCard->expirationYear = $expirationYear;
        $this->data->fundingInstrument->creditCard->number = $number;
        $this->data->fundingInstrument->creditCard->cvc = $cvc;
        $this->data->fundingInstrument->creditCard->holder = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->fullname = $holder->getFullname();
        $this->data->fundingInstrument->creditCard->holder->birthdate = $holder->getBirthDate();
        $this->data->fundingInstrument->creditCard->holder->taxDocument = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->taxDocument->type = $holder->getTaxDocumentType();
        $this->data->fundingInstrument->creditCard->holder->taxDocument->number = $holder->getTaxDocumentNumber();
        $this->data->fundingInstrument->creditCard->holder->phone = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->phone->countryCode = $holder->getPhoneCountryCode();
        $this->data->fundingInstrument->creditCard->holder->phone->areaCode = $holder->getPhoneAreaCode();
        $this->data->fundingInstrument->creditCard->holder->phone->number = $holder->getPhoneNumber();

        return $this;
    }

    public function setBirthDate($birthDate)
    {
        $this->data->birthDate = $birthDate;

        return $this;
    }

    public function setTaxDocument($number, $type = 'CPF')
    {
        $this->data->taxDocument = new stdClass();
        $this->data->taxDocument->type = $type;
        $this->data->taxDocument->number = $number;

        return $this;
    }

    public function setPhone($areaCode, $number, $countryCode = 55)
    {
        $this->data->phone = new stdClass();
        $this->data->phone->countryCode = $countryCode;
        $this->data->phone->areaCode = $areaCode;
        $this->data->phone->number = $number;

        return $this;
    }
}
