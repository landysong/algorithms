<?php
/**
 * Created by PhpStorm.
 * User: 258
 * Date: 2017/5/27
 * Time: 14:35
 * 核心算法: P（Type,sentence）= P（Type）* P（sentence,Type）/ P（sentence）
 */

namespace app\common\extend;

class Classifier extends Factory
{
    public $types = array();    //类型
    public $words = array();    //词语
    public $documents = array();    //语句

    public function init($classifiModel = array())
    {
        $this->types = $classifiModel;
        foreach ($this->types as $k => $v) {
            $this->words[$v] = array();
            $this->documents[$v] = 0;
        }
    }

    public function guess($statement)
    {
        $words = $this->getWords($statement); // 得到单词
        $best_likelihood = 0;
        $best_type = null;
        foreach ($this->types as $type) {
            $likelihood = $this->pTotal($type); //计算 P(Type)
            foreach ($words as $word) {
                $likelihood *= $this->p($word, $type); // 计算 P(word, Type)
            }
            if ($likelihood > $best_likelihood) {
                $best_likelihood = $likelihood;
                $best_type = $type;
            }
        }
        return $best_type;
    }

    /**
     * 使用样本获取 词语对类型，语句对类型 的统计数
     * @param array|$statement
     * @param array|$type
     */
    public function learn($statement, $type)
    {
        $words = $this->getWords($statement);
        foreach ($words as $word) {
            if (!isset($this->words[$type][$word])) {
                $this->words[$type][$word] = 0;
            }
            $this->words[$type][$word]++; // 增加类型的词语统计
        }
        $this->documents[$type]++; // 增加类型的语句统计
    }

    /**
     * 定义对于给定的一个词是属于哪个确定类型的概率
     * @param $word
     * @param $type
     * @return float|int
     */
    public function p($word, $type)
    {
        $count = 0;
        if (isset($this->words[$type][$word])) {
            $count = $this->words[$type][$word];
        }
        return ($count + 1) / (array_sum($this->words[$type]) + 1);
    }

    /**
     * 定义输入的语句是给定类型中的一个的概率，+1 避免为0
     * @param $type
     * @return float|int
     */
    public function pTotal($type)
    {
        return ($this->documents[$type] + 1) / (array_sum($this->documents) + 1);
    }

    /**
     * 对语句进行分词
     * @param $string
     * @return array
     */
    public function getWords($string)
    {
//        $api = API::getInstance();
//        $r = $api->getFenCi($string);
//        return $r;
//        return self::mbStrSplit(preg_replace('/[A-Za-z0-9\s]/', '', strtolower($string)));
//        return preg_split('/\s+/', preg_replace('/[^A-Za-z0-9\s]/', '', strtolower($string)));
        return get_split_word($string);
    }

    private function mbStrSplit($string, $len = 1)
    {
        $start = 0;
        $array = array();
        $strlen = mb_strlen($string);
        while ($strlen) {
            $array[] = mb_substr($string, $start, $len, "utf8");
            $string = mb_substr($string, $len, $strlen, "utf8");
            $strlen = mb_strlen($string);
        }
        return $array;
    }
}
