<img src="https://gist.githubusercontent.com/joaolucasl/00f53024cecf16410d5c3212aae92c17/raw/1789a2131ee389aeb44e3a9d5333f59cfeebc089/moip-icon.png" align="right" />

# MoIP v2 PHP client SDK
> The most simple and fast way to integrate the Moip into your PHP application.

> Current SDK's state

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/moip/moip-sdk-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/moip/moip-sdk-php/?branch=master)
[![Code Climate](https://codeclimate.com/github/moip/moip-sdk-php/badges/gpa.svg)](https://codeclimate.com/github/moip/moip-sdk-php)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/9e877cf78f844b9a9e40cec175c3aa5a)](https://www.codacy.com/app/jeancesargarcia/moip-sdk-php?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=moip/moip-sdk-php&amp;utm_campaign=Badge_Grade)
[![StyleCI](https://styleci.io/repos/19941899/shield)](https://styleci.io/repos/19941899)
[![Build Status](https://travis-ci.org/moip/moip-sdk-php.svg?branch=master)](https://travis-ci.org/moip/moip-sdk-php)

> Statistics

[![Total Downloads](https://poser.pugx.org/moip/moip-sdk-php/downloads)](https://packagist.org/packages/moip/moip-sdk-php)
[![Monthly Downloads](https://poser.pugx.org/moip/moip-sdk-php/d/monthly)](https://packagist.org/packages/moip/moip-sdk-php)

> Versions

[![Latest Stable Version](https://poser.pugx.org/moip/moip-sdk-php/v/stable)](https://packagist.org/packages/moip/moip-sdk-php)
[![Latest Unstable Version](https://poser.pugx.org/moip/moip-sdk-php/v/unstable)](https://packagist.org/packages/moip/moip-sdk-php)

---

**Index**

- [Installing](#installing)
- [Configuring the authentication](#configuring-the-authentication)
  - [By Basic Auth](#by-basic-auth)
  - [By OAuth](#by-oauth)
- [Application examples](#customers)
  - [Customers](#customers)
    - [Create customer](#create-customer)
    - [Get customer](#get-customer)
    - [Add credit card](#add-credit-card)
    - [Delete credit card](#delete-credit-card)
  - [Orders](#orders)
    - [Create order](#create-order)
    - [Get order](#get-order)
    - [List orders](#list-orders)
      - [List orders without filters](#list-orders-without-filters)
      - [List orders with filters](#list-orders-with-filters)
      - [List orders with pagination](#list-orders-with-pagination)
      - [List orders by specific values](#list-orders-by-specific-values)
      - [List orders with all search parameters](#list-orders-with-all-search-parameters)
  - [Payments](#payments)
    - [Create payment](#create-payment)
      - [Payment with credit card](#payment-with-credit-card)
        - [Payment with credit card hash](#payment-with-credit-card-hash)
        - [Payment with credit card data](#payment-with-credit-card-data)
      - [Payment with boleto](#payment-with-boleto)
      - [Payment with online bank debit](#payment-with-online-bank-debit)
    - [Get payment](#get-payment)
    - [Create a pre-authorized payment](#create-a-pre-authorized-payment)
    - [Capture pre-authorized payment](#capture-pre-authorized-payment)
    - [Cancel pre-authorized payment](#cancel-pre-authorized-payment)
  - [Refunds](#refunds)
    - [Refund to credit card](#refund-to-credit-card)
      - [Credit card full refund](#credit-card-full-refund)
      - [Credit card partial refund](#credit-card-partial-refund)
    - [Refund to bank account](#refund-to-bank-account)
      - [Bank account full refund](#bank-account-full-refund)
      - [Bank account partial refund](#bank-account-partial-refund)
    - [Get refund](#get-refund)
  - [Moip Connect setup](#moip-connect-setup)
    - [Create APP](#create-app)
  - [Moip Accounts](#moip-accounts)
    - [Verify if user has Moip Account](#verify-if-user-has-moip-account)
    - [Classical Moip Account](#classical-moip-account)
      - [Create classical Moip Account for person](#create-classical-moip-account-for-person)
      - [Create classical Moip Account for enterprise](#create-classical-moip-account-for-enterprise)
    - [Transparent Moip Account](#transparent-moip-account)
    - [Get Moip Account](#get-moip-account)
    - [Request access permission to the user](#request-access-permission-to-the-user)
    - [Generate the Access Token](#generate-the-access-token)
  - [Balances](#balances)
    - [Get balances](#get-balances)
  - [Multiorders](#multiorders)
    - [Create a Multiorder](#create-a-multiorder)
    - [Get Multiorder](#get-multiorder)
  - [Multipayments](#multipayments)
    - [Create Multipayment](#create-multipayment)
    - [Get Multipayment](#get-multipayment)
  - [Bank Accounts](#bank-accounts)
    - [Create a Bank Account](#create-a-bank-account)
    - [Get Bank Account](#get-bank-account)
    - [List Bank Accounts](#list-bank-accounts)
    - [Update Bank Account](#update-bank-account)
    - [Delete Bank Account](#delete-bank-account)
  - [Transfers](#transfers)
    - [Create a Transfer](#create-a-transfer)
      - [Transfer to a Bank Account](#transfer-to-a-bank-account)
    - [Get Transfer](#get-transfer)
    - [List Transfers](#list-transfers)
      - [List Transfers without pagination](#list-transfers-without-pagination)
      - [List Transfers with pagination](#list-transfers-with-pagination)
    - [Revert Transfer](#revert-transfer)
  - [Notification Preferences](#notification-preferences)
    - [Create a Notification Preference](#create-a-notification-preference)
    - [Get Notification Preference](#get-notification-preference)
    - [List Notification Preferences](#list-notification-preferences)
    - [Delete Notification Preference](#delete-notification-preference)
  - [Webhooks](#webhooks)
    - [List Webhooks](#list-webhooks)
      - [List Webhooks without search parameters](#list-webhooks-without-search-parameters)
      - [List Webhooks with search parameters](#list-webhooks-with-search-parameters)
  - [Exceptions Treatment](#exceptions-treatment)
  - [Documentation](#documentation)
  - [Tests](#tests)
  - [License](#license)

## Packages

* [Laravel 5.x](https://github.com/artesaos/moip)
* [Symfony 2 ou 3](https://github.com/leonnleite/moip-bundle)
* [Laravel 4.x (MoIP API v1)](https://github.com/SOSTheBlack/moip)

## Dependencies
#### require
* PHP >= 5.5
* rmccue/requests >= 1.0

#### require-dev
* phpunit/phpunit ~ 4.0

## Installing
Run on your shell:

> composer require moip/moip-sdk-php

## Configuring the authentication

### By Basic Auth
```php
require 'vendor/autoload.php';

use Moip\Moip;
use Moip\Auth\BasicAuth;

$token = '01010101010101010101010101010101';
$key = 'ABABABABABABABABABABABABABABABABABABABAB';

$moip = new Moip(new BasicAuth($token, $key), Moip::ENDPOINT_SANDBOX);
```

### By OAuth
```php
require 'vendor/autoload.php';

use Moip\Moip;
use Moip\Auth\OAuth;

$access_token = '33031e2aad484051b89030487e59d133_v2';
$moip = new Moip(new OAuth($access_token), Moip::ENDPOINT_SANDBOX);
```

## Customers
The **Customer** is a service's user or a buyer of a virtual store.

### Create customer
```php
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
print_r($customer);
```

### Get customer
```php
$customer = $moip->customers()->get('CUS-Q3BL0CAJ2G33');
print_r($customer);
```

### Add credit card
This endpoint allows you to add one or more credit cards to a customer.

```php
$customer = $moip->customers()->creditCard()
    ->setExpirationMonth('12')
    ->setExpirationYear(2018)
    ->setNumber('4012001037141112')
    ->setCVC('123')
    ->setFullName('Jose Portador da Silva')
    ->setBirthDate('1988-12-30')
    ->setTaxDocument('CPF', '33333333333')
    ->setPhone('55','11','66778899')
    ->create();
```

### Delete credit card
This endpoint allows you to delete a customer's saved credit card.

```php
$moip->customers()->creditCard()->delete(CREDIT_CARD_ID);
```

## Orders
The **Order** is the representation of a product or a provided service.

### Create order
This endpoint allows you to create a Order containing the data of a product.

You can use the customer's ID (informed in the customer request's response), calling the method `setCustomerId()`.
```php
$order = $moip->orders()->setOwnId(uniqid())
    ->addItem("bicicleta 1", 1, "sku1", 10000)
    ->addItem("bicicleta 2", 1, "sku2", 11000)
    ->addItem("bicicleta 3", 1, "sku3", 12000)
    ->addItem("bicicleta 4", 1, "sku4", 13000)
    ->setShippingAmount(3000)
    ->setAddition(1000)
    ->setDiscount(5000)
    ->setCustomerId($customerId)
    ->create();
```

Or you can use the customer object, calling the method `setCustomer()`.
```php
$order = $moip->orders()->setOwnId(uniqid())
    ->addItem("bicicleta 1", 1, "sku1", 10000)
    ->addItem("bicicleta 2", 1, "sku2", 11000)
    ->addItem("bicicleta 3", 1, "sku3", 12000)
    ->addItem("bicicleta 4", 1, "sku4", 13000)
    ->setShippingAmount(3000)
    ->setAddition(1000)
    ->setDiscount(5000)
    ->setCustomer($customer)
    ->create();
```

### Get order
Calling this method, you can get the details of an specific Order.
```php
$order = $moip->orders()->get('ORD-KZCH1S1ORAH23');
```

### List orders
Another way is listing all created orders, with or without filters and search parameters.

#### List orders without filters
```php
$orders = $this->moip->orders()->getList();
```

#### List orders with filters
```php
$filters = new Filters();
$filters->greaterThanOrEqual(OrdersList::CREATED_AT, '2017-08-17');
$filters->in(OrdersList::PAYMENT_METHOD, ['BOLETO', 'DEBIT_CARD']);
$filters->lessThan(OrdersList::VALUE, 100000);

$orders = $this->moip->orders()->getList(null, $filters);
```

#### List orders with pagination
```php
$orders = $this->moip->orders()->getList(new Pagination(10,0));
```

#### List orders by specific values
```php
$orders = $this->moip->orders()->getList(null, null, 'josé silva');
```

#### List orders with all search parameters
You can use all search parameters in the same request.
```php
$filters = new Filters();
$filters->greaterThanOrEqual(OrdersList::CREATED_AT, '2017-08-17');
$filters->lessThan(OrdersList::VALUE, 100000);

$orders = $this->moip->orders()->getList(new Pagination(10,0), $filters, 'josé silva');
```

## Payments
The **Payment** is a financial transaction between the **Customer** and the **Receiver** by credit card, boleto or another payment method.

### Create payment
There are three payment methods:

#### Payment with credit card
Before requesting the payment by credit card it is necessary to set the **holder** (the owner of the credit card) informations. This step is very important, because the holder may be the customer or someone else, a third party.
```php
$holder = $moip->holders()->setFullname('Jose Silva')
    ->setBirthDate("1990-10-10")
    ->setTaxDocument('22222222222', 'CPF')
    ->setPhone(11, 66778899, 55)
    ->setAddress('BILLING', 'Avenida Faria Lima', '2927', 'Itaim', 'Sao Paulo', 'SP', '01234000', 'Apt 101');
```

##### Payment with credit card hash
To pay by **credit card hash**, it is necessary to encrypt the card's informations.
```php
$hash = 'i1naupwpTLrCSXDnigLLTlOgtm+xBWo6iX54V/hSyfBeFv3rvqa1VyQ8/pqWB2JRQX2GhzfGppXFPCmd/zcmMyDSpdnf1GxHQHmVemxu4AZeNxs+TUAbFWsqEWBa6s95N+O4CsErzemYZHDhsjEgJDe17EX9MqgbN3RFzRmZpJqRvqKXw9abze8hZfEuUJjC6ysnKOYkzDBEyQibvGJjCv3T/0Lz9zFruSrWBw+NxWXNZjXSY0KF8MKmW2Gx1XX1znt7K9bYNfhA/QO+oD+v42hxIeyzneeRcOJ/EXLEmWUsHDokevOkBeyeN4nfnET/BatcDmv8dpGXrTPEoxmmGQ==';
```

> If you don't know how to generate the **hash**, click [here](http://moip.github.io/moip-sdk-js/) to use the **Moip's Credit Card Cryptography**.

With the **hash** and the **holder**, is possible create the payment.
```php
$payment = $order->payments()
    ->setCreditCardHash($hash, $holder)
    ->setInstallmentCount(3)
    ->setStatementDescriptor('teste de pag')
    ->execute();
```

##### Payment with credit card data
Another way to create a payment is to use the credit card data, without encryption.

> Warning!
> This method require PCI compliance, ([check the Moip documentation](https://dev.moip.com.br/v2/docs/pci-compliance)).

```php
$payment = $order->payments()->setCreditCard(12, 21, '4073020000000002', '123', $holder)
    ->execute();
```

#### Payment with boleto
To create a boleto payment it is necessary to send just three parameters:
* the `logo_uri` (String) - URL containing the site logo (optional);
* the `expiration_date` (String) - date of boleto expiration;
* the `instruction_lines` (String array {3}) - boleto instructions (optional).
```php
$logo_uri = 'https://cdn.moip.com.br/wp-content/uploads/2016/05/02163352/logo-moip.png';
$expiration_date = new DateTime();
$instruction_lines = ['INSTRUÇÃO 1', 'INSTRUÇÃO 2', 'INSTRUÇÃO 3'];
$payment = $order->payments()  
    ->setBoleto($expiration_date, $logo_uri, $instruction_lines)
    ->execute();
```

#### Payment with online bank debit
This method requires three parameters too:
* the `bank_number` (String) - number of the bank (actually, the only possible value is **341**, referring to Itaú Bank);
* the `return_uri` (String) - URL to which the user will be redirect;
* the `expiration_date` (DateTime) - the date of online bank debit expiration.
```php
$bank_number = '341';
$return_uri = 'https://moip.com.br';
$expiration_date = new DateTime();
$payment = $order->payments()                    
    ->setOnlineBankDebit($bank_number, $expiration_date, $return_uri)
    ->execute();
```

### Get payment
```php
$payment = $moip->payments()->get('PAYMENT-ID');
```

### Create a pre-authorized payment
The new APIs allow you to control the flow of pre-authorization and capture a credit card transaction. This way, instead of the default configuration, where Moip captures the transaction after the anti-fraud analysis (Authorized status), you can control when the capture will be made. The deadline to capture is **5 days** after the payment pre-authorization.

The only addition you have to make to create a pre-authorized payment is to add the parameter `delayCapture`.
```php
$payment = $order->payments()
	->setCreditCardHash($hash, $holder)
	->setInstallmentCount(3)
	->setStatementDescriptor("Minha Loja")
 	->setDelayCapture()
 	->execute();
```

### Capture pre-authorized payment
```php
$captured_payment = $payment->capture();
```

### Cancel pre-authorized payment
```php
$payment = $payment->cancel();
```

## Refunds
The **Refund** is the devolution of a payment to the customer/payer. To make the refund request, you should have the **Payment object** or the **Payment ID**, related with payment that you want refund.

### Refund to credit card
#### Credit card full refund
```php
$refund = $payment->refunds()->creditCardFull();
```

#### Credit card partial refund
```php
$refund = $payment->refunds()->creditCardPartial(30000);
```

### Refund to bank account
#### Bank account full refund
```php
$type = 'CHECKING';
$bank_number = '001';
$agency_number = 4444444;
$agency_check_number = 2;
$account_number = 1234;
$account_check_number = 4;
$refund = $payment->refunds()
    ->bankAccountFull(
        $type,
        $bank_number,
        $agency_number,
        $agency_check_number,
        $account_number,
        $account_check_number,
        $customer
    );
```

#### Bank account partial refund
```php
$amount = 30000;
$type = 'SAVING';
$bank_number = '001';
$agency_number = 4444444;
$agency_check_number = 2;
$account_number = 1234;
$account_check_number = 4;
$refund = $payment->refunds()
    ->bankAccountPartial(
        $amount,
        $type,
        $bank_number,
        $agency_number,
        $agency_check_number,
        $account_number,
        $account_check_number,
        $customer
    );
```

### Get refund
```php
$refund = $payment->refunds()->get($refundId);
```

## Moip Connect setup
**Moip Connect**  allows your application to be a Marketplace, an app to contract services, an e-commerce platform or any system to help people and enterprises receive payments. This process allows your application to access informations of the user's Moip Account, creating transactions, making transfers, visualizing the balance, etc.
You'll register your application on Moip, connect with your user's Moip Account (in case they have one already) or create new accounts.

Thereby, you can:
* **request payments on behalf your users**;
* **get user's Moip Account informations**.

There are two ways of using **Connect**:
1. get permission to connect with user's Moip Account using **OAuth 2.0**;
2. create **Transparent Account**, managed by your own application, checkout the difference between Classical Account and Transparent Account [here](https://dev.moip.com.br/docs/conta-classica-e-conta-transparente).

### Create APP
Check the [API reference](https://dev.moip.com.br/v2/reference#1-criar-um-app) to know how create a Moip APP.

## Moip Accounts
To offer a amazing on-boarding, the Marketplace should consider **_which experience it wants to give to their merchants_**.

Thinking this, Moip developed two options to help you give the best experience to your clients!

### Verify if user has Moip Account
Before creating an account, you can check if the user already has a Moip Account. This can save you some time... This endpoing receives the CPF or CNPJ as a parameter.
```php
$moip->accounts()->checkAccountExists('CPF');
```

> It will return 200 if the user already has an account or 400 if the user hasn't an account.

### Classical Moip Account
The **Classical Moip Account** allows users to manage their transactions easily, having available a complete dashboard (with Moip's layout) where it is possible to track incomes and set the best options to their business.

> If you don't want to provide a feature to create a **Classical Moip Account** on your application, you may redirect the user to [Moip account creation](https://bem-vindo.moip.com.br/).

#### Create classical Moip Account for person
```php
$street = 'Rua de teste';
$number = 123;
$district = 'Bairro';
$city = 'Sao Paulo';
$state = 'SP';
$zip = '01234567';
$complement = 'Apt. 23';
$country = 'BRA';
$area_code = 11;
$phone_number = 66778899;
$country_code = 55;
$identity_document = '4737283560';
$issuer = 'SSP';
$issue_date = '2015-06-23';
$account = $moip->accounts()
    ->setName('Fulano')
    ->setLastName('De Tal')
    ->setEmail('fulano@email2.com')
    ->setIdentityDocument($identity_document, $issuer, $issue_date)
    ->setBirthDate('1988-12-30')
    ->setTaxDocument('16262131000')
    ->setType('MERCHANT')
    ->setPhone($area_code, $phone_number, $country_code)
    ->addAlternativePhone(11, 66448899, 55)
    ->addAddress($street, $number, $district, $city, $state, $zip, $complement, $country)
    ->create();
```

#### Create classical Moip Account for enterprise
```php
$street = 'Rua de teste';
$number = 123;
$district = 'Bairro';
$city = 'Sao Paulo';
$state = 'SP';
$zip = '01234567';
$complement = 'Apt. 23';
$country = 'BRA';
$area_code = 11;
$phone_number = 66778899;
$country_code = 55;
$identity_document = '4737283560';
$issuer = 'SSP';
$issue_date = '2015-06-23';
$account = $moip->accounts()
    ->setName('Fulano')
    ->setLastName('De Tal')
    ->setEmail('fulano@email2.com')
    ->setIdentityDocument($identity_document, $issuer, $issue_date)
    ->setBirthDate('1988-12-30')
    ->setTaxDocument('16262131000')
    ->setType('MERCHANT')
    ->setPhone($area_code, $phone_number, $country_code)
    ->addAlternativePhone(11, 66448899, 55)
    ->addAddress($street, $number, $district, $city, $state, $zip, $complement, $country)        
    ->setCompanyName('Empresa Teste', 'Teste Empresa ME')
    ->setCompanyOpeningDate('2011-01-01')
    ->setCompanyPhone(11, 66558899, 55)
    ->setCompanyTaxDocument('69086878000198')
    ->setCompanyAddress('Rua de teste 2', 123, 'Bairro Teste', 'Sao Paulo', 'SP', '01234567', 'Apt. 23', 'BRA')
    ->setCompanyMainActivity('82.91-1/00', 'Atividades de cobranças e informações cadastrais')
    ->create();
```

### Transparent Moip Account
But what if you don't want your sellers to know about the existence of Moip behind your marketplace?

It is simple, Moip can process your transactions in a transparent way. To attend this issue, we have what is called **Moip Transparent Account**.

> **WARNING!**
> If you choose to use the **transparent account**, you should assume the responsibility of providing all information and resources your sellers need to manage their business, besides that you will assume full responsibility of support to your sellers.

```php
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
        ->create();
```

### Get Moip Account
```php
$account = $moip->accounts()->get(ACCOUNT_ID);
```

### Get public key of Moip Account
The **public key** is used to encrypt the credit card data. It's available on [Sandbox](https://conta-sandbox.moip.com.br/configurations/api_credentials) and [Production](https://conta.moip.com.br/configurations/api_credentials) (after the homologation) accounts. If you want to learn more about _how to use the public key_, check our documentation about [Encryption](https://dev.moip.com.br/v2/docs/criptografia-cartao) (PT-BR).

> IMPORTANT!
> The homologation is the process where we'll test your integration, certifying that all is correct. To learn more about this process, check our [documentation](https://dev.moip.com.br/v2/docs/homologa%C3%A7%C3%A3o-1) (PT-BR).

```php
$keys = $moip->keys()->get();
```

### Request access permission to the user
To request permissions, you'll need to call the method `getAuthUrl` (responsible for generating the URLs), sending the below parameters:

|parameters|type|example value
| :---: | :---: | :---: |
| redirect_uri | String | http://seusite.com.br/callback.php |
| client_id | String | APP-18JTHC3LOMT9 |
| scope | boolean | true |

> IMPORTANT!
> The `redirect_uri` **must be the same** as the one registered in the APP.

```php
$redirect_uri = 'http://seusite.com.br/callback.php';
$client_id = 'APP-18JTHC3LOMT9';
$scope = true;
$connect = new Connect($redirect_uri, $client_id, $scope, Connect::ENDPOINT_SANDBOX);
$connect->setScope(Connect::RECEIVE_FUNDS)
    ->setScope(Connect::REFUND)
    ->setScope(Connect::MANAGE_ACCOUNT_INFO)
    ->setScope(Connect::RETRIEVE_FINANCIAL_INFO);
header('Location: '.$connect->getAuthUrl());
```
Possibles permissions scopes:

| scopes | description |
| :---: | :--- |
|RECEIVE_FUNDS| permission to create and consult **orders**, **payments**, **multiorders** and **multipayments**; consult **entries**; get **sales reconciliation** files. |
| REFUND | permission to create and consult **refunds**. |
| MANAGE_ACCOUNT_INFO | permission to consult registered information of **account**. |
| RETRIEVE_FINANCIAL_INFO | permission to consult the **account balances** and get **sales reconciliation** files. |
| TRANSFER_FUNDS | permission to create **transfers** to a **bank account** and between **Moip accounts**. |
| DEFINE_PREFERENCES | permission to notification preferences creation, modification and deletion. |

### Generate the Access Token
When the permission is granted, a **code** will be returned on the redirect URL endpoint. This code will allow you to generate the **`accessToken`**. It's used to authenticate the requests involving the Moip account that granted the permission.

```php
$redirect_uri = 'http://seusite.com.br/callback.php';
$client_id = 'APP-18JTHC3LOMT9';
$scope = true;
$connect = new Connect($redirect_uri, $client_id, $scope, Connect::ENDPOINT_SANDBOX);
$client_secret = '20f76456f6ec4874a1f38082d3139326';
$connect->setClientSecret($client_secret);
$code = 'f9053ca6e9853dd73f0bc4f332a5ce337b0bb0da';
$connect->setCode($code);
$auth = $connect->authorize();
```

> IMPORTANT!
> * To run this request correctly, the object `Connect` must be instantiated, sending the necessary parameters, like the above example.
> * The `redirect_uri` **must be the same** as registered in the APP. If there is any divergence, the `accessToken` will not be generated.

## Balances
The **Moip balance** is the composition of current values available, unavailable (blocked) and futures, of a Moip Account.

> This API uses **`application/json;version=2.1`**. For more information, check our [API reference](https://dev.moip.com.br/v2/reference#saldo-moip-1).

### Get balances
```php
$balances = $moip->balances()->get();
```

## Multiorders
The **multiorder** is a collection of orders. It's used to allow transactions with different sellers in the same shopping cart. When a multiorder is created with a single customer interaction, Moip generates multiple charges and associates each of them with the appropriate seller, simplifying the management of Marketplaces.

> IMPORTANT!
> By definition, it's not possible refund a multiorder entirely at once, but you can refund order by order.

### Create a Multiorder
As multiorder is composed by an array of orders, its structure is very similar from a simple order structure.

```php
$order = $moip->orders()->setOwnId(uniqid())
    ->addItem("bicicleta 1",1, "sku1", 10000)
    ->addItem("bicicleta 2",1, "sku2", 11000)
    ->addItem("bicicleta 3",1, "sku3", 12000)
    ->addItem("bicicleta 4",1, "sku4", 13000)
    ->setShippingAmount(3000)
    ->setAddition(1000)
    ->setDiscount(5000)
    ->setCustomer($customer)
    ->addReceiver('MPA-VB5OGTVPCI52', 'PRIMARY', NULL);

$order2 = $moip->orders()->setOwnId(uniqid())
    ->addItem("bicicleta 1",1, "sku1", 10000)
    ->addItem("bicicleta 2",1, "sku2", 11000)
    ->addItem("bicicleta 3",1, "sku3", 12000)
    ->setShippingAmount(3000)
    ->setAddition(1000)
    ->setDiscount(5000)
    ->setCustomer($customer)
    ->addReceiver('MPA-IFYRB1HBL73Z', 'PRIMARY', NULL);

$multiorder = $this->moip->multiorders()
    ->setOwnId(uniqid())
    ->addOrder($order)
    ->addOrder($order2)
    ->create();
```

### Get Multiorder
```php
$multiorder_id = 'ORD-KZCH1S1ORAH25';
$multiorder = $moip->multiorders()->get($multiorder_id);
print_r($multiorder);
```

## Multipayments
The **multipayment** is a collection of payments associated with a multiorder. It's used in the implementation of the **shopping cart/multiorder**, when it is necessary charge for different sellers with only one checkout. When a multipayment is created Moip creates a payment for each order of a multiorder and does the auto-charge.

> IMPORTANT!
> In cases of multipayment with credit card, multiple authorizations are generated, one for each payment, dividing the charges of a customer, to facilitate the management of the Marketplace or Platform.

### Create Multipayment
In the same way that multiorder's structure is similar to order's structure, the multipayment is similar to payment.

```php
$hash = 'i1naupwpTLrCSXDnigLLTlOgtm+xBWo6iX54V/hSyfBeFv3rvqa1VyQ8/pqWB2JRQX2GhzfGppXFPCmd/zcmMyDSpdnf1GxHQHmVemxu4AZeNxs+TUAbFWsqEWBa6s95N+O4CsErzemYZHDhsjEgJDe17EX9MqgbN3RFzRmZpJqRvqKXw9abze8hZfEuUJjC6ysnKOYkzDBEyQibvGJjCv3T/0Lz9zFruSrWBw+NxWXNZjXSY0KF8MKmW2Gx1XX1znt7K9bYNfhA/QO+oD+v42hxIeyzneeRcOJ/EXLEmWUsHDokevOkBeyeN4nfnET/BatcDmv8dpGXrTPEoxmmGQ==';
$payment = $multiorder->multipayments()
    ->setCreditCardHash($hash, $customer)
    ->setInstallmentCount(3)
    ->setStatementDescriptor('teste de pag')
    ->execute();
print_r($payment);
```

### Get Multipayment
```php
$payment = $moip->payments()->get('MULTIPAYMENT-ID');
print_r($payment);
```

## Bank Accounts
The **bank account** is the bank address of a Moip Account.

### Create a Bank Account
```php
$bankAccount = $moip->bankaccount()
    ->setBankNumber('237')
    ->setAgencyNumber('12345')
    ->setAgencyCheckNumber('0')
    ->setAccountNumber('12345678')
    ->setAccountCheckNumber('7')
    ->setType('CHECKING')
    ->setHolder('Demo Moip', '622.134.533-22', 'CPF')
    ->create($moipAccountId);
```

### Get Bank Account
```php
$bankAccount = $moip->bankaccount()->get($bankAccountId);
```

### List Bank Accounts
```php
$bankAccounts = $moip->bankaccount()->getList($moipAccountId)->getBankAccounts();
```

### Update Bank Account
With this feature you can update all bank account data.

```php
$bankAccount = $moip->bankaccount()
    ->setAccountCheckNumber('8')
    ->update($bankAccountId);
```

### Delete Bank Account
```php
$moip->bankaccount()->delete($bankAccountId);
```

## Transfers
The **Transfer** is a fund movement between the Moip account and another payment account (it can be a bank account or another Moip account).

### Create a Transfer
The transfer can be created to a bank account (previously created or not) or to another Moip account.

#### Transfer to a Bank Account
```php
$amount = 500;
$bankNumber = '001';
$agencyNumber = '1111';
$agencyCheckNumber = '2';
$accountNumber = '9999';
$accountCheckNumber = '8';
$holderName = 'Nome do Portador';
$taxDocument = '22222222222';

$transfer = $moip->transfers()
    ->setTransfers($amount, $bankNumber, $agencyNumber, $agencyCheckNumber, $accountNumber, $accountCheckNumber)
    ->setHolder($holderName, $taxDocument)
    ->execute();
```

To transfer using a previously created bank account:
```php
$transfer = $moip->transfers()
    ->setTransfersToBankAccount($amount, $bankAccountId)
    ->execute();
```

### Get Transfer
```php
$transferId = 'TRA-28HRLYNLMUFH';
$transfer = $moip->transfers()->get($transferId);
```

### List Transfers
Is possible to use search parameters to list the transfers.

| parameters | type |
| :---: | :---: |
| pagination | integer |

#### List Transfers without pagination
```php
$transfers = $moip->transfers()->getList();
```

#### List Transfers with pagination
```php
$transfers = $moip->transfers()->getList(new Pagination(10,0));
```

### Revert Transfer
```php
$transferId = 'TRA-28HRLYNLMUFH';

$transfer = $moip->transfers()->revert($transferId);
```

## Notification Preferences
**Webhooks** are the notifications sent by Moip to your system every time your transaction has its status changed. So, through webhooks, is possible to synchronize your application with Moip.

### Create a Notification Preference
To receive webhooks you should create a notification preference. At this moment, you can register one or more URLs to receive webhooks.

```php
$notification = $moip->notifications()->addEvent('ORDER.*')
    ->addEvent('PAYMENT.AUTHORIZED')
    ->setTarget('http://requestb.in/1dhjesw1')
    ->create();
```

### Get Notification Preference
```php
$notification = $this->moip->notifications()->get('NPR-N6QZE3223P98');
```

### List Notification Preferences
```php
$notifications = $moip->notifications()->getList();
```

### Delete Notification Preference
```php
$notification = $moip->notifications()->delete('NOTIFICATION-ID');
```

## Webhooks
PHP is, by default, able to receive just some types of 'content-type' ('application/x-www-form-urlencoded' and 'multipart/form-data'), but Moip sends data in JSON format. So, to receive and access the data sent by Moip, you should add the below code to the file which will receive the notifications.

```php
// Gets the request's raw data
$json = file_get_contents('php://input');
// Converts data to JSON
$response = json_decode($json, true);
```

### List Webhooks
This listing can be filtered with some search parameters:

| parameters | type |
| :---: | :---: |
| pagination | Integer |
| order/payment ID | String |
| status/event | String |

#### List Webhooks without search parameters
```php
$moip->webhooks()->get();
```

#### List Webhooks with search parameters
```php
$moip->webhooks()->get(new Pagination(10, 0), 'ORD-ID', 'ORDER.PAID');
```

## Exceptions Treatment
When an error occurs on the API, an exception it's thrown:

| errors | cause | status |
| :---: | :---: | :---: |
| UnautorizedException | to authentication errors | == 401 |
| ValidationException | to validation errors | >= 400 && <= 499 (except 401) |
| UnexpectedException | to unexpected errors | >= 500 |

To catch these errors for debug:

```php
try {
    $moip->customers()->setOwnId(uniqid())
        ->setFullname('Fulano de Tal')
        ->setEmail('fulano@email.com')
        //...
        ->create();
} catch (\Moip\Exceptions\UnautorizedException $e) {
    //StatusCode 401
    echo $e->getMessage();
} catch (\Moip\Exceptions\ValidationException $e) {
    //StatusCode entre 400 e 499 (exceto 401)
    printf($e->__toString());
} catch (\Moip\Exceptions\UnexpectedException $e) {
    //StatusCode >= 500
    echo $e->getMessage();
}
```

## Documentation
[Official documentation](https://dev.moip.com.br/v2/docs).

## Tests
By default, the tests don't make any request to Moip API. However, is possible run the tests on Sandbox environment. In order to do it, it's enough set the environment variable:
    - `MOIP_ACCESS_TOKEN` (the authorization token of your [Moip APP](https://dev.moip.com.br/v2/docs/app-1)).

Example:
```shell
export MOIP_ACCESS_TOKEN=76926cb0305243c8adc79aad54321ec1_v2
vendor/bin/phpunit -c .
```

## License
[The MIT License](https://github.com/moip/php-sdk/blob/master/LICENSE)