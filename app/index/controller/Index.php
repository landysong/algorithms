<?php

namespace app\index\controller;

use app\common\extend\API;
use app\common\extend\Classifier;
use app\common\extend\Queue;
use app\common\extend\SpellCorrect;
use think\Cache;
use think\Controller;
use think\View;

class Index extends Controller
{
    public function index()
    {
        $classifiModel = model('Area')->column('area');
        //$classifier = new Classifier($classifiModel);
        $classifier = Classifier::getInstance();
        $classifier->init($classifiModel);

        $list = model('Learn')->column('id,type,sample');
        foreach ($list as $k => $v) {
            $classifier->learn($v['sample'], $v['type']);
            print_r($classifier->getWords($v['sample']));
        }
        print_r($classifier->types);
        echo '<br/><br/>';
        print_r($classifier->words);
        echo '<br/><br/>';
        print_r($classifier->documents);
        echo '<br/><br/>';

        $queue = Queue::getInstance();
        $queue->init('classifier');
        $queue->enQueue($classifier->guess('醴陵'));
        $queue->enQueue($classifier->guess('北京'));
        $queue->enQueue($classifier->guess('石家庄啊师'));
        $queue->enQueue($classifier->guess('阿啊上海'));
        $queue->enQueue($classifier->guess('的股北京斯'));
        $queue->enQueue($classifier->guess('天宇国际'));
        $queue->enQueue($classifier->guess('很明白成都'));
        $queue->destory();

        $rs = model('Example')->page(5, 20)->select();

        $content = '';
        foreach ($rs as $k => $v) {
            //var_dump($classifier->guess($v)); // string(8) "positive"
            $content .= $v['title'] . '---' . $classifier->guess($v['title']) . '<br>';
        }

        /*$msg = send_mail('songguanjin@258.com', '测试邮件', $content) ? '成功' : '失败';
        echo $msg;*/
    }

    public function quickSort()
    {
        $arr = generate_rand_sequence( 20);
        print_r($arr);
        echo '<br><br>';
        print_r(quick_sort($arr));
    }

    public function binarySearch()
    {
        $arr = quick_sort(generate_rand_sequence( 20));
        echo binary_search($arr, 5);
    }

    public function hash()
    {
        $cKey = get_str_hash('Example,5,20');
        $hashTable = Cache::get($cKey);
        if (empty($hashTable)) {
            $r = model('Example')->page(5, 20)->select();
            foreach ($r as $k => $v) {
                $hashTable[get_str_hash($v['title'])] = $v['title'];
            }
            Cache::set($cKey, $hashTable);
        }
        echo '<pre>';
        print_r($hashTable);
    }

    public function getSplitWord()
    {
        $string = '北欧床实木风格双人床日式现代简约实木 床 婚床1.8米1.5主卧家具';
        print_r(get_split_word($string));
    }

    public function spellCorrect()
    {
        $word = input('post.word', '', 'trim');
        if (!empty($word)) {
            $spellCorrect = new SpellCorrect('../public/dict/big.txt');
            $this->assign('result', $spellCorrect->correct($word));
        }
        return view();
    }

}
