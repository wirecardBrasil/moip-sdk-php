<?php

namespace Moip\Tests;

use Moip\Auth\OAuth;
use Moip\Moip;
use Moip\Resource\Customer;
use Moip\Resource\Orders;
use PHPUnit_Framework_TestCase;
use Requests_Response;

/**
 * class TestCase.
 */
abstract class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Variables representing the test modes. On MOCK mode no http request will be made.
     * In SANDBOX mode HTTP requests will be made to the Moip::SANDBOX_ENDPOINT, the authentication information
     * is retrieved from the MOIP_TOKEN and MOIP_KEY environment variables.
     */
    const MOCK = 'mock';
    const SANDBOX = 'sandbox';

    /**
     * Intance of \Moip\Moip.
     *
     * @var \Moip\Moip
     * */
    protected $moip;

    /**
     * @var string current format for dates.
     */
    protected $date_format = 'Y-m-d';

    /**
     * @var string date used for testing.
     */
    protected $date_string = '1989-06-01';
    //todo: add the ability to use the play(https://github.com/rodrigosaito/mockwebserver-player) files from the jada sdk
    //the two responses below were based on the moip Java sdk's test files (https://github.com/moip/moip-sdk-java/)
    /**
     * @var string response from the client moip API.
     */
    protected $body_client = '{"id":"CUS-CFMKXQBZNJQQ","ownId":"meu_id_sandbox","fullname":"Jose Silva","email":"jose_silva0@email.com","phone":{"countryCode":"55","areaCode":"11","number":"66778899"},"birthDate":"1989-06-01","taxDocument":{"type":"CPF","number":"22222222222"},"shippingAddress":{"street":"Avenida Faria Lima","streetNumber":"2927","complement":"8","city":"Sao Paulo","state":"SP","country":"BRA","zipCode":"01234000"},"fundingInstruments":[],"createdAt":"2016-02-18T19:55:00.000-02","_links":{"self":{"href":"https://sandbox.moip.com.br/v2/customers/CUS-CFMKXQBZNJQQ"}}}';

    /**
     * @var string response from the order moip API.
     */
    protected $body_order = '{"id":"ORD-HG479ZEIB7LV","ownId":"meu_id_pedido","status":"CREATED","createdAt":"2016-02-19T12:24:55.849-02","updatedAt":"2016-02-19T12:24:55.849-02","amount":{"total":102470,"fees":0,"refunds":0,"liquid":0,"otherReceivers":0,"currency":"BRL","subtotals":{"shipping":1490,"addition":0,"discount":1000,"items":101980}},"items":[{"price":100000,"detail":"Mais info...","quantity":1,"product":"Nome do produto"},{"price":990,"detail":"Abacaxi de terra de areia","quantity":2,"product":"abacaxi"}],"customer":{"id":"CUS-7U5K9KWG8DBZ","ownId":"meu_id_saasdadadsnasdasddboxssssssssss","fullname":"Jose Silva","createdAt":"2016-02-18T20:03:28.000-02","birthDate":"1989-06-01T00:00:00.000-03","email":"jose_silva0@email.com","phone":{"countryCode":"55","areaCode":"11","number":"66778899"},"taxDocument":{"type":"CPF","number":"22222222222"},"shippingAddress":{"zipCode":"01234000","street":"Avenida Faria Lima","streetNumber":"2927","complement":"8","city":"Sao Paulo","district":"Itaim","state":"SP","country":"BRA"},"_links":{"self":{"href":"https://sandbox.moip.com.br/v2/customers/CUS-7U5K9KWG8DBZ"}}},"payments":[],"refunds":[],"entries":[],"events":[{"type":"ORDER.CREATED","createdAt":"2016-02-19T12:24:55.849-02","description":""}],"receivers":[{"moipAccount":{"id":"MPA-7ED9D2D0BC81","login":"ev@traca.com.br","fullname":"Carmen Elisabete de Menezes ME"},"type":"PRIMARY","amount":{"total":102470,"fees":0,"refunds":0}}],"shippingAddress":{"zipCode":"01234000","street":"Avenida Faria Lima","streetNumber":"2927","complement":"8","city":"Sao Paulo","district":"Itaim","state":"SP","country":"BRA"},"_links":{"self":{"href":"https://sandbox.moip.com.br/v2/orders/ORD-HG479ZEIB7LV"},"checkout":{"payOnlineBankDebitItau":{"redirectHref":"https://checkout-sandbox.moip.com.br/debit/itau/ORD-HG479ZEIB7LV"},"payCreditCard":{"redirectHref":"https://checkout-sandbox.moip.com.br/creditcard/ORD-HG479ZEIB7LV"},"payBoleto":{"redirectHref":"https://checkout-sandbox.moip.com.br/boleto/ORD-HG479ZEIB7LV"}}}}';

    /**
     * @var string response from moip API.
     */
    protected $body_cc_pay_pci = '{"id":"PAY-L6J2NKS9OGYU","status":"IN_ANALYSIS","delayCapture":false,"amount":{"total":102470,"fees":5695,"refunds":0,"liquid":96775,"currency":"BRL"},"installmentCount":1,"fundingInstrument":{"creditCard":{"id":"CRC-2TJ13YB4Y1WU","brand":"MASTERCARD","first6":"555566","last4":"8884","holder":{"birthdate":"1989-06-01","birthDate":"1989-06-01","taxDocument":{"type":"CPF","number":"22222222222"},"fullname":"Jose Silva"}},"method":"CREDIT_CARD"},"fees":[{"type":"TRANSACTION","amount":5695}],"events":[{"type":"PAYMENT.IN_ANALYSIS","createdAt":"2016-02-19T18:18:54.535-02"},{"type":"PAYMENT.CREATED","createdAt":"2016-02-19T18:18:51.946-02"}],"_links":{"order":{"href":"https://sandbox.moip.com.br/v2/orders/ORD-8UDL4K9VRJTB","title":"ORD-8UDL4K9VRJTB"},"self":{"href":"https://sandbox.moip.com.br/v2/payments/PAY-L6J2NKS9OGYU"}},"createdAt":"2016-02-19T18:18:51.944-02","updatedAt":"2016-02-19T18:18:54.535-02"}';

    /**
     * @var string response from moip API.
     */
    protected $body_cc_pay_pci_store = '{"id":"PAY-L6J2NKS9OGYU","status":"IN_ANALYSIS","delayCapture":false,"amount":{"total":102470,"fees":5695,"refunds":0,"liquid":96775,"currency":"BRL"},"installmentCount":1,"fundingInstrument":{"creditCard":{"id":"CRC-2TJ13YB4Y1WU","brand":"MASTERCARD","first6":"555566","last4":"8884","store":false,"holder":{"birthdate":"1989-06-01","birthDate":"1989-06-01","taxDocument":{"type":"CPF","number":"22222222222"},"fullname":"Jose Silva"}},"method":"CREDIT_CARD"},"fees":[{"type":"TRANSACTION","amount":5695}],"events":[{"type":"PAYMENT.IN_ANALYSIS","createdAt":"2016-02-19T18:18:54.535-02"},{"type":"PAYMENT.CREATED","createdAt":"2016-02-19T18:18:51.946-02"}],"_links":{"order":{"href":"https://sandbox.moip.com.br/v2/orders/ORD-8UDL4K9VRJTB","title":"ORD-8UDL4K9VRJTB"},"self":{"href":"https://sandbox.moip.com.br/v2/payments/PAY-L6J2NKS9OGYU"}},"createdAt":"2016-02-19T18:18:51.944-02","updatedAt":"2016-02-19T18:18:54.535-02"}';

    /**
     * @var string response from moip API.
     */
    protected $body_cc_pay_pci_escrow = '{"id":"PAY-DB5TBW0E0Z24","status":"IN_ANALYSIS","delayCapture":false,"amount":{"total":7300,"fees":0,"refunds":0,"liquid":7300,"currency":"BRL"},"installmentCount":1,"statementDescriptor":"minhaLoja.com","fundingInstrument":{"creditCard":{"id":"CRC-7D197TPTPYWQ","brand":"VISA","first6":"401200","last4":"1112","store":true,"holder":{"birthdate":"1988-12-30","birthDate":"1988-12-30","taxDocument":{"type":"CPF","number":"22222222222"},"fullname":"Jose Portador da Silva"}},"method":"CREDIT_CARD"},"fees":[{"type":"TRANSACTION","amount":0}],"escrows":[{"id":"ECW-MYB3UUWHHPM9","status":"HOLD_PENDING","description":"teste de descricao","amount":7300,"createdAt":"2017-07-05T10:19:19.156-03","updatedAt":"2017-07-05T10:19:19.156-03","_links":{"self":{"href":"https://sandbox.moip.com.br/v2/escrows/ECW-MYB3UUWHHPM9"},"order":{"href":"https://sandbox.moip.com.br/v2/orders/ORD-QDDLVRVO8ZTK","title":"ORD-QDDLVRVO8ZTK"},"payment":{"href":"https://sandbox.moip.com.br/v2/payments/PAY-DB5TBW0E0Z24","title":"PAY-DB5TBW0E0Z24"}}}],"events":[{"type":"PAYMENT.IN_ANALYSIS","createdAt":"2017-07-05T10:19:19.299-03"},{"type":"PAYMENT.CREATED","createdAt":"2017-07-05T10:19:19.125-03"}],"receivers":[{"moipAccount":{"id":"MPA-7ED9D2D0BC81","login":"ev@traca.com.br","fullname":"Carmen Elisabete de Menezes ME"},"type":"PRIMARY","amount":{"total":7300,"refunds":0}}],"_links":{"self":{"href":"https://sandbox.moip.com.br/v2/payments/PAY-DB5TBW0E0Z24"},"order":{"href":"https://sandbox.moip.com.br/v2/orders/ORD-QDDLVRVO8ZTK","title":"ORD-QDDLVRVO8ZTK"}},"createdAt":"2017-07-05T10:19:19.117-03","updatedAt":"2017-07-05T10:19:19.299-03"}';

    /**
     * @var string response from moip API.
     */
    protected $body_release_escrow = '{"id":"ECW-H57H2GERO1WD","status":"RELEASED","description":"teste de descricao","amount":7300,"createdAt":"2017-07-06T10:57:33.000-03","updatedAt":"2017-07-06T10:57:33.000-03","_links":{"self":{"href":"https://sandbox.moip.com.br/v2/escrows/ECW-H57H2GERO1WD"},"order":{"href":"https://sandbox.moip.com.br/v2/orders/ORD-P7SAQDF3ZRK4","title":"ORD-P7SAQDF3ZRK4"},"payment":{"href":"https://sandbox.moip.com.br/v2/payments/PAY-6UIWRQ6YA89A","title":"PAY-6UIWRQ6YA89A"}}}';

    /**
     * @var string response from moip API.
     */
    protected $body_billet_pay = '{"id":"PAY-XNVIBO5MIQ9S","status":"WAITING","delayCapture":false,"amount":{"total":102470,"fees":3645,"refunds":0,"liquid":98825,"currency":"BRL"},"installmentCount":1,"fundingInstrument":{"boleto":{"expirationDate":"2016-05-21","lineCode":"23793.39126 60000.062608 32001.747909 7 68010000102470"},"method":"BOLETO"},"fees":[{"type":"TRANSACTION","amount":3645}],"events":[{"type":"PAYMENT.CREATED","createdAt":"2016-05-20T15:19:47.000-03"},{"type":"PAYMENT.WAITING","createdAt":"2016-05-20T15:19:47.000-03"}],"_links":{"order":{"href":"https://sandbox.moip.com.br/v2/orders/ORD-3KSQDBJSTIF6","title":"ORD-3KSQDBJSTIF6"},"payBoleto":{"redirectHref":"https://checkout-sandbox.moip.com.br/boleto/PAY-XNVIBO5MIQ9S"},"self":{"href":"https://sandbox.moip.com.br/v2/payments/PAY-XNVIBO5MIQ9S"}},"updatedAt":"2016-05-20T15:19:47.000-03","createdAt":"2016-05-20T15:19:47.000-03"}';

    /**
     * @var string response from moip API.
     */
    protected $body_refund_full_bankaccount = '{"id":"REF-F60THFADO8N4","status":"REQUESTED","events":[{"type":"REFUND.REQUESTED","createdAt":"2017-06-27T08:52:36.000-03"}],"amount":{"total":45000,"fees":0,"currency":"BRL"},"type":"FULL","refundingInstrument":{"bankAccount":{"bankNumber":"001","bankName":"BANCO DO BRASIL S.A.","agencyNumber":"1584","agencyCheckNumber":"9","accountNumber":"00210169","accountCheckNumber":"6","type":"CHECKING","holder":{"taxDocument":{"type":"CPF","number":"22222222222"},"fullname":"Fulano de Tal"}},"method":"BANK_ACCOUNT"},"createdAt":"2017-06-27T08:52:36.000-03","_links":{"self":{"href":"https://sandbox.moip.com.br/v2/refunds/REF-F60THFADO8N4"},"order":{"href":"https://sandbox.moip.com.br/v2/orders/ORD-KNCJJINLN9QL","title":"ORD-KNCJJINLN9QL"},"payment":{"href":"https://sandbox.moip.com.br/v2/payments/PAY-WRZLMJ8JZA9Q","title":"PAY-WRZLMJ8JZA9Q"}}}';

    /**
     * @var string response from moip API.
     */
    protected $body_refund_partial_bankaccount = '{"id":"REF-0R8FSCPTI5IS","status":"REQUESTED","events":[{"type":"REFUND.REQUESTED","createdAt":"2017-06-27T11:27:56.000-03"}],"amount":{"total":20000,"fees":0,"currency":"BRL"},"type":"PARTIAL","refundingInstrument":{"bankAccount":{"bankNumber":"001","bankName":"BANCO DO BRASIL S.A.","agencyNumber":"1584","agencyCheckNumber":"9","accountNumber":"00210169","accountCheckNumber":"6","type":"SAVING","holder":{"taxDocument":{"type":"CPF","number":"22222222222"},"fullname":"Fulano de Tal"}},"method":"BANK_ACCOUNT"},"createdAt":"2017-06-27T11:27:56.000-03","_links":{"self":{"href":"https://sandbox.moip.com.br/v2/refunds/REF-0R8FSCPTI5IS"},"order":{"href":"https://sandbox.moip.com.br/v2/orders/ORD-0HX56ERCBKWE","title":"ORD-0HX56ERCBKWE"},"payment":{"href":"https://sandbox.moip.com.br/v2/payments/PAY-1177YNDVSO7W","title":"PAY-1177YNDVSO7W"}}}';

    /**
     * @var string response from moip API.
     */
    protected $body_notification_preference = '{"events":["ORDER.*","PAYMENT.AUTHORIZED","PAYMENT.CANCELLED"],"target":"http://requestb.in/1dhjesw1","media":"WEBHOOK","token":"994e3ffae9214fbc806d01de2dd5d341","id":"NPR-N6QZE3223P98"}';

    /**
     * @var string response from moip API.
     */
    protected $body_moip_account = '{"id":"MPA-CB428374873D","login":"fulano@email2.com","accessToken":"f16fee8779d84e8ba91588b443b665c6_v2","channelId":"APP-18JTHC3LEMT9","type":"MERCHANT","transparentAccount":true,"email":{"address":"fulano@email2.com","confirmed":false},"person":{"name":"Fulano","lastName":"De Tal","birthDate":"1988-12-30","taxDocument":{"type":"CPF","number":"162.621.310-00"},"address":{"street":"Rua de teste","streetNumber":"123","district":"Bairro","zipcode":"01234567","zipCode":"01234567","city":"Sao Paulo","state":"SP","country":"BRA","complement":"Apt. 23"},"phone":{"countryCode":"55","areaCode":"11","number":"66778899"},"identityDocument":{"number":"4737283560","issuer":"SSP","issueDate":"2015-06-23","type":"RG"},"alternativePhones":[{"countryCode":"55","areaCode":"11","number":"66448899"},{"countryCode":"55","areaCode":"11","number":"66338899"}]},"company":{"name":"Empresa Teste","businessName":"Teste Empresa ME","taxDocument":{"type":"CNPJ","number":"69.086.878/0001-98"},"address":{"street":"Rua de teste 2","streetNumber":"123","district":"Bairro Teste","zipcode":"01234567","zipCode":"01234567","city":"Sao Paulo","state":"SP","country":"BRA","complement":"Apt. 23"},"phone":{"countryCode":"55","areaCode":"11","number":"66558899"},"openingDate":"2011-01-01"},"createdAt":"2017-07-10T13:42:19.967Z","_links":{"self":{"href":"https://sandbox.moip.com.br/moipaccounts/MPA-CB428374873D","title":null}}}';

    /**
     * @var string response from moip API.
     */
    protected $body_order_list = '{"_links":{"next":{"href":"https://test.moip.com.br/v2/orders?filters=&limit=0&offset=0"},"previous":{"href":"https://test.moip.com.br/v2/orders?filters=&limit=0&offset=0"}},"summary":{"count":1410,"amount":143206880},"orders":[{"id":"ORD-YGTX9WA7LJH4","ownId":"ord-596d0a2f27afd","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-R4EVDK7XCWXO","installmentCount":1,"fundingInstrument":{"method":"BOLETO","brand":null}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T16:04:17Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-YGTX9WA7LJH4"}},"createdAt":"2017-07-17T16:04:15-0300","updatedAt":"2017-07-17T16:04:17-0300"},{"id":"ORD-V3S4BJVZE498","ownId":"ord-596d0a2b8d761","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-VT4S5A8T7K3Q","installmentCount":1,"fundingInstrument":{"method":"BOLETO","brand":null}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T16:04:14Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-V3S4BJVZE498"}},"createdAt":"2017-07-17T16:04:12-0300","updatedAt":"2017-07-17T16:04:14-0300"},{"id":"ORD-G0VSWOKU4XT6","ownId":"ord-596d0a29ccc20","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-8D4X9ORC3AQE","installmentCount":1,"fundingInstrument":{"method":"CREDIT_CARD","brand":"MASTERCARD"}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T16:04:12Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-G0VSWOKU4XT6"}},"createdAt":"2017-07-17T16:04:10-0300","updatedAt":"2017-07-17T16:04:12-0300"},{"id":"ORD-X86V05B9C9KP","ownId":"ord-596d0a2817d20","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-ZZ6KTYGB0A3W","installmentCount":1,"fundingInstrument":{"method":"CREDIT_CARD","brand":"MASTERCARD"}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T16:04:10Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-X86V05B9C9KP"}},"createdAt":"2017-07-17T16:04:08-0300","updatedAt":"2017-07-17T16:04:10-0300"},{"id":"ORD-CH5KC3651X0F","ownId":"ord-596d0a266f3b8","status":"WAITING","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-JJN5HE5UDBI8","installmentCount":1,"fundingInstrument":{"method":"BOLETO","brand":null}}],"events":[],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-CH5KC3651X0F"}},"createdAt":"2017-07-17T16:04:07-0300","updatedAt":"2017-07-17T16:04:07-0300"},{"id":"ORD-9K08IRRNFN7B","ownId":"ord-596d0a24ae966","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-R1TXXR77Q545","installmentCount":1,"fundingInstrument":{"method":"CREDIT_CARD","brand":"MASTERCARD"}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T16:04:07Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-9K08IRRNFN7B"}},"createdAt":"2017-07-17T16:04:05-0300","updatedAt":"2017-07-17T16:04:07-0300"},{"id":"ORD-SM7XKXN7MIG5","ownId":"ord-596d0a1d285ad","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-01G3VA7E68Q6","installmentCount":1,"fundingInstrument":{"method":"CREDIT_CARD","brand":"MASTERCARD"}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T16:04:00Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-SM7XKXN7MIG5"}},"createdAt":"2017-07-17T16:03:57-0300","updatedAt":"2017-07-17T16:04:00-0300"},{"id":"ORD-H8LPAPRTTAKZ","ownId":"ord-596cbf48d469e","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-T7L8KE7PQN5A","installmentCount":1,"fundingInstrument":{"method":"BOLETO","brand":null}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T10:44:44Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-H8LPAPRTTAKZ"}},"createdAt":"2017-07-17T10:44:42-0300","updatedAt":"2017-07-17T10:44:44-0300"},{"id":"ORD-YW3TQK546YIC","ownId":"ord-596cbf4561215","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-9GIQDLRBOIV9","installmentCount":1,"fundingInstrument":{"method":"BOLETO","brand":null}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T10:44:40Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-YW3TQK546YIC"}},"createdAt":"2017-07-17T10:44:38-0300","updatedAt":"2017-07-17T10:44:40-0300"},{"id":"ORD-HVR5MWIG0P34","ownId":"ord-596cbf4390fee","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-IVE2AOU9J2N8","installmentCount":1,"fundingInstrument":{"method":"CREDIT_CARD","brand":"MASTERCARD"}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T10:44:38Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-HVR5MWIG0P34"}},"createdAt":"2017-07-17T10:44:37-0300","updatedAt":"2017-07-17T10:44:38-0300"},{"id":"ORD-2J5JLVU7EGKB","ownId":"ord-596cbf41d2fbc","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-UAFV8545206B","installmentCount":1,"fundingInstrument":{"method":"CREDIT_CARD","brand":"MASTERCARD"}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T10:44:37Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-2J5JLVU7EGKB"}},"createdAt":"2017-07-17T10:44:35-0300","updatedAt":"2017-07-17T10:44:37-0300"},{"id":"ORD-HKRZQVRTRDH8","ownId":"ord-596cbf4010d6e","status":"WAITING","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-BCRX4I3AM9KB","installmentCount":1,"fundingInstrument":{"method":"BOLETO","brand":null}}],"events":[],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-HKRZQVRTRDH8"}},"createdAt":"2017-07-17T10:44:33-0300","updatedAt":"2017-07-17T10:44:34-0300"},{"id":"ORD-WLBSRO4P4OSN","ownId":"ord-596cbf3e68ecd","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-X55ILM54CJDD","installmentCount":1,"fundingInstrument":{"method":"CREDIT_CARD","brand":"MASTERCARD"}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T10:44:33Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-WLBSRO4P4OSN"}},"createdAt":"2017-07-17T10:44:32-0300","updatedAt":"2017-07-17T10:44:33-0300"},{"id":"ORD-208TQQEBOVGK","ownId":"ord-596cbf370d5b9","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-4ERO22PR5KR0","installmentCount":1,"fundingInstrument":{"method":"CREDIT_CARD","brand":"MASTERCARD"}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T10:44:26Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-208TQQEBOVGK"}},"createdAt":"2017-07-17T10:44:24-0300","updatedAt":"2017-07-17T10:44:26-0300"},{"id":"ORD-Y3BUNI0V0LSW","ownId":"ord-596cbc3254a14","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-J3S1516G5JVU","installmentCount":1,"fundingInstrument":{"method":"BOLETO","brand":null}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T10:31:33Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-Y3BUNI0V0LSW"}},"createdAt":"2017-07-17T10:31:31-0300","updatedAt":"2017-07-17T10:31:33-0300"},{"id":"ORD-Q7NOC8REYY06","ownId":"ord-596cbc2e9590c","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-HT2FZDWICTK2","installmentCount":1,"fundingInstrument":{"method":"BOLETO","brand":null}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T10:31:30Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-Q7NOC8REYY06"}},"createdAt":"2017-07-17T10:31:28-0300","updatedAt":"2017-07-17T10:31:30-0300"},{"id":"ORD-JU20ND03V5HC","ownId":"ord-596cbc2ce0a94","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-TCDRZ8EF41LL","installmentCount":1,"fundingInstrument":{"method":"CREDIT_CARD","brand":"MASTERCARD"}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T10:31:28Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-JU20ND03V5HC"}},"createdAt":"2017-07-17T10:31:26-0300","updatedAt":"2017-07-17T10:31:28-0300"},{"id":"ORD-ZN18QTEJPH8V","ownId":"ord-596cbc2b4331f","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-PDF6SCXFCSXB","installmentCount":1,"fundingInstrument":{"method":"CREDIT_CARD","brand":"MASTERCARD"}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T10:31:26Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-ZN18QTEJPH8V"}},"createdAt":"2017-07-17T10:31:24-0300","updatedAt":"2017-07-17T10:31:26-0300"},{"id":"ORD-ET4URMB22ZEB","ownId":"ord-596cbc299d9b8","status":"WAITING","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-4MKP4WM73M4V","installmentCount":1,"fundingInstrument":{"method":"BOLETO","brand":null}}],"events":[],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-ET4URMB22ZEB"}},"createdAt":"2017-07-17T10:31:23-0300","updatedAt":"2017-07-17T10:31:24-0300"},{"id":"ORD-7S9CWLEOMGSI","ownId":"ord-596cbc27c7853","status":"PAID","blocked":false,"amount":{"total":102470,"addition":0,"fees":0,"deduction":0,"otherReceivers":0,"currency":"BRL"},"receivers":[{"type":"PRIMARY","moipAccount":{"id":"MPA-8D5DBB4EF8B8"}}],"customer":{"fullname":"jose silva","email":"jose_silva0@email.com"},"items":[{"product":null}],"payments":[{"id":"PAY-KFPLX5TSEQ3B","installmentCount":1,"fundingInstrument":{"method":"CREDIT_CARD","brand":"MASTERCARD"}}],"events":[{"type":"PAYMENT.AUTHORIZED","createdAt":"2017-07-17T10:31:22Z"}],"_links":{"self":{"href":"https://test.moip.com.br/v2/orders/ORD-7S9CWLEOMGSI"}},"createdAt":"2017-07-17T10:31:21-0300","updatedAt":"2017-07-17T10:31:22-0300"}]}';

    /**
     * @var string holds the last generated customer ownId. In mock mode it'll be always the default, but it changes on sandbox mode.
     */
    protected $last_cus_id = 'meu_id_customer';

    /**
     * @var string same as `$last_cus_id` but for orders.
     *
     * @see $last_cus_id
     */
    protected $last_ord_id = 'meu_id_pedido';
    protected $sandbox_mock = self::MOCK;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    public function setUp()
    {
        // check if we can run the request on sandbox
        $moip_access_token = getenv('MOIP_ACCESS_TOKEN');

        if ($moip_access_token) {
            $this->sandbox_mock = self::SANDBOX;
            $auth = new OAuth($moip_access_token);
        } else {
            $this->sandbox_mock = self::MOCK;
            $auth = $this->getMock('\Moip\Contracts\Authentication');
        }
        $this->moip = new Moip($auth, Moip::ENDPOINT_SANDBOX);
    }

    /**
     * If in MOCK mode returns a mocked Requests_Sessesion if in SANDBOX mode, creates a new session.
     *
     * @param string $body        what the request will return
     * @param int    $status_code what http code the request will return
     */
    public function mockHttpSession($body, $status_code = 200)
    {
        if ($this->sandbox_mock == self::SANDBOX) {
            $this->moip->createNewSession();

            return;
        }
        $resp = new Requests_Response();
        $resp->body = $body;
        $resp->status_code = $status_code;
        $sess = $this->getMock('\Requests_Session');
        $sess->expects($this->once())->method('request')->willReturn($resp);
        $this->moip->setSession($sess);
    }

    /**
     * Creates a customer.
     *
     * @return Customer
     */
    public function createCustomer()
    {
        if ($this->sandbox_mock == self::SANDBOX) {
            $this->last_cus_id = uniqid('CUS-');
        } else {
            $this->last_cus_id = 'meu_id_sandbox';
        }

        $customer = $this->moip->customers()->setOwnId($this->last_cus_id)
            ->setBirthDate(\DateTime::createFromFormat($this->date_format, $this->date_string))
            ->setFullname('Jose Silva')
            ->setEmail('jose_silva0@email.com')
            ->setTaxDocument('22222222222', 'CPF')
            ->setPhone(11, 66778899, 55)
            ->addAddress(Customer::ADDRESS_SHIPPING, 'Avenida Faria Lima', '2927', 'Itaim', 'Sao Paulo', 'SP', '01234000', '8');

        return $customer;
    }

    /**
     * Creates a account.
     *
     * @return Account
     */
    public function createAccount()
    {
        $moip = new Moip(new OAuth('1tldio91gi74r34zv30d4saz8yuuws5'), Moip::ENDPOINT_SANDBOX);

        $uniqEmail = 'fulano'.uniqid('MPA-').'@detal123.com.br';

        $account = $moip->accounts()
            ->setEmail($uniqEmail)
            ->setName('Fulano')
            ->setLastName('de Tal')
            ->setBirthDate('1987-11-27')
            ->setTaxDocument('22222222222')
            ->setPhone(11, 988888888)
            ->addAddress('Av. Ibirapuera', '2035', 'Moema', 'Sao Paulo', 'SP', '04078010')
            ->setIdentityDocument('411111115', 'SSP', '2000-05-06')
            ->create();

        return $account;
    }

    /**
     * Creates an order.
     *
     * @return Orders
     */
    public function createOrder()
    {
        if ($this->sandbox_mock == self::SANDBOX) {
            $this->last_ord_id = uniqid('ORD-');
        } else {
            $this->last_ord_id = 'meu_id_pedido';
        }

        $order = $this->moip->orders()->setCustomer($this->createCustomer())
            ->addItem('Nome do produto', 1, 'Mais info...', 100000)
            ->addItem('abacaxi', 2, 'Abacaxi de terra de areia', 990)
            ->setDiscount(1000)
            ->setShippingAmount(1490)
            ->setOwnId($this->last_ord_id);

        return $order;
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    public function tearDown()
    {
        $this->moip = null;
    }
}
