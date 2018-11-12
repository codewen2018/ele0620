<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class ClearOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear timeout not payed orders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //1. 找出需要处理的订单
        /**
         *  当前时间-创建时间>15*60
         * 当前时间>创建时间+15*60
         * 当前时间-15*60>创建时间
         * 创建时时间<当前时间-15*60
         *
         * "2018-12-12 08:12:11"  < time()-15*60=== 123141234=====>date("Y-m-d H:i:s")
         *
         */
       while (true){
            $orders = Order::where("status", 0)->where("created_at", "<", date("Y-m-d H:i:s", time() - 15 * 60))->get();

            //循环的订单
            foreach ($orders as $order) {
                $order->status = -1;
                $order->save();

                echo date("Ymd H:i:s") ." clear orderId:".$order->id.PHP_EOL;
            }
            sleep(3);
       }

    }
}
