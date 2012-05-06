<?php
/***************************************************************************
 *                          auction_pic_upload.php
 *                            -------------------
 *   begin                : May 2004
 *   copyright            : (C) Mr.Luc
 *   email                : llg@gmx.at
 *   compiled for         : phpbb-auction.com
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

if($upload_mode == 2)
{
	if ( preg_match('/^(http:\/\/)?([\w\-\.]+)\:?([0-9]*)\/(.*)$/', $avatar_filename, $url_ary) )
	{
		if ( empty($url_ary[4]) )
		{
			message_die(GENERAL_ERROR, $lang['inc_or_wrong_url']);
		}

		$base_get = '/' . $url_ary[4];
		$port = ( !empty($url_ary[3]) ) ? $url_ary[3] : 80;

		if ( !($fsock = @fsockopen($url_ary[2], $port, $errno, $errstr)) )
		{
			message_die(GENERAL_ERROR, $lang['no_url_connect']);
		}

		@fputs($fsock, "GET $base_get HTTP/1.1\r\n");
		@fputs($fsock, "HOST: " . $url_ary[2] . "\r\n");
		@fputs($fsock, "Connection: close\r\n\r\n");

		unset($avatar_data);
		$max_length = $auction_pic_config['auction_offer_server_picture_size'];
		while( !@feof($fsock) )
		{
			$avatar_data .= @fread($fsock, 32768);
		}
		@fclose($fsock);

		if (!preg_match('#Content-Length\: ([0-9]+)[^ /][\s]+#i', $avatar_data, $file_data1) || !preg_match('#Content-Type\: image/[x\-]*([a-z]+)[\s]+#i', $avatar_data, $file_data2))
		{
			message_die(GENERAL_ERROR, $lang['no_url_connect']);
		}

		$avatar_filesize = $file_data1[1]; 
		$avatar_filetype = $file_data2[1]; 
		$url_upload = 1;
	}
					
}


 /* dont change $pic_upload = 1 !!!! it tells the program that there was an upload and that it can insert into the database */
$pic_upload = 1;
// set max size for gif convertion
$max_gif_file_to_convert = $auction_config_pic['auction_gif_max_size'];

// now we set the pic language file
	$language = $board_config['default_lang'];
	if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_auction_pic.'.$phpEx) )
	{
		$language = 'english';
	}
	include($phpbb_root_path . 'language/lang_' . $language . '/lang_auction_pic.' . $phpEx);


/* first we check GD ... 3 is auto-gd	*/
					
	$gd = $auction_config_pic['gd_version'];
	if ($gd == 3)
	{
		$gd = (function_exists('imagecopyresampled')) ? 2 : ((function_exists('imagecopyresized'))? 1 : 0   );
	}
	$auction_config_pic['gd_version'] = $gd;
	/* end auto setting check */


	if($url_upload == 1)
	{
		$filetype = 'image/'.$file_data2[1];
		$filesize = $file_data1[1]; 
		// $filetmp = $avatar_data;
	}
	else
	{
		$filetype = $HTTP_POST_FILES['auction_offer_picture_file']['type'];
		$filesize = $HTTP_POST_FILES['auction_offer_picture_file']['size'];
		$filetmp = $HTTP_POST_FILES['auction_offer_picture_file']['tmp_name'];
	}
	/* --------------------------------
			 Prepare variables
	   -------------------------------- */

	$pic_time = time();
	$pic_user_ip = $userdata['session_ip'];
	$recompress = 0;
	$pic_subtype = 'normal';


	/* --------------------------------
		    Check file type
	   -------------------------------- */

	switch ($filetype)
	{
		case 'image/jpeg':
		case 'image/jpg':
		case 'image/pjpeg':
			if ($auction_config_pic['auction_offer_picture_jpeg_allow'] == 0)
			{
				message_die(GENERAL_ERROR, $lang['auction_offer_picture_filetype_jpg_not_allowed']);
			}
			$pic_filetype = '.jpg';
			break;

		case 'image/png':
		case 'image/x-png':
			if ($auction_config_pic['auction_offer_picture_png_allow'] == 0)
			{
				message_die(GENERAL_ERROR, $lang['auction_offer_picture_filetype_png_not_allowed']);
			}
			
			if( ($auction_config_pic['png_convert'] == 1) AND ($auction_config_pic['gd_version'] > 0))
			{
				$pic_filetype = '.jpg';
				$pic_subtype = 'png';
			}
			else
			{
				$pic_filetype = '.png';
			}
			break;

		case 'image/gif':
			if ($auction_config_pic['auction_offer_picture_gif_allow'] == 0)
			{
				message_die(GENERAL_ERROR, $lang['auction_offer_picture_filetype_gif_not_allowed']);
			}
			if( ($auction_config_pic['gif_convert'] == 1) AND ($auction_config_pic['gd_version'] > 0))
			{
				$pic_filetype = '.jpg';
				$pic_subtype = 'gif';
			}
			else
			{
				$pic_filetype = '.gif';
			}
			break;
		default:
			message_die(GENERAL_ERROR, $lang['auction_offer_picture_filetype_not_allowed'] . " <b>" . $filetype . " !!!</b>");
	}

	/* Set the gdtype for the database - when retrieving the picture we will know if its a resized pic or not */

	if( ($auction_config_pic['gd_version'] == 0) OR ($pic_filetype == '.gif') )
	{
		$gd_type = 0;
	}
	else
	{
		$gd_type = 1;
	}

	/* If gd is enabled and the pic is not a real gif  */
	if (($auction_config_pic['gd_version'] > 0 ) AND ($pic_filetype != '.gif'))
	{
		/* Check the filesize */
		if( $filesize > $auction_config_pic['auction_offer_server_picture_size'] )
		{
			message_die(GENERAL_MESSAGE, $lang['auction_picture_filesize_to_big'] . "<br />" . $lang['file_exceeds'] . $auction_config_pic['auction_offer_server_picture_size'] . $lang['bytes']);
		}
		else if ($pic_subtype == 'gif')
		{
			if ($filesize > $max_gif_file_to_convert) // this is set on top of this page 
			{
				message_die(GENERAL_MESSAGE, $lang['auction_picture_filesize_to_big'] . "<br />" . $lang['gif_file_exceeds'] . $max_gif_file_to_convert . $lang['bytes'] . $lang['gif_file_exceeds2'] . $filesize . " ".$lang['bytes']);
			}
		}
			
		if ($filesize > $auction_config_pic['auction_offer_picture_size_allow'])
		{
			// file will be recompressed
			$recompress = 1;
		}
	}
	else // gd is not enabled or the picture is a real gif and treated as if there were no gd
	{
		if ($filesize > $auction_config_pic['auction_offer_picture_size_allow'])
		{
			message_die(GENERAL_MESSAGE, $lang['auction_picture_filesize_to_big'] . "<br />" . $lang['file_exceeds'] . $auction_config_pic['auction_offer_picture_size_allow'] . $lang['bytes']);
		}

	}

/* picture is passed the size checking so we can generate the filename */


	srand((double)microtime()*1000000);	// for older than version 4.2.0 of PHP

	do
	{
		$pic_filename = md5(uniqid(rand())) . $pic_filetype;
	}
	while( file_exists($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename) );

	$pic_thumbnail = $pic_filename;
					
/* We now move this file to the upload directory */

/* if its an url upload */
	if($url_upload == 1)
	{
		$filetmp =  AUCTION_PICTURE_UPLOAD_PATH . 'tmp/'.$pic_filename;
		$avatar_data = substr($avatar_data, strlen($avatar_data) - $avatar_filesize, $avatar_filesize);
			$fptr = @fopen($filetmp, 'wb');
			$bytes_written = @fwrite($fptr, $avatar_data, $filesize);
			@fclose($fptr);
			@chmod($filetmp, 0777);

	}


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
	// if it is a gif or a png to convert
	if($pic_subtype == 'gif') // we dont have to check for gd because if gd=0 pic_subtype would be "normal"
	{
		include_once( $phpbb_root_path . 'auction/graphic_files/phpthumb.gif.php');
		$src = gif_loadFileToGDimageResource($filetmp);

		@imagejpeg($src, $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename, 80);
		@unlink($filetmp);
		Imagedestroy($src);
	}
	else if ($pic_subtype == 'png')
	{
		$move_file($filetmp, $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  .  $pic_filename);
		@chmod($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  .  $pic_filename, 0777);
		$read_function = 'imagecreatefrompng';
		$src = @$read_function($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . $pic_filename);
		@unlink($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
		@imagejpeg($src, $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename, 80);
		Imagedestroy($src);
	}
	else
	{
		$move_file($filetmp, $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . $pic_filename);
		@unlink($filetmp);
	}

	@chmod($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . $pic_filename, 0777);

	/* we have successfully uploaded the picture, now we check its size this time with width and height */
					
	$pic_size = getimagesize($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);

	$pic_width = $pic_size[0];
	$pic_height = $pic_size[1];
					
/* first check for corrupt upload (some jpgs created with arles have this problem) we can only do this with enabled GD */

	if ( (($pic_width == 0) or ($pic_width =="")) OR (($pic_height == 0) or ($pic_height =="")) )
	{
		//now recompress if gd is enabled
		if( ($pic_filetype != '.gif') AND ($auction_config_pic['gd_version'] > 0) )
		{
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

			$temp_src = @$read_function($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . $pic_filename);
			@unlink($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
			if (!$temp_src)
			{
				$gd_errored = TRUE;
				$temp_pic_thumbnail = '';
			}
			else
			{
				$temp_thumbnail = $temp_src;
			}

			if (!$gd_errored)
			{
				$temp_pic_thumbnail = $pic_filename;
				// Write to disk
				switch ($pic_filetype)
				{
					case '.jpg':
						@imagejpeg($temp_thumbnail, $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $temp_pic_thumbnail, $auction_config_pic['offer_auction_pic_quality']);
						break;
					case '.png':
						@imagepng($temp_thumbnail, $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $temp_pic_thumbnail);
						break;
				}

				@chmod($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $temp_pic_thumbnail, 0777);

			} // End IF $gd_errored
		} // end if !gif and gd ok
		else
		{
			// the file is a gif or gd is not enabled . It is corrupt so we can do nothing but reject it.
			@unlink($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
			message_die(GENERAL_ERROR, $lang['Upload_image_not_conform']);
		}

		//get picture size again if it was corrupt we had the wrong size before
		$pic_size = getimagesize($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
		$pic_width = $pic_size[0];
		$pic_height = $pic_size[1];
	}
				
	/* now hopefully we have the size of pic... we check again and delete it if its a non standard type!
	 OK, ok... we dont need it because we have the "else" just above.. but i find it better. */
	if ( ($pic_width == 0) or ($pic_width =="") )
	{
		@unlink($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
		message_die(GENERAL_ERROR, $lang['Upload_image_not_conform']);
	}
	else if ( ($pic_height == 0) or ($pic_height =="") )
	{
		@unlink($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
		message_die(GENERAL_ERROR, $lang['Upload_image_not_conform']);
	}

	/* now we check width and height if it is not larger or wider than the config data we set . 
	This one is a bummer because i was so stupid to set a max width AND max height.. but it is feasable...*/

	if (($pic_width > $auction_config_pic['auction_offer_pic_max_width']) or ($pic_height > $auction_config_pic['auction_offer_pic_max_height']))
	{
		// its a gif or GD is not enabled.. we have to reject it.
		if(($pic_filetype == '.gif') OR ($auction_config_pic['gd_version'] == 0))
		{
			@unlink($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
			message_die(GENERAL_ERROR, $lang['auction_picture_filesize_to_big']. "<br />". $lang['max_allowed_size']. $auction_config_pic['auction_offer_pic_max_width'] . " x " . $auction_config_pic['auction_offer_pic_max_height']);

		}
		else
		{
			// it is a jpg, a png and gd is enabled so we will recompress it
			$recompress = 1;
		}
	}
				
	// if the picture exceeds our specified sizes (width, height or filesize) and gd is enabled we recompress it. Else it must allready have been rejected.
	if( $recompress == 1 )
	{
		// if we are here GD is enabled
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

		$src = @$read_function($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH  . $pic_filename);

		if (!$src)
		{
			$gd_errored = TRUE;
		}
		// we check the height and width again, this time so we can recalculate the new sizes
		// if both are bigger
		if (($pic_width  > $auction_config_pic['auction_offer_pic_max_width']) AND ($pic_height > $auction_config_pic['auction_offer_pic_max_height']))
		{
			// its landscape				
			if ($pic_width > $pic_height)
			{
				$new_width = (int)($auction_config_pic['auction_offer_pic_max_width']);
				$new_height = (int)($auction_config_pic['auction_offer_pic_max_width'] * ($pic_height/$pic_width));
			}
			else
			{
				// its a portrait
				$new_height = (int)($auction_config_pic['auction_offer_pic_max_height']);
				$new_width = (int)($auction_config_pic['auction_offer_pic_max_height'] * ($pic_width/$pic_height));
			}
						
		}
		// only height OR width is bigger than maximum allowed
		else if(($pic_width  > $auction_config_pic['auction_offer_pic_max_width']) OR ($pic_height > $auction_config_pic['auction_offer_pic_max_height']))
		{
			// its wider than allowed
			if($pic_width  > $auction_config_pic['auction_offer_pic_max_width'])
			{
				// its landscape
				if ($pic_width > $pic_height)
				{
					$new_width = (int)($auction_config_pic['auction_offer_pic_max_width']);
					$new_height = (int)($auction_config_pic['auction_offer_pic_max_width'] * ($pic_height/$pic_width));
				}
				else
				{
					// its portrait
					$new_height = (int)($auction_config_pic['auction_offer_pic_max_width'] * ($pic_height/$pic_width));
					$new_width = (int)($auction_config_pic['auction_offer_pic_max_width']);
				}
			}
			// its higher than allowed
			else if($pic_height > $auction_config_pic['auction_offer_pic_max_height'])
			{
				// its landscape
				if ($pic_width > $pic_height)
				{
					$new_width = (int)($auction_config_pic['auction_offer_pic_max_height'] * ($pic_width/$pic_height));
					$new_height = (int)($auction_config_pic['auction_offer_pic_max_height']);
				}
				else
				{
					//its portrait
					$new_height = (int)($auction_config_pic['auction_offer_pic_max_height']);
					$new_width = (int)($auction_config_pic['auction_offer_pic_max_height'] * ($pic_width/$pic_height));
				}
			}
		}
		else
		{	
			// the height and width were ok, but filesize was to big... so we leave the size as it is
			$new_width = $pic_width;
			$new_height = $pic_height;
		}
		
		$new_pic = ($auction_config_pic['gd_version'] == 1) ? @imagecreate($new_width, $new_height) : @imagecreatetruecolor($new_width, $new_height);

		$resize_function = ($auction_config_pic['gd_version'] == 1) ? 'imagecopyresized' : 'imagecopyresampled';

		@$resize_function($new_pic, $src, 0, 0, 0, 0, $new_width, $new_height, $pic_width, $pic_height);

		if (!$gd_errored)
		{
			// overwrite old image
			@unlink($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
			switch ($pic_filetype)
			{
				case '.jpg':
					@imagejpeg($new_pic, $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename, $auction_config_pic['offer_auction_pic_quality']);
					break;
				case '.png':
					@imagepng($new_pic, $phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
					break;
			}
			@chmod($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename, 0777);
			$pic_width = $new_width;
			$pic_height = $new_height;
		} // End IF $gd_errored
		else
		{
			@unlink($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
			message_die(GENERAL_ERROR, $lang['auction_picture_filesize_to_big']. "<br />". $lang['max_allowed_size']. $auction_config_pic['auction_offer_pic_max_width'] . " x " . $auction_config_pic['auction_offer_pic_max_height']);
		}
	}

	/* If we are here the uploaded image is ok the only thing we still have todo is calculate the size of the main picture so we can store it in the database. It will only be used if GD is not enabled so that the main image is resized proportionally. FAQ: when is the resized cached image created? Answer: When someone loads the page for the first time! */
	
	if (($pic_width > $auction_config_pic['auction_offer_main_size']) or ($pic_height > $auction_config_pic['auction_offer_main_size']))
	{
		// the picture exceeds the max-size of the main-offer picture
		// its a landscape
		if ($pic_width > $pic_height)
		{
			$pic_main_width = (int)($auction_config_pic['auction_offer_main_size']);
			$pic_main_height = (int)($auction_config_pic['auction_offer_main_size'] * ($pic_height/$pic_width));
		}
		else
		{
			$pic_main_height = (int)($auction_config_pic['auction_offer_main_size']);
			$pic_main_width = (int)($auction_config_pic['auction_offer_main_size'] * ($pic_width/$pic_height));	
		}
	}
	else
	{
		// the picture is smaller than the defined size of the main-offer picture
		$pic_main_height = (int)($pic_height);
		$pic_main_width = (int)($pic_width);
	}

?>