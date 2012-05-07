<?php



if ($_GET['login']) {



        include('config.php');
	include('checkacc.php');
	 session_start();
 if (strlen($_SESSION['customer_ip'])<7){
   //$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
   $ip = $_SERVER['REMOTE_ADDR'];
   $_SESSION['customer_ip'] = $ip;
 }

//echo "ip = $ip <br>";	


//	$mysql_database		= "freenet2";			// mysql database

//	$mysql_hostname		= "localhost";			// mysql hostname

//	$mysql_username		= "sacarlson";				// mysql username

//	$mysql_password		= "password";			// mysql password



	$table_name			= "customers";				// your login table name (login)

	$table_username		= "customers_email_address";			// table field w/ usernames

	$table_password		= "customers_password";			// table field w/ passwords

	

	$query_username 	= $_POST['username'];	// query username

	$query_password 	= $_POST['password'];	// query password



	$redirect_accepted	= "success.php";		// redirects to this if login is succesful

	$redirect_denied	= "denied.php";			// redirects to this if login has been denied

/* original login.php commented out


	$a = new login($mysql_database,$mysql_hostname,$mysql_username,$mysql_password,$query_username,$query_password,$table_name,$table_username,$table_password,$redirect_accepted,$redirect_denied);

	

	$a->login_mysql();

	$a->login_check();

*/ 

/* start example checkacc


// mysql account data
$username="sacarlson";
$password="password";
$database="freenet";

// this is data to be received from web page
$customers_email="sacarlson@ipipi.com";
$customers_password="password";
$ok = checkacc($customers_email,$customers_password,$username,$password,$database);
echo "<br> ok = $ok <br>";
// $ok will return 1 if we have success in password check and account check
// $ok will return 0 if no user with this name exists
// $ok will return -1 for bad password but a user does exist
// $ok will return -2 for account expired
// also returned session veribles:
// $_SESSION['firstname'] = users first name
// $_SESSION['expire']= date account will expire
// $_SESSION['promotion']=1 for first time one week promotion,  0 if not
// $_SESSION['code']= the same as the number returned example ok above
// $_SESSION['loggedin'] = 0 on login failure,   1 for success

end example checkacc
*/ 

 session_start();
 $ok = checkacc($query_username,$query_password,$mysql_username,$mysql_password,$mysql_database);

//echo "ok = $ok <br>";


                if ($ok == 1) {

                        $_SESSION['loggedin'] = 1;

                        if ( $redirect_accepted) header("Location:  $redirect_accepted");

                        }

                else {

                        $_SESSION['loggedin'] = 0;

                        if ($redirect_denied) header("Location: $redirect_denied");

                        }


	echo "should never get here <br>";



	}

else {

	

	print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";

	print "\n";

	print "<html>\n";

	print "<head>\n";

	print "<title>Login</title>\n";

	print "</head>\n";

	print "<body>\n";

	print "\n";
        print "<h1>Welcome to Freenet Please login here </h1>";
        print "Your email and password entered here are the same  email address and password used \n";
        print "in the FreeNet Store when you created a new account.  <br>";

	print "<form method=\"post\" action=\"login_new.php?login=1\">\n";

	print "email address:<br />\n";

	print "<input type=text name=username>\n";

	print "<br /><br />\n";

	print "password:<br />\n";

	print "<input type=password name=password>\n";

	print "<br /><br />\n";

	print "<input type=submit>\n";

	print "</form>\n";

	print "\n";
        echo "If you forgot your password please go to the Freenet Store and try to login there.<br>";
        print "The Freenet store will provide the option for us to email you a new password";

	print "</body>\n";

	print "</html>\n";

	

	}



?>

