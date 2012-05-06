#!/bin/bash
IFACE="eth0"
TTL=3600
SERVER=localhost
HOSTNAME=test2.surething.biz
ZONE=surething.biz
KEYFILE=/etc/bind/rndc.key
 
#new_ip_address=`ifconfig $IFACE | grep "inet addr:" | awk '{print $2}' | awk -F ":" '{print $2}'`
#new_ip_address=${new_ip_address/ /}
new_ip_address="192.168.2.223"
 
nsupdate -v -k $KEYFILE << EOF
server $SERVER
zone $ZONE
update delete $HOSTNAME A
update add $HOSTNAME $TTL A $new_ip_address
send
EOF
