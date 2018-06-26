<?php


namespace Wechat\Messages;

/**
 * Class TextMessage
 *
 * @property    string      $Content
 */
class TextMessage extends Message
{
    public $Content;

    public function __construct($toUserName, $fromUserName, $content)
    {
        $msgType = 'text';
        $this->Content = $content;
        parent::__construct($toUserName, $fromUserName, $msgType);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'ToUserName' => $this->ToUserName,
            'FromUserName' => $this->FromUserName,
            'CreateTime' => $this->CreateTime,
            'MsgType' => $this->MsgType,
            'Content' => $this->Content,
        ];
    }
}