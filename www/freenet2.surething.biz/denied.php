<?php
include('logsession.php');

session_start();
logsession();
// $code will return 1 if we have success in password check and account check
// $code will return 0 if no user with this name exists
// $code will return -1 for bad password but a user does exist
// $code will return -2 for account expired
// $code will return -3 for ip already exists in another account

// $_SESSION['firstname'] = "scotty";// users first name
// $_SESSION['expire']= date account will expire in human readable format
// $_SESSION['promotion']=1 for first time one week promotion,  0 if not
// $_SESSION['code']= 1;//  see $code above for details,  1 login ok 0 bad user -1 bad password ...
// $_SESSION['loggedin'] = 0 on login failure,   1 for success
// $_SESSION['customers_email'] = "sacarlson@ipipi.com";
// $_SESSION['login_date_time'] = $login_date_time;
// $_SESSION['login_timestamp'] = time();
// $_SESSION['customer_ip'] = address customer logged in as
$code =  $_SESSION['code'];
$firstname = $_SESSION['firstname'];
$expire = $_SESSION['expire'];


if ((!$_SESSION['loggedin']) || ($_SESSION['loggedin'] == 0)) {

        header( 'refresh: 120; url=/catalog2/index.php' );
   if ($code==-3){
     echo "<h1>Sorry $firstname an account already exist for this computer with a different email address.  Press CONTINUE to fix in store or call scotty <h1>";
   } 
   if ($code > -2){
    echo "<h1>Sorry Login Failed incorrect user or password!!!.  Press CONTINUE to fix in store or try again</h1>";
   }
   if ($code == -2){
    echo "<h1> Sorry $firstname your account has expired on $expire.  Press CONTINUE to fix in store.</h1>";
   }
   if ($code == -4){
     $bytes = $_SESSION['bytesleft'];
     echo "<h1> Sorry $firstname Your freenet account byte block is overdrawn at $bytes.  Press CONTINUE to fix in store </h1>";
   }
 echo "error code = $code <br>";
 echo '<FORM ACTION="/catalog2/index.php" METHOD="POST"> <P><INPUT TYPE=SUBMIT VALUE="CONTINUE"STYLE="width: 0.90in; height: 0.26in"></P></FORM>'; 
}


?>
