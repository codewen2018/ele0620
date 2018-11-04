<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('index');
});

Route::get("fuck",function (){
    //1.创建操作微信的对象
    $app = new \EasyWeChat\Foundation\Application(config('wechat'));
    //2.得到支付对象
    $payment = $app->payment;
    //3.生成订单
    //3.1 订单配置
    $attributes = [
        'trade_type'       => 'NATIVE', // JSAPI，NATIVE，APP...
        'body'             => '源码点餐',
        'detail'           => '源码点餐',
        'out_trade_no'     => time(),
        'total_fee'        => 100, // 单位：分
        'notify_url'       => 'http://wenwww.zhilipeng.com/api/order/ok', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
        // 'openid'           => '当前用户的 openid', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
        // ...
    ];
    //3.2 订单生成
    $order = new \EasyWeChat\Payment\Order($attributes);
    //4.统一下单
    $result = $payment->prepare($order);
    return $result;
});
Route::get("test", function () {


    //$content = 'test';//邮件内容
    $shopName="互联网学院";
    $to = 'wjx@itsource.cn';//收件人
    $subject = $shopName.' 审核通知';//邮件标题
    \Illuminate\Support\Facades\Mail::send(
        'emails.shop',
       compact("shopName"),
        function ($message) use($to, $subject) {
            $message->to($to)->subject($subject);
        }
    );


});


Route::domain(env("ADMIN_URL"))->namespace("Admin")->group(function () {
    //region 后台用户
    #后台用户登录
    Route::any('admin/login', "AdminController@login")->name('admin.login');
    #后台用户退出
    Route::get('admin/logout', "AdminController@logout")->name('admin.logout');
    #用户更改密码
    Route::any('admin/changePassword', "AdminController@changePassword")->name('admin.changePassword');
    #后台用户列表
    Route::get('admin/index', "AdminController@index")->name('admin.index');
    #后台用户添加
    Route::any('admin/add', "AdminController@add")->name('admin.add');
    #后台用户删除
    Route::get('admin/del/{id}', "AdminController@del")->name('admin.del');
//endregion
    //region 店铺分类
    //店铺分类 App\Http\Controllers\Admin
    Route::get('shop_category/index', "ShopCategoryController@index")->name('shop_cate.index');
    Route::any('shop_category/add', "ShopCategoryController@add")->name('shop_cate.add');
    Route::get('shop_category/del/{id}', "ShopCategoryController@del")->name('shop_cate.del');
    //endregion
    //region 店铺管理
    #店铺列表
    Route::get('shop/index', "ShopController@index")->name('admin.shop.index');
    Route::any('shop/add/{userId}', "ShopController@add")->name('admin.shop.add');
    #删除店铺
    Route::get('shop/del/{id}', "ShopController@del")->name('admin.shop.del');
    //通过审核
    Route::get('shop/changeStatus/{id}', "ShopController@changeStatus")->name('admin.shop.changeStatus');
    //endregion
    //region 商家用户
    //商家用户管理
    Route::get('user/index', "UserController@index")->name('admin.user.index');
    //endregion
    //region 活动管理
    Route::get('activity/index', "ActivityController@index")->name('admin.activity.index');
    Route::any('activity/add', "ActivityController@add")->name('admin.activity.add');
    //endregion

    //region 权限管理
    Route::get('per/index', "PerController@index")->name('per.index');
    Route::any('per/add', "PerController@add")->name('per.add');

    //endregion

    //region 角色管理
    Route::get('role/index', "RoleController@index")->name('role.index');
    Route::any('role/add', "RoleController@add")->name('role.add');
    Route::any('role/edit/{id}', "RoleController@edit")->name('role.edit');
    Route::get('role/del/{id}', "RoleController@del")->name('role.del');

    //endregion

    //region 抽奖
    Route::get('event/index', "EventController@index")->name('admin.event.index');
    Route::any('event/add', "EventController@add")->name('admin.event.add');
    Route::get('event/open/{id}', "EventController@open")->name('admin.event.open');
    //endregion
    //region 奖品
    Route::get('prize/index', "EventPrizeController@index")->name('admin.prize.index');
    Route::any('prize/add', "EventPrizeController@add")->name('admin.prize.add');
    //endregion
});


Route::domain(env("SHOP_URL"))->namespace("Shop")->group(function () {
    //region 商户首页
    Route::get("index/index", "IndexController@index")->name("shop.index.index");
    //endregion
    //region 用户列表
    Route::get("user/index", "UserController@index")->name("shop.user.index");
    Route::any("user/reg", "UserController@reg")->name("shop.user.reg");
    Route::any("user/login", "UserController@login")->name("shop.user.login");
    //endregion
    //region 店铺管理
    Route::any("shop/add", "ShopController@add")->name("shop.shop.add");
    //endregion
    //region 菜品分类
    Route::get("menu_cate/index", "MenuCategoryController@index")->name('menu_cate.index');
    Route::any("menu_cate/add", "MenuCategoryController@add")->name('menu_cate.add');
    Route::any("menu_cate/edit/{id}", "MenuCategoryController@edit")->name('menu_cate.edit');
    Route::get("menu_cate/del/{id}", "MenuCategoryController@del")->name('menu_cate.del');

    Route::any("menu_cate/upload", "MenuCategoryController@upload")->name('menu_cate.upload');
//endregion
    //region 菜品管理
    Route::get("menu/index", "MenuController@index")->name('menu.index');
    Route::any("menu/add", "MenuController@add")->name('menu.add');
    Route::any("menu/edit/{id}", "MenuController@edit")->name('menu.edit');
    Route::get("menu/del/{id}", "MenuController@del")->name('menu.del');
    Route::any("menu/upload", "MenuController@upload")->name('menu.upload');
//endregion


    //region 订单统计
    Route::get('order/day', "OrderController@day")->name('order.day');
    Route::get('order/index', "OrderController@index")->name('order.index');
    Route::get('order/changeStatus/{id}/{status}', "OrderController@changeStatus")->name('order.changeStatus');
    Route::get('order/detail/{id}', "OrderController@detail")->name('order.detail');
    Route::get('order/menu', "OrderController@menu")->name('order.menu');

//endregion
    //region 抽奖
    Route::get('event/index', "EventController@index")->name('event.index');
    Route::get('event/sign', "EventController@sign")->name('event.sign');

//endregion
});