<?php
/**
 * Created by PhpStorm.
 * User: 258
 * Date: 2017/5/28
 * Time: 9:57
 */

namespace app\common\extend;


class API extends Factory
{
    private $url = 'http://api.ltp-cloud.com/analysis/';
    private $api_key = 'z1z1c8p6w98tNitklzir3nLA4bpPhUOdUUULqOdU';
    private $format = 'json';
    private $requestUrl = '';

    public function __construct()
    {
        $this->requestUrl = $this->url . '?api_key=' . $this->api_key;
    }

    public function curlPost($url, $postFields, $headerData = array())
    {
        $postFields = http_build_query($postFields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        if (!empty($headerData)) {
            $headerArr = array();
            foreach ($headerData as $i => $v) {
                $headerArr[] = $i . ':' . $v;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerData);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    function curlGet($url, $headerData = array())
    {
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($headerData)) {
            $headerArr = array();
            foreach ($headerData as $i => $v) {
                $headerArr[] = $i . ':' . $v;
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArr);
        }
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }


    public function getFenCi($text, $pattern = 'ws')
    {
        $url = $this->requestUrl . "&text=$text&pattern=$pattern&format=$this->format";
        $rs = self::curlGet($url);
        if(!empty($rs)){
            $rs = json_decode($rs);
            $r = array();
            foreach (json_decode(json_encode($rs[0][0]),true) as $k => $v){
                $r[] = $v['cont'];
            }
            return $r;
        }
        return array('获取分词失败');
        //return json_decode(json_encode(json_decode($rs),true));
    }
}