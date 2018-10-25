<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ShopCategory
 *
 * @property int $id
 * @property string $name 名称
 * @property string $img 图片
 * @property int $status 状态：1 显示 0 隐藏
 * @property int $sort 排序
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShopCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShopCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShopCategory whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShopCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShopCategory whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShopCategory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ShopCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ShopCategory extends Model
{
    //
}
