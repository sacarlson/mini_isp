<?
/***************************************************************************
*                               ads_images.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_images.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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

//
// Mode setting
//
if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = "";
}

if ( $HTTP_POST_VARS['cancel'] )
{
	$mode = "";
}

// ===============
// Pre processing
// ===============

if ( $ads_config['images'] != 1 or empty($HTTP_GET_VARS['id']) )
{
	message_die(GENERAL_ERROR, $lang['invalid_request']);
}

// Sanitize input data
$id = intval($HTTP_GET_VARS['id']);

// Get the row from the ads table
$sql = "SELECT * 
		FROM ". ADS_ADVERTS_TABLE ."
		WHERE id = '$id'";
		
$result = $db->sql_query($sql);
$row = $db->sql_fetchrow($result);

if ( !$row )
{
	message_die(GENERAL_ERROR, $lang['ad_not_found'], '', __LINE__, __FILE__, $sql);
}

$category = $row['category'];
$sub_category = $row['sub_category'];
$ad_type_code = $row['ad_type_code'];
$user_id = $row['user_id'];
$title = $row['title'];

// Check that pics allowed for this ad type
if ( $ad_type_code < 3 )
{
	message_die(GENERAL_ERROR, $lang['invalid_request']);
}

// Read the categories table
$sql = "SELECT * 
		FROM ". ADS_CATEGORIES_TABLE ."
		WHERE cat_category = '".addslashes($category)."'
		AND cat_sub_category = '".addslashes($sub_category)."'";

$result = $db->sql_query($sql);
$category_row = $db->sql_fetchrow($result);

if ( !$category_row )
{
	message_die(GENERAL_ERROR, "Error reading category table", "", __LINE__, __FILE__, $sql);
}

$cat_image_level = $category_row['cat_image_level'];
$cat_delete_level = $category_row['cat_delete_level'];

// ===============
// Main processing
// ===============
switch($mode)

{
	case 'delete':

		if ( !isset($HTTP_POST_VARS['confirm']) )
		{
			// ========================
			// Delete confirmation page
			// ========================

			// Check permissions
			if ( delete_allowed($user_id, $cat_delete_level) == FALSE )
			{
				if ( !$userdata['session_logged_in'] ) 
				{
					redirect(append_sid("login.$phpEx?redirect=ads_images.$phpEx&amp;id=$id"));
				}
				else
				{
					message_die(GENERAL_ERROR, $lang['Not_Authorised']);
				}
			}

			// Need a sequence number
			if ( empty($HTTP_GET_VARS['img_seq_no']) )
			{
				message_die(GENERAL_ERROR, $lang['invalid_request']);
			}

			// Sanitize input data
			$img_seq_no = intval($HTTP_GET_VARS['img_seq_no']);

			$img_url = ADS_IMAGES_PATH .'ad'.$id.'_img'.$img_seq_no.'_medium.jpg';

			$page_title = $title;

			include($phpbb_root_path . 'includes/page_header.'.$phpEx);

			$template->set_filenames(array('delete_img' => 'ads_delete_img.tpl')); 

			$template->assign_vars(array(	

				'U_ADS_INDEX' => append_sid("adverts.$phpEx"),
				'U_ADS_ITEM' => append_sid("ads_item.$phpEx?id=$id"),
		
				'S_POST_ACTION' => append_sid("ads_images.$phpEx?mode=delete&id=$id&img_seq_no=$img_seq_no"),
		
				'L_ADS_INDEX' => $lang['ads_index'],
				'L_INFORMATION' => $lang['information'],
				'L_DELETE_IMG_QUESTION' => $lang['delete_img_question'],
				'L_YES' => $lang['yes'],
				'L_NO' => $lang['no'],

				'SITE_NAME' => $board_config['sitename'],
				'ID' => $id,
				'TITLE' => $row["title"],
				'IMG_URL' => $img_url,
				'IMG_SEQ_NO' => $img_seq_no));

			$template->pparse('delete_img'); 
		}
		else
		{
			// ================
			// Delete the image
			// ================

			// Check permissions
			if ( delete_allowed($user_id, $cat_delete_level) == FALSE )
			{
				if ( !$userdata['session_logged_in'] ) 
				{
					redirect(append_sid("login.$phpEx?redirect=ads_images.$phpEx&amp;id=$id"));
				}
				else
				{
					message_die(GENERAL_ERROR, $lang['Not_Authorised']);
				}
			}

			// Need a sequence number
			if ( empty($HTTP_GET_VARS['img_seq_no']) )
			{
				message_die(GENERAL_ERROR, $lang['invalid_request']);
			}

			// Sanitize input variables
			$img_seq_no = intval($HTTP_GET_VARS['img_seq_no']);

			$sql = "UPDATE ". ADS_IMAGES_TABLE ."
				SET img_deleted_ind = 1
				WHERE id = '$id' 
				AND img_seq_no = '$img_seq_no'";

			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update this image', '', __LINE__, __FILE__, $sql);
			}

			$filename = ADS_IMAGES_PATH .'ad'.$id.'_img'.$img_seq_no.'_thumb.jpg';
			unlink($filename);

			$filename = ADS_IMAGES_PATH .'ad'.$id.'_img'.$img_seq_no.'_medium.jpg';
			unlink($filename);

			$filename = ADS_IMAGES_PATH .'ad'.$id.'_img'.$img_seq_no.'_large.jpg';
			unlink($filename);

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("ads_item.$phpEx?id=$id") . '">'));

			$message = $lang['delete_img_confirmation'] . "<br /><br />" . sprintf($lang['click_to_add_images'], "<a href=\"" . append_sid("ads_images.$phpEx?id=$id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_to_view_ad'], "<a href=\"" . append_sid("ads_item.$phpEx?id=$id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
			message_die(GENERAL_MESSAGE, $message);
		}
		
		break;	

	default: // Create image!

		if ( !isset($HTTP_POST_VARS['submit']) )
		{
			// ==========
			// Image page
			// ==========

			// Check permissions
			if ( image_allowed($user_id, $cat_image_level) == FALSE )
			{
				if ( !$userdata['session_logged_in'] ) 
				{
					redirect(append_sid("login.$phpEx?redirect=ads_images.$phpEx&amp;id=$id"));
				}
				else
				{
					message_die(GENERAL_ERROR, $lang['Not_Authorised']);
				}
			}

			$page_title = $title;

			include($phpbb_root_path . 'includes/page_header.'.$phpEx);
		
			$template->set_filenames(array('images_page' => 'ads_images.tpl')); 

			$sql = "SELECT * 
					FROM ". ADS_IMAGES_TABLE ."
					WHERE id = '$id'
					AND img_deleted_ind = 0";
		
			$result = $db->sql_query($sql);
			if ( $db->sql_numrows($result) > 0 )
			{
				while ($row = $db->sql_fetchrow($result)) 
				{
					$img_url = ADS_IMAGES_PATH ."ad".$id."_img".$row["img_seq_no"]."_medium.jpg";
					$delete_img_url = "ads_images.$phpEx?mode=delete&id=$id&img_seq_no=".$row['img_seq_no'];
	
					$template->assign_block_vars('imagecolumn', array(	'IMG_URL' => $img_url,
																						'U_DELETE_IMG_URL' => append_sid("$delete_img_url"))); 
				}
				$template->assign_block_vars('switch_images_found',array());
			}		

			// Set the rest of the template variables

			$template->assign_vars(array(	
		
				'U_ADS_INDEX' => append_sid("adverts.$phpEx"),
				'U_ADS_ITEM' => append_sid("ads_item.$phpEx?id=$id"),
				'U_CREATE_IMG' => append_sid("ads_images.$phpEx?id=$id"),

				'L_ADS_INDEX' => $lang['ads_index'],
				'L_ADD_IMAGES' => $lang['add_images'],
				'L_IMAGE_TYPES' => $lang['image_types'],
				'L_UPLOAD_IMAGE' => $lang['upload_image'],
				'L_DELETE_IMAGES' => $lang['delete_images'],

				'SITE_NAME' => $board_config['sitename'],
				'TITLE' => $title,
				'ICON_DELETE' => $images['icon_delpost'],
				'ID' => $id));

			$template->pparse('images_page'); 
		}
		else
		{
			// ================
			// Create the image
			// ================

			// Check permissions
			if ( image_allowed($user_id, $cat_image_level) == FALSE )
			{
				if ( !$userdata['session_logged_in'] ) 
				{
					redirect(append_sid("login.$phpEx?redirect=ads_images.$phpEx&amp;id=$id"));
				}
				else
				{
					message_die(GENERAL_ERROR, $lang['Not_Authorised']);
				}
			}

			// Limit the number of ads for this user (also prevents flooding)
			$sql = "SELECT COUNT(*) AS count 
					FROM ". ADS_IMAGES_TABLE ."
					WHERE id = '$id'
					AND img_deleted_ind = 0";

			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);

			if ( $row['count'] >= $ads_config['max_images_per_ad'] )
			{
				message_die(GENERAL_ERROR, $lang['max_images_exceded']);
			}

			// Check that an actual images has been selected
			if ( empty($_FILES['image']['size']) )
			{
				message_die(GENERAL_ERROR, $lang['no_image']);
			}
	
			// Check for an allowable image type
			if ( $_FILES['image']['type'] != 'image/pjpeg' 
			and  $_FILES['image']['type'] != 'image/jpeg'
			and  $_FILES['image']['type'] != 'image/x-png' 
			and  $_FILES['image']['type'] != 'image/png'
			and  $_FILES['image']['type'] != 'image/gif' )
			{
				message_die(GENERAL_ERROR, $lang['invalid_image']);
			}

			if ( $_FILES['image']['type'] == 'image/pjpeg' or $_FILES['image']['type'] == 'image/jpeg' )
			{
				$im = imagecreatefromjpeg($_FILES['image']['tmp_name']);
			}
			elseif ( $_FILES['image']['type'] == 'image/x-png' or $_FILES['image']['type'] == 'image/png' )
			{
				$im = imagecreatefrompng($_FILES['image']['tmp_name']);
			}
			elseif ( $_FILES['image']['type'] == 'image/gif' )
			{
				$im = imagecreatefromgif($_FILES['image']['tmp_name']);
			}

			if ( $im )
			{
				// Insert the row
				$sql = "INSERT INTO ". ADS_IMAGES_TABLE ." 
						VALUES ($id, 0, '', 1)";

				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not insert images row', '', __LINE__, __FILE__, $sql);
				}

				$img_seq_no = $db->sql_nextid();

				// First create the thumbnail
				$filename = ADS_IMAGES_PATH ."ad".$id."_img".$img_seq_no."_thumb";
		    	ResizeImage($im, $ads_config['thumb_img_width'], $ads_config['thumb_img_height'], $filename);

				// Then create the medium size image
				$filename = ADS_IMAGES_PATH ."ad".$id."_img".$img_seq_no."_medium";
		  		ResizeImage($im, $ads_config['medium_img_width'], $ads_config['medium_img_height'], $filename);

				// Then create the large size image
				$filename = ADS_IMAGES_PATH ."ad".$id."_img".$img_seq_no."_large";
		  		ResizeImage($im, $ads_config['large_img_width'], $ads_config['large_img_height'], $filename);

				if ( file_exists (ADS_IMAGES_PATH .'ad'.$id.'_img'.$img_seq_no.'_thumb.jpg') 
				and  file_exists (ADS_IMAGES_PATH .'ad'.$id.'_img'.$img_seq_no.'_medium.jpg') 
				and  file_exists (ADS_IMAGES_PATH .'ad'.$id.'_img'.$img_seq_no.'_large.jpg') )
				{
					// Update the row
					$sql = "UPDATE ". ADS_IMAGES_TABLE ." 
							SET img_deleted_ind = 0
							WHERE id = $id
							AND img_seq_no = $img_seq_no";
				}
				else
				{
					message_die(GENERAL_ERROR, $lang['image_creation_failed']);
				}

				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not insert images row', '', __LINE__, __FILE__, $sql);
				}

				// Now destroy the temporary image
				ImageDestroy($im);

				// Put out the confirmation message
				$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("ads_item.$phpEx?id=$id") . '">'));

				$message = $lang['create_img_confirmation'] . "<br /><br />" . sprintf($lang['click_to_add_images'], "<a href=\"" . append_sid("ads_images.$phpEx?id=$id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_to_view_ad'], "<a href=\"" . append_sid("ads_item.$phpEx?id=$id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['click_return_ads_index'], "<a href=\"" . append_sid("adverts.$phpEx") . "\">", "</a>");
				message_die(GENERAL_MESSAGE, $message);
			}
		}

		break;
}

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);	
?>