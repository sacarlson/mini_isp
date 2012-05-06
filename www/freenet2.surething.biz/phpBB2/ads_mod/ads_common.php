<?
/***************************************************************************
 *                              ads_common.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_common.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}

//
// Include Language
//
$language = $board_config['default_lang'];

if ( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_main_ads.'.$phpEx) )
{
	$language = 'english';
}

include($phpbb_root_path . 'language/lang_' . $language . '/lang_main_ads.' . $phpEx);

//
// Get Ads Config
//
$sql = "SELECT *
		FROM ". ADS_CONFIG_TABLE;

if ( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, "Could not query ads config information", "", __LINE__, __FILE__, $sql);
}

while ( $row = $db->sql_fetchrow($result) )
{
	$ads_config_name = $row['config_name'];
	$ads_config_value = $row['config_value'];
	$ads_config[$ads_config_name] = $ads_config_value;
}

//
// Check the version
//

if ( !$ads_config['version'] == '0.5.5' )
{
	message_die(GENERAL_ERROR, "Script and data out of sync. Please run update_to_latest_phpca.php", "", __LINE__, __FILE__);
}

//
// Get Paid Ads Config
//
if ( $ads_config['paid_ads'] == 1 )
{
	$sql = "SELECT *
			FROM ". ADS_PAID_ADS_CONFIG_TABLE;

	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Could not query paid ads config information", "", __LINE__, __FILE__, $sql);
	}

	while ( $row = $db->sql_fetchrow($result) )
	{
		$ads_config_name = $row['config_name'];
		$ads_config_value = $row['config_value'];
		$ads_config[$ads_config_name] = $ads_config_value;
	}
}

include($ads_root_path . 'ads_functions.' . $phpEx);

?>