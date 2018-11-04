<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Event
 *
 * @property int $id
 * @property string $title 名称
 * @property string $content 详情
 * @property int $start_time 开始时间
 * @property int $end_time 结束时间
 * @property int $prize_time 开奖时间
 * @property int $num 报名人数限制
 * @property int $is_prize 是否开奖
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereIsPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event wherePrizeTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Event whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Event extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    //
    public $fillable = ['title', 'content', 'start_time', 'end_time', 'prize_time', 'num', 'is_prize'];

    //通过活动找报名人
    public function users()
    {
        return $this->belongsToMany(User::class, 'event_users', 'event_id', 'user_id');
    }



}
