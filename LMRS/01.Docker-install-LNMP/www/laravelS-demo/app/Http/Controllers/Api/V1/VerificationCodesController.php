<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Request\Api\V1\VerificationCodesRequest;
use App\Support\Sms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class VerificationCodesController extends Controller
{
    /**
     * 验证码前缀
     *
     * @var string
     */
    protected $temp = "您好，您的验证码是：";

    /**
     * 存在redis中，记录手机号一定时间内发了几次
     *
     * @var string
     */
    protected $key;

    public function store(VerificationCodesRequest $request, Sms $sms)
    {
        $phone = $request->phone;     

        $this->key = "sms:code::{$phone}";
        $phoneNum = $this->phoneNum();
        if (!$phoneNum["restful"]) {
            return response()->json($phoneNum); 
        }
        
        $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
        try {
            $restful = $sms->send($phone, $this->temp.$code);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
        // 缓存手机号和验证码
        $key = "sms::code::verification::".$phone."::".str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
        $exp = now()->addMinutes(3);
        Cache::put($key, ["phone" => $phone, "code" => $code], $exp);
        return response()->json([
            'key' => $key,
            'expired_at' => $exp->toDateTimeString(),
            'restful' => true,
            'message' => $restful,
        ]);
    }

    public function phoneNum()
    {
        try {
            $is_exists = Redis::set($this->key, 1, "EX", 60, "NX"); // NX 先查询key存不存在Redis中，如果存在则不设置    
            if ($is_exists != null || Redis::incr($this->key) <= 3) {
                $restful = [
                    "restful" => true,
                ];
            } else {
                $restful = [
                    "restful" => false,
                    "message" => "获取验证码超过了3次，请在1分钟之后再进行尝试！",
                ];
            }
            return $restful;
        } catch (\Throwable $th) {
            // 记录错误日志  
            $restful = [
                "restful" => false,
                "message" => "系统异常，请稍后再尝试！",
            ];
            return $restful;
        }
    }
}
