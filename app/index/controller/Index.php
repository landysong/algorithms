<?php
namespace app\index\controller;

use app\common\service\Type;
use app\common\model\Area;
use app\common\model\Example;
use app\common\model\Learn;


class Index
{
    public function index()
    {
        echo 211148 ^ 1234;
        $classifiModel = Area::column('area');
        $classifier = new \app\common\service\Classifier($classifiModel);

        $list = Learn::column('id,type,sample');
        foreach ($list as $k => $v){
            $classifier->learn($v['sample'], $v['type']);
            //print_r($classifier->getWords($v['sample']));
        }
        print_r($classifier->types);echo '<br/><br/>';
        print_r($classifier->words);echo '<br/><br/>';
        print_r($classifier->documents);echo '<br/><br/>';

        echo $classifier->guess('醴陵'),'<br>';
        echo $classifier->guess('北京'),'<br>';
        echo $classifier->guess('成都'),'<br>';
        $example = new Example();
        $rs = $example->limit(20)->select();

        foreach ($rs as $k => $v){
            //var_dump($classifier->guess($v)); // string(8) "positive"
            echo $v['title'],'---',$classifier->guess($v['title']),'<br>';
        }
    }
}
