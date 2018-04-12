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

    /** @var  LoggerInterface */
    public $logger;

    public $debug = true;

    /**
     * @param $debug
     * @return $this
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLoggerHandler(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

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
        $this->port = intval($port);
        $this->config = $config;

        set_time_limit(0);
        $server = new swoole_server($this->host, $this->port);

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
        $data = trim($data);
        if ($this->debug) {
            dump("fd:{$fd} data:{$data}");
        }
        try {
            $data = json_decode($data, true);
            $service = $data[Enum::SERVICE];
            $method = $data[Enum::METHOD];
            $arguments = $data[Enum::ARGUMENTS];

            if (!isset($this->services[$service])) {
                throw new RpcException('The service handler is not exist!');
            }

            $result = $this->services[$service]->$method(...$arguments);
            $response = $this->success($result);
            $server->send($fd, json_encode($response));

            if ($this->debug && $this->logger && $this->logger instanceof LoggerInterface) {
                $this->logger->info($data, $response);
            }
        } catch (\Exception $ex) {
            $response = $this->fail($ex->getCode(), $ex->getMessage());
            $server->send($fd, json_encode($response));

            if ($this->logger && $this->logger instanceof LoggerInterface) {
                $this->logger->error($data, $response, $ex);
            }
        }
    }

    /**
     * @param $result
     * @return array
     */
    public function success($result)
    {
        return [
            Enum::SUCCESS => true,
            Enum::DATA => $result,
        ];
    }

    /**
     * @param $code
     * @param $message
     * @return array
     */
    public function fail($code, $message)
    {
        return [
            Enum::SUCCESS => false,
            Enum::ERROR_CODE => $code,
            Enum::ERROR_MESSAGE => $message,
        ];
    }
}
