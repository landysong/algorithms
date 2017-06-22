<?php
/**
 * Created by PhpStorm.
 * Author: Landy
 * Date: 2017/6/21
 * Time: 16:01
 */

namespace app\common\extend;


abstract class FactoryAbstract
{
    protected static $instances;

    public static function getInstance()
    {
        $className = static::getClassName();
        if (!(self::$instances instanceof $className)) {
            self::$instances = new $className();
        }

        return self::$instances;
    }

    public static function removeInstance()
    {
//        $className = static::getClassName();
//        if (array_key_exists($className, self::$instances)) {
//            unset(self::$instances[$className]);
//        }
        if (isset(self::$instances)) {
            self::$instances = null;
        }
    }

    final protected static function getClassName()
    {
        return get_called_class();
    }

    protected function __construct()
    {
    }

    final protected function __clone()
    {
        // TODO: Implement __clone() method.
    }

}