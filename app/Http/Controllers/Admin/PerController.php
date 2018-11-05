<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PerController extends Controller
{
    //
    public function add(Request $request)
    {

        if ($request->isMethod("post")){


            $data=$request->post();
            $data['guard_name']="admin";
            Permission::create($data);


        }
        return view("admin.per.add");


    }
}
