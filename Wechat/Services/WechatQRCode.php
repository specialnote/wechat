<?php


namespace Wechat\Services;


class WechatQRCode extends BaseService
{
    public function createTmpCode($key)
    {
        if (preg_match('/^\d{32}$/', $key) && $key != 0) {
            $requestData = [
                "expire_seconds" => 604800,
                "action_name" => "QR_SCENE",
                "action_info" => [
                    "scene" => [
                        "scene_id" => $key
                    ]
                ]
            ];
        } else {
            $requestData = [
                "expire_seconds" => 604800,
                "action_name" => "QR_STR_SCENE",
                "action_info" => [
                    "scene" => [
                        "scene_str" => $key
                    ]
                ]
            ];
        }

        return $this->request($requestData);
    }

    public function createPerpetualCode($id)
    {
        $requestData = [
            'action_name' => "QR_LIMIT_SCENE",
            'action_info' => [
                'scene' => [
                    'scene_id' => $id,
                ]
            ]
        ];

        return $this->request(($requestData));
    }

    private function request($requestData)
    {
        return $this->post('/cgi-bin/qrcode/create', [
            'access_token' => $this->getAccessToken(),
        ], $requestData);
    }
}
