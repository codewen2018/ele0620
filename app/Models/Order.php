<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $user_id 用户Id
 * @property int $shop_id 商家ID
 * @property string $order_code 订单编号
 * @property string $provence 省份
 * @property string $city 市
 * @property string $area 区县
 * @property string $detail_address 详细地址
 * @property string $tel 收货人手机号
 * @property string $name 收货人姓名
 * @property float $total 总价
 * @property int $status 状态(-1:已取消,0:待支付,1:待发货,2:待确认,3:完成)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $order_status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderGood[] $goods
 * @property-read \App\Models\Shop $shop
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereDetailAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereOrderCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereProvence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    //声明静态属性
    static public $statusText = [-1 => "已取消", 0 => "代付款", 1 => "待发货", 2 => "待确认", 3 => "完成"];
    public $fillable = ['user_id', 'shop_id', 'order_code', 'provence', 'city', 'area', 'detail_address', 'tel', 'name', 'total', 'status'];

    /**
     * 读取器
     * @return mixed
     * order_status
     */
    /*public function getOrderStatusAttribute()
    {
        $arr = [-1 => "已取消", 0 => "代付款", 1 => "待发货", 2 => "待确认", 3 => "完成"];
        return $arr[$this->status];
    }*/

  /*  public function getStatusAttribute($value)
    {
        $arr = [-1 => "已取消", 0 => "代付款", 1 => "待发货", 2 => "待确认", 3 => "完成"];
        return $arr[$value];
    }*/


  //order_status  不存在的字段
    public function getOrderStatusAttribute()
    {
         //$arr = [-1 => "已取消", 0 => "代付款", 1 => "待发货", 2 => "待确认", 3 => "完成"];
        return self::$statusText[$this->status];//-1 0 1 2 3
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, "shop_id");
    }

    public function goods()
    {
        return $this->hasMany(OrderGood::class, "order_id");
    }
}
