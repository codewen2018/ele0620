<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    //设置可修改字段
    protected $fillable = ['shop_name', 'shop_img', 'shop_rating', 'brand', 'on_time',
        'fengniao', 'bao', 'piao', 'zhun', 'start_send', 'send_cost', 'notice', 'discount', 'shop_cate_id','status','user_id'];

    public function cate()
    {
        return $this->belongsTo(ShopCategory::class,"shop_cate_id");
    }
    public function user(){
        return $this->belongsTo(User::class,"user_id");
    }
}
