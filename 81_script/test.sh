#!/bin/bash


abs_dirname() {
  local cwd="$(pwd)"
  local path="$1"

  while [ -n "$path" ]; do
    cd "${path%/*}"
    local name="${path##*/}"
    path="$(readlink "$name" || true)"
  done

  pwd -P
  cd "$cwd"
}

SCRIPT_DIR="$(abs_dirname "$0")"

echo $SCRIPT_DIR



if [ $2 != "noclean" ]; then
    rm -rf /root/log/*.log
    $SCRIPT_DIR/../db/db.sh clean
fi
cd `dirname $1`
python `basename $1`
exit $?
