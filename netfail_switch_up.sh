#! /bin/bash
# this is mod to add link changes to freenet website to switch to normal up mode

rm /var/www/freenet.surething.biz/index.php
rm /home/freenet/netfail_active.txt
ln -s /var/www/freenet.surething.biz/index.php.netup /var/www/freenet.surething.biz/index.php
/home/freenet/masq.sh

