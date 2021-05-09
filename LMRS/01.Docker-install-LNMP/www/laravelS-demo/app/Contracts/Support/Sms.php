<?php

namespace App\Contracts\Support;

interface Sms
{
    /**
     * 短信验证码发送服务
     *
     * @author AdamSmith <sogaxili@gmail.com>
     * @param string $phone 手机号码
     * @param string $content 短信内容
     * @return mixed
     */
    public function send(string $phone, string $content);
}