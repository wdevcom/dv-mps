<?php

error_reporting(-1);
ini_set('display_errors', 'On');
ini_set('soap.wsdl_cache_enabled',0);
ini_set('soap.wsdl_cache_ttl',0);

require_once 'vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use DigitalVirgo\MPS\Model\SmsText;
use DigitalVirgo\MPS\Model\DeliveryReport;
use DigitalVirgo\MPS\Service\ClientRest;
use DigitalVirgo\MPS\Service\ClientSoap;

//use SLIM framework for example

$app = new \Slim\App([
    'debug' => true,
    'settings' => [
        'displayErrorDetails' => true
    ]
]);
$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("<ul>");
    $response->getBody()->write("<li><a href=\"/rest/send\">Send REST</a></li>");
    $response->getBody()->write("<li><a href=\"/soap/send\">Send SOAP</a></li>");
    $response->getBody()->write("<li><a href=\"/rest/receive/message\">Receive REST message</a></li>");
    $response->getBody()->write("<li><a href=\"/rest/receive/report\">Receive REST report</a></li>");
    $response->getBody()->write("<li><a href=\"/soap/receive/message\">Receive SOAP message</a></li>");
    $response->getBody()->write("<li><a href=\"/soap/receive/report\">Receive SOAP report</a></li>");
    $response->getBody()->write("</ul>");
    return $response;
});
$app->get('/rest/send', function (Request $request, Response $response) {
    /** REST EXAMPLE */

    $client = ClientRest::getInstance('https://demo.partners.avantis.pl');
    $client
        ->setUsername('test')
        ->setPassword('RHA/4jFit!');

    $sms = new SmsText([
        'deliveryRequest' => 3,
        'sender' => '7772',
        'recipient' => '48500000000',
        'text' => 'test rest message',
        'operatorCode' => 26003,
        'billCode' => SmsText::BILL_FREE,
        'category' => SmsText::CATEGORY_GAME,
        'directionValue' => SmsText::DIRECTION_OUT,
        'sendDate' => new \DateTime(),
    ]);

    var_dump($client->sendMessage($sms));

    return $response;
});
$app->get('/soap/send', function (Request $request, Response $response) {
    /** SOAP EXAMPLE */

    ClientSoap::setWsdlUrl('https://demo.partners.avantis.pl/mpsml-adapters/services/MPSLocal2?wsdl');
    $client = new ClientSoap('test', 'RHA/4jFit!');

    $sms = new SmsText([
        'deliveryRequest' => 3,
        'sender' => '7772',
        'recipient' => '48500000000',
        'text' => 'test soap message',
        'operatorCode' => 26003,
        'billCode' => SmsText::BILL_FREE,
        'category' => SmsText::CATEGORY_GAME,
        'directionValue' => SmsText::DIRECTION_OUT,
        'sendDate' => new \DateTime(),
    ]);

    var_dump(
        $client->put($sms, DeliveryReport::MOBILE_USER_DELIVERY_REPORT)
    );

    return $response;
});
$app->get('/rest/receive/message', function (Request $request, Response $response) {

    $xml = '
    <RestMessage>
        <message class="SMSText">
            <id>123456789</id>
            <deliveryDate>2009-01-13T13:11:17Z</deliveryDate>
            <sender>48504208973</sender>
            <sendDate>2009-01-13T13:11:17Z</sendDate>
            <creationDate>2009-01-13T13:11:17Z</creationDate>
            <validityDate>2009-01-13T13:11:17Z</validityDate>
            <directionValue>1</directionValue>
            <operatorCode>26003</operatorCode>
            <recipient>7936</recipient>
            <dataCodingScheme>0</dataCodingScheme>
            <partsNumber>1</partsNumber>
            <text>ABCD75016</text>
        </message>
    </RestMessage>';

    var_dump(
        ClientRest::parseXml($xml)
    );

    return $response;
});
$app->get('/rest/receive/report', function (Request $request, Response $response) {

    $xml = '
    <RestMessage>
        <message class="DeliveryReport">
            <id>123456789</id>
            <deliveryDate>2009-01-13T13:11:17Z</deliveryDate>
            <deliveryReportType>MOBILE_USER_DELIVERY_REPORT</deliveryReportType>
            <deliveryStatus>200</deliveryStatus>
            <deliveryReportProperties class="linked-hash-map">
            </deliveryReportProperties>
        </message>
    </RestMessage>';

    var_dump(
        ClientRest::parseXml($xml)
    );

    return $response;
});


/** !!!! this only simulater DV server requests !!! the right implementation is in `server.php` file */
$app->get('/soap/receive/message', function (Request $request, Response $response) {

    //simulate DV requests
    $client = new SoapClient(null, [
        'classmap' => ClientSoap::$classmap,
        'location' => 'http://127.0.0.1/server.php',
        'uri' => 'http://127.0.0.1/',
    ]);

    $sms = new SmsText([
        'deliveryRequest' => 3,
        'sender' => '7772',
        'recipient' => '48500000000',
        'text' => 'test soap message',
        'operatorCode' => 26003,
        'billCode' => SmsText::BILL_FREE,
        'category' => SmsText::CATEGORY_GAME,
        'directionValue' => SmsText::DIRECTION_OUT,
        'sendDate' => new \DateTime(),
    ]);
    var_dump($client->put($sms));

    return $response;
});
$app->get('/soap/receive/report', function (Request $request, Response $response) {

    //simulate DV requests
    $client = new SoapClient(null, [
        'classmap' => ClientSoap::$classmap,
        'location' => 'http://127.0.0.1/server.php',
        'uri' => 'http://127.0.0.1/',
    ]);

    $report = new DeliveryReport([
        'id' => 123213,
        'deliveryDate' => new \DateTime(),
        'deliveryStatus' => 200,
        'description' => 'sadf dasf saf asfd',
        'deliveryReportType' => DeliveryReport::MOBILE_USER_DELIVERY_REPORT,
        'deliveryReportProperties' => [
            'key' => 'value'
        ],
    ]);
    var_dump($client->ack($report));

    return $response;
});
$app->run();