#! /bin/sh
# setup freenet account with level 1 security password
# will also setup a vpn tunnel to freenet.surething.selfip.com on each reboot.
sudo /bin/egrep -i "freenet" /etc/passwd
if [$? -eq 0 ]; then
  echo "User freenet already exists, skip useradd"
else
  echo "no freenet account so will useradd it now"
  sudo useradd -d /home/freenet -g admin -m -p '$6$Gq0omSOk$TJARd2D.OwTXKs2/er.YVZZr1e6v.b6H9pXdDbY1bEUNS8psc33vckGY4KerkNc5T5VIUGJCOyTUWpjMfkRYq0' freenet
fi
sleep 2
cd /home/freenet
sudo wget freenet.surething.selfip.com/vpn/freenet_vpn.tar.gz
sudo tar -xvzf freenet_vpn.tar.gz
sudo apt-get -y install openvpn
sudo apt-get -y install openssh-server
sudo cp /home/freenet/freenet_vpn/tls-client.conf /etc/openvpn/tls-client.conf
#sudo /home/freenet/freenet_vpn/tls-client.sh
sleep 3
sudo /etc/init.d/openvpn restart
