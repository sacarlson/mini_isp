#!/usr/bin/ruby
# Control qBittorrent activity limited to available bandwidth judged by measured lan usage
# see settings in config.rb 
#path = "#{File.dirname($PROGRAM_NAME)}"
#Dir.chdir(path)
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'
qbtlimit()

