<?php

namespace Moip\Resource;

use Moip\Exceptions\ValidationException;
use stdClass;

/**
 * Class Account.
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
     * Standard company document type.
     *
     * @const string
     */
    const COMPANY_TAX_DOCUMENT = 'CNPJ';

    /**
     * Default Account Type.
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
        $this->data->person->alternativePhones = [];
        $this->data->businessSegment = new stdClass();
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
     * Add alternative phone to an account.
     *
     * @param int $areaCode    DDD telephone.
     * @param int $number      Telephone number.
     * @param int $countryCode Country code.
     *
     * @return \Moip\Resource\Account
     */
    public function addAlternativePhone($areaCode, $number, $countryCode = 55)
    {
        $alternativePhone = new stdClass();
        $alternativePhone->countryCode = $countryCode;
        $alternativePhone->areaCode = $areaCode;
        $alternativePhone->number = $number;

        $this->data->person->alternativePhones[] = $alternativePhone;

        return $this;
    }

    /**
     * Create a new account.
     *
     * @return \stdClass
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
     * @return stdClass
     */
    public function get($moip_id)
    {
        return $this->getByPath(sprintf('/%s/%s/%s', MoipResource::VERSION, self::PATH, $moip_id));
    }

    /**
     * Check if an account exists.
     *
     * @param string $tax_document
     *
     * @return bool
     */
    public function checkExistence($tax_document)
    {
        try {
            $this->getByPathNoPopulate(sprintf('/%s/%s/%s?tax_document=%s', MoipResource::VERSION, self::PATH, 'exists', $tax_document));

            return true;
        } catch (ValidationException $e) {
            if ($e->getStatusCode() != 404) {
                throw new ValidationException($e->getStatusCode(), $e->getErrors());
            }
        }

        return false;
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
     * Get account access token.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->getIfSet('accessToken');
    }

    /**
     * Get account channel ID.
     *
     * @return string
     */
    public function getChannelId()
    {
        return $this->getIfSet('channelId');
    }

    /**
     * Get account login.
     *
     * @return string The buyer login.
     */
    public function getLogin()
    {
        return $this->getIfSet('login');
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
        return $this->getIfSet('name', $this->data->person).' '.$this->getIfSet('lastName', $this->data->person);
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
     * Get identity document number from account.
     *
     * @return string
     */
    public function getIdentityDocumentNumber()
    {
        return $this->getIfSet('number', $this->data->person->identityDocument);
    }

    /**
     * Get identity document issuer from account.
     *
     * @return string
     */
    public function getIdentityDocumentIssuer()
    {
        return $this->getIfSet('issuer', $this->data->person->identityDocument);
    }

    /**
     * Get identity document issue date from account.
     *
     * @return \DateTime
     */
    public function getIdentityDocumentIssueDate()
    {
        return $this->getIfSet('issueDate', $this->data->person->identityDocument);
    }

    /**
     * Get identity document type from account.
     *
     * @return string Type of value: RG
     */
    public function getIdentityDocumentType()
    {
        return $this->getIfSet('type', $this->data->person->identityDocument);
    }

    /**
     * Get alternative phones.
     *
     * @return array
     */
    public function getAlternativePhones()
    {
        return $this->getIfSet('alternativePhones', $this->data->person);
    }

    /**
     * Get company data.
     *
     * @return array
     */
    public function getCompany()
    {
        return $this->getIfSet('company', $this->data);
    }

    /**
     * Get email address.
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->getIfSet('address', $this->data->email);
    }

    /**
     * Get email confirmed.
     *
     * @return bool
     */
    public function getEmailConfirmed()
    {
        return $this->getIfSet('confirmed', $this->data->email);
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
     * Get business segment id.
     *
     * @return int id.
     */
    public function getBusinessSegmentId()
    {
        return $this->getIfSet('id', $this->data->businessSegment);
    }

    /**
     * Get business segment name.
     *
     * @return string name.
     */
    public function getBusinessSegmentName()
    {
        return $this->getIfSet('name', $this->data->businessSegment);
    }

    /**
     * Get business segment mcc.
     *
     * @return int mcc.
     */
    public function getBusinessSegmentMcc()
    {
        return $this->getIfSet('mcc', $this->data->businessSegment);
    }

    /**
     * Get transparent account (true/false).
     *
     * @return bool
     */
    public function getTransparentAccount()
    {
        return $this->getIfSet('transparentAccount', $this->data);
    }

    /**
     * Get account created at.
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->getIfSet('createdAt', $this->data);
    }

    /**
     * Get link to set the password of created account.
     *
     * @return string
     */
    public function getPasswordLink()
    {
        return $this->getIfSet('href', $this->data->_links->setPassword);
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
        $account->data->email->confirmed = $this->getIfSet('confirmed', $email);

        $account->data->login = $this->getIfSet('login', $response);
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

        $account->data->person->alternativePhones = $this->getIfSet('alternativePhones', $person);

        $businessSegment = $this->getIfSet('businessSegment', $response);

        $account->data->businessSegment->id = $this->getIfSet('id', $businessSegment);
        $account->data->businessSegment->name = $this->getIfSet('name', $businessSegment);
        $account->data->businessSegment->mcc = $this->getIfSet('mcc', $businessSegment);

        $account->data->company = $this->getIfSet('company', $response);
        $account->data->_links = new stdClass();

        $_links = $this->getIfSet('_links', $response);
        $account->data->_links->setPassword = new stdClass();

        $setPassword = $this->getIfSet('setPassword', $_links);
        $account->data->_links->setPassword->href = $this->getIfSet('href', $setPassword);

        $account->data->type = $this->getIfSet('type', $response);
        $account->data->id = $this->getIfSet('id', $response);
        $account->data->accessToken = $this->getIfSet('accessToken', $response);
        $account->data->channelId = $this->getIfSet('channelId', $response);
        $account->data->transparentAccount = $this->getIfSet('transparentAccount', $response);
        $account->data->createdAt = $this->getIfSet('createdAt', $response);

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
     * @param string $lastname Account's person name.
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
     * @param string $number Número do documento.
     * @param string $issuer Emissor do documento.
     * @param $issueDate
     * @param string $type Tipo do documento. Valores possíveis: RG.
     *
     * @return Account
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
     * Set person nationality.
     *
     * @param string $nationality Abbreviation for nationality (3 max length).
     *
     * @return $this
     */
    public function setNationality($nationality = self::ADDRESS_COUNTRY)
    {
        $this->data->person->nationality = $nationality;

        return $this;
    }

    /**
     * Set person birth place.
     *
     * @param string $birthPlace Birth place (city).
     *
     * @return $this
     */
    public function setBirthPlace($birthPlace)
    {
        $this->data->person->birthPlace = $birthPlace;

        return $this;
    }

    /**
     * Set parents name.
     *
     * @param string $motherName Mother name.
     * @param string $fatherName Father name.
     *
     * @return $this
     */
    public function setParentsName($motherName, $fatherName)
    {
        $this->data->person->parentsName = new stdClass();
        $this->data->person->parentsName->mother = $motherName;
        $this->data->person->parentsName->father = $fatherName;

        return $this;
    }

    /**
     * Set site.
     *
     * @param string $site URL from site.
     *
     * @return $this
     */
    public function setSite($site)
    {
        $this->data->site = $site;

        return $this;
    }

    /**
     * Set transparent account.
     *
     * @param bool $transparentAccount Set true if you want create a transparent account.
     *
     * @return $this
     */
    public function setTransparentAccount($transparentAccount)
    {
        $this->data->transparentAccount = $transparentAccount;

        return $this;
    }

    /**
     * Set business segment.
     *
     * @param int $segmentId business segment id. Possible values available at: https://documentao-moip.readme.io/v2.0/reference#tabela-de-categorias-de-estabelecimento .
     *
     * @return $this
     */
    public function setBusinessSegment($segmentId)
    {
        $this->data->businessSegment->id = $segmentId;

        return $this;
    }

    /**
     * Set company name.
     *
     * @param string $name         Trading Name.
     * @param string $businessName Company Name.
     *
     * @return $this
     */
    public function setCompanyName($name, $businessName)
    {
        $this->initializeCompany();
        $this->data->company->name = $name;
        $this->data->company->businessName = $businessName;

        return $this;
    }

    /**
     * Initialize company node.
     */
    private function initializeCompany()
    {
        if (!isset($this->data->company)) {
            $this->data->company = new stdClass();
        }
    }

    /**
     * Set company opening date.
     *
     * @param \DateTime|string $openingDate .
     *
     * @return $this
     */
    public function setCompanyOpeningDate($openingDate)
    {
        if ($openingDate instanceof \DateTime) {
            $openingDate = $openingDate->format('Y-m-d');
        }
        $this->initializeCompany();
        $this->data->company->openingDate = $openingDate;

        return $this;
    }

    /**
     * Set company tax document.
     *
     * @param string $documentNumber .
     *
     * @return $this
     */
    public function setCompanyTaxDocument($documentNumber)
    {
        $this->initializeCompany();
        $this->data->company->taxDocument = new stdClass();
        $this->data->company->taxDocument->type = self::COMPANY_TAX_DOCUMENT;
        $this->data->company->taxDocument->number = $documentNumber;

        return $this;
    }

    /**
     * Set company tax document.
     *
     * @param string $documentNumber .
     *
     * @return $this
     */
    public function setCompanyMainActivity($cnae, $description)
    {
        $this->initializeCompany();
        $this->data->company->mainActivity = new stdClass();
        $this->data->company->mainActivity->cnae = $cnae;
        $this->data->company->mainActivity->description = $description;

        return $this;
    }

    /**
     * Set address to company.
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
    public function setCompanyAddress($street, $number, $district, $city, $state, $zip, $complement = null, $country = self::ADDRESS_COUNTRY)
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

        $this->initializeCompany();
        $this->data->company->address = $address;

        return $this;
    }

    /**
     * Set company phone.
     *
     * @param int $areaCode    DDD telephone.
     * @param int $number      Telephone number.
     * @param int $countryCode Country code.
     *
     * @return \Moip\Resource\Account
     */
    public function setCompanyPhone($areaCode, $number, $countryCode = 55)
    {
        $this->initializeCompany();
        $this->data->company->phone = new stdClass();
        $this->data->company->phone->countryCode = $countryCode;
        $this->data->company->phone->areaCode = $areaCode;
        $this->data->company->phone->number = $number;

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
