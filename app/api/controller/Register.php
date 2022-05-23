<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/5/10
 * Time: 18:20
 */

namespace app\api\controller;


use app\common\controller\Common;
use think\facade\Queue;

class Register extends Common
{
    /**
     * @param
     * @return string
     */
    public function register(): string
    {

        //TODO 调用验证类验证数据
        //TODO 将注册信息插入数据库

        //异步模拟发送短信
        $data = [
            'task' => 'sendSms',
            'mobile' => '152****6268',
        ];

        /*创建新消息并推送到消息队列*/
        // 当前任务由哪个类负责处理
        $job_handler_classname = "app\common\job\DismissJob";
        // 当前队列归属的队列名称
        $job_queue_name = "dismiss_job_queue";
        // 当前任务所需的业务数据
        $job_data = ["ts"=>time(), "bizid"=>uniqid(), "params"=>$data];
        // 将任务推送到消息队列等待对应的消费者去执行
        $is_pushed = Queue::push($job_handler_classname, $job_data, $job_queue_name);
        trace($job_data,'error');
        trace($job_data,'log');

        return "注册成功！".time();
    }


}
