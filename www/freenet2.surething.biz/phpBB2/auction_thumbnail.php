<?php
/***************************************************************************
 *                            auction_thumbnail.php
 *                            -------------------
 *   begin                : may 05, 2003
 *   copyright            : (C) 2003 mr.luc (many routines inspired by Smartor and his album
 *   email                : llg@gmx.at
 *
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
$auction_root_path = $phpbb_root_path . 'auction/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($auction_root_path . 'auction_constants.php');
include($auction_root_path . 'functions_general.php');

// function for watermark 
			// ATTENTION do not rename the files!!!
			$watermark1_file = 'main_watermark.png';
			$watermark2_file = 'big_watermark.png';
function mergePix($sourcefile,$insertfile, $pos=0,$transition=50, $path, $unreg_quality, $cache, $pic_filetype, $file) 
{ 

	//Get the resource id´s of the pictures 
	$insertfile_id = imageCreateFromPNG($insertfile); 
	if($file == 1)
	{
		if($pic_filetype == '.jpg')
		{
			$sourcefile_id = imageCreateFromJPEG($sourcefile); 
		}
		elseif($pic_filetype == '.png')
		{
			$sourcefile_id = ImageCreateFromPNG($sourcefile); 
		}
		else
		{
			$sourcefile_id = imageCreateFromJPEG($sourcefile); 
		}
	}
	else
	{

		$sourcefile_id = $sourcefile;
	}
   //Get the sizes of both pix 
   $sourcefile_width=imageSX($sourcefile_id); 
   $sourcefile_height=imageSY($sourcefile_id); 
   $insertfile_width=imageSX($insertfile_id); 
   $insertfile_height=imageSY($insertfile_id); 

   //middle 
   if( $pos == 0 ) 
   { 
   $dest_x = ( $sourcefile_width / 2 ) - ( $insertfile_width / 2 ); 
   $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
   } 

   //top left 
   if( $pos == 1 ) 
   { 
   $dest_x = 0; 
   $dest_y = 0; 
   } 

   //top right 
   if( $pos == 2 ) 
   { 
   $dest_x = $sourcefile_width - $insertfile_width; 
   $dest_y = 0; 
   } 

   //bottom right 
   if( $pos == 3 ) 
   { 
   $dest_x = $sourcefile_width - $insertfile_width; 
   $dest_y = $sourcefile_height - $insertfile_height; 
   } 

   //bottom left 
   if( $pos == 4 ) 
   { 
   $dest_x = 0; 
   $dest_y = $sourcefile_height - $insertfile_height; 
   } 

   //top middle 
   if( $pos == 5 ) 
   { 
   $dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 ); 
   $dest_y = 0; 
   } 

   //middle right 
   if( $pos == 6 ) 
   { 
   $dest_x = $sourcefile_width - $insertfile_width; 
   $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
   } 

   //bottom middle 
   if( $pos == 7 ) 
   { 
   $dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 ); 
   $dest_y = $sourcefile_height - $insertfile_height; 
   } 

   //middle left 
   if( $pos == 8 ) 
   { 
   $dest_x = 0; 
   $dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
   } 

   //The main thing : merge the two pix 
   imageCopyMerge($sourcefile_id, $insertfile_id,$dest_x,$dest_y,0,0,$insertfile_width,$insertfile_height,$transition); 

   //Create a jpeg out of the modified picture 
   // imagejpeg ($sourcefile_id,"$targetfile"); 
   // Imagejpeg($sourcefile_id,'',100); 

	if($cache == 1)
	{
		@imagejpeg($sourcefile_id, $path, $unreg_quality);
		@imagejpeg($sourcefile_id, '', $unreg_quality);
	}
	else
	{
		@imagejpeg($sourcefile_id, '', $unreg_quality);
	}
	@ImageDestroy($sourcefile_id); 


} 


//
// Start session management
//
$userdata = session_pagestart($user_ip, 444);
init_userprefs($userdata);

//
// End session management
//
// we have only3 language strings... there is no point in loading the whole language file...
// we do it here as you only see this text if you call auction thumbnail.php as standalone
// normal users should not see the text...(debugging only)!!!

$lang['Pic_not_exist'] = 'The specified picture does not exist!';

// this one is for hotlinking (but is NOT seen in the <img> tag)
$lang['Not_Authorised'] = 'You are not authorized to do this.. <br /><font size=-2><i>IP has been logged!</i></font>';
$lang['No_pics_specified'] = 'No picture has been specified!';

// BEGIN include auction-pic-config information
$auction_config_pic = init_auction_config_pic();
// END include auction-pic-config information

// PARAMETERS => SETTINGS

// $crop_id = 2; // for testing only.. delete when croppage is implemented for minis and square thumbs

// check for gd 'imagecopyresized' : 'imagecopyresampled';
// check if gd setting is on AUTO ($gd = 3)

$gd = $auction_config_pic['gd_version'];
if ($gd == 3)
{
	$gd = (function_exists('imagecopyresampled')) ? 2 : ((function_exists('imagecopyresized'))? 1 : 0   );
}
$auction_config_pic['gd_version'] = $gd;


// ------------------------------------
// Check the request
// ------------------------------------

if( isset($HTTP_GET_VARS['pic_id']) )
{
	$pic_id = intval($HTTP_GET_VARS['pic_id']);
}
else if( isset($HTTP_POST_VARS['pic_id']) )
{
	$pic_id = intval($HTTP_POST_VARS['pic_id']);
}
else
{
	die($lang['No_pics_specified']);
}


// check for pic-type 1 = main picture - 2 = thumbnail - 3 = mini icon
// main picture is NOT the fullsized uploaded picture but the main picture of an offer. the thumbnail is bigger than the others !!!


if( isset($HTTP_GET_VARS['pic_type']) )
{
	$pic_type = intval($HTTP_GET_VARS['pic_type']);
}
else if( isset($HTTP_POST_VARS['pic_type']) )
{
	$pic_type = intval($HTTP_POST_VARS['pic_type']);
}
else
{
	$pic_type = 0;
}

// ------------------------------------
// Get this pic info
// ------------------------------------

$sql = "SELECT *
		FROM ". AUCTION_IMAGE_TABLE ."
		WHERE pic_id = '$pic_id'";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}
$thispic = $db->sql_fetchrow($result);



$pic_filetype = substr($thispic['pic_filename'], strlen($thispic['pic_filename']) - 4, 4);
$pic_filename = $thispic['pic_filename'];
$pic_thumbnail = $thispic['pic_filename'];
$pic_user_id = $thispic['pic_user_id'];
// $crop_id = $thispic['pic_crop_id'];


if( empty($thispic) or !file_exists(AUCTION_PICTURE_UPLOAD_PATH . $pic_filename) )
{
	die($lang['Pic_not_exist']);
}


// ------------------------------------
// Check Pic Approval
// ------------------------------------
/* still todo -> if not admin and not mod get the " not approved yet " thumbnail
What i want: in admin panel can be checked: 
- dont show thumbnail if not approved
- show placeholder (awaiting approval)
*/

/* For now the approval ist checked i view offer - here we only do it so people cannot call the pic with the auction_thumbnail.php 
Admin or Mod can view it
*/
// first check if mod needs approval
$mod_approval = 0;
$show_pic = 0;
if(($userdata['user_level'] == MOD) AND ($auction_config_pic['auction_offer_pic_approval_mod'] == 1))
{
	$mod_approval = 1;
}

if (($userdata['user_level'] != ADMIN) AND ($mod_approval == 0))
{
	// ok we need the mini for the user so he can see it in the pic-manager
	// and we need the thumbnail for the recropping 

	// so we check if the user_id is the picture_poster user_id
	// remember that admin and mods impersonate the user_id when uploading in the pic-manager
	// show the thumbnail if recrop == 1
	if(($userdata['user_id'] != $pic_user_id) OR ($userdata['user_level'] == MOD))
	{
		if((isset($HTTP_GET_VARS['recrop'])) AND ($pic_type == 2))
		{
			$show_pic = 1;
		}
		elseif(($HTTP_GET_VARS['pm'] == 1) AND ($pic_type == 3))
		{
			$show_pic = 1;
		}
		else
		{
			$show_pic = 0;
		}
	}

	// we have excluded all shows
	if (($thispic['pic_approval'] != 0) AND ($show_pic == 0))
	{
		die($lang['Not_Authorised']);
	}
}

// ------------------------------------
// Check hotlink
// this part is heavily inspired from smartors album
// ------------------------------------

if( ($auction_config_pic['auction_offer_hotlink_prevent'] == 1) and (isset($HTTP_SERVER_VARS['HTTP_REFERER'])) )
{
	$check_referer = explode('?', $HTTP_SERVER_VARS['HTTP_REFERER']);
	$check_referer = trim($check_referer[0]);

	$good_referers = array();

	if ($auction_config_pic['auction_offer_hotlink_allowed'] != '')
	{
		$good_referers = explode(',', $auction_config_pic['auction_offer_hotlink_allowed']);
	}

	$good_referers[] = $board_config['server_name'] . $board_config['script_path'];

	$errored = TRUE;

	for ($i = 0; $i < count($good_referers); $i++)
	{
		$good_referers[$i] = trim($good_referers[$i]);

		if( (strstr($check_referer, $good_referers[$i])) and ($good_referers[$i] != '') )
		{
			$errored = FALSE;
		}
	}

	if ($errored)
	{
		die($lang['Not_Authorised']);
	}
}



/*
+----------------------------------------------------------
| Main work here...
+----------------------------------------------------------
*/


// ------------------------------------
// Send File to browser
// ------------------------------------
$gd = $auction_config_pic['gd_version'];
//watermark insertion for popup picture

if(($gd > 0) AND ($pic_type == 0) AND (($pic_filetype == '.jpg') OR ($pic_filetype == '.png')))
{
	//its the main popup so we check if watermark has to be inserted
	$insert_water = 0;
	// first we see if watermark is enabled for popup



	if(($auction_config_pic['main_pic_use_water'] == 1 ) AND ($auction_config_pic['main_pic_for_all_water'] == 1 ))
	{
		$wmk_position  = $auction_config_pic['main_watermarkpos'];
		$wmk_transition = $auction_config_pic['main_water_img_trans'];
		$wmk_unreg_quality = $auction_config_pic['main_water_img_qual'];
		$insert_water = 1;
	}
	elseif($auction_config_pic['big_pic_use_water'] == 1 )
	{
		$wmk_position  = $auction_config_pic['big_watermarkpos'];
		$wmk_transition = $auction_config_pic['big_water_img_trans'];
		$wmk_unreg_quality = $auction_config_pic['big_water_img_qual'];
		$insert_water = 2;
	}
	else
	{
		$insert_water = 0;
	}

	// first we check if user is guest and if only guests get to see watermarks
	if(($insert_water == 1) AND (($auction_config_pic['main_pic_for_guest_water'] == 1 ) AND ($userdata['session_logged_in'] )))
	{
		$insert_water = 0;
	}
	if(($insert_water == 2) AND (($auction_config_pic['big_pic_for_guest_water'] == 1 ) AND ($userdata['session_logged_in'] )))
	{
		$insert_water = 0;
	}

	if($insert_water > 0)
	{
		// we check if the cached watermarked image exists
		if( ($auction_config_pic['auction_offer_thumbnail_cache'] == 1) and ($pic_thumbnail != '') and file_exists( AUCTION_PICTURE_WATERMARK_PATH . $pic_thumbnail) )
		{
			switch ($pic_filetype)
			{
				case '.jpg':
					header('Content-type: image/jpeg');
					break;
				case '.png':
					header('Content-type: image/png');
					break;

			}

			readfile( AUCTION_PICTURE_WATERMARK_PATH . $pic_thumbnail);
			exit;

		}
		else // watermarked image does not exist or cache is not enabled so we have to create it
		{
			
			// now we check for the watermark file
			// ATTENTION do not rename the files!!!
			$watermark1_file = 'main_watermark.png';
			$watermark2_file = 'big_watermark.png';

			if($insert_water == 1)
			{
				if(file_exists( AUCTION_PICTURE_UPLOAD_PATH . "wmk/" . $watermark1_file) )
				{
					$water_mark = AUCTION_PICTURE_UPLOAD_PATH . "wmk/" .  $watermark1_file;
				}
				else
				{
					$insert_water = 0;
				}
			}
			elseif($insert_water == 2)
			{
				if(file_exists( AUCTION_PICTURE_UPLOAD_PATH  . "wmk/" .  $watermark2_file) )
				{
					$water_mark = AUCTION_PICTURE_UPLOAD_PATH  . "wmk/" .  $watermark2_file;
				}
				else
				{
					$insert_water = 0;
				}
			}
			
			if($insert_water > 0)
			{
				//we include the watermark here and we save it to disk

				$position  = $wmk_position;
				
				if((!isset($position))  OR ($postion > 8) OR ($postion < 0))
				{
					$position = 3;
				}
				$transition = $wmk_transition;
				if((!isset($transition))  OR ($transition > 100) OR ($transition < 0))
				{
					$transition = 70;
				}
				$unreg_quality = $wmk_unreg_quality;
								if((!isset($unreg_quality))  OR ($unreg_quality > 99) OR ($unreg_quality < 0))
				{
					$unreg_quality = 70;
				}

				// settings end
				//Get the resource id´s of the pictures 
				$sourcefile = AUCTION_PICTURE_UPLOAD_PATH . $pic_thumbnail;
				$insertfile = $water_mark;

				$cache = ($auction_config_pic['auction_offer_thumbnail_cache'] == 1) ? 1 : 0 ;
				$path = AUCTION_PICTURE_WATERMARK_PATH . $pic_thumbnail;
				$file = 1; // the sourcefile is loaded from a file 
				mergePix($sourcefile, $insertfile, $position, $transition, $path, $unreg_quality, $cache, $pic_filetype, $file) ;
				if($auction_config_pic['auction_offer_thumbnail_cache'] == 1)
				{
					if( file_exists($path) )
					{
						@chmod($path, 0777);
					}
				}
				else
				{
					if( file_exists($path) )
					{
						@chmod($path, 0777);
						if($auction_config_pic['auction_offer_thumbnail_cache'] != 1)
						{
							// we delete the file because cache is not enabled
							@unlink($path);
						}
					}
				}
				if($insert_water > 0)
				{
					exit;
				}			


			}
				/* we  do nothing.. if the watermark file doesn't exist... the picture will be loaded as normal picture later. */
		}
	}
}


if( (($pic_filetype != '.jpg') AND ($pic_filetype != '.png')) OR ($gd == 0) OR ($pic_type == 0))
{
	// --------------------------------
	// GD does not support GIF so we must SEND the original pic to browser then exit ;
	// same thing if there is no GD support
	// --------------------------------
	// I know case jpg and png cannot occur, but in future a filetype could be excluded in the if() above.

		switch ($pic_filetype)
		{
			case '.jpg':
				header('Content-type: image/jpeg');
				readfile(AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
				exit;
				break;
			case '.png':
				header('Content-type: image/png');
				readfile(AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
				exit;
				break;
			case '.gif':
				header('Content-type: image/gif');
				readfile(AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
				exit;
				break;
			
			
		}

	// i dunno why this is here... should be treated in case jpg.... hmmm lets leave it there it will do no harm...
	header('Content-type: image/jpeg');
	readfile(AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
	exit;
}
else
{
	// IF we are here we have GD enabled and pic is either jpg or png
	// --------------------------------
	// Check thumbnail cache. If cache is available we will SEND & EXIT

	// -----------------------------
	
	if ( $pic_type == 1)
	{
		// we check if we need watermarked pic or not
		$main_insert_water = 0;
		if($auction_config_pic['main_pic_use_water'] == 1 )
		{
			$main_insert_water = 1;
		}
	
		if(($insert_water == 1) AND (($auction_config_pic['main_pic_for_guest_water'] == 1 ) AND ($userdata['session_logged_in'] )))
		{
			$main_insert_water = 0;
		}

		if( ($auction_config_pic['auction_offer_thumbnail_cache'] == 1) and ($pic_thumbnail != '') and file_exists(AUCTION_PICTURE_MAIN_PATH . $pic_thumbnail) and ($main_insert_water == 0))
		{
			switch ($pic_filetype)
			{
				case '.jpg':
					header('Content-type: image/jpeg');
					break;
				case '.png':
					header('Content-type: image/png');
					break;

			}

			readfile(AUCTION_PICTURE_MAIN_PATH . $pic_thumbnail);
			exit;
		}
		if( ($auction_config_pic['auction_offer_thumbnail_cache'] == 1) and ($pic_thumbnail != '') and file_exists(AUCTION_PICTURE_MAIN_WATERMARK_PATH . $pic_thumbnail) and ($main_insert_water == 1))
		{
			switch ($pic_filetype)
			{
				case '.jpg':
					header('Content-type: image/jpeg');
					break;
				case '.png':
					header('Content-type: image/png');
					break;

			}

			readfile(AUCTION_PICTURE_MAIN_WATERMARK_PATH . $pic_thumbnail);
			exit;
		}



	}	
	
	else if ( $pic_type == 2)
	{
		//we dont check cache if recrop is set to 1
		if($HTTP_GET_VARS['recrop'] <> 1)
		{
			if( ($auction_config_pic['auction_offer_thumbnail_cache'] == 1) and ($pic_thumbnail != '') and file_exists(AUCTION_PICTURE_CACHE_PATH . $pic_thumbnail) )
			{
				switch ($pic_filetype)
				{
					case '.jpg':
						header('Content-type: image/jpeg');
						break;
					case '.png':
						header('Content-type: image/png');
						break;

				}

				readfile(AUCTION_PICTURE_CACHE_PATH . $pic_thumbnail);
				exit;
			}
		}
	}
	else
	{
		if( ($auction_config_pic['auction_offer_thumbnail_cache'] == 1) and ($pic_thumbnail != '') and file_exists(AUCTION_PICTURE_MINI_PATH . $pic_thumbnail) )
		{
			switch ($pic_filetype)
			{
				case '.jpg':
					header('Content-type: image/jpeg');
					break;
				case '.png':
					header('Content-type: image/png');
					break;

			}

			readfile(AUCTION_PICTURE_MINI_PATH . $pic_thumbnail);
			exit;
		}
	}
	
	// --------------------------------
	// Hmm, cache is empty. Try to re-generate!
	// --------------------------------

	$pic_size = @getimagesize(AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
	$pic_width = $pic_size[0];
	$pic_height = $pic_size[1];

	$gd_errored = FALSE;
	switch ($pic_filetype)
	{
		case '.jpg':
			$read_function = 'imagecreatefromjpeg';
			break;
		case '.png':
			$read_function = 'imagecreatefrompng';
			break;
	}

	$src = @$read_function(AUCTION_PICTURE_UPLOAD_PATH  . $pic_filename);

	if (!$src)
	{
		$gd_errored = TRUE;
		$pic_thumbnail = '';
	}

	// check for pictype here
	else if(( $pic_type == 1) AND (($pic_width > $auction_config_pic['auction_offer_main_size']) OR ($pic_height > $auction_config_pic['auction_offer_main_size'])) )
	{
		// ----------------------------
		// Resize it
		// ----------------------------
		/**** we include thumbnail_pro... its the main gd working file. we include it because it could be used by other applications like album or upload mod. ****/

		if(file_exists($phpbb_root_path . 'auction/graphic_files/auction_thumbnail_pro.'.$phpEx))
		{
			// this is the main gd2 working file...
			include($phpbb_root_path . 'auction/graphic_files/auction_thumbnail_pro.'.$phpEx);
		}
		else
		{
			/* if for any reason thumbnail_pro does not exist, we still can use some basic resizing the else could also be replaced with a simple die(). */

			$thumb_size = $auction_config_pic['auction_offer_main_size'];
			
			if ($pic_width > $pic_height)
			{
				$thumbnail_width = $thumb_size;
				$thumbnail_height = $thumb_size * ($pic_height/$pic_width);
			}
			else
			{
				$thumbnail_height = $thumb_size;
				$thumbnail_width = $thumb_size * ($pic_width/$pic_height);
			}

			$thumbnail = ($auction_config_pic['gd_version'] == 1) ? @imagecreate($thumbnail_width, $thumbnail_height) : @imagecreatetruecolor($thumbnail_width, $thumbnail_height);

			$resize_function = ($auction_config_pic['gd_version'] == 1) ? 'imagecopyresized' : 'imagecopyresampled';

			@$resize_function($thumbnail, $src, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $pic_width, $pic_height);
		}

	}
	else if(( $pic_type == 3) AND (($pic_width > $auction_config_pic['auction_offer_mini_size']) OR ($pic_height > $auction_config_pic['auction_offer_mini_size'])) )
	{
		
		if(file_exists($phpbb_root_path . 'auction/graphic_files/auction_thumbnail_pro.'.$phpEx))
		{
			// this is the main gd2 working file...
			include($phpbb_root_path . 'auction/graphic_files/auction_thumbnail_pro.'.$phpEx);
		}
		else
		{
			$thumb_size = $auction_config_pic['auction_offer_mini_size'];


			if ($pic_width > $pic_height)
			{
				$thumbnail_width = $thumb_size;
				$thumbnail_height = $thumb_size * ($pic_height/$pic_width);
			}
			else
			{
				$thumbnail_height = $thumb_size;
				$thumbnail_width = $thumb_size * ($pic_width/$pic_height);
			}

			$thumbnail = ($auction_config_pic['gd_version'] == 1) ? @imagecreate($thumbnail_width, $thumbnail_height) : @imagecreatetruecolor($thumbnail_width, $thumbnail_height);

			$resize_function = ($auction_config_pic['gd_version'] == 1) ? 'imagecopyresized' : 'imagecopyresampled';

			@$resize_function($thumbnail, $src, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $pic_width, $pic_height);
		}

	}
	else if(( $pic_type == 2) AND (($pic_width > $auction_config_pic['auction_offer_thumb_size']) OR ($pic_height > $auction_config_pic['auction_offer_thumb_size'])) )
	{
		

		// ----------------------------
		// Resize it
		// ----------------------------

		if((file_exists($phpbb_root_path . 'auction/graphic_files/auction_thumbnail_pro.'.$phpEx)) AND ($auction_config_pic['gd_version'] > 0))
		{
			// this is the main gd2 working file...
			include($phpbb_root_path . 'auction/graphic_files/auction_thumbnail_pro.'.$phpEx);
		}
		else
		{
			$thumb_size = $auction_config_pic['auction_offer_thumb_size'];

			if ($pic_width > $pic_height)
			{
				$thumbnail_width = $thumb_size;
				$thumbnail_height = $thumb_size * ($pic_height/$pic_width);
			}
			else
			{
				$thumbnail_height = $thumb_size;
				$thumbnail_width = $thumb_size * ($pic_width/$pic_height);
			}

			$thumbnail = ($auction_config_pic['gd_version'] == 1) ? @imagecreate($thumbnail_width, $thumbnail_height) : @imagecreatetruecolor($thumbnail_width, $thumbnail_height);

			$resize_function = ($auction_config_pic['gd_version'] == 1) ? 'imagecopyresized' : 'imagecopyresampled';

			@$resize_function($thumbnail, $src, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $pic_width, $pic_height);
		}

	}

	else
	{
		$thumbnail = $src;
	}

	if (!$gd_errored)
	{

			// ------------------------
			// Re-generate successfully. Write it to disk!
			// ------------------------

			$pic_thumbnail = $pic_filename;
			if ( $pic_type == 3)
			{
				if($auction_config_pic['auction_offer_thumbnail_cache'] == 1)
				{
					switch ($pic_filetype)
					{
						case '.jpg':
							@imagejpeg($thumbnail, AUCTION_PICTURE_MINI_PATH . $pic_thumbnail, $auction_config_pic['auction_offer_mini_quality']);
							break;
						case '.png':
							@imagepng($thumbnail, AUCTION_PICTURE_MINI_PATH . $pic_thumbnail);
							break;
					}

					@chmod(AUCTION_PICTURE_MINI_PATH . $pic_thumbnail, 0777);
				}
				switch ($pic_filetype)
				{
					case '.jpg':
						@imagejpeg($thumbnail, '', $auction_config_pic['auction_offer_mini_quality']);
						break;
					case '.png':
						@imagepng($thumbnail);
						break;
				}
				@imagedestroy($thumbnail);
				exit;
			}
			if ( $pic_type == 2)
			{
				if($auction_config_pic['auction_offer_thumbnail_cache'] == 1)
				{
					switch ($pic_filetype)
					{
						case '.jpg':
							@imagejpeg($thumbnail, AUCTION_PICTURE_CACHE_PATH . $pic_thumbnail, $auction_config_pic['auction_offer_thumb_quality']);
							break;
						case '.png':
							@imagepng($thumbnail, AUCTION_PICTURE_CACHE_PATH . $pic_thumbnail);
							break;
					}

					@chmod(AUCTION_PICTURE_CACHE_PATH . $pic_thumbnail, 0777);
				}
				switch ($pic_filetype)
				{
					case '.jpg':
						@imagejpeg($thumbnail, '', $auction_config_pic['auction_offer_thumb_quality']);
						break;
					case '.png':
						@imagepng($thumbnail);
						break;
				}
				@imagedestroy($thumbnail);
				exit;

			}
			else
			{
				// as there are only 3 pic types the rest must be type 1 (main picture)
				// ok the file has been generated... now we check if we need a watermark

				if($main_insert_water == 1)
				{

					//we include the watermark here and we save it to disk

					$position  = $auction_config_pic['main_watermarkpos'];
				
					if((!isset($position))  OR ($postion > 8) OR ($postion < 0))
					{
						$position = 3;
					}
					$transition = $auction_config_pic['main_water_img_trans'];
					if((!isset($transition))  OR ($transition > 100) OR ($transition < 0))
					{
						$transition = 70;
					}
					$unreg_quality = $auction_config_pic['main_water_img_qual'];
					if((!isset($unreg_quality))  OR ($unreg_quality > 99) OR ($unreg_quality < 0))
					{
						$unreg_quality = 70;
					}

					// settings end
					//Get the resource id´s of the pictures 
					$sourcefile = $thumbnail;
					$insertfile = AUCTION_PICTURE_UPLOAD_PATH  . "wmk/" .  $watermark1_file;

					$cache = ($auction_config_pic['auction_offer_thumbnail_cache'] == 1) ? 1 : 0 ;
					$path = AUCTION_PICTURE_MAIN_WATERMARK_PATH . $pic_thumbnail;
					$file = 0; // the sourcefile is loaded from an image and not from a file 
					mergePix($sourcefile, $insertfile, $position, $transition, $path, $unreg_quality, $cache, $pic_filetype, $file);
					if($auction_config_pic['auction_offer_thumbnail_cache'] == 1)
					{
						if( file_exists($path) )
						{
							@chmod($path, 0777);
						}
					}
					else
					{
						// we clear the cache
						if( file_exists($path) )
						{
							@chmod($path, 0777);
							if($auction_config_pic['auction_offer_thumbnail_cache'] != 1)
							{
								// we delete the file because cache is not enabled
								@unlink($path);
							}
						}
					}
					exit;
				}
				
				else
				{
					if($auction_config_pic['auction_offer_thumbnail_cache'] == 1)
					{
						switch ($pic_filetype)
						{
							case '.jpg':
								@imagejpeg($thumbnail, AUCTION_PICTURE_MAIN_PATH . $pic_thumbnail, $auction_config_pic['auction_offer_main_quality']);
								break;
							case '.png':
								@imagepng($thumbnail, AUCTION_PICTURE_MAIN_PATH . $pic_thumbnail);
								break;
						}

						@chmod(AUCTION_PICTURE_MAIN_PATH . $pic_thumbnail, 0777);
					}
					
					// send to browser


					switch ($pic_filetype)
					{
						case '.jpg':
							@imagejpeg($thumbnail, '', $auction_config_pic['auction_offer_main_quality']);
							break;
						case '.png':
							@imagepng($thumbnail);
							break;
					}
				}
				@imagedestroy($thumbnail);
				exit;
			}

		


		// ----------------------------
		// After write to disk, donot forget to send to browser also
		// ----------------------------


	}
	else
	{
		// ----------------------------
		// It seems you have not GD installed :( so we send the original picture
		// ----------------------------

		switch ($pic_filetype)
		{
			case '.jpg':
				header('Content-type: image/jpeg');
				readfile(AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
				exit;
				break;
			case '.png':
				header('Content-type: image/png');
				readfile(AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
				exit;
				break;
			case '.gif':
				header('Content-type: image/gif');
				readfile(AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
				exit;
				break;
			
			
		}
	}
}
?>