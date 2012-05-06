#!/bin/bash
DATE=$(date)
# we will default with router on true network and if fails will try router_billion TOT next
# if that fails try router True again then if fails again
# leave it with true and give up will try again in 2 min.
echo "*****************switch_router started for date = $DATE" >> ping.log
if ping -c 1 google.com
then
  echo "ok google.com ping on date = $DATE" >> ping.log
  echo "ok google.com ping on date = $DATE" 
  exit -1
else
  echo "bad google.com ping on date = $DATE" >> ping.log
  echo "bad google.com ping on date = $DATE"
  sleep 10
  if ping -c 1 google.com
  then
    echo "ok google.com ping on date = $DATE" >> ping.log
    echo "ok google.com ping on date = $DATE" 
  else
    echo "2nd ping failed switching to billion router tot on date = $DATE" >> ping.log
    echo "2nd ping failed switching to billion router tot on date = $DATE"
    play /home/sacarlson/surething/working/sounds/does_not_compute.wav
    sudo killall NetworkManager
    sudo ifconfig eth0 down
    sudo ifconfig eth0 bigboy
    sudo route del default
    sudo route add default gw tot
    sudo cp /etc/resolv.conf.tot /etc/resolv.conf 
  fi 
fi

sleep 10

if ping -c 1 google.com
then
  echo "ok google.com ping on date = $DATE" >> ping.log
  echo "ok google.com ping on date = $DATE"
  exit -1 
else
  echo "bad google.com ping on date = $DATE" >> ping.log
  echo "bad google.com ping on date = $DATE"
  sleep 10
  if ping -c 1 google.com
  then
    echo "ok google.com ping on date = $DATE" >> ping.log
    echo "ok google.com ping on date = $DATE" 
  else
    echo "2nd ping failed switching to Zyxel router True on date = $DATE" >> ping.log
    echo "2nd ping failed switching to router True on date = $DATE"
    play /home/sacarlson/surething/working/sounds/does_not_compute.wav
    sudo killall NetworkManager
    sudo ifconfig eth0 down
    sudo ifconfig eth0 192.168.2.250
    sudo route del default
    sudo route add default gw true
    sudo cp /etc/resolv.conf.true /etc/resolv.conf
  fi 
fi

sleep 10



if ping -c 1 google.com
then
  echo "ok google.com ping on date = $DATE" >> ping.log
  echo "ok google.com ping on date = $DATE"
  exit -1 
else
  echo "unable to fix, need human help!!!"
fi
echo "*****************switch_router completed for date = $DATE" >> ping.log

