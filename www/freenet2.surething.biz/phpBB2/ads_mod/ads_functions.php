<?
/***************************************************************************
 *                             ads_functions.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_functions.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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

// =========================
// View allowed
// =========================
function view_allowed($view_level)
{
	global $userdata, $phpEx;

	if ( $view_level == ADS_GUEST ) 
	{
		$view_allowed = TRUE;
	}

	if ( $view_level == ADS_USER and $userdata['session_logged_in'] )
	{
		$view_allowed = TRUE;
	}

	if ( $view_level == ADS_MOD and $userdata['user_level'] == MOD )
	{
		$view_allowed = TRUE;
	}

	if ( $userdata['user_level'] == ADMIN )
	{
		$view_allowed = TRUE;
	}

	if ( $view_allowed == TRUE)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

// ========================= 
// Move allowed 
// ========================= 
function move_allowed($user_id, $ads_move_level) 
{ 
	global $userdata, $phpEx; 

	if ( ( $ads_move_level == ADS_USER and $userdata['user_id'] == $user_id and $userdata['session_logged_in'] ) 
	or   ( $ads_move_level == ADS_USER and $userdata['user_level'] == MOD ) ) 
	{ 
		$move_allowed = TRUE; 
	} 

	if ( $ads_move_level == ADS_MOD and $userdata['user_level'] == MOD ) 
	{ 
		$move_allowed = TRUE; 
	} 

	if ($userdata['user_level'] == ADMIN ) 
	{ 
		$move_allowed = TRUE; 
	} 

	if ( $move_allowed == TRUE) 
	{ 
		return TRUE; 
	} 
	else 
	{ 
		return FALSE; 
	} 
} 

// =========================
// Search allowed
// =========================
function search_allowed($search_level)
{
	global $userdata, $phpEx;

	if ( $search_level == ADS_GUEST ) 
	{
		$search_allowed = TRUE;
	}

	if ( $search_level == ADS_USER and $userdata['session_logged_in'] )
	{
		$search_allowed = TRUE;
	}

	if ( $search_level == ADS_MOD and $userdata['user_level'] == MOD )
	{
		$search_allowed = TRUE;
	}

	if ( $userdata['user_level'] == ADMIN )
	{
		$search_allowed = TRUE;
	}

	if ( $search_allowed == TRUE)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

// =========================
// Create allowed
// =========================
function create_allowed($cat_create_level)
{
	global $userdata, $phpEx;

	if ( $cat_create_level == ADS_GUEST ) 
	{
		$create_allowed = TRUE;
	}
	else
	{
		if ( !$userdata['session_logged_in'] ) 
		{
			redirect(append_sid("login.$phpEx?redirect=ads_create.$phpEx"));
		}
	}

	if ( $cat_create_level == ADS_USER )
	{
		$create_allowed = TRUE;
	}

	if ( $cat_create_level == ADS_MOD and $userdata['user_level'] == MOD )
	{
		$create_allowed = TRUE;
	}

	if ( $userdata['user_level'] == ADMIN )
	{
		$create_allowed = TRUE;
	}

	if ( $create_allowed == TRUE)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

// =========================
// Edit allowed
// =========================
function edit_allowed($user_id, $cat_edit_level)
{
	global $userdata, $phpEx;

	if ( ( $cat_edit_level == ADS_USER and $userdata['user_id'] == $user_id and $userdata['session_logged_in'] )
	or   ( $cat_edit_level == ADS_USER and $userdata['user_level'] == MOD ) )
	{
		$edit_allowed = TRUE;
	}

	if ( $cat_edit_level == ADS_MOD and $userdata['user_level'] == MOD )
	{
		$edit_allowed = TRUE;
	}

	if ($userdata['user_level'] == ADMIN )
	{
		$edit_allowed = TRUE;
	}

	if ( $edit_allowed == TRUE)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

// =========================
// Delete allowed
// =========================
function delete_allowed($user_id, $cat_delete_level)
{
	global $userdata, $phpEx;

	if ( ( $cat_delete_level == ADS_USER and $userdata['user_id'] == $user_id and $userdata['session_logged_in'] )
	or   ( $cat_delete_level == ADS_USER and $userdata['user_level'] == MOD ) )
	{
		$delete_allowed = TRUE;
	}

	if ( $cat_delete_level == ADS_MOD and $userdata['user_level'] == MOD )
	{
		$delete_allowed = TRUE;
	}

	if ( $userdata['user_level'] == ADMIN )
	{
		$delete_allowed = TRUE;
	}

	if ( $delete_allowed == TRUE)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

// =========================
// Image allowed
// =========================
function image_allowed($user_id, $cat_image_level)
{
	global $userdata, $phpEx;

	if ( ( $cat_image_level == ADS_USER and $userdata['user_id'] == $user_id and $userdata['session_logged_in'] )
	or   ( $cat_image_level == ADS_USER and $userdata['user_level'] == MOD ) )
	{
		$image_allowed = TRUE;
	}

	if ( $cat_image_level == ADS_MOD and $userdata['user_level'] == MOD )
	{
		$image_allowed = TRUE;
	}

	if ( $userdata['user_level'] == ADMIN )
	{
		$image_allowed = TRUE;
	}

	if ( $image_allowed == TRUE)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

// =========================
// Comment allowed
// =========================
function comment_allowed($cat_comment_level)
{
	global $userdata, $phpEx;

	if ( $cat_comment_level == ADS_GUEST ) 
	{
		$comment_allowed = TRUE;
	}

	if ( $cat_comment_level == ADS_USER and $userdata['session_logged_in'] )
	{
		$comment_allowed = TRUE;
	}

	if ( $cat_comment_level == ADS_MOD and $userdata['user_level'] == MOD )
	{
		$comment_allowed = TRUE;
	}

	if ( $userdata['user_level'] == ADMIN )
	{
		$comment_allowed = TRUE;
	}

	if ( $comment_allowed == TRUE)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

// ==========================================================
// ResizeImage - based on an original script by PHPGarage.com
// ==========================================================
function ResizeImage($im, $resizewidth, $resizeheight, $filename)
{
	$width = imagesx($im);
	$height = imagesy($im);

	if (($resizewidth and $width > $resizewidth) 
	or ($resizeheight and $height > $resizeheight))
	{
		if ($resizewidth and $width > $resizewidth)
		{
			$widthratio = $resizewidth/$width;
			$resizewidth_ind = true;
		}
		if ($resizeheight and $height > $resizeheight)
		{
			$heightratio = $resizeheight/$height;
			$resizeheight_ind = true;
		}
		if ($resizewidth_ind and $resizeheight_ind)
		{
			if ($widthratio < $heightratio)
			{
				$ratio = $widthratio;
			}
			else
			{
				$ratio = $heightratio;
			}
		}
		elseif ($resizewidth_ind)
		{
			$ratio = $widthratio;
		}
		elseif ($resizeheight_ind)
		{
			$ratio = $heightratio;
		}

		$newwidth = $width * $ratio;
		$newheight = $height * $ratio;
		if (function_exists("imagecopyresampled"))
		{
			$newim = imagecreatetruecolor($newwidth, $newheight);
			imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}
		else
		{
			$newim = imagecreate($newwidth, $newheight);
			imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		}
		touch($filename . ".jpg"); 
		ImageJpeg ($newim,$filename . ".jpg");
//		ImageDestroy ($newim);
	}
	else
	{
		touch($filename . ".jpg"); 
		ImageJpeg ($im,$filename . ".jpg");
	}
}

// =========================
// Image popups
// =========================
//function popup($medium_img_url, $large_img_url, $title, $width=false, $height=false) 
//{
//	$title_urlencoded = rawurlencode($title);
//	$title_mouseover = addslashes($title);
//	if ( (!is_integer($width)) or (!is_integer($height)) )
//	{
//		$size = @getimagesize("$large_img_url");
//		$width = $size[0];
//		$height = $size[1];
//	}
//	$image =  "<a href=\"#\" onclick=\"window.open('ads_popup.php?z=$large_img_url&width=$width&height=$height&title=$title_urlencoded','photopopup','width=$width,height=$height,directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=no,screenx=150,screeny=150');return false\" onMouseOver=\"window.status='$title_mouseover';return true\" onMouseOut=\"window.status='';return true\"><img src='$medium_img_url' border='0'></a>";
//	return $image;
//}

// ==========================================================
// Set the confirmation code
// ==========================================================
function confirmation_code()
{
$image = imagecreate(120, 30);
$white = imagecolorallocate($image, 0xFF, 0xFF, 0xFF);
$gray = imagecolorallocate($image, 0xC0, 0xC0, 0xC0);
$darkgray = imagecolorallocate($image, 0x50, 0x50, 0x50);

srand((double)microtime()*1000000);

for ($i = 0; $i < 30; $i++) 
{
	$x1 = rand(0,120);
	$y1 = rand(0,30);
	$x2 = rand(0,120);
	$y2 = rand(0,30);
	imageline($image, $x1, $y1, $x2, $y2 , $gray);  
}

for ($i = 0; $i < 5; $i++) 
{
	$cnum[$i] = rand(0,9);
}

for ($i = 0; $i < 5; $i++)
{
	$fnt = rand(5,8);
	$x = $x + rand(12 , 20);
	$y = rand(7 , 12); 
	imagestring($image, $fnt, $x, $y, $cnum[$i] , $darkgray); 
}

$digit = "$cnum[0]$cnum[1]$cnum[2]$cnum[3]$cnum[4]";

session_start();
$_SESSION['digit'] = $digit;

//header('Content-type: image/png');
$image = imagepng($image);
imagedestroy($image);  

return $image;
}

// ==========================================================
// Send a chaser email
// ==========================================================
function chaser_email($sender_email, $recip_email, $subject, $message)
{
	global $board_config, $phpEx, $phpbb_root_path;
	global $email_headers, $user_lang;

include_once($phpbb_root_path . 'includes/emailer.'.$phpEx);

// Left in for debugging
//echo '===============================<br>';
//echo '$sender_email=',$sender_email,'<br>';
//echo '$recip_email=',$recip_email,'<br>';
//echo '$subject=',$subject,'<br>';
//echo '$message=',$message,'<br>';

$emailer = new emailer($board_config['smtp_delivery']);

$emailer->from($sender_email);
$emailer->replyto($sender_email);
					
$emailer->use_template('admin_send_email', $user_lang);
$emailer->email_address($recip_email);
$emailer->set_subject($subject);
$emailer->extra_headers($email_headers);
					
$emailer->assign_vars(array(
	'SITENAME' => $board_config['sitename'], 
	'BOARD_EMAIL' => $board_config['board_email'], 
	'MESSAGE' => $message));

$emailer->send();
$emailer->reset();
}

// ==========================================================
// Create a comma separated value file from an array
// ==========================================================
function create_csv_file($file, $data) 
{
	//check for array
	if (is_array($data)) 
	{ 
		$post_values=array_values($data); 

		//build csv data
		foreach($post_values as $i) 
			{$csv.="\"$i\",";}

		//remove the last comma from string
		$csv = substr($csv,0,-1); 

		//check for existence of file
		if (file_exists($file) and is_writeable($file)) 
			{$mode = "a";} 
		else 
			{$mode="w";}

		//create file pointer
		$fp = @fopen($file,$mode);
 
		//write to file
		fwrite($fp,$csv . "\n"); 

		//close file pointer
		fclose($fp); 

		return true; 
	} 
	else
	{ 
		return false; 
	} 
}

// ==========================================================
// Create a comma separated value file from an array
// ==========================================================
function create_error_file($file, $message) 
{
	$open = fopen ($file, "a");
	if ($open)
	{
		fwrite ($open, $message."\r\n");
		fclose ($open); 
	}
}

// ==========================================================
// Pick a currency code
// ==========================================================
function cc_select($default, $select_name = 'currency_code')
{
	global $lang;

	if ( !isset($default) )
	{
		$default == 'GBP';
	}
	$cc_select = '<select name="' . $select_name . '">';

	while( list($cc_code, $cc_desc) = @each($lang['paypal_cc']) )
	{
		$selected = ( $cc_code == $default ) ? ' selected="selected"' : '';
		$cc_select .= '<option value="' . $cc_code . '"' . $selected . '>' . $cc_desc . '</option>';
	}
	$cc_select .= '</select>';

	return $cc_select;
}

// ==========================================================
// Pick a language code
// ==========================================================
function lc_select($default, $select_name = 'language_code')
{
	global $lang;

	if ( !isset($default) )
	{
		$default == 'GB';
	}
	$lc_select = '<select name="' . $select_name . '">';

	while( list($lc_code, $lc_desc) = @each($lang['paypal_lc']) )
	{
		$selected = ( $lc_code == $default ) ? ' selected="selected"' : '';
		$lc_select .= '<option value="' . $lc_code . '"' . $selected . '>' . $lc_desc . '</option>';
	}
	$lc_select .= '</select>';

	return $lc_select;
}

// ==========================================================
// htmlspecialchars_decode
// ==========================================================
function htmlspecialchars_decode_php4($str, $quote_style = ENT_COMPAT) 
{
	if ( function_exists('htmlspecialchars_decode') )
	{
		return htmlspecialchars_decode($str);
	}
	else
	{
		if ( function_exists('get_html_translation_table') )
		{
			return strtr($str, array_flip(get_html_translation_table(HTML_ENTITIES)));
		}
		else
		{
			return strtr($str, str_replace(array('&amp;', '&#039;', '&quot;', '&lt;', '&gt;'), array('&', "'", '"', '<', '>'), $category));
		}
	}
}
?>