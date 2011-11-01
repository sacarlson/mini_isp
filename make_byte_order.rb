#!/usr/bin/ruby
# lib test
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'
#make_byte_order(user_id, bytes,con=nil)
make_byte_order(ARGV[0], ARGV[1],ARGV[2]=1440)
