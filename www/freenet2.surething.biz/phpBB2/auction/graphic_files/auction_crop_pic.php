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
 ***************************************************************************/


     if ( !defined('IN_PHPBB') )
          {
               die("Hacking attempt");
          }

     if( isset($HTTP_GET_VARS['pic_id']) )
	  {
		$pic_id = intval($HTTP_GET_VARS['pic_id']);
	  }
     elseif( isset($HTTP_POST_VARS['pic_id']) )
	  {
		$pic_id = intval($HTTP_POST_VARS['pic_id']);
	  }
     else
	  {
		message_die(GENERAL_ERROR, "Oooops, pic_id was empty!");
	  }

     if(!isset($HTTP_GET_VARS['crop'])) // is it not submitted?
          {

	       $sql = "SELECT pic_id
                       FROM ". AUCTION_IMAGE_TABLE ."
                       WHERE pic_id = '$pic_id'";

               if( !($result = $db->sql_query($sql)) )
	            {
		         message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
                    }

               $thispic = $db->sql_fetchrow($result);

               // Start output of page
               $page_title = $lang['Picture_manager'] . " :: " . $lang['Cropping_center'];
               include($phpbb_root_path . 'includes/page_header.'.$phpEx);

               $template->set_filenames(array('body' => 'auction_crop_choice_body.tpl'));

               $template->assign_vars(array(
		    'CROP_IMAGE1' => append_sid($phpbb_root_path . "auction_thumbnail.$phpEx?pic_id=$pic_id&amp;recrop=1&amp;pic_type=2&amp;crop=1"),
		    'CROP_IMAGE2' => append_sid($phpbb_root_path . "auction_thumbnail.$phpEx?pic_id=$pic_id&amp;recrop=1&amp;pic_type=2&amp;crop=2"),
		    'CROP_IMAGE3' => append_sid($phpbb_root_path . "auction_thumbnail.$phpEx?pic_id=$pic_id&amp;recrop=1&amp;pic_type=2&amp;crop=3"),
		    'IMAGE_WIDTH' => $auction_pic_config['auction_offer_thumb_size'],
		    'IMAGE_HEIGHT' => $auction_pic_config['auction_offer_thumb_size'],
		    'L_PIC_MAN' => $lang['Picture_manager'] . '&nbsp;' . $lang['overview'],
		    'U_PIC_MAN' => append_sid("auction_pics_manager.$phpEx?ao=$offer_id"),
		    'L_YOUR_OFFER' => $lang['Your_offer'],
		    'U_YOUR_OFFER' => append_sid("auction_offer_view.$phpEx?ao=$offer_id"),
		    'L_CROP_PIC' => $page_title,
		    'L_CROP' => $lang['Cropping_center'],
		    'L_CROP_SUBTITLE' => $lang['Crop_iur_nail'],
		    'L_CROP_EXPLAIN' => $lang['Crop_iur_nail_exp'],
		    'L_CROP_EXPLAIN_2' => $lang['Crop_iur_nail_exp2'],
		    'L_CROP_CENTER' => $lang['Cropping_center'],
		    'L_PIC_MANAGER' => $lang['Phpbb_auction'] . '&nbsp;' . $lang['Picture_manager'],
		    'U_CROP_IMAGE1' => append_sid("auction_pics_manager.$phpEx?ao=$offer_id&amp;mode=recrop&amp;pic_id=$pic_id&amp;setcrop=1"),
		    'U_CROP_IMAGE2' => append_sid("auction_pics_manager.$phpEx?ao=$offer_id&amp;mode=recrop&amp;pic_id=$pic_id&amp;setcrop=2"),
		    'U_CROP_IMAGE3' => append_sid("auction_pics_manager.$phpEx?ao=$offer_id&amp;mode=recrop&amp;pic_id=$pic_id&amp;setcrop=3")));

		$template->pparse('body');
		
		include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
		exit;
          }
     else
          {
	       message_die(GENERAL_ERROR, 'No cropping possible!<br> No crop information found!');
          }

     exit;

?>