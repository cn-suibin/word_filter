#!/bin/bash
#php app/cli.php Swooleserver test start
basepath=$(cd `dirname $0`; pwd)
echo $basepath
php $basepath/http_server.php >> swoole_pid.log &

