<?php

namespace App\Http\Controllers\Admin;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{

    public function index()
    {
        $users = User::all();

        return view('admin.user.index', compact('users'));
    }

    public function del($id)
    {

        DB::transaction(function () use ($id){

            //1. 删除用户
            User::findOrFail($id)->delete();
            //2. 删除用户对应店铺
            Shop::where("user_id", $id)->delete();

        });



        return back()->with("success", "删除成功");
    }
}
