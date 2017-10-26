<?php

require 'vendor/autoload.php';

use Moip\Auth\Connect;
use Moip\Auth\OAuth;
use Moip\Moip;

/*
 * Tip: Check how to create a Moip APP on https://dev.moip.com.br/reference#criar-um-app
 * and get the client secret
 */
try {
    $connect = new Connect('http://url.com/redirect_uri.php', 'YOUR-APP-ID', true, Connect::ENDPOINT_SANDBOX);

    $connect->setClientSecret('5681772df8944ce1a79d36af34c9842b');
    $connect->setCode($_GET['code']);

    /*
     * After the user authorize your app, you must generate an OAuth token
     * to make transactions in his name.
     */
    $authorize = $connect->authorize();

    // Using OAuth token from merchant
    $moipMerchant = new Moip(new OAuth($authorize->access_token), Moip::ENDPOINT_SANDBOX);

    // Creating an object customer to order
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
                '01234567', 8)->create();

    // Creating an order and splitting payment using 'addReceiver' method
    $order = $moipMerchant->orders()->setOwnId(uniqid())
        ->addItem('bicicleta 1', 1, 'sku1', 10000)
        ->addItem('bicicleta 2', 1, 'sku2', 11000)
        ->addItem('bicicleta 3', 1, 'sku3', 12000)
        ->addItem('bicicleta 4', 1, 'sku4', 13000)
        ->addItem('bicicleta 5', 1, 'sku5', 14000)
        ->addItem('bicicleta 6', 1, 'sku6', 15000)
        ->addItem('bicicleta 7', 1, 'sku7', 16000)
        ->addItem('bicicleta 8', 1, 'sku8', 17000)
        ->addItem('bicicleta 9', 1, 'sku9', 18000)
        ->addItem('bicicleta 10', 1, 'sku10', 19000)
        ->setShippingAmount(3000)->setAddition(1000)->setDiscount(5000)
        ->setCustomer($customer)

        // Here we're setting a secondary account to receive 90% from order value
        ->addReceiver('MPA-ID', 'SECONDARY', null, 90, true)
        ->create();

    // Creating payment to order
    $payment = $order->payments()
        ->setCreditCard(12, 21, '4073020000000002', '123', $customer)
        ->setInstallmentCount(3)
        ->setStatementDescriptor('teste de pag')
        ->execute();

    echo 'Order ID: '.$order->getId().'<br />';

    echo 'Receivers: <br>';

    foreach ($order->getReceiverIterator() as $receiver) {
        echo $receiver->moipAccount->fullname.' - '.$receiver->moipAccount->id.'<br>';
    }

    echo 'Payment ID: '.$payment->getId().'<br />';
    echo 'Created at: '.$payment->getCreatedAt()->format('Y-m-d H:i:s').'<br />';
    echo 'Status: '.$payment->getStatus().'<br />';
    echo 'Amount: '.$payment->getAmount()->total.'<br />';
    echo 'Funding Instrument: '.$payment->getFundingInstrument()->method.'<br />';
    echo 'Installment Count: '.$payment->getInstallmentCount().'<br />';
} catch (\Moip\Exceptions\UnautorizedException $e) {
    echo $e->getMessage();
} catch (\Moip\Exceptions\ValidationException $e) {
    printf($e->__toString());
} catch (\Moip\Exceptions\UnexpectedException $e) {
    echo $e->getMessage();
}
