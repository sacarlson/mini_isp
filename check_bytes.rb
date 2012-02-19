#!/usr/bin/ruby
# lib test
#path = "#{File.dirname($PROGRAM_NAME)}"
#Dir.chdir(path)
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'
notip = $vuzehost
readtables()
filedata = get_iptables_data()
result = bytesAllNot(notip,filedata)
puts "byteAllNot(#{$vuzehost},filedata) = #{result}"
