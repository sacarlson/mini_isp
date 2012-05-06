<?
/***************************************************************************
 *                           ads_db_uninstall.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_db_uninstall.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', true);
$phpbb_root_path='./';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

//
// Here we go
//
include($phpbb_root_path.'includes/sql_parse.'.$phpEx);

$available_dbms = array(
	"mysql" => array(
		"SCHEMA" => "mysql",
		"DELIM" => ";",
		"DELIM_BASIC" => ";",
		"COMMENTS" => "remove_remarks"
	),
	"mysql4" => array(
		"SCHEMA" => "mysql",
		"DELIM" => ";",
		"DELIM_BASIC" => ";",
		"COMMENTS" => "remove_remarks"
	),
	"postgres" => array(
		"SCHEMA" => "postgres",
		"DELIM" => ";",
		"DELIM_BASIC" => ";",
		"COMMENTS" => "remove_comments"
	),
	"mssql" => array(
		"SCHEMA" => "mssql",
		"DELIM" => "GO",
		"DELIM_BASIC" => ";",
		"COMMENTS" => "remove_comments"
	),
	"mssql-odbc" =>	array(
		"SCHEMA" => "mssql",
		"DELIM" => "GO",
		"DELIM_BASIC" => ";",
		"COMMENTS" => "remove_comments"
	)
);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html;">
<meta http-equiv="Content-Style-Type" content="text/css">
<style type="text/css">
<!--

font,th,td,p,body { font-family: "Courier New", courier; font-size: 11pt }

a:link,a:active,a:visited { color : #006699; }
a:hover		{ text-decoration: underline; color : #DD6900;}

hr	{ height: 0px; border: solid #D1D7DC 0px; border-top-width: 1px;}

.maintitle,h1,h2	{font-weight: bold; font-size: 22px; font-family: "Trebuchet MS",Verdana, Arial, Helvetica, sans-serif; text-decoration: none; line-height : 120%; color : #000000;}

.ok {color:green}

-->
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#006699" vlink="#5584AA">

<table width="100%" border="0" cellspacing="0" cellpadding="10" align="center">
	<tr>
		<td><table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center" width="100%" valign="middle"><span class="maintitle">Uninstalling phpCA - Classified Ads for phpBB2</span></td>
			</tr>
		</table></td>
	</tr>
</table>

<br clear="all" />

<?php

$remove_remarks = $available_dbms[$dbms]['COMMENTS'];;
$delimiter = $available_dbms[$dbms]['DELIM'];
$delimiter_basic = $available_dbms[$dbms]['DELIM_BASIC'];

//
// drop table schema
//
$sql_array = array();

$sql_array['drop_schema'][] = "DROP TABLE phpbb_ads_adverts";
$sql_array['drop_schema'][] = "DROP TABLE phpbb_ads_categories";
$sql_array['drop_schema'][] = "DROP TABLE phpbb_ads_chasers";
$sql_array['drop_schema'][] = "DROP TABLE phpbb_ads_comments";
$sql_array['drop_schema'][] = "DROP TABLE phpbb_ads_config";
$sql_array['drop_schema'][] = "DROP TABLE phpbb_ads_details";
$sql_array['drop_schema'][] = "DROP TABLE phpbb_ads_images";
$sql_array['drop_schema'][] = "DROP TABLE phpbb_ads_paid_ads_config";
$sql_array['drop_schema'][] = "DROP TABLE phpbb_ads_rate";
$sql_array['drop_schema'][] = "DROP TABLE phpbb_ads_users";
$sql_array['drop_schema'][] = "DROP TABLE phpbb_ads_paypal_payments";

//
// build sql query
//
$sql_query = preg_replace('/phpbb_/', $table_prefix, $sql_array['drop_schema']);
$sql_count = count($sql_query);

echo "<html>\n";
echo "<body>\n";
for($i = 0; $i < $sql_count; $i++)
{
	echo "Running :: " . $sql_query[$i];
	flush();

	if ( !($result = $db->sql_query($sql_query[$i])) )
	{
		$errored = true;
		$error = $db->sql_error();
		echo " -> <b>FAILED</b> ---> <u>" . $error['message'] . "</u><br /><br />\n\n";
	}
	else
	{
		echo " -> <b><span class=\"ok\">COMPLETED</span></b><br /><br />\n\n";
	}
}


if ( $errored )
{
	$message .= '<br />Some queries failed. Please contact me at <a href="http://www.phpca.net">http://www.phpca.net</a> we may solve your problems...<br />';
}
else
{
	$message .= '<br />Classified Ads Tables removed successfully.<br />';
}

echo "\n<br />\n<b>COMPLETE!</b><br />\n";
echo $message . "<br />";
echo "<br /><b>NOW DELETE THIS FILE</b><br />\n";
echo "</body>";
echo "</html>";

?>