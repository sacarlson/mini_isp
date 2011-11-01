#!/usr/bin/ruby
# lib test
#path = "#{File.dirname($PROGRAM_NAME)}"
#Dir.chdir(path)
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'

#if check_mac("192.168.2.168") == nil then
#  puts "no mac address record found"
#end

#puts check_ip("192.168.2.168")
#puts check_ip("192.168.2.128")

#if !check_ip("192.168.2.168") then
#  puts "yes it's there"
#end

#puts get_active_customers_id()

#puts get_user_id_ip("192.168.2.150")

#puts get_whitelist()

#puts get_bytes_rx_user_id("2")
#puts get_bytes_tx_user_id("2")
#puts get_bytes_total_user_id("2")

#puts set_bytes_ordered("2", "234")
#puts get_bytes_start("2")
#puts get_bytes_ordered("2")
#puts make_byte_order("2", 100000)
#puts get_bytes_start("2")
#puts get_bytes_ordered("2")

ip = "192.168.2.174"
#puts set_bytes_ordered("2", 0)
#puts check_byte_account("2")
#puts get_active_customers_id()
#puts get_ip_list(con=nil)
#puts get_whitelist()
#puts set_not_active_id("2",con=nil)
#puts reset_active(con=nil)
#puts get_active_customers_id(con=nil)
#puts update_mac(con=nil)
#puts make_fairnat_config(3000000,iptables_data_file=nil)
#bytesTotal("192.168.2.164","/home/sacarlson/userusage.txt")
#puts get_bytes_xx_ip(ip,tx=0,con=nil)
#puts get_bytes_xx_ip(ip,tx=1,con=nil)
#puts get_bytes_rx_ip_max(ip,con=nil)
verible = 'bytes_rx'
#puts get_ip_value(ip, verible, con=nil)
#puts get_bytes_last_tot_ip(ip,con=nil)
#puts make_fairnat_config(triger_slow=nil, triger_mid=nil, iptables_data_file=nil)
#puts update_mac()
#make_dhcp_config(fileout="/home/sacarlson/ruby/dhcp.config")
#perm_ip()
#puts get_last_ip_added(con=nil)
newip = "192.168.2.155"
#puts change_ip_address(ip,newip)
#set_fairnat_speed(ip, 2, con=nil)
#puts get_ip_list(con=nil)
#readtables()
#puts File.dirname($PROGRAM_NAME)
user_id = 2
hours = 24
con = nil
#puts make_time_order(user_id, hours,con=nil)
#set_customer_expire(user_id, "DATE_ADD(NOW(), INTERVAL 1 DAY)")
#set_customer_expire(user_id, "DATE_ADD(NOW(), INTERVAL 1 HOUR)")
#make_time_order(user_id, hours)
mbytes = 100
#make_byte_order(user_id, mbytes, hours,con)
#check_byte_account(user_id,con=nil)
#make_fairnat_config(triger_slow=nil, triger_mid=nil, iptables_data_file=nil, force=true)
#puts check_active_id(user_id, con=nil)
#puts set_not_active_id(user_id,con=nil)
#puts set_active_id(user_id,con=nil)
#sleep 1
#puts set_active_customers_id(con=nil)
#puts check_fairnat_speed(ip,con=nil)
set_fairnat_speed(ip, "3")

