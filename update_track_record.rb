#!/usr/bin/ruby
# update ip trafic usage in ip_links table in mysql
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'
change_detected = false

if set_active_ip() then
  puts "changes in active users detected"
  change_detected = true
end
sleep 1
gen_whitelist()
sleep 1
update_tracker()
sleep 1
update_mac()
sleep 1
update_track_record()
sleep 1
#360000000 = 100KB/sec ,180000000 = 50kB/sec,  90000000 = 25KB/sec,
# slow_speed = "@800kbit|800kbit"  = 80kB/sec
# mid_speed = "@1200kbit|1200kbit"       = 120kB/sec
# high_speed = "@2mbit|2mbit"      = 200kB/sec
if make_fairnat_config(triger_slow=360000000, triger_mid=180000000, iptables_data_file=nil) then
  puts "changes in fairnat.config detected will run masq.sh"
  change_detected = true
  sleep 2 
end
if change_detected then
   system("sudo /home/freenet/masq.sh")
end

