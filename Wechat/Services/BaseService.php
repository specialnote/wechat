<?php

namespace Wechat\Services;


use Wechat\Exceptions\WechatException;
use Wechat\WechatClient;

/**
 * Class BaseService
 *
 * @property    WechatClient $client
 */
class BaseService
{
    protected $client;
    protected $baseUrl = "https://api.weixin.qq.com";

    public function __construct(WechatClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $path
     * @param array $params
     * @return array
     * @throws WechatException
     */
    protected function get($path, array $params)
    {
        $url = $this->baseUrl . $path;
        $response = $this->client->requestGet($url, $params);
        return $this->getResponseJsonData($response);
    }

    /**
     * @param string $path
     * @param array $params
     * @param array $requestData
     * @return array
     * @throws WechatException
     */
    protected function post($path, array $params, $requestData)
    {
        $url = $this->baseUrl . $path . "?" . http_build_query($params);
        $response = $this->client->requestPostJson($url, $requestData);
        return $this->getResponseJsonData($response);
    }

    protected function getResponseJsonData($responseString)
    {
        $responseData = json_decode($responseString, true);
        if (empty($responseData)) {
            throw new WechatException('illegal response');
        }
        if (isset($responseData['errcode']) && $responseData['errcode'] !== 0) {
            //todo request fail
            throw new WechatException($responseData['errmsg'], $responseData['errcode']);
        }
        return $responseData;
    }

    protected function getAccessToken()
    {
        if (empty($this->client)) {
            return new WechatException('illegal wechat service');
        }
        return $this->client->accessToken->getToken();
    }
}