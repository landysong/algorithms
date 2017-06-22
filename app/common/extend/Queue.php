<?php
/**
 * Created by PhpStorm.
 * Author: Landy
 * Date: 2017/6/21
 * Time: 15:16
 */

namespace app\common\extend;

use think\Cache;

class Queue extends Factory
{
    private $_queue = array();
    protected $cache = null;
    protected $queueCacheName;

    function init($queueName)
    {
        $options = [
            // 缓存类型为File
            'type' => 'File',
            // 缓存有效期为永久有效
            'expire' => 0,
            //缓存前缀
            'prefix' => 'queue',
            // 指定缓存目录
            'path' => APP_PATH . 'runtime/cache/',
        ];
        $this->cache = Cache::connect($options);

        $this->queueCacheName = 'queue_' . $queueName;
        $result = $this->cache->get($this->queueCacheName);
        if (is_array($result)) {
            $this->_queue = $result;
        }
    }

    function enQueue($value)
    {
        $this->_queue[] = $value;
        $this->cache->set($this->queueCacheName, $this->_queue);

        return $this->_queue;
    }

    function deQueue()
    {
        $result = array_shift($this->_queue);
        $this->cache->set($this->queueCacheName, $this->_queue);

        return $result;
    }

    function size()
    {
        return count($this->_queue);
    }

    function peek($num = 1)
    {
        if (count($this->_queue) < $num) {
            return $this->_queue;
        }
        return array_slice($this->_queue, 0, $num);
    }

    function destory()
    {
        $this->cache->rm($this->queueCacheName);
    }
}