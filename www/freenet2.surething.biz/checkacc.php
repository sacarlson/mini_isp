<?php


//if (strlen($_SESSION['customer_ip'])<7){
//  //$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
//  $ip = $_SERVER['REMOTE_ADDR'];
//  $_SESSION['customer_ip'] = $ip;
//}

// $_SESSION['code'] and value returned from checkacc()
// code will return 1 if we have success in password check and account check
// code will return 0 if no user with this name exists
// code will return -1 for bad password but a user does exist
// code will return -2 for account expired
// code will return -3 for customers IP address not assigned and already account exists with this IP address
// code will return -4 for account expired due to no bytes left in bytes block account
// code will return -5 for customers ip address passed in $_SESSION['customer_ip'] is invalid address
// returned session veribles:
// $_SESSION['firstname'] = users first name
// $_SESSION['expire']= date account will expire
// $_SESSION['bytesleft']= bytes left in customers bytes block account (0 if in unlimited VIP mode)
// $_SESSION['promotion']=1 for first time one week promotion,  0 if not 
// $_SESSION['loggedin'] = 0 on login failure,   1 for success
// $_SESSION['customers_email'] = $customers_email;
// $_SESSION['login_date_time'] = $login_date_time;
// $_SESSION['login_timestamp'] = time();
// $_SESSION['password'] = password attempted 

function writelog($string){
  return;
  $f = fopen("/var/www/debug.log", "a");
  fwrite( $f, $string );
  print($string);
  fclose( $f );
}

function ip_enable($ip){
  $IPTABLES="/sbin/iptables";    
  $command = "sudo ". $IPTABLES . " -t nat -I PREROUTING  -s " . $ip . " -j RETURN ";
  $result = exec($command);   
  $command = "sudo ". $IPTABLES . " -I FORWARD -d " . $ip ;
  $result = exec($command);
  $command = "sudo ". $IPTABLES . " -I FORWARD -s " . $ip ;
  $result = exec($command);
} 

function check_user_already($customers_email,$remote_addr, $mysql){  
  // will check to see if the first customer in this search has ever had an account with freenet before
  // by seeing if his present ip in this session exists with a different user.
  // return 1  if never existed before and all ok for promotion 
  // if  customer ip has never been set then set it now to present session ip
  // $remote_addr comes from $HTTP_SERVER_VARS["REMOTE_ADDR"] on the session page

   // check to see if customer has an IP address set in mysql records
  // if not make sure they don't already have an account as someone else
  // if no records with this ip then update there ip address in customer account
  writelog("check_user_already started \n");
  writelog("remote_addr = " . $remote_addr . "\n");
  writelog("email = " . $customers_email . "\n");
  $query="SELECT * FROM customers WHERE customers_email_address='$customers_email'";
  $result = get_query($query,$mysql); 

  if ($result == 0){
    //echo "<br> Failed Freenet user not found <br>";
    mysql_close();
    $_SESSION['code']=0;
    writelog("failed user not found code 0\n");
    return 0;
  }

  $cust_ip_address=mysql_result($result,0,"customers_ip_address");
  writelog("cust_ip_address = " . $cust_ip_address . "\n");
  $strlen = strlen($cust_ip_address);
  // echo "strlen = $strlen <br>";
  if (strlen($cust_ip_address)==0){
    $cust_ip_address = $remote_addr;

    if (strlen($cust_ip_address)<7){
      // invalid ip address given to check
      $_SESSION['code']= -5;
      writelog(" invalid ip address given code -5 \n");
      return -5;
    }

    // $cust_ip_address = $HTTP_SERVER_VARS["REMOTE_ADDR"];
    // echo " ip = $cust_ip_address <br>";
    $query="SELECT * FROM customers WHERE customers_ip_address='$cust_ip_address'";
    $result = get_query($query,$mysql);
    writelog("result 1 = " . $result . "\n");
    if ($result<>0){
      // sorry they already have an account they will have to update the other one
      mysql_close();
      $_SESSION['code']= -3;
      writelog("account already exists with this ip code -3 \n");
      return -3;
    }
    $query = "UPDATE customers SET customers_ip_address='$cust_ip_address' WHERE customers_email_address='$customers_email'";
    get_query($query,$mysql);
    writelog("new user ok code 1, updated customers_ip_address to " . $cust_ip_address ."\n");
    return 1;
  }
  writelog("this user already has cust_ip_address set code -6 \n");
  return -6;
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


	
function new_mysql($mysql_username,$mysql_password,$mysql_database,$mysql_host){

  // make a mysql data array
  if($mysql_host==""){$mysql_host="localhost";}
  $mysql = array('username'=>$mysql_username,
                 'password'=>$mysql_password,
                 'database'=>$mysql_database,
                 'host'=>$mysql_host);
  mysql_connect($mysql['host'],$mysql['username'],$mysql['password'],true);
  //@mysql_select_db($mysql['database']) or die( "Unable to select database");
  mysql_select_db($mysql['database']) or die( "Unable to select database");
  return $mysql;
}


function get_query($query,$mysql){  
  $result=mysql_query($query);
  if (strpos($query,"UPDATE")===FALSE){
    $num=mysql_numrows($result);
    // echo "num = $num <br>";
    if ($num == 0){
      // no results found 
      //echo "no rusults found <br>";  
      //mysql_close();
      return 0;
    }
  }
  // mysql_close();
  // echo "result = $result <br>";
  return $result;
}
// end function get_query

function GetEmailAddress($customers_id,$mysql){
  $query="SELECT * FROM customers WHERE customers_id='$customers_id'";
  $result = get_query($query,$mysql);
  if ($result == 0){
    return "nul";
  }
  return mysql_result($result,0,"customers_email_address");
}

function GetExpireDate($customers_id,$mysql){
  $query="SELECT * FROM customers WHERE customers_id='$customers_id'";
  $result = get_query($query,$mysql);
  if ($result == 0){
    return "nul";
  }
  return mysql_result($result,0,"customers_date_account_expires");
}

function update_account($customers_email,$mysql,$configValues){
   writelog("update_account starte \n");
  //$mysql = new_mysql($username,$password,$database,"localhost");
  $query="SELECT * FROM customers WHERE customers_email_address='$customers_email'";
  $result = get_query($query,$mysql);
  if ($result == 0){
    //echo "<br> Failed Freenet customers_email not found <br>";
    mysql_close();
    writelog("customers_email not found \n");
    writelog("customers_email = " . $customers_email . "\n");
    return 0;
  }
  $expire=mysql_result($result,0,"customers_date_account_expires");
  $password=mysql_result($result,0,"customers_password");
  $timenow = time();
  $expire = strtotime($expire);
  $newexpire = $expire;
  if ($timenow > $expire){
    $newexpire = $timenow;
  }
  // check account for pending orders status 
  $query="SELECT * FROM orders WHERE customers_email_address='$customers_email' AND orders_status = 1";
  $result = get_query($query,$mysql);

  if ($result == 0){
    // check for promotion package before we give up
    $query= "SELECT * FROM customers WHERE customers_email_address='$customers_email' AND customers_promotion_received IS NULL";
    $result = get_query($query,$mysql); 
    //$result =mysql_query($query);
    // $num =mysql_numrows($result);
    if ($result==0){
      //echo "<b><center> Sorry $first your account has expired. Please go to the Freenet store to correct</center></b><br>"; 
      mysql_close();
      //$_SESSION['code']= -2;
      writelog(" expired with no promotion \n");
      writelog("expire = " . $expire . "\n");
      return $expire;
    }

    // they just stared a new account so give them the 1 day free package 24 hours  = 86400 secounds
    $newexpire=time()+86400;
    writelog("newexpire = " . $newexpire . "\n");
    $query = "UPDATE customers SET customers_date_account_expires=FROM_UNIXTIME($newexpire) WHERE customers_email_address='$customers_email'";
    get_query($query,$mysql);
    //mysql_query($query);
    $query = "UPDATE customers SET customers_promotion_received=NOW() WHERE customers_email_address='$customers_email'";
    get_query($query,$mysql);
    //mysql_query($query);
    $_SESSION['expire'] = date("F j, Y, g:i a",$newexpire);

    $_SESSION['promotion'] = 1;
    //echo "Two day promotion package has now been credited to your account<br> Your account now expires: $newexpire<br>";
    $_SESSION['code']= 1;
    $_SESSION['loggedin'] = 1;
    mysql_close();
    rad_update_user($customers_email,$password,$newexpire,$configValues);    
    writelog("newexpire after rad_update_user = " . $newexpire . "\n");
    writelog("update_account completed \n");
    return $newexpire; 
  }

  // process new orders 
  $num =mysql_numrows($result);
  // echo "<br>num = $num <br>";
  // echo "<br> Processing new orders <br>";
  $i=0;
  // products bytes expire order process not implemented yet but commented bellow might work
  //$new_bytes = 0;
  while ($i < $num) {
    //$date_purchased=mysql_result($result,$i,"date_purchased");
    //echo "date of purchased = $date_purchased <br>";
    $orders_id=mysql_result($result,$i,"orders_id");
    //echo "orders_id = $orders_id <br>";
    $query="SELECT * FROM orders_products WHERE orders_id='$orders_id'";
    //echo $query ;
    $result2 = get_query($query,$mysql);
    $products_id=mysql_result($result2,0,"products_id");
    //echo "products_id = $products_id <br>";
    $products_quantity=mysql_result($result2,0,"products_quantity");

    $query="SELECT * FROM products WHERE products_id='$products_id'";
    $result2 = get_query($query,$mysql);
    $products_hours_to_expire=mysql_result($result2,0,"products_hours_to_expire");
    //$products_bytes_to_expire=mysql_result($result2,0,"products_bytes_to_expire");
    //$new_bytes = $new_bytes + $products_bytes_to_expire;
    $newexpire = $newexpire + (($products_hours_to_expire*$products_quantity)*60*60);
    //echo "products_hours_to_expire = $products_hours_to_expire <br>";
    $query="UPDATE orders SET orders_status=2  WHERE customers_email_address='$customers_email' AND orders_status = 1 AND orders_id ='$orders_id'";
    get_query($query,$mysql);
    $i++;
  }
      
  if ($newexpire>$expire){
    //echo "newexpire = $newexpire <br>";
    $query = "UPDATE customers SET customers_date_account_expires=FROM_UNIXTIME($newexpire) WHERE customers_email_address='$customers_email'";
    get_query($query,$mysql);
    //mysql_query($query);
    $query = "UPDATE customers SET customers_promotion_received=NOW() WHERE customers_email_address='$customers_email'";
    get_query($query,$mysql);
    //$query = "UPDATE customers SET customers_bytes_ordered=customers_bytes_ordered+$new_bytes WHERE customers_email_address='$customers_email'";
    mysql_close();
    rad_update_user($customers_email,$password,$newexpire,$configValues);
    return $newexpire;
  }
  mysql_close();
  return $expire;
}

function checkacc($customers_email,$customers_password,$username,$password,$database,$configValues){

  if (strlen($_SESSION['customer_ip'])<7){
    //$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
    $ip = $_SERVER['REMOTE_ADDR'];
    $_SESSION['customer_ip'] = $ip;
  }
  $ip = $_SESSION['customer_ip'];
  //echo "ip = $ip <br>";

  // set all defaults for not logged in
  $_SESSION['loggedin'] = 0;
  $_SESSION['promotion']= 0;
  $_SESSION['customers_email'] = $customers_email;
  $_SESSION['customers_password'] = $customers_password;
  $_SESSION['login_date_time'] = date("F j, Y, g:i a");
  $_SESSION['login_timestamp'] = time();
  $_SESSION['bytesleft']= 0;

  // check freenet user name
  $mysql = new_mysql($username,$password,$database,"localhost");
  $query="SELECT * FROM customers WHERE customers_email_address='$customers_email'";
  $result = get_query($query,$mysql);

  if ($result == 0){
    //echo "<br> Failed Freenet user not fount <br>";
    mysql_close();
    $_SESSION['code']=0;
    return 0;
  }
  
  // check freenet password and collect needed user info
  $i=0;
  $first=mysql_result($result,$i,"customers_firstname");
  $_SESSION['firstname'] = $first;
  $email=mysql_result($result,$i,"customers_email_address");
  $expire=mysql_result($result,$i,"customers_date_account_expires");
  $_SESSION['expire'] = $expire;
  $strlen = strlen($expire);
  $cust_password=mysql_result($result,$i,"customers_password");
  $pass = strcmp($cust_password,$customers_password);
  if ($pass<>0){
    // echo "<br> Failed  user name (email) not found in database <br>";
    mysql_close();
    $_SESSION['code']= -1; 
    return -1;
  }
  

  // check to see if customer has an IP address set in mysql records
  // if not make sure they don't already have an account as someone else
  // if no records with this ip then update there ip address in customer account
  $cust_ip_address=mysql_result($result,$i,"customers_ip_address");
  $strlen = strlen($cust_ip_address);
  // echo "strlen = $strlen <br>";
  if (strlen($cust_ip_address)==0){
    $cust_ip_address = $_SESSION['customer_ip'];
    if (strlen($cust_ip_address)<7){
      // invalid ip address given to check
      $_SESSION['code']= -5;
      return 5;
    }
    // $cust_ip_address = $HTTP_SERVER_VARS["REMOTE_ADDR"];
    // echo " ip = $cust_ip_address <br>";
    $query="SELECT * FROM customers WHERE customers_ip_address='$cust_ip_address'";
    $result = get_query($query,$mysql);
    if ($result<>0){
      // sorry they already have an account they will have to update the other one
      mysql_close();
      $_SESSION['code']= -3;
      return -3;
    }
    $query = "UPDATE customers SET customers_ip_address='$cust_ip_address' WHERE customers_email_address='$customers_email'";
    get_query($query,$mysql);
  }

  $expire = update_account($customers_email,$mysql,$configValues);

  // check if account time window expired
  $timenow = time();
  if ($timenow > $expire){
    // time window has expired so
    // set code account expired and return -2
    $_SESSION['code']= -2;
    return -2;
  }
  // successfull login
  $expire = date("F j, Y, g:i a",$expire);
  $_SESSION['expire'] = $expire;
  //echo "<b>firstname: $first  <br>E-mail: $email<br>Account will expires on: $expire   <br><hr><br>";
  //echo "<br> cust_password = $cust_password   customers_password = $customers_password <br>";
  //echo "<br> timenow = $timenow  <br>";
  $_SESSION['code']= 1;
  $_SESSION['loggedin'] = 1;
  mysql_close();
  return 1;
}
//end function checkacc

?>
