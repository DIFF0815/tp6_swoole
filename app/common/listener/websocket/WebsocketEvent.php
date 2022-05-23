<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/5/13
 * Time: 20:34
 */

namespace app\common\listener\websocket;


use think\Container;
use think\swoole\Websocket;

class WebsocketEvent
{
    public $websocket = null;

    public function __construct(Container $container)
    {
        $this->websocket = $container->make(Websocket::class);
    }

    /**
     * 事件监听处理
     * @param $event
     */
    public function handle($event)
    {
        $func = $event->type;
        $this->$func($event);
    }

    /**
     * 测试类型
     * @param $event
     */
    public function test($event)
    {
        var_dump($this->websocket->getSender());
        $this->websocket->emit('testcallback', ['aaaaa' => 1, 'getdata' => '123123','getSender'=>$this->websocket->getSender()]);
    }

    public function sendUser($event){
        $data = $event->data;
        var_dump($data[0]['to']);
        var_dump($this->websocket->getSender());
        $this->websocket->to($data[0]['to'])->emit('testcallback',["from_to" => "{$this->websocket->getSender()}=>{$data[0]['to']}",'said'=>$data[0]['message']]);
    }

    /**
     * 加入房间
     * @param $event
     */
    public function join($event)
    {
        $data = $event->data;
        $this->websocket->join($data[0]['room']);
    }

    /**
     * 离开房间
     * @param $event
     */
    public function leave($event)
    {
        $data = $event->data;
        $this->websocket->leave($data[0]['room']);
    }

    public function __call($name,$arguments)
    {
        $this->websocket->emit('testcallback', ['msg' => '不存在的请求类型:'.$name]);
    }
}
