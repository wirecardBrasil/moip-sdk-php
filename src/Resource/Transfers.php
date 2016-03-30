<?php

namespace Moip\Resource;

use Moip\Http\HTTPRequest;
use RuntimeException;
use stdClass;
use UnexpectedValueException;

class Transfers extends MoipResource
{
	/**
     * @const strign
     */
    const PATH = 'transfers';

    const METHOD = 'BANK_ACCOUNT';

    const TYPE = 'CHECKING';

    const TYPE_HOLD = 'CPF';
    /**
     * Initializes new instances.
     */
    protected function initialize()
    {
        $this->data = new stdClass();
    }

    protected function populate(stdClass $response)
    {
        $entry = clone $this;

        $entry->data->id = $this->getIfSet('id', $response);
        $entry->data->status = $this->getIfSet('status', $response);
        return $entry;
    }

    public function setTransfers($amount, $bankNumber, $agencyNumber, $agencyCheckNumber, $accountNumber, $accountCheckNumber) {
    	$this->data->amount = $amount;
    	$this->data->transferInstrument = new stdClass();
        $this->data->transferInstrument->method =  self::METHOD;
        $this->data->transferInstrument->bankAccount  = new stdClass();
    	#$this->data->initialize();
    	$this->data->transferInstrument->bankAccount->type = self::TYPE;
    	$this->data->transferInstrument->bankAccount->bankNumber = $bankNumber;
    	$this->data->transferInstrument->bankAccount->agencyNumber = $agencyNumber;
    	$this->data->transferInstrument->bankAccount->agencyCheckNumber = $agencyCheckNumber;
    	$this->data->transferInstrument->bankAccount->accountNumber = $accountNumber;
    	$this->data->transferInstrument->bankAccount->accountCheckNumber = $accountCheckNumber;
    	return $this; 
    }

    public function setHolder($fullname, $taxDocument){

    	$this->data->transferInstrument->bankAccount->holder = new stdClass();
    	$this->data->transferInstrument->bankAccount->holder->fullname = $fullname;
    	$this->data->transferInstrument->bankAccount->holder->taxDocument = new stdClass();
    	$this->data->transferInstrument->bankAccount->holder->taxDocument->type = self::TYPE_HOLD;
    	$this->data->transferInstrument->bankAccount->holder->taxDocument->number = $taxDocument;
    	return $this; 
    }

    public function execute()
    {
        $body = json_encode($this, JSON_UNESCAPED_SLASHES);

        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');
        $httpConnection->addHeader('Content-Length', strlen($body));
        $httpConnection->setRequestBody($body);

        $path = sprintf('/%s/%s', MoipResource::VERSION, self::PATH);
        
		$httpResponse = $httpConnection->execute($path, HTTPRequest::POST);

        if ($httpResponse->getStatusCode() != 200 && $httpResponse->getStatusCode() != 201) {
             throw new RuntimeException($httpResponse->getContentErrorDescription(), $httpResponse->getStatusCode());
         }

        $response = json_decode($httpResponse->getContent());

        if (!is_object($response)) {
            throw new UnexpectedValueException('O servidor enviou uma resposta inesperada');
        }

        return $this->populate(json_decode($httpResponse->getContent()));
    }

     /**
     * Get MoIP Transfers id.
     * 
     * @return strign
     */
    public function getId()
    {
        return $this->getIfSet('id');
    }
   
}
