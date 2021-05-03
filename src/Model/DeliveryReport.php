<?php

namespace DigitalVirgo\MPS\Model;

class DeliveryReport extends ModelAbstract
{
    const NO_DELIVERY_REPORT              = 0;
    const GENERIC_DELIVERY_REPORT         = 1;
    const MOBILE_ENDPOINT_DELIVERY_REPORT = 2;
    const MOBILE_USER_DELIVERY_REPORT     = 3;

    public static $reportTypeMap = array(
        'NO_DELIVERY_REPORT'              => self::NO_DELIVERY_REPORT,
        'GENERIC_DELIVERY_REPORT'         => self::GENERIC_DELIVERY_REPORT,
        'MOBILE_ENDPOINT_DELIVERY_REPORT' => self::MOBILE_ENDPOINT_DELIVERY_REPORT,
        'MOBILE_USER_DELIVERY_REPORT'     => self::MOBILE_USER_DELIVERY_REPORT
    );

    /**
     * @var int
     */
    public $id;

    /**
     * @var \DateTime;
     */
    public $deliveryDate;

    /**
     * @var int
     */
    public $deliveryStatus;

    /**
     * @var string
     */
    public $description;

    /**
     * @var int
     */
    public $deliveryReportType;

    /**
     * @var array
     */
    public $deliveryReportProperties;

    /**
     * @param \DateTime $deliveryDate
     */
    public function setDeliveryDate($deliveryDate)
    {
        if (is_string($deliveryDate)) {
            $deliveryDate = new \DateTime($deliveryDate);
        }

        $this->deliveryDate = $deliveryDate;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * @param array $deliveryReportProperties
     */
    public function setDeliveryReportProperties($deliveryReportProperties)
    {
        if ($deliveryReportProperties instanceof \SimpleXMLElement) {
            $deliveryReportProperties = (array)$deliveryReportProperties;

            if (array_key_exists('@attributes', $deliveryReportProperties)) {
                unset($deliveryReportProperties['@attributes']);
            }
        }

        $this->deliveryReportProperties = $deliveryReportProperties;
        return $this;
    }

    /**
     * @return array
     */
    public function getDeliveryReportProperties()
    {
        return $this->deliveryReportProperties;
    }

    /**
     * @param int $deliveryReportType
     */
    public function setDeliveryReportType($deliveryReportType)
    {
        if (is_string($deliveryReportType) && array_key_exists($deliveryReportType, self::$reportTypeMap)) {
            $deliveryReportType = self::$reportTypeMap[$deliveryReportType];
        }

        if (!in_array($deliveryReportType, self::$reportTypeMap)) {
            throw new \Exception("Invalid deliveryReportType value: $deliveryReportType");
        }

        $this->deliveryReportType = $deliveryReportType;
        return $this;
    }

    /**
     * @return int
     */
    public function getDeliveryReportType()
    {
        return $this->deliveryReportType;
    }

    /**
     * @param int $deliveryStatus
     */
    public function setDeliveryStatus($deliveryStatus)
    {
        $this->deliveryStatus = (int)$deliveryStatus;
        return $this;
    }

    /**
     * @return int
     */
    public function getDeliveryStatus()
    {
        return $this->deliveryStatus;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


}
