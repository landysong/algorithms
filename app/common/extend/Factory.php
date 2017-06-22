<?php
/**
 * Created by PhpStorm.
 * Author: Landy
 * Date: 2017/6/21
 * Time: 16:13
 */

namespace app\common\extend;


abstract class Factory extends FactoryAbstract
{
    final public static function getInstance()
    {
        return parent::getInstance();
    }

    final public static function removeInstance()
    {
        parent::removeInstance();
    }
}