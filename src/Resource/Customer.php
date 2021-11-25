<?php

namespace Moip\Resource;

use stdClass;
use UnexpectedValueException;

/**
 * Class Customer.
 */
class Customer extends MoipResource
{
    /**
     * Path customers API.
     *
     * @const string
     */
    const PATH = 'customers';

    /**
     * Address Type.
     *
     * @const string
     */
    const ADDRESS_BILLING = 'BILLING';

    /**
     * Address Type.
     *
     * @const string
     */
    const ADDRESS_SHIPPING = 'SHIPPING';

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
     * @return \Moip\Resource\CustomerCreditCard
     */
    public function creditCard()
    {
        return new CustomerCreditCard($this->moip);
    }

    /**
     * Add a new address to the customer.
     *
     * @param string $type       Address type: SHIPPING or BILLING.
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
    public function addAddress($type, $street, $number, $district, $city, $state, $zip, $complement = null, $country = self::ADDRESS_COUNTRY)
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
            case self::ADDRESS_BILLING:
                $this->data->billingAddress = $address;
                break;
            case self::ADDRESS_SHIPPING:
                $this->data->shippingAddress = $address;
                break;
            default:
                throw new UnexpectedValueException(sprintf('%s não é um tipo de endereço válido', $type));
        }

        return $this;
    }

    /**
     * Create a new customer.
     *
     * @return \stdClass
     */
    public function create()
    {
        return $this->createResource(sprintf('/%s/%s/', MoipResource::VERSION, self::PATH));
    }

    /**
     * Find a customer.
     *
     * @param string $moip_id
     *
     * @return \Moip\Resource\Customer|stdClass
     */
    public function get($moip_id)
    {
        return $this->getByPath(sprintf('/%s/%s/%s', MoipResource::VERSION, self::PATH, $moip_id));
    }

    /**
     * Get customer id.
     *
     * @return string The buyer id.
     */
    public function getId()
    {
        return $this->getIfSet('id');
    }

    /**
     * Get customer address.
     *
     * @return \stdClass Customer's address.
     */
    public function getBillingAddress()
    {
        return $this->getIfSet('billingAddress');
    }

    /**
     * Get customer address.
     *
     * @return \stdClass Customer's address.
     */
    public function getShippingAddress()
    {
        return $this->getIfSet('shippingAddress');
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
     * @return \DateTime|null Date of birth of the credit card holder.
     */
    public function getBirthDate()
    {
        return $this->getIfSetDate('birthDate');
    }

    /**
     * Get phone area code from customer.
     *
     * @return int DDD telephone.
     */
    public function getPhoneAreaCode()
    {
        return $this->getIfSet('areaCode', $this->data->phone);
    }

    /**
     * Get phone country code from customer.
     *
     * @return int Country code.
     */
    public function getPhoneCountryCode()
    {
        return $this->getIfSet('countryCode', $this->data->phone);
    }

    /**
     * Get phone number from customer.
     *
     * @return int Telephone number.
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
     * Mount the buyer structure from customer.
     *
     * @param \stdClass $response
     *
     * @return Customer Customer information.
     */
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
        $customer->data->taxDocument->type = $this->getIfSet('type', $this->getIfSet('taxDocument', $response));
        $customer->data->taxDocument->number = $this->getIfSet('number', $this->getIfSet('taxDocument', $response));
        $customer->data->addresses = [];
        $customer->data->shippingAddress = $this->getIfSet('shippingAddress', $response);
        $customer->data->billingAddress = $this->getIfSet('billingAddress', $response);
        $customer->data->fundingInstrument = $this->getIfSet('fundingInstrument', $response);

        $customer->data->_links = $this->getIfSet('_links', $response);

        return $customer;
    }

    /**
     * Set Own id from customer.
     *
     * @param string $ownId Customer's own id. external reference.
     *
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
     *
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
     *
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
     * @param int                          $expirationMonth Card expiration month.
     * @param int                          $expirationYear  Year card expiration.
     * @param int                          $number          Card number.
     * @param int                          $cvc             Card Security Code.
     * @param \Moip\Resource\Customer|null $holder          Cardholder.
     *
     * @return $this
     */
    public function setCreditCard($expirationMonth, $expirationYear, $number, $cvc, Holder $holder = null)
    {
        if ($holder === null) {
            $holder = $this;
        }
        $birthdate = $holder->getBirthDate();
        if ($birthdate instanceof \DateTime) {
            $birthdate = $birthdate->format('Y-m-d');
        }

        $this->data->fundingInstrument = new stdClass();
        $this->data->fundingInstrument->method = Payment::METHOD_CREDIT_CARD;
        $this->data->fundingInstrument->creditCard = new stdClass();
        $this->data->fundingInstrument->creditCard->expirationMonth = $expirationMonth;
        $this->data->fundingInstrument->creditCard->expirationYear = $expirationYear;
        $this->data->fundingInstrument->creditCard->number = $number;
        $this->data->fundingInstrument->creditCard->cvc = $cvc;
        $this->data->fundingInstrument->creditCard->holder = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->fullname = $holder->getFullname();
        $this->data->fundingInstrument->creditCard->holder->birthdate = $birthdate;
        $this->data->fundingInstrument->creditCard->holder->taxDocument = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->taxDocument->type = $holder->getTaxDocumentType();
        $this->data->fundingInstrument->creditCard->holder->taxDocument->number = $holder->getTaxDocumentNumber();
        $this->data->fundingInstrument->creditCard->holder->phone = new stdClass();
        $this->data->fundingInstrument->creditCard->holder->phone->countryCode = $holder->getPhoneCountryCode();
        $this->data->fundingInstrument->creditCard->holder->phone->areaCode = $holder->getPhoneAreaCode();
        $this->data->fundingInstrument->creditCard->holder->phone->number = $holder->getPhoneNumber();

        return $this;
    }

    /**
     * Set birth date from customer.
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
     * Set tax document from customer.
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
     * Set phone from customer.
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
