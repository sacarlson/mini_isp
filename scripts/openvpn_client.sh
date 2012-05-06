#! /bin/sh
if [ "$(pidof openvpn)" ] 
then
  echo "openvpn is running nothing done"
else
  echo "openvpn is not running will start now"
  sudo /usr/sbin/openvpn --remote freenet.surething.biz --dev tun0  --ifconfig 10.2.2.2 10.2.2.3
fi

