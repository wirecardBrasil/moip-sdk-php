<?php

namespace Moip\Tests\Resource;

use Moip\Tests\TestCase;

class TransfersTest extends TestCase
{
    private function createTransfer()
    {
        $this->mockHttpSession($this->body_transfers_create);

        $amount = 500;
        $bank_number = '001';
        $agency_number = '1111';
        $agency_check_number = '2';
        $account_number = '9999';
        $account_check_number = '8';
        $holder_name = 'Integração Taxa por canal';
        $tax_document = '033.575.852-51';
        $transfer = $this->moip->transfers()
            ->setTransfers($amount, $bank_number, $agency_number, $agency_check_number, $account_number, $account_check_number)
            ->setHolder($holder_name, $tax_document)
            ->execute();

        return $transfer;
    }

    public function testShouldCreateTransfer()
    {
        $transfer = $this->createTransfer();
        $this->assertNotEmpty($transfer->getId());
    }

    public function testShouldGetTransfer()
    {
        $transfer_id = $this->createTransfer()->getId();

        $this->mockHttpSession($this->body_transfers_create);

        $transfer = $this->moip->transfers()->get($transfer_id);
        $this->assertEquals($transfer_id, $transfer->getId());

        $transfer_data = $transfer->getTransfers();
        $this->assertEquals(500, $transfer_data->amount);

        $transfer_instrument = $transfer_data->transferInstrument;
        $this->assertEquals('BANK_ACCOUNT', $transfer_instrument->method);

        $bank_account = $transfer_instrument->bankAccount;
        $this->assertEquals('001', $bank_account->bankNumber);
        $this->assertEquals('1111', $bank_account->agencyNumber);
        $this->assertEquals('2', $bank_account->agencyCheckNumber);
        $this->assertEquals('9999', $bank_account->accountNumber);
        $this->assertEquals('8', $bank_account->accountCheckNumber);

        $holder = $transfer->getHolder();
        $this->assertEquals('Integração Taxa por canal', $holder->fullname);

        $tax_document = $holder->taxDocument;
        $this->assertEquals('033.575.852-51', $tax_document->number);
    }

    public function testShouldRevertTransfer()
    {
        $transfer_id = $this->createTransfer()->getId();

        $this->mockHttpSession($this->body_transfers_revert);

        $transfer = $this->moip->transfers()->revert($transfer_id);
        $this->assertNotEmpty($transfer->getId());
    }
}
