<?php
// +----------------------------------------------------------------------
// | EnumException.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: xiaolin <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------
namespace Tests\Rpc;

use Tests\Rpc\App\TestClient;
use Tests\TestCase;
use xiaolin\Support\Str;

class BaseTest extends TestCase
{
    public function testSwooleCase()
    {
        $this->assertTrue(extension_loaded('swoole'));
    }

    public function testReturnString()
    {
        $result = TestClient::getInstance()->returnString();
        $this->assertEquals('success', $result);
    }

    public function testReturnBoolean()
    {
        $result = TestClient::getInstance()->returnTrue();
        $this->assertTrue($result);
    }

    public function testReturnArray()
    {
        $result = TestClient::getInstance()->returnArray();
        $this->assertEquals(['key' => 'val'], $result);
    }

    public function testHasArguments()
    {
        $name = 'xiaolin';
        $result = TestClient::getInstance()->hasArguments($name);
        $this->assertEquals("hi, {$name}", $result);
    }

    public function testException()
    {
        try {
            $result = TestClient::getInstance()->exception();
        } catch (\Exception $ex) {
            $this->assertEquals(400, $ex->getCode());
            $this->assertEquals('测试异常', $ex->getMessage());
        }
    }

    public function testRecvTimeout()
    {
        try {
            $result = TestClient::getInstance()->recvTimeout();
            $this->assertEquals("runtime is 2 seconds", $result);
        } catch (\Exception $ex) {
            $this->assertEquals(2, $ex->getCode());
        }
    }

    public function testBigString()
    {
        $str = Str::random(2048);
        $this->assertEquals($str, TestClient::getInstance()->bigString($str));
    }

    public function testBigReturnString()
    {
        $str = Str::random(200);
        $this->assertEquals(str_repeat($str, 100), TestClient::getInstance()->bigReturnString($str));
    }

    public function testManyRequest()
    {
        $time = microtime(true);
        for ($i = 0; $i < 10000; $i++) {
            $result = TestClient::getInstance()->returnTrue();
        }
        $time = microtime(true) - $time;
        $this->assertTrue($time < 10);
    }
}
