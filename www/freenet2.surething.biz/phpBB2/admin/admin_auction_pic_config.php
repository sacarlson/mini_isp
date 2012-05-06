<?php
/***************************************************************************
 *                         admin_auction_pic_config.php
 *                             -------------------
 *   begin                : June 02, 2004
 *   copyright            : (C) 2004 mr.luc
 *   email                : llg@gmx.at
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

/* Powered by Photo Album v2.x.x (c) 2002-2003 Smartor                     */

     define('IN_PHPBB', true);

     if( !empty($setmodules) )
          {
	       $filename = basename(__FILE__);
	       $module['Auction']['a6_picture_Configuration'] = $filename;
	       return;
          }

if (!empty($_SERVER['SERVER_SOFTWARE']) && strstr($_SERVER['SERVER_SOFTWARE'], 'Apache/2'))
{
	header ('Cache-Control: no-cache, pre-check=0, post-check=0');
}
else
{
	header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header ('Expires: 0');
header ('Pragma: no-cache');

     $phpbb_root_path = "./../";
	// print $phpbb_root_path; exit;
     require($phpbb_root_path . 'extension.inc');
     require('./pagestart.' . $phpEx);
     include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);
     include($phpbb_root_path . 'auction/functions_general.php');
     include($phpbb_root_path . 'auction/auction_constants.php');


require($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_auction_pic_admin.' . $phpEx);
// require($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_auction.' . $phpEx);

// check if reset button was activated
// this is not functioning yet
	if( isset($HTTP_GET_VARS['reset']) )
	{
// section 1
		$new['gd_version'] = 3 ;
		$new['auction_offer_pictures_allow'] = 1 ;
		$new['allow_url_upload'] = 0 ;
		$new['auction_offer_picture_jpeg_allow'] = 1 ;
		$new['auction_offer_picture_png_allow'] = 1 ;
		$new['png_convert'] = 0 ;
		$new['auction_offer_picture_gif_allow'] = 0 ;
		$new['gif_convert'] = 0 ;
		$new['auction_offer_picture_size_allow'] = 0 ;
		$new['auction_offer_server_picture_size'] = 0 ;
		$new['auction_offer_thumbnail_cache'] = 0 ;
		$new['auction_offer_pic_max_width'] = 800 ;
		$new['auction_offer_pic_max_height'] = 600 ; 
		$new['offer_auction_pic_quality'] = 80 ;

		$new['auction_offer_main_size'] = 300 ;
		$new['auction_offer_main_quality'] = 80 ;
		$new['main_pic_border'] = 0 ;
		$new['main_pic_border_color'] = '000000' ;
		$new['main_pic_border_width'] = 1 ;
		$new['main_pic_sharpen'] = 0 ;
		$new['main_pic_bw'] = 0 ;
		$new['main_pic_js_bw'] = 0 ;
		$new['auction_offer_mini_size'] = 0 ;
		$new['auction_offer_mini_quality'] = 0 ;
		$new['mini_pic_border'] = 0 ;
		$new['mini_pic_border_color'] = '000000' ;
		$new['mini_pic_border_width'] = 1 ;
		$new['mini_pic_sharpen'] = 1 ;
		$new['mini_pic_bw'] = 0 ;
		$new['allow_thumb_gallery'] = 0 ;
		$new['amount_of_thumbs'] = 0 ;
		$new['amount_of_thumb_per_line'] = 0 ;
		$new['thumb_pic_type'] = 1 ;
		$new['auction_offer_thumb_size'] = 0 ;
		$new['auction_offer_thumb_quality'] = 0 ;
		$new['thumb_pic_border'] = 0 ;
		$new['thumb_pic_border_color'] = '000000' ;
		$new['thumb_pic_border_width'] = 0 ;
		$new['thumb_pic_sharpen'] = 0 ;
		$new['thumb_pic_bw'] = 0 ;
		$new['thumb_pic_js_bw'] = 0 ;
		$new['auction_offer_hotlink_prevent'] = 0 ;
		$new['auction_offer_hotlink_allowed'] = 0 ;
		$new['auction_offer_pic_approval_admin'] = 0 ;
		$new['auction_offer_pic_approval_mod'] = 0 ;
		$new['main_pic_use_water'] = 0 ;
		$new['main_pic_for_all_water'] = 0 ;
		$new['main_pic_for_guest_water'] = 0 ;
		$new['main_water_img_qual'] = 0 ;
		$new['main_watermarkpos'] = 0 ;
		$new['main_water_img_trans'] = 0 ;
		$new['big_pic_use_water'] = 0 ;
		$new['big_pic_for_guest_water'] = 0 ;
		$new['big_water_img_qual'] = 0 ;
		$new['big_watermarkpos'] = 0 ;
		$new['big_water_img_trans'] = 0 ;

	}


// Pull  required data from auction config table (just the 5 configs needed - for compatibility reasons I left them in the old config file!!!!!)
$auction_config_data = init_auction_config();

// check if image was uploaded
if ( $HTTP_POST_FILES['big_wm_picture_file']['size'] > 0 )
{
	$filetype = $HTTP_POST_FILES['big_wm_picture_file']['type'];
	$filesize = $HTTP_POST_FILES['big_wm_picture_file']['size'];
	$filetmp = $HTTP_POST_FILES['big_wm_picture_file']['tmp_name'];

	switch ($filetype)
	{
		case 'image/jpeg':
		case 'image/jpg':
		case 'image/pjpeg':
			$pic2_filetype = '.png';
			$pic2_subtype = 'jpg';
			break;

		case 'image/png':
		case 'image/x-png':
			$pic2_filetype = '.png';
			break;

		case 'image/gif':
			// check if conversion is possible ---> todo
			$pic2_filetype = '.png';
			$pic2_subtype = 'gif';
			break;
		default:
			message_die(GENERAL_ERROR, $lang['auction_offer_picture_filetype_not_allowed']." Error code: 001");
	}

/*	if($gd==0)
	{
		message_die(GENERAL_ERROR, $lang['auction_offer_picture_filetype_not_allowed']." Error code: 002");
	}
*/		
	// --------------------------------
	// Check file size
	// --------------------------------
	$pic2_filename = 'big_watermark.png';
					
	// --------------------------------
	// Move this file to upload directory 'wmk/' . 
	// --------------------------------
	$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

	if ( @$ini_val('open_basedir') != '' )
	{
		if ( @phpversion() < '4.0.3' )
		{
			message_die(GENERAL_ERROR, 'open_basedir is set and your PHP version does not allow move_uploaded_file<br /><br />Please contact your server admin', '', __LINE__, __FILE__);
		}

		$move_file = 'move_uploaded_file';
	}
	else
	{
		$move_file = 'copy';
	}
	// if it is a gif......
	if($pic2_subtype == 'gif')
	{
		include_once($phpbb_root_path . 'auction/graphic_files/phpthumb.gif.php');
		$src = gif_loadFileToGDimageResource($filetmp);

		@imagepng($src,  $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . 'wmk/' . $pic2_filename);
		Imagedestroy($src);
	}
	else if($pic2_subtype == 'jpg')
	{
		$move_file($filetmp,  $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . 'wmk/' . $pic2_filename);
		@chmod( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . 'wmk/' . $pic2_filename, 0777);
		$read_function = 'imagecreatefromjpeg';
		$src = @$read_function( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . 'wmk/' . $pic2_filename);
		@unlink( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . 'wmk/' . $pic2_filename);
		@imagepng($src,  $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . 'wmk/' . $pic2_filename);
		Imagedestroy($src);
	}
	else
	{
		$move_file($filetmp,  $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . 'wmk/' . $pic2_filename);
	}

	@chmod( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . 'wmk/' . $pic_filename, 0777);
}



if ( $HTTP_POST_FILES['auction_offer_picture_file']['size'] > 0 )
{
	$filetype = $HTTP_POST_FILES['auction_offer_picture_file']['type'];
	$filesize = $HTTP_POST_FILES['auction_offer_picture_file']['size'];
	$filetmp = $HTTP_POST_FILES['auction_offer_picture_file']['tmp_name'];

	switch ($filetype)
	{
		case 'image/jpeg':
		case 'image/jpg':
		case 'image/pjpeg':
			$pic_filetype = '.png';
		$pic_subtype = 'jpg';
			break;

		case 'image/png':
		case 'image/x-png':
			$pic_filetype = '.png';
			break;

		case 'image/gif':
			// check if conversion is possible ---> todo
			$pic_filetype = '.png';
			$pic_subtype = 'gif';
			break;
		default:
			message_die(GENERAL_ERROR, $lang['auction_offer_picture_filetype_not_allowed']." Error code: 001");
	}

	$pic_filename = 'main_watermark.png';
	$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

	if ( @$ini_val('open_basedir') != '' )
	{
		if ( @phpversion() < '4.0.3' )
		{
			message_die(GENERAL_ERROR, 'open_basedir is set and your PHP version does not allow move_uploaded_file<br /><br />Please contact your server admin', '', __LINE__, __FILE__);
		}

		$move_file = 'move_uploaded_file';
	}
	else
	{
		$move_file = 'copy';
	}
	// if it is a gif......
	if($pic_subtype == 'gif')
	{
		include_once($phpbb_root_path . 'auction/graphic_files/phpthumb.gif.php');
		$src = gif_loadFileToGDimageResource($filetmp);

		@imagepng($src,  $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . 'wmk/' . $pic_filename);
		Imagedestroy($src);
	}
	else if($pic_subtype == 'jpg')
	{
		$move_file($filetmp,  $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . 'wmk/' . $pic_filename);
		@chmod( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . 'wmk/' . $pic_filename, 0777);
		$read_function = 'imagecreatefromjpeg';
		$src = @$read_function( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . 'wmk/' . $pic_filename);
		@unlink( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . 'wmk/' . $pic_filename);
		@imagepng($src,  $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . 'wmk/' . $pic_filename);
		Imagedestroy($src);
	}
	else
	{
		$move_file($filetmp,  $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . 'wmk/' . $pic_filename);
	}

	@chmod( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  .'wmk/' .  $pic_filename, 0777);
}

 $sql = "SELECT *
         FROM " . AUCTION_CONFIG_TABLE ." ";
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query auction-config information", "", __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = $config_value;
        if( !isset($HTTP_GET_VARS['reset']) )
		{
			$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];
		}
		else
		{
			$HTTP_POST_VARS['submit'] = 1;
			$new[$config_name] = ( isset($new[$config_name]) ) ? $new[$config_name] : $auction_config_data[$config_name] ;
		}
		if(( isset($HTTP_POST_VARS['submit']))OR( isset($HTTP_GET_VARS['reset'])))
		{
			$sql = "UPDATE " . AUCTION_CONFIG_TABLE . " SET config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
				WHERE config_name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update auction-config for $config_name", "", __LINE__, __FILE__, $sql);
			} // if
		}
	} // while
} // if

// Pull all config data from image config table
$sql = "SELECT * FROM " . AUCTION_IMAGE_CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query Auction Pic config information", "", __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = $config_value;
        if( !isset($HTTP_GET_VARS['reset']) )
		{
			$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];
		}
		else
		{

			$new[$config_name] = ( isset($new[$config_name]) ) ? $new[$config_name] : $default_config[$config_name];
		}
		if(( isset($HTTP_POST_VARS['submit']))OR( isset($HTTP_GET_VARS['reset'])))
		{
			$sql = "UPDATE " . AUCTION_IMAGE_CONFIG_TABLE . " SET
				config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
				WHERE config_name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update Album configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}
	
	if( isset($HTTP_POST_VARS['submit']) )
	{
		if(isset($HTTP_POST_VARS['redirector']))
		{
			$redirects = $HTTP_POST_VARS['redirector'];
		} 
		else
		{
			$redirects = '';
		}
		$message = $lang['Auction_pic_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_auction_pic_config'], "<a href=\"" . append_sid("admin_auction_pic_config.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}


/******************************
******* Real Work here ********
*******************************/
// check GD

	$check_gd = (function_exists('imagecopyresampled')) ? 2 : ((function_exists('imagecopyresized'))? 1 : 0   );
	if( $check_gd == 0)
	{
		$gd_notice = $lang['Auction_pic_gd_no'].$lang['Auction_pic_gd_yes2'];
	}
	else
	{
		$gd_notice = $lang['Auction_pic_gd_yes'].$check_gd."</b>".$lang['Auction_pic_gd_yes2'];
	}


/****************************************************
************* check safe mode ***********************
*****************************************************/
// das wird  erst später aktiviert werden..........
/*
if( isset($HTTP_GET_VARS['recheck']) )
{
	$recheck = intval($HTTP_GET_VARS['recheck']);
}
else
{
	$recheck = 0;
}

if(($new['first_run'] == 0) OR ($recheck == 1))
{
	clearstatcache();
	$safe_mode = ini_get("safe_mode");
	
	if (empty($safe_mode) || !strcasecmp($safe_mode, "off") || !strcasecmp($safe_mode, "0") || !strcasecmp($safe_mode, "false")) 
	{
		include("auction_setup_safe_mode.$phpEx");
		// include("auction_setup_normal.$phpEx");
	}	
	else
	{
		include("auction_setup_safe_mode.$phpEx");
	}
	exit;
}


*/



// check writable directories
// first we have to strip the trailing slash... 
// some php versions wont let us create a directory in safe-mode otherwise.

// as some servers store the file_exists information in statcache

// das muss geändert werden!!!
	if($check_gd == 0)
	{
		$write = $exists = true;
		if (file_exists( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH))
		{
			if (!is_writeable( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH))
			{
	
				$write = (@chmod( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH, 0777)) ? true : false;
			
			}
		}
		else
		{
			
			$write = $exists = (@mkdir( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH, 0777)) ? true : false;
			
		}
		if($write AND $exists)
		{
			$directory_write_proceed = "&nbsp;" . $lang['dir_is_ok'];
		}
		else if($exists AND !$write)
		{
			$directory_write_proceed = $lang['The_dir'] . "&nbsp;" . AUCTION_PICTURE_UPLOAD_PATH . "&nbsp;" . $lang['Dir_write_manual'];
		}
		else
		{
			$directory_write_proceed = $lang['The_dir'] . "&nbsp;" . AUCTION_PICTURE_UPLOAD_PATH . "&nbsp;" . $lang['Dir_write_create'];
		}
	}
	else
	{
		$write = $exists = true;

		if (file_exists( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH))
		{
			if (!is_writeable( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH))
			{
				$write = (@chmod( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH, 0777)) ? true : false;
			}
		}
		else
		{
			$write = $exists = (@mkdir( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH, 0777)) ? true : false;
		}

		if($write AND $exists)
		{
			$directory_write_proceed = AUCTION_PICTURE_UPLOAD_PATH . "&nbsp;" . $lang['Dir_write_ok'];
		}
		else if($exists AND !$write)
		{
			$directory_write_proceed = AUCTION_PICTURE_UPLOAD_PATH . "&nbsp;" . $lang['Dir_write_manual'];
		}
		else
		{
			$directory_write_proceed = AUCTION_PICTURE_UPLOAD_PATH . "&nbsp;" . $lang['Dir_write_create'];
		}
	// check cache directories

	$directory_write_proceed .= $lang['separ'];

		$write = $exists = true;
		if (file_exists($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH))
		{
			if (!is_writeable($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH))
			{
				$write = (@chmod($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH, 0777)) ? true : false;
			}
		}
		else
		{
			$write = $exists = (@mkdir($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH, 0777)) ? true : false;
		}
		if($write AND $exists)
		{
			$directory_write_proceed .=  AUCTION_PICTURE_CACHE_PATH . "&nbsp;" . $lang['Dir_write_ok'];
		}
		else if($exists AND !$write)
		{
			$directory_write_proceed .= AUCTION_PICTURE_CACHE_PATH . "&nbsp;" . $lang['Dir_write_manual'];
		}
		else
		{
			$directory_write_proceed .= AUCTION_PICTURE_CACHE_PATH . "&nbsp;" . $lang['Dir_write_create'];
		}
	// check cache directories
	$directory_write_proceed .= $lang['separ'];

		$write = $exists = true;
		if (file_exists($phpbb_root_path . AUCTION_PICTURE_MAIN_PATH))
		{
			if (!is_writeable($phpbb_root_path . AUCTION_PICTURE_MAIN_PATH))
			{
				$write = (@chmod($phpbb_root_path . AUCTION_PICTURE_MAIN_PATH, 0777)) ? true : false;
			}
		}
		else
		{
			$write = $exists = (@mkdir($phpbb_root_path . AUCTION_PICTURE_MAIN_PATH, 0777)) ? true : false;
		}
		if($write AND $exists)
		{
			$directory_write_proceed .= AUCTION_PICTURE_MAIN_PATH . "&nbsp;" . $lang['Dir_write_ok'];
		}
		else if($exists AND !$write)
		{
			$directory_write_proceed .= AUCTION_PICTURE_MAIN_PATH . "&nbsp;" . $lang['Dir_write_manual'];
		}
		else
		{
			$directory_write_proceed .= AUCTION_PICTURE_MAIN_PATH . "&nbsp;" . $lang['Dir_write_create'];
		}
	// check cache directories
	$directory_write_proceed .= $lang['separ'];
		$write = $exists = true;
		if (file_exists($phpbb_root_path . AUCTION_PICTURE_MAIN_WATERMARK_PATH))
		{
			if (!is_writeable($phpbb_root_path . AUCTION_PICTURE_MAIN_WATERMARK_PATH))
			{
				$write = (@chmod($phpbb_root_path . AUCTION_PICTURE_MAIN_WATERMARK_PATH, 0777)) ? true : false;
			}
		}
		else
		{
			$write = $exists = (@mkdir($phpbb_root_path . AUCTION_PICTURE_MAIN_WATERMARK_PATH, 0777)) ? true : false;
		}
		if($write AND $exists)
		{
			$directory_write_proceed .= AUCTION_PICTURE_MAIN_WATERMARK_PATH . "&nbsp;" . $lang['Dir_write_ok'];
		}
		else if($exists AND !$write)
		{
			$directory_write_proceed .= AUCTION_PICTURE_MAIN_WATERMARK_PATH . "&nbsp;" . $lang['Dir_write_manual'];
		}
		else
		{
			$directory_write_proceed .= AUCTION_PICTURE_MAIN_WATERMARK_PATH . "&nbsp;" . $lang['Dir_write_create'];
		}
	// check cache directories
	$directory_write_proceed .= $lang['separ'];

		$write = $exists = true;
		if (file_exists($phpbb_root_path . AUCTION_PICTURE_MINI_PATH))
		{
			if (!is_writeable($phpbb_root_path . AUCTION_PICTURE_MINI_PATH))
			{
				$write = (@chmod($phpbb_root_path . AUCTION_PICTURE_MINI_PATH, 0777)) ? true : false;
			}
		}
		else
		{
			$write = $exists = (@mkdir($phpbb_root_path . AUCTION_PICTURE_MINI_PATH, 0777)) ? true : false;
		}
		if($write AND $exists)
		{
			$directory_write_proceed .= AUCTION_PICTURE_MINI_PATH . "&nbsp;" . $lang['Dir_write_ok'];
		}
		else if($exists AND !$write)
		{
			$directory_write_proceed .= AUCTION_PICTURE_MINI_PATH . "&nbsp;" . $lang['Dir_write_manual'];
		}
		else
		{
			$directory_write_proceed .= AUCTION_PICTURE_MINI_PATH . "&nbsp;" . $lang['Dir_write_create'];
		}
	// check cache directories
	$directory_write_proceed .= $lang['separ'];
		if (file_exists( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . "tmp/"))
		{
			if (!is_writeable( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . "tmp/"))
			{
				@chmod( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . "tmp", 0777);
			}
		}
		else
		{
			@mkdir( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . "tmp", 0777);
			@chmod( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . "tmp", 0777);
		}
		if((is_writeable( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . "tmp/")) AND (file_exists( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . "tmp/")))
		{
			$directory_write_proceed .= AUCTION_PICTURE_UPLOAD_PATH . "tmp/" . "&nbsp;" . $lang['Dir_write_ok'];
		}
		else if((file_exists( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . "tmp/")) AND (is_writeable( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . "tmp/")))
		{
			$directory_write_proceed .= AUCTION_PICTURE_UPLOAD_PATH . "tmp/" . "&nbsp;" . $lang['Dir_write_manual'];
		}
		else
		{
			$directory_write_proceed .=AUCTION_PICTURE_UPLOAD_PATH . "tmp/" . "&nbsp;" . $lang['Dir_write_create'];
		}
	// check cache directories
	
	$directory_write_proceed .= $lang['separ'];

		$write = $exists = true;
		
		if (file_exists($phpbb_root_path .  AUCTION_PICTURE_UPLOAD_PATH . 'wmk/'))
		{
			
			if (!is_writeable($phpbb_root_path .  AUCTION_PICTURE_UPLOAD_PATH . "wmk"))
			{
				@chmod($phpbb_root_path .  AUCTION_PICTURE_UPLOAD_PATH . "wmk", 0777);
			}
		}
		else
		{
			@mkdir($phpbb_root_path .  AUCTION_PICTURE_UPLOAD_PATH . "wmk", 0777);
			@chmod($phpbb_root_path .  AUCTION_PICTURE_UPLOAD_PATH . "wmk", 0777); 
		}
		if((is_writeable($phpbb_root_path .  AUCTION_PICTURE_UPLOAD_PATH . "wmk")) AND (file_exists($phpbb_root_path .  AUCTION_PICTURE_UPLOAD_PATH . 'wmk/')))
		{
			$directory_write_proceed .=  AUCTION_PICTURE_UPLOAD_PATH . "wmk/" . "&nbsp;" . $lang['Dir_write_ok'];
		}
		else if((file_exists($phpbb_root_path .  AUCTION_PICTURE_UPLOAD_PATH . 'wmk/')) AND (!is_writeable($phpbb_root_path .  AUCTION_PICTURE_UPLOAD_PATH . "wmk")))
		{
			$directory_write_proceed .=  AUCTION_PICTURE_UPLOAD_PATH . "wmk/" . "&nbsp;" . $lang['Dir_write_manual'];
		}
		else
		{
			$directory_write_proceed .=  AUCTION_PICTURE_UPLOAD_PATH . "wmk/" . "&nbsp;" . $lang['Dir_write_create'];
		}
// check cache directories

	$directory_write_proceed .= $lang['separ'];

		$write = $exists = true;
		if (file_exists($phpbb_root_path . AUCTION_PICTURE_WATERMARK_PATH))
		{
			if (!is_writeable($phpbb_root_path . AUCTION_PICTURE_WATERMARK_PATH))
			{
				$write = (@chmod($phpbb_root_path . AUCTION_PICTURE_WATERMARK_PATH, 0777)) ? true : false;
			}
		}
		else
		{
			$write = $exists = (@mkdir($phpbb_root_path . AUCTION_PICTURE_WATERMARK_PATH, 0777)) ? true : false;
		}
		if($write AND $exists)
		{
			$directory_write_proceed .= AUCTION_PICTURE_WATERMARK_PATH . "&nbsp;" . $lang['Dir_write_ok'];
		}
		else if($exists AND !$write)
		{
			$directory_write_proceed .= AUCTION_PICTURE_WATERMARK_PATH . "&nbsp;" . $lang['Dir_write_manual'];
		}
		else
		{
			$directory_write_proceed .= AUCTION_PICTURE_WATERMARK_PATH . "&nbsp;" . $lang['Dir_write_create'];
		}
	}
	// check thumbnail amounts


if( ($new['amount_of_thumb_per_line'] > 5) OR ($new['amount_of_thumb_per_line'] < 3))
{
	$new['amount_of_thumb_per_line'] = 5;
}


// check main pic water


// check if watermark-file exists.
// main picture
$main_watermark_file = 'main_watermark.png';
$big_watermark_file = "big_watermark.png";
$dummy_file = "wmk_test.jpg";
$big_dummy_file = "wmk_test_2.jpg";
$wmm_pic = 1;
$wmt_pic = 1;


if(!file_exists($phpbb_root_path .  AUCTION_PICTURE_UPLOAD_PATH . 'wmk/'. $main_watermark_file))
{
	
	$u_main_pic_current_water = $lang['no_watermark'];
	$wmm_pic = 0;
}
else
{
	
	if((!is_writeable( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . "wmk/" .  $main_watermark_file)) OR (!is_writeable( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . "wmk/")))
	{
		$u_main_pic_current_water = '<font color=red>YOUR WATERMARK DIRECTORY<br />or<br />YOUR WATERMARK FILE<br /> are NOT WRITEABLE!!!</font>check:<br />' .  AUCTION_PICTURE_UPLOAD_PATH . "wmk/" .'for permissions! (chmod 777)';
	}
	else
	{
		$u_main_pic_current_water = '<img src="'. $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . "wmk/" .  $main_watermark_file . '" border=0 />';
	}
}

if(!file_exists( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . "wmk/" .  $big_watermark_file))
{
	$u_big_pic_current_water = $lang['no_watermark'];
	$wmm_pic = 0;
}
else
{
	if((!is_writeable( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . "wmk/" .  $main_watermark_file)) OR(!is_writeable( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . "wmk/")))
	{
		$u_main_pic_current_water = '<font color=red>YOUR WATERMARK DIRECTORY<br />or<br />YOUR WATERMARK FILE<br /> are NOT WRITEABLE!!!</font><br />check:<br />' .  AUCTION_PICTURE_UPLOAD_PATH . "wmk/" .'for permissions! (chmod 777)';
	}
	else
	{
		$u_big_pic_current_water = '<img src="'. $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . "wmk/" .  $big_watermark_file.'" border=0 />';
	}
}



if(!file_exists( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . "wmk/" .  $dummy_file))
{
	if(!file_exists( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . "wmk/" .  $main_watermark_file))
	{
		$u_main_test_pic_water =  $lang['no_test_pic_no_upload'];
		$wmm_pic = 0;
	}
	else
	{
		$u_main_test_pic_water = $lang['no_test_pic'];
		$wmt_pic = 0;
	}
}
else
{

	if(!file_exists( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . "wmk/" .  $main_watermark_file))
	{
		$u_main_test_pic_water =  $lang['no_wm_upload'];
		$wmm_pic = 0;
	}
	else
	{
		$pic_size = @getimagesize( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . "wmk/" . $dummy_file);
		$pic_pop_width = $pic_size[0];
		$pic_pop_height = $pic_size[1];
		$picpopfile = 0;
		$u_main_test_pic_water = "<a href=\"#\" onclick=\"openpop('$pic_pop_width','$pic_pop_height','$picpopfile');return false;\" target=\"_blank\">" . $lang['wmk_test_for_main'] . "</a>" ;
	}
}

if(!file_exists( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . "wmk/" .  $big_dummy_file))
{
	if(!file_exists( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . "wmk/" .  $big_watermark_file))
	{
		$u_big_test_pic_water =  $lang['no_test_pic_no_upload'];
		$wmm_pic = 0;
	}
	else
	{
		$u_big_test_pic_water = $lang['no_test_pic'];
		$wmt_pic = 0;
	}
}
else
{
	if(!file_exists( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . "wmk/" .  $big_watermark_file))
	{
		$u_big_test_pic_water =  $lang['no_wm_upload'];
		$wmm_pic = 0;
	}
	else
	{
        	$picpopfile = 1;
		$pic_size = @getimagesize( $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . "wmk/" .  $big_dummy_file);
		$pic_pop_width2 = $pic_size[0];
		$pic_pop_height2 = $pic_size[1];
		$u_big_test_pic_water = "<a href=\"#\" onclick=\"openpop('$pic_pop_width2','$pic_pop_height2','$picpopfile');return false;\" target=\"_blank\">" . $lang['wmk_test_for_big'] . "</a>" ;
	}
}

// main watermark position

$reset_and_auto_link = '</p><p align=center><a   href="'.append_sid('admin_auction_pic_config.'.$phpEx.'?_reset=1').'"><b>'.$lang['reset_to_defaults'].'</b></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a   href="'.append_sid('admin_auction_pic_config.'.$phpEx.'?_auto=1').'"><b>'.$lang['Auto_config'].'</b></a></p>';


// jumper
if(isset($HTTP_GET_VARS['jump']))
$jumper = $HTTP_GET_VARS['jump']; 

$template->set_filenames(array(
	"body" => "admin/admin_auction_pic_config_body.tpl")
);

$template->assign_vars(array(
	'L_AUCTION_PIC_CONFIG' => $lang['Auction_pic_config'],
	'L_AUCTION_PIC_CONFIG_EXPLAIN' => $lang['Auction_pic_config_explain'],
	'RESET_TO_DEFAULTS' => $reset_and_auto_link,
	'REDIRECTOR' => $jumper,
	'S_AUCTION_PIC_CONFIG_ACTION' => append_sid('admin_auction_pic_config.'.$phpEx),
	'L_BASE_PIC_CONFIG' => $lang['base_pic_config'],
	'L_GD_NOTICE' => $gd_notice,
	'L_MANUAL_THUMBNAIL' => $lang['Manual_thumbnail'],
	'L_AUTO_GD' => $lang['Auto_GD'],
	'NO_GD' => ($new['gd_version'] == 0) ? 'checked="checked"' : '',
	'GD_V1' => ($new['gd_version'] == 1) ? 'checked="checked"' : '',
	'GD_V2' => ($new['gd_version'] == 2) ? 'checked="checked"' : '',
	'GD_V3' => ($new['gd_version'] == 3) ? 'checked="checked"' : '',
	'L_GD_VERSION' => $lang['GD_version'],
	'WORKS_WITH_ALL' => $lang['works_with_all'],
	'WORKS_WITH_GD1' => $lang['works_with_gd1'],
	'WORKS_WITH_GD2' => $lang['works_with_gd2'],
	'L_DIRECTORY_WRITE' => $lang['Dir_write'],
	'DIRECTORY_WRITE_PROCEED' => $directory_write_proceed,
	'L_AUCTION_PICTURE_ALLOW' => $lang['auction_offer_picture_allow'],
	'AUCTION_OFFER_PICTURE_ALLOW_YES' => ( $new['auction_offer_pictures_allow'] == 1) ? 'checked="checked"' : '',
	'AUCTION_OFFER_PICTURE_ALLOW_NO' => ( $new['auction_offer_pictures_allow'] == 0) ? 'checked="checked"' : '',	
	'L_URL_UPLOAD_ALLOW' => $lang['allow_url_upload'],
	'URL_UPLOAD_ENABLED' => ($new['allow_url_upload'] == 1) ? 'checked="checked"' : '',
	'URL_UPLOAD_DISABLED' => ($new['allow_url_upload'] == 0) ? 'checked="checked"' : '',
	'L_AUCTION_PICTURE_JPEG_ALLOW' => $lang['auction_offer_picture_jpeg_allow'],
	'AUCTION_OFFER_PICTURE_JPEG_ALLOW_YES' => ($new['auction_offer_picture_jpeg_allow'] == 1) ? 'checked="checked"' : '',
	'AUCTION_OFFER_PICTURE_JPEG_ALLOW_NO' => ($new['auction_offer_picture_jpeg_allow'] == 0) ? 'checked="checked"' : '',
	'L_AUCTION_PICTURE_PNG_ALLOW' => $lang['auction_offer_picture_png_allow'],
	'AUCTION_OFFER_PICTURE_PNG_ALLOW_YES' => ( $new['auction_offer_picture_png_allow'] == 1) ? 'checked="checked"' : '',
	'AUCTION_OFFER_PICTURE_PNG_ALLOW_NO' => ( $new['auction_offer_picture_png_allow'] == 0) ? 'checked="checked"' : '',
	'L_PNG_CONVERT' => $lang['png_convert'],
	'PNG_CONVERT_ENABLED' => ( $new['png_convert'] == 1) ? 'checked="checked"' : '',
	'PNG_CONVERT_DISABLED' => ( $new['png_convert'] == 0) ? 'checked="checked"' : '',
	'L_AUCTION_PICTURE_GIF_ALLOW' => $lang['auction_offer_picture_gif_allow'],
	'AUCTION_OFFER_PICTURE_GIF_ALLOW_YES' => ($new['auction_offer_picture_gif_allow'] == 1 ) ? 'checked="checked"' : '',
	'AUCTION_OFFER_PICTURE_GIF_ALLOW_NO' => ($new['auction_offer_picture_gif_allow'] == 0 ) ? 'checked="checked"' : '',
	'L_GIF_CONVERT' => $lang['gif_convert_expl'],
	'GIF_CONVERT_ENABLED' => ($new['gif_convert'] == 1) ? 'checked="checked"' : '',
	'GIF_CONVERT_DISABLED' => ($new['gif_convert'] == 0) ? 'checked="checked"' : '',
	'L_AUCTION_GIF_SIZE_ALLOW' => $lang['auction_offer_gif_size_allow'],
	'AUCTION_GIF_SIZE_ALLOW' => $new['auction_gif_max_size'],
	'L_AUCTION_PICTURE_SIZE_ALLOW' => $lang['auction_offer_picture_size_allow'],
	'AUCTION_OFFER_PICTURE_SIZE_ALLOW' => $new['auction_offer_picture_size_allow'],
	'L_AUCTION_SERVER_PICTURE_SIZE_ALLOW' => $lang['server_image_limit'],
	'AUCTION_OFFER_SERVER_PICTURE_SIZE_ALLOW' => $new['auction_offer_server_picture_size'],
	'L_THUMBNAIL_CACHE' => $lang['Thumbnail_cache'],
	'THUMBNAIL_CACHE_ENABLED' => ($new['auction_offer_thumbnail_cache'] == 1) ? 'checked="checked"' : '',
	'THUMBNAIL_CACHE_DISABLED' => ($new['auction_offer_thumbnail_cache'] == 0) ? 'checked="checked"' : '',
	'L_BASE_PICTURE_SETTINGS' => $lang['Base_picture_settings'],
	'L_MAX_WIDTH_BIG_PIC' => $lang['max_width_big_pic'],
	'MAX_WIDTH_BIG_PIC' => $new['auction_offer_pic_max_width'],
	'L_MAX_HEIGHT_BIG_PIC' => $lang['max_height_big_pic'],
	'MAX_HEIGHT_BIG_PIC' => $new['auction_offer_pic_max_height'],
	'L_QUALITY_BIG_PIC' => $lang['quality_big_pic'],
	'QUALITY_BIG_PIC' => $new['offer_auction_pic_quality'],
	'L_MAIN_OFFER_PICTURE' =>  $lang['main_offer_image'],
	'L_MAIN_SETTINGS_EXP' => $lang['clear_main_cache_expl'],
	'CLEAR_MAIN_CACHE' => '<a href="' . append_sid( 'auction_clearcache.php?main=1' ) . '">' . $lang['clear_main_cache'] . '</a>',
	'L_CLEAR_ALL_CACHE' => $lang['clear_all_cache_expl'],
	'CLEAR_ALL_CACHE' => '<a href="'.append_sid('auction_clearcache.php?all=1').'">'.$lang['clear_all_cache'].'</a>',
	'L_MAX_SIZE_MAIN_PIC' => $lang['max_size_main_pic'],
	'MAX_SIZE_MAIN_PIC' => $new['auction_offer_main_size'],
	'L_QUALITY_MAIN_PIC' => $lang['quality_main_pic'],
	'QUALITY_MAIN_PIC' => $new['auction_offer_main_quality'],
	'L_MAIN_PIC_BORDER' => $lang['main_offer_image_border'],
	'L_MAIN_PIC_BORDER_COL' => $lang['main_offer_image_border_col'],
	'L_MAIN_PIC_BORDER_WIDTH' => $lang['main_offer_image_border_width'],
	'MAIN_PIC_BORDER_YES' => ($new['main_pic_border'] == 1) ? 'checked="checked"' : '',
	'MAIN_PIC_BORDER_NO' => ($new['main_pic_border'] == 0) ? 'checked="checked"' : '',
	'MAIN_PIC_BORDER_COL' => $new['main_pic_border_color'],
	'MAIN_PIC_BORDER_WIDTH' => $new['main_pic_border_width'],
	'L_MAIN_PIC_SHARPEN' => $lang['main_pic_sharpen'],
	'SH_SEL_0' => $lang['leave_as_is'],
	'SH_SEL_1' => $lang['sharpen'],
	'SH_SEL_2' => $lang['sharpen_more'],
	'SH_SEL_3' => $lang['blur'],
	'SH_SEL_4' => $lang['blur_more'],
	'SHSMAIN_SEL_0' => ($new['main_pic_sharpen'] == 0) ? 'selected' : '',
	'SHSMAIN_SEL_1' => ($new['main_pic_sharpen'] == 1) ? 'selected' : '',
	'SHSMAIN_SEL_2' => ($new['main_pic_sharpen'] == 2) ? 'selected' : '',
	'SHSMAIN_SEL_3' => ($new['main_pic_sharpen'] == 3) ? 'selected' : '',
	'SHSMAIN_SEL_4' => ($new['main_pic_sharpen'] == 4) ? 'selected' : '',
	'L_MAIN_PIC_BW' => $lang['main_pic_bw'],
	'MAIN_PIC_BW_YES' => ($new['main_pic_bw'] == 1) ? 'checked="checked"' : '',
	'MAIN_PIC_BW_NO' => ($new['main_pic_bw'] == 0) ? 'checked="checked"' : '',
	'L_MAIN_PIC_JS_BW' => $lang['main_pic_js_bw'],
	'MAIN_PIC_JS_BW_YES' => ($new['main_pic_js_bw'] == 1) ? 'checked="checked"' : '',
	'MAIN_PIC_JS_BW_NO' => ($new['main_pic_js_bw'] == 0) ? 'checked="checked"' : '',
	'L_MINI_ICON_SETTINGS' => $lang['mini_icon_settings'],
	'L_MINI_ICON_SETTINGS_EXP' => $lang['clear_mini_icon_cache_expl'],
	'L_MAX_SIZE_MINI_PIC' => $lang['max_size_mini_pic'],
	'MAX_SIZE_MINI_PIC' => $new['auction_offer_mini_size'],
	'L_QUALITY_MINI_PIC' => $lang['quality_mini_pic'],
	'QUALITY_MINI_PIC' => $new['auction_offer_mini_quality'],
	'L_MINI_PIC_BORDER' => $lang['mini_offer_image_border'],
	'L_MINI_PIC_BORDER_COL' => $lang['mini_offer_image_border_col'],
	'MINI_PIC_BORDER_YES' => ($new['mini_pic_border'] == 1) ? 'checked="checked"' : '',
	'MINI_PIC_BORDER_NO' => ($new['mini_pic_border'] == 0) ? 'checked="checked"' : '',
	'MINI_PIC_BORDER_COL' => $new['mini_pic_border_color'],
	'L_MINI_PIC_BORDER_WIDTH' => $lang['mini_pic_border_width'],
	'MINI_PIC_BORDER_WIDTH' => $new['mini_pic_border_width'],
	'L_MINI_PIC_SHARPEN' => $lang['mini_pic_sharpen'],
	'L_MINI_PIC_BW' => $lang['mini_pic_bw'],
	'MINI_PIC_BW_YES' => ($new['mini_pic_bw'] == 1) ? 'checked="checked"' : '',
	'MINI_PIC_BW_NO' => ($new['mini_pic_bw'] == 0) ? 'checked="checked"' : '',
	'SHSMINI_SEL_0' => ($new['mini_pic_sharpen'] == 0) ? 'selected' : '',
	'SHSMINI_SEL_1' => ($new['mini_pic_sharpen'] == 1) ? 'selected' : '',
	'SHSMINI_SEL_2' => ($new['mini_pic_sharpen'] == 2) ? 'selected' : '',
	'SHSMINI_SEL_3' => ($new['mini_pic_sharpen'] == 3) ? 'selected' : '',
	'SHSMINI_SEL_4' => ($new['mini_pic_sharpen'] == 4) ? 'selected' : '',
	'L_OFFER_GAL_SETTINGS' => $lang['offer_gallery_settings'],
	'L_THUMB_SETTINGS_EXP' => $lang['clear_thumb_cache_expl'],
	'CLEAR_THUMB_CACHE' =>'<a href="' . append_sid('auction_clearcache.php?thumb=1') . '">' . $lang['clear_thumb_cache'] . '</a>',
	'L_AUCTION_PICTURE_THUMBS_ALLOW' => $lang['auction_offer_thumbs_allow'],
	'AUCTION_PICTURE_THUMBS_ALLOW_YES' =>  ($new['allow_thumb_gallery'] == 1) ? 'checked="checked"' : '',
	'AUCTION_PICTURE_THUMBS_ALLOW_NO' =>  ($new['allow_thumb_gallery'] == 0) ? 'checked="checked"' : '',
	'L_AUCTION_PICTURE_THUMBS_AMOUNT' =>  $lang['picture_thumbs_amount'],
	'AM_SEL_0' => ($new['amount_of_thumbs'] == 0) ? 'selected' : '',
	'AM_SEL_1' => ($new['amount_of_thumbs'] == 1) ? 'selected' : '',
	'AM_SEL_2' => ($new['amount_of_thumbs'] == 2) ? 'selected' : '',
	'AM_SEL_3' => ($new['amount_of_thumbs'] == 3) ? 'selected' : '',
	'AM_SEL_4' => ($new['amount_of_thumbs'] == 4) ? 'selected' : '',
	'AM_SEL_5' => ($new['amount_of_thumbs'] == 5) ? 'selected' : '',
	'AM_SEL_6' => ($new['amount_of_thumbs'] == 6) ? 'selected' : '',
	'AM_SEL_7' => ($new['amount_of_thumbs'] == 7) ? 'selected' : '',
	'AM_SEL_8' => ($new['amount_of_thumbs'] == 8) ? 'selected' : '',
	'AM_SEL_9' => ($new['amount_of_thumbs'] == 9) ? 'selected' : '',
	'AM_SEL_10' => ($new['amount_of_thumbs'] == 10) ? 'selected' : '',
	'L_GALLERY_COLUMS' => $lang['auction_offer_thumbs_colums'],
	'COL_SEL_3' => ($new['amount_of_thumb_per_line'] == 3) ? 'selected' : '',
	'COL_SEL_4' => ($new['amount_of_thumb_per_line'] == 4) ? 'selected' : '',
	'COL_SEL_5' => ($new['amount_of_thumb_per_line'] == 5) ? 'selected' : '',
	'L_THUMBNAIL_TYPE' => $lang['thumbnail_type'],
	'THUMB_PIC_SQUARE' => ($new['thumb_pic_type'] == 1) ? 'checked="checked"' : '',
	'THUMB_PIC_NORMAL' => ($new['thumb_pic_type'] == 0) ? 'checked="checked"' : '',
	'L_THUMB_PIC_NORMAL' => $lang['thumb_pic_normal'],
	'L_THUMB_PIC_SQUARE' => $lang['thumb_pic_square'],
	'L_MAX_SIZE_THUMB_PIC' => $lang['max_size_thumb_pic'],
	'MAX_SIZE_THUMB_PIC' => $new['auction_offer_thumb_size'],
	'L_QUALITY_THUMB_PIC' => $lang['quality_thumb_pic'],
	'QUALITY_THUMB_PIC' => $new['auction_offer_thumb_quality'],
	'THUMB_PIC_BORDER_YES' => ($new['thumb_pic_border'] == 1) ? 'checked="checked"' : '',
	'THUMB_PIC_BORDER_NO' => ($new['thumb_pic_border'] == 0) ? 'checked="checked"' : '',
	'L_THUMB_PIC_BORDER' => $lang['thumb_offer_image_border'],
	'L_THUMB_PIC_BORDER_COL' => $lang['thumb_offer_image_border_col'],
	'L_THUMB_PIC_SHARPEN' => $lang['thumb_pic_sharpen'],
	'L_THUMB_PIC_BW' => $lang['thumb_pic_bw'],
	'THUMB_PIC_BORDER_COL' => $new['thumb_pic_border_color'],
	'L_THUMB_PIC_BORDER_WIDTH' => $lang['thumb_border_width'],
	'THUMB_PIC_BORDER_WIDTH' => $new['thumb_pic_border_width'],
	'THUMB_PIC_BW_YES' => ($new['thumb_pic_bw'] == 1) ? 'checked="checked"' : '',
	'THUMB_PIC_BW_NO' => ($new['thumb_pic_bw'] == 0) ? 'checked="checked"' : '',
	'SHS_SEL_0' => ($new['thumb_pic_sharpen'] == 0) ? 'selected' : '',
	'SHS_SEL_1' => ($new['thumb_pic_sharpen'] == 1) ? 'selected' : '',
	'SHS_SEL_2' => ($new['thumb_pic_sharpen'] == 2) ? 'selected' : '',
	'SHS_SEL_3' => ($new['thumb_pic_sharpen'] == 3) ? 'selected' : '',
	'SHS_SEL_4' => ($new['thumb_pic_sharpen'] == 4) ? 'selected' : '',
	'L_THUMB_PIC_JS_BW' => $lang['thumb_pic_js_bw'],
	'THUMB_PIC_JS_BW_YES' => ($new['thumb_pic_js_bw'] == 1) ? 'checked="checked"' : '',
	'THUMB_PIC_JS_BW_NO' => ($new['thumb_pic_js_bw'] == 0) ? 'checked="checked"' : '',
	'L_ALLOW_EDITING' => $lang['allow_editing'],
	'EL_SEL_2' => ($new['edit_level'] == 2) ? 'selected' : '',
	'EL_SEL_1' => ($new['edit_level'] == 1) ? 'selected' : '',
	'EL_SEL_0' => ($new['edit_level'] == 0) ? 'selected' : '',
	'L_EL_SEL_2' => $lang['edit_level_2'],
	'L_EL_SEL_1' => $lang['edit_level_1'],
	'L_EL_SEL_0' => $lang['edit_level_0'],
	'L_SECURITY_SETTINGS' => $lang['security_settings'],
	'L_HOTLINK_PREVENT' => $lang['Hotlink_prevent'],
	'HOTLINK_PREVENT_ENABLED' => ($new['auction_offer_hotlink_prevent'] == 1) ? 'checked="checked"' : '',
	'HOTLINK_PREVENT_DISABLED' => ($new['auction_offer_hotlink_prevent'] == 0) ? 'checked="checked"' : '',
	'L_HOTLINK_ALLOWED' => $lang['Hotlink_allowed'],
	'HOTLINK_ALLOWED' => $new['auction_offer_hotlink_allowed'],
	'L_PIC_APPROVAL_ADMIN' => $lang['Pics_Approval_A'],
	'L_PIC_APPROVAL_MOD' => $lang['Pics_Approval_M'],
	'PIC_APPROVAL_ADMIN_ENABLED' => ($new['auction_offer_pic_approval_admin'] == 1) ? 'checked="checked"' : '',
	'PIC_APPROVAL_ADMIN_DISABLED' => ($new['auction_offer_pic_approval_admin'] == 0) ? 'checked="checked"' : '',
	'PIC_APPROVAL_MOD_ENABLED' => ($new['auction_offer_pic_approval_mod'] == 1) ? 'checked="checked"' : '',
	'PIC_APPROVAL_MOD_DISABLED' => ($new['auction_offer_pic_approval_mod'] == 0) ? 'checked="checked"' : '',
	'L_WATER_SETTINGS' => $lang['Watermark_settings'],
	'L_WATER_SETTINGS_EXP' => $lang['clear_water_cache_expl'],
	'CLEAR_BIG_WATER_CACHE' =>'<a href="'.append_sid('auction_clearcache.php?water=1').'">'.$lang['clear_big_water_cache'].'</a>',
	'CLEAR_MAIN_WATER_CACHE' =>'<a href="'.append_sid('auction_clearcache.php?mainwater=1').'">'.$lang['clear_main_water_cache'].'</a>',
	'OR_ALSO' => $lang['or_also'],
	'L_MAIN_PIC_USE_WATER' => $lang['main_pic_use_water'],
	'L_MAIN_PIC_CURRENT_WATER' => $lang['main_pic_current_water'],
	'MAIN_PIC_CURRENT_WATER' => $u_main_pic_current_water,
	'MAIN_PIC_USE_WATER_ENABLED' => ($new['main_pic_use_water'] == 1) ? 'checked="checked"' : '',
	'MAIN_PIC_USE_WATER_DISABLED' => ($new['main_pic_use_water'] == 0) ? 'checked="checked"' : '',
	'L_MAIN_PIC_TEST_WATER' => $lang['main_pic_test_water'] . AUCTION_PICTURE_UPLOAD_PATH  . "wmk/" .  $dummy_file ."</b>",
	'MAIN_PIC_TEST_WATER' => $u_main_test_pic_water,
	'L_MAIN_WATER_IMG_QUAL' => $lang['main_water_img_qual'],
	'MAIN_WATER_IMG_QUAL' => $new['main_water_img_qual'],
	'L_MAIN_WATER_IMG_TRANS' => $lang['main_water_img_trans'],
	'MAIN_WATER_IMG_TRANS' => $new['main_water_img_trans'],
	'L_MAIN_WATER_UPLOAD' => $lang['main_water_upload'],
	'L_MAIN_WATER_IMG_POS' => $lang['main_water_img_pos'],
	'L_MAIN_PIC_FOR_ALL_WATER' => $lang['main_pic_for_all_water'],
	'MAIN_PIC_FOR_ALL_WATER_ENABLED' => ($new['main_pic_for_all_water'] == 1) ? 'checked="checked"' : '',
	'MAIN_PIC_FOR_ALL_WATER_DISABLED' => ($new['main_pic_for_all_water'] == 0) ? 'checked="checked"' : '',
	'L_MAIN_PIC_FOR_GUEST_WATER' => $lang['main_pic_for_guest_water'],
	'MAIN_PIC_FOR_GUEST_WATER_ENABLED' => ($new['main_pic_for_guest_water'] == 1) ? 'checked="checked"' : '',
	'MAIN_PIC_FOR_GUEST_WATER_DISABLED' => ($new['main_pic_for_guest_water'] == 0) ? 'checked="checked"' : '',
	'GUEST' => $lang['guest_only'],
	'ALL_USERS' => $lang['All_users'],
	'MWMP_0' => ($new['main_watermarkpos'] == 0) ? 'checked' : '',
	'MWMP_1' => ($new['main_watermarkpos'] == 1) ? 'checked' : '',
	'MWMP_2' => ($new['main_watermarkpos'] == 2) ? 'checked' : '',
	'MWMP_3' => ($new['main_watermarkpos'] == 3) ? 'checked' : '',
	'MWMP_4' => ($new['main_watermarkpos'] == 4) ? 'checked' : '',
	'MWMP_5' => ($new['main_watermarkpos'] == 5) ? 'checked' : '',
	'MWMP_6' => ($new['main_watermarkpos'] == 6) ? 'checked' : '',
	'MWMP_7' => ($new['main_watermarkpos'] == 7) ? 'checked' : '',
	'MWMP_8' => ($new['main_watermarkpos'] == 8) ? 'checked' : '',
	'CLEAR_MINI_ICON_CACHE' => '<a href="'.append_sid('auction_clearcache.php?mini=1').'">'.$lang['clear_mini_icon_cache'].'</a>',
	'L_NOT_IMPLEMENTED' => $lang['not_impl'],
	'L_WATER_BIG_SETTINGS' => $lang['Watermark_big_settings'],
	'L_BIG_PIC_USE_WATER' => $lang['big_pic_use_water'],
	'BIG_PIC_USE_WATER_ENABLED' => ($new['big_pic_use_water'] == 1) ? 'checked="checked"' : '',
	'BIG_PIC_USE_WATER_DISABLED' => ($new['big_pic_use_water'] == 0) ? 'checked="checked"' : '',
	'L_BIG_PIC_FOR_GUEST_WATER' => $lang['big_pic_for_guest_water'],
	'BIG_PIC_FOR_GUEST_WATER_ENABLED' => ($new['big_pic_for_guest_water'] == 1) ? 'checked="checked"' : '',
	'BIG_PIC_FOR_GUEST_WATER_DISABLED' => ($new['big_pic_for_guest_water'] == 0) ? 'checked="checked"' : '',
	'L_BIG_PIC_CURRENT_WATER' => $lang['big_pic_current_water'],
	'BIG_PIC_CURRENT_WATER' => $u_big_pic_current_water,
	'L_BIG_WATER_UPLOAD' => $lang['main_water_upload'],
	'L_BIG_WATER_IMG_QUAL' => $lang['big_water_img_qual'],
	'BIG_WATER_IMG_QUAL' => $new['big_water_img_qual'],
	'L_BIG_WATER_IMG_POS' => $lang['main_water_img_pos'],
	'BWMP_0' => ($new['big_watermarkpos'] == 0) ? 'checked' : '',
	'BWMP_1' => ($new['big_watermarkpos'] == 1) ? 'checked' : '',
	'BWMP_2' => ($new['big_watermarkpos'] == 2) ? 'checked' : '',
	'BWMP_3' => ($new['big_watermarkpos'] == 3) ? 'checked' : '',
	'BWMP_4' => ($new['big_watermarkpos'] == 4) ? 'checked' : '',
	'BWMP_5' => ($new['big_watermarkpos'] == 5) ? 'checked' : '',
	'BWMP_6' => ($new['big_watermarkpos'] == 6) ? 'checked' : '',
	'BWMP_7' => ($new['big_watermarkpos'] == 7) ? 'checked' : '',
	'BWMP_8' => ($new['big_watermarkpos'] == 8) ? 'checked' : '',
	'BIG_WATER_IMG_TRANS' => $new['big_water_img_trans'],
	'L_BIG_PIC_TEST_WATER' => $lang['big_pic_test_water'] . AUCTION_PICTURE_UPLOAD_PATH  . "wmk/" .  $big_dummy_file ."</b>",
	'BIG_PIC_TEST_WATER' => $u_big_test_pic_water,
	'L_DISABLED' => $lang['Disabled'],
	'L_ENABLED' => $lang['Enabled'],
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset']));

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>