<?
/***************************************************************************
 *                           ads_comment_edit.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_comment_edit.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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
	message_die(GENERAL_ERROR, 'Could not query comment and ad information', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

if ( empty($row) )
{
	message_die(GENERAL_ERROR, $lang['comment_does_not_exist']);
}

$ad_id = $row['comment_ad_id'];
$comment_user_id = $row['comment_user_id'];
$comment_text = $row['comment_text'];

// ------------------------------------
// Get the number of comments
// ------------------------------------

$sql = "SELECT COUNT(comment_id) AS comments_count
		FROM ". ADS_COMMENTS_TABLE ."
		WHERE comment_ad_id = $ad_id
		GROUP BY comment_ad_id
		LIMIT 0,1";

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query comment information', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

if ( empty($row) )
{
	$total_comments = 0;
}
else
{
	$total_comments = $row['comments_count'];
}

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
$username = $row['username'];
$title = $row['title'];
$short_desc = $row['short_desc'];
$time = $row['time'];
$views = $row['views'];

if ( !empty($user_id) )
{
	// ------------------------------------
	// Read the users table
	// ------------------------------------

	$sql = "SELECT user_id, username 
			FROM ". USERS_TABLE ." 
			WHERE user_id = $user_id";

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query users information', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);

	if ( empty($row) )
	{
		message_die(GENERAL_ERROR, $lang['user_does_not_exist']);
	}

	$username = $row['username'];
}

// ------------------------------------
// Read the images table
// ------------------------------------

$sql = "SELECT * 
		FROM ". ADS_IMAGES_TABLE ."
		WHERE id = $ad_id
		AND img_deleted_ind = 0
		LIMIT 0,1";

$result = $db->sql_query($sql);

if ( $db->sql_numrows($result) > 0 )
{
	while ($row = $db->sql_fetchrow($result)) 
	{
		$img_url = ADS_IMAGES_PATH ."ad".$ad_id."_img".$row["img_seq_no"]."_thumb.jpg";
	}
}		
else
{
	$img_url = $images['noimage'];
} 

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

$cat_edit_level = $row['cat_edit_level'];

// ------------------------------------
// Check the permissions
// ------------------------------------

// Check the permissions
if ( edit_allowed($comment_user_id, $cat_edit_level) == FALSE )
{
	if ( !$userdata['session_logged_in'] ) 
	{
		redirect(append_sid("login.$phpEx?redirect=ads_comment_edit.$phpEx&amp;id=$id"));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}
}
	
// ------------------------------------
// Misc
// ------------------------------------

$comments_per_page = $board_config['posts_per_page'];

/*
+----------------------------------------------------------
| Main work here...
+----------------------------------------------------------
*/

if ( !isset($HTTP_POST_VARS['comment']) )
{
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
               Comments Screen
	   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	if ( ($user_id == ADS_GUEST) or ($username == '') )
	{
		$poster = ($username == '') ? $lang['Guest'] : $username;
	}
	else
	{
		$poster = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $user_id) .'">'. $username .'</a>';
	}

	//
	// Start output of page
	//
	$page_title = $lang['comments'];
	
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'ads_comment_body.tpl'));

	$template->assign_block_vars('switch_comment_post', array());

	$template->assign_vars(array(

		'U_ADS_INDEX' => append_sid("adverts.$phpEx"),
		'U_THUMBNAIL' => append_sid("$img_url"),

		'L_ADS_INDEX' => $lang['ads_index'],
		'L_TITLE' => $lang['title'],
		'L_SHORT_DESC' => $lang['short_desc'],
		'L_POSTER' => $lang['poster'],
		'L_ADVERTISER' => $lang['advertiser'],
		'L_DATE_ADDED' => $lang['date_added'],
		'L_VIEWS' => $lang['views'],
		'L_COMMENTS' => $lang['comments'],

		'L_POST_YOUR_COMMENT' => $lang['Post_your_comment'],
		'L_MESSAGE' => $lang['Message'],
		'L_USERNAME' => $lang['Username'],
		'L_COMMENT_NO_TEXT' => $lang['comment_no_text'],
		'L_COMMENT_TOO_LONG' => $lang['comment_too_long'],
		'L_MAX_LENGTH' => $lang['max_length'],
		'U_ITEM' => append_sid("ads_item.$phpEx?id=$ad_id"),

		'SITE_NAME' => $board_config['sitename'],
		'TITLE' => $title,
		'SHORT_DESC' => nl2br($short_desc),
		'POSTER' => $poster,
		'DATE_ADDED'   => date($lang['DATE_FORMAT'],$time),
		'VIEWS' => $views,
		'TOTAL_COMMENTS' => $total_comments,
		'S_MESSAGE' => $comment_text,

		'S_MAX_LENGTH' => 512,

		'L_SUBMIT' => $lang['Submit'],

		'S_ADS_ACTION' => append_sid("ads_comment_edit.$phpEx?comment_id=$comment_id")));

	//
	// Generate the page
	//
	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}
else
{
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
              Comment Submited
	   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	$comment_text = str_replace("\'", "''", htmlspecialchars(substr(trim($HTTP_POST_VARS['comment']), 0, 512)));

	if ( empty($comment_text) )
	{
		message_die(GENERAL_ERROR, $lang['comment_no_text']);
	}

	// --------------------------------
	// Prepare variables
	// --------------------------------

	$comment_edit_time = time();
	$comment_edit_user_id = $userdata['user_id'];

	// --------------------------------
	// Update the DB
	// --------------------------------

	$sql = "UPDATE ". ADS_COMMENTS_TABLE ."
			SET comment_text = '$comment_text', comment_edit_time = '$comment_edit_time', comment_edit_count = comment_edit_count + 1, comment_edit_user_id = '$comment_edit_user_id'
			WHERE comment_id = '$comment_id'";

	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not update comment data', '', __LINE__, __FILE__, $sql);
	}

	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------

	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("ads_comment.$phpEx?comment_id=$comment_id") . '#'.$comment_id.'">')
	);

	$message = $lang['Stored'] . "<br /><br />" . sprintf($lang['Click_view_message'], "<a href=\"" . append_sid("ads_comment.$phpEx?comment_id=$comment_id") . "#$comment_id\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}

?>