<?php

use think\swoole\websocket\socketio\Handler;

return [
    'http'       => [
        'enable'     => true,
        'host'       => env('swoole.host','0.0.0.0'),
        'port'       => env('swoole.port',9501),
        'worker_num' => swoole_cpu_num() *2,
    ],
    'websocket'  => [
        'enable'        => true,
        'handler'       => Handler::class,
        'ping_interval' => 25000,
        'ping_timeout'  => 60000,
        'room'          => [
            'type'  => 'table',
            'table' => [
                'room_rows'   => 4096,
                'room_size'   => 2048,
                'client_rows' => 8192,
                'client_size' => 2048,
            ],
            'redis' => [
                'host'          => env('redis.host','127.0.0.1'),
                'port'          => env('redis.port',6379),
                'max_active'    => 3,
                'max_wait_time' => 5,
            ],
        ],
        'listen'        => [
            // 首字母大小写都可以；值应该是字符串非数
            //'connect' => 'app\api\listener\websocket\WsConnect',
            //'test'    => 'app\api\listener\websocket\WsTest',
            //'connect' => \app\api\listener\websocket\WebsocketConnect::class,
            //'test' => \app\api\listener\websocket\WebsocketTest::class,
            'event'  => app\common\listener\websocket\WebsocketEvent::class,

        ],
        'subscribe'     => [],
    ],
    'rpc'        => [
        'server' => [
            'enable'     => false,
            'host'       => '0.0.0.0',
            'port'       => 9000,
            'worker_num' => swoole_cpu_num(),
            'services'   => [
            ],
        ],
        'client' => [
        ],
    ],
    //队列
    'queue'      => [
        'enable'  => true,
        'workers' => [
            //下面参数是不设置时的默认配置
            'default'            => [
                'delay'      => 0,
                'sleep'      => 3,
                'tries'      => 0,
                'timeout'    => 60,
                'worker_num' => 1,
            ],
            //使用@符号后面可指定队列使用驱动
            /*'default@connection' => [
                //此处可不设置任何参数，使用上面的默认配置
            ],*/
            'dismiss_job_queue' => [
            ],
        ],
    ],
    'hot_update' => [
        'enable'  => env('APP_DEBUG', false),
        'name'    => ['*.php'],
        'include' => [app_path()],
        'exclude' => [],
    ],
    //连接池
    'pool'       => [
        'db'    => [
            'enable'        => true,
            'max_active'    => 3,
            'max_wait_time' => 5,
        ],
        'cache' => [
            'enable'        => true,
            'max_active'    => 3,
            'max_wait_time' => 5,
        ],
        //自定义连接池
    ],
    'tables'     => [],
    //每个worker里需要预加载以共用的实例
    'concretes'  => [],
    //重置器
    'resetters'  => [],
    //每次请求前需要清空的实例
    'instances'  => [],
    //每次请求前需要重新执行的服务
    'services'   => [],
];
