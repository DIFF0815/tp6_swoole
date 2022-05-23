<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/2/17
 * Time: 20:22
 */

namespace app\common\library\cron\abstraction;


abstract class CronTaskAbstraction {

    //强制要求子类定义方法
    abstract protected function run($params = null);

}
