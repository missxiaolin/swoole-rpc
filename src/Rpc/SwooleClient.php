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
use swoole_client;

class SwooleClient implements SwooleClientInterface
{

    public $client;

    protected $timeout = 0.1;

    protected static $_instances = [];

    protected $service;

    /**
     * SwooleClient constructor.
     * @param $host
     * @param $port
     * @param array $options
     * @throws RpcException
     */
    public function __construct($host, $port, $options = [])
    {
        $client = new swoole_client(SWOOLE_TCP | SWOOLE_KEEP);

        if (isset($options[Enum::TIMEOUT]) && is_numeric($options[Enum::TIMEOUT])) {
            $this->timeout = $options[Enum::TIMEOUT];
        }

        if (!$client->connect($host, $port, $this->timeout)) {
            throw new RpcException("connect failed. Error: {$client->errCode}");
        }
        $this->client = $client;
    }

    /**
     * @param $service
     * @param $host
     * @param $port
     * @param array $options
     * @return mixed|static
     */
    public static function getInstance($service, $host, $port, $options = [])
    {
        if (isset(static::$_instances[$service]) && static::$_instances[$service] instanceof static) {
            return static::$_instances[$service];
        }

        $client = new static($host, $port, $options);
        static::$_instances[$service] = $client;
        $client->service = $service;
        return $client;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function handle($data)
    {
        $client = $this->client;
        if (!$client->isConnected()) {
            throw new RpcException("connect failed. Error: {$client->errCode}");
        }
        $client->send(json_encode($data));
        return $client->recv();
    }

    public function flush()
    {
        // $this->client->close();
        unset(static::$_instances[$this->service]);
    }

}