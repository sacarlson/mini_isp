<?php
/***************************************************************************
 *                           auction_constants.php
 *                            -------------------
 *   begin                :   January 2004
 *   copyright            :   (C) FR
 *   email                :   fr@php-styles.com
 *   Last Update          :   DEC 2004 - FR
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This hack is released under the GPL License. 
 *   This hack can be freely used, but not distributed, without permission. 
 *   Intellectual Property is retained by the author listed above. 
 *
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
    die("Hacking attempt");
}

// define images
define('PAYPAL_IMAGE', "http://www.paypal.com/images/lgo/logo3.gif");
define('MONEYBOOKER_IMAGE', "http://www.moneybookers.com/images/banners/88_en_interpayments.gif");

// PATHES
define('AUCTION_PICTURE_UPLOAD_PATH', 'auction/upload/');
define('AUCTION_PICTURE_CACHE_PATH', 'auction/upload/cache/');
define('AUCTION_PICTURE_MINI_PATH', 'auction/upload/mini/');
define('AUCTION_PICTURE_MAIN_PATH', 'auction/upload/main/');
define('AUCTION_PICTURE_WATERMARK_PATH', 'auction/upload/watermark/');
define('AUCTION_PICTURE_MAIN_WATERMARK_PATH', 'auction/upload/main/watermark/');

// Auction status
define('AUCTION_ROOM_UNLOCKED', 0);
define('AUCTION_ROOM_LOCKED', 1);
define('AUCTION_OFFER_UNLOCKED', 0);
define('AUCTION_OFFER_LOCKED', 1);
define('AUCTION_OFFER_SOLD', 1);
define('AUCTION_OFFER_DIRECT_SOLD', 2);

// URL Parameter
define('POST_AUCTION_ROOM_URL', 'ar');
define('POST_AUCTION_CATEGORY_URL', 'ac');
define('POST_AUCTION_OFFER_URL', 'ao');
define('POST_AUCTION_BID_URL', 'ab');
define('POST_AUCTION_URL', 'a');
define('POST_COUPON_URL', 'acoup');
define('POST_IPN_LOG_URL', 'il');

// Account-Actions
define('ACTION_INITIAL', 'INIT');
define('ACTION_PERCENT', 'PERCENT');
define('ACTION_SELLING', 'SELLING');
define('ACTION_CREDIT', 'CREDIT');

// Page numbers for session handling
define('AUCTION_ROOM', 441);
define('AUCTION_OFFER', 442);
define('AUCTION_RATING', 443);
define('AUCTION_FAQ', 446);
define('AUCTION_MYAUCTION', 447);
define('AUCTION_OFFER_VIEW', 448);
define('AUCTION_SITEMAP', 449);
define('AUCTION_PIC_MANAGER', 450);
define('AUCTION_MY_USER_STORE', 451);
define('AUCTION_USER_STORE', 452);

// Tables
define('AUCTION_CATEGORY_TABLE', $table_prefix.'auction_category');
define('AUCTION_ROOM_TABLE', $table_prefix.'auction_room');
define('AUCTION_ROLE_TABLE', $table_prefix.'auction_role');
define('AUCTION_USER_ROLE_TABLE', $table_prefix.'auction_user_role');
define('AUCTION_OFFER_TABLE', $table_prefix.'auction_offer');
define('AUCTION_BID_TABLE', $table_prefix.'auction_bid');
define('AUCTION_CONFIG_TABLE', $table_prefix.'auction_config');
define('AUCTION_USER_RATING_TABLE', $table_prefix.'auction_user_rating');
define('AUCTION_RATING_TABLE', $table_prefix.'auction_rating');
define('AUCTION_WATCHLIST_TABLE', $table_prefix.'auction_watchlist');
define('AUCTION_COUPON_CONFIG_TABLE', $table_prefix.'auction_coupon_config');
define('AUCTION_COUPON_TABLE', $table_prefix.'auction_coupon');
define('AUCTION_IPN_LOG', $table_prefix.'auction_ipn_log');
define('AUCTION_PRUNE_TABLE', $table_prefix.'auction_room_prune');
define('AUCTION_IMAGE_TABLE', $table_prefix.'auction_image');
define('AUCTION_IMAGE_CONFIG_TABLE', $table_prefix.'auction_image_config');
define('AUCTION_USER_STORE_TABLE', $table_prefix.'auction_store');
define('AUCTION_BID_INCREASE_TABLE', $table_prefix.'auction_bid_increase');
define('AUCTION_ACCOUNT_TABLE', $table_prefix.'auction_account');

?>