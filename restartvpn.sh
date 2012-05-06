#! /bin/sh
sudo killall openvpn
sleep 5
sudo /usr/sbin/openvpn --remote freenet.surething.biz --dev tun0 --ifconfig 10.2.2.2 10.2.2.3
 
