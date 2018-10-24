<?php

namespace App\Http\Controllers\Admin;

use App\Mail\OrderShipped;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ShopController extends BaseController
{
    /**
     * 商家列表
     */
    public function index()
    {
        //得到所有商家
        $shops = Shop::all();

        return view('admin.shop.index', compact('shops'));
    }

    //通过审核
    public function changeStatus($id)
    {

        $shop = Shop::findOrFail($id);
        $shop->status = 1;
        $shop->save();
        return back()->with("success", "通过审核");

    }

    /**
     * 删除店铺
     */
    public function del($id)
    {

        //删除店铺
        $shop = Shop::findOrFail($id)->delete();
        //跳转
        return redirect()->route("admin.shop.index")->with('success', '删除成功');
    }
}
