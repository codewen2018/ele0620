<?php

namespace App\Http\Controllers\Shop;

use App\Models\Order;
use App\Models\OrderGood;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Type;

class OrderController extends BaseController
{
    //

    public function index()
    {

    }

    // 按天统计
    public function day(){

//SELECT DATE_FORMAT(created_at,'%Y-%m-%d') as date,COUNT(*) as nums,SUM(total) FROM `orders` WHERE shop_id=3 GROUP BY date;
        $shopId=Auth::user()->shop->id;

      $data=  Order::where("shop_id",$shopId)
            ->select(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') as date,COUNT(*) as nums,SUM(total) as money"))
            ->groupBy('date')
            ->get();

      dd($data->toArray());


    }

    public function months(){
    }

    public function menu(){

        //1.找到当前店铺所有订单 ID
      $ids=  Order::where("shop_id",Auth::user()->shop->id)->pluck("id");

      $data= OrderGood::select(DB::raw('SUM(amount) as nums'))->whereIn("order_id",$ids)->get();
      dd($data->toArray());

    }
}
