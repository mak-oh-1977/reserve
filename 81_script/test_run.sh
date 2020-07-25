#!/bin/bash

echo "" > result.log

../db/db.sh clean
rm -rf /root/log/*.log

while read line
do
    echo $line "********************************************************************" >> result.log
    cur=`pwd`
    cd `dirname $line`
    python `basename $line` >> result.log
    if [ $? -ne 0 ]; then
        exit
    fi
    cd $cur

done << EOS
EOS


