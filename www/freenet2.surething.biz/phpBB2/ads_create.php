<?
/***************************************************************************
 *                              ads_create.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_create.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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
// Mode setting
//
if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = "";
}

//
// Pre-processing
//
if ( $mode == 'calccost' )
{
	// Concatenated category and sub_category must be present
	if ( $HTTP_POST_VARS['cat_sub_cat'] == $lang['please_select'] )
	{
		$message = $lang['calc_cost_error'] . "<br /><br />" . sprintf($lang['click_to_go_back'], "<a href=\"" . append_sid("ads_create.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
		message_die(GENERAL_MESSAGE, $message);
	}

	if ( empty($HTTP_POST_VARS['cat_sub_cat']) )
	{
		message_die(GENERAL_ERROR, $lang['invalid_request']);
	}

	list ($category, $sub_category) = split('>>', $HTTP_POST_VARS['cat_sub_cat']);

	// Sanitize input data
	$category = htmlspecialchars($category);
	$sub_category = htmlspecialchars($sub_category);

	// Extra sanitize for SQL variables
	$category = str_replace("\'", "''", $category);
	$sub_category = str_replace("\'", "''", $sub_category);
}

if ( $mode == 'detail' )
{
	// If coming from PayPal or calccost Category and sub_category must be present in the URL
	if ( empty($HTTP_GET_VARS['category']) or empty($HTTP_GET_VARS['sub_category']) )
	{
		message_die(GENERAL_ERROR, $lang['invalid_request']);
	}

	// Sanitize input data
	$category = htmlspecialchars($HTTP_GET_VARS['category']);	
	$sub_category = htmlspecialchars($HTTP_GET_VARS['sub_category']);	

	// Extra sanitize for SQL variables
	$category = str_replace("\'", "''", $category);
	$sub_category = str_replace("\'", "''", $sub_category);
}

if ( $mode == 'create' )
{
	// Category and sub_category must be sent from the form
	if ( empty($HTTP_POST_VARS['category']) or empty($HTTP_POST_VARS['sub_category']) )
	{
		message_die(GENERAL_ERROR, $lang['invalid_request']);
	}

	// Sanitize input data
	$category = htmlspecialchars($HTTP_POST_VARS['category']);
	$sub_category = htmlspecialchars($HTTP_POST_VARS['sub_category']);

	// Extra sanitize for SQL variables
	$category = str_replace("\'", "''", $category);
	$sub_category = str_replace("\'", "''", $sub_category);
}

if ( $mode == 'calccost' or $mode == 'detail' or $mode == 'create' )
{
	// Read the categories table
	$sql = "SELECT * 
			FROM ". ADS_CATEGORIES_TABLE ." 
			WHERE cat_category = '$category' 
			AND cat_sub_category = '$sub_category'";

	$result = $db->sql_query($sql);
	$category_row = $db->sql_fetchrow($result);

	if ( !$category_row )
	{
		message_die(GENERAL_ERROR, "Error reading categories table", "", __LINE__, __FILE__, $sql);
	}

	$cat_create_level = $category_row['cat_create_level'];

	// Check the permissions
	if ( create_allowed($cat_create_level) == FALSE )
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}
}

//
// Main processing
//
switch($mode)
{
	case 'calccost':

		// Encode the fields
		$u_category = urlencode(stripslashes(htmlspecialchars_decode_php4(str_replace("''", "\'", $category))));
		$u_sub_category = urlencode(stripslashes(htmlspecialchars_decode_php4(str_replace("''", "\'", $sub_category))));

		if ( $ads_config['paid_ads'] == 0 )
		{
			// Set the ad duration and type
			$ad_duration = $ads_config['ad_duration_months'];
			$ad_type = $lang['ad_type_photo'];

			// Redirect to detail page
			redirect(append_sid("ads_create.$phpEx?mode=detail&category=$u_category&sub_category=$u_sub_category&ad_type=$ad_type&ad_duration=$ad_duration"));
		}

		// Check if the ad type or ad duration fields are empty
		if ( empty($HTTP_POST_VARS['ad_type'])
		or  $HTTP_POST_VARS['ad_type'] == $lang['please_select']
		or  !is_numeric($HTTP_POST_VARS['ad_duration']) )
		{
			$message = $lang['calc_cost_error'] . "<br /><br />" . sprintf($lang['click_to_go_back'], "<a href=\"" . append_sid("ads_create.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
			message_die(GENERAL_MESSAGE, $message);
		}

		// Sanitize input data
		$ad_type = htmlspecialchars($HTTP_POST_VARS['ad_type']);
		$ad_duration = htmlspecialchars($HTTP_POST_VARS['ad_duration']);

		// Extra sanitize for SQL variables
		$ad_type = str_replace("\'", "''", $ad_type);
		$ad_duration = str_replace("\'", "''", $ad_duration);

		$page_title = $lang['create_a_new_ad'];

		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array('calculate_cost' => 'ads_calc_cost.tpl')); 

		// Calculate the ad cost
		if ( $ad_type == $lang['ad_type_basic'] )
		{
			$ad_cost = $category_row['cat_basic_cost'];
		}

		if ( $ad_type == $lang['ad_type_standard'] )
		{
			$ad_cost = $category_row['cat_standard_cost'];
		}

		if ( $ad_type == $lang['ad_type_photo'] )
		{
			$ad_cost = $category_row['cat_photo_cost'];
		}

		if ( $ad_type == $lang['ad_type_premium'] )
		{
			$ad_cost = $category_row['cat_premium_cost'];
		}

		// If ad cost < 0 then a 'not available' combination has been selected
		if ( $ad_cost < 0 )
		{
			message_die(GENERAL_ERROR, $lang['ad_type_not_available']);
		}

		$ad_cost = $ad_cost * $ad_duration;

		// Limit the number of free ads for this user (also prevents flooding)
		if ( $userdata['session_logged_in'] and $ad_cost == 0 ) 
		{
			$sql = "SELECT COUNT(*) AS count 
					FROM ". ADS_ADVERTS_TABLE ." 
					WHERE username = '".$userdata['username']."'";

			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);

			if ( $row['count'] >= $ads_config['max_ads_per_user'] )
			{
				message_die(GENERAL_ERROR, $lang['max_ads_exceded']);
			}
		}

		// Is this a free ad?
		if ( $ad_cost == 0 )
		{
			$template->assign_block_vars('switch_free_ad',array());

			$template->assign_block_vars('switch_paypal_divert',array());
		}

		if ( $ad_cost > 0 )
		{
			$template->assign_block_vars('switch_paid_ad',array());

			// Get the row from the users table
			$sql = "SELECT * 
					FROM ". ADS_USERS_TABLE ." 
					WHERE users_user_id = ".$userdata['user_id'];

			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);

			if ( !$row )
			{
				$template->assign_block_vars('switch_paypal',array());

				$credit = 0;
				
				$balance_due = $ad_cost;
			}
			else
			{
				$template->assign_block_vars('switch_balance',array());

				$users_balance = $row['users_balance'];

				if ( $ad_cost > $users_balance )
				{
					$template->assign_block_vars('switch_paypal',array());

					$balance_due = $ad_cost - $users_balance;
				}
				else
				{
					$template->assign_block_vars('switch_paypal_divert',array());

					$balance_due = 0;
				}
			}
		}

		if ( $ads_config['sandbox'] == 0 )
		{
			$paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
		}
		else
		{
			$paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}

		$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';

		$notify_url = $server_protocol . $board_config['server_name'] . $board_config['script_path'] . 'ads_notify.' . $phpEx;
		$return = $server_protocol . $board_config['server_name'] . $board_config['script_path'] . 'ads_create.' . $phpEx. '?mode=detail';
		$cancel_return = $server_protocol . $board_config['server_name'] . $board_config['script_path'] . 'ads_create.' . $phpEx. '?mode=cancel';

		$return = $return.'&category='.$u_category.'&sub_category='.$u_sub_category.
								'&ad_type='.$ad_type.'&ad_duration='.$ad_duration;

		// Set the rest of the template variables
		$template->assign_vars(array(	
		
			'U_ADS_INDEX' => append_sid("adverts.$phpEx"),
			'U_PAYPAL_URL' => append_sid("$paypal_url"),
			'U_IMAGE_URL' => '',										
			'U_NOTIFY_URL' => $notify_url,										
			'U_RETURN' => append_sid("$return"),
			'U_CANCEL_RETURN' => append_sid("$cancel_return"),

			'L_ADS_INDEX' => $lang['ads_index'],
			'L_CREATE_A_NEW_AD' => $lang['create_a_new_ad'],
			'L_CATEGORY_SELECTED' => $lang['category_selected'],
			'L_SUB_CATEGORY_SELECTED' => $lang['sub_category_selected'],
			'L_AD_TYPE_SELECTED' => $lang['ad_type_selected'],
			'L_AD_DURATION_SELECTED' => $lang['ad_duration_selected'],
			'L_MONTHS' => $lang['months'],
			'L_AD_COST' => $lang['ad_cost'],
			'L_ALREADY_PAID' => $lang['already_paid'],
			'L_BALANCE_DUE' => $lang['balance_due'],
			'L_FREE' => $lang['free'],
			'L_PAY_NOW_VIA_PAYPAL' => $lang['pay_now_via_paypal'],
			'L_CONTINUE' => $lang['continue'],
			'L_CURRENCY' => $lang['currency'][$ads_config['currency_code']],

			'SITE_NAME' => $board_config['sitename'],
			'CATEGORY' => $category,										
			'SUB_CATEGORY' => $sub_category,										
			'AD_TYPE' => $ad_type,										
			'AD_DURATION' => $ad_duration,										
			'AD_COST' => $ad_cost,										
			'USERS_BALANCE' => $users_balance,
			'BALANCE_DUE' => $balance_due,
			'BUSINESS' => $ads_config['business_email'],										
			'ITEM_NAME' => $category.' '.$sub_category.' '.$ad_type.' '.$lang['advert'],
			'AMOUNT' => $balance_due,										
			'CURRENCY_CODE' => $ads_config['currency_code'],										
			'CUSTOM' => $userdata['user_id'],										
			'LC' => $ads_config['language_code'],
			'CBT' => $lang['continue']));										
																			
		$template->pparse('calculate_cost'); 

		break;

	case 'detail':

		// Sanitize input data
		$ad_type = htmlspecialchars($HTTP_GET_VARS['ad_type']);
		$ad_duration = htmlspecialchars($HTTP_GET_VARS['ad_duration']);

		// Extra sanitize for SQL variables
		$ad_type = str_replace("\'", "''", $ad_type);
		$ad_duration = str_replace("\'", "''", $ad_duration);

		$page_title = $lang['create_a_new_ad'];

		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array('create_ad' => 'ads_input_detail.tpl')); 

		if ( !$userdata['session_logged_in'] ) 
		{			
			$template->assign_block_vars('not_logged_in',array());
		}

		// Do not allow details to be input for basic ads
		if ( $ad_type != $lang['ad_type_basic'] )
		{			
			$template->assign_block_vars('not_basic_ad',array());

			// Set the custom field template variables
			for ($counter = 1; $counter <= 10; $counter += 1) 
			{
				$field_number = 'field_'.$counter;
				$field_desc = $category_row['cat_field_'.$counter.'_desc'];

				if ( !empty($field_desc) )
				{
					$template->assign_block_vars('custom_field', array(	'FIELD_NUMBER' => $field_number,
																			 				'FIELD_DESC' => $field_desc));
				} 
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

			'S_POST_ACTION' => append_sid("ads_create.$phpEx?mode=create"),
		
			'L_ADS_INDEX' => $lang['ads_index'],
			'L_CREATE_A_NEW_AD' => $lang['create_a_new_ad'],
			'L_SUMMARY' => $lang['summary'],
			'L_USERNAME' => $lang['username'],
			'L_TITLE' => $lang['title'],
			'L_CATEGORY' => $lang['category'],
			'L_SUB_CATEGORY' => $lang['sub_category'],
			'L_SHORT_DESC' => $lang['short_desc'],
			'L_AD_TYPE' => $lang['ad_type'],
			'L_AD_DURATION' => $lang['ad_duration'],
			'L_MONTHS' => $lang['months'],
			'L_PRICE' => $lang['price'],
			'L_PRIVATE_OR_TRADE' => $lang['private_or_trade'],
			'L_PRIVATE' => $lang['private'],
			'L_TRADE' => $lang['trade'],
			'L_ADDITIONAL_INFO' => $lang['additional_info'],
			'L_DETAILS' => $lang['details'],
			'L_CREATE_AD' => $lang['create_ad'],
			'L_PLEASE_SELECT' => $lang['please_select'],

			'SITE_NAME' => $board_config[sitename],
			'CATEGORY' => stripslashes(str_replace("''", "\'", $category)),
			'SUB_CATEGORY' => stripslashes(str_replace("''", "\'", $sub_category)),
			'AD_TYPE' => $ad_type,
			'AD_DURATION' => $ad_duration));

			$template->pparse('create_ad'); 
	
		break;

	case 'create':

		// Checks to see if any of the fields are emty or invalid
		if ( empty($HTTP_POST_VARS['ad_type'])
		or   empty($HTTP_POST_VARS['ad_duration'])
		or   empty($HTTP_POST_VARS['title'])
		or   empty($HTTP_POST_VARS['short_desc'])
		or   empty($HTTP_POST_VARS['price']) )
		{
			$message = $lang['create_instructions'] . "<br /><br />" . sprintf($lang['click_to_go_back'], "<a href=\"javascript:history.go(-1)\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
			message_die(GENERAL_MESSAGE, $message);
		}

		// Checks that either private or trade has been selected
		if ( $ads_config['private_trade_ind'] == 1 )
		{
			if ( !is_numeric($HTTP_POST_VARS['trade_ind']) )
			{
				$message = $lang['private_trade_error'] . "<br /><br />" . sprintf($lang['click_to_go_back'], "<a href=\"javascript:history.go(-1)\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
				message_die(GENERAL_MESSAGE, $message);
			}
		}

		// Sanitize input variables
		$title = htmlspecialchars($HTTP_POST_VARS['title']);
		$ad_type = htmlspecialchars($HTTP_POST_VARS['ad_type']);
		$ad_duration = htmlspecialchars($HTTP_POST_VARS['ad_duration']);
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
		$title = str_replace("\'", "''", $title);
		$ad_type = str_replace("\'", "''", $ad_type);
		$ad_duration = str_replace("\'", "''", $ad_duration);
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

		// Handle BBCode in the additonal_info field
		$additional_info = bbencode_first_pass($additional_info, '');

		// Initialise ad types
		$basic_ad_ind = 0;
		$standard_ad_ind = 0;
		$photo_ad_ind = 0;
		$premium_ad_ind = 0;

		// Set the username
		if ( $userdata['session_logged_in'] ) 
		{
			$user_id = $userdata['user_id'];
			$username = $userdata['username'];
			$username = str_replace("'", "\'", $username);
		}
		else
		{
			$user_id = ANONYMOUS;

			if ( empty($HTTP_POST_VARS['username']) )
			{
				$username = $lang['Guest'];
			}
			else
			{
				$username = htmlspecialchars($HTTP_POST_VARS['username']);
				$username = str_replace("\'", "''", $username);
			}
		}

		// Calculate the ad cost

		// Basic ad
		if ( $ad_type == $lang['ad_type_basic'] )
		{
			$ad_cost = $category_row['cat_basic_cost'];
			$ad_type_code = 1;
			$basic_ad_ind = 1;
		}

		// Standard ad
		if ( $ad_type == $lang['ad_type_standard'] )
		{
			$ad_cost = $category_row['cat_standard_cost'];
			$ad_type_code = 2;
			$standard_ad_ind = 1;
		}

		// Photo ad
		if ( $ad_type == $lang['ad_type_photo'] )
		{
			$ad_cost = $category_row['cat_photo_cost'];
			$ad_type_code = 3;
			$photo_ad_ind = 1;
		}

		// Premium ad
		if ( $ad_type == $lang['ad_type_premium'] )
		{
			$ad_cost = $category_row['cat_premium_cost'];
			$ad_type_code = 4;
			$premium_ad_ind = 1;
		}

		$ad_cost = $ad_cost * $ad_duration;

		// Limit the number of free ads for this user (also prevents flooding)
		if ( $userdata['session_logged_in'] and $ad_cost == 0) 
		{
			$sql = "SELECT COUNT(*) AS count 
					FROM ". ADS_ADVERTS_TABLE ." 
					WHERE username = '".$userdata['username']."'";

			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);

			if ( $row['count'] >= $ads_config['max_ads_per_user'] )
			{
				message_die(GENERAL_ERROR, $lang['max_ads_exceded']);
			}
		}

		// Get the row from the users table
		if ( $ad_cost > 0 )
		{
			$sql = "SELECT * 
					FROM ". ADS_USERS_TABLE ." 
					WHERE users_user_id = ".$userdata['user_id'];

			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);

			if ( !$row )
			{
				message_die(GENERAL_ERROR, $lang['no_credit']);
			}

			// Check for sufficient credit
			if ( $ad_cost > $row['users_balance'] )
			{
				message_die(GENERAL_ERROR, $lang['insuffient_credit']);
			}
		}

		// Create the ad
		$user_ip = $userdata['session_ip'];
		$time = time();
		$expiry_date = mktime(0, 0, 0, date("m") + $ad_duration, date("d"), date("Y"));
		$status = 'active';

		// Insert for the adverts table
		$sql = "INSERT INTO ". ADS_ADVERTS_TABLE ." (category, sub_category, ad_type_code, basic_ad_ind, standard_ad_ind, photo_ad_ind, premium_ad_ind, ad_cost, user_id, username, user_ip, time, title, short_desc, price, status, expiry_date, trade_ind)
				VALUES ('$category', '$sub_category', '$ad_type_code', '$basic_ad_ind', '$standard_ad_ind', '$photo_ad_ind', '$premium_ad_ind', $ad_cost, '$user_id', '$username', '$user_ip', '$time', '$title', '$short_desc', '$price', '$status', '$expiry_date', '$trade_ind')";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not insert adverts row', '', __LINE__, __FILE__, $sql);
		}

		$id = $db->sql_nextid();

		// Insert for the details table
		$sql = "INSERT INTO ". ADS_DETAILS_TABLE ."
				VALUES ($id, '$additional_info', '$field_1', '$field_2', '$field_3', '$field_4',
							'$field_5', '$field_6', '$field_7', '$field_8', '$field_9', '$field_10')";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not insert details row', '', __LINE__, __FILE__, $sql);
		}

		// Insert for the users table
		if ( $ad_cost > 0 )
		{
			$users_balance = $row["users_balance"] - $ad_cost;
			$current_time = time();

			$sql = "UPDATE ". ADS_USERS_TABLE ."
					SET users_balance = $users_balance, users_edit_time = $current_time 
					WHERE users_user_id = ".$userdata['user_id'];

			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update users row', '', __LINE__, __FILE__, $sql);
			}
		}

		// Put out the confirmation message
		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="4;url=' . append_sid("ads_item.$phpEx?id=$id") . '">'));

		if ( $ads_config['images'] == 1 && image_allowed($user_id, $cat_image_level) == TRUE && $ad_type_code > 2 )
		{
			$message = $lang['create_confirmation'] . "<br /><br />" . sprintf($lang['click_to_add_images'], "<a href=\"" . append_sid("ads_images.$phpEx?id=$id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_to_view_ad'], "<a href=\"" . append_sid("ads_item.$phpEx?id=$id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
		}
		else
		{
			$message = $lang['create_confirmation'] . "<br /><br />" . sprintf($lang['click_to_view_ad'], "<a href=\"" . append_sid("ads_item.$phpEx?id=$id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
		}
		
		message_die(GENERAL_MESSAGE, $message);

		break;

	default:

		$page_title = $lang['create_a_new_ad'];

		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array('pre_create' => 'ads_pre_create.tpl')); 

		// Set the category template variables

		$sql = 'SELECT * 
				FROM '. ADS_CATEGORIES_TABLE .'
				ORDER BY cat_category, cat_sub_category ASC';

		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) 
		{
			$option = $row['cat_category'].'>>'.$row['cat_sub_category'];
			$template->assign_block_vars('categorylist', array('OPTION'	=> $option)); 
		}

		if ( $ads_config['paid_ads'] == 1 )
		{
			// Set the paid ads enabled switch
			$template->assign_block_vars('paid_ads_enabled',array());

			// Set the ad type template variables
			if ( $ads_config['basic'] == 1 )
			{	
				$template->assign_block_vars('adtypelist', array('ADTYPE' => $lang['ad_type_basic'])); 
			}

			if ( $ads_config['standard'] == 1 )
			{	
				$template->assign_block_vars('adtypelist', array('ADTYPE' => $lang['ad_type_standard'])); 
			}

			if ( $ads_config['photo'] == 1 )
			{	
				$template->assign_block_vars('adtypelist', array('ADTYPE' => $lang['ad_type_photo'])); 
			}

			if ( $ads_config['premium'] == 1 )
			{	
				$template->assign_block_vars('adtypelist', array('ADTYPE' => $lang['ad_type_premium'])); 
			}
		}
		else
		{
			// Set the paid ads enabled switch
			$template->assign_block_vars('paid_ads_disabled',array());
		}

		// Set the rest of the template variables
		$template->assign_vars(array(	

			'U_ADS_INDEX' => append_sid("adverts.$phpEx"),

			'S_POST_ACTION' => append_sid("ads_create.$phpEx?mode=calccost"),

			'L_ADS_INDEX' => $lang['ads_index'],
			'L_CREATE_A_NEW_AD' => $lang['create_a_new_ad'],
			'L_VIEW_PRICES' => sprintf($lang['view_prices'], "<a href=\"" . append_sid("ads_prices.$phpEx") . "\">", "</a>"),
			'L_PAYPAL_INTRO' => $lang['paypal_intro'],
			'L_CREATE_INTRO' => sprintf($lang['create_intro'], "<a href=\"" . append_sid("ads_multiple.$phpEx") . "\">", "</a>"),
			'L_PLEASE_SELECT' => $lang['please_select'],
			'L_SELECT_CATEGORY' => $lang['select_category'],
			'L_SELECT_AD_TYPE' => $lang['select_ad_type'],
			'L_SELECT_AD_DURATION' => $lang['select_ad_duration'],
			'L_MONTHS' => $lang['months'],
			'L_CALCULATE_COST' => $lang['calculate_cost'],
			'L_CONTINUE' => $lang['continue'],
										
			'SITE_NAME' => $board_config['sitename'],
			'PAYPAL_LOGO_IMG' => $images['paypal_logo']));
											
		$template->pparse('pre_create'); 

		break;
}
	
include($phpbb_root_path . 'includes/page_tail.'.$phpEx);	
?>