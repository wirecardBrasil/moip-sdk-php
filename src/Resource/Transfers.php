<?php


namespace Moip\Resource;

use Requests;
use stdClass;

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
        $transfers = clone $this;
        
        return $transfers;
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
        

        $path = sprintf('/%s/%s', MoipResource::VERSION, self::PATH);
        
        $response = $this->httpRequest($path, Requests::POST, $this);

        return $this->populate($response);
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
