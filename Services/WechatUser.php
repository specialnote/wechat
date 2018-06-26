<?php


namespace Wechat\Services;


use Wechat\Exceptions\WechatException;
use Wechat\WechatClient;

class WechatUser extends BaseService
{
    public function remark($openId, $remark)
    {
        if ($this->client->getWechatClientType() !== WechatClient::WECHAT_CLIENT_FWH) {
            throw new WechatException('not allowed to use this function');
        }

        return $this->post('/cgi-bin/user/info/updateremark', [
            'access_token' => $this->getAccessToken(),
        ],[
            'openid' => $openId,
            'remark' => $remark,
        ]);
    }

    public function getInfo($openId)
    {
        return $this->get('/cgi-bin/user/info', [
            'access_token' => $this->getAccessToken(),
            'openid' => $openId,
            'lang' => 'zh_CN',
        ]);
    }

    public function getInfoOfUserList($openIdList)
    {
        if (empty($openIdList) || count($openIdList) > 100) {
            throw new WechatException('illegal openId list');
        }
        $userList = [];
        foreach ($openIdList as $openId) {
            $userList[] = [
                'openid' => $openId,
                'lang' => 'zh_CN',
            ];
        }
        return $this->post('/cgi-bin/user/info/batchget', [
            'access_token' => $this->getAccessToken(),
        ], [
            'user_list' => $userList,
        ]);
    }

    public function getUserList($nextOpenId = null)
    {
        return $this->get('/cgi-bin/user/get', [
            'access_token' => $this->getAccessToken(),
            'next_openid' => $nextOpenId,
        ]);
    }
}