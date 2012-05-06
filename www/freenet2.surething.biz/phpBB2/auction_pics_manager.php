<?php
/***************************************************************************
 *                          auction_pics_manager.php
 *                            -------------------
 *   begin                : May 2004
 *   copyright            : (C) Mr.Luc
 *   email                : llg@gmx.at
 *   Last update          :   July 2004 - FR
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This hack is released under the GPL License. 
 *   This hack can be freely used, but not distributed, without permission. 
 *   Intellectual Property is retained by the author listed above. 
 *
 ***************************************************************************/

     define('IN_PHPBB', true);
     $phpbb_root_path = './';
     include_once($phpbb_root_path . 'extension.inc');
     include_once($phpbb_root_path . 'common.'.$phpEx);
     include_once($phpbb_root_path . 'auction/auction_common.php');

     // Start session management
     $userdata = session_pagestart($user_ip, AUCTION_PIC_MANAGER);
     init_userprefs($userdata);
     // End session management

     // Start Include language file
     $language = $board_config['default_lang'];
     if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_auction.'.$phpEx) )
          {
               $language = 'english';
          }
     include($phpbb_root_path . 'language/lang_' . $language . '/lang_auction.' . $phpEx);
     include($phpbb_root_path . 'language/lang_' . $language . '/lang_auction_pic.' . $phpEx);
     // end include language file

     if( isset($HTTP_GET_VARS['ao']) )
          {
	        $offer_id = intval($HTTP_GET_VARS['ao']);
	        $offer_id = htmlspecialchars($offer_id);
          }
     elseif( isset($HTTP_POST_VARS['ao']) )
          {
	       $offer_id = intval($HTTP_POST_VARS['ao']);
	       $offer_id = htmlspecialchars($offer_id);
          }
     else
          {
	        message_die(GENERAL_ERROR, $lang['auction_offer_does_not_exist']);
          }


     $user_id = $userdata['user_id'];
     
     if( ($user_id < 1) OR (!$userdata['session_logged_in']) )
          {
	       redirect(append_sid("login.$phpEx?redirect=auction_pics_manager.$phpEx?ao=$offer_id"));
          }

     // get configuration
     $auction_pic_config = init_auction_config_pic();


// we set the height of the popup if url upload is enabled or not

if($auction_pic_config['allow_url_upload'] == 1)
{
	$up_pop_height = 350;
}
else
{
	$up_pop_height = 250;
}

// check auto_gd AND if gd is present. No gallery without GD
if($auction_pic_config['gd_version'] > 0)
{
	$my_gd = (function_exists('imagecopyresampled')) ? 2 : ((function_exists('imagecopyresized'))? 1 : 0   );
	/*********************************************************
	if gd is not enabled ( of falsely enabled !!!) we do not allow the user to use the gallery!!! the images will be to big for the page to load. So remove the following lines at your own risk. No support if you do it
	**********************************************************/
	if(($auction_pic_config['allow_thumb_gallery'] == 1) AND ($my_gd == 0))
	{
		$auction_pic_config['allow_thumb_gallery'] = 0;
	}

	if($auction_pic_config['gd_version']  == 3)
	{
		$auction_pic_config['gd_version']  = $my_gd;
	}
}
// check authorization
$auth = 0;
// first we get the user_id from the auction

     // Grab offer data
     $sql = "SELECT FK_auction_offer_user_id,
                    FK_auction_offer_room_id,
                    auction_offer_title,
                    auction_offer_time_stop,
                    auction_offer_paid,
                    FK_auction_offer_last_bid_user_id
	     FROM " . AUCTION_OFFER_TABLE . "
	     WHERE PK_auction_offer_id = '" . $offer_id . "'";

     if( !($result = $db->sql_query($sql)) )
          {
	       message_die(GENERAL_ERROR, 'Could not query offer', '', __LINE__, __FILE__, $sql);
          } // End if
 
     $auction_offer_row = $db->sql_fetchrow($result);
     $db->sql_freeresult($result);  // Please check!!! I don't know if we need a freeresult here!!!!
     // Does auction exist with this id ?
     if($auction_offer_row['auction_offer_title']=="" )
          {
	       message_die(GENERAL_MESSAGE, $lang['auction_offer_does_not_exist']);
          }

$auction_room = $auction_offer_row['FK_auction_offer_room_id'];
/*************************************************************
**** there are 5 authorization levels.   *********************
**** 4 = admin or mod if mod-control is activated. ***********
**** 3 = mod with control is not active. he can lock only ****
**** 2 = user with allow editing active **********************
**** 1 = user with allow editing until first bid *************
**** 0 = No editing possible *********************************
**************************************************************/
// first we check the mode
if( isset($HTTP_GET_VARS['mode']) )
{
	$mode = $HTTP_GET_VARS['mode'];
}
elseif($HTTP_POST_VARS['mode'])
{
	$mode = $HTTP_POST_VARS['mode'];
}
else
{
	$mode = 'display';
}
if(($mode == 'upload') OR ($mode == 'do_upload') OR ($mode == 'replace') OR ($mode == 'do_replace'))
{
	$gen_simple_header = TRUE;
}


if(($userdata['user_level'] == ADMIN) OR (($userdata['user_level'] == MOD) AND ($auction_pic_config['auction_offer_pic_approval_mod'] == 1)))
{
	$auth = 4;
	// admin will be able to impersonate a user when he uploads
	$user_id = $auction_offer_row['FK_auction_offer_user_id'];
}
elseif(($userdata['user_level'] == MOD) AND ($auction_pic_config['auction_offer_pic_approval_mod'] == 0))
{
	$auth = 3;
}
elseif($user_id == $auction_offer_row['FK_auction_offer_user_id'])
{
	/* now we check if editig is authorized */
	
	if( $auction_pic_config['edit_level'] == 2)
	{
		// user has control
		$auth = 2;
	}
	elseif(($auction_pic_config['edit_level'] == 1) AND ($auction_offer_row['FK_auction_offer_last_bid_user_id'] == 0))
	{
		$auth = 1;
	}
	else
	{
		$auth = 0;
		message_die(GENERAL_ERROR, $lang['auction_not_authorized_to_edit_picture']);
	}
}
else
{
	$auth = 0;
	message_die(GENERAL_ERROR, $lang['auction_not_authorized_to_edit_picture']);
}
// allowed number of pics
if($auction_pic_config['auction_offer_pictures_allow'] == 1)
{
	if($auction_pic_config['allow_thumb_gallery'] == 1)
	{
		$amount_of_allowed_pics = $auction_pic_config['amount_of_thumbs'] + 1;
	}
	else
	{
		$amount_of_allowed_pics = 1;
	}
}
else
{
	$amount_of_allowed_pics = 0;
	message_die(GENERAL_ERROR, $lang['auction_not_authorized_to_edit_picture']);
}
// now we get the amount of empty allowed picture slots
// to do so we count the amount of existing pictures

     $sql = "SELECT COUNT(pic_id) AS count
             FROM ". AUCTION_IMAGE_TABLE ."
             WHERE pic_auction_id = '" . $offer_id . "' ";
             
     if( !($result = $db->sql_query($sql)) )
          {
	       message_die(GENERAL_ERROR, 'Could not count pictures', '', __LINE__, __FILE__, $sql);
          }

     $howmany = $db->sql_fetchrow($result);
     $db->sql_freeresult($result);  // Please check!!! I don't know if we need a freeresult here!!!!
     $current_pics = $howmany['count'];

$empty_slots_left = $amount_of_allowed_pics - $current_pics;

/* we auto-synchronize the pictures...folders. we do this here because we only need it if the user uploads more than one pic - we dont touch the main picture */

if($empty_slots_left < 0)
{
	// get the pics
	$sql = "SELECT *
			FROM ". AUCTION_IMAGE_TABLE ."
			WHERE pic_auction_id = '" . $offer_id . "' AND pic_main <> 1 ";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get pic info', '', __LINE__, __FILE__, $sql);
	}

	$del_row = array();
	while( $row = $db->sql_fetchrow($result) )
	{
		$del_row[] = $row;
	}
	$db->sql_freeresult($result);  // Please check!!! I don't know if we need a freeresult here!!!!

	// we count the pics again
	$pic_num = count($del_row);

	for ($i = 1; $i <= $pic_num; $i++)
	{
		
		if($i > $auction_pic_config['amount_of_thumbs'])
		{
			$j = $i-1;
			$del_id = $del_row[$j]['pic_id'];
			$pic_filename = $del_row[$j]['pic_filename'];

			// check if file exists and delete it from ALL caches (don't forget watermark caches)
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MINI_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_MINI_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MAIN_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_MAIN_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_WATERMARK_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_WATERMARK_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MAIN_WATERMARK_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_MAIN_WATERMARK_PATH . $pic_filename);
			}
			// finally delete it from main upload dir
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
			}

			// And from database
			$sql = "DELETE FROM " . AUCTION_IMAGE_TABLE . "
				WHERE pic_id = '$del_id'";

			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete picture $del_id. Please try again.", "", __LINE__, __FILE__, $sql);
			} 
		}
	}
}

/* auto-synchronize end */

$pic_upload = 0;

if($mode == 'do_upload')
{
	if ( $HTTP_POST_FILES['auction_offer_picture_file']['size'] > 0 )
	{
		if($empty_slots_left > 0)
		{
			// If the include does not exist we exit
			if(!file_exists($phpbb_root_path . 'auction/graphic_files/auction_pic_upload.' . $phpEx))
			{
				message_die(GENERAL_ERROR, $lang['auction_pic_upload_missing']);
			}
			else
			{

				// we fetch the image parameters
                                $auction_config_pic = $auction_pic_config;
				// we include the upload file
				// all the upload work is done there
				include($phpbb_root_path . 'auction/graphic_files/auction_pic_upload.' . $phpEx);
			}

			$pic_main = ($current_pics == 0) ? 1 : 0 ;

			if(($userdata['user_level'] == ADMIN) OR (($userdata['user_level'] == MOD) AND ($auction_pic_config['auction_offer_pic_approval_mod'] == 1)))
			{
				$pic_approval = 0;
			}
			else
			{
				$pic_approval = $auction_pic_config['auction_offer_pic_approval_admin'];
			}
						
			
			if ($pic_upload == 1) 
			{
				$sql = "INSERT INTO
					" . AUCTION_IMAGE_TABLE . " (pic_filename, pic_auction_id, pic_user_id, pic_time, pic_cat, pic_room,  pic_approval, pic_main, pic_width, pic_height, pic_main_width, pic_main_height, pic_user_ip, pic_gd_type)
					VALUES ('" . $pic_filename . "', " . $offer_id . ", " . $user_id . "," . $pic_time . "," . $auction_room . "," . $auction_room . "," . $pic_approval . "," . $pic_main . ", " . $pic_width . "," . $pic_height . "," . $pic_main_width . ", " . $pic_main_height . ", '" . $pic_user_ip . "', " . $gd_type . ")";

				 if( !($result = $db->sql_query($sql)) )
				{
				   message_die(GENERAL_ERROR, 'Could not insert main image', '', __LINE__, __FILE__, $sql);
				}

			}
						
			/* if we are here means that we are authentified
			now we check if its an image upload */
			if( isset($HTTP_POST_VARS['win_close']) )
			{
				$win_close = $HTTP_POST_VARS['win_close'];
				if($win_close == 1)
				{
					echo '<html><head><title>' . $lang['auction_upload_ok'] . '</title></head><body onLoad="window.opener.location.reload(); window.close();" ></body></html>'; flush();	exit;
				}
			}
		}
		else
		{
			message_die(GENERAL_ERROR, $lang['auction_upload_limit_reached']);
		}
	}
	elseif(($HTTP_POST_VARS['auction_offer_url_file'] != "") AND ($HTTP_POST_VARS['auction_offer_url_file'] != "http://"))
	{
		$avatar_filename = $HTTP_POST_VARS['auction_offer_url_file'];
		$error = false;
		if ( preg_match('/^(http:\/\/)?([\w\-\.]+)\:?([0-9]*)\/(.*)$/', $avatar_filename, $url_ary) )
		{
			if ( empty($url_ary[4]) )
			{
				message_die(GENERAL_ERROR, "incomplete or wrong url!");
			}

			$base_get = '/' . $url_ary[4];
			$port = ( !empty($url_ary[3]) ) ? $url_ary[3] : 80;

			if ( !($fsock = @fsockopen($url_ary[2], $port, $errno, $errstr)) )
			{
				message_die(GENERAL_ERROR, $lang['auction_no_url_connection']);
			}

			@fputs($fsock, "GET $base_get HTTP/1.1\r\n");
			@fputs($fsock, "HOST: " . $url_ary[2] . "\r\n");
			@fputs($fsock, "Connection: close\r\n\r\n");

			unset($avatar_data);
			$max_length = 32768; // 
			while( !@feof($fsock) )
			{
				$avatar_data .= @fread($fsock, $max_length);
			}
			@fclose($fsock);

			if (!preg_match('#Content-Length\: ([0-9]+)[^ /][\s]+#i', $avatar_data, $file_data1) || !preg_match('#Content-Type\: image/[x\-]*([a-z]+)[\s]+#i', $avatar_data, $file_data2))
			{
					message_die(GENERAL_ERROR, "No file data");
			}

			$avatar_filesize = $file_data1[1]; 
			$avatar_filetype = $file_data2[1]; 
			$url_upload = 1;

			// If the include does not exist we exit
			if(!file_exists($phpbb_root_path . 'auction/graphic_files/auction_pic_upload.' . $phpEx))
			{
				message_die(GENERAL_ERROR, $lang['auction_pic_upload_missing']);
			}
			else
			{
				// we fetch the image parameters
				$auction_config_pic = $auction_pic_config;
				// $auction_config_pic = init_auction_config_pic();
				// we include the upload file
				// all the upload work is done there
				include($phpbb_root_path . 'auction/graphic_files/auction_pic_upload.' . $phpEx);
			}

			$pic_main = ($current_pics == 0) ? 1 : 0 ;

			if(($userdata['user_level'] == ADMIN) OR (($userdata['user_level'] == MOD) AND ($auction_pic_config['auction_offer_pic_approval_mod'] == 1)))
			{
				$pic_approval = 0;
			}
			else
			{
				$pic_approval = $auction_pic_config['auction_offer_pic_approval_admin'];
			}
						
			
			if ($pic_upload == 1) 
			{
				$sql = "INSERT INTO " . AUCTION_IMAGE_TABLE . "
                                             (pic_filename,
                                              pic_auction_id,
                                              pic_user_id,
                                              pic_time,
                                              pic_cat,
                                              pic_room,
                                              pic_approval,
                                              pic_main,
                                              pic_width,
                                              pic_height,
                                              pic_main_width,
                                              pic_main_height,
                                              pic_user_ip,
                                              pic_gd_type)
					VALUES ('" . $pic_filename . "',
                                                 " . $offer_id . ",
                                                 " . $user_id . ",
                                                 " . $pic_time . ",
                                                 " . $auction_room . ",
                                                 " . $auction_room . ",
                                                 " . $pic_approval . ",
                                                 " . $pic_main . ",
                                                 " . $pic_width . ",
                                                 " . $pic_height . ",
                                                 " . $pic_main_width . ",
                                                 " . $pic_main_height . ",
                                                '" . $pic_user_ip . "',
                                                 " . $gd_type . ")";

				 if( !($result = $db->sql_query($sql)) )
				{
				   message_die(GENERAL_ERROR, 'Could not insert main image', '', __LINE__, __FILE__, $sql);
				}

			}
						
			/* if we are here means that we are authentified
			now we check if its an image upload */
			if( isset($HTTP_POST_VARS['win_close']) )
			{
				$win_close = $HTTP_POST_VARS['win_close'];
				if($win_close == 1)
				{
					echo '<html><head><title>' . $lang['auction_upload_ok'] . '</title></head><body onLoad="window.opener.location.reload(); window.close();" ></body></html>'; flush();	exit;
				}
			}

//	print "avatar_filetype=".$avatar_filetype.'<br />avatar_filesize = '.$avatar_filesize; exit;


		/*	if ( !$error && $avatar_filesize > 0 && $avatar_filesize < $board_config['avatar_filesize'] )
			{
				
				$fptr = @fopen($tmp_filename, 'wb');
				$bytes_written = @fwrite($fptr, $avatar_data, $avatar_filesize);
				@fclose($fptr);

			}
*/
		}
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['auction_image_empty'] );
	}
}

if($mode == 'do_replace')
{
// the pic_id_to replace must be post here
	if( isset($HTTP_POST_VARS['pic_id']) )
	{
		$pic_id = intval($HTTP_POST_VARS['pic_id']);
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['auction_image_empty']);
	}

	if ( $HTTP_POST_FILES['auction_offer_picture_file']['size'] > 0 )
	{
		$empty_slots_left = 1;
		if($empty_slots_left > 0)
		{
			// If the include does not exist we exit
			if(!file_exists($phpbb_root_path . 'auction/graphic_files/auction_pic_upload.' . $phpEx))
			{
				message_die(GENERAL_ERROR, $lang['auction_pic_upload_missing']);
			}
			else
			{
				// we fetch the image parameters
				//	$auction_config_pic = init_auction_config_pic();
				$auction_config_pic = $auction_pic_config;
				// we include the upload file
				// all the upload work is done there
				include($phpbb_root_path . 'auction/graphic_files/auction_pic_upload.' . $phpEx);
			}

			$pic_main = ($current_pics == 0) ? 1 : 0 ;

			if(($userdata['user_level'] == ADMIN) OR (($userdata['user_level'] == MOD) AND ($auction_pic_config['auction_offer_pic_approval_mod'] == 1)))
			{
				$pic_approval = 0;
			}
			else
			{
				$pic_approval = $auction_pic_config['auction_offer_pic_approval_admin'];
			}
						
			
			if ($pic_upload == 1) 
			{
				$sql = "INSERT INTO " . AUCTION_IMAGE_TABLE . " (pic_filename, pic_auction_id, pic_user_id, pic_time, pic_cat, pic_room,  pic_approval, pic_main, pic_width, pic_height, pic_main_width, pic_main_height, pic_user_ip, pic_gd_type)
					VALUES ('" . $pic_filename . "', " . $offer_id . ", " . $user_id . "," . $pic_time . "," . $auction_room . "," . $auction_room . "," . $pic_approval . "," . $pic_main . ", " . $pic_width . "," . $pic_height . "," . $pic_main_width . ", " . $pic_main_height . ", '" . $pic_user_ip . "', " . $gd_type . ")";

				 if( !($result = $db->sql_query($sql)) )
				{
				   message_die(GENERAL_ERROR, 'Could not insert main image', '', __LINE__, __FILE__, $sql);
				}

			}
	
				// delete start		
 // here we delete the old picture and all its instances @@
			$sql = "SELECT *
                                FROM ". AUCTION_IMAGE_TABLE ."
	                        WHERE pic_id = '$pic_id'";
	                        
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not select pic information', '', __LINE__, __FILE__, $sql);
			}


			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			// now get the filename
			$pic_filename = $row['pic_filename'];
			// check if file exists and delete it from ALL caches (don't forget watermark caches)
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MINI_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_MINI_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MAIN_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_MAIN_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_WATERMARK_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_WATERMARK_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MAIN_WATERMARK_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_MAIN_WATERMARK_PATH . $pic_filename);
			}
			// finally delete it from main upload dir
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
			}

			// And from database
			$sql = "DELETE FROM " . AUCTION_IMAGE_TABLE . "
				WHERE pic_id = '$pic_id'";

			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete picture. Please try again.", "", __LINE__, __FILE__, $sql);
			} 


// delete end
			/* if we are here means that we are authentified
			now we check if its an image upload */
			if( isset($HTTP_POST_VARS['win_close']) )
			{
				$win_close = $HTTP_POST_VARS['win_close'];
				if($win_close == 1)
				{
					echo '<html><head><title>Upload OK!</title></head><body onLoad="window.opener.location.reload(); window.close();" ></body></html>'; flush();	exit;
				}
			}
		}
		else
		{
			message_die(GENERAL_ERROR, $lang['auction_upload_limit_reached']);
		}
	}
	elseif(($HTTP_POST_VARS['auction_offer_url_file'] != "") AND ($HTTP_POST_VARS['auction_offer_url_file'] != "http://"))
	{
		$avatar_filename = $HTTP_POST_VARS['auction_offer_url_file'];
		$error = false;
		if ( preg_match('/^(http:\/\/)?([\w\-\.]+)\:?([0-9]*)\/(.*)$/', $avatar_filename, $url_ary) )
		{
			if ( empty($url_ary[4]) )
			{
				message_die(GENERAL_ERROR, "incomplete or wrong url!");
			}

			$base_get = '/' . $url_ary[4];
			$port = ( !empty($url_ary[3]) ) ? $url_ary[3] : 80;

			if ( !($fsock = @fsockopen($url_ary[2], $port, $errno, $errstr)) )
			{
				message_die(GENERAL_ERROR, "Nur URL connection!");
			}

			@fputs($fsock, "GET $base_get HTTP/1.1\r\n");
			@fputs($fsock, "HOST: " . $url_ary[2] . "\r\n");
			@fputs($fsock, "Connection: close\r\n\r\n");

			unset($avatar_data);
			while( !@feof($fsock) )
			{
				$avatar_data .= @fread($fsock, "300000");
			}
			@fclose($fsock);

			if (!preg_match('#Content-Length\: ([0-9]+)[^ /][\s]+#i', $avatar_data, $file_data1) || !preg_match('#Content-Type\: image/[x\-]*([a-z]+)[\s]+#i', $avatar_data, $file_data2))
			{
					message_die(GENERAL_ERROR, "No file data");
			}

			$avatar_filesize = $file_data1[1]; 
			$avatar_filetype = $file_data2[1]; 
			$url_upload = 1;






			// If the include does not exist we exit
			if(!file_exists($phpbb_root_path . 'auction/graphic_files/auction_pic_upload.' . $phpEx))
			{
				message_die(GENERAL_ERROR, $lang['auction_pic_upload_missing']);
			}
			else
			{
				// we fetch the image parameters
				// $auction_config_pic = init_auction_config_pic();
$auction_config_pic = $auction_pic_config;
				// we include the upload file
				// all the upload work is done there
				include($phpbb_root_path . 'auction/graphic_files/auction_pic_upload.' . $phpEx);
			}

			$pic_main = ($current_pics == 0) ? 1 : 0 ;

			if(($userdata['user_level'] == ADMIN) OR (($userdata['user_level'] == MOD) AND ($auction_pic_config['auction_offer_pic_approval_mod'] == 1)))
			{
				$pic_approval = 0;
			}
			else
			{
				$pic_approval = $auction_pic_config['auction_offer_pic_approval_admin'];
			}
						
			
			if ($pic_upload == 1) 
			{
				$sql = "INSERT INTO
					" . AUCTION_IMAGE_TABLE . " (pic_filename, pic_auction_id, pic_user_id, pic_time, pic_cat, pic_room,  pic_approval, pic_main, pic_width, pic_height, pic_main_width, pic_main_height, pic_user_ip, pic_gd_type)
					VALUES ('" . $pic_filename . "', " . $offer_id . ", " . $user_id . "," . $pic_time . "," . $auction_room . "," . $auction_room . "," . $pic_approval . "," . $pic_main . ", " . $pic_width . "," . $pic_height . "," . $pic_main_width . ", " . $pic_main_height . ", '" . $pic_user_ip . "', " . $gd_type . ")";

				 if( !($result = $db->sql_query($sql)) )
				{
				   message_die(GENERAL_ERROR, 'Could not insert main image', '', __LINE__, __FILE__, $sql);
				}
			}
// insert delete

 // here we delete the old picture and all its instances @@
			$sql = "SELECT * FROM ". AUCTION_IMAGE_TABLE ."
					
					WHERE pic_id = '$pic_id'";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not select pic information', '', __LINE__, __FILE__, $sql);
			}


			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			// now get the filename
			$pic_filename = $row['pic_filename'];
			// check if file exists and delete it from ALL caches (don't forget watermark caches)
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MINI_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_MINI_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MAIN_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_MAIN_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_WATERMARK_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_WATERMARK_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MAIN_WATERMARK_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_MAIN_WATERMARK_PATH . $pic_filename);
			}
			// finally delete it from main upload dir
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
			}

			// And from database
			$sql = "DELETE FROM " . AUCTION_IMAGE_TABLE . "
				WHERE pic_id = '$pic_id'";

			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete picture. Please try again.", "", __LINE__, __FILE__, $sql);
			} 

// end delete

			/* if we are here means that we are authentified
			now we check if its an image upload */
			if( isset($HTTP_POST_VARS['win_close']) )
			{
				$win_close = $HTTP_POST_VARS['win_close'];
				if($win_close == 1)
				{
					echo '<html><head><title>' . $lang['auction_upload_ok'] . '</title></head><body onLoad="window.opener.location.reload(); window.close();" ></body></html>'; flush();	exit;
				}
			}

//	print "avatar_filetype=".$avatar_filetype.'<br />avatar_filesize = '.$avatar_filesize; exit;


		/*	if ( !$error && $avatar_filesize > 0 && $avatar_filesize < $board_config['avatar_filesize'] )
			{
				
				$fptr = @fopen($tmp_filename, 'wb');
				$bytes_written = @fwrite($fptr, $avatar_data, $avatar_filesize);
				@fclose($fptr);

			}
*/
		}
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['auction_image_empty']);
	}
}


/*******************************************************************
******* here goes all the stuff we have todo when submitting *******
********************************************************************/
if($mode == 'approve')
{
	if( isset($HTTP_GET_VARS['pic_id']) )
	{
		$pic_id = intval($HTTP_GET_VARS['pic_id']);
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['auction_image_empty']);
	}
	if(($userdata['user_level'] == ADMIN) OR (($userdata['user_level'] == MOD) AND ($auction_pic_config['auction_offer_pic_approval_mod'] == 1)))
	{
		$sql = "UPDATE ". AUCTION_IMAGE_TABLE ."
				SET pic_approval = 0
				WHERE pic_id = '$pic_id'";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update pic information', '', __LINE__, __FILE__, $sql);
		}
	}
}

if($mode == 'recrop')
{
	if( isset($HTTP_GET_VARS['pic_id']) )
	{
		$pic_id = intval($HTTP_GET_VARS['pic_id']);
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['auction_image_empty']);
	}

	
	if(($userdata['user_level'] == ADMIN) OR ($userdata['user_level'] == MOD) OR ( $userdata['user_id'] == $auction_offer_row['FK_auction_offer_user_id'] ))
	{
		if(!isset($HTTP_GET_VARS['setcrop']))
		{
			if(!file_exists($phpbb_root_path . 'auction/graphic_files/auction_crop_pic.' . $phpEx))
			{
				message_die(GENERAL_ERROR, $lang['auction_crop_pic_missing']);
			}
			else
			{
				include($phpbb_root_path . 'auction/graphic_files/auction_crop_pic.' . $phpEx);
			}
			exit;
		}
		else
		{
			if( isset($HTTP_GET_VARS['setcrop']) )
			{
				$setcrop = intval($HTTP_GET_VARS['setcrop']);
			}
			else
			{
				message_die(GENERAL_ERROR, "Oooops, crop_id was empty!");
			}


			if(($setcrop < 1) OR ($setcrop > 3))
			{
				message_die(GENERAL_ERROR, 'Wrong crop id');
			}

			$sql = "SELECT pic_filename
					FROM ". AUCTION_IMAGE_TABLE ."
					WHERE pic_id = '$pic_id'";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
			}
			$thispic = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			$pic_thumbnail = $thispic['pic_filename'];


			$sql = "UPDATE ". AUCTION_IMAGE_TABLE ."
				SET crop_id = '$setcrop'
				WHERE pic_id = '$pic_id'";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update pic information', '', __LINE__, __FILE__, $sql);
			}

			// now we delete the 2 thumbnails that could allready have been generated
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH . $pic_thumbnail))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH . $pic_thumbnail);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MINI_PATH . $pic_thumbnail))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_MINI_PATH . $pic_thumbnail);
			}
		}
	}
}

if($mode == 'reject')
{
	if( isset($HTTP_GET_VARS['pic_id']) )
	{
		$pic_id = intval($HTTP_GET_VARS['pic_id']);
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['auction_image_empty']);
	}
	if(($userdata['user_level'] == ADMIN) OR (($userdata['user_level'] == MOD) AND ($auction_pic_config['auction_offer_pic_approval_mod'] == 1)))
	{
		$sql = "UPDATE ". AUCTION_IMAGE_TABLE ."
				SET pic_approval = 1
				WHERE pic_id = '$pic_id'";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update pic information', '', __LINE__, __FILE__, $sql);
		}
	}
}
if($mode == 'lock')
{
	if( isset($HTTP_GET_VARS['pic_id']) )
	{
		$pic_id = intval($HTTP_GET_VARS['pic_id']);
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['auction_image_empty']);
	}
	if(($userdata['user_level'] == ADMIN) OR ($userdata['user_level'] == MOD))
	{
		$sql = "UPDATE ". AUCTION_IMAGE_TABLE ."
				SET pic_lock = 1
				WHERE pic_id = '$pic_id'";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update pic information', '', __LINE__, __FILE__, $sql);
		}
	}
}

if($mode == 'unlock')
{
	if( isset($HTTP_GET_VARS['pic_id']) )
	{
		$pic_id = intval($HTTP_GET_VARS['pic_id']);
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['auction_image_empty']);
	}
	if(($userdata['user_level'] == ADMIN) OR ($userdata['user_level'] == MOD))
	{
		$sql = "UPDATE ". AUCTION_IMAGE_TABLE ."
				SET pic_lock = 0
				WHERE pic_id = '$pic_id'";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update pic information', '', __LINE__, __FILE__, $sql);
		}
	}
}
if($mode == 'delete')
{
	if( isset($HTTP_GET_VARS['pic_id']) )
	{
		$pic_id = intval($HTTP_GET_VARS['pic_id']);
	}
	else
	{
		message_die(GENERAL_ERROR, "Oooops, pic_id was empty!");
	}
	if(($userdata['user_level'] == ADMIN) OR (($userdata['user_level'] == MOD) AND ($auction_pic_config['auction_offer_pic_approval_mod'] == 1)) OR ( $userdata['user_id'] == $auction_offer_row['FK_auction_offer_user_id'] ))
	{
		
		if( !isset($HTTP_POST_VARS['confirm']) )
		{
			/* If the user pressed the cancel button */
			if( isset($HTTP_POST_VARS['cancel']) )
			{
				redirect(append_sid("auction_pics_manager.$phpEx?ao=$offer_id"));
				exit;
			}
		
			$page_title = "Delete picture from Auction-Offer";
			include($phpbb_root_path . 'includes/page_header.'.$phpEx);

			$template->set_filenames(array('body' => 'confirm_body.tpl'));

			$template->assign_vars(array(
			     'MESSAGE_TITLE' => $lang['Confirm'],
			     'MESSAGE_TEXT' => '<b>' . $lang['auction_picture_delete_confirm'] . ' <br /><br /><img src="auction_thumbnail.'.$phpEx.'?pic_type=2&recrop=2&pic_id='.$pic_id.'" border=0 /><br /><br />' . $lang['auction_no_undo'] . '</b>',
			     'L_NO' => $lang['No'],
			     'L_YES' => $lang['Yes'],
			     'S_CONFIRM_ACTION' => append_sid('auction_pics_manager.'.$phpEx.'?mode=delete&ao='.$offer_id.'&pic_id='.$pic_id)));

			$template->pparse('body');
			include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
		}
		else
		{
		
			$sql = "SELECT * FROM ". AUCTION_IMAGE_TABLE ."
			        WHERE pic_id = '$pic_id'";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not select pic information', '', __LINE__, __FILE__, $sql);
			}


			$row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			// now get the filename
			$pic_filename = $row['pic_filename'];
			// check if file exists and delete it from ALL caches (don't forget watermark caches)
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MINI_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_MINI_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MAIN_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_MAIN_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_WATERMARK_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_WATERMARK_PATH . $pic_filename);
			}
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MAIN_WATERMARK_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_MAIN_WATERMARK_PATH . $pic_filename);
			}
			// finally delete it from main upload dir
			if(@file_exists($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename))
			{
				@unlink($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
			}

			// And from database
			$sql = "DELETE FROM " . AUCTION_IMAGE_TABLE . "
				WHERE pic_id = '$pic_id'";

			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete coupon picture. Please try again.", "", __LINE__, __FILE__, $sql);
			} 

		}
	}
}
if($mode == 'set_main')
{
	if( isset($HTTP_GET_VARS['pic_id']) )
	{
		$pic_id = intval($HTTP_GET_VARS['pic_id']);
	}
	else
	{
		message_die(GENERAL_ERROR, "Oooops, pic_id was empty!");
	}
	if(($userdata['user_level'] == ADMIN) OR ($userdata['user_level'] == MOD) OR ( $userdata['user_id'] == $auction_offer_row['FK_auction_offer_user_id']))
	{
		$sql = "UPDATE ". AUCTION_IMAGE_TABLE ."
				SET pic_main = 0
				WHERE pic_auction_id = '" . $offer_id . "' ";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not setback pic main to 0', '', __LINE__, __FILE__, $sql);
		}
		$sql = "UPDATE ". AUCTION_IMAGE_TABLE ."
				SET pic_main = 1
				WHERE pic_id = '$pic_id'";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update pic main information', '', __LINE__, __FILE__, $sql);
		}


	}

}

if($mode == 'upload')
{
	if($empty_slots_left < 1)
	{
		message_die(GENERAL_ERROR, "Sorry you have reached your upload limit.");
	}
	$page_title = $lang['auction_image_upload'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array('body' => 'auction_pics_manager_upload_body.tpl'));


	if($auction_pic_config['allow_url_upload'] == 1)
	{
		$template->assign_block_vars('urlupload', array());
	}

	$template->assign_vars(array(
		'U_UPLOAD_PIC' => append_sid("album_upload.$phpEx?cat_id=". PERSONAL_GALLERY),
		'OPEN_POP_URL' => append_sid('auction_pics_manager.' . $phpEx . '?mode=upload&ao=' . $offer_id . '&pic_no=3'),
		'OPEN_POP_SIZE' => $up_pop_height,
		'FOR_OFFER' => 'for '.'<a href="'.append_sid("auction_offer_view.$phpEx?ao=$offer_id").'">'.$auction_offer_row['auction_offer_title'].'</a>',
		'S_HIDDEN_FIELDS' => '<input type="hidden" name="win_close" value="1"><input type="hidden" name="ao" value="'.$offer_id.'"><input type="hidden" name="mode" value="do_upload">',
		'S_UPLOAD_ACTION' => append_sid("auction_pics_manager.$phpEx"),
		'SORT_ASC' => ($sort_order == 'ASC') ? 'selected="selected"' : '',
		'SORT_DESC' => ($sort_order == 'DESC') ? 'selected="selected"' : ''));
	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
	exit;

}
if($mode == 'replace')
{
	
	if( isset($HTTP_GET_VARS['pic_id']) )
	{
		$pic_id = intval($HTTP_GET_VARS['pic_id']);
	}
	else
	{
		message_die(GENERAL_ERROR, "Oooops, pic_id was empty!");
	}
	$page_title = $lang['auction_image_replace'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array('body' => 'auction_pics_manager_upload_body.tpl'));
	
	if($auction_pic_config['allow_url_upload'] == 1)
	{
		$template->assign_block_vars('urlupload', array());
	}
	$template->assign_vars(array(
		'U_UPLOAD_PIC' => append_sid("album_upload.$phpEx?cat_id=". PERSONAL_GALLERY),
		'OPEN_POP_URL' => append_sid('auction_pics_manager.' . $phpEx . '?mode=upload&ao=' . $offer_id . '&pic_no=3'),
		'OPEN_POP_SIZE' => '350',
		'FOR_OFFER' => 'for '.'<a href="'.append_sid("auction_offer_view.$phpEx?ao=$offer_id").'">'.$auction_offer_row['auction_offer_title'].'</a>',
		'S_HIDDEN_FIELDS' => '<input type="hidden" name="win_close" value="1"><input type="hidden" name="ao" value="'.$offer_id.'"><input type="hidden" name="mode" value="do_replace"><input type="hidden" name="pic_id" value="' . $pic_id . '">',
		'S_UPLOAD_ACTION' => append_sid("auction_pics_manager.$phpEx"),
		'SORT_ASC' => ($sort_order == 'ASC') ? 'selected="selected"' : '',
		'SORT_DESC' => ($sort_order == 'DESC') ? 'selected="selected"' : ''));
	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
	exit;

}



/*******************************************************************
******* We have submitted so we now grab the pic information *******
********************************************************************/

// we grab all the pictures.. we dont care about locked or non authorized pictures there are only a max of 11 so the server load wont be to big



$sql = "SELECT *
        FROM ". AUCTION_IMAGE_TABLE ."
        WHERE pic_auction_id = '" . $offer_id . "' ";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not get pic info', '', __LINE__, __FILE__, $sql);
}

$pic_row = array();
while( $row = $db->sql_fetchrow($result) )
{
	$pic_row[] = $row;
}
$db->sql_freeresult($result);  // Please check!!! I don't know if we need a freeresult here!!!!

// we count the pics again
$pic_num = count($pic_row);

// if the piccount is 0 and upload is enabled we offer the possibility of uploading and exit.
// used parameters @@@@
// $auction_pic_config['gd_version']
// $auction_pic_config['auction_offer_pictures_allow']
// $auction_pic_config['auction_offer_picture_jpeg_allow']
// $auction_pic_config['auction_offer_picture_png_allow']
// $auction_pic_config['auction_offer_picture_gif_allow']
// $auction_pic_config['allow_url_upload']
// $auction_pic_config['gif_convert'] 
// $auction_pic_config['auction_gif_max_size']
// $auction_pic_config['auction_offer_picture_size_allow'] 
// $auction_pic_config['auction_offer_server_picture_size']
// $auction_pic_config['auction_offer_pic_max_width']
// $auction_pic_config['auction_offer_pic_max_height']
// $auction_pic_config['allow_thumb_gallery']
// $auction_pic_config['amount_of_thumbs']
// $auction_pic_config['auction_offer_pic_approval_admin']
// $auction_pic_config['auction_offer_pic_approval_mod']




	$page_title = $lang['Auction'] . ' :: ' . $lang['Picture_manager'] . ' :: ' . $auction_offer_row['auction_offer_title'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array('body' => 'auction_pics_manager_body.tpl'));


if($pic_num == 0)
{
	// Generate the no pics page --> todo

}
else
{


if ($amount_of_allowed_pics < $pic_num)
{
	$pic_num = $amount_of_allowed_pics;
}

$row_col = 0;
	for ($i = 0; $i < $pic_num; $i++)
	{
	
			if($row_col==1)
			{
				$color = "row1";
				$row_col = 0;
			}
			else
			{
				$color = "row2";
				$row_col = 1;
			}

			if($pic_row[$i]['pic_lock'] == 0)
			{
				$status = ($pic_row[$i]['pic_approval'] == 1) ? "<b><font color=red>" . $lang['auction_picture_awaiting'] . "<br />" . $lang['auction_picture_validation'] . "</font></b>" : "<b><font color=green>" . $lang['auction_picture'] ."<br />" . $lang['auction_picture_online'] . "</font></b>";
			}
			else
			{
				$status = "<b><font color=red>" . $lang['auction_picture'] . "<br />" . $lang['auction_picture_locked'] . "</font></b>";
			}
			if($auth == 4)
			{
				// its the admin or a special mod
				$other_percent = 11;
			}
			elseif($auth == 3)
			{
				// its a mod
				$other_percent = 13;
			}
			else
			{
				// its a user
				$other_percent = 15;
			}

			$val_reject = '';
			$val_reject .= '<td align="center" width=' . $other_percent . '% class="' . $color .'"><span class="genmed">';
			$val_reject .= ($pic_row[$i]['pic_approval'] == 1) ? '<a href="'.append_sid('auction_pics_manager.'.$phpEx.'?mode=approve&ao='.$offer_id.'&pic_id='.$pic_row[$i]['pic_id']).'"><b>' . $lang['auction_picture_approve'] . '<br />' . $lang['auction_picture'] . '</b></a>' : '<a href="'.append_sid('auction_pics_manager.'.$phpEx.'?mode=reject&ao='.$offer_id.'&pic_id='.$pic_row[$i]['pic_id']).'"><b>' . $lang['auction_picture_reject'] . '<br />' . $lang['auction_picture'] . '</b></a>' ;
			$val_reject .= '</span></td>';


			$val_lock = '';
			$val_lock .= '<td align="center" width=' . $other_percent . '% class="' . $color .'"><span class="genmed">';
			$val_lock .= ($pic_row[$i]['pic_lock'] == 1) ? '<a href="'.append_sid('auction_pics_manager.'.$phpEx.'?mode=unlock&ao='.$offer_id.'&pic_id='.$pic_row[$i]['pic_id']).'"><b>' . $lang['auction_picture_unlock'] . '<br />' . $lang['auction_picture'] . '</b></a>' : '<a href="'.append_sid('auction_pics_manager.'.$phpEx.'?mode=lock&ao='.$offer_id.'&pic_id='.$pic_row[$i]['pic_id']).'"><b>' . $lang['auction_picture_lock'] . '<br />' . $lang['auction_picture'] . '</b></a>' ;
			$val_lock .= '</span></td>';
			
			$main_or_thumb = ($pic_row[$i]['pic_main'] == 1) ? '<b>' . $lang['auction_picture_main'] . '<br />' . $lang['auction_picture'] . '</b>' : '<b>' . $lang['auction_picture_thumbnail'] .'<br />' . $lang['auction_picture_gallery_picture'] . '</b>';

			$set_as_main = ($pic_row[$i]['pic_main'] == 1) ? '<img src="'. $phpbb_root_path . 'auction/images/pic.gif' . '" alt="Main Picture" />' : '<a href="'.append_sid('auction_pics_manager.'.$phpEx.'?mode=set_main&ao='.$offer_id.'&pic_id='.$pic_row[$i]['pic_id']).'"><b>Set as<br />' . $lang['auction_picture_main_picture'] . '</b></a>' ;

			$delete_image = '<a href="'.append_sid('auction_pics_manager.'.$phpEx.'?mode=delete&ao='.$offer_id.'&pic_id='.$pic_row[$i]['pic_id']).'"><b>' . $lang['auction_picture_delete'] . '<br />' . $lang['auction_picture'] . '</b></a>' ;

			// replace picture
			$recrop_image = '<a href="'.append_sid('auction_pics_manager.'.$phpEx.'?mode=recrop&ao='.$offer_id.'&pic_id='.$pic_row[$i]['pic_id']).'"><b>' . $lang['auction_picture_recrop'] . '<br />' . $lang['auction_picture'] . '</b></a>' ;
			$crop_mini_id = $pic_row[$i]['crop_id'];
			if($auth == 4)
			{
				// its the admin or a special mod
				$img_percent = 12;
				$other_percent = 11;
			}
			elseif($auth == 3)
			{
				// its a mod
				$val_reject = '';
				$img_percent = 9;
				$other_percent = 13;
			}
			else
			{
				// its a user
				$val_reject = '';
				$val_lock = '';
				$img_percent = 10;
				$other_percent = 15;
			}

			$mini_width_height = ($auction_pic_config['gd_version'] == 0) ? ' width="' . $auction_pic_config['auction_offer_mini_size'] . '" height="' . $auction_pic_config['auction_offer_mini_size'] . '" ' : "" ;

					

					$style = ' style=" border:solid 1px #000000; "';
					
	
			$template->assign_block_vars('picrow', array(
				'THUMBNAIL' => append_sid('auction_thumbnail.'.$phpEx.'?pic_type=3&pm=1&crop=' . $crop_mini_id . '&pic_id='.$pic_row[$i]['pic_id']),
				'DESC' => $picrow[$j]['pic_desc'],
				'ROW_COLOR' => $color,
				'MINI_WIDTH_HEIGHT' => $mini_width_height,
				'MINI_STYLE' => $style,
				'STATUS' => $status,
				'IMG_PERCENT' => $img_percent,
				'OTHER_PERCENT' => $other_percent,
				'MAIN_OR_THUMB' => $main_or_thumb,
				'SET_AS_MAIN' => $set_as_main,
				'VALIDATE' => $val_reject,
				'DELETE_IMAGE' => $delete_image,
				'REPLACE_IMAGE' => '<b>' . $lang['auction_replace'] . '<br />' . $lang['auction_picture']. '</b>',
				'OPEN_POP_SIZE' => $up_pop_height,
				'OPEN_POP_URL' => append_sid('auction_pics_manager.' . $phpEx . '?mode=replace&ao=' . $offer_id . '&pic_id=' . $pic_row[$i]['pic_id']),
				'RECROP_IMAGE' => $recrop_image,
				'LOCK' => $val_lock));
	}
}
//
// Start output of page
//
$open_pop_size = 350;


$quota_explain = '';

if($auth == 4)
{
	$s_cols = 9;
}
elseif($auth == 3)
{
	$s_cols = 8; // no approval moderator (lock only)
}
else
{
	$s_cols = 7; // its a user
}

$picture_types = '<b>' . $lang['jpg_images'] . ': ';
$picture_types .= ($auction_pic_config['auction_offer_picture_jpeg_allow'] == 1) ? '<font color=green>' . $lang['Allowed'] . '</font><br />' : '<font color=red>' . $lang['Notallowed'] . '</font><br />';
$picture_types .= $lang['png_images'] . ': ';
$picture_types .= ($auction_pic_config['auction_offer_picture_png_allow'] == 1) ? '<font color=green>' . $lang['Allowed'] . '</font><br />' : '<font color=red>' . $lang['Notallowed'] . '</font><br />';
$picture_types .= $lang['gif_images'] . ': ';
$picture_types .= ($auction_pic_config['auction_offer_picture_gif_allow'] == 1) ? '<font color=green>' . $lang['Allowed'] . '</font></b><br /><br />' : '<font color=red>' . $lang['Notallowed'] . '</font></b><br /><br />';


$picture_sizes = '';
if($auction_pic_config['auction_offer_picture_jpeg_allow'] == 1)
{
	$picture_sizes .= '<b>' . $lang['Size_limit'] . $lang['jpg_images'] . ': ';
	if( $auction_pic_config['gd_version'] > 0)
	{
		$picture_sizes .= (int)(($auction_pic_config['auction_offer_server_picture_size'])/1000) .' Kb<br />';
	}
	else
	{
		$picture_sizes .= (int)(($auction_pic_config['auction_offer_picture_size_allow'])/1000) .' Kb<br />';
	}
}
if($auction_pic_config['auction_offer_picture_png_allow'] == 1)
{
	$picture_sizes .=  $lang['Size_limit'] . $lang['png_images'] . ': ';
	if( $auction_pic_config['gd_version'] > 0)
	{
		$picture_sizes .= (int)(($auction_pic_config['auction_offer_server_picture_size'])/1000) .' Kb<br />';
	}
	else
	{
		$picture_sizes .= (int)(($auction_pic_config['auction_offer_picture_size_allow'])/1000) .' Kb<br />';
	}
}
if($auction_pic_config['auction_offer_picture_gif_allow'] == 1)
{
	$picture_sizes .=  $lang['Size_limit'] . $lang['gif_images'] . ': ';
	if( $auction_pic_config['gd_version'] > 0)
	{
		if($auction_pic_config['gif_convert'] == 1)
		{
			$picture_sizes .= (int)(($auction_pic_config['auction_gif_max_size'])/1000) .' Kb';
		}
		else
		{
			$picture_sizes .= (int)(($auction_pic_config['auction_offer_picture_size_allow'])/1000) .' Kb';
		}
	}
	else
	{
		$picture_sizes .= (int)(($auction_pic_config['auction_offer_picture_size_allow'])/1000) .' Kb';
	}
}

$pic_tables1 = '<td class="row1"  width=25% valign="top" align="left" height="28"><span class="genmed">' . $picture_types . '</span></td>';
$pic_tables2 = '<td class="row1"  width=25% valign="top" align="right" height="28"><span class="genmed">' . $picture_sizes . '</span></td>';

if($empty_slots_left > 1)
{
	$l_quota = $lang['quota_exp_many'];
	$l_quota = sprintf($l_quota, $empty_slots_left);

	$l_upload_link = '<a href="javascript:openpop(\'' . append_sid('auction_pics_manager.php?mode=upload&ao=' . $offer_id . '&pic_no=3') . '\',550,' . $up_pop_height . ');" title="Upload a picture" alt="Upload a picture" ><b>' . $lang['upload_link'] . '</b></a>';
}
elseif($empty_slots_left ==  1)
{
	$l_quota = $lang['quota_exp_one'];
	$l_upload_link = '<a href="javascript:openpop(\'' . append_sid('auction_pics_manager.php?mode=upload&ao=' . $offer_id . '&pic_no=3') . '\',550,' . $open_pop_size . ');" title="Upload a picture" alt="Upload a picture" ><b>' . $lang['upload_link'] . '</b></a>';
}
else
{
	$l_quota = $lang['quota_exp_none'];
	$l_upload_link = $lang['but_u_can_replace'];
	$picture_sizes = '';
	$picture_types = '';
	$pic_tables1 = '';
	$pic_tables2 = '';
}

	$template->assign_vars(array(
		'OPEN_POP_SIZE' => $up_pop_height,
		'S_COLS' => $s_cols,
		'L_QUOTA_EXP' => $l_quota,
		'PICTABLE_1' => $pic_tables1,
		'PICTABLE_2' => $pic_tables2,
		'L_UPLOAD_LINK' => $l_upload_link,
		'L_QUOTA_EXPLAIN2' => '',
		'PICTURE_TYPES' => $picture_types,
		'PICTURE_SIZES' => $picture_sizes,
		'FOR_OFFER' => 'for '.'<a href="'.append_sid("auction_offer_view.$phpEx?ao=$offer_id").'">'.$auction_offer_row['auction_offer_title'].'</a>',
		'L_PIC_MAN' => $lang['Picture_manager'] . '&nbsp;' . $lang['overview'],
		'L_PIC_MANAGER' => $lang['Picture_manager'],
		'AUCTION_OFFER_TITLE' => $auction_offer_row['auction_offer_title'],
		'L_YOUR_OFFER' => $lang['Your_offer'],
		'U_YOUR_OFFER' => append_sid("auction_offer_view.$phpEx?ao=$offer_id")));
	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
	
exit;

?>