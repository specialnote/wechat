<?php


namespace Wechat\Services;


class WechatBlackList extends BaseService
{
    public function getList($nextOpenId = null)
    {
        return $this->post('/cgi-bin/tags/members/getblacklist', [
            'access_token' => $this->getAccessToken(),
        ], [
            'begin_openid' => $nextOpenId
        ]);
    }

    public function addList($openIdList)
    {
        return $this->post('/cgi-bin/tags/members/batchblacklist', [
            'access_token' => $this->getAccessToken(),
        ], [
            'openid_list' => $openIdList
        ]);
    }

    public function removeList($openIdList)
    {
        return $this->post('/cgi-bin/tags/members/batchunblacklist', [
            'access_token' => $this->getAccessToken(),
        ], [
            'openid_list' => $openIdList
        ]);
    }
}