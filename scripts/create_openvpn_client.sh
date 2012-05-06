#! /bin/bash
filename="/home/freenet/openvpn_client.sh"
if [ -e $filename ]
then
  echo "$filename exists nothing more will be done"
  exit 1
fi 
echo "$filename not found will create one"

sudo echo '#! /bin/sh' > $filename
sudo echo ' if [ "$(pidof openvpn)" ] ' >> $filename
sudo echo ' then ' | cat $filename
sudo echo '  echo "openvpn is running nothing done" ' >> $filename
sudo echo ' else ' | cat $filename
sudo echo '  echo "openvpn is not running will start now" ' >> $filename
sudo echo '  sudo /usr/sbin/openvpn --remote freenet.surething.biz --dev tun0  --ifconfig 10.2.2.2 10.2.2.3 ' >> $filename
sudo echo ' fi ' >> $filename

sudo chmod +x $filename
echo "*/15 * * * * $filename  >/dev/null 2>&1" | sudo crontab -
sudo chmod +x $filename


