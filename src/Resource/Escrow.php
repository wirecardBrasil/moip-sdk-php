<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Moip\Resource;

/**
 * Description of Escrow
 *
 * @author caiogaspar
 */
class Escrow extends MoipResource {
    
    /**
     * @const string
     */
    const PATH = 'escrows';
    
    /**
     * Initializes new instances.
     */
    protected function initialize()
    {
        $this->data = new stdClass();
        $this->data->installmentCount = 1;
        $this->data->fundingInstrument = new stdClass();
    }
    
    /**
     * Mount payment structure.
     *
     * @param \stdClass $response
     *
     * @return Escrow
     */
    protected function populate(stdClass $response)
    {
        $escrow = clone $this;

        $escrow->data->id = $this->getIfSet('id', $response);
        $escrow->data->status = $this->getIfSet('status', $response);
        $escrow->data->delayCapture = $this->getIfSet('delayCapture', $response);
        $escrow->data->amount = new stdClass();
        $escrow->data->amount->total = $this->getIfSet('total', $response->amount);
        $escrow->data->amount->currency = $this->getIfSet('currency', $response->amount);
        $escrow->data->installmentCount = $this->getIfSet('installmentCount', $response);
        $escrow->data->fundingInstrument = $this->getIfSet('fundingInstrument', $response);
        $escrow->data->fees = $this->getIfSet('fees', $response);
        $escrow->data->refunds = $this->getIfSet('refunds', $response);
        $escrow->data->_links = $this->getIfSet('_links', $response);
        $escrow->data->createdAt = $this->getIfSetDateTime('createdAt', $response);
        $escrow->data->updatedAt = $this->getIfSetDateTime('updatedAt', $response);

        return $escrow;
    }
}
