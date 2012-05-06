<?php

function ip_enable($ip)
  {
  $IPTABLES="/sbin/iptables";
  $IPTABLES=$IPTABLES . " -t nat -I PREROUTING  -s " . $ip . " -j RETURN";
  echo $IPTABLES . " .<br />";
  echo " $ip. " now enabled <br /> ";
  $result = exec("sudo ". $IPTABLES . " >log.txt");
  }


ip_enable("192.168.2.129");
echo "<br /> completed <br /> ";


?>

