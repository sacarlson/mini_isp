#!/usr/bin/ruby
# add ip address to mysql freenet to be used in usage_track_record.rb
# also used to add linked accounts to user_id
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'

#def ip_add(ip,user_id="0")

ip_add(ARGV[0],ARGV[1])

