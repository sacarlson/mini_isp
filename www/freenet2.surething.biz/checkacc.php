<?php

if (strlen($_SESSION['customer_ip'])<7){
  //$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
  $ip = $_SERVER['REMOTE_ADDR'];
  $_SESSION['customer_ip'] = $ip;
}

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
	
function new_mysql($mysql_username,$mysql_password,$mysql_database,$mysql_host){

  // make a mysql data array
  if($mysql_host==""){$mysql_host="localhost";}
  $mysql = array('username'=>$mysql_username,
                 'password'=>$mysql_password,
                 'database'=>$mysql_database,
                 'host'=>$mysql_host);
  mysql_connect($mysql['host'],$mysql['username'],$mysql['password']);
  @mysql_select_db($mysql['database']) or die( "Unable to select database");
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

function update_account($customers_email,$mysql){
  //$mysql = new_mysql($username,$password,$database,"localhost");
  $query="SELECT * FROM customers WHERE customers_email_address='$customers_email'";
  $result = get_query($query,$mysql);
  if ($result == 0){
    //echo "<br> Failed Freenet customers_email not found <br>";
    mysql_close();
    return 0;
  }
  $expire=mysql_result($result,0,"customers_date_account_expires");
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
      return $expire;
    }

    // they just stared a new account so give them the 1 day free package 24 hours  = 86400 secounds
    $newexpire=time()+86400;
    $query = "UPDATE customers SET customers_date_account_expires=FROM_UNIXTIME($newexpire) WHERE customers_email_address='$customers_email'";
    get_query($query,$mysql);
    //mysql_query($query);
    $query = "UPDATE customers SET customers_promotion_received=NOW() WHERE customers_email_address='$customers_email'";
    get_query($query,$mysql);
    //mysql_query($query);
    $newexpire = date("F j, Y, g:i a",$newexpire);
    $_SESSION['expire'] = $newexpire;
    $_SESSION['promotion'] = 1;
    //echo "Two day promotion package has now been credited to your account<br> Your account now expires: $newexpire<br>";
    $_SESSION['code']= 1;
    $_SESSION['loggedin'] = 1;
    mysql_close();
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
    return $newexpire;
  }
  mysql_close();
  return $expire;
}

function checkacc($customers_email,$customers_password,$username,$password,$database){

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

  $expire = update_account($customers_email,$mysql);

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
