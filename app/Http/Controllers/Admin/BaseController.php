<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class BaseController extends Controller
{
    public function __construct()
    {
        //1.添加中间件 auth:admin
        $this->middleware("auth:admin")->except(["login"]);

        //2. 有没有权限

        $this->middleware(function ($request, \Closure $next){

            //如果没有权限  停在这里
            //1. 得到当前访问地址的路由
            $route=Route::currentRouteName();

            //2.设置一个白名单
            $allow=[
                "admin.login",
                "admin.logout"
            ];
            //2.判断当前登录用户有没有权限
          /*  if (!in_array($route,$allow)){

                if (!Auth::guard("admin")->user()->can($route)){
                    if (Auth::guard("admin")->id()!=1){
                        exit(view("admin.fuck"));
                    }
                }


            }*/



          //要保证在白名单 并且 有权限 而且 Id==1
            if (!in_array($route,$allow) &&!Auth::guard("admin")->user()->can($route) && Auth::guard("admin")->id()!=1){

            /*  echo view("admin.fuck");
              exit;*/
                exit(view("admin.fuck"));
            }

          return $next($request);

        });



    }

}
