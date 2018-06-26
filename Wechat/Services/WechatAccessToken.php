<?php

namespace Wechat\Services;


use Wechat\Exceptions\WechatException;

class WechatAccessToken extends BaseService
{
    private $cacheKey = "wechat_access_token_cache_key";


    public function getToken($refreshCache = false)
    {
        $cache = $this->client->cache;
        if (!$refreshCache
            && $cache->has($this->getCacheKey())
        ) {
            $data = $cache->get($this->getCacheKey());
            if (empty($data)
                || !isset($data['token'])
                || !isset($data['expire'])
                || empty($data['token'])
                || $data['expire'] < time()
            ) {
                $data = [];
            }
        }
        if (empty($data)) {
            $data = $this->requestToken();
            $cache->set($this->getCacheKey(), $data, $data['expire']);
        }
        return $data['token'];
    }

    private function getCacheKey()
    {
        return md5($this->client->getAppId() . $this->client->getAppName() . $this->cacheKey);
    }

    private function requestToken()
    {
        try {
            $response = $this->get('/cgi-bin/token', [
                'grant_type' => 'client_credential',
                'appid' => $this->client->getAppId(),
                'secret' => $this->client->getAppSecret(),
            ]);
            return [
                'token' => $response['access_token'],
                'expire' => time() + $response['expires_in'] - 300,// cache expire time less five minutes than wechat limit
            ];
        } catch(WechatException $e) {
            //todo failed to get access token
            throw $e;
        }
    }
}