<?php

namespace App\Http\Controllers\Shop;

use App\Models\Event;
use App\Models\EventUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class EventController extends Controller
{
    public function index()
    {

        $events = Event::all();

        return view("shop.event.index", compact('events'));
    }

    public function sign(Request $request)
    {
        $eventId = $request->input('id');
        $userId = $request->input('user_id');

       // dd($eventId);

      /*  //1.判断当前报名人数据有没有满
        $event=Event::find($eventId);

        //2.当前已报名人数
        $curUsers=$event->users->count();

        //3.判断人数是否已满
        if ($curUsers<$event->num){
            //报名成功
            EventUser::insert([
               "event_id"=>$eventId,
               "user_id"=> $userId
            ]);
            return "报名成功";

        }else{
            return "报名失败";
        }*/


        //判断当前报名人数 和限制报名人数

        //1.取出限制报名人数
        $num=Redis::get("event_num:".$eventId);

        //2.取出报名人数
        $users=Redis::scard("event:".$eventId);

        if ($users<$num){

            //3. 把当前报名的人的ID 存到 Redis中  存什么类型 格式 event:3
            Redis::sadd("event:".$eventId,$userId);
            return "报名成功";
        }else{
            return "报名失败";
        }


       //dd($num);

        //2.取出已报名人数
        //$users=Redis::scard("event:".$eventId);

        //3.判断
       /* if ($users<$num){

            //存reids 集合
            Redis::sadd("event:".$eventId,$userId);
            return "报名成功";
        }*/

      //  return "已报满";

    }
}
