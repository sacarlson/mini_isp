#!/usr/bin/ruby
#create the USERS= string needed to append o fairnat.config.template to control bandwidth of users
#iptables_data_file name is what was created with readtables(filename = "/home/sacarlson/userusage.txt")
#triger_slow is the value that trigers puting a user into slow_speed b/w mode if this value is greater than get_bytes_last_tot
#triger_mid is the same as triger_slow above this value will put user in mid_speed b/w mode
#force will force it to gen users return even if no triger changes detected
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'

make_fairnat_config(triger_slow=nil, triger_mid=nil, iptables_data_file=nil, force=true)
