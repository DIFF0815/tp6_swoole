<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/2/21
 * Time: 19:56
 */

namespace app\common\library\mq;

use app\common\library\mq\constant\MqTaskHandleType;

class MqTaskHandle
{
    public string $mqTaskTypeName;

    public ?object $instance = null;

    public function __construct($mqTaskTypeName,$handleType){

        $this->mqTaskTypeName = ucfirst($mqTaskTypeName);
        try{
            if($handleType == MqTaskHandleType::PRODUCER){
                $this->instance = (new MqTaskProducer($this->mqTaskTypeName));
            }elseif(($handleType == MqTaskHandleType::CONSUMER)){
                $this->instance = (new MqTaskConsumer($this->mqTaskTypeName));
            }

        }catch (\Throwable $e){

        }

    }

    /**
     * @return MqTaskConsumer|MqTaskProducer|object|null
     */
    public function getHandle()
    {
        return $this->instance;
    }

}
