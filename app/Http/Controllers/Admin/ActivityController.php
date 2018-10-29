<?php

namespace App\Http\Controllers\Admin;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    /**
     * 列表
     */
    public function index()
    {
        $acts=Activity::all();

        return view('admin.activity.index',compact('acts'));
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')){

            //验证
            $this->validate($request,[
               'title'=>"required",
               'content'=>"required",
               'start_time'=>"required",
               'end_time'=>"required",
            ]);

            $data=$request->post();
            var_dump($data);
            $data['start_time']=strtotime($data['start_time']);
            $data['end_time']=strtotime($data['end_time']);
           dd($data);
            //添加数据
            Activity::create($data);
            //返回并提醒
            return redirect()->route("admin.activity.index")->with("success","添加成功")->withInput();

        }

        return view('admin.activity.add');

    }
}
