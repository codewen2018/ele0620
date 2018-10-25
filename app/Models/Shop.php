<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Shop
 *
 * @property int $id
 * @property int $shop_cate_id
 * @property string $shop_name 店铺名称
 * @property string $shop_img 店铺图片
 * @property float|null $shop_rating 评分
 * @property int|null $brand 是否品牌
 * @property int|null $on_time 是否准时送达
 * @property int|null $fengniao 是否蜂鸟配送
 * @property int|null $bao 是否保标记
 * @property int|null $piao 是否票标记
 * @property int|null $zhun 是否准标记
 * @property float $start_send 起送金额
 * @property float $send_cost 配送费
 * @property string $notice 店公告
 * @property string $discount 优惠信息
 * @property int $status 状态:1正常,0待审核,-1禁用
 * @property int $user_id 用户Id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ShopCategory $cate
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereBao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereFengniao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereNotice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereOnTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop wherePiao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereSendCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereShopCateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereShopImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereShopName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereShopRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereStartSend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Shop whereZhun($value)
 * @mixin \Eloquent
 */
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
