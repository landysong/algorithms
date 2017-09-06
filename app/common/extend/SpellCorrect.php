<?php
/**
 * Created by PhpStorm.
 * Author: Landy <songguanjin@258.com>
 * Date: 2017/8/24
 * Time: 16:41
 */

namespace app\common\extend;


class SpellCorrect
{
    public $WordsNum;

    public function __construct($text)
    {
        $file = fopen($text, 'r') or exit('无法读取文件');

        $WordsNum = [];
        while (!feof($file)) {
            preg_match_all('/[a-z]+/', strtolower(fgets($file)), $matchs);
//            echo '<pre>';
//            print_r($matchs);echo '<br>';
            foreach ($matchs[0] as $v) {
                if (array_key_exists($v, $WordsNum)) {
                    $WordsNum[$v]++;
                } else {
                    $WordsNum[$v] = 1;
                }
            }
        }

        fclose($file);

        $this->WordsNum = $WordsNum;
    }

    public function correct($word)
    {
        if (array_key_exists($word, $this->WordsNum)) {
            return $word;
        }
        $result = [];
        foreach ($this->WordsNum as $k => $v) {
            if (levenshtein($word, $k) === 1) {
                $result[$k] = $v;
            }
        }

        $result = array_keys(quick_sort($result));
//        return $result;
        return end($result);
    }
}