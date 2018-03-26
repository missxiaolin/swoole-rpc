<?php
// +----------------------------------------------------------------------
// | EnumException.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: xiaolin <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------
namespace Lin\Swoole\Rpc;

use Lin\Enum\Exception\RpcException;
use swoole_client;

class SwooleClient implements SwooleClientInterface
{

    public $client;

    protected $timeout = 0.1;

    protected static $_instances = [];

    /**
     * SwooleClient constructor.
     * @param $host
     * @param $port
     * @param array $options
     * @throws RpcException
     */
    public function __construct($host, $port, $options = [])
    {
        $client = new swoole_client(SWOOLE_SOCK_TCP);

        if (isset($options['timeout']) && is_numeric($options['timeout'])) {
            $this->timeout = $options['timeout'];
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

        return static::$_instances[$service] = new static($host, $port, $options);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function handle($data)
    {
        $this->client->send(json_encode($data));
        return $this->client->recv();
    }

}