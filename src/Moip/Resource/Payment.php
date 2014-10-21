<?php
namespace Moip\Resource;

use \stdClass;
use Moip\Http\HttpRequest;

class Payment extends MoipResource
{
    /**
     * @var \Moip\Resource\Orders
     */
    private $order;
    
    /**
     * @var \Moip\Resource\Multiorders
     */
    private $multiorder;
    
    protected function initialize()
    {
        $this->data = new stdClass();
        $this->data->installmentCount = 1;
        $this->data->fundingInstrument = new stdClass();
    }
    
    public function execute()
    {
        $body = json_encode($this);

        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');
        $httpConnection->addHeader('Content-Length', strlen($body));
        $httpConnection->setRequestBody($body);
        
        if ($this->order !== null) {        
            $path = sprintf('/v2/orders/%s/payments', $this->order->getId());
        } else {
            $path = sprintf('/v2/multiorders/%s/multipayments', $this->multiorder->getId());
        }
        
        $httpResponse = $httpConnection->execute($path, HTTPRequest::POST);
            
        if ($httpResponse->getStatusCode() != 200) {
            throw new \RuntimeException($httpResponse->getStatusMessage(), $httpResponse->getStatusCode());
        }

        $response = json_decode($httpResponse->getContent());

        if (!is_object($response)) {
            throw new \UnexpectedValueException('O servidor enviou uma resposta inesperada');
        }
        
        return $this->populate(json_decode($httpResponse->getContent()));
    }
    
    public function get($id)
    {
        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');
        
        if ($this->order !== null) {        
            $path = sprintf('/v2/payments/%s', $this->order->getId());
        } else {
            $path = sprintf('/v2/multipayments/%s', $this->multiorder->getId());
        }
        
        $httpResponse = $httpConnection->execute('/v2/payments/'.$id, HTTPRequest::GET);
        
        if ($httpResponse->getStatusCode() != 200) {
            throw new \RuntimeException($httpResponse->getStatusMessage(), $httpResponse->getStatusCode());
        }
        
        return $this->populate(json_decode($httpResponse->getContent()));
        
    }
    
    public function getId()
    {
        return $this->getIfSet('id');
    }
    
    
    protected function populate(stdClass $response)
    {
        $payment = clone $this;
        
        $payment->data->id = $this->getIfSet('id', $response);
        $payment->data->status = $this->getIfSet('status', $response);
        $payment->data->amount = new stdClass();
        $payment->data->amount->total = $this->getIfSet('total', $response->amount);
        $payment->data->amount->currency = $this->getIfSet('currency', $response->amount);
        $payment->data->installmentCount = $this->getIfSet('installmentCount', $response);
        $payment->data->fundingInstrument = $this->getIfSet('fundingInstrument', $response);
        $payment->data->fees = $this->getIfSet('fees', $response);
        $payment->data->refunds = $this->getIfSet('refunds', $response);
        $payment->data->_links = $this->getIfSet('_links', $response);
        
        return $payment;
    }
    
    public function refunds()
    {
        $refund = new Refund($this->moip);
        $refund->setPayment($this);
        
        return $refund;
    }
    
    public function setFundingInstrument(stdClass $fundingInstrument)
    {
        $this->data->fundingInstrument = $fundingInstrument;
        
        return $this;
    }
    
    public function setBoleto($expirationDate, $logoUri, array $instructionLines = array())
    {
        $keys = array('first', 'second', 'third');
        
        $this->data->fundingInstrument->method = 'BOLETO';
        $this->data->fundingInstrument->boleto = new stdClass();
        $this->data->fundingInstrument->boleto->expirationDate = $expirationDate;
        $this->data->fundingInstrument->boleto->instructionLines = array_combine($keys, $instructionLines);
        $this->data->fundingInstrument->boleto->logoUri = $logoUri;
        
        return $this;
    }
    
    public function setCreditCard($expirationMonth, $expirationYear, $number, $cvc, Customer $holder)
    {
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
    
    public function setInstallmentCount($installmentCount)
    {
        $this->data->installmentCount = $installmentCount;
        
        return $this;
    }
    
    public function setOnlineBankDebit($bankNumber, $expirationDate, $returnUri)
    {
        $this->data->fundingInstrument->method = 'ONLINE_BANK_DEBIT';
        $this->data->fundingInstrument->onlineBankDebit = new stdClass();
        $this->data->fundingInstrument->onlineBankDebit->bankNumber = $bankNumber;
        $this->data->fundingInstrument->onlineBankDebit->expirationDate = $expirationDate;
        $this->data->fundingInstrument->onlineBankDebit->returnUri = $returnUri;
        
        return $this;
    }
    
    public function setMultiorder(Multiorders $multiorder)
    {
        $this->multiorder = $multiorder;
    }
    
    public function setOrder(Orders $order)
    {
        $this->order = $order;
        
        return $this;
    }
}