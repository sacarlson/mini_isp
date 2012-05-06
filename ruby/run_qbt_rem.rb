#!/usr/bin/ruby
# lib test
#path = "#{File.dirname($PROGRAM_NAME)}"
#Dir.chdir(path)
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'

command = "run"

qbtControl(command,show=nil,hostaddress=$vuzehost,user=$vuzeuser,password=$vuzepass)

