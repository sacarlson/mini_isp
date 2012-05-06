#! /bin/sh
# forward all port 80 trafic to freenet web page except addresses in whiteslist.txt file
# eth0 is on internet router side, eth1 is on the wifi access point side
# eth0 is set to 192.168.2.240 on oldbox
# eth1 is set to 192.168.1.240 on oldbox
# this one modified to leave raj ip open with no login.
# must modify this line to match what we assign to raj
RAJ=192.168.2.101
RAJ2=192.168.2.232

#
# rc.firewall-iptables
FWVER=0.762
#
#  in scotty's case the eth0 port is on my internet router side
#    and eth1 is on the wifi access point side.
#    and rausb0 is added as bridge to repeater wifi such as sandy hotel or hello wifi. 
#
IPTABLES=/sbin/iptables
DEPMOD=/sbin/depmod
MODPROBE=/sbin/modprobe
BLACKLIST=/home/sacarlson/blacklist.txt
WHITELIST=/home/sacarlson/whitelist.txt
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
INTTAP="tap0"
echo "   External Interface router side:  $EXTIF"
echo "   Internal Interface access point side:  $INTIF"
echo "   Internal Interface:  $INTIF2"
echo "   Internal tap Interface:  $INTTAP"

EXTIP="192.168.2.241"
echo "   External IP:  $EXTIP"

#                             to
#                       FORWARD_IPV4=true
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


# Enable simple IP forwarding and Masquerading
#
#  NOTE:  In IPTABLES speak, IP Masquerading is a form of SourceNAT or SNAT.
#
#
echo "   Clearing any existing rules and setting default policy.."
$IPTABLES -P INPUT ACCEPT
$IPTABLES -F INPUT 
$IPTABLES -P OUTPUT ACCEPT
$IPTABLES -F OUTPUT 
$IPTABLES -P FORWARD DROP
$IPTABLES -F FORWARD 
$IPTABLES -t nat -F


echo 'Allowing Localhost'
#Allow localhost.
$IPTABLES -A INPUT -t filter -s 127.0.0.1 -j ACCEPT


#
## Blacklist
#

for x in `grep -v ^# $BLACKLIST | awk '{print $1}'`; do
        echo "Denying $x..."
        $IPTABLES -A INPUT -t filter -s $x -j DROP
done

# add filter to drop all port 21 and 22 from eth0 (world internet access)  to prevent attempted rsh breakin
# change of plan just allow all to bigboy and restrict prots 21 and 22... to all others see port allow list to change
$IPTABLES -A INPUT  -t filter -s 192.168.1.250 -j ACCEPT
$IPTABLES -A INPUT  -t filter -d 192.168.1.250 -j ACCEPT
$IPTABLES -A INPUT -t filter -d 192.168.2.250 -j ACCEPT
$IPTABLES -A INPUT -t filter -s 192.168.2.250 -j ACCEPT

#
## Permitted Ports
#allow dns port 53
iptables -A INPUT -t filter -p udp --sport 53 -j ACCEPT
#allow ping icmp port
iptables -A INPUT -p icmp  -j ACCEPT 

for port in $ALLOWED; do
        echo "Accepting port TCP $port..."
        $IPTABLES -A INPUT -t filter -p tcp --dport $port -j ACCEPT
done

for port in $ALLOWED; do
        echo "Accepting port UDP $port..."
        $IPTABLES -A INPUT -t filter -p udp --dport $port -j ACCEPT
done


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
#$IPTABLES -t nat -A PREROUTING -s 192.168.2.250 -j RETURN
$IPTABLES -t nat -A PREROUTING -d 192.168.2.241 -j RETURN
$IPTABLES -t nat -A PREROUTING -d 192.168.1.241 -j RETURN
$IPTABLES -t nat -A PREROUTING -d 10.0.0.241 -j RETURN
#modified for Raj
$IPTABLES -t nat -A PREROUTING -s $RAJ -j RETURN
$IPTABLES -t nat -A PREROUTING -s $RAJ2 -j RETURN

#added for openvpn
$IPTABLES -t nat -A PREROUTING -s 10.8.0.0/24 -j RETURN

for x in `grep -v ^# $WHITELIST | awk '{print $1}'`; do
        echo "Permitting $x..."
  $IPTABLES -t nat -A PREROUTING -i eth1 -p tcp --dport 80 -s $x -j RETURN
done

$IPTABLES -t nat -A PREROUTING -p tcp --dport 53 -j RETURN
$IPTABLES -t nat -A PREROUTING -p udp --dport 53 -j RETURN
$IPTABLES -t nat -A PREROUTING -p udp --sport 53 -j RETURN
$IPTABLES -t nat -A PREROUTING -p tcp --sport 53 -j RETURN
#$IPTABLES -t nat -A PREROUTING -p tcp  --dport 80 -j DNAT --to-destination 192.168.2.241:80
$IPTABLES -t nat -A PREROUTING -p tcp  -j DNAT --to-destination 192.168.2.241
$IPTABLES -t nat -A PREROUTING -p udp  -j DNAT --to-destination 192.168.2.241
# reset apaipac
#/usr/sbin/fetchipac -S
echo "end forfire.sh"
