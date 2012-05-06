#!/bin/bash
DATE=$(date)
if ! arping -c 1 192.168.2.180
then
  echo "fail first try"
  sleep 5
  if ! arping -c 1 192.168.2.180
  then
    echo "arping 180 failed"
    echo "failed 180 arping on date = $DATE" >> ping.log
    play /home/sacarlson/surething/working/sounds/does_not_compute.wav
  fi
else
  echo "ping 180 ok"
  echo "ok 180 ping on date = $DATE" >> ping.log
fi
  
