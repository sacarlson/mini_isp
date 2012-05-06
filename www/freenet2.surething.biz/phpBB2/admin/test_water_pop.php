<?php
/***************************************************************************
 *                            test_water_pop.php
 *                            -------------------
 *   begin                : Wednesday, February 05, 2003
 *   copyright            : (C) 2003 Smartor
 *   email                : smartor_xp@hotmail.com
 *
 *   $Id: album_pic.php,v 2.0.5 2003/02/28 14:33:12 ngoctu Exp $
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
     $phpbb_root_path = "./../";
     include($phpbb_root_path . 'extension.inc');
     include($phpbb_root_path . 'common.'.$phpEx);

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


if( isset( $HTTP_GET_VARS['file']) )
{
	$file = intval($HTTP_GET_VARS['file']);
}
else
{
	$file = 0;
}

$t = "Watermark-test";
$img = append_sid("test_water.$phpEx?file=$file");

print "<html>";
print "<head>";
print "<script language=\"javascript\">\n";
print "function shutwin() {\n";
print 'setTimeout("window.close()",1250);'."\n";
print "}\n";
print "</script>\n";
print "<title>$t</title>";
print "</head>";
print "<body bgcolor=\"Black\" leftmargin=0 topmargin=0  marginwidth=0 marginheight=0 onblur=\"shutwin();\" onload=\"self.focus();\">";
print "<img src=\"$img\" width=$real_width height=$real_height border=0 galleryimg=\"no\" onClick= \"window.close();\">";
print "</body></html>";


exit;
?>
