# Moip v2 PHP client SDK
O jeito mais simples e rápido de integrar o Moip a sua aplicação PHP

> Estado atual do sdk

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/moip/moip-sdk-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/moip/moip-sdk-php/?branch=master)
[![Code Climate](https://codeclimate.com/github/moip/moip-sdk-php/badges/gpa.svg)](https://codeclimate.com/github/moip/moip-sdk-php)
[![Codacy Badge](https://www.codacy.com/project/badge/186f98a92a004554abeef36452850004)](https://www.codacy.com/app/jeancesargarcia/moip-sdk-php)
[![StyleCI](https://styleci.io/repos/19941899/shield)](https://styleci.io/repos/19941899)
[![Build Status](https://travis-ci.org/moip/moip-sdk-php.svg?branch=master)](https://travis-ci.org/moip/moip-sdk-php)

> Informações

[![Dependency Status](https://gemnasium.com/moip/moip-sdk-php.svg)](https://gemnasium.com/moip/moip-sdk-php)
[![Github Issues](http://githubbadges.herokuapp.com/moip/moip-sdk-php/issues.svg?style=square)](https://github.com/moip/moip-sdk-php/issues)
[![Github Pulls](http://githubbadges.herokuapp.com/moip/moip-sdk-php/pulls.svg?style=square)](https://github.com/moip/moip-sdk-php/issues)

> Estatísticas

[![Total Downloads](https://poser.pugx.org/moip/moip-sdk-php/downloads)](https://packagist.org/packages/moip/moip-sdk-php)
[![Monthly Downloads](https://poser.pugx.org/moip/moip-sdk-php/d/monthly)](https://packagist.org/packages/moip/moip-sdk-php)
[![Daily Downloads](https://poser.pugx.org/moip/moip-sdk-php/d/daily)](https://packagist.org/packages/moip/moip-sdk-php)
![Repo Size](https://reposs.herokuapp.com/?path=Moip/moip-sdk-php)

> Versãoes

[![Latest Stable Version](https://poser.pugx.org/moip/moip-sdk-php/v/stable)](https://packagist.org/packages/moip/moip-sdk-php)
[![Latest Unstable Version](https://poser.pugx.org/moip/moip-sdk-php/v/unstable)](https://packagist.org/packages/moip/moip-sdk-php)

> Licença

[![License](https://poser.pugx.org/moip/moip-sdk-php/license)](https://packagist.org/packages/moip/moip-sdk-php)

---

## Dependências

* PHP >= 5.5.9

## Instalação

#### Usando Terminal

Execute:

    composer require moip/moip-sdk-php 1.@stable
    
## Configurando sua autenticação
```php
require 'vendor/autoload.php';

use Moip\Moip;
use Moip\MoipBasicAuth;

$token = '01010101010101010101010101010101';
$key = 'ABABABABABABABABABABABABABABABABABABABAB';

$moip = new Moip(new MoipBasicAuth($token, $key), Moip::ENDPOINT_SANDBOX);
```

## Criando um pedido
Nesse exemplo será criado um pedido com dados do cliente - Com endereço de entrega e de pagamento.

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
                                              '01234567', 8);
    
```
## Setando produtos e valores de pagamento
Nesse exemplo com vários produtos e ainda especificando valor de frete (ShippingAmount) valor adcional (Addtion) e ainda valor de desconto
```php
$order = $moip->orders()->setOwnId(uniqid())
                            ->addItem("bicicleta 1",1, "sku", 10000)
                            ->addItem("bicicleta 2",1, "sku", 10000)
                            ->addItem("bicicleta 3",1, "sku", 10000)
                            ->addItem("bicicleta 4",1, "sku", 10000)
                            ->addItem("bicicleta 5",1, "sku", 10000)
                            ->addItem("bicicleta 6",1, "sku", 10000)
                            ->addItem("bicicleta 7",1, "sku", 10000)
                            ->addItem("bicicleta 8",1, "sku", 10000)
                            ->addItem("bicicleta 9",1, "sku", 10000)
                            ->addItem("bicicleta 10",1, "sku", 10000)

                            ->setShippingAmount(3000)->setAddition(100)->setDiscount(500)
                            ->setCustomer($customer)
                            ->create();
    
    
```

## Criando o pagamento
Após criar o pedido basta criar um pagamento nesse pedido. Usando a opção DelayCapture (caso não queira basta remover a info )

```php
$payment =  $order->payments()->setCreditCard(12, 25, '4073020000000002', '123', $customer)->setDelayCapture()
            ->execute();
```

## Criando uma solicitação de transferência automática
Atenção para este processo é necessário usar o Oauth

```php
    $moip = new Moip(new MoipOAuth($oauth), Moip::ENDPOINT_PRODUCTION);
    $transfers = $moip->transfers()->setTransfers('500','001','1111','2','9999','8')->setHolder('BRUNO ELISEI', '34057603808')->execute();
```

## Documentação

[Documentação oficial](https://moip.com.br/referencia-api/)

## Licença

[The MIT License](https://github.com/moip/php-sdk/blob/master/LICENSE)
