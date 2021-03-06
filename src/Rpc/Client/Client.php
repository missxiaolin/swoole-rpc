<?php
// +----------------------------------------------------------------------
// | EnumException.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: xiaolin <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------
namespace Lin\Swoole\Rpc\Client;


use Lin\Swoole\Rpc\Exception\RpcException;
use Lin\Swoole\Rpc\Enum;
use Lin\Swoole\Rpc\SwooleClient;

abstract class Client
{
    protected static $_instances = [];

    protected $service;

    protected $host;

    protected $port;

    /** @var SwooleClient */
    protected $client;

    const TIMEOUT = 0.1;

    /**
     * Client constructor.
     * @throws RpcException
     */
    public function __construct()
    {
        if (!isset($this->service)) {
            throw new RpcException('The service name is required!');
        }

        if (!isset($this->host)) {
            throw new RpcException('The host is required!');
        }

        if (!isset($this->port)) {
            throw new RpcException('The port is required!');
        }

        $this->client = $this->getSwooleClient();
    }

    /**
     * @return SwooleClient
     */
    public function getSwooleClient()
    {
        $options = [
            Enum::TIMEOUT => static::TIMEOUT,
        ];

        $service = $this->service;
        $host = $this->host;
        $port = intval($this->port);

        return SwooleClient::getInstance($service, $host, $port, $options);
    }

    /**
     * @return mixed|static
     */
    public static function getInstance()
    {
        $key = get_called_class();

        if (isset(static::$_instances[$key]) && static::$_instances[$key] instanceof static) {
            return static::$_instances[$key];
        }

        return static::$_instances[$key] = new static();
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return static::getInstance()->$name(...$arguments);
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws RpcException
     */
    public function __call($name, $arguments)
    {
        $data = $this->getData($name, $arguments);
        $result = $this->client->handle($data);
        if ($result = json_decode($result, true)) {
            if (true === $result[Enum::SUCCESS]) {
                return $result[Enum::DATA];
            }

            throw new RpcException($result[Enum::ERROR_MESSAGE], $result[Enum::ERROR_CODE]);
        }

        throw new RpcException('未知错误');
    }

    /**
     * @param $name
     * @param $arguments
     * @return array
     */
    public function getData($name, $arguments)
    {
        return [
            Enum::SERVICE => $this->service,
            Enum::METHOD => $name,
            Enum::ARGUMENTS => $arguments,
        ];
    }

    public function flush()
    {
        unset(static::$_instances[$this->service]);
    }
}