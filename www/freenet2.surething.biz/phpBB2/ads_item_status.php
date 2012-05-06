<?
/***************************************************************************
 *                              ads_status.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_status.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

$ads_root_path = $phpbb_root_path . 'ads_mod/';
include($ads_root_path . 'ads_common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

if ( empty($HTTP_GET_VARS[id]) )
{
	message_die(GENERAL_ERROR, $lang['invalid_request']);
}

// Sanitize input variables
$id = intval($HTTP_GET_VARS['id']);

// Read the ads table
$sql = "SELECT * 
		FROM ". ADS_ADVERTS_TABLE ."
		WHERE id = '$id'";

$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);

if ( !$row )
{
	message_die(GENERAL_ERROR, $lang['ad_not_found']);
}

$user_id = $row['user_id'];
$status = $row['status'];
$category = $row['category'];
$sub_category = $row['sub_category'];

// Read the categories table
$sql = "SELECT * 
		FROM ". ADS_CATEGORIES_TABLE ."
		WHERE cat_category = '".addslashes($category)."'
		AND cat_sub_category = '".addslashes($sub_category)."'";

$result = $db->sql_query($sql);
$category_row = $db->sql_fetchrow($result);

if ( !$category_row )
{
	message_die(GENERAL_ERROR, "Error reading categories table", "", __LINE__, __FILE__, $sql);
}

// Check the permissions
$cat_edit_level = $category_row['cat_edit_level'];

if ( edit_allowed($user_id, $cat_edit_level) == FALSE )
{
	message_die(GENERAL_ERROR, $lang['Not_Authorised']);
}

// Main processing
if ( empty($HTTP_GET_VARS[status]) )
{
	message_die(GENERAL_ERROR, $lang['invalid_request']);
}

// Sanitize input variables
$new_status = htmlspecialchars($HTTP_GET_VARS[status]);

// Extra sanitize for SQL variables
$new_status = str_replace("\'", "''", $new_status);

if ( $new_status != 'active' and $new_status != 'sold' )
{
	message_die(GENERAL_ERROR, $lang['invalid_request']);
}

// Can only change sold to active or active to sold
if ( $new_status == 'sold' and $status != 'active' )
{
	message_die(GENERAL_ERROR, $lang['invalid_request']);
}

if ( $new_status == 'active' and $status != 'sold' )
{
	message_die(GENERAL_ERROR, $lang['invalid_request']);
}

// Update the adverts table
$sql = "UPDATE ". ADS_ADVERTS_TABLE ."
		SET status = '$new_status' 
		WHERE id = $id";

if ( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, 'Could not update adverts row', '', __LINE__, __FILE__, $sql);
}

$template->assign_vars(array(
	'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("ads_item.$phpEx?id=$id") . '">'));

$message = $lang['edit_confirmation'] . "<br /><br />" . sprintf($lang['click_to_view_ad'], "<a href=\"" . append_sid("ads_item.$phpEx?id=$id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
message_die(GENERAL_MESSAGE, $message);

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);	
?>