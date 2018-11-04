<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EventPrize
 *
 * @property int $id
 * @property int $event_id 活动Id
 * @property string $name 名称
 * @property string $description 奖品详情
 * @property int $user_id 商户Id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventPrize whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventPrize whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventPrize whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventPrize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventPrize whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventPrize whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventPrize whereUserId($value)
 * @mixin \Eloquent
 */
class EventPrize extends Model
{
    //
    public $fillable=['event_id','name','description','user_id'];
}
