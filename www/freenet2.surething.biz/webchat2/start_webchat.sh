#! /bin/bash
# to start active irc web client on port 2001
# to access when running in browser go to http://irc.surething.biz:2001
# will run this in cron @reboot
cd /var/www/freenet.surething.biz/webchat2/
php -f ./chat.php
