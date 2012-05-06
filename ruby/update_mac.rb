#!/usr/bin/ruby
# update mac addresses found on network and add to mysql register
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'

update_mac()

