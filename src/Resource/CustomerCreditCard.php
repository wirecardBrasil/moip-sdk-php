<?php

namespace Moip\Resource;

use stdClass;

/**
 * Class CustomerFunding.
 *
 * Add a credit card.
 * Through this API you can add one or more credit cards to a Customer.
 */
class CustomerCreditCard extends MoipResource
{
    /**
     * Path funding instruments.
     *
     * @const
     */
    const PATH_POST = 'customers/%s/fundinginstruments';

    /**
     * Delete a credit card.
     *
     * @const
     */
    const PATH_DELETE = 'fundinginstruments/%s';

    /**
     * @const sting
     */
    const METHOD_CREDIT_CARD = 'CREDIT_CARD';

    /**
     * Initialize a new instance.
     */
    protected function initialize()
    {
        $this->data = new stdClass();
        $this->data->method = self::METHOD_CREDIT_CARD;
        $this->data->creditCard = new stdClass();
        $this->data->creditCard->holder = new stdClass();
        $this->data->creditCard->holder->taxDocument = new stdClass();
        $this->data->creditCard->holder->phone = new stdClass();
    }

    /**
     * Mount information of a determined object.
     *
     * @param \stdClass $response
     *
     * @return mixed
     */
    protected function populate(stdClass $response)
    {
        $funding = clone $this;
        $funding->data->method = self::METHOD_CREDIT_CARD;
        $funding->data->creditCard = new stdClass();
        $funding->data->creditCard->id = $response->creditCard->id;
        $funding->data->creditCard->brand = $response->creditCard->brand;
        $funding->data->creditCard->first6 = $response->creditCard->first6;
        $funding->data->creditCard->last4 = $response->creditCard->last4;
        $funding->data->creditCard->store = $response->creditCard->store;
        $funding->data->card = new stdClass();
        $funding->data->card->brand = $response->card->brand;
        $funding->data->card->store = $response->card->store;

        return $funding;
    }

    /**
     * Create.
     *
     * @param $customer_id
     *
     * @return stdClass
     */
    public function create($customer_id)
    {
        return $this->createResource(sprintf('%s/%s', MoipResource::VERSION, sprintf(self::PATH_POST, $customer_id)));
    }

    /**
     * Delete.
     *
     * @param $creditcard_id
     *
     * @return mixed
     */
    public function delete($creditcard_id)
    {
        return $this->deleteByPath(sprintf('%s/%s', MoipResource::VERSION, sprintf(self::PATH_DELETE, $creditcard_id)));
    }

    /**
     * Mês de expiração do cartão.
     * Necessário estar dentro do escopo PCI para enviar esse campo sem criptografia.
     *
     * @param int $expiration_month
     *
     * @return $this
     */
    public function setExpirationMonth($expiration_month)
    {
        $this->data->creditCard->expirationMonth = $expiration_month;

        return $this;
    }

    /**
     * Ano de expiração do cartão.
     * Necessário estar dentro do escopo PCI para enviar esse campo sem criptografia.
     *
     * @param int $expiration_year
     *
     * @return $this
     */
    public function setExpirationYear($expiration_year)
    {
        $this->data->creditCard->expirationYear = $expiration_year;

        return $this;
    }

    /**
     * Número do cartão de crédito.
     * Necessário estar dentro do escopo PCI para enviar esse campo sem criptografia.
     *
     * @param $number
     *
     * @return $this
     */
    public function setNumber($number)
    {
        $this->data->creditCard->number = $number;

        return $this;
    }

    /**
     * Código de segurança do cartão.
     * Necessário estar dentro do escopo PCI para enviar esse campo sem criptografia.
     *
     * @param string $cvc
     *
     * @return $this
     */
    public function setCvc($cvc)
    {
        $this->data->creditCard->cvc = $cvc;

        return $this;
    }

    /**
     * Nome do portador impresso no cartão.
     *
     * @param $fullname
     *
     * @return $this
     */
    public function setFullname($fullname)
    {
        $this->data->creditCard->holder->fullname = $fullname;

        return $this;
    }

    /**
     * Data de nascimento do cliente. date(AAAA-MM-DD),.
     *
     * @param $birthdate
     *
     * @return $this
     */
    public function setBirthdate($birthdate)
    {
        $this->data->creditCard->holder->birthdate = $birthdate;

        return $this;
    }

    /**
     * Documento fiscal.
     *
     * @param string $type   Tipo do documento. Valores possíveis: CPF.
     * @param string $number Número do documento.
     *
     * @return $this
     */
    public function setTaxDocument($type, $number)
    {
        $this->data->creditCard->holder->taxDocument->type = $type;
        $this->data->creditCard->holder->taxDocument->number = $number;

        return $this;
    }

    /**
     * Telefone do cliente.
     *
     * @param int $country_code DDI (código internacional) do telefone. Valores possíveis: 55.
     * @param int $area_code    Código de área do cliente. Limite de caracteres: (2).
     * @param int $number       Número de telefone do cliente. Limite de caracteres: 9
     *
     * @return $this
     */
    public function setPhone($country_code, $area_code, $number)
    {
        $this->data->creditCard->holder->phone->countryCode = $country_code;
        $this->data->creditCard->holder->phone->areaCode = $area_code;
        $this->data->creditCard->holder->phone->number = $number;

        return $this;
    }

    /**
     * Get credit card id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->data->creditCard->id;
    }

    /**
     * Get brand.
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->data->creditCard->brand;
    }

    /**
     * Get first 6 characters from credit card.
     *
     * @return string
     */
    public function getFirst6()
    {
        return $this->data->creditCard->first6;
    }

    /**
     * Get last 4 characters from credit card.
     *
     * @return string
     */
    public function getLast4()
    {
        return $this->data->creditCard->last4;
    }

    /**
     * Get if a credit card was stored or not.
     *
     * @return bool
     */
    public function getStore()
    {
        return $this->data->creditCard->store;
    }
}
