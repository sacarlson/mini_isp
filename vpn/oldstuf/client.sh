echo on
sudo ifconfig wlan0 down
sudo ifwconfig essid FREENET
sudo ifconfig wlan0 up
sudo dhclient wlan0
sleep 10
sudo openvpn  --config client.conf
sleep 5
