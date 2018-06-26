<?php


namespace Wechat\Services;


use Wechat\Exceptions\WechatException;

class WechatMedia extends BaseService
{
    public function getAllowedMediaType()
    {
        return [
            'image',
            'voice',
            'video',
            'thumb'
        ];
    }

    public function sendTmpMedia($file, $fileType)
    {
        if (!in_array($fileType, $this->getAllowedMediaType())) {
            throw new WechatException('illegal media type');
        }
        $url = $this->baseUrl . "/cgi-bin/media/upload?access_token=" . $this->getAccessToken() . "&type=" . $fileType;
        $response = $this->client->requestPostFile($url, $file);
        return $this->getResponseJsonData($response);
    }

    public function getTmpMedia($mediaId, $fileType)
    {
        if (!in_array($fileType, $this->getAllowedMediaType())) {
            throw new WechatException('illegal media type');
        }
        $baseUrl = $this->baseUrl;
        if ($fileType === 'video') {
            $baseUrl = str_replace('https://', 'http://', $baseUrl);
        }
        $url = $baseUrl . '/cgi-bin/media/get';
        $response = $this->client->requestGet($url, [
            'access_token' => $this->getAccessToken(),
            'media_id' => $mediaId
        ]);
        return $response;
    }
}