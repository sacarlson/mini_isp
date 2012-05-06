sudo /etc/init.d/dhcp3-server restart
sudo su sacarlson -c "ssh  sacarlson@192.168.2.112 'sudo /etc/init.d/isc-dhcp-server stop'"
sudo su sacarlson -c "ssh -t sacarlson@192.168.2.112 'sudo rm /etc/dhcp/dhcpd.conf'"
