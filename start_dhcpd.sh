sudo cp /home/freenet/ruby/dhcpd.conf /etc/dhcp/dhcpd.conf
sleep 1
sudo /etc/init.d/isc-dhcp-server start
