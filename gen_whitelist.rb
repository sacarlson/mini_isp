#!/usr/bin/ruby
# generate ip whitelist for masq.sh
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'

set_active_ip()
gen_whitelist("./whitelist.txt")
