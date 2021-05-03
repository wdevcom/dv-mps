<?php

error_reporting(-1);
ini_set('display_errors', 'On');
//ini_set('soap.wsdl_cache_enabled',0);
//ini_set('soap.wsdl_cache_ttl',0);

require_once 'vendor/autoload.php';

use DigitalVirgo\MPS\Service\ClientSoap;

require_once 'MySoapServer.php';



$server = new SoapServer(null, [
    'classmap' => ClientSoap::$classmap,
    'uri' => 'http://127.0.0.1/',
]);
$server->setClass('MySoapServer');
$server->handle();