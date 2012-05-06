#!/bin/bash
DATE=$(date)
for i in {1..5}
do
  if ping -c 1 google.com > /dev/null 2>&1
  then
    echo "ok google.com ping on date = $DATE" >> imalive.log
    echo "ok" 
    exit -1
  fi
  sleep 10
done
echo "fail google.com ping on date = $DATE" >> imalive.log
echo "fail"
