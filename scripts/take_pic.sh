file="/home/sacarlson/pics/"`date +%s`.jpeg
echo $file
streamer -s 640/480 -f jpeg -o $file
sleep 5
streamer -s 640/480 -f jpeg -o $file
