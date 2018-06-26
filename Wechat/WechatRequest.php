<?php


namespace Wechat;

/**
 * Class WechatRequest
 *
 */
class WechatRequest extends BaseModel
{
    public $isEncrypted = false;

    protected $_legalAttributes = [
        //========= get 参数 =========
        'signature',
        'timestamp',
        'nonce',
        'echostr',
        'encryptType',
        'msgSignature',

        //========= post xml 公共参数 =========
        'ToUserName',
        'FromUserName',
        'CreateTime',
        'MsgType',
        'MsgId',

        //========= post xml 不同类型的消息自定义参数 =========
        'Content',
    ];
    protected $_allowedMsgType = [
        'text',
        'image',
        'voice',
        'video',
        'location',
        'link'
    ];


    /**
     * 判断请求是否客服
     *
     * @return bool
     */
    function isLegal()
    {
        return !empty($this->get('ToUserName'))
            && !empty($this->get('FromUserName'))
            && !empty($this->get('CreateTime'))
            && !empty($this->get('MsgType'))
            && !empty($this->get('MsgId'))
            && in_array($this->get('MsgType'), $this->_allowedMsgType)
            ;
    }

    /**
     * 保存微信 post 请求数据
     * @param $attributes
     */
    public function setRequestData($attributes)
    {
        if (is_array($attributes)) {
            foreach ($attributes as $attribute => $value) {
                $this->$attribute = $value;
            }
        }
    }

    /**
     * 将当前请求标记为加密请求
     *
     * @param bool  $isEncrypted
     */
    public function isEncrypted($isEncrypted = true)
    {
        $this->isEncrypted = $isEncrypted ? true : false;
    }
}