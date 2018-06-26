<?php


namespace Wechat\Traits;


use Wechat\Exceptions\WechatException;
use Wechat\Utils\Aes;
use Wechat\Utils\Xml;

/**
 * Class SecurityTrail
 *
 * @property int    $blockSize
 */
trait SecurityTrail
{
    private $blockSize = 32;

    protected function getAesKey()
    {
        return base64_decode($this->getEncodingAesKey().'=', true);
    }

    /**
     * Encrypt the message and return XML.
     *
     * @param string $xml
     * @param string $nonce
     * @param int    $timestamp
     *
     * @return string
     *
     * @throws WechatException
     */
    public function encrypt($xml, $nonce = null, $timestamp = null)
    {
        try {
            $xml = $this->pkcs7Pad(
                $this->getRandomStr(16) .pack('N', strlen($xml)) .$xml .$this->getAppId()
                , $this->blockSize
            );

            $encrypted = base64_encode(Aes::encrypt(
                $xml,
                $this->getAesKey(),
                substr($this->getAesKey(), 0, 16),
                OPENSSL_NO_PADDING
            ));
        } catch (\Exception $e) {
            throw new WechatException($e->getMessage(), WechatException::ERROR_ENCRYPT_AES);
        }

        !is_null($nonce) || $nonce = substr($this->getAppId(), 0, 10);
        !is_null($timestamp) || $timestamp = time();

        $response = [
            'Encrypt' => $encrypted,
            'MsgSignature' => $this->signature($this->getCallbackToken(), $timestamp, $nonce, $encrypted),
            'TimeStamp' => $timestamp,
            'Nonce' => $nonce,
        ];

        //生成响应xml
        return Xml::build($response);
    }

    /**
     * Decrypt message.
     *
     * @param string $content
     * @param string $msgSignature
     * @param string $nonce
     * @param string $timestamp
     *
     * @return string
     *
     * @throws WechatException
     */
    public function decrypt($content, $msgSignature, $nonce, $timestamp)
    {
        $signature = $this->signature($this->getCallbackToken(), $timestamp, $nonce, $content);
        if ($signature !== $msgSignature) {
            throw new WechatException('Invalid Signature.', WechatException::ERROR_INVALID_SIGNATURE);
        }
        $decrypted = Aes::decrypt(
            base64_decode($content, true),
            $this->getAesKey(),
            substr($this->getAesKey(), 0, 16),
            OPENSSL_NO_PADDING
        );
        $result = $this->pkcs7Unpad($decrypted);
        $content = substr($result, 16, strlen($result));
        $contentLen = unpack('N', substr($content, 0, 4))[1];
        if (trim(substr($content, $contentLen + 4)) !== $this->getAppId()) {
            throw new WechatException('Invalid appId.', WechatException::ERROR_INVALID_APP_ID);
        }
        return substr($content, 4, $contentLen);
    }

    /**
     * Get SHA1.
     *
     * @return string
     *
     * @throws self
     */
    public function signature()
    {
        $array = func_get_args();
        sort($array, SORT_STRING);

        return sha1(implode($array));
    }

    /**
     * PKCS#7 pad.
     *
     * @param string $text
     * @param int    $blockSize
     *
     * @return string
     *
     * @throws WechatException
     */
    public function pkcs7Pad($text, $blockSize)
    {
        if ($blockSize > 256) {
            throw new WechatException('$blockSize may not be more than 256');
        }
        $padding = $blockSize - (strlen($text) % $blockSize);
        $pattern = chr($padding);

        return $text.str_repeat($pattern, $padding);
    }

    /**
     * PKCS#7 unpad.
     *
     * @param string $text
     *
     * @return string
     */
    public function pkcs7Unpad($text)
    {
        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > $this->blockSize) {
            $pad = 0;
        }

        return substr($text, 0, (strlen($text) - $pad));
    }

    /**
     * 随机生成指定位数随机字符串
     * @param int   $len    需要生成字符串的位数
     * @return string 生成的字符串
     */
    function getRandomStr($len = 16)
    {

        $str = "";
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;
        for ($i = 0; $i < $len; $i++) {
            $str .= $strPol[mt_rand(0, $max)];
        }
        return $str;
    }
}
