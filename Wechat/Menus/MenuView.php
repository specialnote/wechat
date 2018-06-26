<?php


namespace Wechat\Menus;


use Wechat\Utils\PregUtils;

class MenuView extends Menu
{
    protected $url;

    public function getUrl()
    {
        if (PregUtils::isUrl($this->url)) {
            return $this->url;
        } else {
            return null;
        }
    }

    public function toArray()
    {
        return [
            'type' => 'view',
            'name' => $this->getName(),
            'url' => $this->getUrl(),
        ];
    }
}