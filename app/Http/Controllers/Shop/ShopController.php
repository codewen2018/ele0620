<?php

namespace App\Http\Controllers\Shop;

use App\Models\Shop;
use App\Models\ShopCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShopController extends BaseController
{
    public function add(Request $request)
    {
        //判断当前用户是否已有店铺
        if (Auth::user()->shop){
            return redirect()->back()->with("danger","已有店铺不能再创建");
        }
        //判断是不是POST提交
        if ($request->isMethod("post")) {
            //1. 验证
            $this->validate($request, [
                'shop_cate_id' => 'required|integer',
                'shop_name' => 'required|max:100|unique:shops',
                'shop_img' => 'required|image',
                'start_send' => 'required|numeric',
                'send_cost' => 'required|numeric',
                'notice' => 'string',
                'discount' => 'string',
            ]);
            //2. 接收数据
            $data=$request->post();
            //2.1 设置店铺的状态为0 未审核
            $data['status'] = 0;
            //2.2 设置用户ID
            $data['user_id'] = Auth::user()->id;
            //2.3 处理图片
            $data['shop_img'] = "";
            //图片上传
            $file = $request->file('shop_img');
            //判断是否上传了图片
            if ($file) {
                //存在就上传
                $data['shop_img'] =$file->store("shop", 'public');

            }

            //3. 添加数据
            Shop::create($data);

            //添加成功
            session()->flash('success', '添加成功等待平台审核');
            //跳转至添加页面
            return redirect()->route("shop.index.index");
        }
        //得到所有商家分类
        $cates = ShopCategory::where("status", 1)->get();
        //显示视图并赋值
        return view("shop.shop.add", compact('cates'));

    }
}
