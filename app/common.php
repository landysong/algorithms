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
function get_split_word($statement, $num = null)
{
    if (empty($statement)) return false;
    $pscws = \app\common\extend\PSCWS4::getInstance();
    $pscws->set_dict(DICT_PATH . 'dict.utf8.xdb');
    $pscws->set_rule(DICT_PATH . 'rules.utf8.ini');
    $pscws->set_ignore(true);
    $pscws->send_text($statement);
    $result = $pscws->get_tops($num);
//    $result = $pscws->get_result();
    $pscws->close();

    $words = array();
    foreach ($result as $k => $v) {
        $words[] = $v['word'];
    }

    return $words;
}

function send_mail($toemail, $subject, $body)
{
    if (empty($toemail) || empty($subject) || empty($body)) return false;
    $mail = \app\common\extend\PHPMailer::getInstance();

    //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
    $mail->SMTPDebug = 0;
    //使用smtp鉴权方式发送邮件，当然你可以选择pop方式 sendmail方式等 本文不做详解
    //可以参考http://phpmailer.github.io/PHPMailer/当中的详细介绍
    $mail->isSMTP();
    //加密方式 "ssl" or "tls"
    //这里要注意, QQ发送邮件使用的ssl方式,如果不设置, 则会失败! 请认真查看下面的配置文件!!!
    $mail->SMTPSecure = config('email_config.secure');

    //smtp需要鉴权 这个必须是true
    $mail->SMTPAuth = true;

    //链接qq域名邮箱的服务器地址
    $mail->Host = config('email_config.host');

    //设置ssl连接smtp服务器的远程服务器端口号 可选465或587
    $mail->Port = config('email_config.port');

    //smtp登录的账号 这里填入字符串格式的qq号即可
    $mail->Username = config('email_config.username');

    //smtp登录的密码 这里填入“独立密码” 若为设置“独立密码”则填入登录qq的密码 建议设置“独立密码”
    $mail->Password = config('email_config.psw');

    //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
    $mail->From = config('email_config.From');

    //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
    $mail->FromName = config('email_config.FromName');

    //设置发送的邮件的编码 可选GB2312 我喜欢utf-8 据说utf8在某些客户端收信下会乱码
    $mail->CharSet = 'UTF-8';

    //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
    $mail->isHTML(true);
    //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
    // 添加收件人地址，可以多次使用来添加多个收件人
    if (is_array($toemail)) {
        foreach ($toemail as $to_email) {
            $mail->AddAddress($to_email);
        }
    } else {
        $mail->AddAddress($toemail);
    }

    //添加该邮件的标题
    $mail->Subject = $subject;

    //添加邮件正文 上方将isHTML设置成了true，则可以是完整的html字符串 如：使用file_get_contents函数读取本地的html文件
    $mail->Body = $body;
    //为该邮件添加附件 该方法也有两个参数 第一个参数为附件存放的目录（相对目录、或绝对目录均可） 第二参数为在邮件附件中该附件的名称
    //$mail->addAttachment('./d.jpg','mm.jpg');
    //同样该方法可以多次调用 上传多个附件
    //$mail->addAttachment('./Jlib-1.1.0.js','Jlib.js');
    //dump($mail);exit;

    //发送命令 返回布尔值
    //PS：经过测试，要是收件人不存在，若不出现错误依然返回true 也就是说在发送之前 自己需要些方法实现检测该邮箱是否真实有效
    $status = $mail->send();

    //简单的判断与提示信息
    if ($status) {
        //echo 'success';
        return true;
    } else {
        //dump($mail->ErrorInfo);
        return false;
    }
}

/**
 * 快速排序
 * @param array $arr
 * @return array
 */
function quick_sort($arr)
{
    $length = count($arr);
    if ($length <= 1) {
        return $arr;
    }

    $baseNum = isset($arr[0]) ? $arr[0] : '';
    $leftArray = $rightArray = [];

    foreach ($arr as $k => $v) {
        if ($arr[$k] < $baseNum) {
            $leftArray[] = $v;
        }
        if ($arr[$k] > $baseNum) {
            $rightArray[] = $v;
        }
    }

    $leftArray = quick_sort($leftArray);
    $rightArray = quick_sort($rightArray);

    return array_merge($leftArray, array($baseNum), $rightArray);
}

/**
 * generate_rand_sequence
 * @param integer $len
 * @return array
 */
function generate_rand_sequence($len)
{
    $sequence = $result = [];

    for ($i = 0; $i < $len; $i++) {
        $sequence[$i] = $i;
    }

    $end = $len - 1;

    for ($i = 0; $i < $len; $i++) {
        $num = rand(0, $end);
        $result[] = $sequence[$num];
        $sequence[$num] = $sequence[$end];
        $end--;
    }

    return $result;
}

/**
 * get_str_hash
 * @param string $str
 * @return int
 */
function get_str_hash($str)
{
    $len = strlen($str);
    $hash = 5381;

    for ($i = 0; $i < $len; $i++) {
        $hash += ($hash << 5) + ord($str{$i});
    }

    return $hash & 0x7FFFFFFF;
}

/**
 * binary_search
 * @param array $arr
 * @param integer $value
 * @return bool|int
 */
function binary_search($arr, $value)
{
    $len = count($arr);
    $left = 0;
    $right = $len - 1;
    while ($left <= $right) {
        $middle = $left + (($right - $left) >> 1);
        if ($arr[$middle] > $value) {
            $right = $middle - 1;
        } elseif ($arr[$middle] < $value) {
            $left = $middle + 1;
        } else {
            return $middle;
        }
    }
    return false;
}
