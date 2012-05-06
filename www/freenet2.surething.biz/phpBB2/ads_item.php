<?
/***************************************************************************
 *                               ads_item.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_item.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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

// Must alsways have an ad id
if ( empty($HTTP_GET_VARS['id']) )
{
	message_die(GENERAL_ERROR, $lang['invalid_request']);
}

// Sanitize input data
$id = intval($HTTP_GET_VARS['id']);

// Get the row from the adverts table
$sql = "SELECT * 
		FROM ". ADS_ADVERTS_TABLE ." 
		WHERE id = '$id'";
		
$result = $db->sql_query($sql);
$adverts_row = $db->sql_fetchrow($result);

if ( !$adverts_row )
{
	message_die(GENERAL_ERROR, $lang['ad_not_found'], '', __LINE__, __FILE__, $sql);
}

$category = $adverts_row['category'];
$sub_category = $adverts_row['sub_category'];
$ad_type_code = $adverts_row['ad_type_code'];
$user_id = $adverts_row['user_id'];
$username = $adverts_row['username'];
$time = $adverts_row['time'];
$edit_user_id = $adverts_row['edit_user_id'];
$edit_time = $adverts_row['edit_time'];
$edit_count = $adverts_row['edit_count'];
$title = $adverts_row['title'];
$short_desc = $adverts_row['short_desc'];
$price = $adverts_row['price'];
$views = $adverts_row['views'];
$status = $adverts_row['status'];
$expiry_date = $adverts_row['expiry_date'];
$trade_ind = $adverts_row['trade_ind'];

// Update the views counter
$sql = "UPDATE ". ADS_ADVERTS_TABLE ." 
		SET views = views + 1
		WHERE id = $id";

if ( !$db->sql_query($sql) )
{
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, $lang['update_fail'], '', __LINE__, __FILE__, $sql);
	}
}

// Get the row from the categories table
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

$cat_create_level = $category_row['cat_edit_level'];
$cat_edit_level = $category_row['cat_edit_level'];
$cat_delete_level = $category_row['cat_delete_level'];
$cat_image_level = $category_row['cat_image_level'];

// Get the row from the details table

$sql = "SELECT * 
		FROM ". ADS_DETAILS_TABLE ." 
		WHERE id = '$id'";
		
$result = $db->sql_query($sql);
$details_row = $db->sql_fetchrow($result);

if ( !$details_row )
{
	message_die(GENERAL_ERROR, $lang['ad_detail_not_found'], '', __LINE__, __FILE__, $sql);
}

$additional_info = $details_row['additional_info'];

// Get the row from the comments table

$sql = "SELECT COUNT(comment_id) AS comments_count
		FROM ". ADS_COMMENTS_TABLE ."
		WHERE comment_ad_id = $id
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

// Profile / PM / Email

if ( $user_id != ANONYMOUS )
{
	// Get advertisers details from phpBB
	$profiledata = get_userdata($user_id); 

	$temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id");
	$profile_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" border="0" /></a>';
	$profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';

	$temp_url = append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=$user_id");
	$pm_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" border="0" /></a>';
	$pm = '<a href="' . $temp_url . '">' . $lang['Send_private_message'] . '</a>';

	if ( !empty($profiledata['user_viewemail']) || $userdata['user_level'] == ADMIN ) 
	{ 
   	$email_uri = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $user_id) : 'mailto:' . $profiledata['user_email']; 

		$email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" border="0" /></a>'; 
		$email = '<a href="' . $email_uri . '">' . $lang['Send_email'] . '</a>';	
	}
	else
	{
		$email_img = '';
		$email = '';
	}

	$www_img = ( $profiledata['user_website'] ) ? '<a href="' . $profiledata['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" border="0" /></a>' : '';
	$www = ( $profiledata['user_website'] ) ? '<a href="' . $profiledata['user_website'] . '" target="_userwww">' . $lang['Visit_website'] . '</a>' : '';

	if ( !empty($profiledata['user_icq']) )
	{
		$icq_status_img = '<a href="http://wwp.icq.com/' . $profiledata['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $profiledata['user_icq'] . '&img=5" width="18" height="18" border="0" /></a>';
		$icq_img = '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $profiledata['user_icq'] . '"><img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" border="0" /></a>';
		$icq =  '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $profiledata['user_icq'] . '">' . $lang['ICQ'] . '</a>';
	}
	else
	{
		$icq_status_img = '';
		$icq_img = '';
		$icq = '';
	}

	$aim_img = ( $profiledata['user_aim'] ) ? '<a href="aim:goim?screenname=' . $profiledata['user_aim'] . '&amp;message=Hello+Are+you+there?"><img src="' . $images['icon_aim'] . '" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" border="0" /></a>' : '';
	$aim = ( $profiledata['user_aim'] ) ? '<a href="aim:goim?screenname=' . $profiledata['user_aim'] . '&amp;message=Hello+Are+you+there?">' . $lang['AIM'] . '</a>' : '';

	$temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id");
	$msn_img = ( $profiledata['user_msnm'] ) ? '<a href="' . $temp_url . '"><img src="' . $images['icon_msnm'] . '" alt="' . $lang['MSNM'] . '" title="' . $lang['MSNM'] . '" border="0" /></a>' : '';
	$msn = ( $profiledata['user_msnm'] ) ? '<a href="' . $temp_url . '">' . $lang['MSNM'] . '</a>' : '';

	$yim_img = ( $profiledata['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $postrow[$i]['user_yim'] . '&amp;.src=pg"><img src="' . $images['icon_yim'] . '" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" border="0" /></a>' : '';
	$yim = ( $profiledata['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $postrow[$i]['user_yim'] . '&amp;.src=pg">' . $lang['YIM'] . '</a>' : '';
}
else
{
	$profile_img = '';
	$profile = '';
	$pm_img = '';
	$pm = '';
	$email_img = '';
	$email = '';
	$www_img = '';
	$www = '';
	$icq_status_img = '';
	$icq_img = '';
	$icq = '';
	$aim_img = '';
	$aim = '';
	$msn_img = '';
	$msn = '';
	$yim_img = '';
	$yim = '';
}

// Start populating template variables

if ( edit_allowed($user_id, $cat_edit_level) == TRUE )
{
	$template->assign_block_vars('edit_allowed',array());

	if ( time() >= ($expiry_date - ($ads_config['first_chase_days']*60*60*24)) )
	{
		$template->assign_block_vars('switch_renewal_allowed',array());
	}

	if ( $status == 'active' )
	{
		$template->assign_block_vars('switch_active',array());
	}

	if ( $status == 'sold' )
	{
		$template->assign_block_vars('switch_sold',array());
	}
}

if ( delete_allowed($user_id, $cat_delete_level) == TRUE )
{
	$template->assign_block_vars('delete_allowed',array());
}

if ( $ads_config['images'] == 1 && image_allowed($user_id, $cat_image_level) == TRUE && $ad_type_code > 2 )
{
	$template->assign_block_vars('image_allowed',array());
}

if ( $user_id != ANONYMOUS )
{
	$template->assign_block_vars('non_guest_ad',array());
}

if ( $edit_count > 0 )
{
	// Get recipients details from phpBB
	$profiledata = get_userdata($edit_user_id); 

	$edit_details = sprintf($lang['last_edited_by'], $profiledata['username'], date($lang['DATE_FORMAT'],$edit_time), $edit_count);
}

$page_title = $title;

include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array('item_page' => 'ads_item.tpl')); 

// Do not allow details to be input for basic ads
if ( $ad_type_code != 1 )
{			
	$template->assign_block_vars('not_basic_ad',array());

	// Set the custom field template variables
	for ($counter = 1; $counter <= 10; $counter += 1) 
	{
		$field_val = $details_row['field_'.$counter];
		$field_desc = $category_row['cat_field_'.$counter.'_desc'];
	
		if ($field_val != '')
			{$template->assign_block_vars( 'custom_field', array(	'FIELD_VAL' => $field_val,
																		 			'FIELD_DESC' => $field_desc));} 
	}
}

// Get rows from the images table
$sql = "SELECT * 
		FROM ". ADS_IMAGES_TABLE ." 
		WHERE id = '$id'
		AND img_deleted_ind = 0";
		
$result = $db->sql_query($sql);

if ($db->sql_numrows($result)>0)
{
	while ($row = $db->sql_fetchrow($result)) 
	{
		$medium_img_url = ADS_IMAGES_PATH .'ad'.$id.'_img'.$row['img_seq_no'].'_medium.jpg';
		$large_img_url = ADS_IMAGES_PATH .'ad'.$id.'_img'.$row['img_seq_no'].'_large.jpg';
//		$image = popup($medium_img_url, $large_img_url, $title);
		$image =  "<a href='$large_img_url' target='_blank'><img src='$medium_img_url' border='0' alt='$title'></a>";
		$template->assign_block_vars('imagerow', array(	'IMAGE' => $image)); 
	}
	$template->assign_block_vars('switch_images_found',array());
}

// Set the private/trade ad switch
if ( $ads_config['private_trade_ind'] == 1 )
{
	$template->assign_block_vars('private_trade',array());

	if ( $trade_ind == 1 )
	{
		$sale_type = $lang['trade'];
	}
	else
	{
		$sale_type = $lang['private'];
	}
}

// Set the rest of the template variables
$template->assign_vars(array(	

	'U_ADS_INDEX' => append_sid("adverts.$phpEx"),
	'U_ADS_ITEM' => append_sid("ads_item.$phpEx?id=$id"),
	'U_CREATE_AD' => append_sid("ads_create.$phpEx"),
	'U_EDIT_AD' => append_sid("ads_item_edit.$phpEx?id=$id"),
	'U_IMAGES' => append_sid("ads_images.$phpEx?id=$id"),
	'U_DELETE_AD' => append_sid("ads_item_delete.$phpEx?id=$id"),
	'U_RENEW_AD' => append_sid("ads_item_renewal.$phpEx?id=$id"),
	'U_STATUS_TO_SOLD' => append_sid("ads_item_status.$phpEx?id=$id&status=sold"),
	'U_STATUS_TO_SOLD' => append_sid("ads_item_status.$phpEx?id=$id&status=sold"),
	'U_STATUS_TO_ACTIVE' => append_sid("ads_item_status.$phpEx?id=$id&status=active"),
	'U_COMMENTS' => append_sid("ads_comment.$phpEx?ad_id=$id"),
	'U_USER_SEARCH' => append_sid("ads_search.$phpEx?search_name=$username"),

	'L_ADS_INDEX' => $lang['ads_index'],
	'L_ADVERT_DETAILS' => $lang['advert_details'],
	'L_SUMMARY' => $lang['summary'],
	'L_EDIT_AD' => $lang['edit_ad'],
	'L_IMAGES' => $lang['images'],
	'L_DELETE' => $lang['Delete'],
	'L_CATEGORY' => $lang['category'],
	'L_SUB_CATEGORY' => $lang['sub_category'],
	'L_TITLE' => $lang['title'],
	'L_SHORT_DESC' => $lang['short_desc'],
	'L_DATE_ADDED' => $lang['date_added'],
	'L_PRICE' => $lang['price'],
	'L_SALE_TYPE' => $lang['sale_type'],
	'L_ADDITIONAL_INFO' => $lang['additional_info'],
	'L_DETAILS' => $lang['details'],
	'L_ADVERT_INFO' => $lang['advert_info'],
	'L_EXPIRY_DATE' => $lang['expiry_date'],
	'L_AD_STATUS' => $lang['ad_status'],
	'L_VIEWS' => $lang['views'],
	'L_COMMENTS' => $lang['comments'],
	'L_ADVERTISER' => $lang['advertiser'],
	'L_CONTACT' => $lang['contact'],
	'L_IMAGES' => $lang['images'],
	'L_NO_IMAGES_FOUND' => $lang['no_images_found'],
	'L_ALL_SELLERS_ADS' => $lang['all_sellers_ads'],
	'L_CHANGE_STATUS_TO' => $lang['change_status_to'],

	'MSG_NEWAD' => $images['msg_newad'],
	'ICON_EDIT' => $images['icon_edit'],
	'ICON_IMAGES' => $images['icon_images'],
	'ICON_DELPOST' => $images['icon_delpost'],
	'ICON_RENEW' => $images['icon_renew'],
	'ICON_SOLD' => $images['icon_sold'],
	'ICON_ACTIVE' => $images['icon_active'],

	'PROFILE_IMG' => $profile_img,
	'PROFILE' => $profile,
	'PM_IMG' => $pm_img,
	'PM' => $pm,
	'EMAIL_IMG' => $email_img,
	'EMAIL' => $email,
	'WWW_IMG' => $www_img,
	'WWW' => $www,
	'ICQ_STATUS_IMG' => $icq_status_img,
	'ICQ_IMG' => $icq_img,
	'ICQ' => $icq,
	'AIM_IMG' => $aim_img,
	'AIM' => $aim,
	'MSN_IMG' => $msn_img,
	'MSN' => $msn,
	'YIM_IMG' => $yim_img,
	'YIM' => $yim,

	'SITE_NAME' => $board_config['sitename'],
	'CATEGORY' => $category,
	'SUB_CATEGORY' => $sub_category,
	'TITLE' => $title,
	'SHORT_DESC' => nl2br($short_desc),
	'PRICE' => $price,
	'SALE_TYPE' => $sale_type,
	'ADDITIONAL_INFO' => nl2br($additional_info),
	'DATE_ADDED' => date($lang['DATE_FORMAT'],$time),
	'EDIT_DETAILS' => $edit_details,
	'EXPIRY_DATE' => date($lang['DATE_FORMAT'],$expiry_date),
	'STATUS' => $lang[$status],
	'VIEWS' => $views,
	'TOTAL_COMMENTS' => $total_comments,
	'USERNAME' => $username));

$template->pparse('item_page'); 

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);	
?>