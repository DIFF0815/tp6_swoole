<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/2/18
 * Time: 14:06
 */

namespace app\common\library\mq;


use app\common\library\mq\abstraction\MqTaskConsumerAbstraction;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Wire\AMQPTable;

class MqTaskConsumer extends MqTaskConsumerAbstraction
{
    /**
     * @var object
     */
    protected object $amqpTable;

    /**
     * 设置消息表配置
     */
    protected function setAmqpTable(){
        $table = [];
        isset($this->typeConf['message_ttl']) && $table['x-message-ttl'] = $this->typeConf['message_ttl'];
        if(isset($this->typeConf['dead_exchange_name']) && isset($this->typeConf['dead_route_key'])){
            $table['x-dead-letter-exchange'] = $this->typeConf['dead_exchange_name'];
            $table['x-dead-letter-routing-key'] = $this->typeConf['dead_route_key'];
        }
        $this->amqpTable = new AMQPTable($table);
    }

    /**
     * direct 类型初始化
     */
    public function directInit(){
        $this->setAmqpTable();
        $this->rabbitMqHandle->createExchange($this->typeConf['exchange_name'],AMQPExchangeType::DIRECT, false, true);
        $this->rabbitMqHandle->createQueue($this->typeConf['queue_name'],false,true,false,false,false,$this->amqpTable);
        $this->rabbitMqHandle->bindQueue($this->typeConf['queue_name'],$this->typeConf['exchange_name'],$this->typeConf['route_key']);
    }

    /**
     * fanout 类型初始化
     */
    public function fanoutInit(){
        $this->setAmqpTable();
        $this->rabbitMqHandle->createExchange($this->typeConf['exchange_name'],AMQPExchangeType::FANOUT, false, true);
        $this->rabbitMqHandle->createQueue($this->typeConf['queue_name'],false,true,false,false,false,$this->amqpTable);
        $this->rabbitMqHandle->bindQueue($this->typeConf['queue_name'],$this->typeConf['exchange_name']);
    }

    /**
     * @param $mqTaskObj
     * @throws \ErrorException
     */
    public function run($mqTaskObj){

        $this->rabbitMqHandle->consumeMessage($this->typeConf['queue_name'], array($mqTaskObj, 'processMessage'));

        while (count($this->rabbitMqHandle->getChannel()->callbacks)) {
            $this->rabbitMqHandle->getChannel()->wait();
        }
        unset($this->rabbitMqHandle);//关闭连接


    }

}
