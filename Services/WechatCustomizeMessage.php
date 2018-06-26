<?php


namespace Wechat\Services;


use Wechat\Exceptions\WechatException;

class WechatCustomizeMessage extends BaseService
{
    private $receiver;

    /**
     * @param   string $receiver
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    public function haveReceiver()
    {
        return !empty($this->receiver);
    }


    public function sendText($content)
    {
        $attrs = [
            'msgtype' => 'text',
            'text' => [
                'content' => $content,
            ]
        ];

        return $this->send($attrs);
    }

    public function sendImage($mediaId)
    {
        $attrs = [
            'msgtype' => 'image',
            'image' => [
                'media_id' => $mediaId,
            ]
        ];

        return $this->send($attrs);
    }

    public function sendVoice($mediaId)
    {
        $attrs = [
            'msgtype' => 'voice',
            'voice' => [
                'media_id' => $mediaId,
            ]
        ];

        return $this->send($attrs);
    }

    public function sendVideo($mediaId, $title, $description)
    {
        $attrs = [
            'msgtype' => 'video',
            'video' => [
                'media_id' => $mediaId,
                'thumb_media_id' => $mediaId,
                'title' => $title,
                'description' => $description,
            ]
        ];

        return $this->send($attrs);
    }

    public function sendMusic($mediaId, $title, $description, $url, $hqUrl)
    {
        $attrs = [
            'msgtype' => 'music',
            'music' => [
                'title' => $title,
                'description' => $description,
                'musicurl' => $url,
                'hqmusicurl' => $hqUrl,
                'thumb_media_id' => $mediaId,
            ]
        ];

        return $this->send($attrs);
    }

    public function sendNews($articleList)
    {
        $attr = [
            'msgtype' => 'news',
        ];
        $articles = [];
        foreach ($articleList as $article) {
            if (!isset($article['title'])
                || !isset($article['description'])
                || !isset($article['url'])
                || !isset($article['picurl'])
            ) {
                throw new WechatException('illegal news message params');
            }
            $articles[] = [
                'title' => $article['title'],
                'description' => $article['description'],
                'url' => $article['url'],
                'picurl' => $article['picurl'],
            ];
        }

        $attr['news'] = [
            'articles' => $articles,
        ];

        return $this->send($attr);
    }

    public function sendMpNews($mediaId)
    {
        $attrs = [
            'msgtype' => 'mpnews',
            'mpnews' => [
                'media_id' => $mediaId,
            ]
        ];

        return $this->send($attrs);
    }

    public function sendCard($cardId)
    {
        $attrs = [
            'msgtype' => 'wxcard',
            'wxcard' => [
                'card_id' => $cardId,
            ]
        ];

        return $this->send($attrs);
    }

    public function sendMiniProgram($title, $appid, $pagePath, $mediaId)
    {
        $attrs = [
            'msgtype' => 'miniprogrampage',
            'miniprogrampage' => [
                'title' => $title,
                'appid' => $appid,
                'pagepath' => $pagePath,
                'thumb_media_id' => $mediaId,
            ]
        ];

        return $this->send($attrs);
    }

    public function sendCustomerType($attrs)
    {
        if (!is_array($attrs) || empty($attrs) || !isset($attrs['msgtype'])) {
            throw new WechatException('illegal message attributes');
        }
        return $this->send($attrs);
    }

    private function send($attrs)
    {
        if (!$this->haveReceiver()) {
            throw new WechatException('empty receiver');
        }
        $requestData = [
            'touser' => $this->receiver,
        ];

        $requestData += $attrs;
        $accessToken = $this->getAccessToken();
        try {
            $response = $this->post('/cgi-bin/message/custom/send', [
                'access_token' => $accessToken,
            ], $requestData);
            return $response;
        } catch (WechatException $e) {
            if ($e->getCode() === 45015) {
                //todo
                return false;
            }
            throw $e;
        }
    }
}