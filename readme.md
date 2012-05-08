#Mini ISP
Copyright:: Copyright (c) 2012 sacarlson

Mini ISP is a group of integrated programs and infrastructure needed to setup a small prepaid WIFI or wired World Wide Internet network service.  It is designed to be used in short to medium term residence like Hotels or resort apartments where residences want a quick method to connect to the Internet from between 1 day to 3 months or longer.  Mini ISP provides redirected service to unregistered users to enable them to choose and purchase the desired duration of time to be allowed access to the Internet.  After this time window, world Internet service is automatically discontinued until another contract is purchased.

##License:
 GPLv3 see http://www.gnu.org for details

##Requirments:
* Standard PC computer with at least 256meg ram and minimal 10Gig Hard disk.
* One or more Wifi access points or wired infrastructure for network distribution.
* Ubuntu version 10.04 – 11.10 

 Mini ISP software was designed to be run on a low power standard PC with as little as 256 meg ram and as little as 10gb available hard disk space partitioned to run Ubuntu. Mini ISP also needs to be run on the open source operating system Ubuntu version 10.04 – 11.10 server addition or desktop.  You will also need to have at least one connection to the world Internet and either a single or many wifi access points or an infrastructure of wired local network to provide distribution to your customers. 

##Features:

* Oscommerce driven store to provide automated customer purchase of service contracts.
* Customer authentication and time/bandwidth usage accounting for each.
* Limited services provided to unregistered customers by iptables redirection.
* Fairnat customer bandwidth limiting with custom per user controllable settings. 
* PhpBB2 bulletin board integrated to provide customers information on service status and changes and other custom local events.
* Bandwidth usage monitored and reported with Cacti SMTP of each node in network.
* Direct chat integrated for customer support with webchat2.0.
* Auto fallback on failure of primary ISP provider to auto redirect all traffic to backup ISP line.
* Optional VPN services support also available. 

  See http://freenet.surething.biz to demo a working site.

##Instalation:
 see https://github.com/sacarlson/mini_isp/blob/master/freenet_install.sh for more installation details.
 
