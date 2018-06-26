<?php


namespace Wechat\Services;


class WechatAuth extends BaseService
{
    public function getCodeUrl($redirectUrl, $scope)
    {
        return "https://open.weixin.qq.com/connect/oauth2/authorize"
            . "?appid=" . $this->client->getAppId()
            . "&redirect_uri=" . urlencode($redirectUrl)
            . "&response_type=code&scope=" . $scope
            . "&state=STATE#wechat_redirect";
    }

    public function getAuthToken($code)
    {
        return $this->get("/sns/oauth2/access_token", [
            "appid" => $this->client->getAppId(),
            "secret" => $this->client->getAppSecret(),
            "code" => $code,
            "grant_type" => "authorization_code"
        ]);
    }
}