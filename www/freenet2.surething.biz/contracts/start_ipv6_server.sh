echo start miredo ipv6 tunnel
sudo /etc/init.d/miredo restart
sleep 2
echo start totd dns proxy
sudo totd
sleep 2 
echo start prtrtd ipv6 translation
# change line bellow to match where you compiled it
sudo /home/sacarlson/Downloads/ptrtd-0.5.2/ptrtd
sleep 2
echo "enable ipv6 forwarding"
echo "1" > /proc/sys/net/ipv6/conf/all/forwarding
sleep 1
echo start radvd
radvd
