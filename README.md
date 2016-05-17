# Moip v2 PHP client SDK
O jeito mais simples e rápido de integrar o Moip a sua aplicação PHP

> Estado atual do sdk

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/moipv2/moip-sdk-php-v2/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/moipv2/moip-sdk-php-v2/?branch=master)
[![Code Climate](https://codeclimate.com/github/moipv2/moip-sdk-php-v2/badges/gpa.svg)](https://codeclimate.com/github/moipv2/moip-sdk-php-v2)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/9e877cf78f844b9a9e40cec175c3aa5a)](https://www.codacy.com/app/jeancesargarcia/moip-sdk-php?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=moipv2/moip-sdk-php-v2&amp;utm_campaign=Badge_Grade)
[![StyleCI](https://styleci.io/repos/19941899/shield)](https://styleci.io/repos/19941899)
[![Build Status](https://travis-ci.org/moipv2/moip-sdk-php-v2.svg?branch=master)](https://travis-ci.org/moipv2/moip-sdk-php-v2)

> Informações

[![Dependency Status](https://gemnasium.com/moipv2/moip-sdk-php-v2.svg)](https://gemnasium.com/moipv2/moip-sdk-php-v2)
[![Github Issues](http://githubbadges.herokuapp.com/moipv2/moip-sdk-php-v2/issues.svg?style=square)](https://github.com/moipv2/moip-sdk-php-v2/issues)
[![Github Pulls](http://githubbadges.herokuapp.com/moipv2/moip-sdk-php-v2/pulls.svg?style=square)](https://github.com/moipv2/moip-sdk-php-v2/issues)

> Estatísticas

[![Total Downloads](https://poser.pugx.org/moipv2/moip-sdk-php-v2/downloads)](https://packagist.org/packages/moipv2/moip-sdk-php-v2)
[![Monthly Downloads](https://poser.pugx.org/moipv2/moip-sdk-php-v2/d/monthly)](https://packagist.org/packages/moipv2/moip-sdk-php-v2)
[![Daily Downloads](https://poser.pugx.org/moipv2/moip-sdk-php-v2/d/daily)](https://packagist.org/packages/moipv2/moip-sdk-php-v2)
![Repo Size](https://reposs.herokuapp.com/?path=moipv2/moip-sdk-php-v2)

> Versões

[![Latest Stable Version](https://poser.pugx.org/moipv2/moip-sdk-php-v2/v/stable)](https://packagist.org/packages/moipv2/moip-sdk-php-v2)
[![Latest Unstable Version](https://poser.pugx.org/moipv2/moip-sdk-php-v2/v/unstable)](https://packagist.org/packages/moipv2/moip-sdk-php-v2)

> Licença

[![License](https://poser.pugx.org/moipv2/moip-sdk-php-v2/license)](https://packagist.org/packages/moipv2/moip-sdk-php-v2)

---

## Dependências

* PHP >= 5.5

## Instalação

#### Usando Terminal

Execute:

    composer require moipv2/moip-sdk-php-v2 ~1
    
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
            '01234567', 8);

    $order = $moip->orders()->setOwnId(uniqid())
        ->addItem('Bicicleta Specialized Tarmac 26 Shimano Alivio', 1, 'uma linda bicicleta', 10000)
        ->setCustomer($customer)
        ->create();

    print_r($order);
} catch (Exception $e) {
    printf("Code: %s - Description: %s - Path: %s", $error->getCode(), $error->getDescription(), $error->getPath());
}
```

## Criando o pagamento
Após criar o pedido basta criar um pagamento nesse pedido.

```php
try {
    $payment = $order->payments()->setCreditCard(12, 21, '4073020000000002', '123', $customer)
        ->execute();

    print_r($payment);
} catch (Exception $e) {
    printf("Code: %s - Description: %s - Path: %s", $error->getCode(), $error->getDescription(), $error->getPath());
}
```
## Documentação

[Documentação oficial](https://moip.com.br/referencia-api/)

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
