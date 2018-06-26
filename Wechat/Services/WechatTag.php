<?php


namespace Wechat\Services;


use Wechat\Exceptions\WechatException;

class WechatTag extends BaseService
{
    function create($tagName)
    {
        if (mb_strlen($tagName, 'UTF-8') > 30) {
            throw new WechatException('tag name is too long');
        }
        return $this->post('/cgi-bin/tags/create', [
            'access_token' => $this->getAccessToken(),
        ], [
            'tag' => [
                'name' => $tagName,
            ]
        ]);
    }

    function getTags()
    {
        return $this->get('/cgi-bin/tags/get', [
            'access_token' => $this->getAccessToken(),
        ]);
    }

    function update($id, $tagName)
    {
        if (mb_strlen($tagName, 'UTF-8') > 30) {
            throw new WechatException('tag name is too long');
        }
        return $this->post('/cgi-bin/tags/update', [
            'access_token' => $this->getAccessToken(),
        ], [
            'tag' => [
                'id' => $id,
                'name' => $tagName,
            ]
        ]);
    }

    function delete($id)
    {
        return $this->post('/cgi-bin/tags/delete', [
            'access_token' => $this->getAccessToken(),
        ], [
            'tag' => [
                'id' => $id,
            ]
        ]);
    }

    public function getUserList($tagId, $nextOpenId = null)
    {
        return $this->post('/cgi-bin/user/tag/get', [
            'access_token' => $this->getAccessToken(),
        ],[
            'tagid' => $tagId,
            'next_openid' => $nextOpenId,
        ]);
    }

    public function bindTag($tagId, $openIdList)
    {
        return $this->post('/cgi-bin/tags/members/batchtagging', [
            'access_token' => $this->getAccessToken(),
        ],[
            'tagid' => $tagId,
            'openid_list' => $openIdList,
        ]);
    }

    public function unbindTag($tagId, $openIdList)
    {
        return $this->post('/cgi-bin/tags/members/batchuntagging', [
            'access_token' => $this->getAccessToken(),
        ],[
            'tagid' => $tagId,
            'openid_list' => $openIdList,
        ]);
    }

    public function getTagsOfUser($openId)
    {
        return $this->post('/cgi-bin/tags/getidlist', [
            'access_token' => $this->getAccessToken(),
        ],[
            'openid' => $openId,
        ]);
    }

}