#! /bin/sh
sleep 20
sudo /etc/init.d/bind9 restart
sudo /etc/init.d/apache2 restart
sudo /etc/init.d/dhcp3-server restart
