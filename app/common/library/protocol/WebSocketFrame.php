<?php
/**
 * Created by PhpStorm.
 * User: DIFF
 * Date: 2022/3/23
 * Time: 11:41
 */

namespace app\common\library\protocol;


class WebSocketFrame
{
    public $finish = false;
    public $opcode;
    public $data;
    public $length;
    public $rsv1;
    public $rsv2;
    public $rsv3;
    public $mask;
}
