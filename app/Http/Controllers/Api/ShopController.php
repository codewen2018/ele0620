<?php

namespace App\Http\Controllers\Api;

use App\Models\MenuCategory;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    //
    public function index()
    {

        //得到所有店铺 设置状态为1
        $shops = Shop::where("status", 1)->get();

        //  dump($shops->toArray());
        //追加 距离 和时间
        foreach ($shops as $k => $v) {

            //$shops[$k]->shop_img=Storage::url($v->shop_img);
            $shops[$k]->shop_img = env("ALIYUN_OSS_URL") . $v->shop_img;
            $shops[$k]->distance = rand(1000, 5000);
            $shops[$k]->estimate_time = ceil($shops[$k]['distance'] / rand(100, 150));

        }
        // dd($shops->toArray());

        return $shops;


    }

    public function detail()
    {
        $id = request()->get('id');
        $shop = Shop::find($id);

        $shop->shop_img=env("ALIYUN_OSS_URL").$shop->shop_img;
        $shop->service_code = 4.6;

        $shop->evaluate = [
            [
                "user_id" => 12344,
                "username" => "w******k",
                "user_img" => "http=>//www.homework.com/images/slider-pic4.jpeg",
                "time" => "2017-2-22",
                "evaluate_code" => 1,
                "send_time" => 30,
                "evaluate_details" => "不怎么好吃"],


            ["user_id" => 12344,
                "username" => "w******k",
                "user_img" => "http=>//www.homework.com/images/slider-pic4.jpeg",
                "time" => "2017-2-22",
                "evaluate_code" => 4.5,
                "send_time" => 30,
                "evaluate_details" => "很好吃"]


        ];

        $cates=MenuCategory::where("shop_id",$id)->get();

        //当前分类有哪些商品
        foreach ($cates as $k=>$cate){

            $cates[$k]->goods_list=$cate->goodsList;

        }


        $shop->commodity=$cates;

        return $shop;
       // dd($shop->toArray());



    }
}
