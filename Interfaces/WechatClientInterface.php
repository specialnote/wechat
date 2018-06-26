<?php


namespace Wechat\Interfaces;


use Wechat\WechatRequest;

interface WechatClientInterface
{
    /**
     * WechatServiceInterface constructor.
     *
     *  params 格式为
     *  [
     *      'appName' => [
     *          'appName' => '',
     *          'originalId' => '',
     *          'wechatClientType' => '',
     *          'appId' => '',
     *          'appsecret' => '',
     *
     *          'encodingAESKey' => '',
     *          'callbackMessageType' => '',
     *          'callbackToken' => '',
     *
     *          'mchId' => '',
     *          'signKey' => '',
     *          'apiClientCertPemPath' => '',
     *          'apiClientKeyPemPath' => '',
     *      ]
     * ]
     *
     * @param array $params  微信公共号、小程序相关全局配置
     *
     * services 格式为 [
     *      'cache' => Psr\SimpleCache\CacheInterface
     * ]
     * @param array $services 需要注入的自定义服务
     */
    public function __construct($params, $services);

    /**
     * 根据微信 originalId 从配置文件中获取相应的配置，然后实例化出 client
     * @param string $originalId
     * @return self
     */
    public function initByOriginalId($originalId);

    /**
     * 根据自定义微信 appName 从配置文件中获取相应的配置，然后实例化出 client
     * @param string $appName
     * @return self
     */
    public function initByAppName($appName);

    /**
     * 验证微信请求签名
     * @return bool
     */
    public function signatureWechatRequest(WechatRequest $request);
}
