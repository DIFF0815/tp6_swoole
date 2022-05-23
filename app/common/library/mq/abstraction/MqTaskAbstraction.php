<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/2/17
 * Time: 20:22
 */

namespace app\common\library\mq\abstraction;


use app\common\tool\MqTool;

abstract class MqTaskAbstraction {

    //强制要求子类定义方法

    /**
     * 所有功能类型的配置
     * @var array
     */
    public array $allTypeConf;

    /**
     * 当前功能类型的配置
     * @var array
     */
    public array $typeConf;

    /**
     * 当前功能类型名称
     * @var string
     */
    protected string $typeName;

    /**
     * @var string
     */
    public string $exchangeType;

    public ?MqTool $rabbitMqHandle;

    public function __construct($typeName){

        $this->typeName = $typeName;
        //初始化
        $this->allTypeConf = config('rabbitmq');
        $this->typeConf = $this->allTypeConf[$this->typeName] ?? [];

        if(empty($this->typeConf)){
            $logStr = date('Y-m-d H:i:s').' 配置错误，请检查配置是否有问题';
            echo $logStr;
            //@todo
            throw new \Exception($logStr);
        }

        //注册
        register_shutdown_function(array($this, 'shutdown'));

        $this->rabbitMqHandle = MqTool::getInstance();
        $this->exchangeType = $this->typeConf['exchange_type'];
        $this->exchangeType = lcfirst($this->exchangeType);

        if($this->exchangeType){
            $exchangeTypeInit = "{$this->exchangeType}Init";
            if(method_exists($this,$exchangeTypeInit)){
                $this->$exchangeTypeInit();
            }
        }
    }

    /**
     *
     */
    public function shutdown()
    {
        // TODO: Implement shutdown() method.
    }


}
