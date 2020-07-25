#!/bin/bash

SCRIPT_DIR=$(cd $(dirname $0); pwd)

if [ $# -le 0 ]; then
    echo "データベースのインポート、エクスポート、データ削除"
    echo "Usage: ${cmdname} [action] [option]"
    echo "  action は　import export clean"
    exit;
fi

if [ $1 = "import" ]; then
    $SCRIPT_DIR/db_import.sh $3 $2
fi


if [ $1 = "export" ]; then
    fn=db_`date +%Y%m%d%H%M%S`
    $SCRIPT_DIR/db_export.sh $fn
fi

if [ $1 = "clean" ]; then
    $SCRIPT_DIR/db_clean.sh
fi

if [ $1 = "init" ]; then
    $SCRIPT_DIR/db_init.sh
fi
