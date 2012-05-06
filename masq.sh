#! /bin/bash
# forward all port 80 trafic to freenet web page except addresses in whiteslist.txt file
# eth0 is on internet router side, eth1 is on the wifi access point side
# eth0 is set to 192.168.2.240 on oldbox
# eth1 is set to 192.168.1.240 on oldbox
#added ip address  for quick shut down of single custom users.
# see /etc/dhcp3/dhcpd.conf for details
# may also require macchanger.
#  small change for netfail_active mode disable
 
#
# rc.firewall-iptables
FWVER=0.762
#
#  in scotty's case the eth0 port is on my internet router side
#    and eth1 is on the wifi access point side.
#    and rausb0 is added as bridge to repeater wifi such as sandy hotel or hello wifi. 

#turn off fairnat
cd /home/freenet/fairnat
/home/freenet/fairnat/fairnat-0.80-dhcp.sh stop
#

IPTABLES=/sbin/iptables
DEPMOD=/sbin/depmod
MODPROBE=/sbin/modprobe
BLACKLIST=/home/freenet/blacklist.txt
WHITELIST=/home/freenet/ruby/whitelist.txt
#WHITELIST2=/home/freenet/not_expired.txt
#
## Specify ports you wish to use.
#

#ALLOWED="22 25 53 80 443 465 587 993 8080"

#ALLOWED="22 53 80 443 465 587 993 137 138 139 445"
# added 6881:6999 to try to enable torrentflux
#ALLOWED=" 53 80 443 465 587 993 8080 4881:6999"
#ALLOWED="22 53 80 443 465 587 993 999:65000"
ALLOWED="21:65000"

#Setting the EXTERNAL and INTERNAL interfaces for the network
#EXTIF = BILLION ROUTER SIDE
#INTIF = DLINK ACCESSPOINT SIDE
EXTIF="eth0"
INTIF="eth1"
INTIF2="rausb0"
INTTAP="tun+"
echo "   External Interface router side:  $EXTIF"
echo "   Internal Interface access point side:  $INTIF"
echo "   Internal Interface:  $INTIF2"
echo "   Internal tap Interface:  $INTTAP"

EXTIP="192.168.2.112"
echo "   External IP:  $EXTIP"

#                             to
#                       FORWARD_IPV4=true
#
echo "   Enabling forwarding.."
echo "1" > /proc/sys/net/ipv4/ip_forward

rm /var/www/freenet.surething.biz/index.php
rm /home/freenet/netfail_active.txt
ln -s /var/www/freenet2.surething.biz/index.php.netup /var/www/freenet2.surething.biz/index.php

/home/freenet/ruby/gen_whitelist.rb

sleep 1
#/home/freenet/mod_fairnat_config.rb
#ifconfig eth0:1 192.168.2.247
#ifconfig eth0:2 192.168.2.248
#ifconfig eth0:3 192.168.2.249

#vpn_enable="true"
vpn_enable="false"
if [ $vpn_enable = 'true' ]; then
  echo "vpn enabled"
  ifconfig eth0:2 down
  #openvpn didn't start correct at boot so restart here
  #note vpn uses 192.168.1.112 network so must not have eth0 with that network here
  /home/sacarlson/vpn/tls-server_freenet.sh
  sleep 6
else
  echo "vpn disabled"
  ifconfig tun0 down
  sleep 1
  ifconfig eth0 down
  sleep 2
  ifconfig eth0 $EXTIP
  #ifconfig eth0:2 192.168.1.250
  #ifconfig eth0:2 192.168.1.253
  sudo route del default
  sudo route add default gw router
fi

/home/freenet/ruby/set_perm_ip.rb
/home/freenet/ruby/gen_whitelist.rb
/home/freenet/ruby/update_tracker.rb
/home/freenet/ruby/make_fairnat.config.rb
/home/freenet/ruby/make_dhcpd.conf.rb

# Dynamic IP users:
#
#   If you get your IP address dynamically from SLIP, PPP, or DHCP, 
#   enable this following option.  This enables dynamic-address hacking
#   which makes the life with Diald and similar programs much easier.
#
echo "   Enabling DynamicAddr.."
echo "1" > /proc/sys/net/ipv4/ip_dynaddr


# Enable simple IP forwarding and Masquerading
#
#  NOTE:  In IPTABLES speak, IP Masquerading is a form of SourceNAT or SNAT.
#
#
echo "   Clearing any existing rules and setting default policy.."
$IPTABLES --flush                        
$IPTABLES --table nat --flush
$IPTABLES --delete-chain                  
$IPTABLES --table nat --delete-chain
# above added later
$IPTABLES -P INPUT ACCEPT
$IPTABLES -F INPUT 
$IPTABLES -P OUTPUT ACCEPT
$IPTABLES -F OUTPUT 
$IPTABLES -P FORWARD DROP
$IPTABLES -F FORWARD 
$IPTABLES -t nat -F


# add gateway to network for openvpn client
sleep 1
route add -net 192.168.1.0 netmask 255.255.255.0 gw 10.0.0.2
#
## Blacklist
#

for x in `grep -v ^# $BLACKLIST | awk '{print $1}'`; do
        echo "Denying $x..."
        $IPTABLES -A INPUT -t filter -s $x -j DROP
	$IPTABLES -A INPUT -t filter -d $x -j DROP
	$IPTABLES -A FORWARD -d $x -j DROP
	$IPTABLES -A FORWARD -s $x -j DROP
done

echo 'Allowing Localhost'
#Allow localhost.
$IPTABLES -A INPUT -t filter -s 127.0.0.1 -j ACCEPT

# add filter to drop all port 21 and 22 from eth0 (world internet access)  to prevent attempted rsh breakin
# change of plan just allow all to bigboy and restrict prots 21 and 22... to all others see port allow list to change
#$IPTABLES -A INPUT  -t filter -s 192.168.1.250 -j ACCEPT
#$IPTABLES -A INPUT  -t filter -d 192.168.1.250 -j ACCEPT
$IPTABLES -A INPUT -t filter -d $EXTIP -j ACCEPT
$IPTABLES -A INPUT -t filter -s $EXTIP -j ACCEPT
$IPTABLES -A INPUT -t filter -s 10.0.0.1 -j ACCEPT
$IPTABLES -A INPUT -t filter -d 10.0.0.1 -j ACCEPT

#
## Permitted Ports
#allow dns port 53
$IPTABLES -A INPUT -t filter -p udp --sport 53 -j ACCEPT
#allow ping icmp port
$IPTABLES -A INPUT -p icmp  -j ACCEPT 

for port in $ALLOWED; do
        echo "Accepting port TCP $port..."
        $IPTABLES -A INPUT -t filter -p tcp --dport $port -j ACCEPT
done

for port in $ALLOWED; do
        echo "Accepting port UDP $port..."
        $IPTABLES -A INPUT -t filter -p udp --dport $port -j ACCEPT
done

# allow all trafic to and from ports 80 and 443 
#drop all ports above 1000 (from 1000 to 65535) trafic to see if that will stop some of my masive trafic from bit torrents
#iptables -A FORWARD -t filter -p tcp --destination-port 80 -j ACCEPT
#iptables -A FORWARD -t filter -p tcp --destination-port 443 -j ACCEPT
#iptables -A FORWARD -t filter -p tcp --source-port 80 -j ACCEPT
#iptables -A FORWARD -t filter -p tcp --source-port 443 -j ACCEPT
#iptables -A FORWARD -t filter -p udp --destination-port 1000:65535 -j DROP
#iptables -A FORWARD -t filter -p udp --source-port 1000:65535 -j DROP
#iptables -A FORWARD -t filter -p tcp --destination-port 1000:65535 -j DROP
#iptables -A FORWARD -t filter -p tcp --source-port 1000:65535 -j DROP


# filter only let local network use samba network
$IPTABLES -N SMB-IN
$IPTABLES -A SMB-IN -s 192.168.2.1/24 -j ACCEPT
$IPTABLES -A SMB-IN -s 192.168.1.1/24 -j ACCEPT
# this opens samba over openvpn but only good if you need it. not good for video downloads
#$IPTABLES -A SMB-IN -s 10.0.0.0/24 -j ACCEPT
$IPTABLES -A SMB-IN -s 127.0.0.1 -i lo -j ACCEPT

$IPTABLES -I INPUT -p tcp -m multiport --dports 139,445 -j DROP
$IPTABLES -I INPUT -p udp --dport 137:138 -j DROP
$IPTABLES -I INPUT -p udp --dport 137:138 -j SMB-IN
$IPTABLES -I INPUT -p tcp -m multiport --dports 139,445 -j SMB-IN
# end samba local filter

$IPTABLES -A INPUT -m state --state RELATED,ESTABLISHED -j ACCEPT
$IPTABLES -A INPUT -p udp -j DROP
$IPTABLES -A INPUT -p tcp --syn -j DROP


#echo "   FWD: Allow all connections OUT and only existing and related ones IN"
#$IPTABLES -A FORWARD -i $EXTIF -o $INTIF -m state --state ESTABLISHED,RELATED -j ACCEPT
#$IPTABLES -A FORWARD -i $INTIF -o $EXTIF -j ACCEPT
#$IPTABLES -A FORWARD -j LOG

echo "   FWD: Allow all connections OUT and all connections  IN"
$IPTABLES -A FORWARD -i $EXTIF -j ACCEPT
$IPTABLES -A FORWARD -i $INTIF -j ACCEPT
$IPTABLES -A FORWARD -i $INTIF2 -j ACCEPT
$IPTABLES -A FORWARD -i $INTTAP -j ACCEPT

#$IPTABLES -t nat -A POSTROUTING -o $EXTIF -j SNAT --to $EXTIP

echo "   Enabling SNAT (MASQUERADE) functionality on $EXTIF"
$IPTABLES -t nat -A POSTROUTING -o $EXTIF -j MASQUERADE

echo -e "\nrc.firewall-iptables v$FWVER done.\n"


#WHITELIST=/home/sacarlson/whitelist.txt
#
## Whitelist
#
$IPTABLES -t nat -A PREROUTING -d $EXTIP -j RETURN
#$IPTABLES -t nat -A PREROUTING -d 192.168.1.250 -j RETURN

# added for openvpn
$IPTABLES -t nat -A PREROUTING -d 192.168.1.0/24 -j RETURN
$IPTABLES -t nat -A PREROUTING -s 10.0.0.0/24 -j RETURN
#$IPTABLES -A FORWARD -i tun0 -o eth0 -s 10.0.0.0/24  -j ACCEPT

# added accounting
$IPTABLES -I FORWARD -p tcp --sport 443
$IPTABLES -I FORWARD -p tcp --dport 443
$IPTABLES -I FORWARD -p tcp --sport 80
$IPTABLES -I FORWARD -p tcp --dport 80
# account for all udp activity above port 1023
$IPTABLES -I FORWARD -p udp --sport 1024:
$IPTABLES -I FORWARD -p udp --dport 1024:

for x in `grep -v ^# $WHITELIST | awk '{print $1}'`; do
 # added accounting of whithlisted users
  $IPTABLES -I FORWARD -d $x
  $IPTABLES -I FORWARD -s $x 
  $IPTABLES -t nat -A PREROUTING -s $x -j RETURN
done



$IPTABLES -t nat -A PREROUTING -p tcp --dport 53 -j RETURN
$IPTABLES -t nat -A PREROUTING -p udp --dport 53 -j RETURN
$IPTABLES -t nat -A PREROUTING -p udp --sport 53 -j RETURN
$IPTABLES -t nat -A PREROUTING -p tcp --sport 53 -j RETURN
$IPTABLES -t nat -A PREROUTING -p tcp  -j DNAT --to-destination $EXTIP
$IPTABLES -t nat -A PREROUTING -p udp  -j DNAT --to-destination $EXTIP


# reset apaipac
#/usr/sbin/fetchipac -S

#turn on fairnat
cd /home/freenet/fairnat
/home/freenet/fairnat/fairnat-0.80-dhcp.sh
#
echo "end forfire.sh"
