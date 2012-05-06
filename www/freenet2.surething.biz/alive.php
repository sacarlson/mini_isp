<?php
require_once "tcp_ping.class.php";
$host = "router";
$pinger = new TcpPing("$host","http",2);
$good = $pinger->Ping();
if ($good!=1){print "bad_route_ping"; exit;}

#$host="google.com";
$host="8.8.8.8";
$pinger2 = new TcpPing("$host","domain",2);
$good = $pinger2->ping();
if ($good==1){print "imalive";}
else {print "bad_google_ping";}

?>
