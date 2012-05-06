<?
/***************************************************************************
 *                               adverts.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: adverts.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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

// Check the permissions
if ( view_allowed($ads_config['view_level']) == FALSE )
{
	if ( !$userdata['session_logged_in'] ) 
	{
		redirect(append_sid("login.$phpEx?redirect=adverts.$phpEx"));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}
}

// If admin send renewal chasers
if ( $userdata['user_level'] == ADMIN )
{
	$filename = ADS_CHASERS_PATH . date("dMy").'.txt';
	if ( !file_exists($filename) )
	{
		include($ads_root_path . 'ads_renewal_mailer.'.$phpEx);
	}
}

// Sanitize input data
if ( isset($HTTP_GET_VARS['category']) )
{
	$inp_category = htmlspecialchars($HTTP_GET_VARS['category']);
}

if ( isset($HTTP_GET_VARS['sub_category']) )
{
	$inp_sub_category = htmlspecialchars($HTTP_GET_VARS['sub_category']);
}

// Extra sanitize for SQL variables
$inp_category = str_replace("\'", "''", $inp_category);
$inp_sub_category = str_replace("\'", "''", $inp_sub_category);

$current_time = time();

$page_title = $lang['ads_index'];

include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array('adverts_page' => 'adverts.tpl')); 

// SQL for adverts table
$sql = "SELECT category, sub_category, COUNT(sub_category) AS number 
		FROM ". ADS_ADVERTS_TABLE ." 
		WHERE expiry_date > $current_time
		GROUP BY category, sub_category";

$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result)) 
{
	$sub_category_ar[$row['category'].'_'.$row['sub_category']] = $row['number'];
}

$sql = 'SELECT * 
		FROM '. ADS_CATEGORIES_TABLE .'
		ORDER BY cat_category, cat_sub_category ASC';

$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result)) 
{
	$category = $row['cat_category'];
	$sub_category = $row['cat_sub_category'];

	// Encode the fields
	$u_category = urlencode(stripslashes(htmlspecialchars_decode_php4(str_replace("''", "\'", $category))));
	$u_sub_category = urlencode(stripslashes(htmlspecialchars_decode_php4(str_replace("''", "\'", $sub_category))));

	if ( $category != $save_category )
	{
		$category_url = append_sid("adverts.$phpEx?category=$u_category");
		$template->assign_block_vars('categoryrow', array('CATEGORY' => "<b><a href='$category_url'>$category</a></b><br>")); 
		$save_category = $category;
	}

	$sub_category_temp = "<a href='".append_sid("adverts.$phpEx?category=$u_category&sub_category=$u_sub_category")."'>$sub_category</a>"; 

	if ( $sub_category_ar[$save_category.'_'.$sub_category] )
	{
		$sub_category_temp .= ' ('.$sub_category_ar[$save_category.'_'.$sub_category].')';
	}

	$template->assign_block_vars('categoryrow', array('CATEGORY' => $sub_category_temp));
}

// Set the start page
if ( $HTTP_GET_VARS['start'] )
{
	$start = intval($HTTP_GET_VARS['start']);
}
else
{
	$start = 0;
}
	
// Get the total number of ads
if ( $inp_category and $inp_sub_category )
{
	$sql = "SELECT * 
		FROM ". ADS_ADVERTS_TABLE ." 
		WHERE expiry_date > $current_time
		AND category = '$inp_category'
		AND sub_category = '$inp_sub_category'";
}
else if ( $inp_category )
{
	$sql = "SELECT * 
		FROM ". ADS_ADVERTS_TABLE ." 
		WHERE expiry_date > $current_time
		AND category = '$inp_category'";}
else	
{
	$sql = "SELECT * 
		FROM ". ADS_ADVERTS_TABLE ."
		WHERE expiry_date > $current_time";
}

$result = $db->sql_query($sql);
$ads_count = $db->sql_numrows($result);

// Get the ads for this page
if ( $inp_category and $inp_sub_category )
{
	$sql = "SELECT * 
			FROM ". ADS_ADVERTS_TABLE ." 
			WHERE category = '$inp_category' 
			AND sub_category = '$inp_sub_category' 
			AND expiry_date > $current_time
			ORDER BY premium_ad_ind DESC, id DESC
			LIMIT ".$start.", ".$ads_config['ads_per_page'];
}
else
if ( $inp_category )
{
	$sql = "SELECT * 
			FROM ". ADS_ADVERTS_TABLE ." 
			WHERE category = '$inp_category' 
			AND expiry_date > $current_time
			ORDER BY premium_ad_ind DESC, id DESC
			LIMIT ".$start.", ".$ads_config['ads_per_page'];
}
else	
{
	$sql = "SELECT * 
			FROM ". ADS_ADVERTS_TABLE ." 
			WHERE expiry_date > $current_time
			ORDER BY premium_ad_ind DESC, id DESC
			LIMIT ".$start.", ".$ads_config['ads_per_page'];
}

$result = $db->sql_query($sql);

if ( $db->sql_numrows($result) > 0 )
{
	while ($row = $db->sql_fetchrow($result)) 
	{
		$id = $row['id'];
		$user_id = $row['user_id'];

		$profiledata = get_userdata($user_id); 

		if ( $user_id != ANONYMOUS )
		{
			$profile = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id");
		}
		else
		{
			$profile = '';
		}

		if ( $row['status'] == 'sold' )
		{
			$status = $lang['sold'];
		}
		elseif ( $row['status'] == 'expired' )
		{
			$status = $lang['expired'];
		}
		elseif ( $row['status'] == 'active' )
		{
			$status = '';
		}

		if ( $row['ad_type_code'] == '4' )
		{
			$premium_ad = '<b>['.$lang['premium_ad'].']</b><br>';
		}
		else
		{
			$premium_ad = '';
		}

		// Count the comments
		$sql2 = "SELECT COUNT(comment_id) AS comments_count 
		      FROM ". ADS_COMMENTS_TABLE ." 
   		   WHERE comment_ad_id = '$id' 
		      GROUP BY comment_ad_id 
		      LIMIT 1"; 

		$result2 = $db->sql_query($sql2); 
		$row2 = $db->sql_fetchrow($result2); 

		if ( empty($row2) ) 
		{ 
		   $total_comments = 0; 
		} 
		else 
		{ 
		   $total_comments = $row2['comments_count']; 
		}
			
		// Check to see if an image exists for this ad
		if ( $ads_config['images'] == 1 )
		{
			$sql2 = "SELECT * 
					FROM ". ADS_IMAGES_TABLE ."
					WHERE id = '$id' 
					AND img_deleted_ind = 0
					LIMIT 0,1";

			$result2 = $db->sql_query($sql2);

			if ( $db->sql_numrows($result2) > 0 )
			{
				while ($row2 = $db->sql_fetchrow($result2)) 
				{
					$img_url = ADS_IMAGES_PATH ."ad".$id."_img".$row2["img_seq_no"]."_thumb.jpg";
				}
			}		
			else
			{
				$img_url = $images['noimage'];
			} 

			$template->assign_block_vars('imagerow', array(
		
			'U_ADS_ITEM'	=> append_sid("ads_item.$phpEx?id=".$row['id']),
			'U_PROFILE'		=> $profile,

			'USERNAME'		=> $profiledata['username'],
			'TITLE'			=> $row['title'],
			'SHORT_DESC'	=> $premium_ad.nl2br($row['short_desc']),
			'DATE_ADDED'   => date($lang['DATE_FORMAT'],$row['time']),
			'STATUS'			=> $status,
			'PRICE'			=> $row['price'],
			'VIEWS'			=> $row['views'],
			'COMMENTS'		=> $total_comments,
			'IMAGE'			=> $img_url)); 
		}
		else
		{
			$template->assign_block_vars('noimagerow', array(
		
			'U_ADS_ITEM'	=> append_sid("ads_item.$phpEx?id=".$row['id']),
			'U_PROFILE'		=> $profile,

			'USERNAME'		=> $profiledata['username'],
			'TITLE'			=> $premium_ad.$row['title'],
			'SHORT_DESC'	=> nl2br($row['short_desc']),
			'DATE_ADDED'   => date($lang['DATE_FORMAT'],$row['time']),
			'STATUS'			=> $status,
			'PRICE'			=> $row['price'],
			'VIEWS'			=> $row['views'],
			'COMMENTS'		=> $total_comments)); 
		}
	}
}
else
{	
	$template->assign_block_vars('switch_no_items_found',array());
}

if ( $ads_config['images'] == 1 )
{
	$template->assign_block_vars('switch_images_enabled',array());
}

if ( $inp_category )
{
	$pointer= ' -> ';
}

if ( $inp_sub_category )
{
	$pointer2 = ' -> ';
}

if ( $ads_count == 0 )
{
	$ads_count = 1;
}

// Encode the fields
$u_category = urlencode(stripslashes(htmlspecialchars_decode_php4(str_replace("''", "\'", $inp_category))));
$u_sub_category = urlencode(stripslashes(htmlspecialchars_decode_php4(str_replace("''", "\'", $inp_sub_category))));

$goto_string = generate_pagination("adverts.$phpEx?category=$u_category&sub_category=$u_sub_category", $ads_count, $ads_config[ads_per_page], $start);
$page_string = sprintf($lang['Page_of'], ( floor( $start / $ads_config[ads_per_page] ) + 1 ), ceil( $ads_count / $ads_config[ads_per_page] ));

$template->assign_vars(array(	

	'U_ADS_INDEX' => append_sid("adverts.$phpEx"),
	'U_CREATE_AD' => append_sid("ads_create.$phpEx"),
	'U_CATEGORY' => append_sid("adverts.php?category=$u_category"),
	'U_SUB_CATEGORY' => append_sid("adverts.php?category=$u_category&sub_category=$u_sub_category"),
	'U_RSS2' => "ads_rss2.$phpEx",
	
	'L_ADS_INDEX' => $lang['ads_index'],
	'L_CATEGORIES' => $lang['categories'],
	'L_TITLE' => $lang['title'],
	'L_SHORT_DESC' => $lang['short_desc'],
	'L_DATE_ADDED' => $lang['date_added'],
	'L_AD_STATUS' =>  $lang['ad_status'],
	'L_PRICE' => $lang['price'],
	'L_USERNAME' => $lang['username'],
	'L_IMAGE' => $lang['image'],
	'L_STATS' => $lang['stats'],
	'L_VIEWS' => $lang['views'],
	'L_COMMENTS' => $lang['comments'],
	'L_NO_ITEMS_FOUND' => $lang['no_items_found'],

	'SITE_NAME' => $board_config['sitename'],
	'MSG_NEWAD' => $images['msg_newad'],
	'GOTO_STRING' => $goto_string,
	'PAGE_STRING' => $page_string, 
	'POINTER' => $pointer,
	'POINTER2' => $pointer2,
	'CATEGORY' => stripslashes(str_replace("''", "\'", $inp_category)),
	'SUB_CATEGORY' => stripslashes(str_replace("''", "\'", $inp_sub_category))));

$template->pparse('adverts_page'); 

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);	
?>