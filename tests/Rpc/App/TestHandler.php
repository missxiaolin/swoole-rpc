<?php
// +----------------------------------------------------------------------
// | EnumException.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: xiaolin <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------
namespace Tests\Rpc\App;


use Lin\Swoole\Rpc\Code\InstanceTrait;
use Lin\Swoole\Rpc\Handler\HanderInterface;

class TestHandler implements HanderInterface
{
    use InstanceTrait;

    public function returnString()
    {
        return 'success';
    }

    public function returnTrue()
    {
        return true;
    }

    public function returnArray()
    {

        return [
            'key' => 'val',
        ];
    }

    public function hasArguments($name)
    {
        return "hi, {$name}";
    }

    public function recvTimeout()
    {
        sleep(2);
        return 'runtime is 2 seconds';
    }

    public function exception()
    {
        throw new \Exception('测试异常', 400);
    }
}