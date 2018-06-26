<?php


namespace Wechat\Menus;


abstract class BaseMenu
{
    public function __construct($attributes = [])
    {
        foreach ($attributes as $attr => $value) {
            $this->$attr = $value;
        }
    }

    public function __set($name, $value)
    {
        if (is_string($name) && method_exists($this, 'get' . ucfirst($name))) {
            $this->$name = $value;
        }
    }

    abstract function toArray();
}