<?php

namespace DigitalVirgo\MPS\Model;

abstract class SmsAbstract extends MessageAbstract
{

    const DIRECTION_UNDEFINED = 0;
    const DIRECTION_IN        = 1;
    const DIRECTION_OUT       = 2;
    const DIRECTION_INTERNAL  = 3;

    const CODING_SCHEME_ASCII_GSM_7BIT = 0;
    const CODING_SCHEME_BINARY = 4;

    /**
     * @var string
     */
    public $directionValue = self::DIRECTION_UNDEFINED;

    /**
     * @var string
     */
    public $operatorCode;

    /**
     * @var string
     */
    public $recipient;

    /**
     * @var int
     */
    public $dataCodingScheme;

    /**
     * @var int
     */
    public $partsNumber = 0;

    /**
     * @param string $directionValue
     */
    public function setDirectionValue($directionValue)
    {
        if (!in_array($directionValue, array(
            self::DIRECTION_UNDEFINED,
            self::DIRECTION_IN,
            self::DIRECTION_OUT,
            self::DIRECTION_INTERNAL
        ))) {
            throw new \Exception("Invalid directionValue value: $directionValue");
        }

        $this->directionValue = $directionValue;
        return $this;
    }

    /**
     * @return string
     */
    public function getDirectionValue()
    {
        return $this->directionValue;
    }

    /**
     * @param string $operatorCode
     */
    public function setOperatorCode($operatorCode)
    {
        $this->operatorCode = $operatorCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getOperatorCode()
    {
        return $this->operatorCode;
    }

    /**
     * @param string $recipient
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param int $dataCodingScheme
     */
    public function setDataCodingScheme($dataCodingScheme)
    {
        if (!in_array($dataCodingScheme, array(
            self::CODING_SCHEME_ASCII_GSM_7BIT,
            self::CODING_SCHEME_BINARY,
        ))) {
            throw new \Exception("Invalid dataCodingScheme value: $dataCodingScheme");
        }

        $this->dataCodingScheme = $dataCodingScheme;
        return $this;
    }

    /**
     * @param int $partsNumber
     */
    public function setPartsNumber($partsNumber)
    {
        $this->partsNumber = $partsNumber;
        return $this;
    }

    /**
     * @return int
     */
    public function getPartsNumber()
    {
        return $this->partsNumber;
    }



    /**
     * @return \DOMElement
     */
    protected function _prepareXml()
    {
        $message = parent::_prepareXml();

        $directionValue = $this->getDirectionValue();
        if (!empty($directionValue)) {
            $param = $this->_xml->createElement('directionValue', $directionValue);
            $message->appendChild($param);
        }

        $operatorCode = $this->getOperatorCode();
        if (!empty($operatorCode)) {
            $param = $this->_xml->createElement('operatorCode', $operatorCode);
            $message->appendChild($param);
        }

        $recipient = $this->getRecipient();
        if (!empty($recipient)) {
            $param = $this->_xml->createElement('recipient', $recipient);
            $message->appendChild($param);
        }

        return $message;
    }
}