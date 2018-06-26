<?php


namespace Wechat\Interfaces;


use Wechat\WechatClient;
use Wechat\WechatRequest;

interface WechatResponseInterface
{
    public function __construct(WechatClient $client, WechatRequest $request);

    public function response();
}