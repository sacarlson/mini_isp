<?php
/***************************************************************************
 *                               auction_pop_pic.php
 *                            -------------------
 *   begin                : 25 July 2004
 *   copyright            : phpbb-auction
 *   email                : fr@php-styles.com
 *
 *   Strongly inspired by : smartor_xp@hotmail.com
 *
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
     $phpbb_root_path = './';
     $auction_root_path = $phpbb_root_path . 'auction/';
     include($phpbb_root_path . 'extension.inc');
     include($phpbb_root_path . 'common.'.$phpEx);

     // Start session management
     $userdata = session_pagestart($user_ip, 444);
     init_userprefs($userdata);
     // End session management

     if( isset($HTTP_GET_VARS['pic_id']) )
          {
	       $pic_id = intval($HTTP_GET_VARS['pic_id']);
          }
     else if( isset($HTTP_POST_VARS['pic_id']) )
          {
	       $pic_id = intval($HTTP_POST_VARS['pic_id']);
          }
     else
          {
               die('No pics specified');
          }
if( isset($HTTP_GET_VARS['real_width']) )
{
	$real_width = intval($HTTP_GET_VARS['real_width']);
}
else if( isset($HTTP_POST_VARS['real_width']) )
{
	$real_width = intval($HTTP_POST_VARS['real_width']);
}
else
{
	die('No width specified');
}
if( isset($HTTP_GET_VARS['real_height']) )
{
	$real_height = intval($HTTP_GET_VARS['real_height']);
}
else if( isset($HTTP_POST_VARS['real_height']) )
{
	$real_height = intval($HTTP_POST_VARS['real_height']);
}
else
{
	die('No height specified');
}

$t = "Auction Image";

$img = append_sid("auction_thumbnail.$phpEx?pic_type=0&pic_id=$pic_id");
// this is non-standard phpbb code!!! but it works
print "<html>";
print "<head>";
print "<script language=\"javascript\">\n";
print "function shutwin() {\n";
print 'setTimeout("window.close()",1250);'."\n"; 
print "}\n";
print "</script>\n";
print "<title>$t</title>";
print "</head>";
print "<body bgcolor=\"Black\" leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 onblur=\"shutwin();\" onload=\"self.focus();\">";
print "<img src=\"$img\" width=$real_width height=$real_height border=0 galleryimg=\"no\" onClick= \"window.close();\">";
print "</body></html>";


exit;
?>
