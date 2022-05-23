<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/2/17
 * Time: 20:22
 */

namespace app\common\library\mq\abstraction;


abstract class MqTaskConsumerAbstraction extends MqTaskAbstraction {

    //强制要求子类定义方法
    abstract protected function run($mqTaskObj);
    abstract protected function directInit();
    abstract protected function fanoutInit();

}
