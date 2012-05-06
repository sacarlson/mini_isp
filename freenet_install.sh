#! /bin/sh
# you must have a freenet account before you run this and dir at /home/freenet
# this must be run from were it was extracted like:
#tar -xvvzf freenet_install_files.tar.gz
# cd install_files
# then ./freenet_install.sh
sudo apt-get -y install bind9
sudo apt-get -y install apache2
sudo apt-get -y install php5
sudo apt-get -y install mysql-server-5.1
sudo apt-get -y install phpmyadmin
sudo apt-get -y install ruby-full
sudo apt-get -y install rubygems1.8
sudo apt-get -y install libmysql-ruby

#sudo atp-get -y install dhcp3-server

# note may need to install isc-dhcp-server instead of dhcp3-server on later versions of ubuntu/debian


# this part of install is optional
#sudo apt-get -y install cacti
#sudo apt-get -y install snmp  ; was already installed at this point
#sudo apt-get -y install snmpd
# at this time cacti wasn't working fully yet.
#delete the cacti mysql before install and import the original cacti.sql after insall
#cp -a ./etc/snmp/snmpd.conf /etc/snmp/.
#chown -R root:root /etc/snmp 

#sudo apt-get -y install samba
#sudo apt-get -y install samba-common-bin
# must add new host service name to dyndns.org example freenet2.selfip.com before you run ddclient
# using the sacarlson2000 account at this time
# also change router port forward to set to server address for all ports
#sudo apt-get -y install ddclient
#sudo apt-get -y install wireshark


# I do the above first before doing the bellow to be sure all installed ok
#mv /etc/apache2 /etc/apache2.org
#cp -a ./etc/apache2 /etc/apache2
#ln -s /etc/apache2/sites-available/default /etc/apache2/sites-enabled/000-default
#chown -R root:root /etc/apache2
#mv /etc/dhcp3 /etc/dhcp3.org
#cp -a ./etc/dhcp3 /etc/dhcp3
#chown -R root:root /etc/dhcp3
#mv /etc/network /etc/network.org
#cp -a ./etc/network /etc/network
#chown -R root:root /etc/network
#mv /etc/bind /etc/bind.org
#cp -a ./etc/bind /etc/bind
#chown -R root:root /etc/bind
#mv /etc/hosts /etc/hosts.org
#cp -a ./etc/hosts /etc/hosts
#chown root:root /etc/hosts
#mv /etc/sudoers /etc/sudoers.org
#cp -a ./etc/sudoers /etc/sudoers
#chown root:root /etc/sudoers
#mv /etc/resolv.conf /etc/resolv.conf.org
#cp -a ./etc/resolv.conf /etc/resolv.conf
#chown -R root:root /etc/resolv.conf
#mv /etc/samba /etc/samba.org
#cp -a ./etc/samba /etc/samba
#chown -R root:root /etc/samba
#crontab ./crontab.txt
#ln -s /home/freenet/www/freenet2.surething.biz /var/www/freenet2.surething.biz
#ln -s /var/www/freenet2.surething.biz /var/www/freenet2
#/home/freenet/ruby/make_dhcpd.conf.rb
#chown -R sacarlson:sacarlson /var/www

#mv /var/lib/mysql /var/lib/mysql.org
#cp ./mysql_backup.tar.gz /var/lib/.
#cd /var/lib
#tar -xvvzf mysql_backup.tar.gz
# echo now reboot system

# at this time still have to modify /var/www/freenet/index.php manualy
# to point to the http://freenetXX.surething.biz dns lookup name
# also bind configs must reflect the dns lookup name for local access.
# this first proto franchise freenet release server was set to IP address 192.168.2.112
# with name in dns name in bind set to freenet2.surething.biz



