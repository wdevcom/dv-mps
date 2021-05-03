<?php

namespace DigitalVirgo\MPS\Model;

class SmsText extends SmsAbstract
{

    /**
     * @var string
     */
    public $text;

    /**
     * @var int
     */
    public $dataCodingScheme = self::CODING_SCHEME_ASCII_GSM_7BIT;

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;

        $this->setPartsNumber(1);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    public function getMessageType()
    {
        return 'SMSText';
    }

    public function validate()
    {
        if (empty($this->text)) {
            throw new \Exception('text field is required');
        }

        return parent::validate();
    }


    /**
     * @return \DOMElement|void
     */
    protected function _prepareXml()
    {
        $message = parent::_prepareXml();

        $text = $this->getText();
        if (!empty($text)) {
            $param = $this->_xml->createElement('text', $text);
            $message->appendChild($param);
        }
    }

}