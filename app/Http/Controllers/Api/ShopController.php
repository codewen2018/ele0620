<?php

namespace App\Http\Controllers\Api;

use App\Models\MenuCategory;
use App\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class ShopController extends BaseController
{
    //
    public function index(Request $request)
    {


        $keyword=$request->get("keyword");
        if ($keyword){

          $shops = Shop::search($keyword)->get();

          dd($shops->toArray());

        }

        //从Redis取出来的字符串
       // $shopsJson=Redis::get("shop_index");
        $shops=Cache::get("shop_index");

        if(!$shops){
            //得到所有店铺 设置状态为1
            $shops = Shop::where("status", 1)->get();


            //把数据存redis
           // Redis::set("shop_index",json_encode($shops));
            //一定要设置过期时间

            //用Cache来存
            Cache::set("shop_index",$shops,1);
        }






        //  dump($shops->toArray());
        //追加 距离 和时间
        foreach ($shops as $k => $v) {

            //$shops[$k]->shop_img=Storage::url($v->shop_img);
         //   $shops[$k]->shop_img = env("ALIYUN_OSS_URL") . $v->shop_img;
            $shops[$k]['distance'] = rand(1000, 5000);
            $shops[$k]['estimate_time'] = ceil($shops[$k]['distance'] / rand(100, 150));

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

            //取当前分类下所有菜品
            $goods=$cate->menus;
       /*     //拼图片路径
            foreach ($goods as $kk=>$good){
                $goods[$kk]->goods_img=env("ALIYUN_OSS_URL").$good->goods_img;
            }*/
            //追加到分类中
            $cates[$k]->goods_list=$goods;

        }


        $shop->commodity=$cates;

        return $shop;
       // dd($shop->toArray());
    }
}
