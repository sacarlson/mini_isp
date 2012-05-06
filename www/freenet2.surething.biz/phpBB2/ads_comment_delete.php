<?
/***************************************************************************
 *                          ads_comment_delete.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_comment_delete.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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

// ------------------------------------
// Check feature enabled
// ------------------------------------

if ( $ads_config['comment'] == 0 )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}

// ------------------------------------
// Check the request
// ------------------------------------

if ( isset($HTTP_GET_VARS['comment_id']) )
{
	$comment_id = intval($HTTP_GET_VARS['comment_id']);
}
else if ( isset($HTTP_POST_VARS['comment_id']) )
{
	$comment_id = intval($HTTP_POST_VARS['comment_id']);
}
else
{
	message_die(GENERAL_ERROR, $lang['no_comment_id_specified']);
}

// ------------------------------------
// Get the comment info
// ------------------------------------

$sql = "SELECT *
		FROM ". ADS_COMMENTS_TABLE ."
		WHERE comment_id = '$comment_id'";

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query this comment information', '', __LINE__, __FILE__, $sql);
}

$thiscomment = $db->sql_fetchrow($result);

if ( empty($thiscomment) )
{
	message_die(GENERAL_ERROR, $lang['comment_does_not_exist']);
}

// ------------------------------------
// Get the comment info
// ------------------------------------

$sql = "SELECT *
		FROM ". ADS_COMMENTS_TABLE ."
		WHERE comment_id = '$comment_id'";

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query comment and ad information', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

if ( empty($row) )
{
	message_die(GENERAL_ERROR, $lang['comment_does_not_exist']);
}

$ad_id = $row['comment_ad_id'];
$comment_user_id = $row['comment_user_id'];

// ------------------------------------
// Read the adverts table
// ------------------------------------

$sql = "SELECT *
		FROM ". ADS_ADVERTS_TABLE ."
		WHERE id = $ad_id";

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query ad information', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

if ( empty($row) )
{
	message_die(GENERAL_ERROR, $lang['advert_does_not_exist']);
}

$category = $row['category'];
$sub_category = $row['sub_category'];
$user_id = $row['user_id'];
$title = $row['title'];
$short_desc = $row['short_desc'];
$views = $row['views'];

// ------------------------------------
// Read the categories table
// ------------------------------------

$sql = "SELECT *
		FROM ". ADS_CATEGORIES_TABLE ."
		WHERE cat_category = '".addslashes($category)."'
		AND cat_sub_category = '".addslashes($sub_category)."'";

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query category information', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

if ( empty($row) )
{
	message_die(GENERAL_ERROR, $lang['category_does_not_exist']);
}

$cat_delete_level = $row['cat_delete_level'];

// ------------------------------------
// Check the permissions
// ------------------------------------

// Check the permissions
if ( delete_allowed($comment_user_id, $cat_delete_level) == FALSE )
{
	if ( !$userdata['session_logged_in'] ) 
	{
		redirect(append_sid("login.$phpEx?redirect=ads_comment_delete.$phpEx?comment_id=$comment_id"));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}
}

/*
+----------------------------------------------------------
| Main work here...
+----------------------------------------------------------
*/

if ( !isset($HTTP_POST_VARS['confirm']) )
{
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
               Confirm Screen
	   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	// --------------------------------
	// If user give up deleting...
	// --------------------------------
	if ( isset($HTTP_POST_VARS['cancel']) )
	{
		redirect(append_sid("ads_comment.$phpEx?comment_id=$comment_id"));
	}

	//
	// Start output of page
	//
	$page_title = $lang['Album'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'confirm_body.tpl')
	);

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Confirm'],

		'MESSAGE_TEXT' => $lang['comment_delete_confirm'],

		'L_NO' => $lang['No'],
		'L_YES' => $lang['Yes'],

		'S_CONFIRM_ACTION' => append_sid("ads_comment_delete.$phpEx?comment_id=$comment_id"),
		)
	);

	//
	// Generate the page
	//
	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}
else
{
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
              Do the deleting
	   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	$sql = "DELETE
			FROM ". ADS_COMMENTS_TABLE ."
			WHERE comment_id = '$comment_id'";

	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not delete this comment', '', __LINE__, __FILE__, $sql);
	}

	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------

	$message = $lang['Deleted'];

	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("ads_item.$phpEx?id=$ad_id") . '">')
	);

	$message .= "<br /><br />" . sprintf($lang['click_to_view_ad'], "<a href=\"" . append_sid("ads_item.$phpEx?id=$ad_id") . "\">", "</a>");

	$message .= "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}

?>