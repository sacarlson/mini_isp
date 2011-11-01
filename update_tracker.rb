#!/usr/bin/ruby
#add any missing ip's to ip_links of any active customers accounts 
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'

update_tracker()
update_mac()
