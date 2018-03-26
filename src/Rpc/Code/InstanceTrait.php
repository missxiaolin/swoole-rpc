<?php
// +----------------------------------------------------------------------
// | EnumException.php [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 xiaolin All rights reserved.
// +----------------------------------------------------------------------
// | Author: xiaolin <462441355@qq.com> <https://github.com/missxiaolin>
// +----------------------------------------------------------------------

namespace Lin\Swoole\Rpc\Code;

trait InstanceTrait
{
    protected static $_instances = [];

    protected $instanceKey;

    /**
     * @param string $key
     * @return static
     */
    public static function getInstance($key = 'default')
    {
        if (!isset($key)) {
            $key = 'default';
        }

        if (isset(static::$_instances[$key]) && static::$_instances[$key] instanceof static) {
            return static::$_instances[$key];
        }

        $client = new static();
        $client->instanceKey = $key;
        return static::$_instances[$key] = $client;
    }

    /**
     * @desc   回收单例对象
     * @author xiaolin
     */
    public function flushInstance()
    {
        unset(static::$_instances[$this->instanceKey]);
    }
}
