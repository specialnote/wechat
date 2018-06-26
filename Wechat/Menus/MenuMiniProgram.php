<?php


namespace Wechat\Menus;


use Wechat\Utils\PregUtils;

class MenuMiniProgram extends Menu
{
    protected $url;
    protected $appId;
    protected $pagePath;

    public function getUrl()
    {
        if (PregUtils::isUrl($this->url)) {
            return $this->url;
        } else {
            return null;
        }
    }

    public function getAppId()
    {
        return $this->appId;
    }

    public function getPagePath()
    {
        return ltrim($this->pagePath, '/');
    }

    public function toArray()
    {
        return [
            'type' => 'miniprogram',
            'name' => $this->getName(),
            'url' => $this->getUrl(),
            'appid' => $this->getAppId(),
            'pagepath' => $this->getPagePath(),
        ];
    }
}