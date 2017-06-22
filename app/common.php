<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * get_split_word
 * @param $statement
 * @param null $num
 * @return mixed
 */
function get_split_word($statement, $num = null){
    $pscws = \app\common\extend\PSCWS4::getInstance();
    $pscws->set_dict(DICT_PATH . 'dict.utf8.xdb');
    $pscws->set_rule(DICT_PATH . 'rules.utf8.ini');
    $pscws->set_ignore(true);
    $pscws->send_text($statement);
    $result = $pscws->get_tops($num);
    $pscws->close();

    $words = array();
    foreach ($result as $k => $v)
    {
        $words[] = $v['word'];
    }

    return $words;
}
