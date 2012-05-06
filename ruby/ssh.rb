#!/usr/bin/ruby
# lib test
#path = "#{File.dirname($PROGRAM_NAME)}"
#Dir.chdir(path)
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'
 require 'rubygems'
 require 'net/ssh'
 
 HOST = '192.168.2.112'
 USER = 'username'
 PASS = 'password'
 
 Net::SSH.start( HOST, USER, :password => PASS ) do|ssh|
 result = ssh.exec!('killall vi')
 puts result
 end


