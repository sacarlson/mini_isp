#!/bin/bash
host="192.168.2.112"

pingmany ()
{
host=$1
for i in {1..3}
do
  if ping -c 1 $host
  then
    echo "ping $host ok" 
    return 0
  fi
  echo "ping $host try # $i failed"
  sleep 2
done
echo "5 pings $host failed return 255"
return 255
}

check_imalive ()
{
host=$1
result=$(ssh sacarlson@$host /home/sacarlson/imalive.sh)
if [ "$result" == "ok" ]; then
  echo "returned ok"
  return 0
else
  return 255
fi
}

host_check ()
{
host=$1
if pingmany $host ; then
  echo "pingmany $host ok"
  if check_imalive $host
  then
    echo "imalive $host ok"
    return 0
  else
    echo "imalive $host bad"
    return 127
  fi
else
  echo "pingmany $host bad"
  return 255
fi
}

dhcpd_active ()
{
if [ ! -z "$(pidof dhcpd3)" ] || [ ! -z "$(pidof dhcpd)" ] ; 
then
  echo "dhcpd seen active"
  return 0
else
  echo "neather dhcpd detected"
  return 255
fi
}

dhcpd_local_on ()
{
host=$1
if dhcpd_active ; then
  echo "local dhcpd already running, will exit nothing done."
  return 0
else
  sudo /etc/init.d/dhcp3-server restart
  sudo su sacarlson -c "ssh  sacarlson@192.168.2.112 'sudo /etc/init.d/isc-dhcp-server stop'"
  sudo su sacarlson -c "ssh -t sacarlson@192.168.2.112 'sudo rm /etc/dhcp/dhcpd.conf'"
  echo "dhcpd_local_on at date = $DATE" >> ping.log
fi
}

dhcpd_local_off ()
{
host=$1
if dhcpd_active ; then
  sudo su sacarlson -c "ssh  sacarlson@192.168.2.112 'sudo cp /home/freenet/ruby/dhcpd.conf /etc/dhcp/dhcpd.conf'"
  sudo su sacarlson -c "ssh  sacarlson@192.168.2.112 'sudo /etc/init.d/isc-dhcp-server restart'"
  sudo /etc/init.d/dhcp3-server stop
  #sudo /etc/init.d/isc-dhcp-server stop
  echo "dhcpd_local_off at date = $DATE" >> ping.log
  echo "local dhcpd stoped"
else
  echo "local dhcpd already offline, nothing to be done."
  return 0
fi
}

if host_check $host ; then
  echo "host_check $host good"
  dhcpd_local_off $host
else
  echo "remote host failed, will activate local dhcpd"
  dhcpd_local_on $host
fi

sleep 4

if dhcpd_active ; then
  echo "dhcpd seen active"
else
  echo "dhcpd NOT seen active"
fi


