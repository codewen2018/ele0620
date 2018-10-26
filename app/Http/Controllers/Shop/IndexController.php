<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends BaseController
{
    public function index(){

      /*  //判断是否拥有店铺
        if(Auth::user()->shop===null){
            //跳转到添加店铺
            return redirect()->route("shop.shop.add")->with("danger","你还没有创建店铺");
        }*/


        return view("shop.index.index");

    }
}
