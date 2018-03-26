<?php
// +----------------------------------------------------------------------
// | EnumException.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------
require __DIR__ . '/../vendor/autoload.php';

use \Lin\Swoole\Rpc\Server;

$server = new Server();

$server->serve('0.0.0.0', '11520', [
    'pid_file' => './socket.pid',
    'daemonize' => false,
    'max_request' => 500, // 每个worker进程最大处理请求次数
]);
