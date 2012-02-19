#!/usr/bin/ruby
# add ip address to mysql freenet to be used in usage_track_record.rb
# also used to add linked accounts to user_id
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'

#def ip_add(ip,user_id="0")

ip_add(ARGV[0],ARGV[1])
sleep 1
set_active_ip(con=nil)
sleep 1
system("sudo /home/sacarlson/masq.sh")

