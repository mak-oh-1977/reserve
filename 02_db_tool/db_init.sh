#!/bin/bash
# 初期ＤＢ作成スクリプト
# 現状のＤＢからデータを削除し、初期ＤＢを作成
#

# usage
pass=$MYSQL_ROOT_PASSWORD

cd `dirname $0`


mysqldump -h $DB_HOST -u $DB_USER -p$DB_PASS -B $DB_NAME > db_tmp.sql


mysql -h $DB_HOST -u $DB_USER -p$DB_PASS  $DB_NAME << EOS
delete from 100t_shop;
delete from 090t_ope_log;

EOS


mysqldump -h $DB_HOST -u $DB_USER -p$DB_PASS -B $DB_NAME > db.sql
mv db.sql /docker-entrypoint-initdb.d/db.sql

mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME < db_tmp.sql

rm db_tmp.sql



