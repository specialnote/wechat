<?php


namespace Wechat\Services;


use Wechat\Exceptions\WechatException;

class WechatTemplateMessage extends BaseService
{
    private $receiver;

    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    public function haveReceiver()
    {
        return !empty($this->receiver);
    }

    public function send($templateId, $url, $data, $miniProgram = [])
    {
        $attrs = [
            'touser' => $this->receiver,
            'template_id' => $templateId,
            'url' => $url,
            'data' => $data,
        ];
        if (!empty($miniProgram) && isset($miniProgram['appid']) && isset($miniProgram['pagepath'])) {
            $attrs['miniprogram'] = [
                'appid' => $miniProgram['appid'],
                'pagepath' => $miniProgram['pagepath'],
            ];
        }

        try {
            $response = $this->post('/cgi-bin/message/template/send', [
                'access_token' => $this->getAccessToken(),
            ], $attrs);
            return $response;
        } catch (WechatException $e) {
            //todo
            return false;
        }
    }
}