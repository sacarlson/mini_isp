#run this on bigboy to be a static vpn server
sudo killall openvpn
sudo ifconfig tun0 down
sleep 1
sudo openvpn --config /home/sacarlson/vpn/tls-server.conf &
sleep 5
#  already done in masq.sh now
#sudo iptables -A FORWARD -i tun0 -o eth0 -s 10.0.0.0/24 -m state --state NEW -j ACCEPT
#sudo iptables -A FORWARD -m state --state ESTABLISHED,RELATED -j ACCEPT
#sudo iptables -A POSTROUTING -t nat -j MASQUERADE
# sudo iptables -t nat -A POSTROUTING -s 10.0.0.0/24 -o eth0 -j MASQUERADE
echo "done"
sleep  10
exit -1
# this also worked
#sudo iptables -A FORWARD -i tun0 -o eth0 -s 10.0.0.0/24  -j ACCEPT
