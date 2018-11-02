<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Member;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderGood;
use App\Models\Shop;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * 添加订单
     */
    public function add(Request $request)
    {

        //1.查出收货地址
        $address = Address::find($request->post('address_id'));
        //2.判断地址是否有误
        if ($address === null) {
            return [
                "status" => "false",
                "message" => "地址选择不正确"
            ];
        }

        //3.分别赋值
        //3.1用户Id
        $data['user_id'] = $request->post('user_id');
        //3.2 店铺Id

        $carts = Cart::where("user_id", $request->post('user_id'))->get();
        //先找购物车第一条数据的商品ID，再通过商品ID在菜品中找出shop_id
        $shopId = Menu::find($carts[0]->goods_id)->shop_id;

        $data['shop_id'] = $shopId;

        //3.3 订单号生成 1811020931051002
        $data['order_code'] = date("ymdHis") . rand(1000, 9999);
        //3.4 地址
        $data['provence'] = $address->provence;
        $data['city'] = $address->city;
        $data['area'] = $address->area;
        $data['detail_address'] = $address->detail_address;
        $data['tel'] = $address->tel;
        $data['name'] = $address->name;

        //3.5 算总价
        $total = 0;

        foreach ($carts as $k => $v) {
            $good = Menu::where('id', $v->goods_id)->first();


            //算总价
            $total += $v->amount * $good->goods_price;
        }
        $data['total'] = $total;
        //3.6 状态 等待支付
        $data['status'] = 0;



        //启动事务
        DB::beginTransaction();

        try{

            //订单入库
            $order= Order::create($data);

            //dd($order);

            //订单商品
            foreach ($carts as $kk=>$cart){

                //得到当前菜品
                $menu=Menu::find($cart->goods_id);

                //判断库存是否充足
                if ($cart->amount>$menu->stock){

                    //抛出异常
                    throw  new \Exception($menu->goods_name." 库存不足");
                }

                //减去库存
                $menu->stock=$menu->stock-$cart->amount;
                //保存
                $menu->save();

                OrderGood::insert([
                    'order_id'=>$order->id,
                    'goods_id'=>$cart->goods_id,
                    'amount'=>$cart->amount,
                    'goods_name'=>$menu->goods_name,
                    'goods_img'=>$menu->goods_img,
                    'goods_price'=>$menu->goods_price
                ]);

            }

            //清空购物车
            Cart::where("user_id",$request->post('user_id'))->delete();
            //提交事务
            DB::commit();

        }catch (\Exception $exception){
            //回滚
            DB::rollBack();
            return [
                "status" => "false",
                "message" => $exception->getMessage(),
            ];
        }


        return [
            "status" => "true",
            "message" => "添加成功",
            "order_id" => $order->id
        ];
    }

    /**
     * 订单详情
     */
    public function detail(Request $request)
    {

        $order = Order::find($request->input('id'));

        $data['id'] = $order->id;
        $data['order_code'] = $order->sn;
        $data['order_birth_time'] = (string)$order->created_at;
        $data['order_status'] = $order->order_status;
        $data['shop_id'] = $order->shop_id;
        $data['shop_name'] = $order->shop->shop_name;
        $data['shop_img'] = $order->shop->shop_img;
        $data['order_price'] = $order->total;
        $data['order_address'] = $order->provence . $order->city . $order->area . $order->detail_address;

        $data['goods_list'] = $order->goods;


        return $data;
//        dump($data);


    }


    /**
     * 订单列表
     */
    public function index(Request $request)
    {

        $orders = Order::where("user_id", $request->input('user_id'))->get();

        $datas=[];
        foreach ($orders as $order) {
            $data['id'] = $order->id;
            $data['order_code'] = $order->sn;
            $data['order_birth_time'] = (string)$order->created_at;
            $data['order_status'] = $order->order_status;
            $data['shop_id'] = (string)$order->shop_id;
            $data['shop_name'] = $order->shop->shop_name;
            $data['shop_img'] = $order->shop->shop_img;
            $data['order_price'] = $order->total;
            $data['order_address'] = $order->provence . $order->city . $order->area . $order->detail_address;

            $data['goods_list'] = $order->goods;

            $datas[] = $data;
        }

        return $datas;
    }
}
