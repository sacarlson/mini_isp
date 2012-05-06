echo on
sudo killall openvpn
#sudo killall NetworkManager
sleep 2
#sudo ifconfig eth0 down
#sudo ifconfig ath0 down
#sudo iwconfig ath0 essid "POL@JOMA" key "abcde12345"
#sudo iwconfig ath0 essid "FreeNet"
#sleep 3
#sudo ifconfig ath0 192.168.2.222
#sudo route del default
# tot at this time was at 192.168.1.253
#sudo route add default gw 192.168.2.250
#sudo cp /etc/resolv.conf.big /etc/resolv.conf
#sudo dhclient eth0
#sleep 1

sudo openvpn  --config /home/sacarlson/vpn/tls-client.conf &
echo "start sleep"
sleep 1
# now resolv.conf set to 10.0.0.1
sudo cp /etc/resolv.conf.vpn /etc/resolv.conf
#sudo route del default
#sudo route add default gw 10.0.0.1
echo "client_ether.sh complete"
sleep 5
