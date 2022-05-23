<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/2/18
 * Time: 10:34
 */
//队列配置
return [
    /*******************Direct*****************************/
    //测试
    'TestDirectMqTaskConsumer' => [
        'exchange_name' => 'test.direct.exchange', //交换机名
        'exchange_type' => 'direct',//交换机模式： direct、topic、fanout
        'queue_name' => 'test.direct.queue', //队列名
        'route_key' => 'test.direct.key',//绑定路由key
        'consumer_tag' => '',//消费者标识（默认就好）
        'message_ttl' => 86400000*7, //7天
        'dead_exchange_name' => 'test.exception.direct.exchange',
        'dead_route_key' => 'test.exception.direct.key',
    ],



];
