<?
/***************************************************************************
 *                           admin_ads_config.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: admin_ads_config.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Classified_Ads']['Configuration'] = "$file";
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
require($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_ads.' . $phpEx);

//
// Pull all config data
//
$sql = "SELECT *
	FROM " . ADS_CONFIG_TABLE;
	
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query ads config information in admin_board", "", __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = isset($HTTP_POST_VARS['submit']) ? str_replace("'", "\'", $config_value) : $config_value;
		
		$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];

		if( isset($HTTP_POST_VARS['submit']) )
		{
			$sql = "UPDATE " . ADS_CONFIG_TABLE . " SET
				config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
				WHERE config_name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update ads configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}

	if( isset($HTTP_POST_VARS['submit']) )
	{
		$message = $lang['Config_updated'] . "<br /><br />" . sprintf($lang['ads_click_return_config'], "<a href=\"" . append_sid("admin_ads_config.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}

$template->set_filenames(array(
	"body" => "admin/ads_config_body.tpl")
);

if ( file_exists("admin_ads_paid_ads.$phpEx") )
{
	$template->assign_block_vars('paid_ads_installed',array());
}

$template->assign_vars(array(

	"S_CONFIG_ACTION" => append_sid("admin_ads_config.$phpEx"),

	"L_YES" => $lang['Yes'],
	"L_NO" => $lang['No'],

	"L_ADS_CONFIGURATION_TITLE" => $lang['ads_general_config'],
	"L_ADS_CONFIGURATION_EXPLAIN" => $lang['ads_config_explain'],

	"L_ADS_GENERAL_SETTINGS" => $lang['ads_general_settings'],

	'L_VIEW_LEVEL' => $lang['view_level'],
	'L_MOVE_LEVEL' => $lang['move_level'],
	'L_SEARCH_LEVEL' => $lang['search_level'],

	'L_GUEST' => $lang['Forum_ALL'], 
	'L_REG' => $lang['Forum_REG'], 
	'L_MOD' => $lang['Forum_MOD'], 
	'L_ADMIN' => $lang['Forum_ADMIN'],

	"L_ADS_PER_PAGE" => $lang['ads_per_page'],
	"L_AD_DURATION_MONTHS" => $lang['ad_duration_months'],
	"L_MAX_ADS_PER_USER" => $lang['max_ads_per_user'],
	"L_MAX_IMAGES_PER_AD" => $lang['max_images_per_ad'],

	"L_ADS_IMAGE_SETTINGS" => $lang['ads_image_settings'],
	
	"L_ENABLE_IMAGES" => $lang['enable_images'], 
	"L_THUMB_IMG_WIDTH" => $lang['thumb_img_width'], 
	"L_THUMB_IMG_HEIGHT" => $lang['thumb_img_height'], 
	"L_MEDIUM_IMG_WIDTH" => $lang['medium_img_width'], 
	"L_MEDIUM_IMG_HEIGHT" => $lang['medium_img_height'], 
	"L_LARGE_IMG_WIDTH" => $lang['large_img_width'], 
	"L_LARGE_IMG_HEIGHT" => $lang['large_img_height'], 

	"L_ADS_CHASE_SETTINGS" => $lang['ads_chase_settings'],
	
	"L_FIRST_CHASE_DAYS" => $lang['first_chase_days'],
	"L_SECOND_CHASE_DAYS" => $lang['second_chase_days'],

	"L_SUBMIT" => $lang['Submit'], 
	"L_RESET" => $lang['Reset'], 

	'L_EXTRA_SETTINGS' => $lang['extra_settings'],

	'L_PAID_ADS' => $lang['paid_ads'],
	'L_RATE_SYSTEM' => $lang['rate_system'],
	'L_RATE_SCALE' => $lang['rate_scale'],
	'L_COMMENT_SYSTEM' => $lang['comment_system'],
	'L_PRIVATE_TRADE' => $lang['private_trade'],

	'VIEW_GUEST' => ($new['view_level'] == ADS_GUEST) ? 'checked="checked"' : '',
	'VIEW_REG' => ($new['view_level'] == ADS_USER) ? 'checked="checked"' : '',
	'VIEW_MOD' => ($new['view_level'] == ADS_MOD) ? 'checked="checked"' : '',
	'VIEW_ADMIN' => ($new['view_level'] == ADS_ADMIN) ? 'checked="checked"' : '',

	'MOVE_REG' => ($new['move_level'] == ADS_USER) ? 'checked="checked"' : '',
	'MOVE_MOD' => ($new['move_level'] == ADS_MOD) ? 'checked="checked"' : '',
	'MOVE_ADMIN' => ($new['move_level'] == ADS_ADMIN) ? 'checked="checked"' : '',

	'SEARCH_GUEST' => ($new['search_level'] == ADS_GUEST) ? 'checked="checked"' : '',
	'SEARCH_REG' => ($new['search_level'] == ADS_USER) ? 'checked="checked"' : '',
	'SEARCH_MOD' => ($new['search_level'] == ADS_MOD) ? 'checked="checked"' : '',
	'SEARCH_ADMIN' => ($new['search_level'] == ADS_ADMIN) ? 'checked="checked"' : '',

	"ADS_PER_PAGE" => $new['ads_per_page'],
	"AD_DURATION_MONTHS" => $new['ad_duration_months'],
	"MAX_ADS_PER_USER" => $new['max_ads_per_user'],
	"MAX_IMAGES_PER_AD" => $new['max_images_per_ad'],

	'IMAGES_ENABLED' => ($new['images'] == 1) ? 'checked="checked"' : '',
	'IMAGES_DISABLED' => ($new['images'] == 0) ? 'checked="checked"' : '',
		
	"THUMB_IMG_WIDTH" => $new['thumb_img_width'], 
	"THUMB_IMG_HEIGHT" => $new['thumb_img_height'], 
	"MEDIUM_IMG_WIDTH" => $new['medium_img_width'], 
	"MEDIUM_IMG_HEIGHT" => $new['medium_img_height'], 
	"LARGE_IMG_WIDTH" => $new['large_img_width'], 
	"LARGE_IMG_HEIGHT" => $new['large_img_height'], 

	"FIRST_CHASE_DAYS" => $new['first_chase_days'],
	"SECOND_CHASE_DAYS" => $new['second_chase_days'],

	'PAID_ADS_ENABLED' => ($new['paid_ads'] == 1) ? 'checked="checked"' : '',
	'PAID_ADS_DISABLED' => ($new['paid_ads'] == 0) ? 'checked="checked"' : '',
	
	'RATE_ENABLED' => ($new['rate'] == 1) ? 'checked="checked"' : '',
	'RATE_DISABLED' => ($new['rate'] == 0) ? 'checked="checked"' : '',

	'RATE_SCALE' => $new['rate_scale'],

	'COMMENT_ENABLED' => ($new['comment'] == 1) ? 'checked="checked"' : '',
	'COMMENT_DISABLED' => ($new['comment'] == 0) ? 'checked="checked"' : '',

	'PRIVATE_TRADE_ENABLED' => ($new['private_trade_ind'] == 1) ? 'checked="checked"' : '',
	'PRIVATE_TRADE_DISABLED' => ($new['private_trade_ind'] == 0) ? 'checked="checked"' : '')
);

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>