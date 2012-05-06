<?php
include('logsession.php');
session_start();
 //$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
 $ip = $_SERVER['REMOTE_ADDR'];
       $_SESSION['customer_ip'] = $ip;

logsession();

function ip_enable($ip)
  {
    $IPTABLES="/sbin/iptables";    
    $command = "sudo ". $IPTABLES . " -t nat -I PREROUTING  -s " . $ip . " -j RETURN ";
    $result = exec($command);   
    $command = "sudo ". $IPTABLES . " -I FORWARD -d " . $ip ;
    $result = exec($command);
    $command = "sudo ". $IPTABLES . " -I FORWARD -s " . $ip ;
    $result = exec($command);
  }


// $_SESSION['firstname'] = users first name
// $_SESSION['expire']= date account will expire
// $_SESSION['promotion']=1 for first time one week promotion,  0 if not
// $_SESSION['code']= the same as the number returned example ok above
// $_SESSION['loggedin'] = 0 on login failure,   1 for success
// $_SESSION['customers_email'] = $customers_email;
// $_SESSION['login_date_time'] = $login_date_time; in human readable format
// $_SESSION['login_timestamp'] = time();  in intiger seconds from 1970 format
// $_SESSION['customer_ip'] = address customer logged in as 
// $_SESSION['bytesleft']= bytes left in customers bytes block account (0 if in unlimited VIP mode)

if ($_SESSION['loggedin'] == 1) {

	//$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
	$ip = $_SERVER['REMOTE_ADDR'];
	ip_enable($ip);
	$_SESSION['customer_ip'] = $ip;

 //header( 'refresh: 10; url= http://surething.biz' );
 header( 'refresh: 30; url = http://freenet2.surething.biz/phpBB2/viewforum.php?f=2');
//	echo " <br> You have successfully logged into the world network <br>";
	$firstname = $_SESSION['firstname'];
        print "Welcome back $firstname <br> ";
	print "You have successfully logged in from IP address $ip <br>";
	$pr = $_SESSION['promotion'];
	if ($pr==1){
     	   print " You have given the automatic one day FREE promotion <br> ";
    	 }
	$expire = $_SESSION['expire'];
	print " Your account is good till:  $expire <br>";
        $bytes_left = $_SESSION['bytesleft'];
        if ($bytes_left<>0){
           echo "You have $bytes_left  bytes left in you byte block <br>";
         }
	print " You are now free to surf the Internet,   Enjoy!! <br>";
	echo '<FORM ACTION="go_google.php" METHOD="POST"> <P><INPUT TYPE=SUBMIT VALUE="CONTINUE"STYLE="width: 0.90in; height: 0.26in"></P></FORM>';
	}



?>
