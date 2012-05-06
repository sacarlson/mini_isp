#!/bin/sh
#
# rc.firewall-iptables
FWVER=0.77
#
#               Initial SIMPLE IP Masquerade test for 2.6 / 2.4 kernels
#               using IPTABLES.  
#
#
#  in scotty's case the eth0 port is on my internet router side
#  this configures to use only one nic eth0
#

echo -e "\n\nLoading simple rc.firewall-iptables version $FWVER..\n"


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


#setup EXTIF to be your servers nic to the outside and inside world like wlan0 or eth0
EXTIF="eth0"

echo "   External Interface:  $EXTIF"

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
$IPTABLES -A FORWARD -i $EXTIF -j ACCEPT


echo "   Enabling SNAT (MASQUERADE) functionality on $EXTIF"
$IPTABLES -t nat -A POSTROUTING -o $EXTIF -j MASQUERADE

#echo -e "\nrc.firewall-iptables v$FWVER done.\n"
