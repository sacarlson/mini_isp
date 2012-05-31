<?php

function writelog($string){
  return;
  $f = fopen("/var/www/debug.log", "a");
  fwrite( $f, $string );
  fclose( $f );
} 

// added radius support bellow
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

?>



