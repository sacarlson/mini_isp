<?
/***************************************************************************
 *                           ads_item_renewal.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_item_renewal.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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
if ( empty($HTTP_GET_VARS['id']) )
{
	message_die(GENERAL_ERROR, $lang['invalid_request']);
}

// Sanitize input variables
$id = intval($HTTP_GET_VARS['id']);

if ( $HTTP_GET_VARS['renewal_password'] ) 
{
	$renewal_password = intval($HTTP_GET_VARS['renewal_password']);
}

// Read the adverts table
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
$expiry_date = $row['expiry_date'];

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

// Get the relevant row from the chasers table
if ( !empty($renewal_password) )
{
	$sql = "SELECT * 
			FROM ". ADS_CHASERS_TABLE ."
			WHERE id = '$id'";

	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);

	if ( !$row )
	{
		message_die(GENERAL_ERROR, $lang['ad_not_found']);
	}
}

// Check the permissions
$renewal_allowed = FALSE;

if ( $renewal_password == $row['renewal_password'] and !empty($renewal_password) )
{
	$renewal_allowed = TRUE;
}

if ( edit_allowed($user_id, $cat_edit_level) == TRUE )
{
	$renewal_allowed = TRUE;
}

if ( $renewal_allowed == FALSE ) 
{
	if ( !$userdata['session_logged_in'] ) 
	{
		redirect(append_sid("login.$phpEx?redirect=ads_item_renewal.$phpEx?id=$id"));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}
}

// Jump to free if not using paid ads
if ( $ads_config['paid_ads'] == 0 )
{
	$mode = 'free';
}

//
// Main processing
//
switch($mode)
{
	default:

		$page_title = $lang['renew_ad'];

		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array('pre_create' => 'ads_pre_renew.tpl')); 

		// Set the rest of the template variables
		$template->assign_vars(array(	

			'U_ADS_INDEX' => append_sid("adverts.$phpEx"),

			'S_POST_ACTION' => append_sid("ads_item_renewal.$phpEx?mode=calccost&id=$id"),

			'L_ADS_INDEX' => $lang['ads_index'],
			'L_RENEW_AD' => $lang['renew_ad'],
			'L_VIEW_PRICES' => sprintf($lang['view_prices'], "<a href=\"" . append_sid("ads_prices.$phpEx") . "\">", "</a>"),
			'L_PAYPAL_INTRO' => $lang['paypal_intro'],
			'L_PLEASE_SELECT' => $lang['please_select'],
			'L_SELECT_ADD_AD_DURATION' => $lang['select_add_ad_duration'],
			'L_MONTHS' => $lang['months'],
			'L_CALCULATE_COST' => $lang['calculate_cost'],
										
			'SITE_NAME' => $board_config['sitename'],
			'PAYPAL_LOGO_IMG' => $images['paypal_logo']));
											
		$template->pparse('pre_create'); 

		break;

	case 'calccost':

		// Check if the ad duration field is empty
		if ( !is_numeric($HTTP_POST_VARS['ad_duration']) )
		{
			$message = $lang['calc_cost_error'] . "<br /><br />" . sprintf($lang['click_to_go_back'], "<a href=\"" . append_sid("ads_create.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
			message_die(GENERAL_MESSAGE, $message);
		}

		// Sanitize input data
		$ad_duration = intval($HTTP_POST_VARS['ad_duration']);

		$page_title = $lang['renew_ad'];

		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array('calculate_cost' => 'ads_calc_renewal_cost.tpl')); 

		// Calculate the ad cost
		if ( $ad_type_code == 1 )
		{
			$ad_cost = $category_row['cat_basic_cost'];
		}

		if ( $ad_type_code == 2 )
		{
			$ad_cost = $category_row['cat_standard_cost'];
		}

		if ( $ad_type_code == 3 )
		{
			$ad_cost = $category_row['cat_photo_cost'];
		}

		if ( $ad_type_code == 4 )
		{
			$ad_cost = $category_row['cat_premium_cost'];
		}

		// If ad cost < 0 then a 'not available' combination has been selected
		if ( $ad_cost < 0 )
		{
			message_die(GENERAL_ERROR, $lang['ad_type_not_available']);
		}

		$ad_cost = $ad_cost * $ad_duration;

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
		$return = $server_protocol . $board_config['server_name'] . $board_config['script_path'] . 'ads_item_renewal.' . $phpEx. '?mode=confirm&id='.$id;
		$cancel_return = $server_protocol . $board_config['server_name'] . $board_config['script_path'] . 'ads_create.' . $phpEx. '?mode=cancel';

		$return = $return.'&ad_duration='.$ad_duration;

		// Set the rest of the template variables
		$template->assign_vars(array(	
		
			'U_ADS_INDEX' => append_sid("adverts.$phpEx"),
			'U_PAYPAL_URL' => append_sid("$paypal_url"),
			'U_IMAGE_URL' => '',										
			'U_NOTIFY_URL' => $notify_url,										
			'U_RETURN' => append_sid("$return"),
			'U_CANCEL_RETURN' => append_sid("$cancel_return"),
	
			'L_ADS_INDEX' => $lang['ads_index'],
			'L_RENEW_AD' => $lang['renew_ad'],
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
			'AD_DURATION' => $ad_duration,										
			'AD_COST' => $ad_cost,										
			'USERS_BALANCE' => $users_balance,
			'BALANCE_DUE' => $balance_due,
			'BUSINESS' => $ads_config['business_email'],										
			'ITEM_NAME' => $category.' '.$sub_category.' '.$ad_type.' Advert',
			'AMOUNT' => $balance_due,										
			'CURRENCY_CODE' => $ads_config['currency_code'],										
			'CUSTOM' => $userdata['user_id'],										
			'LC' => $ads_config['language_code'],
			'CBT' => $lang['continue']));										
																			
		$template->pparse('calculate_cost'); 

		break;

	case 'confirm':

		// Sanitize input data
		$ad_duration = intval($HTTP_GET_VARS['ad_duration']);

		$page_title = $lang['renew_ad'];

		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array('create_ad' => 'ads_renewal_confirm.tpl')); 

		if ( !$userdata['session_logged_in'] ) 
		{			
			$template->assign_block_vars('not_logged_in',array());
		}

		// Set the rest of the template variables
		$template->assign_vars(array(	

			'U_ADS_INDEX' => append_sid("adverts.$phpEx"),

			'S_POST_ACTION' => append_sid("ads_item_renewal.$phpEx?id=$id&mode=update"),
		
			'L_ADS_INDEX' => $lang['ads_index'],
			'L_RENEW_AD' => $lang['renew_ad'],
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

	case 'update':

		// Check if the ad duration field is empty
		if ( !is_numeric($HTTP_POST_VARS['ad_duration']) )
		{
			$message = $lang['calc_cost_error'] . "<br /><br />" . sprintf($lang['click_to_go_back'], "<a href=\"" . append_sid("ads_create.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
			message_die(GENERAL_MESSAGE, $message);
		}

		// Sanitize input data
		$ad_duration = intval($HTTP_POST_VARS['ad_duration']);

		// Calculate the ad cost
		if ( $ad_type_code == 1 )
		{
			$ad_cost = $category_row['cat_basic_cost'];
		}

		if ( $ad_type_code == 2 )
		{
			$ad_cost = $category_row['cat_standard_cost'];
		}

		if ( $ad_type_code == 3 )
		{
			$ad_cost = $category_row['cat_photo_cost'];
		}

		if ( $ad_type_code == 4 )
		{
			$ad_cost = $category_row['cat_premium_cost'];
		}

		// If ad cost < 0 then a 'not available' combination has been selected
		if ( $ad_cost < 0 )
		{
			message_die(GENERAL_ERROR, $lang['ad_type_not_available']);
		}

		$ad_cost = $ad_cost * $ad_duration;

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

		// Add the ad_duration_months to the current date
		$expiry_date = mktime(0, 0, 0, date("m") + $ad_duration, date("d"), date("Y"));

		// Update the adverts table
		$sql = "UPDATE ". ADS_ADVERTS_TABLE ."
				SET status = 'active', expiry_date = '$expiry_date' 
				WHERE id = $id";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, $lang['update_fail'], '', __LINE__, __FILE__, $sql);
		}

		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("ads_item.$phpEx?id=$id") . '">'));

		$message = $lang['renewal_confirmation'] . "<br /><br />" . sprintf($lang['click_to_view_ad'], "<a href=\"" . append_sid("ads_item.$phpEx?id=$id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
		message_die(GENERAL_MESSAGE, $message);

		break;

	case 'free':

		// Add the ad_duration_months to the current date
		$ad_duration = $ads_config['ad_duration_months'];

		$expiry_date = mktime(0, 0, 0, date("m") + $ad_duration, date("d"), date("Y"));

		// Update the adverts table
		$sql = "UPDATE ". ADS_ADVERTS_TABLE ."
				SET status = 'active', expiry_date = '$expiry_date' 
				WHERE id = $id";

		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, $lang['update_fail'], '', __LINE__, __FILE__, $sql);
		}

		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("ads_item.$phpEx?id=$id") . '">'));

		$message = $lang['renewal_confirmation'] . "<br /><br />" . sprintf($lang['click_to_view_ad'], "<a href=\"" . append_sid("ads_item.$phpEx?id=$id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
		message_die(GENERAL_MESSAGE, $message);

		break;
}

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);	
?>