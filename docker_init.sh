#!/bin/bash
# データ取込スクリプト
# 全データ削除と氏名の匿名化オプションあり
#

# usage
docker-compose down


docker system prune -a
docker volume rm $(docker volume ls -qf dangling=true)
docker rmi $(docker images -q)

