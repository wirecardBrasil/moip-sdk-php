<img src="https://gist.githubusercontent.com/joaolucasl/00f53024cecf16410d5c3212aae92c17/raw/1789a2131ee389aeb44e3a9d5333f59cfeebc089/moip-icon.png" align="right" />

# MoIP v2 PHP client SDK
> O jeito mais simples e rápido de integrar o Moip a sua aplicação PHP

> Estado atual do sdk

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/moip/moip-sdk-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/moip/moip-sdk-php/?branch=master)
[![Code Climate](https://codeclimate.com/github/moip/moip-sdk-php/badges/gpa.svg)](https://codeclimate.com/github/moip/moip-sdk-php)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/9e877cf78f844b9a9e40cec175c3aa5a)](https://www.codacy.com/app/jeancesargarcia/moip-sdk-php?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=moip/moip-sdk-php&amp;utm_campaign=Badge_Grade)
[![StyleCI](https://styleci.io/repos/19941899/shield)](https://styleci.io/repos/19941899)
[![Build Status](https://travis-ci.org/moip/moip-sdk-php.svg?branch=master)](https://travis-ci.org/moip/moip-sdk-php)

> Estatísticas

[![Total Downloads](https://poser.pugx.org/moip/moip-sdk-php/downloads)](https://packagist.org/packages/moip/moip-sdk-php)
[![Monthly Downloads](https://poser.pugx.org/moip/moip-sdk-php/d/monthly)](https://packagist.org/packages/moip/moip-sdk-php)

> Versões

[![Latest Stable Version](https://poser.pugx.org/moip/moip-sdk-php/v/stable)](https://packagist.org/packages/moip/moip-sdk-php)
[![Latest Unstable Version](https://poser.pugx.org/moip/moip-sdk-php/v/unstable)](https://packagist.org/packages/moip/moip-sdk-php)

---

**Índice** 

- [Instalação](#instalação)
- [Configurando a autenticação](#configurando-a-autenticação)
  - [Por BasicAuth](#por-basic-auth)
  - [Por OAuth](#por-oauth)
- [Exemplos de Uso](#clientes):
  - [Clientes](#clientes)
    - [Criação](#criando-um-comprador)
    - [Consulta](#consultando-os-dados-de-um-comprador)
    - [Adicionar cartão de crédito](#adicionar-cartão-de-crédito)
    - [Deletar cartão de crédito](#deletar-cartão-de-crédito)
  - [Pedidos](#pedidos)
    - [Criação](#criando-um-pedido-com-o-comprador-que-acabamos-de-criar)
    - [Consulta](#consultando-um-pedido)
  - [Pagamentos](#pagamentos)
    - [Cartão de Crédito](#cartão-de-crédito)
      - [Com Hash](#com-hash)
      - [Com Dados do Cartão](#com-dados-do-cartão)
    - [Com Boleto](#criando-um-pagamento-com-boleto)
  - [Reembolsos](#reembolsos)
    - [Cartão de crédito](#cartão-de-crédito-1)
      - [Valor Total](#valor-total)
      - [Valor Parcial](#valor-parcial)
    - [Conta Bancária](#conta-bancária)
      - [Valor Total](#valor-total-1)
      - [Valor Parcial](#valor-parcial-1)
    - [Consulta](#consulta-2)
  - [Multipedidos](#multipedidos)
    - [Criação](#criando-um-multipedido)
    - [Consulta](#consultando-um-multipedido)
  - [Multipagamentos](#multipagamentos)
    - [Criação](#criando-um-multipagamento)
  - [Conta Moip](#conta-moip)
    - [Criação](#criação)
    - [Consulta](#consulta-3)
    - [Verifica se usuário já possui Conta Moip](#verifica-se-usuário-já-possui-conta-moip)
  - [Webhooks](#webhooks) 
    - [Consulta](#consulta-4)
- [Packages](#packages)
- [Documentação](#documentação)
- [Testes](#testes)
- [Licença](#licença)

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

## Configurando a autenticação

### Por Basic Auth
```php
require 'vendor/autoload.php';

use Moip\Moip;
use Moip\Auth\BasicAuth;

$token = '01010101010101010101010101010101';
$key = 'ABABABABABABABABABABABABABABABABABABABAB';

$moip = new Moip(new BasicAuth($token, $key), Moip::ENDPOINT_SANDBOX);
```

### Por OAuth
```php
require 'vendor/autoload.php';

use Moip\Moip;
use Moip\Auth\OAuth;

$access_token = '33031e2aad484051b89030487e59d133_v2';
$moip = new Moip(new OAuth($access_token), Moip::ENDPOINT_SANDBOX);
```

## Clientes
### Criando um comprador
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

### Consultando os dados de um comprador
```php
try {
    $customer_id = 'CUS-Q3BL0CAJ2G33';
    $customer = $moip->customers()->get($customer_id);
    print_r($customer);
} catch (Exception $e) {
    printf($e->__toString());
}
```

### Adicionar cartão de crédito
```php
try {
    $customer = $moip->customers()->creditCard()
        ->setExpirationMonth('05')
        ->setExpirationYear(2018)
        ->setNumber('4012001037141112')
        ->setCVC('123')
        ->setFullName('Jose Portador da Silva')
        ->setBirthDate('1988-12-30')
        ->setTaxDocument('CPF', '33333333333')
        ->setPhone('55','11','66778899')
        ->create(CUSTOMER_ID);
} catch (Exception $e) {
    printf($e->__toString());
}
```

### Deletar cartão de crédito
```php
try {
    $moip->customers()->creditCard()->delete(CREDIT_CARD_ID);
} catch (Exception $e) {
    printf($e->__toString());
}
```

## Pedidos
### Criando um pedido com o comprador que acabamos de criar
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

### Consultando um pedido
```php
try {
    $order_id = 'ORD-KZCH1S1ORAH23';
    $order = $moip->orders()->get($order_id);
    print_r($order);
} catch (Exception $e) {
    printf($e->__toString());
}
```

## Pagamentos
### Cartão de crédito
Após criar o pedido basta criar um pagamento nesse pedido.

#### Com hash
> Para mais detalhes sobre a geração de hash com os dados do cartão [consulte a documentação.](https://dev.moip.com.br/docs/criptografia-de-cartao)

```php
try {
    $hash = 'i1naupwpTLrCSXDnigLLTlOgtm+xBWo6iX54V/hSyfBeFv3rvqa1VyQ8/pqWB2JRQX2GhzfGppXFPCmd/zcmMyDSpdnf1GxHQHmVemxu4AZeNxs+TUAbFWsqEWBa6s95N+O4CsErzemYZHDhsjEgJDe17EX9MqgbN3RFzRmZpJqRvqKXw9abze8hZfEuUJjC6ysnKOYkzDBEyQibvGJjCv3T/0Lz9zFruSrWBw+NxWXNZjXSY0KF8MKmW2Gx1XX1znt7K9bYNfhA/QO+oD+v42hxIeyzneeRcOJ/EXLEmWUsHDokevOkBeyeN4nfnET/BatcDmv8dpGXrTPEoxmmGQ==';
    $payment = $order->payments()
        ->setCreditCardHash($hash, $customer)
	->setInstallmentCount(3)
	->setStatementDescriptor('teste de pag')
	->setDelayCapture(false)
	->execute();
	print_r($payment);
} catch (Exception $e) {
    printf($e->__toString());
}
```

#### Com dados do cartão
> Esse método requer certificação PCI. [Consulte a documentação.](https://documentao-moip.readme.io/v2.0/reference#criar-pagamento)
```php
try {
    $payment = $order->payments()->setCreditCard(12, 21, '4073020000000002', '123', $customer)
        ->execute();

    print_r($payment);
} catch (Exception $e) {
    printf($e->__toString());
}
```


### Criando um pagamento com boleto
```php
$logo_uri = 'https://cdn.moip.com.br/wp-content/uploads/2016/05/02163352/logo-moip.png';
$expiration_date = new DateTime();
$instruction_lines = ['INSTRUÇÃO 1', 'INSTRUÇÃO 2', 'INSTRUÇÃO 3'];
try {
    $payment = $order->payments()  
        ->setBoleto($expiration_date, $logo_uri, $instruction_lines)
        ->execute();
    print_r($payment);
} catch (Exception $e) {
    printf($e->__toString());
}
```

## Reembolsos

Para fazer reembolsos é necessário ter o objeto **```Payment```** do pagamento que você deseja reembolsar.

### Cartão de crédito
#### Valor Total
```php
try {
    $refund = $payment->refunds()->creditCardFull();
    print_r($refund);
} catch (Exception $e) {
    printf($e->__toString());
}
```

#### Valor Parcial
```php
try {
    $amount = 30000;
    $refund = $payment->refunds()->creditCardPartial($amount);
    print_r($refund);
} catch (Exception $e) {
    printf($e->__toString());
}
```

### Conta bancária
#### Valor Total
```php
try {
    $type = 'CHECKING';
    $bank_number = '001';
    $agency_number = 4444444;
    $agency_check_number = 2;
    $account_number = 1234;
    $account_check_number = 4;
    $refund = $payment->refunds()
	->bankAccountFull($type, $bank_number, $agency_number, $agency_check_number, $account_number, $account_check_number, $customer);
    print_r($refund);
} catch (Exception $e) {
    printf($e->__toString());
}
```

#### Valor Parcial
```php
try {
    $amount = 30000;
    $type = 'SAVING';
    $bank_number = '001';
    $agency_number = 4444444;
    $agency_check_number = 2;
    $account_number = 1234;
    $account_check_number = 4;
    $refund = $payment->refunds()
	->bankAccountPartial($amount, $type, $bank_number, $agency_number, $agency_check_number, $account_number, $account_check_number, $customer);
    print_r($refund);
} catch (Exception $e) {
    printf($e->__toString());
}
```

## Multipedidos

### Criando um multipedido
```php
try {
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
    print_r($multiorder);
} catch (Exception $e) {
    printf($e->__toString());
}
```

### Consultando um multipedido
```php
try {
    $multiorder_id = 'ORD-KZCH1S1ORAH25';
    $multiorder = $moip->multiorders()->get($multiorder_id);
    print_r($multiorder);
} catch (Exception $e) {
    printf($e->__toString());
}
```

## Multipagamentos

### Criando um multipagamento 
```php
try {
    $hash = 'i1naupwpTLrCSXDnigLLTlOgtm+xBWo6iX54V/hSyfBeFv3rvqa1VyQ8/pqWB2JRQX2GhzfGppXFPCmd/zcmMyDSpdnf1GxHQHmVemxu4AZeNxs+TUAbFWsqEWBa6s95N+O4CsErzemYZHDhsjEgJDe17EX9MqgbN3RFzRmZpJqRvqKXw9abze8hZfEuUJjC6ysnKOYkzDBEyQibvGJjCv3T/0Lz9zFruSrWBw+NxWXNZjXSY0KF8MKmW2Gx1XX1znt7K9bYNfhA/QO+oD+v42hxIeyzneeRcOJ/EXLEmWUsHDokevOkBeyeN4nfnET/BatcDmv8dpGXrTPEoxmmGQ==';
    $payment = $multiorder->multipayments()
        ->setCreditCardHash($hash, $customer)
        ->setInstallmentCount(3)
        ->setStatementDescriptor('teste de pag')
        ->setDelayCapture(false)
        ->execute();
    print_r($payment);
} catch (Exception $e) {
    printf($e->__toString());
}
```

## Conta Moip

### Criação
```php
try {
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
    print_r($account);
} catch (Exception $e) {
    printf($e->__toString());
}
```

### Consulta
```php
try {	
	$account = $moip->accounts()->get(ACCOUNT_ID);
	print_r($account);
} catch (Exception $e) {
	printf($e->__toString());
}
```

### Verifica se usuário já possui conta Moip
```php
// retorna verdadeiro se já possui e falso caso não possuir conta Moip
$moip->accounts()->checkAccountExists(CPF);
```

## Webhooks
> O PHP, por padrão, está preparado para receber apenas alguns tipos de `content-type` (`application/x-www-form-urlencoded` e `multipart/form-data`). A plataforma do Moip, no entanto, envia dados no formato JSON, o qual a linguagem não está preparada para receber por padrão. 
Para receber e acessar os dados enviados pelo Moip, você precisa adicionar o seguinte código ao seu arquivo que receberá os webhooks:

```php
// Pega o RAW data da requisição
$json = file_get_contents('php://input');
// Converte os dados recebidos
$response = json_decode($json, true);
```

### Consulta

#### Sem paginação ou filtro por resource/evento
```php
$moip->webhooks()->get();
```

#### Com paginação e filtros por resource/evento
```php
$moip->webhooks()->get(new Pagination(10, 0), 'ORD-ID', 'ORDER.PAID');
```

## Documentação

[Documentação oficial](https://documentao-moip.readme.io/v2.0/reference)

## Testes
Por padrão os testes não fazem nenhuma requisição para a API do Moip. É possível rodar os testes contra 
o ambiente de [Sandbox](https://conta-sandbox.moip.com.br/) do moip, para isso basta setar a variável de ambiente:
 - `MOIP_ACCESS_TOKEN` Token de autenticação do seu aplicativo Moip.

[Como registrar seu aplicativo Moip](https://dev.moip.com.br/docs/moip-connect#section--registrando-seu-aplicativo-)

Para registrar seu aplicativo Moip você precisará de suas chaves de acesso.
[Como obter suas chaves de acesso](http://dev.moip.com.br/docs/#obter-chaves-de-acesso).

Exemplo:
```shell
export MOIP_ACCESS_TOKEN=76926cb0305243c8adc79aad54321ec1_v2
vendor/bin/phpunit -c .
```

## Licença

[The MIT License](https://github.com/moip/php-sdk/blob/master/LICENSE)
