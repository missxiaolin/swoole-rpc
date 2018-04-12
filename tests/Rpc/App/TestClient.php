<?php
// +----------------------------------------------------------------------
// | EnumException.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: xiaolin <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------
namespace Tests\Rpc\App;


use Lin\Swoole\Rpc\Client\Client;

/**
 * Class TestClient
 * @package Tests\Rpc\App
 * @method returnString()
 * @method hasArguments($name)
 * @method exception()
 * @method bigString($str)
 * @method bigReturnString($str)
 */
class TestClient extends Client
{
    protected $service = 'test';

    protected $host = '127.0.0.1';

    protected $port = 11520;

    const TIMEOUT = 3;
}