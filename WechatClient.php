<?php


namespace Wechat;

use Wechat\Interfaces\WechatClientInterface;
use Wechat\Interfaces\WechatResponseInterface;
use Wechat\Traits\SecurityTrail;
use Wechat\Traits\WechatConfigTrait;
use Wechat\Traits\WechatRequestTrait;
use Wechat\Traits\WechatServiceTrait;

class WechatClient extends BaseModel implements WechatClientInterface
{
    use WechatConfigTrait;
    use WechatRequestTrait;
    use SecurityTrail;
    use WechatServiceTrait;

    const WECHAT_CLIENT_GGH = 'dyh';// 公共号-订阅号
    const WECHAT_CLIENT_FWH = 'fwh';// 公共号-服务号
    const WECHAT_CLIENT_XCX = 'xcx';// 公共号-小程序

    const CALLBACK_MESSAGE_TYPE_PLAINTEXT = 'plaintext';//消息传递方式 明文模式
    const CALLBACK_MESSAGE_TYPE_COMPATIBLE = 'compatible';//消息传递方式 兼容模式
    const CALLBACK_MESSAGE_TYPE_SAFETY = 'safety';//消息传递方式 安全模式

    protected $_legalAttributes = [
        'curlProxyHost',
        'curlProxyPort',
        'exportLevel',

        'originalId',
        'appName',
        'wechatClientType',
        'appId',
        'appSecret',

        'mchId',
        'signKey',
        'apiClientCertPemPath',
        'apiClientKeyPemPath',

        'encodingAESKey',
        'callbackMessageType',
        'callbackToken',
    ];

    private $params;
    private $services;

    public function __construct($params, $services)
    {
        $this->params = $params;
        $this->services = $services;
        $this->setAttributes($params);
        $this->loadServices($services);
    }

    public function initByAppName($appName)
    {
        if (isset($this->params[$appName])) {
           return new self($this->params[$appName], $this->services);
        }
        return null;
    }

    public function initByOriginalId($originalId)
    {
        foreach ($this->params as $appName => $config) {
            if (isset($config['originalId']) && $config['originalId'] === $originalId) {
                return  new self($config, $this->services);
            }
        }
        return null;
    }

    public function signatureWechatRequest(WechatRequest $request)
    {
        return $request->get('signature') == $this->signature($this->getCallbackToken(),
            $request->get('timestamp'),
            $request->get('nonce')
        );
    }
}
