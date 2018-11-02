<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Menu
 *
 * @property int $id
 * @property string $goods_name 菜品名称
 * @property float $goods_price 价格
 * @property string $description 描述
 * @property string $goods_img 商品图片
 * @property int $shop_id 所属商家ID
 * @property int $cate_id 所属分类ID
 * @property string|null $tips 提示信息
 * @property float $rating 评分
 * @property int $month_sales 月销量
 * @property int $rating_count 评分数量
 * @property int $satisfy_count 满意度数量
 * @property float $satisfy_rate 满意度评分
 * @property int $status 状态：1上架，0下架
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereCateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereGoodsImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereGoodsName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereGoodsPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereMonthSales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereSatisfyCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereSatisfyRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereTips($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Menu whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Menu extends Model
{
    //设置可修改的字段
    public $fillable = ['goods_name', "goods_price", "goods_img", "shop_id", "cate_id", "description", 'tips', 'rating', 'month_sales', 'rating_count', 'satisfy_count', 'satisfy_rate', 'status'];


    /**
     * 获取器
     * @param $value 数据库中原来的值
     * @return string
     */
    public function getGoodsImgAttribute($value)
    {
       return env("ALIYUN_OSS_URL").$value;
    }


}
