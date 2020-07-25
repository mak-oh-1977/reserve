#!/bin/bash
# 初期ＤＢ作成スクリプト
# 現状のＤＢからデータを削除し、初期ＤＢを作成
#

# usage
pass=$MYSQL_ROOT_PASSWORD

cd `dirname $0`


mysqldump -h $DB_HOST -u $DB_USER -p$DB_PASS -B analysis > db_tmp.sql


mysql -h $DB_HOST -u $DB_USER -p$DB_PASS  analysis << EOS
update 030m_op_company set mail = '';

delete from 032m_user where groupid > 100000;

delete from 090t_ope_log;
delete from 091t_wk_log;

delete from 110t_order;
delete from 111t_order_detail;

delete from 200t_sample;
delete from 201t_sample_del;
delete from 210t_request;
delete from 211t_request_del;

delete from 320t_personal;
delete from 325t_kk_uketuke;
delete from 326t_wk_sjnk;


delete from 520t_dispensing;
delete from 521t_dispensing_item;
delete from 530t_plate;
delete from 531t_plate_item;


delete from 600t_quality_control;
delete from 601t_quality_control_data;

delete from 620t_worklist;
delete from 621t_worklist_item;

delete from 650t_raw_density;
delete from 660t_raw_density;
delete from 670t_final_density where division='Y' or division = 'T';
delete from 690t_risk where division='Y' or division = 'T';



delete from 620t_worklist;
delete from 621t_worklist_item;

delete from 800t_invoice;
delete from 800t_invoice_item;
delete from 800t_invoice_others;
delete from 801t_payment;
delete from 802t_invoice_delivery;


delete from 900m_company where  companyid > 100000;
delete from 910m_group where companyid > 100000;
delete from 920t_contact;
delete from 930t_news;
delete from 931t_news_read;

delete from t_keep_data;

EOS


mysqldump -h $DB_HOST -u $DB_USER -p$DB_PASS -B analysis > db.sql
mv db.sql /docker-entrypoint-initdb.d/db.sql

mysql -h $DB_HOST -u $DB_USER -p$DB_PASS analysis < db_tmp.sql

rm db_tmp.sql



