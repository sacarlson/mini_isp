#run as sacarlson not sudo or it won't work
#ssh  sacarlson@192.168.2.112 'sudo /etc/init.d/isc-dhcp-server restart'
sudo su sacarlson -c "ssh  sacarlson@192.168.2.112 'sudo cp /home/freenet/ruby/dhcpd.conf /etc/dhcp/dhcpd.conf'"
sudo su sacarlson -c "ssh  sacarlson@192.168.2.112 'sudo /etc/init.d/isc-dhcp-server restart'"
sudo /etc/init.d/dhcp3-server stop
