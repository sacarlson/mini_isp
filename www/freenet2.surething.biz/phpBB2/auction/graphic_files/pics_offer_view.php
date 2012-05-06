<?php
/***************************************************************************
 *                          pics_offer_view.php
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
 ***************************************************************************
 *   If some think they recognize codeparts of Smartors Album they are right!
 *   I wrote most of this mod initially for Smartor's album. That is why i used very similar
 *   variable names AND i adapted the Mod to the album style. Even though this
 *   mod has nothing to do with Smartor's album, I could never have written it without
 *   the inspiration of that great piece of software. So a great part of credit
 *   goes to Smartor (without him even knowing it).
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
    die("Hacking attempt");
}

/* First we check if pic_upload is there are any pictures */
/* now we check if there are any pictures */

// set a few variables
// first the offer_id
$offer_id = 0; 
$offer_id = $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];

	// check auto_gd AND if gd is present. No gallery without GD
if($auction_pic_config['gd_version'] > 0)
{
	$my_gd = (function_exists('imagecopyresampled')) ? 2 : ((function_exists('imagecopyresized'))? 1 : 0   );
	/*********************************************************
	if gd is not enabled ( of falsely enabled !!!) we do not allow the user to use the gallery!!! the images will be to big for the page to load. Imagine 11 pics with 80kb each for a modem user!!! So only remove the following lines at your own risk. No support if you remove them.
	**********************************************************/
	if(($auction_pic_config['allow_thumb_gallery'] == 1) AND ($my_gd == 0))
	{
		$auction_pic_config['allow_thumb_gallery'] = 0;
	}
	// here we check the AUTO GD setting
	if($auction_pic_config['gd_version']  == 3)
	{
		$auction_pic_config['gd_version']  = $my_gd;
	}
}

// check edit level
if($auction_pic_config['edit_level'] == 2)
{
	$allow_pic_editing = 1;
}
elseif(($auction_pic_config['edit_level'] == 1) AND (count($auction_corresponding_bidder_matches) == 0 ))
{
	$allow_pic_editing = 1;
}
else
{
	$allow_pic_editing = 0;
}
$show_edit_link = 0;

// check if user is admin, mod or offer-poster
if( ($userdata['user_level'] == ADMIN) OR ($userdata['user_level'] == MOD) OR ($auction_offer_row['user_id'] == $userdata['user_id']) )
{
	$show_edit_link = 1;
	$sql_1 = "";
}
else
{
		$sql_1 = "AND pic_approval = 0 AND pic_lock = 0 ";
}

$sql = "SELECT COUNT(pic_id) AS pic_count
		FROM ". AUCTION_IMAGE_TABLE ."
		WHERE pic_auction_id = '" . $auction_offer_id . "' ";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not count your pic', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);
$db->sql_freeresult($result);
$offer_pics = $row['pic_count'];

if($offer_pics < 1)
{
		if($show_edit_link == 1)
		{
			if(($auction_pic_config['allow_thumb_gallery'] == 1) AND ($auction_pic_config['amount_of_thumbs'] > 0) )
			{
				if($allow_pic_editing == 1)
				{
					$pic_manage_link = '<a href="' . append_sid($phpbb_root_path . 'auction_pics_manager.' . $phpEx . '?ao=' . $offer_id) . '">' .  $lang['admin_pic_u_can_upload_or_edit_all'] . '</a>';
				}
				else
				{
					$pic_manage_link = $lang['admin_pic_no_modification_allowed'];	
				}
			}
			else
			{
				if($allow_pic_editing == 1)
				{
					$pic_manage_link = '<a href="' . append_sid($phpbb_root_path . 'auction_pics_manager.' . $phpEx . '?ao=' . $offer_id) . '">' . $lang['admin_pic_u_can_upload_or_edit_1'] . '</a>';
						
				}
				else
				{
					$pic_manage_link = $lang['admin_pic_no_modification_allowed'];	
				}

			}
				$pic_hr = 1;
				$pic_span = 1;
		}
		$offer_picture_url = "<img src=\"". $images['icon_auction_no_pic'] . "\" />";
	//dostuff if there are no pics
}
else
{
	// first we generate the main picture
	$sql = "SELECT *
                FROM " . AUCTION_IMAGE_TABLE . "
                WHERE pic_auction_id = '" . $auction_offer_id . "' AND
                      pic_main = 1  ". $sql_1 . "
                LIMIT 1";

	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query pictures', '', __LINE__, __FILE__, $sql);
	} // End if
	
	$picrow = $db->sql_fetchrow($result);
	
	$db->sql_freeresult($result);  // Please check!!! I don't know if we need a freeresult here!!!!

	// check if there is a main picture	
	if (!$picrow['pic_main'])
	{
		// ooops there is no main picture so we set the default nopic.gif
		// TODO: We have a picture but no main picture... so we should AUTOMATICALLY set this picture as the main picture... will be hopefully done in future versions (actually this should never happen... but you never know).

		$auction_offer_picture = "<img src=\"". $phpbb_root_path . $current_template_images . $images['icon_auction_no_pic'] . "\"></img>";
		$offer_picture_url = $auction_offer_picture;
	}
	else
	{
		// there is 1 picture set to main..	the others will be offer-album pictures
		if($picrow['pic_main'] == 1) 
		{
			//first we get the size of the original picture
			$pic_pop_size = @getimagesize($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $picrow['pic_filename']);
			$pic_pop_width = $pic_pop_size[0];
			$pic_pop_height = $pic_pop_size[1];
			$picture_pop = $picrow['pic_id'];
			$pic_desc = $auction_offer_row['auction_offer_title'] . " - Main-Picture"; // get this to $lang
			$picture_url = append_sid($phpbb_root_path . "auction_thumbnail.$phpEx?pic_type=1&pic_id=". $picrow['pic_id']);

			//make sure url is not set
			$offer_picture_url =  "";
			
			// we treat the pic differently if gd is enabled of not
				
			if($auction_pic_config['gd_version'] > 0)
			{
				// gd is enabled
				if ($auction_pic_config['main_pic_js_bw'] == 1)
				{
					$auction_offer_picture = '<img src="'. $picture_url . '"  alt="' . $pic_desc . '" title="' . $pic_desc . '" galleryimg="no" border=0 class="iexbutton" onmouseover="this.className=\'iexbuttonover\'" onmouseout="this.className=\'iexbutton\'" onmousedown="this.className=\'iexbuttondown\'" />';
				}
				else
				{
					$auction_offer_picture = '<img src="'. $picture_url . '"  alt="' . $pic_desc . '" title="' . $pic_desc . '" galleryimg="no" border=0 />';
				}
			}
			else
			{
				//gd is not enabled so we have to calculate width and height of the main picture
				// we check the height and width to determine if picture has to be resized.
				if (($pic_pop_width > $auction_pic_config['auction_offer_main_size']) or ($pic_pop_height > $auction_pic_config['auction_offer_main_size']))
				{
					// the picture exceeds the max-size of the main-offer picture and
					// its a landscape
					if ($pic_pop_width > $pic_pop_height)
					{
						$pic_main_width = $auction_pic_config['auction_offer_main_size'];
						$pic_main_height = $auction_pic_config['auction_offer_main_size'] * ($pic_pop_height/$pic_pop_width);
					}
					else // its a portrait
					{
						$pic_main_height = $auction_pic_config['auction_offer_main_size'];
						$pic_main_width = $auction_pic_config['auction_offer_main_size'] * ($pic_pop_width/$pic_pop_height);	
					}
				}
				else 
				{
					// the picture is smaller than the defined size of the main-offer picture
					$pic_main_height = $pic_pop_height;
					$pic_main_width = $pic_pop_width;
				}
				// now we generate the pic with the right settings...
				if ($auction_pic_config['main_pic_js_bw'] == 1)
				{
					$auction_offer_picture = '<img src="'. $picture_url . '" width="' . $pic_main_width . '" height="' . $pic_main_height . '" alt="' . $pic_desc . '" title="' . $pic_desc . '" galleryimg="no" border=0  vspace="10"   class="iexbutton" onmouseover="this.className=\'iexbuttonover\'" onmouseout="this.className=\'iexbutton\'" onmousedown="this.className=\'iexbuttondown\'" />';
				}
				else
				{
					if($auction_pic_config['main_pic_border'] == 1)
					{
						$style = ' style=" border:solid 1px #'.$auction_pic_config['main_pic_border_color'].'; "';
					}
					else
					{
						$style = '';
					}
					$auction_offer_picture = '<img '.$style.' src="'. $picture_url . '" width="' . $pic_main_width . '" height="' . $pic_main_height . '" alt="' . $pic_desc . '" title="' . $pic_desc . '" galleryimg="no" border=0 />';
				}
			}
			$offer_picture_url .= "<a href=\"#\" onclick=\"openpop('$picture_pop','$pic_pop_width','$pic_pop_height');return false;\" target=\"_blank\">".$auction_offer_picture."</a>" ;
		}
	}
	/* OK, we now have our main pic.. the $offer_picture_url is placed in the main template in the main script */
 // we turn off the gallery if user doesn#t have gd

	if(($auction_pic_config['allow_thumb_gallery'] == 1) AND ($auction_pic_config['amount_of_thumbs'] > 0) AND ($auction_pic_config['gd_version'] > 0))
	{
		$amount_of_thumbs = $auction_pic_config['amount_of_thumbs'];
		// ATTENTION TODO: If for ex. the admin sets 9 thumbs allowed and user has gallery of 9 thumbs.. then the admin reduces the amount of thumbs, we have the limit. We will have in future to ad a sort order so that the last (or first ????????) thumbs are shown. Lets wait and see what the users say.... for the moment no sort order has been added.

		// the $sql_1 has been done above
		$sql = "SELECT *
                        FROM " . AUCTION_IMAGE_TABLE . "
                        WHERE pic_auction_id = '" . $auction_offer_id . "' AND
                              pic_main <> 1 ". $sql_1 . "
                        LIMIT " . $amount_of_thumbs . " ";

		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query pictures', '', __LINE__, __FILE__, $sql);
		}
	
		$pic_gal_row = array();
		while( $thumb_row = $db->sql_fetchrow($result) )
		{
			$pic_gal_row[] = $thumb_row;
		}
		$db->sql_freeresult($result);  // Please check!!! I don't know if we need a freeresult here!!!!

		$offer_thumb_num = count($pic_gal_row);
		
		if($offer_thumb_num > $auction_pic_config['amount_of_thumbs'])
		{
			$offer_thumb_num = $auction_pic_config['amount_of_thumbs'];
		}
		// as we filter the main image we need a counter that we set to zero right here
		$pic_counter = 0;

		// first we check if there are thumbnails

		for ($i = 0; $i < $offer_thumb_num; $i++)
		{
			if( $i >= $offer_thumb_num )
			{
				break;
			}
			else
			{
				$pic_counter = 	$pic_counter + 1;
					
				if($pic_counter > $auction_pic_config['amount_of_thumb_per_line'])
				{
					$table = "<tr>";
					$table1 = "</tr>";
					$pic_counter = 	1;
				}
				else
				{
					$table1 = "";
					$table = "";
				}
				// now we calculate the thumbnail and the link for the popup
				// we need this for the popupsize
				$thumb_pic_size = @getimagesize($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_gal_row[$i]['pic_filename']);
				$pic_width = $thumb_pic_size[0];
				$pic_height = $thumb_pic_size[1];
				$pic_desc = "Gallery Picture " . $pic_counter . " - " . $auction_offer_row['auction_offer_title']; 
				$crop_id = $pic_gal_row[$i]['crop_id'];
				$picture_url_thumb = append_sid($phpbb_root_path . "auction_thumbnail.$phpEx?pic_type=2&crop=" . $crop_id . "&pic_id=". $pic_gal_row[$i]['pic_id']);
				$picture_pop = $pic_gal_row[$i]['pic_id'];

				// we make sure its empty
				$offer_picture_url_thumb =  "";
					
				if($auction_pic_config['gd_version'] > 0)
				{
					if ($auction_pic_config['thumb_pic_js_bw'] == 1)
					{
						$auction_offer_picture = '<img src="'. $picture_url_thumb . '"  alt="' . $pic_desc . '" title="' . $pic_desc . '" galleryimg="no" border=0  class="iexbutton" onmouseover="this.className=\'iexbuttonover\'" onmouseout="this.className=\'iexbutton\'" onmousedown="this.className=\'iexbuttondown\'" />';
					}
					else
					{
						$auction_offer_picture = '<img src="'. $picture_url_thumb . '"  alt="' . $pic_desc . '" title="' . $pic_desc . '" galleryimg="no" border=0 />';
					}
				} // there is no else because boards with no gd we don't have a gallery
			
				$pic_pop_width = $pic_width;
				$pic_pop_height = $pic_height;
				$offer_picture_url_thumb .= "<a href=\"#\" onclick=\"openpop('$picture_pop','$pic_pop_width','$pic_pop_height');return false;\" target=\"_blank\">".$auction_offer_picture."</a>" ;

				/******** now we populate the template ******/
				$template->assign_block_vars('piccol', array(
					'U_PIC' => ($album_config['fullpic_popup']) ? append_sid("album_pic.$phpEx?pic_id=". $pic_gal_row[$i]['pic_id']) : append_sid("album_page.$phpEx?pic_id=". $pic_gal_row[$i]['pic_id']),
					'THUMBNAIL' => $offer_picture_url_thumb,
					'DESC' => $pic_gal_row[$i]['pic_desc'],
					'COUNT' => $pic_counter .$table1 ,
					'TABLE' => $table,
					'TABLE1' => $table1));
			} // end else in for loop
		} // end for

		$pic_span = (($offer_thumb_num > 0) AND ($offer_thumb_num <= $auction_pic_config['amount_of_thumb_per_line'])) ? $offer_thumb_num : (($offer_thumb_num == 0) ? 1 : $auction_pic_config['amount_of_thumb_per_line']) ;
		$pic_hr = 1;
		if($show_edit_link == 1)
		{
			if(($userdata['user_level'] == ADMIN) OR (($userdata['user_level'] == MOD) AND ($auction_pic_config['auction_offer_pic_approval_mod'] == 1)))
			{
				$pic_manage_link = '<a href="' . append_sid($phpbb_root_path . 'auction_pics_manager.' . $phpEx . '?ao=' . $offer_id) . '">' . $lang['admin_pic_manager_link_all'] . '</a>';
			}
			elseif (($userdata['user_level'] == MOD) AND ($auction_pic_config['auction_offer_pic_approval_mod'] != 1))
			{
				$pic_manage_link = '<a href="' . append_sid($phpbb_root_path . 'auction_pics_manager.' . $phpEx . '?ao=' . $offer_id) . '">' .  '4'. $lang['admin_pic_manager_mod_link_all'] . '</a>';
			}
			else
			{
				if($offer_pics == 0)
				{
					if($allow_pic_editing == 1)
					{
						$pic_manage_link = '<a href="' . append_sid($phpbb_root_path . 'auction_pics_manager.' . $phpEx . '?ao=' . $offer_id) . '">' .  '5'.$lang['admin_pic_u_can_upload_or_edit_all'] . '</a>';
					}
					else
					{
						$pic_manage_link = $lang['admin_pic_no_modification_allowed'];
					}
				}
				elseif($offer_pics == 1) 
				{
					if($allow_pic_editing == 1)
					{
						$pic_manage_link = '<a href="' . append_sid($phpbb_root_path . 'auction_pics_manager.' . $phpEx . '?ao=' . $offer_id) . '">' .  $lang['admin_pic_u_can_edit_and_upload_more'] . '</a>';
					}
					else
					{
						$pic_manage_link = $lang['admin_pic_no_modification_allowed'];
					}
				}
				else
				{
					if($allow_pic_editing == 1)
					{
						$pic_manage_link = '<a href="' . append_sid($phpbb_root_path . 'auction_pics_manager.' . $phpEx . '?ao=' . $offer_id) . '">' .  $lang['admin_pic_u_can_edit_and_upload_more'] . '</a>';						
					}
					else
					{
						$pic_manage_link = $lang['admin_pic_no_modification_allowed'];
					}
				}
			}
		} // end if $show_edit_link
		else
		{
			if($offer_pics > 1)
			{
				$pic_manage_link = $lang['additional_pictures'];
			}
		}
	} // end if gallery enabled
	else 
	{// gallery is not enabled
		$pic_span = 1;
		$pic_hr = 1;
		if($show_edit_link == 1)
		{
			if(($userdata['user_level'] == ADMIN) OR (($userdata['user_level'] == MOD) AND ($auction_pic_config['auction_offer_pic_approval_mod'] == 1)))
			{
				// goes to Language file
				$lang['admin_pic_manager_link_1'] =  'Click here to manage the users picture';
				$pic_manage_link = '<a href="' . append_sid($phpbb_root_path . 'auction_pics_manager.' . $phpEx . '?ao=' . $offer_id) . '">' .  $lang['admin_pic_manager_link_1'] . '</a>';

			}
			elseif (($userdata['user_level'] == MOD) AND ($auction_pic_config['auction_offer_pic_approval_mod'] != 1))
			{
				$lang['admin_pic_manager_mod_link_1'] = 'Click here to edit the users picture';
				$pic_manage_link = '<a href="' . append_sid($phpbb_root_path . 'auction_pics_manager.' . $phpEx . '?ao=' . $offer_id) . '">' .  $lang['admin_pic_manager_mod_link_1'] . '</a>';
			}
			else
			{
				if($offer_pics == 0)
				{
					if($allow_pic_editing == 1)
					{
						$pic_manage_link = '<a href="' . append_sid($phpbb_root_path . 'auction_pics_manager.' . $phpEx . '?ao=' . $offer_id) . '">' . '10'. $lang['admin_pic_u_can_upload_or_edit_1'] . '</a>';
					}
					else
					{
						$pic_manage_link = $lang['admin_pic_no_modification_allowed'];
					}
				}
				else
				{
					if($allow_pic_editing == 1)
					{
						$pic_manage_link = '<a href="' . append_sid($phpbb_root_path . 'auction_pics_manager.' . $phpEx . '?ao=' . $offer_id) . '">' . '11'. $lang['admin_pic_u_can_edit_1'] . '</a>';						
					}
					else
					{
						$pic_manage_link = $lang['admin_pic_no_modification_allowed'];
					}
				}
			} // end else userlevel
		} // end if show_edit_link
	} // end else
	// now we populate the template with the manager link
}
	$hor_ruler = ($pic_hr == 1) ? '<tr><td colspan="2" class="row1"><hr width="90%"></td></tr>' : '';

                    // Check auction_permission
if ( checkBoolPermission('IMAGE_UPLOAD') )
     {
	$template->assign_block_vars('hr', array(
		'NUMBER_OF_PICS_IN_ROW' => $pic_span,
		'L_GALLERY_OR_EDIT_LINK' => $pic_manage_link,
		'HR' => $hor_ruler));
     }

?>