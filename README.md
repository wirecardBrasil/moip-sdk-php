# MoIP v2 PHP client SDK
O jeito mais simples e rápido de integrar o Moip a sua aplicação PHP

> Estado atual do sdk

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/moip/moip-sdk-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/moip/moip-sdk-php/?branch=master)
[![Code Climate](https://codeclimate.com/github/moip/moip-sdk-php/badges/gpa.svg)](https://codeclimate.com/github/moip/moip-sdk-php)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/9e877cf78f844b9a9e40cec175c3aa5a)](https://www.codacy.com/app/jeancesargarcia/moip-sdk-php?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=moip/moip-sdk-php&amp;utm_campaign=Badge_Grade)
[![StyleCI](https://styleci.io/repos/19941899/shield)](https://styleci.io/repos/19941899)
[![Build Status](https://travis-ci.org/moip/moip-sdk-php.svg?branch=master)](https://travis-ci.org/moip/moip-sdk-php)
[![Circleci Status](https://circleci.com/gh/moip/moip-sdk-php/tree/analysis-qg67K6.svg?style=shield)](#)

> Informações

[![Dependency Status](https://gemnasium.com/moip/moip-sdk-php.svg)](https://gemnasium.com/moip/moip-sdk-php)
[![Github Issues](http://githubbadges.herokuapp.com/moip/moip-sdk-php/issues.svg?style=square)](https://github.com/moip/moip-sdk-php/issues)
[![Github Pulls](http://githubbadges.herokuapp.com/moip/moip-sdk-php/pulls.svg?style=square)](https://github.com/moip/moip-sdk-php/issues)

> Estatísticas

[![Total Downloads](https://poser.pugx.org/moip/moip-sdk-php/downloads)](https://packagist.org/packages/moip/moip-sdk-php)
[![Monthly Downloads](https://poser.pugx.org/moip/moip-sdk-php/d/monthly)](https://packagist.org/packages/moip/moip-sdk-php)
[![Daily Downloads](https://poser.pugx.org/moip/moip-sdk-php/d/daily)](https://packagist.org/packages/moip/moip-sdk-php)
![Repo Size](https://reposs.herokuapp.com/?path=Moip/moip-sdk-php)

> Versões

[![Latest Stable Version](https://poser.pugx.org/moip/moip-sdk-php/v/stable)](https://packagist.org/packages/moip/moip-sdk-php)
[![Latest Unstable Version](https://poser.pugx.org/moip/moip-sdk-php/v/unstable)](https://packagist.org/packages/moip/moip-sdk-php)

> Licença

[![License](https://poser.pugx.org/moip/moip-sdk-php/license)](https://packagist.org/packages/moip/moip-sdk-php)

---
## Packages

* [Laravel 5.x](https://github.com/artesaos/moip)
* [Symfony 2 ou 3](https://github.com/leonnleite/moip-bundle)
* [Laravel 4.x (MoIP API v1)](https://github.com/SOSTheBlack/moip) 

## Dependências
#### require
* PHP >= 5.5
* rmccue/requests >= 1.0

#### require-dev
* phpunit/phpunit ~ 4.0

## Instalação

Execute em seu shell:

    composer require moip/moip-sdk-php

## Configurando sua autenticação
```php
require 'vendor/autoload.php';

use Moip\Moip;
use Moip\Auth\BasicAuth;

$token = '01010101010101010101010101010101';
$key = 'ABABABABABABABABABABABABABABABABABABABAB';

$moip = new Moip(new BasicAuth($token, $key), Moip::ENDPOINT_SANDBOX);
```

## Criando um comprador
Nesse exemplo será criado um pedido com dados do cliente - Com endereço de entrega e de pagamento.
```php
try {
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
} catch (Exception $e) {
    printf($e->__toString());
}
```

## Criando um pedido com o comprador que acabamos de criar
Nesse exemplo com vários produtos e ainda especificando valor de frete, valor adicional e ainda valor de desconto.

```php
try {
    $order = $moip->orders()->setOwnId(uniqid())
        ->addItem("bicicleta 1",1, "sku1", 10000)
        ->addItem("bicicleta 2",1, "sku2", 11000)
        ->addItem("bicicleta 3",1, "sku3", 12000)
        ->addItem("bicicleta 4",1, "sku4", 13000)
        ->addItem("bicicleta 5",1, "sku5", 14000)
        ->addItem("bicicleta 6",1, "sku6", 15000)
        ->addItem("bicicleta 7",1, "sku7", 16000)
        ->addItem("bicicleta 8",1, "sku8", 17000)
        ->addItem("bicicleta 9",1, "sku9", 18000)
        ->addItem("bicicleta 10",1, "sku10", 19000)
        ->setShippingAmount(3000)->setAddition(1000)->setDiscount(5000)
        ->setCustomer($customer)
        ->create();

    print_r($order);
} catch (Exception $e) {
    printf($e->__toString());
}
```

## Criando o pagamento
Após criar o pedido basta criar um pagamento nesse pedido.
Nesse exemplo estamos pagando com Cartão de Crédito.

```php
try {
    $payment = $order->payments()->setCreditCard(12, 21, '4073020000000002', '123', $customer)
        ->execute();

    print_r($payment);
} catch (Exception $e) {
    printf($e->__toString());
}
```
## Documentação

[Documentação oficial](https://documentao-moip.readme.io/v2.0/reference)

## Testes
Por padrão os testes não fazem nenhuma requisição para a API do Moip. É possível rodar os testes contra 
o ambiente de [Sandbox](https://conta-sandbox.moip.com.br/) do moip, para isso basta setar as seguintes variáveis de ambiente:
 - `MOIP_TOKEN` Seu token de acesso a sandbox.
 - `MOIP_KEY` Sua chave de acesso a sandbox.

[Como obter suas chaves](http://dev.moip.com.br/docs/#obter-chaves-de-acesso).

Exemplo:
```shell
export MOIP_TOKEN=01010101010101010101010101010101
export MOIP_KEY=ABABABABABABABABABABABABABABABABABABABAB
vendor/bin/phpunit -c .
```

## Licença

[The MIT License](https://github.com/moip/php-sdk/blob/master/LICENSE)
