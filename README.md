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

    composer require moip/moip-sdk-php ~1
    
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
