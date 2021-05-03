<?php

use \DigitalVirgo\MPS\Model\MessageAbstract;
use \DigitalVirgo\MPS\Model\DeliveryReport;

class MySoapServer {

    public function put(MessageAbstract $message)
    {
        //handle message

        return print_r($message, true); //only to show client what server receive
    }

    public function ack(DeliveryReport $deliveryReport)
    {
        //handle report

        return print_r($deliveryReport, true); //only to show client what server receive
    }
}