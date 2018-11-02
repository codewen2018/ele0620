<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OrderGood
 *
 * @property int $id
 * @property int $order_id 订单Id
 * @property int $goods_id 商品Id
 * @property int $amount 购买数量
 * @property string $goods_name 商品名称
 * @property string $goods_img 商品图片
 * @property float $goods_price 商品价格
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderGood whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderGood whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderGood whereGoodsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderGood whereGoodsImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderGood whereGoodsName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderGood whereGoodsPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderGood whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderGood whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OrderGood whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderGood extends Model
{
    //
    public $fillable=['order_id','goods_id','amount','goods_name','goods_img','goods_price'];
}
