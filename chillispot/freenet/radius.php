<?php
require('./config.php');

function writelog($string){
  //return;
  $f = fopen("/var/www/debug.log", "a");
  fwrite( $f, $string );
  print( $string );
  fclose( $f );
} 

// added radius support bellow
function returnMacAddress($remoteIp) {
  exec("/usr/sbin/arp -n",$arpSplitted);
  $ipFound = false;
  // Cicle the array to find the match with the remote ip address
  foreach ($arpSplitted as $value) {
    // Split every arp line, this is done in case the format of the arp
    // command output is a bit different than expected
    $valueSplitted = split(" ",$value);
    foreach ($valueSplitted as $spLine) {
      if (preg_match("/$remoteIp/",$spLine)) {
        $ipFound = true;
      }
      // The ip address has been found, now rescan all the string
      // to get the mac address
      if ($ipFound) {
        // Rescan all the string, in case the mac address, in the string
        // returned by arp, comes before the ip address
        reset($valueSplitted);
        foreach ($valueSplitted as $spLine) {
          $pg = preg_match("/[0-9a-f][0-9a-f][:-]".
          "[0-9a-f][0-9a-f][:-]".
          "[0-9a-f][0-9a-f][:-]".
          "[0-9a-f][0-9a-f][:-]".
          "[0-9a-f][0-9a-f][:-]".
          "[0-9a-f][0-9a-f]/i",$spLine);
          if ($pg) {
            return $spLine;
          }
        }
      }
      $ipFound = false;
    }
  }
  return false;
}

function rad_add_ip($remoteIp,$expiredate,$configValues){
  //convert $remoteIp to mac and use in rad_add_mac
  //$password for rad_add_mac here will be hardcoded for now
  $password="macpass";
  $mac_addr = returnMacAddress($remoteIp);
  writelog("rad_add_ip ip = " . $remoteIp . " mac_addr = " . $mac_addr . "\n");
  writelog("expiredate = ". $expiredate . "\n");
  if ($mac_addr){
    rad_add_mac($mac_addr,$password,$expiredate,$configValues);
  }
  else{
    writelog("no mac returned from ip no mac account created\n");
  }
}


function rad_add_mac($mac_addr,$password,$expiredate,$configValues){
  // add a mac address authentication entry to radius radcheck table
  // mac address authentication requires no password from users for auto login
  // $password is value used in coova-chilli config option HS_MACPASSWD=macpass
  // $mac_addr format example 00:11:22:33:44:55 or 00-11-22-33-44-55
  // $expiredate is in timestamp format seconds since Jan 01 1970 like seen from time()
  // $configValues is from config.php $configValues array values
  // returns values the same as rad_update_users
  $new_mac = preg_replace("/:/", "-", $mac_addr);
  writelog("new_mac = " . $new_mac . "\n");
  rad_update_user($new_mac,$password,$expiredate,$configValues);
}

function rad_user_exists($username,$configValues){
  //check to see if username is present in radius radcheck table.
  // return true if exists false if not (returns positive count if true zero if false).
  // $configValues is from config.php $configValues array values
  $con = mysql_connect($configValues['CONFIG_DB_HOST'],$configValues['CONFIG_DB_USER'],$configValues['CONFIG_DB_PASS'],true);
  mysql_select_db($configValues['CONFIG_DB_NAME']) or die( "Unable to select database"); 
  $query="SELECT * FROM radcheck WHERE username = '$username' ";
  $result = mysql_query($query,$con);
  $num=mysql_numrows($result);
  mysql_close($con);
  return $num;
}

function rad_add_user($username,$password,$expiredate,$configValues){
  // add a new user to the radius radcheck table include expire date if we have one
  // expire date is in timestamp format seconds since Jan 01 1970 like seen from time()
  $con = mysql_connect($configValues['CONFIG_DB_HOST'],$configValues['CONFIG_DB_USER'],$configValues['CONFIG_DB_PASS'],true);
  mysql_select_db($configValues['CONFIG_DB_NAME']) or die( "Unable to select database"); 
  $newexpire = date("d M Y",$expiredate);
  $query = "INSERT INTO radcheck (username,attribute,op,value) VALUES('$username', 'Cleartext-Password',':=','$password' ) ";
  mysql_query($query,$con);
  $query = "INSERT INTO radcheck (username,attribute,op,value) VALUES('$username', 'Expiration',':=','$newexpire' ) ";
  mysql_query($query,$con);
  mysql_close($con);
}

function rad_update_user($username,$password,$expiredate,$configValues){
  // update the expire date of the user $username in radius radcheck table
  // expire date is in timestamp format seconds since Jan 01 1970 like seen from time()
  // if $username doesn't exist then add new user and return 1 if user existed will return 0
  // if $password string is length 0 then password is left unchanged
  // if enable_radius_support is set to 0 then return 2 with nothing done.
  $test = $configValues['CONFIG_DB_HOST'];
  $string = "start rad_update_user host $test \n";
  writelog($string);
  writelog("username $username , pass $password, expiredate $expiredate \n");
  if ($configValues['enable_radius_support']==0){
    return 2;
  }
  writelog("radius_support active \n");
  if (rad_user_exists($username,$configValues)>0){
    writelog("rad user exists \n");
    $con = mysql_connect($configValues['CONFIG_DB_HOST'],$configValues['CONFIG_DB_USER'],$configValues['CONFIG_DB_PASS'],true);
    mysql_select_db($configValues['CONFIG_DB_NAME']) or die( "Unable to select database"); 
    $newexpire = date("d M Y",$expiredate);
    $query="UPDATE radcheck SET value='$newexpire' WHERE username='$username' AND attribute='Expiration'";
    mysql_query($query,$con);
    if (strlen($password)>1){
      $query="UPDATE radcheck SET value='$password' WHERE username='$username' AND attribute='Cleartext-Password'";
      mysql_query($query,$con);
      writelog("password set to $password \n");
    } 
    mysql_close($con);      
    return 1;
  }
  else{
    writelog("rad_add_user \n");
    rad_add_user($username,$password,$expiredate,$configValues);
    return 0;
  }
}
// end added radius support

//$mac_addr = "00-11-22:33:44:55";
//$password = "macpass";
$expiredate = 1339837127;
//$configValues = "";
//rad_add_mac($mac_addr,$password,$expiredate,$configValues)

$remoteIp = "192.168.2.122";
//rad_add_ip($remoteIp,$expiredate,$configValues)
$mac = returnMacAddress($remoteIp);
print("mac = ". $mac . "\n");
if ($mac){
  print "true \n";
}
else{
  print "false \n";
}

?>



