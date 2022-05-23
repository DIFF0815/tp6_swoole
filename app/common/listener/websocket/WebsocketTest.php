<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/5/13
 * Time: 11:26
 */

namespace app\common\listener\websocket;


class WebsocketTest
{
    /**
     * 事件监听处理
     *
     * @return mixed
     */
    public function handle($event)
    {
        $ws = app('think\swoole\Websocket');
        //获取当前发送消息客户端的 fd
        //var_dump($ws->getSender());
        //发送给指定 fd 的客户端，包括发送者自己
        $ws->to(intval($event['to']))->emit('testcallback',$event['message']);
        //发送广播消息，群发消息
        //$ws->broadcast()->emit('testcallback',$event['message']);

        return ;
    }

}
