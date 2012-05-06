<?php
//include('ipacfunc.php');
include('returnMacAdress.php');
function logsession() {
  

// this will log session data to /var/www/session.log file, later will make it put in mysql database table 


// $code will return 1 if we have success in password check and account check
// $code will return 0 if no user with this name exists
// $code will return -1 for bad password but a user does exist
// $code will return -2 for account expired

// $_SESSION['firstname'] = "scotty";// users first name
// $_SESSION['expire']= date account will expire in human readable format
// $_SESSION['promotion']=1 for first time one week promotion,  0 if not
// $_SESSION['code']= 1;//  see $code above for details,  1 login ok 0 bad user -1 bad password ...
// $_SESSION['loggedin'] = 0 on login failure,   1 for success
// $_SESSION['customers_email'] = "sacarlson@ipipi.com";
// $_SESSION['customers_password'] = password of cusstomer 
// $_SESSION['login_date_time'] = $login_date_time;
// $_SESSION['login_timestamp'] = time();
// $_SESSION['customer_ip'] = address customer logged in as
// $_SESSION['totdatin'] = total byte count of data_in used on wire from start to end of recording
// $_SESSION['totdatout']= total byte count of data_out, used on wire from start to end of recording


$datetime = date("F j, Y, g:i a");
$email = $_SESSION['customers_email'];
$password = $_SESSION['customers_password'];
$firstname = $_SESSION['firstname'];
$code = $_SESSION['code'];
#$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
$ip = $_SESSION['customer_ip'];
$mac = returnMacAddress($ip);

//readipac($ip,"1M","0s");
 //addipac($ip);
// $outString = $firstname . " : ".$email." : ".$password." : ".$code . " : ".$ip. " : ". $datetime .":". $_SESSION['totdatout']."\n";
 $outString = $firstname . " : ".$email." : ".$password." : ".$code . " : ".$ip. " : ".$mac." : ". $datetime ." \n";
 $f = fopen("./private/session.log", "a");
 fwrite( $f, $outString );
 fclose( $f );
 return;
 }
?>
