<?php

 function ip_enable($ip)
  { 
    echo "start ip_enable";
    echo exec('whoami');
    echo exec("sudo ls -A >>/var/www/log2.txt");
    $result = exec("echo start ip_enable >>/var/www/log2.txt"); 
    $IPTABLES = "/sbin/iptables";
    $IPTABLES = $IPTABLES . " -t nat -I PREROUTING  -s " . $ip . " -j RETURN";
    $result = exec("sudo ". $IPTABLES . " >>/var/www/log2.txt");
    $result = exec("sudo echo end ip_enable >>/var/www/log2.txt");
  }

 $ip = "192.168.2.194";
 ip_enable($ip);
 echo "<h1> done4 </h1>";

?>
