<?php

namespace DigitalVirgo\MPS\Model;

abstract class ModelAbstract
{

    public function __construct($params = array())
    {
        if (!empty($params)) {
            $this->setData($params);
        }
    }

    public function setData($params)
    {
        foreach ($params as $name => $value) {
            $method = 'set' . ucfirst($name);
            if (method_exists($this, $method)) {
                call_user_func(array($this, $method), $value);
            } else if (property_exists($this, $name)) {
                $this->$name = $value;
            }
        }
    }

    public function toArray()
    {
        $data = (array)$this;

        foreach ($data as $key => $value) {
            $propName = substr($key, 3);
            if (property_exists(get_class($this), $propName)) {
                if (!is_object($value)) {
                    if (is_bool($value)) {
                        $data[$propName] = intval($value);
                    } else {
                        $data[$propName] = $value;
                    }
                } else if ($value instanceof \DateTime) {
                    $data[$propName] = $value;
                } else if ($value instanceof ModelAbstract) {
                    $data[$propName] = $value->toArray();
                }
            }

            unset($data[$key]);

        }

        return $data;
    }
}
