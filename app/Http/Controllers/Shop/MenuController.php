<?php

namespace App\Http\Controllers\Shop;

use App\Models\Menu;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MenuController extends BaseController
{

    /**
     * 菜品列表
     */
    public function index()
    {

        //接收参数
        $minPrice = \request()->input('minPrice');
        $maxPrice = \request()->input('maxPrice');
        $keyword = \request()->input('keyword');
        $cateId = \request()->input('cate_id');
        // $query=DB::table('menus');
        $query = Menu::where("shop_id",Auth::user()->shop->id);


        if ($minPrice !== null) {

            $query->where('goods_price', '>=', $minPrice);
        }


        if ($maxPrice !== null) {

            $query->where('goods_price', '<=', $maxPrice);
        }

        if ($keyword !== null) {

            $query->where('goods_name', 'like', "%{$keyword}%");

        }
        if ($cateId !== null) {

            $query->where('cate_id',  $cateId);

        }


        $menus = $query->paginate(1);

        //得到所有分类
        $cates = MenuCategory::where("shop_id",Auth::user()->shop->id)->get();

        //dd($cates);

        return view('shop.menu.index', compact('menus', 'cates'));


    }

    public function add(Request $request)
    {

        if ($request->isMethod("post")) {

            //验证


            //接收参数
            $data=$request->post();
            $data['shop_id']=Auth::user()->shop->id;
            $data['goods_img']=$request->file('goods_img')->store("menu");
            //入库
            Menu::create($data);

            //提示成功
            return redirect()->route('menu.index')->with("success","添加成功");

        }
        //得到分类
        $cates = MenuCategory::where("shop_id",Auth::user()->shop->id)->get();
        return view("shop.menu.add", compact('cates'));
    }

    public function edit()
    {

    }

    public function del($id)
    {

        $menu=Menu::find($id);

        $logo=$menu->goods_img;

     return   Storage::url($logo);
       // dd($logo);

    }


}
