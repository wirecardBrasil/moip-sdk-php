<?php

namespace Moip\Resource;

use stdClass;
use UnexpectedValueException;

/**
 * Class Account.
 * 
 * @todo Devo acrescentar transparentAccount?
 * 
 */
class Account extends MoipResource
{
    /**
     * Path accounts API.
     *
     * @const string
     */
    const PATH = 'accounts';
    
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
     * Default Account Type
     * 
     * @var string
     */
    const ACCOUNT_TYPE = 'MERCHANT';

    /**
     * Initialize a new instance.
     */
    public function initialize()
    {
        $this->data = new stdClass();
        $this->data->email = new stdClass();
        $this->data->person = new stdClass();
        $this->data->type = self::ACCOUNT_TYPE;
    }

    /**
     * Add a new address to the account.
     *
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
    public function addAddress($street, $number, $district, $city, $state, $zip, $complement = null, $country = self::ADDRESS_COUNTRY)
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
        
        $this->data->person->address = $address;

        return $this;
    }

    /**
     * Create a new account.
     *
     * @return \stdClass
     */

    /**
     * @return stdClass
     */
    public function create()
    {
        return $this->createResource(sprintf('/%s/%s/', MoipResource::VERSION, self::PATH));
    }

    /**
     * Find a account.
     *
     * @param string $moip_id
     *
     * @return \Moip\Resource\Account
     */
    public function get($moip_id)
    {
        return $this->getByPath(sprintf('/%s/%s/%s', MoipResource::VERSION, self::PATH, $moip_id));
    }

    /**
     * Get account id.
     *
     * @return string The buyer id.
     */
    public function getId()
    {
        return $this->getIfSet('id');
    }

    /**
     * Get account address.
     *
     * @return \stdClass Account's address.
     */
    public function getAddress()
    {
        return $this->getIfSet('address', $this->data->person);
    }

    /**
     * Get account fullname.
     *
     * @return string Account's full name.
     */
    public function getFullname()
    {
        return $this->getIfSet('name', $this->data->person) . ' ' . $this->getIfSet('lastName', $this->data->person);
    }

    /**
     * Get birth date from account.
     *
     * @return \DateTime|null Date of birth of the credit card holder.
     */
    public function getBirthDate()
    {
        return $this->getIfSetDate('birthDate', $this->data->person);
    }

    /**
     * Get phone area code from account.
     *
     * @return int DDD telephone.
     */
    public function getPhoneAreaCode()
    {
        return $this->getIfSet('areaCode', $this->data->person->phone);
    }

    /**
     * Get phone country code from account.
     *
     * @return int Country code.
     */
    public function getPhoneCountryCode()
    {
        return $this->getIfSet('countryCode', $this->data->person->phone);
    }

    /**
     * Get phone number from account.
     *
     * @return int Telephone number.
     */
    public function getPhoneNumber()
    {
        return $this->getIfSet('number', $this->data->person->phone);
    }

    /**
     * Get tax document type from account.
     *
     * @return string Type of value: CPF and CNPJ
     */
    public function getTaxDocumentType()
    {
        return $this->getIfSet('type', $this->data->person->taxDocument);
    }

    /**
     * Get tax document number from account.
     *
     * @return string Document Number.
     */
    public function getTaxDocumentNumber()
    {
        return $this->getIfSet('number', $this->data->person->taxDocument);
    }
    
    /**
     * Get account type.
     *
     * @return string Document Number.
     */
    public function getType()
    {
    	return $this->getIfSet('type', $this->data);
    }

    /**
     * Mount the seller structure from account.
     *
     * @param \stdClass $response
     *
     * @return \Moip\Resource\Account Account data
     */
    protected function populate(stdClass $response)
    {
        $account = clone $this;
        $account->data->email = new stdClass();
        
        $email = $this->getIfSet('email', $response);
        
        $account->data->email->address = $this->getIfSet('address', $email);
        $account->data->person = new stdClass();
        
        $person = $this->getIfSet('person', $response);
        
        $account->data->person->name = $this->getIfSet('name', $person);
        $account->data->person->lastName = $this->getIfSet('lastName', $person);
        $account->data->person->taxDocument = new stdClass();
        
        $taxDocument = $this->getIfSet('taxDocument', $person);
        
        $account->data->person->taxDocument->type = $this->getIfSet('type', $taxDocument);
        $account->data->person->taxDocument->number = $this->getIfSet('number', $taxDocument);
        $account->data->person->phone = new stdClass();
        
        $phone = $this->getIfSet('phone', $person);
        
        $account->data->person->phone->countryCode = $this->getIfSet('countryCode', $phone);
        $account->data->person->phone->areaCode = $this->getIfSet('areaCode', $phone);
        $account->data->person->phone->number = $this->getIfSet('number', $phone);
        $account->data->person->identityDocument = new stdClass();
        
        $identityDocument = $this->getIfSet('identityDocument', $person);
        
        $account->data->person->identityDocument->type = $this->getIfSet('type', $identityDocument);
        $account->data->person->identityDocument->number = $this->getIfSet('number', $identityDocument);
        $account->data->person->identityDocument->issuer = $this->getIfSet('issuer', $identityDocument);
        $account->data->person->identityDocument->issueDate = $this->getIfSet('issueDate', $identityDocument);
        
        $account->data->person->birthDate = $this->getIfSet('birthDate', $person);
        $account->data->person->address = $this->getIfSet('address', $person);
        $account->data->_links = $this->getIfSet('_links', $response);
        $account->data->type = $this->getIfSet('type', $response);
        
        return $account;
    }

    /**
     * Set e-mail from account.
     *
     * @param string $email Email account.
     *
     * @return \Moip\Resource\Account
     */
    public function setEmail($email)
    {
        $this->data->email->address = $email;

        return $this;
    }
    
    /**
     * Set name from account.
     *
     * @param string $name Account's person name.
     *
     * @return \Moip\Resource\Account
     */
    public function setName($name)
    {
    	$this->data->person->name = $name;
    
    	return $this;
    }
    
    /**
     * Set name from account.
     *
     * @param string $name Account's person name.
     *
     * @return \Moip\Resource\Account
     */
    public function setLastName($lastname)
    {
    	$this->data->person->lastName = $lastname;
    
    	return $this;
    }

    /**
     * Set birth date from account.
     *
     * @param \DateTime|string $birthDate Date of birth of the credit card holder.
     *
     * @return \Moip\Resource\Account
     */
    public function setBirthDate($birthDate)
    {
        if ($birthDate instanceof \DateTime) {
            $birthDate = $birthDate->format('Y-m-d');
        }

        $this->data->person->birthDate = $birthDate;

        return $this;
    }

    /**
     * Set tax document from account.
     *
     * @param string $number Document number.
     * @param string $type   Document type.
     *
     * @return \Moip\Resource\Account
     */
    public function setTaxDocument($number, $type = self::TAX_DOCUMENT)
    {
        $this->data->person->taxDocument = new stdClass();
        $this->data->person->taxDocument->type = $type;
        $this->data->person->taxDocument->number = $number;

        return $this;
    }

    /**
     * Set phone from account.
     *
     * @param int $areaCode    DDD telephone.
     * @param int $number      Telephone number.
     * @param int $countryCode Country code.
     *
     * @return \Moip\Resource\Account
     */
    public function setPhone($areaCode, $number, $countryCode = 55)
    {
        $this->data->person->phone = new stdClass();
        $this->data->person->phone->countryCode = $countryCode;
        $this->data->person->phone->areaCode = $areaCode;
        $this->data->person->phone->number = $number;

        return $this;
    }
    
    /**
     * Set identity document from account.
     *
     * @param string $number    						Número do documento.
     * @param string $issuer      						Emissor do documento.
     * @param \DateTime|string $birthDate $issueDate 	Data de emissão do documento.
     * @param string $type								Tipo do documento. Valores possíveis: RG.
     *
     * @return \Moip\Resource\Account
     */
    public function setIdentityDocument($number, $issuer, $issueDate, $type = 'RG')
    {
    	$this->data->person->identityDocument = new stdClass();
    	$this->data->person->identityDocument->type = $type;
    	$this->data->person->identityDocument->number = $number;
    	$this->data->person->identityDocument->issuer = $issuer;
    	$this->data->person->identityDocument->issueDate = $issueDate;
    	
    	return $this;
    }
    
    /**
     * Set account type. Possible values: CONSUMER, MERCHANT.
     * 
     * @param string $type
     * 
     * @return \Moip\Resource\Account
     */
    public function setType($type)
    {
    	$this->data->type = $type;
    	
    	return $this;
    }
}
