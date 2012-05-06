tc -s -d qdisc show dev eth0
tc -s -d class show dev eth0
tc -s -d filter show dev eth0
tc -s qdisc ls dev eth0
tc -s class ls dev eth0
tc -s filter ls dev eth0
iptables -t mangle -L -n -v
