<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/5/11
 * Time: 16:04
 */

namespace app\common\job;


use think\facade\Log;
use think\queue\Job;

// php think queue:work --queue dismiss_job_queue
class DismissJob
{
    /**
     * fire是消息队列默认调用的方法
     * @param Job $job 当前的任务对象
     * @param array|mixed $data 发布任务时自定义的数据
     */
    public function fire(Job $job, $data)
    {
        //var_dump($data);
        //有效消息到达消费者时可能已经不再需要执行了
        if(!$this->checkJob($data)){
            $job->delete();
            return;
        }
        //执行业务处理
        if($this->doJob($data)){
            $job->delete();//任务执行成功后删除
            Log::record("job has been down and deleted");
        }else{
            //检查任务重试次数
            if($job->attempts() > 3){
                Log::record("job has been retried more that 3 times");
                $job->delete();
            }
        }
    }

    /**
     * 消息在到达消费者时可能已经不需要执行了
     * @param array|mixed $data 发布任务时自定义的数据
     * @return boolean 任务执行的结果
     */
    private function checkJob($data)
    {
        $ts = $data["ts"];
        $bizid = $data["bizid"];
        $params = $data["params"];

        return true;
    }

    /**
     * 根据消息中的数据进行实际的业务处理
     */
    private function doJob($data)
    {
        // 实际业务流程处理
        //sleep(10);
        //var_dump($data);
        //dump($data);
        Log::record(json_encode($data));
        //Log::record(123456);
        return true;

    }

}
