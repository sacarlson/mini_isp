#! /bin/sh
# opentunnel.sh  by Scott Carlson aka sacarlson sacarlson@ipipi.com
# simple setup client openvpn tunnel with no encryption to a remote site
# to enable tunnel from wifi or firewaled network from the remote site.
# note: you will need to run this script as superuser of sudo
# on success you should see responce of:  Initialization Sequence Completed
# you will also need to install openvpn if not already installed with:
#sudo apt-get install openvpn

openvpn --remote remote.site.ip.or.com --dev tun0  --ifconfig 10.2.2.2 10.2.2.3

#on the server to prepare for connection with:
#sudo openvpn --dev tun0 --ifconfig 10.2.2.3 10.2.2.2
# when connected should see connection established message from openvpn
#with this the server side can connect direct to the remote client with ping 10.2.2.2
# or ssh 10.2.2.2
