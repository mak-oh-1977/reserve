#!/bin/bash

#/var/www　以下はアプリケーションフォルダのため
#  アプリケーションの展開が終わったタイミングで
#  フォルダの生成を行う


/usr/sbin/httpd -DFOREGROUND
#tail -f /dev/null
