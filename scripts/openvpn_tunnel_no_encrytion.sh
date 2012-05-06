#! /bin/sh
# opentunnel.sh  by Scott Carlson aka sacarlson sacarlson@ipipi.com
# setup client openvpn tunnel with no encryption to freenet.surething.biz
# to enable tunnel from wifi or firewaled network
# note: you will need to run this script as superuser of sudo
# on success you should see responce of:  Initialization Sequence Completed
# you will also need to install openvpn if not already installed with:
#sudo apt-get install openvpn

sudo openvpn --remote freenet.surething.biz --dev tun0  --ifconfig 10.2.2.2 10.2.2.3

#on the server to prepare for connection with:
#sudo openvpn --dev tun0 --ifconfig 10.2.2.3 10.2.2.2
#with this the server side can connect direct to the remote with ping 10.2.2.2
# or ssh 10.2.2.2
