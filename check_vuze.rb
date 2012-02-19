#!/usr/bin/ruby
# voice activity of vuze torrent being active or deactivated
#path = "#{File.dirname($PROGRAM_NAME)}"
#Dir.chdir(path)
Dir.chdir(File.dirname($PROGRAM_NAME))
require './freenet_lib.rb'
state = "inactive"
loop do
  show = vuzeControl("show")
  if show.include?("()") then
    if state == "inactive" then
      state = "active"
      puts "vuze is stoped "
      #play_sound("./sounds/vuze_deactiveated.wav")
      play_sound("./sounds/vuze_active.wav")
    end
  end
  if show.include?("stopped") then
    if state == "active" then
      state = "inactive"
      puts "vuze is active"
      #play_sound("./sounds/vuze_active.wav")
      play_sound("./sounds/vuze_deactiveated.wav")
    end       
  end
  sleep 30
end


