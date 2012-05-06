#!/bin/bash
#this worked ok make sure the key is updated
IPADDR=$(lynx -dump http://www.whatismyip.com/automation/n09230945.asp)
echo ipaddr = $IPADDR
local_ip="192.168.2.250"
	echo "server localhost" > ./nsupdate.txt
	echo 'key rndc-key yfBKheRzDF3PVPecl29m/w==' >> ./nsupdate.txt
	echo "zone surething.biz" >> ./nsupdate.txt
	echo "update delete ns.surething.biz " >> ./nsupdate.txt
	echo "update add ns.surething.biz 3500 A $IPADDR" >> ./nsupdate.txt
	echo "update delete surething.biz " >> ./nsupdate.txt
	echo "update add surething.biz 3500 A $IPADDR" >> ./nsupdate.txt
	echo "update delete freenet.surething.biz " >> ./nsupdate.txt
	echo "update add freenet.surething.biz 3500 A $local_ip" >> ./nsupdate.txt
	echo "update delete *.surething.biz " >> ./nsupdate.txt
	echo "update add *.surething.biz 3500 A $IPADDR" >> ./nsupdate.txt	
	echo "show" >> ./nsupdate.txt
	echo "send" >> ./nsupdate.txt
	echo "" >> ./nsupdate.txt
	/usr/bin/nsupdate -v -d ./nsupdate.txt
