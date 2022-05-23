<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/5/12
 * Time: 18:15
 */

namespace app\api\controller;


use app\api\model\LogModel;
use app\common\controller\Common;
use Swoole\Timer;
use think\facade\Log;
use think\swoole\App;

class Order extends Common
{
    /**
     * 模拟下单
     * @return string
     */
    public function save()
    {

        //TODO 调用验证类验证数据
        //TODO 将订单信息插入数据库

        $data = [
            'orderSn' => '202005260001',
            'time' => time()
        ];
        $model = new \think\facade\Log();

        //`Swoole\Timer::after`在指定的时间后执行函数，是一个一次性定时器，执行完成后就会销毁。
        \Swoole\Timer::after(10000, function () use ($data,$model) {

            //TODO 判断订单是否已经支付，如果未支付，取消订单

            $msg = '超时取消订单:'.$data['orderSn'];
            dump($msg);
            //$model::instance();
            //$model::record($msg);

            $logModel = new LogModel();
            $logModel->addData();


        });


        return "下单成功！".time();
    }

}
