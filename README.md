# Moip v2 PHP client SDK
O jeito mais simples e rápido de integrar o Moip a sua aplicação PHP

---

## Dependências

* PHP >= 5.4

## Instalação

#### Usando composer

Adicione o trecho abaixo em seu arquivo `composer.json`:

    {
        "require" : {
            "moip/sdk-php" : "dev-master"
        }
    }
    
Execute:

    composer install
    
## Configurando sua autenticação
```php
require 'vendor/autoload.php';

use Moip\Moip;
use Moip\MoipBasicAuth;

$moip = new Moip(new MoipBasicAuth('api-token', 'api-key'));
```

## Criando um pedido
Nesse exemplo será criado um pedido com dados do cliente.

```php
$customer = $moip->customer()->setOwnId('meu_id_de_cliente')
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
$order = $moip->orders()->setOwnId('id_proprio')
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
