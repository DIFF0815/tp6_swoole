<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/2/18
 * Time: 14:06
 */

namespace app\common\tool;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class MqTool
{

    /**
     * @var MqTool|null
     */
    private static ?MqTool $instance = null;

    /**
     * @var AMQPStreamConnection
     */
    protected AMQPStreamConnection $connection;

    /**
     * @var
     */
    protected \PhpAmqpLib\Channel\AMQPChannel $channel;

    /**
     * @var array|null
     */
    protected ?array $hostConf;

    public int $times;

    /**
     * constructor.
     */
    private function __construct()
    {

        $this->hostConf = config('amqp');
        $this->times = time();

        $this->make();

        //注册
        register_shutdown_function(array($this, 'shutdown'));
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * @return MqTool|null
     */
    public static function getInstance(): ?MqTool
    {
        if(empty(self::$instance)){
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * make
     */
    protected function make(){

        try {
            $this->connection = new AMQPStreamConnection(
                $this->hostConf['host'],
                $this->hostConf['port'],
                $this->hostConf['user'],
                $this->hostConf['password'],
                $this->hostConf['vhost'],
                false,
                'AMQPLAIN',
                null,
                'en_US',
                3.0,
                3.0,
                null,
                false,
                $this->hostConf['heartbeat'],
                0.0,
                null
            );

            $this->channel = $this->connection->channel();

        }catch (\Throwable $e){
            echo $e->getMessage();
        }
    }

    /**
     * 获取channel对象
     * @return \PhpAmqpLib\Channel\AMQPChannel
     */
    public function getChannel(): \PhpAmqpLib\Channel\AMQPChannel
    {
        return $this->channel;
    }

    /**
     * 获取Connection对象
     * @return AMQPStreamConnection
     */
    public function getConnection(): AMQPStreamConnection
    {
        return $this->connection;
    }

    /**
     * 声明创建交换机
     * @param $exchangeName
     * @param $type
     * @param false $passive
     * @param false $durable
     * @param false $autoDelete
     * @param false $internal
     * @param false $nowait
     * @param array $arguments
     * @param null $ticket
     */
    public function createExchange($exchangeName, $type, $passive = false, $durable = false, $autoDelete = false, $internal = false, $nowait = false, $arguments = array(), $ticket = null)
    {
        $this->channel->exchange_declare($exchangeName, $type, $passive, $durable, $autoDelete, $internal, $nowait, $arguments, $ticket);
    }

    /**
     * 创建队列
     * @param $queueName
     * @param false $passive
     * @param false $durable
     * @param false $exclusive
     * @param false $autoDelete
     * @param false $nowait
     * @param array $arguments
     */
    public function createQueue($queueName, $passive = false, $durable = false, $exclusive = false, $autoDelete = false, $nowait = false, $arguments = [])
    {
        $this->channel->queue_declare($queueName, $passive, $durable, $exclusive, $autoDelete, $nowait, $arguments);
    }


    /**
     * 绑定关系
     * @param $queue
     * @param $exchangeName
     * @param string $routing_key
     * @param false $nowait
     * @param array $arguments
     * @param null $ticket
     */
    public function bindQueue($queue, $exchangeName, $routing_key = '', $nowait = false, $arguments = array(), $ticket = null)
    {
        $this->channel->queue_bind($queue, $exchangeName, $routing_key, $nowait, $arguments, $ticket);
    }


    /**
     * 发送消息到队列
     * @param $message
     * @param $routeKey
     * @param string $exchange
     * @param array $properties
     */
    public function sendMessage($message, $routeKey, $exchange = '', $properties = [])
    {
        $data = new AMQPMessage(
            $message, $properties
        );
        $this->channel->basic_publish($data, $exchange, $routeKey);
    }

    /**
     * 消费消息
     * @param $queueName
     * @param $callback
     * @param string $tag
     * @param false $noLocal
     * @param false $noAck
     * @param false $exclusive
     * @param false $noWait
     * @throws \ErrorException
     */
    public function consumeMessage($queueName, $callback, $tag = '', $noLocal = false, $noAck = false, $exclusive = false, $noWait = false)
    {
        $this->channel->basic_qos(null,1,null);
        $this->channel->basic_consume($queueName, $tag, $noLocal, $noAck, $exclusive, $noWait, $callback);
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    /**
     * 关闭连接
     * @throws \Exception
     */
    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * 关闭
     */
    public function shutdown()
    {
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * 设置ack成功回调函数
     * @param $callback
     */
    public function setAckHandle($callback){
        $this->channel->set_ack_handler($callback);
    }

    /**
     * 设置ack失败回调函数
     * @param $callback
     */
    public function setNackHandle($callback){
        $this->channel->set_nack_handler($callback);
    }

    /**
     * 开启确认模式
     */
    public function confirmSelect(){
        $this->channel->confirm_select();
    }

}
