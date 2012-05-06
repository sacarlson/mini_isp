#make backup of freenet install package
#must be run with sudo
mv ./etc ./etc2
mkdir ./etc
cp -a /etc/apache2 ./etc/apache2
#chown -R freenet:freenet ./etc/apache2
cp -a /etc/dhcp3 ./etc/dhcp3
#chown -R freenet:freenet ./etc/dhcp3
cp -a /etc/network ./etc/network
#chown -R freenet:freenet ./etc/network
cp -a /etc/bind ./etc/bind
#chown -R freenet:freenet ./etc/bind
cp -a /etc/hosts ./etc/hosts
#chown freenet:freenet ./etc/hosts
cp -a /etc/sudoers ./etc/sudoers
#chown freenet:freenet ./etc/sudoers
cp -a /etc/resolv.conf ./etc/resolv.conf
#chown -R freenet:freenet ./etc/resolv.conf
cp -a /etc/snmp ./etc/snmp
chown -R freenet:freenet ./etc
#cp -a /etc/samba ./etc/samba
#chown -R freenet:freenet ./etc/samba
#note change user and password as needed
mysqldump -usacarlson -ppassword --all-databases > mysql_all.sql
#mysqldump -usacarlson -ppassword freenet2 > freenet2.sql
#mysqldump -usacarlson -ppassword cacti > cacti.sql
#mysqldump -usacarlson -ppassword chat > chat.sql
#mysqldump -usacarlson -ppassword phpbb2 > phpbb2.sql
chown freenet:freenet ./mysql_all.sql
tar -zcvf freenet2.tar.gz ./
chown freenet:freenet ./freenet2.tar.gz
