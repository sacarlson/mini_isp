<?php

#############################################################
#                                                           #
#  Program Base    : IPN Development Handler                #
#  Originaly By    : Marcus Cicero                          #
#  Website         : EliteWeaver UK                         #
#                                                           #
#############################################################
#                                                           #
#  Modified By     : < Omen > Damien A.                     #
#  For Use With    : phpBB2 2.0.6                           #
#  Website         : mods.Modpros.com                       #
#                                                            #
#############################################################



define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_search.'.$phpEx);
include($phpbb_root_path . 'auction/auction_constants.'.$phpEx);
$current_time = time();


     // START Include language file
     $language = $board_config['default_lang'];
     if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_auction.'.$phpEx) )
          {
               $language = 'english';
          }
     include($phpbb_root_path . 'language/lang_' . $language . '/lang_auction.' . $phpEx);
     // END include language file





// IPN validation modes, choose: 1 or 2
// 1 Live - 2 EliteWeaver
$postmode=1;

// Log action
if ( $postmode == 2 )
     {
           $status = $lang['ipn_log_testing'];
     }
if ($postmode == 1 )
     {
          $status = $lang['ipn_log_real_transaction'];
     }

$sql = "INSERT INTO " . AUCTION_IPN_LOG . " (auction_ipn_log_date, auction_ipn_log_status)
        VALUES ('" . time() . "', '" . $status . "')";

if( !$result = $db->sql_query($sql) )
  {
       message_die(GENERAL_ERROR, "Couldn't log ipn.", "", __LINE__, __FILE__, $sql);
  }
// End log action

// Debugger, 1 = on and 0 = off
$debugger=1;

// Convert super globals on older php builds

    if (phpversion() <= '4.0.6')
    {
        $_SERVER = ($HTTP_SERVER_VARS);
        $_POST = ($HTTP_POST_VARS); }

// No ipn post means this script does not exist
    if (!@$_POST['txn_type'])
    {
        @header("Status: 404 Not Found"); exit; }

    else
    {
        @header("Status: 200 OK");  // Prevents ipn reposts on some servers


    // Notify validate
    $postipn = 'cmd=_notify-validate';

    foreach ($_POST as $ipnkey => $ipnval)
    {
    if (get_magic_quotes_gpc())
        $ipnval = stripslashes ($ipnval); // Fix issue with magic quotes
    if (!eregi("^[_0-9a-z-]{1,30}$",$ipnkey)
    || !strcasecmp ($ipnkey, 'cmd'))
    { // ^ Antidote to potential variable injection and poisoning
    unset ($ipnkey); unset ($ipnval); } // Eliminate the above
    if (@$ipnkey != '') { // Remove empty keys (not values)
        @$_PAYPAL[$ipnkey] = $ipnval; // Assign data to new global array
    unset ($_POST); // Destroy the original ipn post array, sniff...

        $postipn.='&'.@$ipnkey.'='.urlencode(@$ipnval); }} // Notify string
        $error=0; // No errors let's hope it's going to stays like this!


    if ($postmode == 1)
    {
        $domain = "www.paypal.com"; }
    elseif ($postmode == 2)
    {
        $domain = "www.eliteweaver.co.uk"; }
    else
    {
        $error=1;
        $bmode=1;
    if ($debugger) debugInfo(); }


@set_time_limit(60); // Attempt to double default time limit incase we switch to Get



// Post back the reconstructed instant payment notification

        $socket = @fsockopen($domain,80,$errno,$errstr,30);
        $header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
        $header.= "User-Agent: PHP/".phpversion()."\r\n";
        $header.= "Referer: ".$_SERVER['HTTP_HOST'].
        $_SERVER['PHP_SELF'].@$_SERVER['QUERY_STRING']."\r\n";
        $header.= "Server: ".$_SERVER['SERVER_SOFTWARE']."\r\n";
        $header.= "Host: ".$domain.":80\r\n";
        $header.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header.= "Content-Length: ".strlen($postipn)."\r\n";
        $header.= "Accept: */*\r\n\r\n";

//* Note: "Connection: Close" is not required using HTTP/1.0


// Problem: Now is this your firewall or your ports?
            if (!$socket && !$error)
            {

// Switch to a Get request for a last ditch attempt!
        $getrq=1;

    if (phpversion() >= '4.3.0'
    && function_exists('file_get_contents'))
    {} // Checking for a new function
    else
    { // No? We'll create it instead

function file_get_contents($ipnget) {
        $ipnget = @file($ipnget);
    return $ipnget[0];
        }}

                   $response = @file_get_contents('http://'.$domain.':80/cgi-bin/webscr?'.$postipn);

    if (!$response)
    {
        $error=1;
        $getrq=0;

    if ($debugger) debugInfo();
    // If this is as far as you get then you need a new web host!
            }}



// If no problems have occured then we proceed with the processing

    else
    {
        @fputs ($socket,$header.$postipn."\r\n\r\n"); // Required on some environments
    while (!feof($socket))
    {
        $response = fgets ($socket,1024); }}
        $response = trim ($response); // Also required on some environments

        // Log action
        $sql = "INSERT INTO " . AUCTION_IPN_LOG . " (auction_ipn_log_date, auction_ipn_log_status)
                VALUES ('" . time() . "', '" . $lang['ipn_log_postback'] . "')";

        if( !$result = $db->sql_query($sql) )
          {
               message_die(GENERAL_ERROR, "Couldn't log ipn.", "", __LINE__, __FILE__, $sql);
          }


// uncomment '#' to assign posted variables to local variables
extract($_PAYPAL); // if globals is on they are already local

// and/or >>>

// refer to each ipn variable by reference (recommended)
// $_PAYPAL['receiver_id']; etc... (see: ipnvars.txt)



// IPN was confirmed as both genuine and VERIFIED
    if (!strcmp ($response, "VERIFIED"))
    {

                      // Log action
                  $sql = "INSERT INTO " . AUCTION_IPN_LOG . " (auction_ipn_log_date, auction_ipn_log_status)
                          VALUES ('" . time() . "', '" . $lang['ipn_log_confirmation_received'] . "')";

                  if( !$result = $db->sql_query($sql) )
                    {
                         message_die(GENERAL_ERROR, "Couldn't log ipn.", "", __LINE__, __FILE__, $sql);
                    }

                 // UPDATE Last bid in offer-table. I know its not normalized, but it saves us a lot of sql-queries on the users frontend.
                 $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                         SET auction_offer_paid  =  1
                         WHERE PK_auction_offer_id = " . $_PAYPAL['item_number'] . "";
                 if( !($result = $db->sql_query($sql)) )
                     {
                         // Nobody cares anyways as just paypal uses this site .... ;)
                     }

                  // Log action
                  $sql = "INSERT INTO " . AUCTION_IPN_LOG . " (auction_ipn_log_date, auction_ipn_log_status,FK_auction_offer_id )
                          VALUES ('" . time() . "', '" . $lang['ipn_log_confirmation_received'] . "'," . $_PAYPAL['item_number'] . ")";

                  if( !$result = $db->sql_query($sql) )
                    {
                         message_die(GENERAL_ERROR, "Couldn't log ipn.", "", __LINE__, __FILE__, $sql);
                    }
    }

    elseif (!strcmp ($response, "INVALID"))
         {

                      // Log action
                  $sql = "INSERT INTO " . AUCTION_IPN_LOG . " (auction_ipn_log_date, auction_ipn_log_status)
                          VALUES ('" . time() . "', '" . $lang['ipn_log_invalid'] . "')";

                  if( !$result = $db->sql_query($sql) )
                    {
                         message_die(GENERAL_ERROR, "Couldn't log ipn.", "", __LINE__, __FILE__, $sql);
                    }

            }



    else
    { // Just incase something serious should happen!
            }}

    if ($debugger) debugInfo();



#########################################################
#     Inernal Functions : variableAudit & debugInfo     #
#########################################################


// Function: variableAudit
// Easy LOCAL to IPN variable comparison
// Returns 1 for match or 0 for mismatch

function variableAudit($v,$c)
{
    global  $_PAYPAL;
    if (!strcasecmp($_PAYPAL[$v],$c))
    { return 1; } else { return 0; }
}



// Function: debugInfo
// Displays debug info
// Set $debugger to 1

function debugInfo()
{
    global  $_PAYPAL,
        $postmode,
        $socket,
        $error,
        $postipn,
        $getrq,
        $response;

        $ipnc = strlen($postipn)-21;
        $ipnv = count($_PAYPAL)+1;

    @flush();
    @header('Cache-control: private'."\r\n");
    @header('Content-Type: text/plain'."\r\n");
    @header('Content-Disposition: inline; filename=debug.txt'."\r\n");
    @header('Content-transfer-encoding: ascii'."\r\n");
    @header('Pragma: no-cache'."\r\n");
    @header('Expires: 0'."\r\n\r\n");
    echo '#########################################################'."\r\n";
    echo '# <-- PayPal IPN Variable Output & Status Debugger! --> #'."\r\n";
    echo '#########################################################'."\r\n\r\n";
    if (phpversion() >= '4.3.0' && $socket)
    {
    echo 'Socket Status: '."\r\n\r\n";
    print_r (socket_get_status($socket));
    echo "\r\n\r\n"; }
    echo 'PayPal IPN: '."\r\n\r\n";
    print_r($_PAYPAL);
    echo "\r\n\r\n".'Validation String: '."\r\n\r\n".wordwrap($postipn, 64, "\r\n", 1);
    echo "\r\n\r\n\r\n".'Validation Info: '."\r\n";
    echo "\r\n\t".'PayPal IPN String Length Incoming => '.$ipnc."\r\n";
    echo "\t".'PayPal IPN String Length Outgoing => '.strlen($postipn)."\r\n";
    echo "\t".'PayPal IPN Variable Count Incoming => ';
    print_r(count($_PAYPAL));
    echo "\r\n\t".'PayPal IPN Variable Count Outgoing => '.$ipnv."\r\n";
    if ($postmode == 1)
    {
    echo "\r\n\t".'IPN Validation Mode => Live -> PayPal, Inc.'; }
    elseif ($postmode == 2)
    {
    echo "\r\n\t".'IPN Validation Mode => Test -> EliteWeaver.'; }
    else
    {
    echo "\r\n\t".'IPN Validation Mode => Incorrect Mode Set!'; }
    echo "\r\n\r\n\t\t".'IPN Validate Response => '.$response;
    if (!$getrq && !$error)
    {
    echo "\r\n\t\t".'IPN Validate Method => POST (success)'."\r\n\r\n"; }
    elseif ($getrq && !$error)
    {
    echo "\r\n\t\t".'IPN Validate Method => GET (success)'."\r\n\r\n"; }
    elseif ($bmode)
    {
    echo "\r\n\t\t".'IPN Validate Method => NONE (stupid)'."\r\n\r\n"; }
    elseif ($error)
    {
    echo "\r\n\t\t".'IPN Validate Method => BOTH (failed)'."\r\n\r\n"; }
    else
    {
    echo "\r\n\t\t".'IPN Validate Method => BOTH (unknown)'."\r\n\r\n"; }
    echo '#########################################################'."\r\n";
    echo '#    THIS SCRIPT IS FREEWARE AND IS NOT FOR RE-SALE!    #'."\r\n";
    echo '#########################################################'."\r\n\r\n";
    @flush();

}


// Terminate the socket connection (if open) and exit
    @fclose ($socket); exit;

?>