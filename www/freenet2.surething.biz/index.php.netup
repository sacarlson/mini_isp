<?php
 session_start();
 if (strlen($_SESSION['customer_ip'])<7){
   //$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
   $ip = $_SERVER['REMOTE_ADDR'];
   $_SESSION['customer_ip'] = $ip;
 }
//echo "ip = $ip <br>";
// warning we now don't have https encryption enabled.
 header("Cache-Control: no-cache, must-revalidate");
 header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
 header( 'refresh: 0; url= http://freenet2.surething.biz/login3.php' );
# echo ' <P ALIGN=CENTER><FONT SIZE=6>Welcome to Freenet WiFi</FONT></P>';
?>
