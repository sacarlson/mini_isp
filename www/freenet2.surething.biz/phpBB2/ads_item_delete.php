<?
/***************************************************************************
                              ads_item_delete.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_item_delete.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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

//
// Pre-processing
//
if ( empty($HTTP_GET_VARS['id']) )
{
	message_die(GENERAL_ERROR, $lang['invalid_request']);
}

// Sanitize input data
$id = intval($HTTP_GET_VARS['id']);

// Get the row from the ads table
$sql = "SELECT * 
		FROM ". ADS_ADVERTS_TABLE ."
		WHERE id = '$id'";

$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);

if ( !$row )
{
	message_die(GENERAL_ERROR, $lang['ad_not_found']);
}

$category = $row['category'];
$sub_category = $row['sub_category'];
$user_id = $row['user_id'];
$title = $row['title'];

// Read the categories table
$sql = "SELECT * 
		FROM ". ADS_CATEGORIES_TABLE ."
		WHERE cat_category = '".addslashes($category)."'
		AND cat_sub_category = '".addslashes($sub_category)."'";

$result = $db->sql_query($sql);
$category_row = $db->sql_fetchrow($result);

if ( !$category_row )
{
	message_die(GENERAL_ERROR, "Error reading category table", "", __LINE__, __FILE__, $sql);
}

$cat_delete_level = $category_row['cat_edit_level'];

// Check the permissions
if ( delete_allowed($user_id, $cat_delete_level) == FALSE )
{
	if ( !$userdata['session_logged_in'] ) 
	{
		redirect(append_sid("login.$phpEx?redirect=ads_item_delete.$phpEx&amp;id=$id"));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}
}

//
// Main processing
//
if (!isset($HTTP_POST_VARS['confirm']))
{
	// User says no!
	if ( isset($HTTP_POST_VARS['cancel']) )
	{
		redirect(append_sid("ads_item.$phpEx?id=$id"));
		exit;
	}

	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array('delete_ad' => 'confirm_body.tpl')); 

	$template->assign_vars(array(

		'U_INDEX' => append_sid("adverts.$phpEx"),

		'S_CONFIRM_ACTION' => append_sid("ads_item_delete.$phpEx?id=$id"),

		'L_INDEX' => $board_config['sitename'].' '.$lang['ads_index'],
		'L_YES' => $lang['yes'],
		'L_NO' => $lang['no'],

		'MESSAGE_TITLE' => $lang['information'],
		'MESSAGE_TEXT' => $lang['delete_question']));

	$template->pparse('delete_ad'); 
}
else
{
	// Delete from adverts table
	$sql = "DELETE 
			FROM ". ADS_ADVERTS_TABLE ."
			WHERE id = $id";

	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Failed to delete adverts row", "", __LINE__, __FILE__, $sql);
	}

	// Delete from details table
	$sql = "DELETE 
			FROM ". ADS_DETAILS_TABLE ."
			WHERE id = $id";

	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Failed to delete details row", "", __LINE__, __FILE__, $sql);
	}

	// Delete from chasers table
	$sql = "DELETE 
			FROM ". ADS_CHASERS_TABLE ."
			WHERE id = '$id'";

	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Failed to delete chasers rows", "", __LINE__, __FILE__, $sql);
	}

	// Delete from comments table
	$sql = "DELETE 
			FROM ". ADS_COMMENTS_TABLE ."
			WHERE comment_ad_id = $id";

	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Failed to delete comments rows", "", __LINE__, __FILE__, $sql);
	}

	// Mark as deleted on images table
	$sql = "UPDATE ". ADS_IMAGES_TABLE ."
		SET img_deleted_ind = 1
		WHERE id = '$id'";

	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Failed to update images row", "", __LINE__, __FILE__, $sql);
	}

	// Delete images
	foreach (glob(ADS_IMAGES_PATH ."ad".$id."*.jpg") as $filename)
	{
	   unlink($filename);
	}

	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("adverts.$phpEx") . '">'));

	$message = $lang['delete_confirmation'] . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>" );
	message_die(GENERAL_MESSAGE, $message);
}

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);	
?>