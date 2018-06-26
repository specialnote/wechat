<?php


namespace Wechat\Traits;

use Psr\SimpleCache\CacheInterface;
use Wechat\Services\WechatAccessToken;
use Wechat\Services\WechatAuth;
use Wechat\Services\WechatBlackList;
use Wechat\Services\WechatCustomizeMenu;
use Wechat\Services\WechatCustomizeMessage;
use Wechat\Services\WechatMedia;
use Wechat\Services\WechatQRCode;
use Wechat\Services\WechatTag;
use Wechat\Services\WechatTemplateMessage;
use Wechat\Services\WechatUrl;
use Wechat\Services\WechatUser;
use Wechat\WechatClient;
use Wechat\WechatContainer;

/**
 * @property    CacheInterface $cache
 * @property    WechatAccessToken $accessToken
 * @property    WechatCustomizeMenu $customerMenu
 * @property    WechatCustomizeMessage $customerMessage
 * @property    WechatTemplateMessage   $templateMessage
 * @property    WechatAuth  $auth
 * @property    WechatMedia $media
 * @property    WechatTag   $tag
 * @property    WechatUser   $user
 * @property    WechatBlackList   $blackList
 * @property    WechatQRCode    $qrCode
 * @property    WechatUrl   $url
 */
trait WechatServiceTrait
{
    private $container;

    public function getContainer()
    {
        return $this->container;
    }

    protected function loadServices($services)
    {
        if (is_null($this->container)) {
            $container = new WechatContainer();
        } else {
            $container = $this->container;
        }
        /**
         * @var WechatClient $this
         */
        $client = $this;
        //---------- load services ----------
        //cache
        if (isset($services['cache']) && is_subclass_of($services['cache'], CacheInterface::class)) {
            $container['cache'] = function () use ($services) {
                return new $services['cache'];
            };
        }
        //wechatAccessToken
        $container['wechatAccessToken'] = function () use ($client) {
            return new WechatAccessToken($client);
        };
        //menu
        $container['wechatCustomerMenu'] = function () use ($client) {
            return new WechatCustomizeMenu($client);
        };
        //customer message
        $container['wechatCustomerMessage'] = function () use ($client) {
            return new WechatCustomizeMessage($client);
        };
        //template message
        $container['wechatTemplateMessage'] = function () use ($client) {
            return new WechatTemplateMessage($client);
        };
        //auth
        $container['wechatAuth'] = function () use ($client) {
            return new WechatAuth($client);
        };
        //media
        $container['wechatMedia'] = function () use ($client) {
            return new WechatMedia($client);
        };
        //tag
        $container['wechatTag'] = function () use ($client) {
            return new WechatTag($client);
        };
        //user
        $container['wechatUser'] = function () use ($client) {
            return new WechatUser($client);
        };
        //blackList
        $container['wechatBlackList'] = function () use ($client) {
            return new WechatBlackList($client);
        };
        //qrCode
        $container['wechatQRCode'] = function () use ($client) {
            return new WechatQRCode($client);
        };
        //url
        $container['wechatUrl'] = function () use ($client) {
            return new WechatUrl($client);
        };

        $this->container = $container;

        return $this;
    }

    public function getCache()
    {
        return $this->container['cache'];
    }

    public function getAccessToken()
    {
        return $this->container['wechatAccessToken'];
    }

    public function getCustomerMenu()
    {
        return $this->container['wechatCustomerMenu'];
    }

    public function getCustomerMessage()
    {
        return $this->container['wechatCustomerMessage'];
    }

    public function getTemplateMessage()
    {
        return $this->container['wechatTemplateMessage'];
    }

    public function getAuth()
    {
        return $this->container['wechatAuth'];
    }

    public function getMedia()
    {
        return $this->container['wechatMedia'];
    }

    public function getTag()
    {
        return $this->container['wechatTag'];
    }

    public function getUser()
    {
        return $this->container['wechatUser'];
    }

    public function getBlackList()
    {
        return $this->container['wechatBlackList'];
    }

    public function getQrCode()
    {
        return $this->container['wechatQRCode'];
    }

    public function getUrl()
    {
        return $this->container['wechatUrl'];
    }
}