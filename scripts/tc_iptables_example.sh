DEV="eth0"
RATE="3000"
tc qdisc add dev $DEV root handle 1: htb default 22

tc class add dev $DEV parent 1: classid 1:1 htb rate ${RATEUP}kbit

tc class add dev $DEV parent 1:1 classid 1:20 htb rate $[40*$RATEUP/100]kbit \
	ceil ${RATEUP}kbit prio 0
tc class add dev $DEV parent 1:1 classid 1:21 htb rate $[40*$RATEUP/100]kbit \
	ceil ${RATEUP}kbit prio 1
tc class add dev $DEV parent 1:1 classid 1:22 htb rate $[19*$RATEUP/100]kbit \
	ceil $[20*RATEUP/100]kbit prio 2
tc class add dev $DEV parent 1:1 classid 1:23 htb rate $[1*$RATEUP/100]kbit \
	ceil $[5*RATEUP/100]kbit prio 3

tc qdisc add dev $DEV parent 1:20 handle 20: sfq perturb 10
tc qdisc add dev $DEV parent 1:21 handle 21: sfq perturb 10
tc qdisc add dev $DEV parent 1:22 handle 22: sfq perturb 10
tc qdisc add dev $DEV parent 1:23 handle 23: sfq perturb 10

tc filter add dev $DEV parent 1:0 prio 0 protocol ip handle 20 fw flowid 1:20
tc filter add dev $DEV parent 1:0 prio 0 protocol ip handle 21 fw flowid 1:21
tc filter add dev $DEV parent 1:0 prio 0 protocol ip handle 22 fw flowid 1:22
tc filter add dev $DEV parent 1:0 prio 0 protocol ip handle 23 fw flowid 1:23


# Table TOSFIX
iptables -t mangle -N tosfix
iptables -t mangle -A tosfix -p tcp -m length --length 0:512 -j RETURN
iptables -t mangle -A tosfix -m limit --limit 2/s --limit-burst 10 -j RETURN
iptables -t mangle -A tosfix -j TOS --set-tos Maximize-Throughput
iptables -t mangle -A tosfix -j RETURN

# Table ACK
iptables -t mangle -N ack
iptables -t mangle -A ack -m tos ! --tos Normal-Service -j RETURN
iptables -t mangle -A ack -p tcp -m length --length 0:128 -j TOS --set-tos Minimize-Delay
iptables -t mangle -A ack -p tcp -m length --length 128: -j TOS --set-tos Maximize-Throughput
iptables -t mangle -A ack -j RETURN

# Is our TOS broken? Fix it for TCP ACK and OpenSSH.

iptables -t mangle -A POSTROUTING -p tcp -m tcp --tcp-flags SYN,RST,ACK ACK -j ack
iptables -t mangle -A POSTROUTING -p tcp -m tos --tos Minimize-Delay -j tosfix


# Match DNS Packets
#iptables -t mangle -I POSTROUTING -o $DEV -p udp --dport 53 -j MARK --set-mark 20
iptables -t mangle -I POSTROUTING -o $DEV -p udp --dport 53 -j DROP
iptables -t mangle -I POSTROUTING -o $DEV -p udp --dport 53 -j RETURN


# Match UDP Packets
iptables -t mangle -A POSTROUTING -o $DEV -p udp -j MARK --set-mark 21

# Here we deal with ACK, SYN, and RST packets

# Match SYN and RST packets
iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m tcp --tcp-flags ! SYN,RST,ACK ACK -j MARK --set-mark 20
iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m tcp --tcp-flags ! SYN,RST,ACK ACK -j RETURN


# Match ACK packets
iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m tcp --tcp-flags SYN,RST,ACK ACK -m length --length :128 -m tos --tos Minimize-Delay -j MARK --set-mark 20
iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m tcp --tcp-flags SYN,RST,ACK ACK -m length --length :128 -m tos --tos Minimize-Delay -j MARK --set-mark 20


# Match packets with TOS Minimize-Delay
iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m tos --tos Minimize-Delay -j MARK --set-mark 20
iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m tos --tos Minimize-Delay -j RETURN

# Flow Selection
iptables -t mangle -A POSTROUTING -o $DEV -p tcp --dport 80 -j MARK --set-mark 21
iptables -t mangle -A POSTROUTING -o $DEV -p tcp --dport 80 -j RETURN
iptables -t mangle -A POSTROUTING -o $DEV -p tcp --dport 443 -j MARK --set-mark 21
iptables -t mangle -A POSTROUTING -o $DEV -p tcp --dport 443 -j RETURN


iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m layer7 --l7proto edonkey -j MARK --set-mark 23
iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m layer7 --l7proto edonkey -j RETURN

iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m layer7 --l7proto bittorrent -j MARK --set-mark 23
iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m layer7 --l7proto bittorrent -j RETURN

iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m layer7 --l7proto imesh -j MARK --set-mark 23
iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m layer7 --l7proto imesh -j RETURN

iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m layer7 --l7proto gnutella -j MARK --set-mark 23
iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m layer7 --l7proto gnutella -j RETURN

iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m layer7 --l7proto fasttrack -j MARK --set-mark 23
iptables -t mangle -A POSTROUTING -o $DEV -p tcp -m layer7 --l7proto fasttrack -j RETURN

exit
