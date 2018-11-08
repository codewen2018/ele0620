<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nav extends Model
{
    //


    /**
     * 处理过后一级分类
     * @return mixed
     */

    public static function navs1()
    {
        $admin=\Illuminate\Support\Facades\Auth::guard("admin")->user();
        $navs = self::where("pid", 0)->get();

        //判断是否1号管理员
        if ($admin->id==1){
            return $navs;
        }


        //dump($navs->toArray());
        foreach ($navs as $k1 => $v1) {

            //找出第一个儿子
            $child = self::where("pid", $v1->id)->first();

            //如果没有儿子，把它父亲干掉
            if ($child == null) {

                unset($navs[$k1]);
            }

            //判断当前所有儿子都没有权限 也应该干掉
            $childs=self::where("pid",$v1->id)->get();
            //声明一个变量
            $num=0;
            foreach ($childs as $k2=>$v2){

                //判断当前儿子有没有权限
                if ($admin &&!$admin->can($v2->url)){
                    $num++;
                }
            }

            //判断num和儿子个数一样 干掉父亲
            if ($num==count($childs)){
                unset($navs[$k1]);
            }


        }

        return $navs;
    }
}
