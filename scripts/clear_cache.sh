#! /bin/bash
# clear cache mem to make avalible mem for virtualbox
sudo sync
sleep 1
sudo echo 3 | sudo tee /proc/sys/vm/drop_caches
