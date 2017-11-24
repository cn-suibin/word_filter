<?php
/**
 * by suibin
 * 2017-9-11
 */

// 设置脚本最大运行内存，根据字典大小调整
ini_set('memory_limit', '1024M');

// 设置时区
date_default_timezone_set('Asia/Shanghai');

// 加载助手文件
require_once('FilterHelper.php');

// http服务绑定的ip及端口
$serv = new swoole_http_server("0.0.0.0", 9502);



// 字典树文件路径，默认当时目录下
$tree_file = 'blackword.tree';

// 清除文件状态缓存
clearstatcache();

// 获取请求时，字典树文件的修改时间
$new_mtime = filemtime($tree_file);

// 获取最新trie-tree对象
$trie = FilterHelper::get_trie($tree_file, $new_mtime);

$serv->set(array(
       //'worker_num'=>4,//默认不设置，或者CPU数量
       //'max_request'=>10000,//限制并发数，会降低性能
    //'daemonize' => 1,//不能开
    'log_file' => 'swoole_server.log',
//    'user' => 'www',
//    'group' => 'www'
));

 $serv->on('Start', function($serv) use($trie){

        $name="guanjianzi_swoole";

        if (function_exists('cli_set_process_title')) {
            cli_set_process_title($name);
        } else {
            if (function_exists('swoole_set_process_name')) {
                swoole_set_process_name($name);
            } else {
                trigger_error(__METHOD__ . " failed. require cli_set_process_title or swoole_set_process_name.");
            }
        }

});

/**
 * 处理请求
 */
$serv->on('Request', function($request, $response) use($trie) {

//    xhprof_enable();

    // 接收get请求参数
    $content = isset($request->get['content']) ? $request->get['content']: '';

    $arr_ret = array();

    if (!empty($content)) {
	$arr_ret['memory'] = (memory_get_peak_usage() / 1024 / 1024) . 'M';
        // 执行查找敏感词
        $stime = microtime(true);
        $arr_ret['data'] = $trie->search_all($content);
        $etime = microtime(true);
	    $arr_ret['time'] = sprintf('%01.6f', $etime-$stime);
		//	echo json_encode($arr_ret)."\n";
    }



//    $arr_ret['time'] = sprintf('%01.6f', $etime-$stime);

//    $arr_ret['memory'] = (memory_get_peak_usage() / 1024 / 1024) . 'M';

//    $xhprof_data = xhprof_disable();
//    $XHPROF_ROOT = '/var/www/xhprof';
//    include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
//    include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";
//    $xhprof_runs = new XHProfRuns_Default();
//    $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");

    // 定义http服务信息及响应处理结果
    $response->cookie("User", "founder");
    $response->header("X-Server", "http WebServer(Unix) (Red-Hat/Linux)");
    $response->header('Content-Type', 'Content-Type: text/html; charset=utf-8');
    $response->end(json_encode($arr_ret));
});

$serv->start();
