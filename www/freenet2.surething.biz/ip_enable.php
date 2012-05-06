<?

function ip_enable()
  {
  $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
  $IPTABLES="/sbin/iptables";
  $IPTABLES=$IPTABLES . " -t nat -I PREROUTING  -s " . $ip . " -j RETURN";
//  echo $IPTABLES . " .<br />";
//  echo "<br />". $ip. " now enabled <br /> ";
  $result = exec("sudo ". $IPTABLES . " >log.txt");
  }

//ip_enable();
?>
