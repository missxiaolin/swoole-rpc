<?php
// +----------------------------------------------------------------------
// | EnumException.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------
namespace Tests\Rpc;

use Tests\TestCase;

class BaseTest extends TestCase
{
    public function testSwooleCase()
    {
        $this->assertTrue(extension_loaded('swoole'));
    }
}
