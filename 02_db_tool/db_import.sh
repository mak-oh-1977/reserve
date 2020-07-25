#!/bin/bash
# データ取込スクリプト
# 全データ削除と氏名の匿名化オプションあり
#

# usage
cmdname=`basename $0`
function usage()
{
  echo "Usage: ${cmdname} [-c] [-n] file" 1>&2
}

cd `dirname $0`

# check options
allclear=false
anony=true
while getopts cn OPTION
do
  case $OPTION in
    c)
      allclear=true
      ;;
    n)
      anony=false
      ;;
    \?)
      echo "NO"
      usage
      exit 1
      ;;
  esac
done
shift `expr $OPTIND - 1`

# check arguments
if [ $# -gt 1 ]; then
  usage
  exit 1
fi
arg1="$1"

if [ "$arg1" = "" ]; then
    usage
    exit 1
fi

if [ "$allclear" = true ]; then

mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME << EOS
drop schema $DB_NAME;
EOS

fi

outname=`basename $arg1 .bak`
openssl enc -d -aes-256-cbc -salt -k $DB_PASS -in $arg1 -out $outname.gz

gzip -d -f $outname.gz

# main
echo "import $arg1"
mysql -h $DB_HOST -u $DB_USER -p$DB_PASS < $outname

rm $outname

#氏名匿名化
if [ "$anony" = true ]; then
echo "to anonymouse";

mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME << EOS
SET NAMES utf8;

commit;

EOS

fi


exit 0


