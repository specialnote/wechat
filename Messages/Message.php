<?php


namespace Wechat\Messages;

use Wechat\Utils\Xml;


/**
 * 微信相应消息基类
 *
 * Class Message
 *
 * @property    string      $ToUserName
 * @property    string      $FromUserName
 * @property    string      $CreateTime
 * @property    string      $MsgType
 */
abstract class Message
{
    public $ToUserName;
    public $FromUserName;
    public $CreateTime;
    public $MsgType;

    public function __construct($toUserName, $fromUserName, $msgType)
    {
        $this->ToUserName = $toUserName;
        $this->FromUserName = $fromUserName;
        $this->MsgType = $msgType;
        $this->CreateTime = time();
    }

    /**
     * @return array
     */
    abstract function toArray();

    /**
     * @return string
     */
    public function toXml()
    {
        return Xml::build($this->toArray());
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}