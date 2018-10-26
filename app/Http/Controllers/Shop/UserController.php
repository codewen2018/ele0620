<?php

namespace App\Http\Controllers\Shop;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    //
    public function index()
    {

        return view("shop.user.index");
    }

    public function reg(Request $request)
    {
        if ($request->isMethod("post")) {

            //1. 验证
            $this->validate($request, [
                "name" => "required|unique:users",
                "password" => "required|confirmed",
                "email" => "required"
            ]);
            //2. 接收数据
            $data = $request->post();

            //2.1密码加密
            $data['password'] = bcrypt($data['password']);

            //3. 入库
            User::create($data);

            //4. 跳转
            return redirect()->route("shop.user.login")->with("success", "注册成功");


        }

        return view("shop.user.reg");
    }

    public function login(Request $request)
    {


        //判断是否POST提交
        if ($request->isMethod("post")) {
            //验证
            $data = $this->validate($request, [
                'name' => "required",
                'password' => "required"
            ]);
            //验证账号密码
            if (Auth::attempt($data)) {

                //当前登录用户Id
                $user = Auth::user();   //Auth::user()=============User::find(2)
                $shop = $user->shop;
                //通过用户找店铺
                if ($shop) {
                    //如果有店铺 状态 -1 0 1
                    switch ($shop->status) {
                        case -1:
                            //禁用
                            Auth::logout();
                            return back()->withInput()->with("danger", "店铺已禁用");
                            break;
                        case 0:
                            //未审核
                            Auth::logout();
                            return back()->withInput()->with("danger", "店铺还未通过审核");
                            break;
                    }

                } else {
                    //跳转到申请店铺

                    return redirect()->route("shop.shop.add")->with("danger","还未申请店铺");

                }
                // session()->flash("success","登录成功");
                //登录成功
                return redirect()->intended(route("shop.index.index"))->with("success", "登录成功");
            }
        }

        return view("shop.user.login");

    }
}
