<?php
// +----------------------------------------------------------------------
// | EnumException.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: limx <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------

namespace Lin\Swoole\Rpc;

use Lin\Enum\Exception\SwooleException;
use swoole_server;

class Server
{
    public $host;

    public $port;

    public $config;

    public function serve($host, $port, $config = [])
    {
        if (!extension_loaded('swoole')) {
            throw new SwooleException('The swoole extension is not installed');
        }

        $this->host = $host;
        $this->port = $port;
        $this->config = $config;

        set_time_limit(0);
        $server = new swoole_server($host, $port);

        $server->set($config);

        $server->on('receive', [$this, 'receive']);
        $server->on('workerStart', [$this, 'workerStart']);

        $this->beforeServerStart($server);

        $server->start();
    }

    public function beforeServerStart(swoole_server $server)
    {
        echo "-------------------------------------------" . PHP_EOL;
        echo "     Socket服务器开启 端口：" . $this->port . PHP_EOL;
        echo "-------------------------------------------" . PHP_EOL;
    }

    public function workerStart(swoole_server $server, $workerId)
    {
    }

    public function receive(swoole_server $server, $fd, $reactor_id, $data)
    {
    }
}
