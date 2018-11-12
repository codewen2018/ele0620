<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Member;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderGood;
use App\Models\Shop;

use EasyWeChat\Foundation\Application;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Symfony\Component\HttpFoundation\Response;

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

        try {

            //订单入库
            $order = Order::create($data);

            //dd($order);

            //订单商品
            foreach ($carts as $kk => $cart) {

                //得到当前菜品
                $menu = Menu::find($cart->goods_id);

                //判断库存是否充足
                if ($cart->amount > $menu->stock) {

                    //抛出异常
                    throw  new \Exception($menu->goods_name . " 库存不足");
                }

                //减去库存
                $menu->stock = $menu->stock - $cart->amount;
                //保存
                $menu->save();

                OrderGood::insert([
                    'order_id' => $order->id,
                    'goods_id' => $cart->goods_id,
                    'amount' => $cart->amount,
                    'goods_name' => $menu->goods_name,
                    'goods_img' => $menu->goods_img,
                    'goods_price' => $menu->goods_price
                ]);

            }

            //清空购物车
            Cart::where("user_id", $request->post('user_id'))->delete();
            //提交事务
            DB::commit();

        } catch (\Exception $exception) {
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


        //$arr = [-1 => "已取消", 0 => "代付款", 1 => "待发货", 2 => "待确认", 3 => "完成"];
        $data['id'] = $order->id;
        $data['order_code'] = $order->order_code;
        $data['order_birth_time'] = (string)$order->created_at;
        $data['order_status'] = $order->order_status;
        //  $data['order_status'] = $arr[$order->status];
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

        $datas = [];
        foreach ($orders as $order) {
            $data['id'] = $order->id;
            $data['order_code'] = $order->order_code;
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


    /**
     * 订单支付
     */
    public function pay(Request $request)
    {


        // 得到订单
        $order = Order::find($request->post('id'));

        //得到用户
        $member = Member::find($order->user_id);

        //判断钱够不够
        if ($order->total > $member->money) {

            return [
                'status' => 'false',
                "message" => "用户余额不够，请充值"
            ];
        }

        DB::transaction(function () use ($member, $order) {
            //否则扣钱
            $member->money = $member->money - $order->total;
            $member->save();

            //更改订单状态
            $order->status = 1;
            $order->save();
        });

        return [
            'status' => 'true',
            "message" => "支付成功"
        ];
    }

    public function wxPay()
    {
        /*
                $codeUrl="http://www.itsource.cn";
                $qrCode = new QrCode($codeUrl);
                header('Content-Type: ' . $qrCode->getContentType());//设置响应类型
                exit($qrCode->writeString());*/


        /*// Create a basic QR code
                $qrCode = new QrCode("http://www.itsource.cn");
                $qrCode->setSize(250);//大小

        // Set advanced options
                $qrCode
                    ->setMargin(10)//外边框
                    ->setEncoding('UTF-8')//编码
                    ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)//容错级别
                    ->setForegroundColor(['r' => 45, 'g' => 65, 'b' => 0])//码颜色
                    ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])//背景色
                    ->setLabel('微信扫码支付', 16, public_path("font/msyh.ttf"), LabelAlignment::CENTER)
                    ->setLogoPath(public_path("images/logo.png")) //LOGO
                    ->setLogoWidth(100);//LOGO大小

        // Directly output the QR code
                header('Content-Type: '.$qrCode->getContentType());//响应类型
               exit( $qrCode->writeString());*/

//订单ID
        $id = \request()->get("id");
//把订单找出来
        $orderModel = Order::find($id);
        //0.配置
        $options = config("wechat");
        //dd($options);
        $app = new Application($options);

        $payment = $app->payment;
        //1.生成订单
        $attributes = [
            'trade_type' => 'NATIVE', // JSAPI，NATIVE，APP...
            'body' => '源码点餐平台支付',
            'detail' => '源码点餐平台支付11111',
            'out_trade_no' => $orderModel->order_code,
            'total_fee' => $orderModel->total * 100, // 单位：分
            'notify_url' => 'http://www3.zjl1996.cn/api/order/ok', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            // 'openid'           => '当前用户的 openid', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];

        $order = new \EasyWeChat\Payment\Order($attributes);

        //2. 统计下单

        $result = $payment->prepare($order);
        //   dd($result);
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
            //2.1 拿到预支付链接
            $codeUrl = $result->code_url;


            $qrCode = new QrCode($codeUrl);
            $qrCode->setSize(250);//大小
// Set advanced options
            $qrCode
                ->setMargin(10)//外边框
                ->setEncoding('UTF-8')//编码
                ->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH)//容错级别
                ->setForegroundColor(['r' => 45, 'g' => 65, 'b' => 0])//码颜色
                ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255])//背景色
                ->setLabel('微信扫码支付', 16, public_path("font/msyh.ttf"), LabelAlignment::CENTER)
                ->setLogoPath(public_path("images/logo.png"))//LOGO
                ->setLogoWidth(100);//LOGO大小

// Directly output the QR code
            header('Content-Type: ' . $qrCode->getContentType());//响应类型
            exit($qrCode->writeString());


        } else {
            return $result;
        }
    }

    public function status()
    {

        $id = \request()->get("id");

        $order = Order::find($id);


        return [
            "status"=>$order->status
        ];


    }

    //微信异步通知
    public function ok()
    {
        //0.配置
        $options = config("wechat");
        //dd($options);
        $app = new Application($options);
        //1.回调
        $response = $app->payment->handleNotify(function ($notify, $successful) {
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
           // $order = 查询订单($notify->out_trade_no);
            $order=Order::where("order_code",$notify->out_trade_no)->first();

            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->status==1) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }

            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
                //$order->paid_at = time(); // 更新支付时间为当前时间
                $order->status = 1;
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });

        return $response;
    }

    /**
     * 清除超时未支付的订单
     */
    public function clear(){

        //1. 找出需要处理的订单
        /**
         *  当前时间-创建时间>15*60
         * 当前时间>创建时间+15*60
         * 当前时间-15*60>创建时间
         * 创建时时间<当前时间-15*60
         *
         * "2018-12-12 08:12:11"  < time()-15*60=== 123141234=====>date("Y-m-d H:i:s")
         *
         */
       $orders= Order::where("status",0)->where("created_at","<",date("Y-m-d H:i:s",time()-15*60))->get();


       //循环的订单
       foreach ($orders as $order){
           $order->status=-1;
           $order->save();

           //取出当前订单的商品
           $goods=OrderGood::where("order_id",$order->id)->get();
           //退库存
           foreach ($goods as $good){
               $amount=$good->amount;
               $menuId=$good->goods_id;
               //操作menu表
               Menu::where("id",$menuId)->increment("stock",$amount);

           }



       }
       //dd($orders->toArray());

    }
}
