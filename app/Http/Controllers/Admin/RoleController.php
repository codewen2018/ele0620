<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {

        $roles=Role::all();
        return view('admin.role.index',compact('roles'));
    }
    //
    public function add(Request $request)
    {

        if ($request->isMethod("post")){

            //1.接收参数 并处理数据
           $pers=$request->post('per');
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

    /*
   * 角色编辑
   */
    public function edit(Request $request, $id)
    {

        //得到当前角色
        $role = Role::findOrFail($id);

        if ($request->isMethod('post')) {


            // dd($request->post('per'));
            //接收参数
            $data['name'] = $request->post('name');
            //$data['guard_name']="admin";


            //创建角色
            $role->update($data);

            //还给给角色添加权限 $role->syncPermissions(['权限名1','权限名2']);
            $role->syncPermissions($request->post('per'));

            //跳转并提示
            return redirect()->route('role.index')->with('success', '编辑' . $role->name . "成功");


        }

        //得到所有权限
        $pers = Permission::all();

        return view('admin.role.edit', compact('pers', 'role'));

    }
}
