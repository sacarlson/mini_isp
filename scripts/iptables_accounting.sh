sudo iptables -t mangle -F
sudo iptables -t mangle -X


sudo iptables -t mangle -I PREROUTING -s 192.168.2.159 -m mark ! --mark 159
sudo iptables -t mangle -I POSTROUTING -d 192.168.2.159 -m mark ! --mark 0x159

sudo iptables -nv -L -t mangle

