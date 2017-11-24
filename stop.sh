#!/bin/bash
echo "Stoping..."
basepath=$(cd `dirname $0`; pwd)
cmd=$(pidof guanjianzi_swoole)
kill "$cmd"
echo "Stoped"




