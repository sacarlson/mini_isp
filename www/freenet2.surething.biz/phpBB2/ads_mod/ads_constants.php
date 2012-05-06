<?
/***************************************************************************
 *                             ads_constants.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_constants.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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

// User Levels for Ads system <- do NOT change these values
define('ADS_ANONYMOUS', -1);
define('ADS_GUEST', -1);

define('ADS_USER', 0);
define('ADS_ADMIN', 1);
define('ADS_MOD', 2);
define('ADS_PRIVATE', 3);

// Path (trailing slash required)
define('ADS_IMAGES_PATH', 'ads_mod/images/');
define('ADS_CHASERS_PATH', 'ads_mod/chasers/');
define('ADS_CHASERS_PATH', 'ads_mod/chasers/');
define('ADS_PAYMENTS_PATH', 'ads_mod/payments/');

// Table names
define('ADS_ADVERTS_TABLE', $table_prefix.'ads_adverts');
define('ADS_CATEGORIES_TABLE', $table_prefix.'ads_categories');
define('ADS_CHASERS_TABLE', $table_prefix.'ads_chasers');
define('ADS_COMMENTS_TABLE', $table_prefix.'ads_comments');
define('ADS_CONFIG_TABLE', $table_prefix.'ads_config');
define('ADS_DETAILS_TABLE', $table_prefix.'ads_details');
define('ADS_IMAGES_TABLE', $table_prefix.'ads_images');
define('ADS_PAID_ADS_CONFIG_TABLE', $table_prefix.'ads_paid_ads_config');
define('ADS_PAYPAL_PAYMENTS', $table_prefix.'ads_paypal_payments');
define('ADS_RATE_TABLE', $table_prefix.'ads_rate');
define('ADS_USERS_TABLE', $table_prefix.'ads_users');
?>