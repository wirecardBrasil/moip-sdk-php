# Moip v2 PHP client SDK
O jeito mais simples e rápido de integrar o Moip a sua aplicação PHP

> Estado atual do sdk

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/moip/moip-sdk-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/moip/moip-sdk-php/?branch=master)
[![Code Climate](https://codeclimate.com/github/moip/moip-sdk-php/badges/gpa.svg)](https://codeclimate.com/github/moip/moip-sdk-php)
[![Codacy Badge](https://www.codacy.com/project/badge/186f98a92a004554abeef36452850004)](https://www.codacy.com/app/jeancesargarcia/moip-sdk-php)
[![Build Status](https://scrutinizer-ci.com/g/moip/moip-sdk-php/badges/build.png?b=master)](https://scrutinizer-ci.com/g/moip/moip-sdk-php/build-status/master) 
[![Project Status](http://stillmaintained.com/SOSTheBlack/moip-sdk-php.png)](https://stillmaintained.com/SOSTheBlack/moip-sdk-php)

> Estatísticas

[![Total Downloads](https://poser.pugx.org/moip/moip-sdk-php/downloads)](https://packagist.org/packages/moip/moip-sdk-php)
[![Monthly Downloads](https://poser.pugx.org/moip/moip-sdk-php/d/monthly)](https://packagist.org/packages/moip/moip-sdk-php)
[![Daily Downloads](https://poser.pugx.org/moip/moip-sdk-php/d/daily)](https://packagist.org/packages/moip/moip-sdk-php)

> Versãoes

[![Latest Stable Version](https://poser.pugx.org/moip/moip-sdk-php/v/stable)](https://packagist.org/packages/moip/moip-sdk-php)
[![Latest Unstable Version](https://poser.pugx.org/moip/moip-sdk-php/v/unstable)](https://packagist.org/packages/moip/moip-sdk-php)

> Licença

[![License](https://poser.pugx.org/moip/moip-sdk-php/license)](https://packagist.org/packages/moip/moip-sdk-php)

---

## Dependências

* PHP >= 5.4

## Instalação

#### Usando composer

Adicione o trecho abaixo em seu arquivo `composer.json`:

    {
        "require" : {
            "moip/moip-sdk-php" : "1.0.x-dev"
        }
    }
    
Execute:

    composer install
    
## Configurando sua autenticação
```php
require 'vendor/autoload.php';

use Moip\Moip;
use Moip\MoipBasicAuth;

$endpoint = 'test.moip.com.br';
$token = '01010101010101010101010101010101';
$key = 'ABABABABABABABABABABABABABABABABABABABAB';

$moip = new Moip(new MoipBasicAuth($token, $key), $endpoint);
```

## Criando um pedido
Nesse exemplo será criado um pedido com dados do cliente.

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
                                          '01234567', 8);
```
```php
$order = $moip->orders()->setOwnId(uniqid())
                        ->addItem('Bicicleta Specialized Tarmac 26 Shimano Alivio', 1, 'uma linda bicicleta', 10000)
                        ->setCustomer($customer)
                        ->create();
```

## Criando o pagamento
Após criar o pedido basta criar um pagamento nesse pedido.

```php
$payment = $order->payments()->setCreditCard(12, 15, '4073020000000002', '123', $customer)
                             ->execute();
```
## Documentação

[Documentação oficial](https://moip.com.br/referencia-api/)

## Licença

[The MIT License](https://github.com/moip/php-sdk/blob/master/LICENSE)
