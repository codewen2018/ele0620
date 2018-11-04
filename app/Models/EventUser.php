<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EventUser
 *
 * @property int $id
 * @property int $event_id 活动Id
 * @property int $user_id 商户Id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventUser whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventUser whereUserId($value)
 * @mixin \Eloquent
 */
class EventUser extends Model
{
    //
}
