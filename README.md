# Moip PHP client SDK
The faster and easier way to start your integration with the Moip API using PHP

---

## Dependencies

* PHP >= 5.4

## Installation

#### Using composer

Add to your composer.json file:

    {
        "require" : {
            "moip/sdk-php" : "dev-master"
        }
    }
    
Then run:

    composer install
    
## Quick start

    <?php
    require 'vendor/autoload.php';

    use Moip\Moip;
    use Moip\MoipBasicAuth;

    $moip = new Moip(new MoipBasicAuth('api-token', 'api-key'));
    
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

    $order = $moip->orders()->setOwnId('id_proprio')
                            ->addItem('Bicicleta Specialized Tarmac 26 Shimano Alivio', 1, 'uma linda bicicleta', 10000)
                            ->setCustomer($customer)
                            ->create();
                            
    $payment = $order->payments()->setCreditCard(12, 15, '4073020000000002', '123', $customer)
                                 ->execute();

## Documentation

[Official Documentation](#)

## License

[The MIT License](https://github.com/moip/php-sdk/blob/master/LICENSE)