<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get("shop/index","Api\ShopController@index");
Route::get("shop/detail","Api\ShopController@detail");
Route::get("member/sms","Api\MemberController@sms");
Route::post("member/login","Api\MemberController@login");
Route::post("member/reg","Api\MemberController@reg");
Route::get("member/detail","Api\MemberController@detail");