<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Mrgoon\AliSms\AliSms;

class MemberController extends Controller
{
    //
    public function reg()
    {

        //1.验证验证码是否对？
        //1.1 通过手机号把验证码取出来
        //1.2 再和输入的对比
        // Redis::get("tel_".$tel);

    }


    public function sms(Request $request)
    {
        //1. 接收参数
        $tel = $request->get('tel');

        //2.随机生成验证码  6位
        $code = mt_rand(100000, 999999);

        //3. 把验证码存起来 怎么村？
        //3.1 redis 文件缓存===========redis
        //3.2 怎么存？tel_15211512888====》1234  tel_158566663232====》4321
        /* Redis::set("tel_".$tel,$code);
         Redis::expire("tel_".$tel,60*5);*/
        Redis::setex("tel_" . $tel, 5, $code);
        Cache::set("tel_",$code,1);

        //4.把验证码发给手机号
        //TODO
        $config = [
            'access_key' => env("ALIYUNU_ACCESS_ID"),//appID
            'access_secret' => env("ALIYUNU_ACCESS_KEY"),//appKey
            'sign_name' => '张燕',//签名
        ];


        $sms = new AliSms();
        //  $response = $sms->sendSms($tel, 'SMS_149417370', ['code'=> $code], $config);

        //5. 返回
        $data = [
            "status" => true,
            "message" => "获取短信验证码成功" . $code
        ];

        return $data;

    }

    public function login()
    {
        //1.接收用户名和密码

        //2. 判断用户名是否存在

        //3. 再判断密码是否正确
        //Hash::check()
    }
}
