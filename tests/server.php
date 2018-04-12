<?php
// +----------------------------------------------------------------------
// | EnumException.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: xiaolin <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------
require __DIR__ . '/bootstrap.php';

use \Lin\Swoole\Rpc\Server;
use \Tests\Rpc\App\TestHandler;
use \Tests\Rpc\App\LoggerHandler;

$server = new Server();

$server->setHandler('test', TestHandler::getInstance())
    ->setLoggerHandler(LoggerHandler::getInstance())
    ->serve('0.0.0.0', '11520', [
        'pid_file' => './socket.pid',
        'daemonize' => false,
        'max_request' => 500, // 每个worker进程最大处理请求次数
        'worker_num' => 1,
        'open_eof_check' => true,
        'package_eof' => "\r\n",
    ]);
