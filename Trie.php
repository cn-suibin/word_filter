<?php
/**
 * 单树算法
 * Date: 2017/8/29
 * Time: 上午10:56
 * by suibin
 */

//树节点结构体
class Node
{
    public $value = null;//词
    public $children = array();//节点
}

//树
class Trie
{
    public $root;
    public $max_len;

    public function __construct()
    {
        $this->root = new Node();
        mb_internal_encoding("UTF-8");
    }

//创建树节点
    public function insert($word)
    {
        $node =& $this->root;

        $word_len = mb_strlen($word);

        if ($word_len> $this->max_len) {
            $this->max_len = $word_len;
        }

        for ($i=0; $i<$word_len; $i++) {

            $char = mb_substr($word, $i, 1);

            if (!array_key_exists($char, $node->children)) {
                $child = new Node();
                $node->children[$char] = $child;
                $node =& $node->children[$char];

            } else {
                $node =& $node->children[$char];
            }
        }

        $node->value = $word;
    }

//生成字典树
    public function load_words($file)
    {
        $handle = fopen($file, 'r');

        while (!feof($handle)) {
            $word = rtrim(fgets($handle));

            if (!empty($word)) {
                $this->insert($word);
            }
        }
    }

//单词查询字典树
    public function search($word)
    {
        $node = $this->root;

        $word_len = mb_strlen($word);

        for ($i=0; $i<$word_len; $i++) {
            $char = strtolower(mb_substr($word, $i, 1));

            if (!array_key_exists($char, $node->children)) {
                return $node->value;
            } else {
                $node = $node->children[$char];
            }
        }

        return $node->value;
    }

//段落查询字典树,每个字符从字典树中遍历max_len长度，
    public function search_all($text)
    {
        $result = array();

        $str_len = mb_strlen($text);

        for ($i = 0; $i <$str_len ; $i++) {

            $search_word = mb_substr($text, $i, $this->max_len);
            $filter_word = $this->search($search_word);
            if ($filter_word) {
                $result[] = $filter_word;
            }
        }

        return array_unique($result);
    }
}