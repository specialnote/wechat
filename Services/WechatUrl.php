<?php


namespace Wechat\Services;


class WechatUrl extends BaseService
{
    public function toShort($longUrl)
    {
        return $this->post('/cgi-bin/shorturl', [
            'access_token' => $this->getAccessToken(),
        ], [
            'action' => 'long2short',
            'long_url' => $longUrl,
        ]);
    }
}