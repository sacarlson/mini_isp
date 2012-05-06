#!/bin/bash
DATE=$(date)

if sudo arping -I eth0 -c 1 192.168.2.188
then
   echo "ok ? 13 floor wifi 192.168.2.188 arping on date = $DATE" >> ping.log
   echo "ok ? 13floor wifi 192.168.2.188 arping  on date = $DATE" 
else
   echo "paul wifi arping bad  on date = $DATE" >> ping.log
   echo "bad paul wifi arping bad  on date = $DATE" 
fi


if ping -c 1 tot
then
   echo "ok router_billion tot ping on date = $DATE" >> ping.log
   echo "ok router_billion tot ping on date = $DATE" 
else
   echo "bad router_billion ping on date = $DATE" >> ping.log
   echo "bad router_billion ping on date = $DATE" 
fi


if ping -c 1 google.com
then
  echo "ok google.com ping on date = $DATE" >> ping.log
  echo "ok google.com ping on date = $DATE" 
else
  echo "bad google.com ping on date = $DATE" >> ping.log
  echo "bad google.com ping on date = $DATE"
  sleep 10
  if ping -c 1 google.com
  then
    echo "ok google.com ping on date = $DATE" >> ping.log
    echo "ok google.com ping on date = $DATE" 
  else
    echo "bad 2nd alarmed google.com ping on date = $DATE" >> ping.log
    echo "bad 2nd alarmed google.com ping on date = $DATE"
    play /home/freenet/danger_will_robinson.wav
  fi 
fi

echo "*****************ping test completed for date = $DATE" >> ping.log

