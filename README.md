# php-filter

PHP敏感词过滤系统,采用php c扩展swoole开发。压测2W并发无error,实际并发根据服务器性能和响应时间做集群。

# 技术预研
```
敏感词过滤技术，采用搜索树算法，常用的算法：

1、trie 字典树算法【搜索快速，内存占用大】
2、DoubleArrayTrie 双数组树算法【基于trie的改进，速度相当，主要用双树结构来解决内存占用大的问题】
3、Aho Corasick自动机结合DoubleArrayTrie极速多模式匹配【Aho Corasick使得trie算法提速】

本系统，采用PHP编写的简单树算法。2,3通常用于百万以上敏感词库的过滤。
```
2有C实现的扩展，只支持PHP5.6以下不再升级。【实测，性能和php7+字典树差不多】
3只有JAVA的扩展。
```

RadixTree(基数树) 效率对比，推论字典树算法性能最优：http://www.hankcs.com/nlp/performance-comparison-of-several-trie-tree.html


```


# 安装
```

1、安装php7.1以上,性能比5.6提高1倍。
2、安装swoole 1.9扩展,该版本稳定。

linux环境一键安装包：https://github.com/cn-suibin/SD_SETUP

```

# 使用
```

1、字典树生成：sudo php reload_dict.php


2、服务开启：sudo sh start.sh


3、服务关闭：sudo sh stop.sh


4、浏览器输入:http://192.168.248.243:9502/?content=中华人民共和国

```


# 备注：
```


字典明文文件：dict.txt

生成的字典树：blackword.tree

```