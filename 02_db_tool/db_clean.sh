#!/bin/bash
# 初期ＤＢ作成スクリプト
# 現状のＤＢからデータを削除し、初期ＤＢを作成
#

# usage
cd `dirname $0`


echo "allclear userdata";

mysql -h $DB_HOST -u $DB_USER -p$DB_PASS analysis << EOS
SET NAMES utf8;

EOS
