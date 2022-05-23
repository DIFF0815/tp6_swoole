<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/5/19
 * Time: 15:18
 */

namespace app\api\controller;


use app\common\controller\Common;
use app\common\library\mq\MqTaskHandle;

class MqTest extends Common
{

    public function test(){

        $typeName = 'TestDirectMqTaskConsumer';
        $producerHandle = (new MqTaskHandle($typeName,'producer'))->getHandle();
        var_dump($producerHandle->rabbitMqHandle->times);
        $data = [1,2,3,4];
        $part = 1;
        $time = time();
        //var_dump($producerHandle);
        $producerHandle->addOneToMQ($data,$part,$time);


        echo " 测试mq...".PHP_EOL;


        $typeName = 'TestDirectMqTaskConsumer';
        $producerHandle = (new MqTaskHandle($typeName,'producer'))->getHandle();
        var_dump($producerHandle->rabbitMqHandle->times);

        $typeName = 'TestDirectMqTaskConsumer';
        $producerHandle = (new MqTaskHandle($typeName,'producer'))->getHandle();
        var_dump($producerHandle->rabbitMqHandle->times);


    }

}
