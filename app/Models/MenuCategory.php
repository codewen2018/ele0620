<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MenuCategory
 *
 * @property int $id
 * @property string $name 分类名称
 * @property string|null $type_accumulation 菜品编号
 * @property int $shop_id 所属商铺
 * @property string $description 描述
 * @property string $is_selected 是否默认分类：1是，0否
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $logo
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Menu[] $menus
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereIsSelected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereShopId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereTypeAccumulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MenuCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MenuCategory extends Model
{
    //设置隐藏字段
    public $fillable = ['name', 'type_accumulation', 'shop_id', 'description', 'is_selected','logo'];

    //通过分类找菜品goods_list=====>goodsList
    public function menus(){

      return $this->hasMany(Menu::class,"cate_id");


    }
}
