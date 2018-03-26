<?php
// +----------------------------------------------------------------------
// | EnumException.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: xiaolin <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------

namespace Lin\Swoole\Rpc;

use Lin\Swoole\Rpc\Exception\RpcException;
use Lin\Swoole\Rpc\Handler\HanderInterface;
use swoole_server;

class Server
{
    public $host;

    public $port;

    public $config;

    public $services = [];

    /**
     * @param $service
     * @param HanderInterface $hander
     * @return $this
     */
    public function setHandler($service, HanderInterface $hander)
    {
        $this->services[$service] = $hander;
        return $this;
    }

    /**
     * @param $host
     * @param $port
     * @param array $config
     * @throws RpcException
     */
    public function serve($host, $port, $config = [])
    {
        if (!extension_loaded('swoole')) {
            throw new RpcException('The swoole extension is not installed');
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

    /**
     * @param swoole_server $server
     */
    public function beforeServerStart(swoole_server $server)
    {
        echo "-------------------------------------------" . PHP_EOL;
        echo "     Socket服务器开启 端口：" . $this->port . PHP_EOL;
        echo "-------------------------------------------" . PHP_EOL;
    }

    /**
     * @param swoole_server $server
     * @param $workerId
     */
    public function workerStart(swoole_server $server, $workerId)
    {
    }

    /**
     * @param swoole_server $server
     * @param $fd
     * @param $reactor_id
     * @param $data
     */
    public function receive(swoole_server $server, $fd, $reactor_id, $data)
    {
        try {
            $data = json_decode($data, true);
            $service = $data['service'];
            $method = $data['method'];
            $arguments = $data['arguments'];

            if (!isset($this->services[$service])) {
                throw new RpcException('The service handler is not exist!');
            }

            $result = $this->services[$service]->$method(...$arguments);
            $server->send($fd, $this->success($result));
        } catch (\Exception $ex) {
            $server->send($fd, $this->fail($ex->getCode(), $ex->getMessage()));
        }
    }

    /**
     * @param $result
     * @return string
     */
    public function success($result)
    {
        return json_encode([
            Enum::SUCCESS => true,
            Enum::DATA => $result,
        ]);
    }

    /**
     * @param $code
     * @param $message
     * @return string
     */
    public function fail($code, $message)
    {
        return json_encode([
            Enum::SUCCESS => false,
            Enum::ERROR_CODE => $code,
            Enum::ERROR_MESSAGE => $message,
        ]);
    }
}
