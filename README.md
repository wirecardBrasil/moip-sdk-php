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
      - [Pedido Específico](#pedido-específico)
      - [Todos os Pedidos](#todos-os-pedidos)
        - [Sem Filtro](#sem-filtro)
        - [Com Filtros](#com-filtros)
        - [Com Paginação](#com-paginação)
        - [Consulta Valor Específico](#consulta-valor-específico)
  - [Pagamentos](#pagamentos)
    - [Criação](#criação)
      - [Cartão de Crédito](#cartão-de-crédito)
        - [Inserir dados do portador](#inserir-dados-do-portador)
        - [Com Hash](#com-hash)
        - [Com Dados do Cartão](#com-dados-do-cartão)
      - [Com Boleto](#criando-um-pagamento-com-boleto)
      - [Com Débito Bancário](#criando-um-pagamento-com-débito-bancário)
    - [Consulta](#consulta)
    - [Capturar pagamento pré-autorizado](#capturar-pagamento-pré-autorizado)
    - [Cancelar pagamento pré-autorizado](#cancelar-pagamento-pré-autorizado)
  - [Reembolsos](#reembolsos)
    - [Cartão de crédito](#cartão-de-crédito-1)
      - [Valor Total](#valor-total)
      - [Valor Parcial](#valor-parcial)
    - [Conta bancária](#conta-bancária)
      - [Valor Total](#valor-total-1)
      - [Valor Parcial](#valor-parcial-1)
    - [Consulta](#consultar-reembolso)
  - [OAuth (Moip Connect)](#oauth-moip-connect)
    - [Solicitar permissões de acesso ao usuário](#solicitar-permissões-de-acesso-ao-usuário)
    - [Gerando access token OAuth](#gerando-access-token-oauth)
  - [Multipedidos](#multipedidos)
    - [Criação](#criando-um-multipedido)
    - [Consulta](#consultando-um-multipedido)
  - [Multipagamentos](#multipagamentos)
    - [Criação](#criando-um-multipagamento)
    - [Consulta](#consulta-2)
  - [Conta Moip](#conta-moip)
    - [Criação](#criação-1)
    - [Consulta](#consulta-3)
    - [Consulta](#consulta-1)
    - [Verifica se usuário já possui Conta Moip](#verifica-se-usuário-já-possui-conta-moip)
    - [Obter chave pública de uma Conta Moip](#obter-chave-pública-de-uma-conta-moip)
  - [Saldo Moip](#saldo-moip)
    - [Consulta](#consultar-saldos)
  - [Conta Bancária](#conta-bancária-1)
    - [Criação](#criar-conta-bancária)
    - [Consulta](#consultar-conta-bancária)
    - [Listagem](#listar-contas-bancárias)
    - [Atualização](#atualizar-conta-bancária)
    - [Deletar](#deletar-conta-bancária)
  - [Transferência](#transferência)
    - [Criação](#criando/executando-uma-transferência)
    - [Consulta](#consultar-transferência)
    - [Listagem](#listar-transferências)
    - [Reversão](#reverter-transferência)
  - [Preferências de Notificação](#preferências-de-notificação)
    -  [Criação](#criação-2)
    -  [Consulta](#consulta-5)
    -  [Exclusão](#exclusão)
    -  [Listagem](#listagem)
  - [Webhooks](#webhooks)
    - [Consulta](#consulta-6)
- [Packages](#packages)
- [Tratamento de exceções](#tratamento-de-exceções)
- [Documentação](#documentação)
- [Testes](#testes)
- [Licença](#licença)
- [Comunidade Slack](#comunidade-slack-) [![Slack](https://user-images.githubusercontent.com/4432322/37355972-ba0e9f32-26c3-11e8-93d3-39917eb24109.png)](https://slackin-cqtchmfquq.now.sh)

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

### Consultando os dados de um comprador
```php
$customer = $moip->customers()->get('CUS-Q3BL0CAJ2G33');
print_r($customer);
```

### Adicionar cartão de crédito
```php
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
print_r($customer);
```

### Deletar cartão de crédito
```php
$moip->customers()->creditCard()->delete(CREDIT_CARD_ID);
```

## Pedidos
### Criando um pedido com o comprador que acabamos de criar
Nesse exemplo com vários produtos e ainda especificando valor de frete, valor adicional e ainda valor de desconto.

```php
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
```

### Consultando um pedido
#### Pedido específico
```php
$order = $moip->orders()->get('ORD-KZCH1S1ORAH23');
print_r($order);
```

#### Todos os Pedidos
##### Sem Filtro
```php
$orders = $this->moip->orders()->getList();
```

##### Com Filtros
```php
$filters = new Filters();
$filters->greaterThanOrEqual(OrdersList::CREATED_AT, '2017-08-17');
$filters->in(OrdersList::PAYMENT_METHOD, ['BOLETO', 'DEBIT_CARD']);
$filters->lessThan(OrdersList::VALUE, 100000);

$orders = $this->moip->orders()->getList(null, $filters);
```

##### Com Paginação
```php
$orders = $this->moip->orders()->getList(new Pagination(10,0));
```

##### Consulta Valor Específico
```php
$orders = $this->moip->orders()->getList(null, null, 'josé silva');
```

> Também é possível usar paginação, filtros e consulta de valor específico juntos

```php
$filters = new Filters();
$filters->greaterThanOrEqual(OrdersList::CREATED_AT, '2017-08-17');
$filters->lessThan(OrdersList::VALUE, 100000);

$orders = $this->moip->orders()->getList(new Pagination(10,0), $filters, 'josé silva');
```

## Pagamentos

### Criação
#### Cartão de crédito
Após criar o pedido basta criar um pagamento nesse pedido.

##### Inserir dados do portador
Para realizar o pagamento, via cartão de crédito, utilizando o cartão de um terceiro (quando o cliente não é o portador do cartão que será utilizado), é necessário que estes dados sejam diferenciados e informados corretamente, para cada etapa do fluxo.

```php
$holder = $moip->holders()->setFullname('Jose Silva')
    ->setBirthDate("1990-10-10")
    ->setTaxDocument('22222222222', 'CPF')
    ->setPhone(11, 66778899, 55)
    ->setAddress('BILLING', 'Avenida Faria Lima', '2927', 'Itaim', 'Sao Paulo', 'SP', '01234000', 'Apt 101');
```
##### Com hash
> Para mais detalhes sobre a geração de hash com os dados do cartão [consulte a documentação.](https://dev.moip.com.br/docs/criptografia-de-cartao)

```php
$hash = 'i1naupwpTLrCSXDnigLLTlOgtm+xBWo6iX54V/hSyfBeFv3rvqa1VyQ8/pqWB2JRQX2GhzfGppXFPCmd/zcmMyDSpdnf1GxHQHmVemxu4AZeNxs+TUAbFWsqEWBa6s95N+O4CsErzemYZHDhsjEgJDe17EX9MqgbN3RFzRmZpJqRvqKXw9abze8hZfEuUJjC6ysnKOYkzDBEyQibvGJjCv3T/0Lz9zFruSrWBw+NxWXNZjXSY0KF8MKmW2Gx1XX1znt7K9bYNfhA/QO+oD+v42hxIeyzneeRcOJ/EXLEmWUsHDokevOkBeyeN4nfnET/BatcDmv8dpGXrTPEoxmmGQ==';
$payment = $order->payments()
    ->setCreditCardHash($hash, $holder)
    ->setInstallmentCount(3)
    ->setStatementDescriptor('teste de pag')
    ->execute();
print_r($payment);
```

##### Com dados do cartão
> Esse método requer certificação PCI. [Consulte a documentação.](https://documentao-moip.readme.io/v2.0/reference#criar-pagamento)
```php
$payment = $order->payments()->setCreditCard(12, 21, '4073020000000002', '123', $holder)
    ->execute();
print_r($payment);
```

#### Criando um pagamento com boleto
```php
$logo_uri = 'https://cdn.moip.com.br/wp-content/uploads/2016/05/02163352/logo-moip.png';
$expiration_date = new DateTime();
$instruction_lines = ['INSTRUÇÃO 1', 'INSTRUÇÃO 2', 'INSTRUÇÃO 3'];
$payment = $order->payments()  
    ->setBoleto($expiration_date, $logo_uri, $instruction_lines)
    ->execute();
print_r($payment);
```

#### Criando um pagamento com Débito Bancário
No pagamento por débito bancário online também são enviados apenas 3 parâmetros:

- URL do logo que você deseja que apareça, representada abaixo com a variável: $return_uri;
- Data de vencimento, representada pela variável $expiration_date;
- Número do banco representado pela variável $bank_number (atualmente único valor possível é `341`, referente ao Banco Itaú).
```php
$bank_number = '341';
$return_uri = 'https://moip.com.br';
$expiration_date = new DateTime();
$payment = $order->payments()                    
    ->setOnlineBankDebit($bank_number, $expiration_date, $return_uri)
    ->execute();
print_r($payment);
```

### Consulta
```php
$payment = $moip->payments()->get('PAYMENT-ID');
print_r($payment);
```

### Capturar pagamento pré-autorizado
```php
$captured_payment = $payment->capture();
print_r($captured_payment);
```

### Cancelar pagamento pré-autorizado

> O método `avoid` usado para cancelamento de pagamentos pré-autorizados foi substituído por `cancel`.

```php
$payment = $payment->cancel();
print_r($payment);
```

## Reembolsos

Para fazer reembolsos é necessário ter o objeto **```Payment```** do pagamento que você deseja reembolsar ou passar apenas o ID do pagamento.

### Cartão de crédito
#### Valor Total

##### Com o objeto
```php
$refund = $payment->refunds()->creditCardFull();
print_r($refund);
```

#### Valor Parcial

##### Com o objeto
```php
$refund = $payment->refunds()->creditCardPartial(30000);
print_r($refund);
```

### Conta bancária
#### Valor Total

##### Com o objeto
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
print_r($refund);
```

#### Valor Parcial

##### Com o objeto
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
print_r($refund);
```

### Consultar reembolso
```php
$refund = $payment->refunds()->get($refund_id);
```

## OAuth (Moip Connect)
### Solicitar permissões de acesso ao usuário
Para solicitar as permissões você deverá invocar o método getAuthUrl (que monta a URL) e redirecionar o usuário para a URL gerada. O usuário deverá conceder a permissão e então ele será redirecionado para a URL determinada pelo seu App e passada como atributo para o objeto Connect.

A URL passada como atributo deve ser exatamente a mesma que foi cadastrada na criação do APP, caso haja alguma divergência o usuário não será redirecionado corretamente.

Com a permissão concedida, você receberá um `code` que lhe permitirá gerar o `accessToken` de autenticação e processar requisições envolvendo outro usuário.

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

### Gerando access token OAuth
Abaixo usaremos o método authorize para gerar o access token OAuth. Note que é necessário instanciar o objeto Connect e passar os parâmetros como no exemplo abaixo.

Usamos a variável `$code` para enviar o `code` recebido pela permissão do usuário e inserimos no objeto com o método `setCode`.

A URL passada como atributo deve ser exatamente a mesma que foi cadastrada na criação do APP, caso haja alguma divergência não será possível recuperar o accessToken.

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
print_r($auth);
```

## Multipedidos

### Criando um multipedido
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
print_r($multiorder);
```

### Consultando um multipedido
```php
$multiorder_id = 'ORD-KZCH1S1ORAH25';
$multiorder = $moip->multiorders()->get($multiorder_id);
print_r($multiorder);
```

## Multipagamentos

### Criando um multipagamento
```php
$hash = 'i1naupwpTLrCSXDnigLLTlOgtm+xBWo6iX54V/hSyfBeFv3rvqa1VyQ8/pqWB2JRQX2GhzfGppXFPCmd/zcmMyDSpdnf1GxHQHmVemxu4AZeNxs+TUAbFWsqEWBa6s95N+O4CsErzemYZHDhsjEgJDe17EX9MqgbN3RFzRmZpJqRvqKXw9abze8hZfEuUJjC6ysnKOYkzDBEyQibvGJjCv3T/0Lz9zFruSrWBw+NxWXNZjXSY0KF8MKmW2Gx1XX1znt7K9bYNfhA/QO+oD+v42hxIeyzneeRcOJ/EXLEmWUsHDokevOkBeyeN4nfnET/BatcDmv8dpGXrTPEoxmmGQ==';
$payment = $multiorder->multipayments()
    ->setCreditCardHash($hash, $customer)
    ->setInstallmentCount(3)
    ->setStatementDescriptor('teste de pag')
    ->execute();
print_r($payment);
```

### Consulta
```php
$payment = $moip->payments()->get('MULTIPAYMENT-ID');
print_r($payment);
```

## Conta Moip

### Criação
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
print_r($account);
```

### Consulta
```php
$account = $moip->accounts()->get(ACCOUNT_ID);
print_r($account);
```

### Verifica se usuário já possui conta Moip
```php
// retorna verdadeiro se já possui e falso caso não possuir conta Moip
$moip->accounts()->checkAccountExists(CPF);
```

### Obter chave pública de uma Conta Moip
```php
$keys = $moip->keys()->get();
print_r($keys);
```

## Saldo Moip
O Saldo é a composição de valores atuais disponíveis, indisponíveis (bloqueados) e futuros de uma determinada **Conta Moip**.

> Esta API está na versão 2.1, contendo o _header_ **Accept**, com o valor `application/json;version=2.1`.
### Consultar saldos
```php
$balances = $moip->balances()->get();
```

_Requer autenticação `OAuth`._

## Conta Bancária
A Conta bancária é o domicílio bancário de uma determinada Conta Moip. Esta API permite a criação, a consulta e a alteração dos dados de uma Conta Bancária.

### Criar Conta Bancária
```php
$bank_account = $moip->bankaccount()
    ->setBankNumber('237')
    ->setAgencyNumber('12345')
    ->setAgencyCheckNumber('0')
    ->setAccountNumber('12345678')
    ->setAccountCheckNumber('7')
    ->setType('CHECKING')
    ->setHolder('Demo Moip', '622.134.533-22', 'CPF')
    ->create($moip_account_id);
```

### Consultar Conta Bancária
```php
$bank_account = $moip->bankaccount()->get($bank_account_id);
```

### Listar Contas Bancárias
```php
$bank_accounts = $moip->bankaccount()->getList($account_id)->getBankAccounts();
```

### Atualizar Conta Bancária
```php
$bank_account = $moip->bankaccount()
    ->setAccountCheckNumber('8')
    ->update($bank_account_id);
```

### Deletar Conta Bancária
```php
$moip->bankaccount()->delete($bank_account_id);
```

## Preferências de notificação

### Criação
```php
$notification = $moip->notifications()->addEvent('ORDER.*')
    ->addEvent('PAYMENT.AUTHORIZED')
    ->setTarget('http://requestb.in/1dhjesw1')
    ->create();
print_r($notification);
```

### Consulta
```php
$notification = $this->moip->notifications()->get('NPR-N6QZE3223P98');
print_r($notification);
```

### Exclusão
```php
$notification = $moip->notifications()->delete('NOTIFICATION-ID');
print_r($notification);
```

### Listagem
```php
$notifications = $moip->notifications()->getList();
print_r($notifications);
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

## Transferência
A Transferência é uma movimentação de fundos entre uma Conta Moip e outra conta de pagamento (pode ser uma Conta bancária ou uma determinada Conta Moip).

### Criando/executando uma transferência
#### Por conta bancária

```php
$amount = 500;
$bank_number = '001';
$agency_number = '1111';
$agency_check_number = '2';
$account_number = '9999';
$account_check_number = '8';
$holder_name = 'Nome do Portador';
$tax_document = '22222222222';

$transfer = $moip->transfers()
    ->setTransfers($amount, $bank_number, $agency_number, $agency_check_number, $account_number, $account_check_number)
    ->setHolder($holder_name, $tax_document)
    ->execute();

print_r($transfer);
```

Para realizar uma transferência utilizando uma conta bancária já cadastrada:
```php
$transfer = $moip->transfers()
    ->setTransfersToBankAccount($amount, $bank_account_id)
    ->execute();
```

### Consultar transferência
```php
$transfer_id = 'TRA-28HRLYNLMUFH';
$transfer = $moip->transfers()->get($transfer_id);

print_r($transfer);
```

### Listar transferências
#### Sem paginação
```php
$transfers = $moip->transfers()->getList();
```

#### Com paginação
```php
$transfers = $moip->transfers()->getList(new Pagination(10,0));
```

### Reverter transferência
```php
$transfer_id = 'TRA-28HRLYNLMUFH';

$transfer = $moip->transfers()->revert($transfer_id);
```

## Tratamento de Exceções

Quando ocorre algum erro na API, é lançada a exceção `UnexpectedException` para erros inesperados, `UnautorizedException` para erros de autenticação e `ValidationException`
para erros de validação.

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

## Comunidade Slack [![Slack](https://user-images.githubusercontent.com/4432322/37355972-ba0e9f32-26c3-11e8-93d3-39917eb24109.png)](https://slackin-cqtchmfquq.now.sh)


Tem dúvidas? Fale com a gente no [Slack](https://slackin-cqtchmfquq.now.sh/)! 