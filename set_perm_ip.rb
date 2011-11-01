#!/usr/bin/ruby
#set perminet ip address for newly added whitelisted customers
#create a dhcpd.conf file to be used in /etc/dhcp3/dhcpd.conf
# after creating the file and puting it will also restart dhcpd
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'

set_perm_ip()
