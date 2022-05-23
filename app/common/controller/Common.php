<?php
namespace app\common\controller;

use app\BaseController;
use think\facade\View;

class Common extends BaseController
{

    protected function assign($name, $value = null){
        return View::assign($name, $value);
    }

    protected function fetch($template = '', $vars = []){
        return View::fetch($template, $vars);
    }

}
