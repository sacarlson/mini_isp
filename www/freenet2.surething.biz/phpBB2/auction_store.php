<?php
/***************************************************************************
 *                          auction_mystore.php
 *                            -------------------
 *   begin                : Monday, Jan 12, 2004
 *   copyright            : (C)  FR
 *   email                : fr@php-styles.com
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

     define('IN_PHPBB', 1);
     //define('SHOW_ONLINE', true);

     $phpbb_root_path = './';
     include_once($phpbb_root_path . 'auction/auction_common.php');

     // Start session management
     $userdata = session_pagestart($user_ip, AUCTION_USER_STORE);
     init_userprefs($userdata);
     // End session management

     //   Information for the standard Who-is-Online-Block
     $total_posts     = get_db_stat('postcount');
     $total_users     = get_db_stat('usercount');
     $newest_userdata = get_db_stat('newestuser');
     $newest_user     = $newest_userdata['username'];
     $newest_uid      = $newest_userdata['user_id'];

     if( $total_posts == 0 )
          {
               $l_total_post_s = $lang['Posted_articles_zero_total'];
          }
     else if( $total_posts == 1 )
          {
               $l_total_post_s = $lang['Posted_article_total'];
          }
     else
          {
               $l_total_post_s = $lang['Posted_articles_total'];
          }
     if( $total_users == 0 )
          {
               $l_total_user_s = $lang['Registered_users_zero_total'];
          }
     else if( $total_users == 1 )
          {
               $l_total_user_s = $lang['Registered_user_total'];
          }
     else
          {
               $l_total_user_s = $lang['Registered_users_total'];
          }
     // End information for standard Who-is-online-Block


     // Mode setting
     if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
     {
         $mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
         $mode = htmlspecialchars($mode);
     }
     else
     {
         $mode = "";
     }

                 $page_title = $lang['auction_myauction_auctions'];
                 include('./includes/page_header.php');

     if( !empty($mode) ) 
     {
         switch($mode)
         {
             case 'info':

                 $sql = "SELECT *
                         FROM " . AUCTION_USER_STORE_TABLE . "
                         WHERE FK_user_id =" . $HTTP_GET_VARS[POST_USERS_URL];

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not query user-store information', '', __LINE__, __FILE__, $sql);
                     } // if

                 $row = $db->sql_fetchrow($result);

                 ( $row['show_block_ticker']) ? includeTickerBlock() : "";
                 ( $row['show_block_rooms']) ? includeAuctionRoomBlock() :"" ;
                 ( $row['show_block_closetoend']) ? includeCloseToEndBlock() :"" ;
                 ( $row['show_block_statistics']) ? includeStatisticBlock() :"" ;
                 ( $row['show_block_myauction']) ? includeMyAuctionsBlock($userdata) :"" ;
                 ( $row['show_block_calendar']) ? includeCalendarBlock() :"" ;
                 ( $row['show_block_search']) ? includeSearchBlock() :"" ;
                 ( $row['show_block_priceinfo']) ? includeTermsBlock() :"" ;
                 ( $row['show_block_specials']) ? includeAuctionSpecialBlock() :"" ;
                 ( $row['show_block_drop_down']) ? includeAuctionDropDownRoomBlock() :"" ;

                 if ( $userdata['user_id'] == $HTTP_GET_VARS[POST_USERS_URL] )
                      {
                          $template->assign_block_vars('info_edit', array(
                               'L_STORE_EDIT' => $lang['store_edit'],
                               'U_STORE_EDIT' => append_sid("auction_mystore.php")));
                      }
                      
                 include($phpbb_root_path . 'auction/auction_header.'.$phpEx);
                 $template->set_filenames(array('body' => 'auction_user_store.tpl'));
                 $template->assign_block_vars('info', array(
                      'L_STORE_VIEW' => $lang['store_view'],
                      'U_STORE_VIEW' => append_sid("auction_store.php?mode=store&" . POST_USERS_URL . "=" . $HTTP_GET_VARS[POST_USERS_URL]),
                      'STORE_NAME' => stripslashes($row['store_name']),
                      'STORE_DESCRIPTION' => stripslashes($row['store_description']),
                      'STORE_HEADER' => stripslashes($row['store_header'])));

                 break;

             case 'store':

                 $sql = "SELECT *
                         FROM " . AUCTION_USER_STORE_TABLE . "
                         WHERE FK_user_id =" . $HTTP_GET_VARS[POST_USERS_URL];

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not query user-store information', '', __LINE__, __FILE__, $sql);
                     } // if

                 $row = $db->sql_fetchrow($result);

                 ( $row['show_block_ticker']) ? includeTickerBlock() : "";
                 ( $row['show_block_rooms']) ? includeAuctionRoomBlock() :"" ;
                 ( $row['show_block_closetoend']) ? includeCloseToEndBlock() :"" ;
                 ( $row['show_block_statistics']) ? includeStatisticBlock() :"" ;
                 ( $row['show_block_myauction']) ? includeMyAuctionsBlock($userdata) :"" ;
                 ( $row['show_block_calendar']) ? includeCalendarBlock() :"" ;
                 ( $row['show_block_search']) ? includeSearchBlock() :"" ;
                 ( $row['show_block_priceinfo']) ? includeTermsBlock() :"" ;
                 ( $row['show_block_specials']) ? includeAuctionSpecialBlock() :"" ;
                 ( $row['show_block_drop_down']) ? includeAuctionDropDownRoomBlock() :"" ;

                 if ( $userdata['user_id'] == $HTTP_GET_VARS[POST_USERS_URL] )
                      {
                          $template->assign_block_vars('info_edit', array(
                               'L_STORE_EDIT' => $lang['store_edit'],
                               'U_STORE_EDIT' => append_sid("auction_mystore.php")));
                      }

                 include($phpbb_root_path . 'auction/auction_header.'.$phpEx);
                 $template->set_filenames(array('body' => 'auction_user_store.tpl'));
                 $template->assign_block_vars('store', array(
                      'STORE_HEADER' => stripslashes($row['store_header']) ));

                  // Grab offer-data
                  $sql = "SELECT t.*,
                                 u.username,
                                 u.user_id,
                                 u2.username as maxbidder_user_name,
                                 u2.user_id as maxbidder_user_id
                          FROM (" . AUCTION_OFFER_TABLE . " t
                          LEFT JOIN " . USERS_TABLE . " u ON u.user_id = t.FK_auction_offer_user_id
                          LEFT JOIN " . USERS_TABLE . " u2 ON u2.user_id = t.FK_auction_offer_last_bid_user_id)
                          WHERE FK_auction_offer_user_id = " . $HTTP_GET_VARS[POST_USERS_URL] . "
                                AND auction_offer_time_stop>" . time() . "
                                AND auction_offer_time_start<" . time() . "
                                AND auction_offer_paid = 1
                                AND auction_offer_state = " . AUCTION_OFFER_UNLOCKED . "
                                AND auction_offer_direct_sell_price > 0
                          ORDER BY t.auction_offer_time_stop;";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not obtain offer information', '', __LINE__, __FILE__, $sql);
                       }

                  $total_offers = 0;
                  while( $row = $db->sql_fetchrow($result) )
                       {
                            $auction_offer_rowset[] = $row;
                            $total_offers++;
                       }
                  $db->sql_freeresult($result);

                  // Dump out the page
                  if( $total_offers )
                       {
                            for($i = 0; $i < $total_offers; $i++)
                                 {
                                      $auction_offer_id = $auction_offer_rowset[$i]['PK_auction_offer_id'];
                                      $auction_offer_title = ( count($orig_word) ) ? preg_replace($orig_word, $replacement_word, $auction_offer_rowset[$i]['auction_offer_title']) : $auction_offer_rowset[$i]['auction_offer_title'];
                                      $view_auction_offer_url = append_sid("auction_offer_view.$phpEx?" . POST_AUCTION_OFFER_URL . "=$auction_offer_id");
                                      $views = $auction_offer_rowset[$i]['auction_offer_views'];

                                      $sql = "SELECT COUNT(pic_id) AS total
		                              FROM " . AUCTION_IMAGE_TABLE . "
		                              WHERE pic_auction_id = $auction_offer_id";

	if( !($result = $db->sql_query($sql)) )
            {
              message_die(GENERAL_ERROR, 'Could not get pic count from auction information :: ' . $auction_offer_id, '', __LINE__, __FILE__, $sql);
            }

		if ( $row = $db->sql_fetchrow($result) )
		{
			$total_pics = ($row['total']) ? $row['total'] : 0;
		}
			else
		{
			$total_pics = 0;
		}

			$auction_config_pic = init_auction_config_pic();
			if($auction_config_pic['auction_offer_pic_approval_admin'] == 1)
			     {
				if( ($userdata['user_level'] == ADMIN) OR ($userdata['user_level'] == MOD) )
				     {
					$x_sql = "";
				     }
				else
				     {
					$x_sql = "AND pic_approval = 0 AND pic_lock = 0 ";
				     }
			     }
			else
			     {
				if( ($userdata['user_level'] == ADMIN) OR ($userdata['user_level'] == MOD) )
				     {
					$x_sql = "";
				     }
				else
				     {
					$x_sql = "AND pic_lock = 0 ";
				     }
			     }

			// specials pictures
			$sql = "SELECT pic_id, crop_id
				FROM " . AUCTION_IMAGE_TABLE . "
				WHERE pic_auction_id = '$auction_offer_id' AND
                                      pic_main = 1 $x_sql";

			if( !($result = $db->sql_query($sql)) )
			     {
				message_die(GENERAL_ERROR, 'Could not get pic  special-offer information  for auction id: ' . $auction_offer_id, '', __LINE__, __FILE__, $sql);
			     }

			$pic_row = $db->sql_fetchrow($result);

			$mini_pic_id = $pic_row['pic_id'];
			$mini_crop_id = $pic_row['crop_id'];

			if ($mini_pic_id > 0 )
			     {
				$pic_yes = 1;
				$image_url = append_sid('auction_thumbnail.' . $phpEx . '?pic_type=3&crop=' . $mini_crop_id . '&pic_id=' . $mini_pic_id);
				$pic_width = $auction_config_pic['auction_offer_mini_size'];
				$pic_height = $auction_config_pic['auction_offer_mini_size'];
			     }
			else
			     {
				$pic_yes = 0;
			     }

            $template->assign_block_vars('store.offer', array(
                       'AUCTION_OFFER_TIME_STOP' => create_date($board_config['default_dateformat'], $auction_offer_rowset[$i]['auction_offer_time_stop'], $board_config['board_timezone'])  . "</br>" . dateDiff(time(), $auction_offer_rowset[$i]['auction_offer_time_stop']),
                       'AUCTION_OFFER_TITLE' => $auction_offer_title,
                       'AUCTION_OFFER_DESCRIPTION' => $auction_offer_rowset[$i]['auction_offer_text'],
                       'AUCTION_OFFER_VIEWS' => $views,
                       'U_AUCTION_OFFER_BUY_NOW' => append_sid("auction_offer.php?mode=buy_now&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id . ""),
                       'IMG_DIRECT_SELL' => $images['direct_sell'],
                       'AUCTION_OFFER_DIRECT_SELL' => ( $auction_offer_rowset[$i]['auction_offer_direct_sell_price'] > 0 ) ? "<img src=\"" . $images['direct_sell'] . "\" border=\"0\" />" : "",
                       'L_AUCTION_OFFER_PICTURE_ALT' => $auction_offer_picture_alt,
                       'AUCTION_OFFER_FIRST_PRICE' => $auction_offer_rowset[$i]['auction_offer_direct_sell_price']  . " " . $auction_config_data['currency'],
                       'AUCTION_SPECIAL_PICTURE' => ( $pic_yes == 0 ) ? '<a href="' . append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id) . '"><img src="' . $images['icon_auction_no_pic'] . '" alt="' . $lang['auction_user_rating_view_offer'] . '" title="' . $lang['auction_user_rating_view_offer'] . '" border="0" /></a>' : '<a href="' . append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id) . '"><img src="'.$image_url . '" width="'.$pic_width.'" height="'.$pic_height.'" alt="' . $lang['auction_user_rating_view_offer'] . '" title="' . $lang['auction_user_rating_view_offer'] . '" border="0" /></a>',
                       'U_VIEW_AUCTION_OFFER' => $view_auction_offer_url));
          }
          }
     else
          {
               // No topics
               $no_offer = ( $auction_room_row['auction_room_state'] == AUCTION_ROOM_LOCKED ) ? $lang['auction_room_locked'] : $lang['no_offer'];
               $template->assign_vars(array('L_NO_OFFER' => $no_offer));
               $template->assign_block_vars('no_offer', array() );
          }


                 break;
         }
    }


     $template->pparse('body');
     include($phpbb_root_path . 'auction/auction_footer.'.$phpEx);
     include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>