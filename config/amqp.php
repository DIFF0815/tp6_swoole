<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/2/18
 * Time: 10:34
 */
//amqp mq配置
return [
    'host'     => env('amqp.host','127.0.0.1'),
    'port'     => env('amqp.port','5672'),
    'user' => env('amqp.user','guest'),
    'password' => env('amqp.password','password'),
    'vhost'    => env('amqp.vhost','/'),
    'heartbeat' => env('amqp.heartbeat','60'),
];
