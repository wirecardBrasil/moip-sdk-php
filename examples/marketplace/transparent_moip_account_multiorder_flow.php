<?php

require 'vendor/autoload.php';

use Moip\Auth\OAuth;
use Moip\Moip;

/*
 * Tip: Check how to create an Moip APP on https://dev.moip.com.br/reference#criar-um-app
 * to generate an OAuth token.
 */
$token = 'YOUR-OAUTH-TOKEN';
$moip = new Moip(new OAuth($token), Moip::ENDPOINT_SANDBOX);

try {

    // Here we are creating a transparent account to a merchant
    $account = $moip->accounts()
        ->setName('Fulano')
        ->setLastName('De Tal')
        ->setEmail('fulano@email2.com')
        ->setIdentityDocument('4737283560', 'SSP', '2015-06-23')
        ->setBirthDate('1988-12-30')
        ->setTaxDocument('16262131000')
        ->setType('MERCHANT')
        ->setTransparentAccount(true)
        ->setPhone(11, 66778899, 55)
        ->addAlternativePhone(11, 66448899, 55)
        ->addAddress('Rua de teste', 123, 'Bairro', 'Sao Paulo', 'SP', '01234567', 'Apt. 23', 'BRA')
        ->setCompanyName('Empresa Teste', 'Teste Empresa ME')
        ->setCompanyOpeningDate('2011-01-01')
        ->setCompanyPhone(11, 66558899, 55)
        ->setCompanyTaxDocument('69086878000198')
        ->setCompanyAddress('Rua de teste 2', 123, 'Bairro Teste', 'Sao Paulo', 'SP', '01234567', 'Apt. 23', 'BRA')
        ->setCompanyMainActivity('82.91-1/00', 'Atividades de cobranças e informações cadastrais')
        ->create();

    /*
     * When a transparent account is created, access token is returned from API
     * and you should save in your database to make transactions in name of the merchant
     */
    $merchantAccessToken = $account->getAccessToken();

    // Using OAuth token from merchant
    $moipMerchant = new Moip(new OAuth($merchantAccessToken), Moip::ENDPOINT_SANDBOX);

    // Creating an object customer to orders
    $customer = $moipMerchant->customers()->setOwnId(uniqid())
        ->setFullname('Fulano de Tal')
        ->setEmail('fulano@email.com')
        ->setBirthDate('1988-12-30')
        ->setTaxDocument('22222222222')
        ->setPhone(11, 66778899)
        ->addAddress('BILLING',
            'Rua de teste', 123,
            'Bairro', 'Sao Paulo', 'SP',
            '01234567', 8)
        ->addAddress('SHIPPING',
                  'Rua de teste do SHIPPING', 123,
                  'Bairro do SHIPPING', 'Sao Paulo', 'SP',
                  '01234567', 8);

    // Creating an multiorder and setting receiver for each order with `addReceiver` method
    $order = $moip->orders()->setOwnId(uniqid())
        ->addItem("bicicleta 1",1, "sku1", 10000)
        ->addItem("bicicleta 2",1, "sku2", 11000)
        ->addItem("bicicleta 3",1, "sku3", 12000)
        ->addItem("bicicleta 4",1, "sku4", 13000)
        ->setShippingAmount(3000)
        ->setAddition(1000)
        ->setDiscount(5000)
        ->setCustomer($customer)
        ->addReceiver('MPA-ID', 'PRIMARY', NULL);

    $order2 = $moip->orders()->setOwnId(uniqid())
        ->addItem("bicicleta 1",1, "sku1", 10000)
        ->addItem("bicicleta 2",1, "sku2", 11000)
        ->addItem("bicicleta 3",1, "sku3", 12000)
        ->setShippingAmount(3000)
        ->setAddition(1000)
        ->setDiscount(5000)
        ->setCustomer($customer)
        ->addReceiver('MPA-ID', 'PRIMARY', NULL);

    $multiorder = $moip->multiorders()
        ->setOwnId(uniqid())
        ->addOrder($order)
        ->addOrder($order2)
        ->create();

    // Creating multipayment to multiorder
    $multipayment = $multiorder->multipayments()
        ->setCreditCard(12, 21, '4073020000000002', '123', $customer)
        ->setInstallmentCount(3)
        ->setStatementDescriptor('teste de pag')
        ->execute();

    echo 'Multiorder ID: '.$multiorder->getId().'<br />';

    echo 'Multipayment ID: '.$multipayment->getId().'<br />';
    echo 'Status: '.$multipayment->getStatus().'<br />';
    echo 'Amount: '.$multipayment->getAmount()->total.'<br />';

    foreach ($multipayment->getPayments() as $payment) {
        echo '<br />Payment ID:'.$payment->id.'<br />';
        echo 'Status: '.$payment->status.'<br />';
        echo 'Amount: '.$payment->amount->total.'<br />';
        echo 'Created at: '.$payment->createdAt.'<br />';
        echo 'Funding Instrument: '.$payment->fundingInstrument->method.'<br />';
        echo 'Installment Count: '.$payment->installmentCount.'<br />';
    }
    
} catch (\Moip\Exceptions\UnautorizedException $e) {
    echo $e->getMessage();
} catch (\Moip\Exceptions\ValidationException $e) {
    printf($e->__toString());
} catch (\Moip\Exceptions\UnexpectedException $e) {
    echo $e->getMessage();
}
