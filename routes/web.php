<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::domain("admin.ele.com")->namespace("Admin")->group(function (){

    //商户分类
    Route::get("shopCate/index","ShopCategoryController@index")->name("admin.shopCate.index");




});


Route::domain("shop.ele.com")->namespace("Shop")->group(function (){


//商户首页
    Route::get("index/index","IndexController@index")->name("shop.index.index");

    //商户分类
    Route::get("user/index","UserController@index")->name("shop.user.index");
    Route::get("user/add","UserController@add")->name("shop.user.add");





});