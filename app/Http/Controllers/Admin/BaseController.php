<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        //1.添加中间件 auth:admin
        $this->middleware("auth:admin")->except(["login"]);

    }
}
