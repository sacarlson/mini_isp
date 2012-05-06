<?
/***************************************************************************
 *                              ads_comment.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_comment.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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
include($phpbb_root_path . 'includes/functions_validate.'.$phpEx);

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

if ( isset($HTTP_GET_VARS['ad_id']) )
{
	$ad_id = intval($HTTP_GET_VARS['ad_id']);
}
else if ( isset($HTTP_POST_VARS['ad_id']) )
{
	$ad_id = intval($HTTP_POST_VARS['ad_id']);
}
else
{
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
		message_die(GENERAL_ERROR, $lang['bad_request']);
	}
}

// ------------------------------------
// Get ad_id from $comment_id
// ------------------------------------

if ( isset($comment_id) )
{
	$sql = "SELECT comment_id, comment_ad_id
			FROM ". ADS_COMMENTS_TABLE ."
			WHERE comment_id = $comment_id";

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
}

// ------------------------------------
// Count the comments
// ------------------------------------

$sql = "SELECT COUNT(comment_id) AS comments_count 
		FROM ". ADS_COMMENTS_TABLE ."
		WHERE comment_ad_id = $ad_id
		GROUP BY comment_ad_id
		LIMIT 0,1";

$result = $db->sql_query($sql);
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

	if( !($result = $db->sql_query($sql)) )
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

$cat_comment_level = $row['cat_comment_level'];
$cat_edit_level = $row['cat_edit_level'];
$cat_delete_level = $row['cat_delete_level'];

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

	// ------------------------------------
	// Get the comments thread
	// Beware: when this script was called with comment_id (without start)
	// ------------------------------------

	if ( !isset($comment_id) )
	{
		if ( isset($HTTP_GET_VARS['start']) )
		{
			$start = intval($HTTP_GET_VARS['start']);
		}
		else if ( isset($HTTP_POST_VARS['start']) )
		{
			$start = intval($HTTP_POST_VARS['start']);
		}
		else
		{
			$start = 0;
		}
	}
	else
	{
		// We must do a query to co-ordinate this comment
		$sql = "SELECT COUNT(comment_id) AS count
				FROM ". ADS_COMMENTS_TABLE ."
				WHERE comment_ad_id = $ad_id
				AND comment_id < $comment_id";

		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain comments information from the database', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);

		if ( !empty($row) )
		{
			$start = floor( $row['count'] / $comments_per_page ) * $comments_per_page;
		}
		else
		{
			$start = 0;
		}
	}

	if ( isset($HTTP_GET_VARS['sort_order']) )
	{
		switch ( $HTTP_GET_VARS['sort_order'] )
		{
			case 'ASC':
				$sort_order = 'ASC';
				break;
			default:
				$sort_order = 'DESC';
		}
	}
	else if ( isset($HTTP_POST_VARS['sort_order']) )
	{
		switch ($HTTP_POST_VARS['sort_order'])
		{
			case 'ASC':
				$sort_order = 'ASC';
				break;
			default:
				$sort_order = 'DESC';
		}
	}
	else
	{
		$sort_order = 'ASC';
	}

	if ( $total_comments > 0 )
	{
		$limit_sql = ($start == 0) ? $comments_per_page : $start .','. $comments_per_page;

		$sql = "SELECT c.*, u.user_id, u.username
				FROM ". ADS_COMMENTS_TABLE ." AS c
				LEFT JOIN ". USERS_TABLE ." AS u ON c.comment_user_id = u.user_id
				WHERE c.comment_ad_id = $ad_id
				ORDER BY c.comment_id $sort_order
				LIMIT $limit_sql";

		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain comments information from the database', '', __LINE__, __FILE__, $sql);
		}

		$commentrow = array();

		while ($row = $db->sql_fetchrow($result))
		{
			$commentrow[] = $row;
		}

		for ($i = 0; $i < count($commentrow); $i++)
		{
			if ( ($commentrow[$i]['user_id'] == ADS_GUEST) 
			or ($commentrow[$i]['username'] == '') )
			{
				$poster = ($commentrow[$i]['comment_username'] == '') ? $lang['Guest'] : $commentrow[$i]['comment_username'];
			}
			else
			{
				$poster = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $commentrow[$i]['user_id']) .'">'. $commentrow[$i]['username'] .'</a>';
			}

			if ( $commentrow[$i]['comment_edit_count'] > 0 )
			{
				$sql = "SELECT c.comment_id, c.comment_edit_user_id, u.user_id, u.username
						FROM ". ADS_COMMENTS_TABLE ." AS c
						LEFT JOIN ". USERS_TABLE ." AS u ON c.comment_edit_user_id = u.user_id
						WHERE c.comment_id = '".$commentrow[$i]['comment_id']."'
						LIMIT 0,1";

				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain last edit information from the database', '', __LINE__, __FILE__, $sql);
				}

				$lastedit_row = $db->sql_fetchrow($result);

				$edit_info = ($commentrow[$i]['comment_edit_count'] == 1) ? $lang['Edited_time_total'] : $lang['Edited_times_total'];

				$edit_info = '<br /><br />&raquo;&nbsp;'. sprintf($edit_info, $lastedit_row['username'], create_date($board_config['default_dateformat'], $commentrow[$i]['comment_edit_time'], $board_config['board_timezone']), $commentrow[$i]['comment_edit_count']) .'<br />';
			}
			else
			{
				$edit_info = '';
			}

			$template->assign_block_vars('commentrow', array(
				'ID' => $commentrow[$i]['comment_id'],
				'POSTER' => $poster,
				'TIME' => create_date($board_config['default_dateformat'], $commentrow[$i]['comment_time'], $board_config['board_timezone']),
				'IP' => ($userdata['user_level'] == ADMIN) ? '-----------------------------------<br />' . $lang['IP_Address'] . ': <a href="http://network-tools.com/default.asp?host=' . decode_ip($commentrow[$i]['comment_user_ip']) . '" target="_blank">' . decode_ip($commentrow[$i]['comment_user_ip']) .'</a><br />' : '',

				'TEXT' => nl2br($commentrow[$i]['comment_text']),
				'EDIT_INFO' => $edit_info,

				'EDIT' => ( edit_allowed($commentrow[$i]['comment_user_id'], $cat_edit_level) == TRUE ) ? '<a href="'. append_sid("ads_comment_edit.$phpEx?comment_id=". $commentrow[$i]['comment_id']) .'">'. $lang['edit'] .'</a>' : '',
				'DELETE' => ( delete_allowed($commentrow[$i]['comment_user_id'], $cat_delete_level) == TRUE ) ? '<a href="'. append_sid("ads_comment_delete.$phpEx?comment_id=". $commentrow[$i]['comment_id']) .'">'. $lang['delete'] .'</a>' : ''));
		}

		$template->assign_block_vars('switch_comment', array());

		$template->assign_vars(array(
			'PAGINATION' => generate_pagination(append_sid("ads_comment.$phpEx?ad_id=$ad_id&amp;sort_order=$sort_order"), $total_comments, $comments_per_page, $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $comments_per_page ) + 1 ), ceil( $total_comments / $comments_per_page ))
			)
		);
	}

	//
	// Start output of page
	//
	$page_title = $lang['comments'];

	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'ads_comment_body.tpl'));

	if ( $user_id == ADS_GUEST or $username == '' )
	{
		$poster = ($username == '') ? $lang['Guest'] : $username;
	}
	else
	{
		$poster = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $user_id) .'">'. $username .'</a>';
	}

	//---------------------------------
	// Comment Posting Form
	//---------------------------------

	if ( comment_allowed($cat_comment_level) == TRUE )
	{
		$template->assign_block_vars('switch_comment_post', array());

		if ( !$userdata['session_logged_in'] )
		{
			$template->assign_block_vars('switch_comment_post.logout', array());
		}
	}

	$template->assign_vars(array(

		'U_ADS_INDEX' => append_sid("adverts.$phpEx"),
		'U_THUMBNAIL' => append_sid("$img_url"),
		'U_ITEM' => append_sid("ads_item.$phpEx?id=$ad_id"),

		'L_ADS_INDEX' => $lang['ads_index'],
		'L_TITLE' => $lang['title'],
		'L_SHORT_DESC' => $lang['short_desc'],
		'L_POSTER' => $lang['poster'],
		'L_ADVERTISER' => $lang['advertiser'],
		'L_DATE_ADDED' => $lang['date_added'],
		'L_VIEWS' => $lang['views'],
		'L_COMMENTS' => $lang['comments'],

		'L_POST_YOUR_COMMENT' => $lang['post_your_comment'],
		'L_MESSAGE' => $lang['Message'],
		'L_USERNAME' => $lang['username'],
		'L_COMMENT_NO_TEXT' => $lang['comment_no_text'],
		'L_COMMENT_TOO_LONG' => $lang['comment_too_long'],
		'L_MAX_LENGTH' => $lang['max_length'],

		'SITE_NAME' => $board_config['sitename'],
		'TITLE' => $title,
		'SHORT_DESC' => nl2br($short_desc),
		'POSTER' => $poster,
		'DATE_ADDED'   => date($lang['DATE_FORMAT'],$time),
		'VIEWS' => $views,
		'TOTAL_COMMENTS' => $total_comments,

		'S_MAX_LENGTH' => 512,

		'L_ORDER' => $lang['Order'],
		'L_SORT' => $lang['Sort'],
		'L_ASC' => $lang['Sort_Ascending'],
		'L_DESC' => $lang['Sort_Descending'],

		'SORT_ASC' => ($sort_order == 'ASC') ? 'selected="selected"' : '',
		'SORT_DESC' => ($sort_order == 'DESC') ? 'selected="selected"' : '',

		'L_SUBMIT' => $lang['Submit'],

		'S_ADS_ACTION' => append_sid("ads_comment.$phpEx?ad_id=$ad_id")
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
              Comment Submited
	   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	// ------------------------------------
	// Check the permissions: COMMENT
	// ------------------------------------

	// Check the permissions
	if ( comment_allowed($cat_comment_level) == FALSE )
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}

	$comment_text = str_replace("\'", "''", htmlspecialchars(substr(trim($HTTP_POST_VARS['comment']), 0, 512)));

	$comment_username = (!$userdata['session_logged_in']) ? str_replace("\'", "''", substr(htmlspecialchars(trim($HTTP_POST_VARS['comment_username'])), 0, 32)) : str_replace("\'", "''", htmlspecialchars(trim($userdata['username'])));

	if ( empty($comment_text) )
	{
		message_die(GENERAL_ERROR, $lang['comment_no_text']);
	}

	// --------------------------------
	// Check username for guest posting
	// --------------------------------

	if ( !$userdata['session_logged_in'] )
	{
		if ( $comment_username != '' )
		{
			$result = validate_username($comment_username);
			if ( $result['error'] )
			{
				message_die(GENERAL_MESSAGE, $result['error_msg']);
			}
		}
	}

	// --------------------------------
	// Prepare variables
	// --------------------------------

	$comment_time = time();
	$comment_user_id = $userdata['user_id'];
	$comment_user_ip = $userdata['session_ip'];

	// --------------------------------
	// Get $comment_id
	// --------------------------------

	$sql = "SELECT MAX(comment_id) AS max
			FROM ". ADS_COMMENTS_TABLE;

	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not find comment_id', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);

	$comment_id = $row['max'] + 1;

	// --------------------------------
	// Insert into DB
	// --------------------------------

	$sql = "INSERT INTO ". ADS_COMMENTS_TABLE ." (comment_id, comment_ad_id, comment_user_id, comment_username, comment_user_ip, comment_time, comment_text)
			VALUES ('$comment_id', '$ad_id', '$comment_user_id', '$comment_username', '$comment_user_ip', '$comment_time', '$comment_text')";

	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not insert new comment', '', __LINE__, __FILE__, $sql);
	}

	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------

	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("ads_comment.$phpEx?comment_id=$comment_id") . '#'.$comment_id.'">'));

	$message = $lang['Stored'] . "<br /><br />" . sprintf($lang['Click_view_message'], "<a href=\"" . append_sid("ads_comment.$phpEx?comment_id=$comment_id") . "#$comment_id\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
	message_die(GENERAL_MESSAGE, $message);
}

?>