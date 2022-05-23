<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/5/13
 * Time: 10:40
 */

namespace app\api\model;


use think\Model;

class LogModel extends Model
{
    protected $name = 'log';


    public function addData($data=null){
        if(empty($data)){
            $data = [
                'create_time'=>time(),
            ];
        }
        $this->insert($data);
    }

}
