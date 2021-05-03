<?php

namespace DigitalVirgo\MPS\Service;

use DigitalVirgo\MPS\Model\MessageAbstract;
use DigitalVirgo\MPS\Model\PlainTextCredentials;


class ClientSoap extends \SoapClient
{
    static $wsdlUrl = null;

    public static $classmap = array(
        'Message'              => 'DigitalVirgo\MPS\Model\MessageAbstract',
        'SMSText'              => 'DigitalVirgo\MPS\Model\SmsText',
        'PlainTextCredentials' => 'DigitalVirgo\MPS\Model\PlainTextCredentials',
        'DeliveryReport'       => 'DigitalVirgo\MPS\Model\DeliveryReport'
    );

    protected $_credentials;

    public function __construct($username, $password)
    {
        if (self::$wsdlUrl === null) {
            throw new \Exception('Wsdl url required!');
        }

        $this->__setLocation(self::$wsdlUrl);

        $this->_credentials = new PlainTextCredentials(array(
            'login'    => $username,
            'password' => $password
        ));


        return parent::__construct(self::$wsdlUrl, array(
            'classmap' => self::$classmap
        ));
    }

    /**
     * @param MessageAbstract $message
     * @param int $deliveryReport
     * @return Response
     */
    public function put(MessageAbstract $message, $deliveryReport)
    {
        return parent::put($message, $this->_credentials, $deliveryReport);
    }

    /**
     * @return null|string
     */
    public static function getWsdlUrl()
    {
        return self::$wsdlUrl;
    }

    /**
     * @param null|string $wsdlUrl
     */
    public static function setWsdlUrl($wsdlUrl)
    {
        self::$wsdlUrl = $wsdlUrl;
    }


}