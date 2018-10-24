<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends BaseController
{
    //
    public function index(){

        return view("shop.user.index");
    }

    public function add(){

        return view("shop.user.add");
    }
}
