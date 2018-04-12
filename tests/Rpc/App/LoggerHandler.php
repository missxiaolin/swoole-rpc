<?php
// +----------------------------------------------------------------------
// | EnumException.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: xiaolin <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------
namespace Tests\Rpc\App;

use Exception;
use Lin\Swoole\Rpc\Code\InstanceTrait;
use Lin\Swoole\Rpc\LoggerInterface;

class LoggerHandler implements LoggerInterface
{
    use InstanceTrait;

    public function info($request, $response)
    {
        $data = [
            'request' => $request,
            'response' => $response,
        ];

        $file = TESTS_PATH . '/info.log';
        file_put_contents($file, json_encode($data));
    }

    public function error($request, $response, Exception $ex)
    {
        $data = [
            'request' => $request,
            'response' => $response,
        ];

        $file = TESTS_PATH . '/error.log';
        file_put_contents($file, json_encode($data));
    }
}