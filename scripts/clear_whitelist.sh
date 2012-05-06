#! /bin/sh
# clear whitelist file that contains ip's that don't need to login to FreeNet
# this will be ran 1st of each month so that clients see they have expired.
sudo mv /home/freenet/whitelist.txt /home/freenet/whitelist.txt.org
# to restore
#sudo mv /home/freenet/whitelist.txt.org /home/freenet/whitelist.txt
sudo cp /home/freenet/whitelist_nextmonth.txt /home/freenet/whitelist.txt
