<?php

namespace App\Http\Controllers\Api;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Mrgoon\AliSms\AliSms;

class MemberController extends Controller
{
    /**
     * 短信发送
     * @param Request $request
     * @return array
     */
    public function sms(Request $request)
    {
        //1. 接收参数
        $tel = $request->get('tel');

        //2.随机生成验证码  6位
        $code = mt_rand(100000, 999999);

        //3. 把验证码存起来 怎么村？
        //3.1 redis 文件缓存===========redis
        //3.2 怎么存？tel_15211512888====》1234  tel_158566663232====》4321
        //Session>文件缓存>数据库>Redis
        /* Redis::set("tel_".$tel,$code);
         Redis::expire("tel_".$tel,60*5);*/
        Redis::setex("tel_" . $tel, 60*5, $code);
        //Cache::set("tel_",$code,1);

        //4.把验证码发给手机号
        //TODO
        $config = [
            'access_key' => env("ALIYUNU_ACCESS_ID"),//appID
            'access_secret' => env("ALIYUNU_ACCESS_KEY"),//appKey
            'sign_name' => env("ALIYUN_SMS_NAME"),//签名
        ];


        $sms = new AliSms();
        $response = $sms->sendSms($tel, 'SMS_149417370', ['code'=> $code], $config);
       //  dd($response);
        if ($response->Code=="OK"){

            //5. 返回
            $data = [
                "status" => true,
                "message" => "获取短信验证码成功" . $code  //TODO 去测试
            ];


        }else{
            $data = [
                "status" => false,
                "message" => $response->Message
            ];
        }

        return $data;

    }
    /**
     * 用户注册
     */
    public function reg(Request $request)
    {

        //接收参数
        $data = $request->all();
        //创建一个验证规则
        $validate = Validator::make($data, [
            'username' => 'required|unique:members',
            'sms' => 'required|integer|min:1000|max:999999',
            'tel' => [
                'required',
                'regex:/^0?(13|14|15|17|18|19)[0-9]{9}$/',
                'unique:members'
            ],
            'password' => 'required|min:6'
        ]);
        //验证 如果有错
        if ($validate->fails()) {

            //返回错误
            return [
                'status' => "false",
                //获取错误信息
                "message" => $validate->errors()->first()
            ];

        }

        //验证 验证码
        //1.取出验证码
        $code = Redis::get("tel_" . $data['tel']);
       // return $code;
        //2.判断验证码是否和取出的一致
        if ($code != $data['sms']) {
            //返回错误
            return [
                'status' => "false",
                //获取错误信息
                "message" => "验证码错误"
            ];

        }
        //密码加密
        //  $data['password'] = bcrypt($data['password']);
        $data['password'] =Hash::make($data['password']);
        //数据入库
        Member::create($data);
        //返回数据
        return [
            'status' => "true",
            "message" => "添加成功"
        ];




    }

    /**
     * 登录
     */
    public function login(Request $request)
    {

        //1.先通过用户名找哪当前用户
        $member = Member::where("username", $request->post('name'))->first();

        //2.如果用户密码存在 再来验证密码  Hash:check
        //3.如果密码也成功 登录成功
        if ($member && Hash::check($request->post('password'), $member->password)) {


            return [
                'status' => 'true',
                'message' => '登录成功',
                'user_id'=>$member->id,
                'username'=>$member->username,
            ];

        }

        return [
            'status' => 'false',
            'message' => '账号或密码错误'
        ];


    }

    /**
     * 用户信息
     */
    public function detail(Request $request)
    {
        return Member::find($request->get('user_id'));
    }

}
