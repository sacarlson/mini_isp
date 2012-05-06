#!/bin/bash
DATE=$(date)
# we will default with router on true network and if fails will try router_billion TOT next
# if that fails try router True again then if fails again
# leave it with true and give up will try again in 2 min.


echo "*****************switch_router started for date = $DATE" >> ping.log
echo " start test_network.sh"

  #if ! arping -c 1 192.168.2.180
  if ! ping -c 1 192.168.2.112
  then
    echo "fail first try"
    sleep 5
    #if ! arping -c 1 192.168.2.180
    if ! ping -c 1 192.168.2.112
    then
      echo "ping 112 failed"
      echo "failed 112 ping on date = $DATE" >> ping.log
      /home/sacarlson/test_alive.sh
      play /home/sacarlson/surething/working/sounds/does_not_compute.wav
    fi
  else
    echo "ping 112 ok"
    echo "ok 112 ping on date = $DATE" >> ping.log
  fi


if ! ping -c 1 192.168.2.5
then
  sleep 5
  if ! ping -c 1 192.168.2.5
  then
    echo "ping dlink failed"
    echo "failed dlink ping on date = $DATE" >> ping.log
    play /home/sacarlson/surething/working/sounds/does_not_compute.wav
  fi
else
  echo "ping dlink ok"
  echo "ok dlink ping on date = $DATE" >> ping.log
fi
  
for i in {1..5}
do
  if ping -c 1 google.com
  then
    echo "ok google.com ping on date = $DATE" >> ping.log
    echo "ok google.com ping on date = $DATE" 
    if test -f /home/sacarlson/netfail_active.txt
    then
      echo "netfail_active.txt mode was set, will disable now"
      rm /home/sacarlson/netfail_active.txt
      /home/sacarlson/masq.sh
    fi
    exit -1
  fi
  echo "ping try # $i failed"
  sleep 10
done
  
if test -f /home/sacarlson/netfail_active.txt
then
  echo "netfail_active.txt already set nothing more needed"
  exit -1
fi
echo "5th ping failed switching to failnet mode on date = $DATE" >> ping.log
echo "5th ping failed switching to failnet mode on date = $DATE"
play /home/sacarlson/surething/working/sounds/does_not_compute.wav
touch /home/sacarlson/netfail_active.txt
/home/sacarlson/netfail_switch_fail.sh
exit -1
  


echo "*****************test_network completed for date = $DATE" >> ping.log

