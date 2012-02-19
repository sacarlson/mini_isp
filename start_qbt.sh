 
#export DISPLAY=:0; qbittorrent&
sleep 2
export DISPLAY=:0; xdotool search "qBittorrent v2.2.5" windowactivate --sync key --clearmodifiers ctrl+shift+s
#pause qBT
#xdotool search "qBittorrent v2.2.5" windowactivate --sync key --clearmodifiers ctrl+shift+p
