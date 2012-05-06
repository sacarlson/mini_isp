#!/bin/sh
#
# rc.firewall-iptables
FWVER=0.76
#
#               Initial SIMPLE IP Masquerade test for 2.6 / 2.4 kernels
#               using IPTABLES.  
#
#
#  in scotty's case the eth0 port is on my internet router side
#    and eth1 is on the wifi access point side.
#

echo -e "\n\nLoading simple rc.firewall-iptables version $FWVER..\n"
sleep 1
#turn off fairnat
cd /home/freenet/fairnat
/home/freenet/fairnat/fairnat-0.80-dhcp.sh stop
sleep 2
/home/freenet/ruby/gen_whitelist.rb
/home/freenet/ruby/make_fairnat.config.rb
/home/freenet/ruby/set_perm_ip.rb
/home/freenet/ruby/make_dhcpd.conf.rb
sudo cp /home/freenet/ruby/dhcpd.conf /etc/dhcp3/dhcpd.conf
sudo cp /home/freenet/ruby/dhcpd.conf /etc/dhcp/dhcpd.conf
sleep 2
sudo /etc/init.d/dhcp3-server restart
sudo /etc/init.d/isc-dhcp-server restart
# The location of the iptables and kernel module programs
#
#
# ** Please use the "whereis iptables" command to figure out 
# ** where your copy is and change the path below to reflect 
# ** your setup
# 
IPTABLES=/sbin/iptables
DEPMOD=/sbin/depmod
MODPROBE=/sbin/modprobe
WHITELIST=/home/freenet/ruby/whitelist.txt
#ifconfig eth0:1 192.168.2.247
#ifconfig eth0:3 192.168.2.249

#Setting the EXTERNAL and INTERNAL interfaces for the network
#
#  Each IP Masquerade network needs to have at least one
#  external and one internal network.  The external network
#  is where the natting will occur and the internal network
#  should preferably be addressed with a RFC1918 private address
#  scheme.
#
#  For this example, "eth0" is external and "eth1" is internal"
#
#
EXTIF="eth0"
INTIF="eth1"
INTTAP="tun+"
echo "   External Interface:  $EXTIF"
echo "   Internal Interface:  $INTIF"
echo "   tap  Interface    :  $INTTAP"

#CRITICAL:  Enable IP forwarding since it is disabled by default since
#
#
echo "   Enabling forwarding.."
echo "1" > /proc/sys/net/ipv4/ip_forward


# Dynamic IP users:
#
#   If you get your IP address dynamically from SLIP, PPP, or DHCP, 
#   enable this following option.  This enables dynamic-address hacking
#   which makes the life with Diald and similar programs much easier.
#
echo "   Enabling DynamicAddr.."
echo "1" > /proc/sys/net/ipv4/ip_dynaddr




#Clearing any previous configuration
#
#
#
echo "   Clearing any existing rules and setting default policy.."
iptables -X
iptables -F
echo "   Clearing any existing rules and setting default policy.."
$IPTABLES --flush                        
$IPTABLES --table nat --flush
$IPTABLES --delete-chain                  
$IPTABLES --table nat --delete-chain

$IPTABLES -P INPUT ACCEPT
$IPTABLES -F INPUT 
$IPTABLES -P OUTPUT ACCEPT
$IPTABLES -F OUTPUT 
$IPTABLES -P FORWARD DROP
$IPTABLES -F FORWARD 
$IPTABLES -t nat -F


echo "   FWD: Allow all connections OUT and all connections  IN"
for x in `grep -v ^# $WHITELIST | awk '{print $1}'`; do
 # added accounting of whithlisted users
  $IPTABLES -I FORWARD -d $x
  $IPTABLES -I FORWARD -s $x 
done
$IPTABLES -A FORWARD -i $EXTIF -j ACCEPT
$IPTABLES -A FORWARD -i $INTIF -j ACCEPT
$IPTABLES -A FORWARD -i $INTTAP -j ACCEPT

# filter only let local network use samba network
$IPTABLES -N SMB-IN
$IPTABLES -A SMB-IN -s 192.168.2.1/24 -j ACCEPT
$IPTABLES -A SMB-IN -s 192.168.1.1/24 -j ACCEPT
# this opens samba over openvpn but don't thinks that's wise to keep open all the time. bad for video downloads
#$IPTABLES -A SMB-IN -s 10.0.0.0/24 -j ACCEPT
$IPTABLES -A SMB-IN -s 127.0.0.1 -i lo -j ACCEPT
$IPTABLES -A INPUT -p tcp -m multiport --dports 139,445 -j SMB-IN
$IPTABLES -A INPUT -p tcp -m multiport --dports 139,445 -j DROP
$IPTABLES -A INPUT -p udp --dport 137:138 -j SMB-IN
$IPTABLES -A INPUT -p udp --dport 137:138 -j DROP
# end samba local filter

echo "   Enabling SNAT (MASQUERADE) functionality on $EXTIF"
$IPTABLES -t nat -A POSTROUTING -o $EXTIF -j MASQUERADE

# reset ipaipac
#/usr/sbin/fetchipac -S
#echo -e "\nrc.firewall-iptables v$FWVER done.\n"
