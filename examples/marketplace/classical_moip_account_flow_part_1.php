<?php

/*
 * Tip: This setup section generally goes in other files,
 * and you access them in your controllers as globals,
 * instead of reinstantiating them every time.
 */
require 'vendor/autoload.php';

use Moip\Auth\Connect;
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
        ->setEmail('fulano'.uniqid().'@email2.com')
        ->setIdentityDocument('4737283560', 'SSP', '2015-06-23')
        ->setBirthDate('1988-12-30')
        ->setTaxDocument('16262131000')
        ->setType('MERCHANT')
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

    // Now it's time to create a URL then redirect your user to ask him permissions to create projects in his name
    $connect = new Connect('http://url.com/redirect_uri.php', 'YOUR-APP-ID', true, Connect::ENDPOINT_SANDBOX);
    $connect->setScope(Connect::RECEIVE_FUNDS)
        ->setScope(Connect::REFUND)
        ->setScope(Connect::MANAGE_ACCOUNT_INFO)
        ->setScope(Connect::RETRIEVE_FINANCIAL_INFO);

    // Redirecting user to URL generated
    header('Location: '.$connect->getAuthUrl());

    /*
     * After redirect this implementation continues in classic_moip_account_flow_2.php
     */
} catch (\Moip\Exceptions\UnautorizedException $e) {
    echo $e->getMessage();
} catch (\Moip\Exceptions\ValidationException $e) {
    printf($e->__toString());
} catch (\Moip\Exceptions\UnexpectedException $e) {
    echo $e->getMessage();
}
