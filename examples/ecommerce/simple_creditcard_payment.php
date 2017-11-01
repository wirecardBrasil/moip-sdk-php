<?php

/*
 * Tip: This setup section generally goes in other files,
 * and you access them in your controllers as globals,
 * instead of reinstantiating them every time.
 */
require 'vendor/autoload.php';

use Moip\Auth\BasicAuth;
use Moip\Moip;

$token = 'YOUR-TOKEN';
$key = 'YOUR-KEY';
$moip = new Moip(new BasicAuth($token, $key), Moip::ENDPOINT_SANDBOX);

/*
 * Don't forget you must generate your hash to encrypt credit card data using https://github.com/moip/moip-sdk-js
 */
$hash = 'YOUR-HASH';

try {
    /*
     * If you want to persist your customer data and save later, now is the time to create it.
     * TIP: Don't forget to generate your `ownId` or use one you already have,
     * here we set using uniqid() function.
     */
    $customer = $moip->customers()->setOwnId(uniqid())
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
                  '01234567', 8)
        ->create();

    // Creating an order
    $order = $moip->orders()->setOwnId(uniqid())
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
        ->create();

    // Creating payment to order
    $payment = $order->payments()
        ->setCreditCardHash($hash, $customer)
        ->setInstallmentCount(3)
        ->setStatementDescriptor('teste de pag')
        ->execute();

    echo 'Order ID: '.$order->getId().'<br />';
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
