<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Address
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property string $provence 省份
 * @property string $city 市
 * @property string $area 区县
 * @property string $detail_address 详细地址
 * @property string $tel 手机号
 * @property string $name 姓名
 * @property int|null $is_default 默认:0否 1是
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereDetailAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereProvence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereTel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Address whereUserId($value)
 * @mixin \Eloquent
 */
class Address extends Model
{
    //
    public $fillable=['name','user_id','tel','provence','city','area','detail_address','is_default'];
}
