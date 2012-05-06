#! /bin/sh
# start server openvpn with no encryption see open_tunnel_no_encrytion.sh for the client side
#on the server to prepare for connection with:
sudo killall openvpn
sleep 3
sudo openvpn --dev tun0 --ifconfig 10.2.2.3 10.2.2.2
#with this the server side can connect direct to the remote with ping 10.2.2.2
# or ssh freenet@10.2.2.2
