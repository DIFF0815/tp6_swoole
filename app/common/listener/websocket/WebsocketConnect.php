<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/5/13
 * Time: 11:29
 */

namespace app\common\listener\websocket;


use think\Container;
use think\facade\Log;
use think\swoole\Websocket;

class WebsocketConnect
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle($event)
    {
        Log::record(111111111111);
        //实例化 Websocket 类
        $ws = app('\think\swoole\Websocket');
        //$ws = Container::getInstance()->make(Websocket::class);

        $ws->emit('testcallback',$ws->getSender());

        return ;
    }

}
