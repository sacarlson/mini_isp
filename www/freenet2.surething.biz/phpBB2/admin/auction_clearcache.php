<?php
/***************************************************************************
 *                          admin_album_clearcache.php
 *                             -------------------
 *   begin                : Thursday, February 06, 2003
 *   copyright            : (C) 2003 Smartor
 *   email                : smartor_xp@hotmail.com
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
     $phpbb_root_path = "./../";
     require($phpbb_root_path . 'extension.inc');
     require('./pagestart.' . $phpEx);
     include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);
     include($phpbb_root_path . 'auction/functions_general.php');
     include($phpbb_root_path . 'auction/auction_constants.php');

     require($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_auction_pic_admin.' . $phpEx);
// require($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_auction.' . $phpEx);


if( !isset($HTTP_POST_VARS['confirm']) )
{
	// Start output of page
	$template->set_filenames(array('body' => 'confirm_body.tpl'));

	if(isset($HTTP_GET_VARS['mini']))
	{
		$mode = 1;
		$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Confirm'],
			'MESSAGE_TEXT' => $lang['clear_mini_cache_confirm'],
			'L_NO' => $lang['No'],
			'L_YES' => $lang['Yes'],
			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_CONFIRM_ACTION' => append_sid("auction_clearcache.$phpEx")));
	}
	else if(isset($HTTP_GET_VARS['thumb']))
	{
		$mode = 2;
		$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Confirm'],
			'MESSAGE_TEXT' => $lang['clear_thumb_cache_confirm'],
			'L_NO' => $lang['No'],
			'L_YES' => $lang['Yes'],
			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_CONFIRM_ACTION' => append_sid("auction_clearcache.$phpEx")));
	}
	else if(isset($HTTP_GET_VARS['main']))
	{
		$mode = 3;
		$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Confirm'],
			'MESSAGE_TEXT' => $lang['clear_main_cache_confirm'],
			'L_NO' => $lang['No'],
			'L_YES' => $lang['Yes'],
			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_CONFIRM_ACTION' => append_sid("auction_clearcache.$phpEx")));
	}
	else if(isset($HTTP_GET_VARS['water']))
	{
		$mode = 4;
		$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Confirm'],
			'MESSAGE_TEXT' => $lang['clear_big_water_cache_confirm'],
			'L_NO' => $lang['No'],
			'L_YES' => $lang['Yes'],
			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_CONFIRM_ACTION' => append_sid("auction_clearcache.$phpEx")));
	}
	else if(isset($HTTP_GET_VARS['mainwater']))
	{
		$mode = 5;
		$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Confirm'],
			'MESSAGE_TEXT' => $lang['clear_water_cache_confirm'],
			'L_NO' => $lang['No'],
			'L_YES' => $lang['Yes'],
			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_CONFIRM_ACTION' => append_sid("auction_clearcache.$phpEx")));
	}
	else
	{
		$mode = 0;
		$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Confirm'],
			'MESSAGE_TEXT' => $lang['clear_all_cache_confirm'],
			'L_NO' => $lang['No'],
			'L_YES' => $lang['Yes'],
			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_CONFIRM_ACTION' => append_sid("auction_clearcache.$phpEx")));

	}

	// Generate the page
	$template->pparse('body');
	include('./page_footer_admin.'.$phpEx);
}
else
{
	if($HTTP_POST_VARS['mode'] == 1)
	{
		$cache_dir = @opendir('../' . AUCTION_PICTURE_MINI_PATH);

		while( $cache_file = @readdir($cache_dir) )
		{
			if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
			{
				@unlink('../' . AUCTION_PICTURE_MINI_PATH . $cache_file);
			}
		}

		@closedir($cache_dir);

		message_die(GENERAL_MESSAGE, $lang['mini_cache_cleared_successfully']);
	}
	else if($HTTP_POST_VARS['mode'] == 2)
	{
		$cache_dir = @opendir('../' . AUCTION_PICTURE_CACHE_PATH);

		while( $cache_file = @readdir($cache_dir) )
		{
			if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
			{
				@unlink('../' . AUCTION_PICTURE_CACHE_PATH . $cache_file);
			}
		}

		@closedir($cache_dir);

		message_die(GENERAL_MESSAGE, $lang['Thumbnail_cache_cleared_successfully']);
	}
	else if($HTTP_POST_VARS['mode'] == 3)
	{
		$cache_dir = @opendir('../' . AUCTION_PICTURE_MAIN_PATH);

		while( $cache_file = @readdir($cache_dir) )
		{
			if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
			{
				@unlink('../' . AUCTION_PICTURE_MAIN_PATH . $cache_file);
			}
		}

		@closedir($cache_dir);

		message_die(GENERAL_MESSAGE, $lang['main_cache_cleared_successfully']);
	}
	else if($HTTP_POST_VARS['mode'] == 4)
	{
		$cache_dir = @opendir('../' . AUCTION_PICTURE_WATERMARK_PATH);

		while( $cache_file = @readdir($cache_dir) )
		{
			if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
			{
				@unlink('../' . AUCTION_PICTURE_WATERMARK_PATH . $cache_file);
			}
		}

		@closedir($cache_dir);

		message_die(GENERAL_MESSAGE, $lang['water_cache_cleared_successfully']);
	}
	else if($HTTP_POST_VARS['mode'] == 5)
	{
		$cache_dir = @opendir('../' . AUCTION_PICTURE_MAIN_WATERMARK_PATH);

		while( $cache_file = @readdir($cache_dir) )
		{
			if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
			{
				@unlink('../' . AUCTION_PICTURE_MAIN_WATERMARK_PATH . $cache_file);
			}
		}

		@closedir($cache_dir);

		message_die(GENERAL_MESSAGE, $lang['water_main_cache_cleared_successfully']);
	}
	
	else
	{
		$cache_dir = @opendir('../' . AUCTION_PICTURE_WATERMARK_PATH);

		while( $cache_file = @readdir($cache_dir) )
		{
			if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
			{
				@unlink('../' . AUCTION_PICTURE_WATERMARK_PATH . $cache_file);
			}
		}

		@closedir($cache_dir);

		$cache_dir = @opendir('../' . AUCTION_PICTURE_MAIN_WATERMARK_PATH);

		while( $cache_file = @readdir($cache_dir) )
		{
			if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
			{
				@unlink('../' . AUCTION_PICTURE_MAIN_WATERMARK_PATH . $cache_file);
			}
		}

		@closedir($cache_dir);
		$cache_dir = @opendir('../' . AUCTION_PICTURE_MAIN_PATH);

		while( $cache_file = @readdir($cache_dir) )
		{
			if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
			{
				@unlink('../' . AUCTION_PICTURE_MAIN_PATH . $cache_file);
			}
		}

		@closedir($cache_dir);
		$cache_dir = @opendir('../' . AUCTION_PICTURE_CACHE_PATH);

		while( $cache_file = @readdir($cache_dir) )
		{
			if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
			{
				@unlink('../' . AUCTION_PICTURE_CACHE_PATH . $cache_file);
			}
		}

		@closedir($cache_dir);
		$cache_dir = @opendir('../' . AUCTION_PICTURE_MINI_PATH);

		while( $cache_file = @readdir($cache_dir) )
		{
			if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $cache_file) )
			{
				@unlink('../' . AUCTION_PICTURE_MINI_PATH . $cache_file);
			}
		}

		@closedir($cache_dir);

		message_die(GENERAL_MESSAGE, $lang['All_cache_cleared_successfully']);
	}
}



?>