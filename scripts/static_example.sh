#! /bin/sh
# this will setup a temp static address on a ubuntu box  by sacarlson  aka sacarlson@ipipi.com
# modify the ubuntuboiphere and gatewaaddresss to the values you need
# also change eth0 if needed for another device like eth1
sudo service network-manager stop
sudo ifconfig eth0 down
sudo ifconfig eth0 ubuntuboxiphere
sudo route del default
sudo route add default gw gatewayaddresshere
#you will need to create the /etc/resolv.conf.my  file with values you need
#example add line:  nameserver 208.67.222.222
# to setup opendns.com dns server
sudo cp /etc/resolv.conf.my /etc/resolv.conf
