<?php


namespace Wechat\Traits;

use Wechat\Exceptions\WechatException;
use Wechat\WechatClient;

/**
 * Trait WechatConfig
 *
 */
trait WechatConfigTrait
{
    //=============== 请求基本配置 ===============
    protected $curlProxyHost = '0.0.0.0';
    protected $curlProxyPort = 0;
    protected $exportLevel = 1;

    //===============  微信账号信息配置  ===============
    protected $appName;
    protected $originalId;
    protected $wechatClientType;
    protected $appId;
    protected $appSecret;

    //===============  微信商户号信息  ===============
    protected $mchId;
    protected $signKey;
    protected $apiClientCertPemPath;
    protected $apiClientKeyPemPath;

    // ===============  微信消息配置  ===============
    protected $callbackToken;
    protected $encodingAesKey;
    protected $callbackMessageType;


    function getAppName()
    {
        return $this->appName;
    }

    function getOriginalId()
    {
        return $this->originalId;
    }

    function getWechatClientType()
    {
        if (in_array($this->wechatClientType, [
            WechatClient::WECHAT_CLIENT_FWH,
            WechatClient::WECHAT_CLIENT_GGH,
            WechatClient::WECHAT_CLIENT_XCX,
        ])) {
            return $this->wechatClientType;
        }
        throw new WechatException('illegal wechat client type');
    }

    function getAppId()
    {
        return $this->appId;
    }

    function getAppSecret()
    {
        return $this->appSecret;
    }

    function getCallbackToken()
    {
        return $this->callbackToken;
    }

    function getEncodingAesKey()
    {
        return $this->encodingAesKey;
    }

    function getCallbackMessageType()
    {
        return $this->callbackMessageType;
    }

    function getMchId()
    {
        return $this->mchId;
    }

    function getSignKey()
    {
        return $this->signKey;
    }

    function getApiClientCertPemPath()
    {
        return $this->apiClientCertPemPath;
    }

    function getApiClientCertKeyPath()
    {
        return $this->apiClientKeyPemPath;
    }
}
