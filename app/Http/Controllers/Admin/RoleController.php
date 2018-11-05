<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    //
    public function add(Request $request)
    {

        if ($request->isMethod("post")){

            //1.接收参数 并处理数据
           $pers=$request->post('pers');
            //2.添加角色
            $role=Role::create([
                "name"=>$request->post("name"),
                "guard_name"=>"admin"
            ]);
            //3. 给角色同步权限
            if ($pers){
                $role->syncPermissions($pers);
            }






        }


        //得到所有权限
        $pers = Permission::all();


        return view("admin.role.add",compact("pers"));

    }
}
