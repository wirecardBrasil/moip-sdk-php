<?php
namespace Moip\Resource;

use \stdClass;
use Moip\Http\HTTPRequest;

class Entry extends MoipResource
{
    protected function initialize()
    {
        $this->data = new stdClass();
        $this->data->amount = new stdClass();
        $this->data->details = new stdClass();
        $this->data->parentPayments = new stdClass();
    }
    
    protected function populate(stdClass $response)
    {
        $entry = clone $this;
        
        $entry->data->id = $this->getIfSet('id', $response);
        $entry->data->status = $this->getIfSet('status', $response);
        $entry->data->operation = $this->getIfSet('operation', $response);
        
        if (isset($response->amount)) {
            $entry->data->amount->total = $this->getIfSet('total', $response->amount);
            $entry->data->amount->fee = $this->getIfSet('fee', $response->amount);
            $entry->data->amount->liquid = $this->getIfSet('liquid', $response->amount);
            $entry->data->amount->currency = $this->getIfSet('currency', $response->amount);
        }
        
        if (isset($response->details)) {
            $entry->data->details = $this->getIfSet('details', $response);
        }
        
        if (isset($response->{'parent'}) && isset($response->{'parent'}->payments)) {
            $payments = new Payment($entry->moip);
            $payments->populate($response->{'parent'}->payments);
            
            $entry->data->parentPayments = $payments;
        }
        
        return $entry;
    }
    
    public function get($id)
    {   
        $httpConnection = $this->createConnection();
        $httpConnection->addHeader('Content-Type', 'application/json');
        
        $httpResponse = $httpConnection->execute('/v2/entries/'.$id, HTTPRequest::GET);
        
        if ($httpResponse->getStatusCode() != 200) {
            throw new \RuntimeException($httpResponse->getStatusMessage(), $httpResponse->getStatusCode());
        }
        
        return $this->populate(json_decode($httpResponse->getContent()));
    }
    
    public function getId()
    {
        return $this->getIfSet('id');
    }
    
    public function getStatus()
    {
        return $this->getIfSet('status');
    }
    
    public function getOperation()
    {
        return $this->getIfSet('operation');
    }
    
    public function getAmountTotal()
    {
        return $this->getIfSet('total', $this->data->amount);
    }
    
    public function getAmountFee()
    {
        return $this->getIfSet('fee', $this->data->amount);
    }
    
    public function getAmountLiquid()
    {
        return $this->getIfSet('liquid', $this->data->amount);
    }
    
    public function getAmountCurrency()
    {
        return $this->getIfSet('currency', $this->data->amount);
    }
    
    public function getDetails()
    {
        return $this->getIfSet('details');
    }
    
    public function getParentPayments()
    {
        return $this->getIfSet('parentPayments');
    }
    
    public function getScheduledFor()
    {
        return $this->getIfSet('scheduledFor');
    }
    
    public function getSettledAt()
    {
        return $this->getIfSet('settledAt');
    }
    
    public function getUpdatedAt()
    {
        return $this->getIfSet('updatedAt');
    }
    
    public function getCreatedAt()
    {
        return $this->getIfSet('createdAt');
    }
}