#!/bin/bash

pass=$MYSQL_ROOT_PASSWORD

cd `dirname $0`

mysqldump -h $DB_HOST -u $DB_USER -p$DB_PASS -B $DB_NAME > $1

gzip $1
openssl enc -e -aes-256-cbc -salt -k $DB_PASS -in $1.gz -out $1.bak

rm $1.gz
