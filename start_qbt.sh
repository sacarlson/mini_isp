
if [ ! -z "$(pidof qbittorrent)" ] ; 
then
  echo "qbittorrent seen active, no need to start"
else
  echo "qbittorrent not running will start it"
  export DISPLAY=:0; qbittorrent&
  sleep 6
fi

export DISPLAY=:0; xdotool search "qBittorrent v2.2.5" windowactivate --sync key --clearmodifiers ctrl+shift+s

