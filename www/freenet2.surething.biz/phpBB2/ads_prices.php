<?
/***************************************************************************
 *                              ads_prices.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_prices.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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

$page_title = $lang['ad_prices'];

include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array('prices_page' => 'ads_prices.tpl')); 

$sql = "SELECT * 
		FROM ". ADS_CATEGORIES_TABLE;

$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result)) 
{
	$category = $row['cat_category'];
	$sub_category = $row['cat_sub_category'];

	if ( $ads_config['basic'] == 0 || $row['cat_basic_cost'] < 0 )
	{
		$basic_cost = 'Not available';
	}
	else if ( $row['cat_basic_cost'] == 0 )
	{
		$basic_cost = $lang['free'];
	}
	else
	{
		$basic_cost = $lang['currency'][$ads_config['currency_code']] . $row['cat_basic_cost']; 
	}
		
	if ( $ads_config['standard'] == 0 || $row['cat_standard_cost'] < 0 )
	{
		$standard_cost = 'Not available';
	}
	else if ( $row['cat_standard_cost'] == 0 )
	{
		$standard_cost = $lang['free'];
	}	
	else
	{
		$standard_cost = $lang['currency'][$ads_config['currency_code']] . $row['cat_standard_cost'];
	}	
		
	if ( $ads_config['photo'] == 0 || $row['cat_photo_cost'] < 0 )
	{
		$photo_cost = 'Not available';
	}
	else if ( $row['cat_photo_cost'] == 0 )
	{
		$photo_cost = $lang['free'];
	}	
	else
	{
		$photo_cost = $lang['currency'][$ads_config['currency_code']] . $row['cat_photo_cost'];
	}	
		
	if ( $ads_config['premium'] == 0 || $row['cat_premium_cost'] < 0 )
	{
		$premium_cost = 'Not available';
	}
	else if ( $row['cat_premium_cost'] == 0 )
	{
		$premium_cost = $lang['free']; 
	}	
	else
	{
		$premium_cost = $lang['currency'][$ads_config['currency_code']] . $row['cat_premium_cost'];
	}	
		
	$template->assign_block_vars('categoryrow', array(	'CATEGORY' => $category,
																		'SUB_CATEGORY' => $sub_category,
																		'BASIC_COST' => $basic_cost,
																		'STANDARD_COST' => $standard_cost,
																		'PHOTO_COST' => $photo_cost,
																		'PREMIUM_COST' => $premium_cost));
}

$template->assign_vars(array(	

	'U_ADS_INDEX' => append_sid("adverts.$phpEx"),

	'L_ADS_INDEX' => $lang['ads_index'],
	'L_CATEGORY' => $lang['category'],
	'L_SUB_CATEGORY' => $lang['sub_category'],
	'L_AD_TYPE_BASIC' => $lang['ad_type_basic'],
	'L_AD_TYPE_STANDARD' => $lang['ad_type_standard'],
	'L_AD_TYPE_PHOTO' => $lang['ad_type_photo'],
	'L_AD_TYPE_PREMIUM' => $lang['ad_type_premium'],

	'SITE_NAME' => $board_config['sitename']));

$template->pparse('prices_page'); 

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);	
?>