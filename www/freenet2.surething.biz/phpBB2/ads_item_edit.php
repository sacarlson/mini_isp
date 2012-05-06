<?
/***************************************************************************
                               ads_item_edit.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_item_edit.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);

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

$category = $row['category'];
$sub_category = $row['sub_category'];
$ad_type_code = $row['ad_type_code'];
$user_id = $row['user_id'];
$username = $row['username'];
$title = $row['title'];
$short_desc = $row['short_desc'];
$price = $row['price'];

if ( $row['trade_ind'] == 0)
{ 
	$private_checked = 'checked'; 
	$trade_checked = ''; 
}
else
{ 
	$private_checked = ''; 
	$trade_checked = 'checked'; 
}

// Put out the page header
$page_title = $title;

include($phpbb_root_path . 'includes/page_header.'.$phpEx);

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

$cat_edit_level = $category_row['cat_edit_level'];

// Check the permissions
if ( edit_allowed($user_id, $cat_edit_level) == FALSE )
{
	if ( !$userdata['session_logged_in'] ) 
	{
		redirect(append_sid("login.$phpEx?redirect=ads_item_edit.$phpEx&amp;id=$id"));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}
}

//
// Main processing
//
if ( !isset($HTTP_POST_VARS['submit']) )
{
	// Read the details table
	$sql = "SELECT * 
			FROM ". ADS_DETAILS_TABLE ."
			WHERE id = '$id'";

	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	$additional_info = $row['additional_info'];

	// Handle BBCode
	$additional_info = str_replace(':]', ']', $additional_info);

	$template->set_filenames(array('edit_ad' => 'ads_edit.tpl')); 

	// Do not allow details to be input for basic ads
	if ( $ad_type_code != 1 )
	{			
		$template->assign_block_vars('not_basic_ad',array());

		// Set the custom field template variables
		for ($counter = 1; $counter <= 10; $counter += 1) 
		{
			$field_number = 'field_'.$counter;
			$field_val = $row['field_'.$counter];
			$field_desc = $category_row['cat_field_'.$counter.'_desc'];

			if ( !empty($field_desc) )
			{
				$template->assign_block_vars('custom_field', array( 'FIELD_NUMBER' => $field_number,
																					 'FIELD_VAL' => $field_val,
																			 		 'FIELD_DESC' => $field_desc));
			} 
		}
	}

	// Ads mover
	if ( move_allowed($user_id, $ads_config['move_level']) == TRUE ) // check permissions 
	{ 
   	$template->assign_block_vars('move_allowed',array()); 

		// Set the category template variables 
      $sql = 'SELECT * 
            FROM '. ADS_CATEGORIES_TABLE .' 
            ORDER BY cat_category, cat_sub_category ASC'; 

		$result = $db->sql_query($sql); 
		while ($row = $db->sql_fetchrow($result)) 
		{ 
			$option = $row['cat_category'].'>>'.$row['cat_sub_category']; 
			$template->assign_block_vars('categorylist', array('OPTION'   => $option)); 
		} 
	}    

	// Set the private/trade ad switch
	if ( $ads_config['private_trade_ind'] == 1 )
	{
		$template->assign_block_vars('private_trade',array());
	}

	// Set the rest of the template variables
	$template->assign_vars(array(
		
		'U_ADS_INDEX' => append_sid("adverts.$phpEx"),
		'U_ADS_ITEM' => append_sid("ads_item.$phpEx?id=$id"),

		'S_POST_ACTION' => append_sid("ads_item_edit.$phpEx?id=$id"),
				
		'L_ADS_INDEX' => $lang['ads_index'],
		'L_EDIT_AD' => $lang['edit_ad'],
		'L_SUMMARY' => $lang['summary'],
		'L_TITLE' => $lang['title'],
		'L_CATEGORY' => $lang['category'],
		'L_SUB_CATEGORY' => $lang['sub_category'],
      'L_PLEASE_SELECT' => $lang['please_select'], 
      'L_SELECT_CATEGORY' => $lang['select_category'], 
		'L_SHORT_DESC' => $lang['short_desc'],
		'L_PRICE' => $lang['price'],
		'L_PRIVATE_OR_TRADE' => $lang['private_or_trade'],
		'L_PRIVATE' => $lang['private'],
		'L_TRADE' => $lang['trade'],
		'L_ADDITIONAL_INFO' => $lang['additional_info'],
		'L_DETAILS' => $lang['details'],
		'L_SUBMIT' => $lang['submit'],

		'SITE_NAME' => $board_config['sitename'],
		'ID' => $id,
		'CATEGORY' => $category,
		'SUB_CATEGORY' => $sub_category,
		'TITLE' => $title,
		'SHORT_DESC' => $short_desc,
		'PRICE' => $price,
		'PRIVATE_CHECKED' => $private_checked,
		'TRADE_CHECKED' => $trade_checked,
		'ADDITIONAL_INFO' => $additional_info));
																					
	$template->pparse('edit_ad'); 
}
else
{
	//Checks to see if the category, name, message or email fields are empty.
	if ( empty($HTTP_POST_VARS['title'])
	or   empty($HTTP_POST_VARS['short_desc'])
	or   empty($HTTP_POST_VARS['price']) )
	{
		$message = $lang['create_instructions'] . "<br /><br />" . sprintf($lang['click_to_go_back'], "<a href=\"" . append_sid("javascript:history.back()") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>"); 
      message_die(GENERAL_MESSAGE, $message);
	}

	// Checks that either private or trade has been selected
	if ( $ads_config['private_trade_ind'] == 1 )
	{
		if ( !is_numeric($HTTP_POST_VARS['trade_ind']) )
		{
			$message = $lang['private_trade_error'] . "<br /><br />" . sprintf($lang['click_to_go_back'], "<a href=\"" . append_sid("javascript:history.back()") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
			message_die(GENERAL_MESSAGE, $message);
		}
	}

	// Sanitize input variables
	$id = intval($HTTP_GET_VARS['id']);
	$title = htmlspecialchars($HTTP_POST_VARS['title']);
	$short_desc = htmlspecialchars($HTTP_POST_VARS['short_desc']);
	$price = htmlspecialchars($HTTP_POST_VARS['price']);
	$trade_ind = intval($HTTP_POST_VARS['trade_ind']);
	$additional_info = htmlspecialchars($HTTP_POST_VARS['additional_info']);

	$field_1 = htmlspecialchars($HTTP_POST_VARS['field_1']);
	$field_2 = htmlspecialchars($HTTP_POST_VARS['field_2']);
	$field_3 = htmlspecialchars($HTTP_POST_VARS['field_3']);
	$field_4 = htmlspecialchars($HTTP_POST_VARS['field_4']);
	$field_5 = htmlspecialchars($HTTP_POST_VARS['field_5']);
	$field_6 = htmlspecialchars($HTTP_POST_VARS['field_6']);
	$field_7 = htmlspecialchars($HTTP_POST_VARS['field_7']);
	$field_8 = htmlspecialchars($HTTP_POST_VARS['field_8']);
	$field_9 = htmlspecialchars($HTTP_POST_VARS['field_9']);
	$field_10 = htmlspecialchars($HTTP_POST_VARS['field_10']);

	// Extra sanitize for SQL variables
	$id = str_replace("\'", "''", $id);
	$title = str_replace("\'", "''", $title);
	$short_desc = str_replace("\'", "''", $short_desc);
	$price = str_replace("\'", "''", $price);
	$additional_info = str_replace("\'", "''", $additional_info);

	$field_1 = str_replace("\'", "''", $field_1);
	$field_2 = str_replace("\'", "''", $field_2);
	$field_3 = str_replace("\'", "''", $field_3);
	$field_4 = str_replace("\'", "''", $field_4);
	$field_5 = str_replace("\'", "''", $field_5);
	$field_6 = str_replace("\'", "''", $field_6);
	$field_7 = str_replace("\'", "''", $field_7);
	$field_8 = str_replace("\'", "''", $field_8);
	$field_9 = str_replace("\'", "''", $field_9);
	$field_10 = str_replace("\'", "''", $field_10);

	// Handle BBCode
	$additional_info = bbencode_first_pass($additional_info, '');

	// Check that the ad exists
	$sql = "SELECT * 
			FROM ". ADS_ADVERTS_TABLE ." 
			WHERE id = '$id'";

	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	if ( !$row )
	{
		message_die(GENERAL_ERROR, $lang['ad_not_found']);
	}

	// Update the ad
	$user_id = $userdata['user_id'];
	$time = time();

	if ( empty($HTTP_POST_VARS['cat_sub_cat']) 
	or $HTTP_POST_VARS['cat_sub_cat'] == $lang['please_select'] )
	{ 
		$sql = "UPDATE ". ADS_ADVERTS_TABLE ." 
				SET edit_user_id = $user_id, edit_time = $time, edit_count = edit_count + 1, title = '$title', short_desc = '$short_desc', price = '$price', trade_ind = $trade_ind 
				WHERE id = $id"; 
	} 
	else
	{ 
		list ($category, $sub_category) = split('>>', $HTTP_POST_VARS['cat_sub_cat']); 

		// Sanitize input data 
		$category = htmlspecialchars($category); 
		$sub_category = htmlspecialchars($sub_category); 

		// Extra sanitize for SQL variables 
		$category = str_replace("\'", "''", $category); 
		$sub_category = str_replace("\'", "''", $sub_category); 
    
		$sql = "UPDATE ". ADS_ADVERTS_TABLE ." 
				SET edit_user_id = $user_id, edit_time = $time, edit_count = edit_count + 1, title = '$title', short_desc = '$short_desc', price = '$price', 
				category = '$category', sub_category = '$sub_category', trade_ind = '$trade_ind' 
				WHERE id = $id";       
	} 

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, $lang['update_fail'], '', __LINE__, __FILE__, $sql);
	}

	$sql = "UPDATE ". ADS_DETAILS_TABLE ."
			SET additional_info='$additional_info', field_1='$field_1', 
			field_2='$field_2', field_3='$field_3', field_4='$field_4', field_5='$field_5', 
			field_6='$field_6', field_7='$field_7', field_8='$field_8', field_9='$field_9', 
			field_10='$field_10' 
			WHERE id=$id";

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, $lang['update_fail'], '', __LINE__, __FILE__, $sql);
	}

	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("ads_item.$phpEx?id=$id") . '">'));

	if ( $ads_config['images'] == 1 && image_allowed($user_id, $cat_image_level) == TRUE && $ad_type_code > 2 )
	{
		$message = $lang['create_confirmation'] . "<br /><br />" . sprintf($lang['click_to_add_images'], "<a href=\"" . append_sid("ads_images.$phpEx?id=$id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_to_view_ad'], "<a href=\"" . append_sid("ads_item.$phpEx?id=$id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
	}
	else
	{
		$message = $lang['create_confirmation'] . "<br /><br />" . sprintf($lang['click_to_view_ad'], "<a href=\"" . append_sid("ads_item.$phpEx?id=$id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
	}

	message_die(GENERAL_MESSAGE, $message);
}

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);	
?>