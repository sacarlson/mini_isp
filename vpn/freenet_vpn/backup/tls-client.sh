echo on
sudo killall openvpn
#sudo killall NetworkManager
sleep 2

sudo openvpn  --config /home/freenet/freenet_vpn/tls-client.conf &
echo "start sleep"
sleep 1
# now resolv.conf set to 10.0.0.1
#sudo cp /etc/resolv.conf.vpn /etc/resolv.conf
#sudo route del default
#sudo route add default gw 10.0.0.1
echo "tls-client.sh complete"
sleep 5
