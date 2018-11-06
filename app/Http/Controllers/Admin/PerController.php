<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

class PerController extends Controller
{
    //
    public function add(Request $request)
    {

        //声明一个空数组用来装路由名字
        $urls=[];
        //得到所有路由
        $routes = Route::getRoutes();
        //循环得到单个路由
        foreach ($routes as $route) {
           //判断命名空间是 后台的
            if ($route->action["namespace"]=="App\Http\Controllers\Admin"){
                //取别名存到$urls中
                $urls[]=$route->action['as'];
            }
        }

        //从数据库取出已经存在的
        $pers=Permission::pluck("name")->toArray();

        //已经存在的从$urls中去掉
        $urls=array_diff($urls,$pers);





        if ($request->isMethod("post")) {


            $data = $request->post();
            $data['guard_name'] = "admin";
            Permission::create($data);

            return redirect()->refresh();


        }
        return view("admin.per.add",compact("urls"));


    }
}
