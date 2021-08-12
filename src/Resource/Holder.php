<?php

namespace Moip\Resource;

use stdClass;

/**
 * Class Holder.
 */
class Holder extends MoipResource
{
    /**
     * Address Type.
     *
     * @const string
     */
    const ADDRESS_BILLING = 'BILLING';

    /**
     * Standard country .
     *
     * @const string
     */
    const ADDRESS_COUNTRY = 'BRA';

    /**
     * Standard document type.
     *
     * @const string
     */
    const TAX_DOCUMENT = 'CPF';

    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
    }

    /**
     * Add a new address to the holder.
     *
     * @param string $type       Address type: BILLING.
     * @param string $street     Street address.
     * @param string $number     Number address.
     * @param string $district   Neighborhood address.
     * @param string $city       City address.
     * @param string $state      State address.
     * @param string $zip        The zip code billing address.
     * @param string $complement Address complement.
     * @param string $country    Country ISO-alpha3 format, BRA example.
     *
     * @return $this
     */
    public function setAddress($type, $street, $number, $district, $city, $state, $zip, $complement = null, $country = self::ADDRESS_COUNTRY)
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

        $this->data->billingAddress = $address;

        return $this;
    }

    /**
     * Get holder address.
     *
     * @return \stdClass Holder's address.
     */
    public function getBillingAddress()
    {
        return $this->getIfSet('billingAddress');
    }

    /**
     * Get holser fullname.
     *
     * @return string Holder's full name.
     */
    public function getFullname()
    {
        return $this->getIfSet('fullname');
    }

    /**
     * Get birth date from holder.
     *
     * @return \DateTime|null Date of birth of the credit card holder.
     */
    public function getBirthDate()
    {
        return $this->getIfSetDate('birthDate');
    }

    /**
     * Get phone area code from holder.
     *
     * @return int DDD telephone.
     */
    public function getPhoneAreaCode()
    {
        return $this->getIfSet('areaCode', $this->data->phone);
    }

    /**
     * Get phone country code from holder.
     *
     * @return int Country code.
     */
    public function getPhoneCountryCode()
    {
        return $this->getIfSet('countryCode', $this->data->phone);
    }

    /**
     * Get phone number from holder.
     *
     * @return int Telephone number.
     */
    public function getPhoneNumber()
    {
        return $this->getIfSet('number', $this->data->phone);
    }

    /**
     * Get tax document type from holder.
     *
     * @return string Type of value: CPF and CNPJ
     */
    public function getTaxDocumentType()
    {
        return $this->getIfSet('type', $this->data->taxDocument);
    }

    /**
     * Get tax document number from holder.
     *
     * @return string Document Number.
     */
    public function getTaxDocumentNumber()
    {
        return $this->getIfSet('number', $this->data->taxDocument);
    }

    /**
     * Mount the buyer structure from holder.
     *
     * @param \stdClass $response
     *
     * @return Holder information.
     */
    protected function populate(stdClass $response)
    {
        $holder = clone $this;
        $holder->data = new stdClass();
        $holder->data->fullname = $this->getIfSet('fullname', $response);
        $holder->data->phone = new stdClass();

        $phone = $this->getIfSet('phone', $response);

        $holder->data->phone->countryCode = $this->getIfSet('countryCode', $phone);
        $holder->data->phone->areaCode = $this->getIfSet('areaCode', $phone);
        $holder->data->phone->number = $this->getIfSet('number', $phone);
        $holder->data->birthDate = $this->getIfSet('birthDate', $response);
        $holder->data->taxDocument = new stdClass();
        $holder->data->taxDocument->type = $this->getIfSet('type', $this->getIfSet('taxDocument', $response));
        $holder->data->taxDocument->number = $this->getIfSet('number', $this->getIfSet('taxDocument', $response));
        $holder->data->billingAddress = $this->getIfSet('billingAddress', $response);

        return $holder;
    }

    /**
     * Set fullname from holder.
     *
     * @param string $fullname Holder's full name.
     *
     * @return $this
     */
    public function setFullname($fullname)
    {
        $this->data->fullname = $fullname;

        return $this;
    }

    /**
     * Set birth date from holder.
     *
     * @param \DateTime|string $birthDate Date of birth of the credit card holder.
     *
     * @return $this
     */
    public function setBirthDate($birthDate)
    {
        if ($birthDate instanceof \DateTime) {
            $birthDate = $birthDate->format('Y-m-d');
        }

        $this->data->birthDate = $birthDate;

        return $this;
    }

    /**
     * Set tax document from holder.
     *
     * @param string $number Document number.
     * @param string $type   Document type.
     *
     * @return $this
     */
    public function setTaxDocument($number, $type = self::TAX_DOCUMENT)
    {
        $this->data->taxDocument = new stdClass();
        $this->data->taxDocument->type = $type;
        $this->data->taxDocument->number = $number;

        return $this;
    }

    /**
     * Set phone from holder.
     *
     * @param int $areaCode    DDD telephone.
     * @param int $number      Telephone number.
     * @param int $countryCode Country code.
     *
     * @return $this
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
