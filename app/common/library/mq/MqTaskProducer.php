<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/2/22
 * Time: 10:47
 */

namespace app\common\library\mq;


use app\common\library\mq\abstraction\MqTaskProducerAbstraction;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class MqTaskProducer extends MqTaskProducerAbstraction
{
    /**
     * 加入队列（单）
     * @param $data
     * @param $part
     * @param $time
     * @param int[] $headers
     */
    public function addOneToMQ($data,$part,$time,$headers=['retry' => 0]){

        $queueData = [
            'mqTypeName' => $this->typeName,
            'mqPart' => $part,
            'mqTime' => $time,
            'mqData' => $data,
        ];
        if($this->exchangeType == 'direct'){
            $this->rabbitMqHandle->sendMessage(json_encode($queueData), $this->typeConf['route_key'], $this->typeConf['exchange_name'], [
                'application_headers' => new AMQPTable($headers),//自定义的
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT //消息持久化，重启rabbitmq，消息不会丢失
            ]);
        }elseif($this->exchangeType == 'fanout'){
            $this->rabbitMqHandle->sendMessage(json_encode($queueData), null, $this->typeConf['exchange_name'], [
                'application_headers' => new AMQPTable($headers),//自定义的
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT //消息持久化，重启rabbitmq，消息不会丢失
            ]);
        }

    }
}
