<?php


namespace Wechat;


class BaseModel
{
    protected $_legalAttributes = [];
    protected $_attributes = [];

    public function setAttributes($attributes)
    {
        if (is_array($attributes)) {
            foreach ($attributes as $attribute => $value) {
                $this->$attribute = $value;
            }
        }
    }

    public function __set($attribute, $value)
    {
        if (is_string($attribute)
            && in_array($attribute, $this->_legalAttributes)
            && !method_exists($this, 'get' . ucfirst($attribute))
        ) {
            if (!array_key_exists($attribute, $this->_attributes) || $this->_attributes[$attribute] !== $value) {
                $this->$attribute = $value;
                $this->_attributes[$attribute] = $value;
            }
        }
    }

    public function __get($name)
    {
        if (isset($this->$name)) {
            return $name;
        }
        $func = 'get' . ucfirst($name);
        if (method_exists($this, $func)) {
            return $this->$func();
        }
        return null;
    }

    public function get($key)
    {
        if (isset($this->_attributes[$key])) {
            return $this->_attributes[$key];
        }
        return null;
    }
}