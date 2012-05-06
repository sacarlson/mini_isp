<?php
 session_start();
 if (strlen($_SESSION['customer_ip'])<7){
   $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
   $_SESSION['customer_ip'] = $ip;
 }
//echo "ip = $ip <br>";
 header("Cache-Control: no-cache, must-revalidate");
 header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
 header( 'refresh: 0; url= http://freenet2.surething.biz/login3.html' );
# echo ' <P ALIGN=CENTER><FONT SIZE=6>Welcome to Freenet WiFi</FONT></P>';
?>
