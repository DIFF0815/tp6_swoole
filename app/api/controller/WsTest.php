<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/5/13
 * Time: 11:37
 */

namespace app\api\controller;


use app\common\controller\Common;

class WsTest extends Common
{
    public function index(){
        return $this->fetch('test/test');
    }
}
