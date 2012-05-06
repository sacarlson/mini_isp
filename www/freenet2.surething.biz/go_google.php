<?php
header( 'refresh: 10; url= http://freenet.surething.biz/phpBB2/viewforum.php?f=2' );
#header( 'refresh: 20; url= http://google.com' );
require_once "tcp_ping.class.php";
print "<U><B><BIG>Network Conectivity Test results</BIG></B></U><BR><BR>";
$host = "router";
$pinger = new TcpPing("$host","http",2);
$good = $pinger->Ping();
if ($good==1){print "ping of host $host looks good <BR>";}
else {print "FreeNET router ping failure! This may be a FreeNet problem. please inform scotty of failure  <BR>";
      print " phone 086-827-0277 if other Internet connections fail.<BR>";
      exit;}

#$host="72.14.207.99";
$host="google.com";
$pinger->mTargetHost = trim($host);
$good = $pinger->ping();
if ($good==1){print "<BR>Ping of host google.com looks good <BR>";}
else {print "ping of host google.com failed. This could be a TOT problem please try again later <BR>  If problem continues for more that 24 hours contact Scotty at PH# 086-827-0277 <BR>";}

// phpinfo(); exit();
// header( 'refresh: .1; url= http://google.com' );
 //   header( 'refresh: 3; url= http://surething.biz' );
 ?>
