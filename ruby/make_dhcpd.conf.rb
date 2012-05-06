#!/usr/bin/ruby
#create a dhcpd.conf file to be used in /etc/dhcp3/dhcpd.conf
# after creating the file and puting it there you will also have to restart dhcpd
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'

make_dhcpd_conf(fileout="./dhcpd.conf")
#sleep 1
#system("sudo cp ./dhcpd.conf /etc/dhcp3/dhcpd.conf")
#sleep 2
#system("sudo /etc/init.d/dhcp3-server restart")
