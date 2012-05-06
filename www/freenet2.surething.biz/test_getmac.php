<?php
 include('returnMacAdress.php');
 $GLOBALS['REMOTE_ADDR'] = "192.168.2.3";
 $mac = returnMacAddress("192.168.2.3");
 echo "results = " . $mac;
 echo "\n<br> ";
 // should see: results = 00:1D:E0:A6:62:C7 


?>
