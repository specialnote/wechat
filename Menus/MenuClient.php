<?php

namespace Wechat\Menus;

/**
 * Class MenuClient
 *
 */
class MenuClient extends Menu
{
    protected $key;

    public function getKey()
    {
        if (empty($this->key)) {
            return md5(microtime(true) . rand(1, 10000));
        }
        return $this->key;
    }

    public function toArray()
    {
        return [
            'type' => 'click',
            'name' => $this->getName(),
            'key' => $this->getKey(),
        ];
    }
}