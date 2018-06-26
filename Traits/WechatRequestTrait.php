<?php

namespace Wechat\Traits;

trait WechatRequestTrait
{
    /**
     * Send a normal POST request using cURL
     * @param string $url to request
     * @param array $postData values to send
     * @param array $options for cURL
     * @return string
     */
    function requestPost($url, array $postData = [], array $options = [])
    {
        $defaults = array(
            CURLOPT_POST => 1,//to do a regular HTTP POST. This POST is the normal application/x-www-form-urlencoded kind, most commonly used by HTML forms.
            CURLOPT_FRESH_CONNECT => 1,//to force the use of a new connection instead of a cached one
            CURLOPT_FORBID_REUSE => 1,//to force the connection to explicitly close when it has finished processing, and not be pooled for reuse
            CURLOPT_POSTFIELDS => http_build_query($postData)
        );

        return $this->request($url, ($options + $defaults));
    }

    /**
     * Send a JSON POST request using cURL
     * @param string $url to request
     * @param array $postData values to send
     * @param array $options for cURL
     * @return string
     */
    function requestPostJson($url, array $postData = [], array $options = [])
    {
        $postJson = json_encode($postData, JSON_UNESCAPED_UNICODE);
        $defaults = array(
            CURLOPT_FRESH_CONNECT => 1,//to force the use of a new connection instead of a cached one
            CURLOPT_FORBID_REUSE => 1,//to force the connection to explicitly close when it has finished processing, and not be pooled for reuse
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postJson),
            ],
            CURLOPT_POSTFIELDS => $postJson
        );

        return $this->request($url, ($options + $defaults));
    }

    /**
     * Send a GET request using cURL
     * @param string $url to request
     * @param array $getParams values to send
     * @param array $options for cURL
     * @return string
     */
    function requestGet($url, array $getParams = [], array $options = [])
    {
        $url = $url . (strpos($url, '?') === FALSE ? '?' : '') . http_build_query($getParams);
        return $this->request($url, $options);
    }

    /**
     * Send a GET request using cURL
     * @param string $url to request
     * @param string $filePath the file to be uploaded
     * @param array $options for cURL
     * @return string
     */
    function requestPostFile($url, $filePath, $options = [])
    {
        $defaults = array(
            CURLOPT_POST => 1,//to do a regular HTTP POST. This POST is the normal application/x-www-form-urlencoded kind, most commonly used by HTML forms.
            CURLOPT_FRESH_CONNECT => 1,//to force the use of a new connection instead of a cached one
            CURLOPT_FORBID_REUSE => 1,//to force the connection to explicitly close when it has finished processing, and not be pooled for reuse
            CURLOPT_POSTFIELDS => [
                'media' => new \CURLFile(realpath($filePath))
            ]
        );

        return $this->request($url, ($options + $defaults));
    }

    /**
     * @param $url
     * @param $options
     * @return mixed|string
     */
    private function request($url, $options)
    {
        $defaults = [
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 0,//to include the header in the output
            CURLOPT_RETURNTRANSFER => 1,//to return the transfer as a string of the return value of curl_exec() instead of outputting it directly
            CURLOPT_TIMEOUT => 10,//The maximum number of seconds to allow cURL functions to execute
        ];
        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        $result = curl_exec($ch);
        if (false === $result) {
            return json_encode([
                'errcode' => curl_errno($ch),
                'errmsg' => curl_error($ch),
            ]);
        }
        curl_close($ch);
        return $result;
    }
}
