<?php
/***************************************************************************
 *                           auction_offer_view.php
 *                            -------------------
 *   begin                :   January 2004
 *   copyright            :   (C) FR
 *   email                :   fr@php-styles.com
 *   Last update          :   DEC 2004 - FR
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
     $phpbb_root_path = './';
     include_once($phpbb_root_path . 'auction/auction_common.php');

     // Start session management
     $userdata = session_pagestart($user_ip, AUCTION_OFFER_VIEW);
     init_userprefs($userdata);
     // End session management

     // Check auction_permission
     checkPermission('VIEW_ALL');
     checkPermission('VIEW_OFFER');

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

     // Include-Blocks
     includeTickerBlock();
     includeAuctionRoomBlock();
     includeCloseToEndBlock();
     includeStatisticBlock();
     includeMyAuctionsBlock($userdata);
     includeCalendarBlock();
     includeSearchBlock();
     includeTermsBlock();
     includeNewsBlock();
     includeAuctionSpecialBlock();
     includeAuctionDropDownRoomBlock();
     includeLastBidsBlock();
     includeNewestOffersBlock();


     // START
     if ( $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] <> "")
          {
            $auction_offer_id = $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];
          }

     // Check if id is set
     if ( $HTTP_POST_VARS['auction_quickview_id'] <> "")
          {
            $auction_offer_id = $HTTP_POST_VARS['auction_quickview_id'];
          }

     // Grab offer data
     $sql = "SELECT o.*,
                    u.username,
                    u.user_id
             FROM (" . AUCTION_OFFER_TABLE . " o
             LEFT JOIN " . USERS_TABLE . " u ON u.user_id = o.FK_auction_offer_user_id)
             WHERE o.PK_auction_offer_id = '" . $auction_offer_id . "'";

     if( !($result = $db->sql_query($sql)) )
         {
                   message_die(GENERAL_ERROR, 'Could not query offer', '', __LINE__, __FILE__, $sql);
         } // End if
     $auction_offer_row = $db->sql_fetchrow($result);

     // Does auction exist with this id ?
     ( $auction_offer_row['auction_offer_title']=="" ) ? message_die(GENERAL_MESSAGE, $lang['auction_offer_does_not_exist']) : "";

     // Do not display "not paid" offers to anybody except creator and admin
     if ($auction_offer_row['auction_offer_paid']==0 AND $auction_offer_row['FK_auction_offer_user_id']<>$userdata['user_id'] AND $userdata['user_level']<>1 )
         {
                  message_die(GENERAL_MESSAGE, $lang['auction_offer_does_not_exist']);
         } // End if

     if (count($auction_offer_row)>0)
         {

               // get username of max-bid
               $sql = "SELECT b.*,
                              u.username,
                              u.user_id,
                              u.user_level
                       FROM (" . AUCTION_BID_TABLE . " b
                       LEFT JOIN " . USERS_TABLE . " u ON u.user_id = b.FK_auction_bid_user_id)
                       WHERE b.FK_auction_bid_offer_id=" . $auction_offer_id . "
                       ORDER BY b.auction_bid_price DESC";

               if( !($result = $db->sql_query($sql)) )
                   {
                             message_die(GENERAL_ERROR, 'Could not query corresponding bids', '', __LINE__, __FILE__, $sql);
                   } // End if

               $auction_offer_max_bidder_id = "-";
               $auction_offer_max_bidder_name = "-";

               while ($auction_corresponding_bidder_row = $db->sql_fetchrow($result))
                    {
                          if ( $auction_offer_max_bidder_name=="-" )
                               {
                                     $auction_offer_max_bidder_price = $auction_corresponding_bidder_row['auction_bid_price'];
                                     $auction_offer_max_bidder_id = $auction_corresponding_bidder_row['user_id'];
                                     $auction_offer_max_bidder_name = $auction_corresponding_bidder_row['username'];
                               }  // End if
                          $auction_corresponding_bidder_matches[] = $auction_corresponding_bidder_row;
                    }  // End while

               // Grab buyer on direct buy
               if ( $auction_offer_row['auction_offer_state'] == 2 )
                    {
                         // get username of max-bid
                         $sql = "SELECT u.username, u.user_id
                                 FROM (" . AUCTION_OFFER_TABLE . " o
                                 LEFT JOIN " . USERS_TABLE . " u ON u.user_id = o.FK_auction_offer_last_bid_user_id)
                                 WHERE o.PK_auction_offer_id=" . $auction_offer_id . "";

                         if( !($result = $db->sql_query($sql)) )
                              {
                                   message_die(GENERAL_ERROR, 'Could not query direct buyer', '', __LINE__, __FILE__, $sql);
                              } // End if

                         $auction_direct_buyer = $db->sql_fetchrow($result);

                         $auction_offer_max_bidder_id = $auction_direct_buyer['user_id'];
                         $auction_offer_max_bidder_name = $auction_direct_buyer['username'];
                    }

               if ( count($auction_corresponding_bidder_matches) == 0 && checkBoolPermission('VIEW_BID_HISTORY') )
                    {
                          $template->assign_block_vars('bidrow', array(
                                   'AUCTION_OFFER_BID_CLASS' => 'row2',
                                   'AUCTION_OFFER_BID_NO' => $lang['auction_no_bid']));
                    } // End if

               for ($i = 0; $i < count($auction_corresponding_bidder_matches); $i++)
                    {
                          // create different colors for bid history
                          // bcmod does not work for every php-version !!!!
                          //if ( bcmod($i+1, 2) == 1 )
                          //     {
                                    $row_class = 'row2';
                          //    }
                          //else
                          //     {
                          //          $row_class = 'row3';
                          //     }  // End if

                          if ( checkBoolPermission('VIEW_BID_HISTORY') )
                               {
                                    $template->assign_block_vars('bidrow', array(
                                         'AUCTION_OFFER_BID_CLASS' => $row_class,
                                         'AUCTION_OFFER_BID_BIDDER_NAME_URL' => append_sid("auction_rating.$phpEx?mode=view&" . POST_USERS_URL . "=" . $auction_corresponding_bidder_matches[$i]['user_id'] . ""),
                                         'AUCTION_OFFER_BID_BIDDER_NAME' => $auction_corresponding_bidder_matches[$i]['username'],
                                         'AUCTION_OFFER_BID_BIDDER_RATING_COUNT' => "(" . getRatingCount($auction_corresponding_bidder_matches[$i]['user_id']) . ")",
                                         'AUCTION_OFFER_BID_BIDDER_RATING' => '<a href="' . append_sid("auction_rating.$phpEx?mode=view&" . POST_USERS_URL . "=" . $auction_corresponding_bidder_matches[$i]['user_id'] . "") . '">[ ' . $lang['auction_user_rating'] . ' ] </a>',
                                         'AUCTION_OFFER_BID_PRICE' => $auction_corresponding_bidder_matches[$i]['auction_bid_price'] . " " . $auction_config_data['currency']));
                               }
                               
                          // if Mr. Admin is watching the offer, he can delete bids
                          if ( $userdata['user_level'] == ADMIN OR checkBoolPermission('DELETE_BID') )
                               {
                                    // regs and auctioneers can only delete own bids
                                    $role = getRole();
                                    if ( $role == 'registered' OR $role == 'auctioneer' )
                                         {
                                              // is the user the bidder ?
                                              if ( $auction_corresponding_bidder_matches[$i]['FK_auction_bid_user_id']==$userdata['user_id'] )
                                                   {
                                                        $template->assign_block_vars('bidrow.delete_bidrow', array(
                                                             'U_AUCTION_OFFER_BID_DELETE' => append_sid("auction_offer.php?mode=delete_bid&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id . "&" . POST_AUCTION_BID_URL . "=" . $auction_corresponding_bidder_matches[$i]['PK_auction_bid_id'] . ""),
                                                             'L_AUCTION_OFFER_BID_DELETE' => $lang['auction_offer_bid_delete']));
                                                   }
                                         }
                                    // admins and auction-mods can delete anybodies bids
                                    else
                                         {
                                              $template->assign_block_vars('bidrow.delete_bidrow', array(
                                                   'U_AUCTION_OFFER_BID_DELETE' => append_sid("auction_offer.php?mode=delete_bid&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id . "&" . POST_AUCTION_BID_URL . "=" . $auction_corresponding_bidder_matches[$i]['PK_auction_bid_id'] . ""),
                                                   'L_AUCTION_OFFER_BID_DELETE' => $lang['auction_offer_bid_delete']));
                                         }
                               }
                    }  // End for
          }
      else
           {
                $auction_offer_bid_total = $lang['auction_no_bid'];
                $auction_offer_bid_max = $lang['auction_no_bid'];
           }   // End if

     // BUY NOW Fields
     if ( ( $auction_offer_row['auction_offer_direct_sell_price']>0 ) && ( $auction_config_data['auction_allow_direct_sell']==1 && $auction_offer_row['auction_offer_state']<>2 ))
          {
                 $template->assign_block_vars('buy_now', array(
                     'L_AUCTION_OFFER_BUY_NOW' => $lang['auction_offer_buy_now'],
                     'L_AUCTION_OFFER_DIRECT_SELL' => $lang['auction_offer_cost_direct_sell'],
                     'AUCTION_OFFER_DIRECT_SELL_PRICE' => $auction_offer_row['auction_offer_direct_sell_price'] . " " . $auction_config_data['currency'],
                     'S_AUCTION_OFFER_BUY_NOW_ACTION' => append_sid("auction_offer.$phpEx?mode=buy_now&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id)));
          }

     // Notice for sold items via buy-now
     if ( $auction_offer_row['auction_offer_state']==2 )
          {

                 $sql = "SELECT user_id, username
                         FROM " . USERS_TABLE . "
                         WHERE user_id=" . $auction_offer_row['FK_auction_offer_last_bid_user_id'] . "";

                 if( !($result = $db->sql_query($sql)) )
                     {
                               message_die(GENERAL_ERROR, 'Could not grab direct buyer', '', __LINE__, __FILE__, $sql);
                     } // End if

                 $auction_direct_buyer_row = $db->sql_fetchrow($result);

                 $template->assign_block_vars('sold', array(
                     'L_AUCTION_OFFER_SOLD_DIRECT' => $lang['auction_offer_sold_to'],
                     'AUCTION_OFFER_BUYER' => $auction_direct_buyer_row['username'],
                     'U_AUCTION_OFFER_BUYER' => append_sid("profile.$phpEx?mode=viewprofile&" . POST_USERS_URL . "=" . $auction_direct_buyer_row['user_id'])));
          }


     // Extra-features currently just for admin
     $auction_offer_delete_image = '';
     $auction_offer_move_image = '';
     $auction_offer_edit_image = '';
     $auction_offer_special_image = '';
     $auction_offer_add_to_watchlist_image = '';

     if ( ( $userdata['user_level'] == ADMIN OR checkBoolPermission('DELETE_OFFER') ) && $auction_offer_row['auction_offer_time_stop'] > time() AND $auction_offer_row['auction_offer_state']<>2)
          {
               if  (( getRole() == 'auctioneer' OR getRole() == 'registered') AND $auction_offer_row['FK_auction_offer_user_id']<>$userdata['user_id'] )
                    {}
               else
                    {
                         $auction_offer_delete_image = '<a href="' . append_sid("auction_offer.$phpEx?mode=delete_confirm&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id) . '"><img src="' . $images['icon_auction_delete'] . '" alt="' . $lang['auction_offer_delete'] . '" title="' . $lang['auction_offer_delete'] . '" border="0" /></a>';
                    }
          }

    if ( $userdata['user_id'] != ANONYMOUS && $auction_offer_row['auction_offer_time_stop'] > time()  AND $auction_offer_row['auction_offer_state']<>2)
          {
                 $auction_offer_add_to_watchlist_link = append_sid("auction_my_auctions.php?mode=add_to_watchlist&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id . "");
                 $auction_offer_add_to_watchlist_image = "<a href=\"" . $auction_offer_add_to_watchlist_link. "\"><img src=\"". $images['icon_auction_watch']  . "\" title=\"" . $lang['auction_offer_add_to_watchlist'] .  "\" border=\"0\"></img></a>";
          }  // End if

     // Feature offer
     if ( ( $userdata['user_level'] == ADMIN OR checkBoolPermission('SPECIAL') ) && $auction_offer_row['auction_offer_time_stop'] > time() && $auction_offer_row['auction_offer_special'] != 1  AND $auction_offer_row['auction_offer_state']<>2)
          {
                $auction_offer_feature_link = append_sid("auction_offer.$phpEx?mode=feature&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id . '');
                $auction_offer_special_image = '<a href="' . append_sid("auction_offer.$phpEx?mode=feature&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id) . '"><img src="' . $images['icon_auction_feature'] . '" alt="' . $lang['auction_offer_feature'] . '" title="' . $lang['auction_offer_feature'] . '" border="0" /></a>';
          }
     else
          {
                $auction_offer_feature_link = "";
          }  // End if

     // Move offer
     if ( $auction_offer_row['auction_offer_time_stop'] > time() AND $auction_offer_row['auction_offer_state']<>2 AND ( $userdata['user_level']==ADMIN OR checkBoolPermission('MOVE') ) )
          {
               if (( getRole() == 'auctioneer' OR getRole() == 'registered') AND $auction_offer_row['FK_auction_offer_user_id']<>$userdata['user_id'] )
                    {}
               else
                    {
                         $auction_offer_move_link = append_sid("auction_offer.$phpEx?mode=move_select&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id . '');
                         $auction_offer_move_image = '<a href="' . append_sid("auction_offer.$phpEx?mode=move_select&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id) . '"><img src="' . $images['icon_auction_move'] . '" alt="' . $lang['auction_offer_move'] . '" title="' . $lang['auction_offer_move'] . '" border="0" /></a>';
                    }
          }
     else
          {
                $auction_offer_move_link = "";
          }  // End if


     // UPDATE OFFER-VIEWS
     $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
             SET auction_offer_views =  auction_offer_views + 1
             WHERE PK_auction_offer_id = " . $auction_offer_id . "";

     if( !($result = $db->sql_query($sql)) )
         {
                   message_die(GENERAL_ERROR, 'Could not update offer views', '', __LINE__, __FILE__, $sql);
         }

     $auction_offer_time_start = create_date($board_config['default_dateformat'], $auction_offer_row['auction_offer_time_start'], $board_config['board_timezone']);
     $auction_offer_time_stop = create_date($board_config['default_dateformat'], $auction_offer_row['auction_offer_time_stop'], $board_config['board_timezone']);
     if ($auction_offer_row['auction_offer_time_stop']<time() )
          {
               $auction_time_remaining = '-';
          }
     else
          {
               $auction_time_remaining = datediff(time(), $auction_offer_row['auction_offer_time_stop']);
          }
     // create auction-offer-message (not started, active, or over)
     if ( $auction_offer_row['auction_offer_time_start'] > time())
          {
                 $auction_offer_time_message = "<font color=\"red\">" . $lang['auction_offer_not_started'] . "</font>";
          }
     elseif ($auction_offer_row['auction_offer_time_stop']<time())
          {
                 $auction_offer_time_message = "<font color=\"red\">" . $lang['auction_offer_over'] . "</font>";
          }
     else
          {
                 $auction_offer_time_message = "<font color=\"red\">" . $lang['auction_offer_status_active'] . "</font>";
          }

     // getting seller-info
     $sql = "SELECT u.username,
                    u.user_id,
                    u.user_posts,
                    u.user_from,
                    u.user_website,
                    u.user_email,
                    u.user_icq,
                    u.user_aim,
                    u.user_yim,
                    u.user_regdate,
                    u.user_msnm,
                    u.user_viewemail,
                    u.user_rank,
                    u.user_sig,
                    u.user_sig_bbcode_uid,
                    u.user_avatar,
                    u.user_avatar_type,
                    u.user_allowavatar,
                    u.user_allowsmile
            FROM " . USERS_TABLE . " u
            WHERE  u.user_id = " . $auction_offer_row['user_id'] . "";

        if ( !($result = $db->sql_query($sql)) )
        {
            message_die(GENERAL_ERROR, "Could not obtain post/user information.", '', __LINE__, __FILE__, $sql);
        }

        $sellerrow = array();
        if ($row = $db->sql_fetchrow($result))
        {
            do
            {
                $sellerrow[] = $row;
            }
            while ($row = $db->sql_fetchrow($result));
            $db->sql_freeresult($result);
        }

        $temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $auction_offer_row['user_id'] . "");
             $profile_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" border="0" /></a>';
             $profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';

             $temp_url = append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=" . $auction_offer_row['user_id'] . "");
             $pm_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" border="0" /></a>';
             $pm = '<a href="' . $temp_url . '">' . $lang['Send_private_message'] . '</a>';

             $email_uri = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $auction_offer_row['user_id']) : 'mailto:' . $sellerrow[0]['user_email'];

             $email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" border="0" /></a>';
             $email = '<a href="' . $email_uri . '">' . $lang['Send_email'] . '</a>';

             $www_img = ( $sellerrow[0]['user_website'] ) ? '<a href="' . $sellerrow[0]['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" border="0" /></a>' : '';
             $www = ( $sellerrow[0]['user_website'] ) ? '<a href="' . $sellerrow[0]['user_website'] . '" target="_userwww">' . $lang['Visit_website'] . '</a>' : '';

             if ( !empty($sellerrow[0]['user_icq']) )
             {
                 $icq_img = '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $sellerrow[0]['user_icq'] . '"><img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" border="0" /></a>';
                 $icq =  '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $sellerrow[0]['user_icq'] . '">' . $lang['ICQ'] . '</a>';
             }
             else
             {
                 $icq_status_img = '';
                 $icq_img = '';
                 $icq = '';
             }

             $aim_img = ( $sellerrow[0]['user_aim'] ) ? '<a href="aim:goim?screenname=' . $sellerrow[0]['user_aim'] . '&amp;message=Hello+Are+you+there?"><img src="' . $images['icon_aim'] . '" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" border="0" /></a>' : '';
             $aim = ( $sellerrow[0]['user_aim'] ) ? '<a href="aim:goim?screenname=' . $sellerrow[0]['user_aim'] . '&amp;message=Hello+Are+you+there?">' . $lang['AIM'] . '</a>' : '';

             $temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $auction_offer_row['user_id'] . "");
             $msn_img = ( $sellerrow[0]['user_msnm'] ) ? '<a href="' . $temp_url . '"><img src="' . $images['icon_msnm'] . '" alt="' . $lang['MSNM'] . '" title="' . $lang['MSNM'] . '" border="0" /></a>' : '';
             $msn = ( $sellerrow[0]['user_msnm'] ) ? '<a href="' . $temp_url . '">' . $lang['MSNM'] . '</a>' : '';

             $yim_img = ( $sellerrow[0]['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $sellerrow[0]['user_yim'] . '&amp;.src=pg"><img src="' . $images['icon_yim'] . '" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" border="0" /></a>' : '';
             $yim = ( $sellerrow[0]['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $sellerrow[0]['user_yim'] . '&amp;.src=pg">' . $lang['YIM'] . '</a>' : '';

             // get image config
             $auction_pic_config = init_auction_config_pic();

             // now we check if pic uploading is enabled (no point in loading anything if its not).
             if($auction_pic_config['auction_offer_pictures_allow'] == 1 AND $auction_offer_row['auction_offer_state']<>2 AND $auction_offer_row['auction_offer_time_stop']> time())
			{
				if( file_exists($phpbb_root_path . 'auction/graphic_files/pics_offer_view.' . $phpEx) )
				{
					include($phpbb_root_path . 'auction/graphic_files/pics_offer_view.' . $phpEx);
				}
				else
				{
					message_die(GENERAL_MESSAGE, 'Cannot find "auction/graphic_files/pics_offer_view.php"!!<br />Please check if the file is present!');

				}
			}
             else
			{
				// ATTENTION... new pic for no pics... only inside the offer.. dont delete the other one!!!
				//$offer_picture_url = "<img src=\"". $phpbb_root_path . 'auction/images/nopic_yet.gif' . "\" alt=\"No image\"></img>";
				$offer_picture_url = "<img src='" . $images['icon_auction_no_pic'] . "'></img>";
			}
     // START RATING WINDOW
     // as SELLER
     // Prepare categories
     $sql = "SELECT *
             FROM " . AUCTION_RATING_TABLE . "";

         if( !$result = $db->sql_query($sql) )
         {
             message_die(GENERAL_ERROR, "Couldn't get list of rating-options", "", __LINE__, __FILE__, $sql);
         }

         $rating_category_list = "";
     while( $row = $db->sql_fetchrow($result) )
         {
             $auction_category_list .= "<option value=\"" . $row['PK_auction_rating_id']. "\">" . $row['auction_rating_title'] . "</option>";
         }

     // Rating as seller if time is over or offer is sold
     if ( ( $auction_offer_row['auction_offer_time_stop']<time() OR
            $auction_offer_row['auction_offer_state']==2 ) &&
          ( $userdata['user_id']==$auction_offer_row['user_id'] &&
            $auction_offer_row['auction_offer_last_bid_price']>0 ))
          {
                 $template->assign_block_vars('raterow', array(
                     'L_AUCTION_RATE_SELLER' => $lang['auction_rate_buyer'],
                     'L_AUCTION_RATING_PERSON'=> $lang['auction_offer_buyer'],
                     'L_AUCTION_RATE_SELLER_TEXT' => $lang['auction_rate_seller_text'],
                     'L_AUCTION_RATE_NOW' => $lang['auction_rate_now'],
                     'L_AUCTION_RATING_CATEGORY' => $lang['auction_rating_category'],
                     'AUCTION_OFFER_RATING_CATEGORIES' => $auction_category_list,
                     'AUCTION_OFFER_OFFERER' => $auction_offer_max_bidder_name,
                     'S_AUCTION_RATE_ACTION' => append_sid("auction_rating.$phpEx?mode=create&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id)));
          }
     // Rating as buyer if time is over or offer is sold
     if ( ( $auction_offer_row['auction_offer_time_stop']<time() &&
            $userdata['user_id']==$auction_offer_max_bidder_id ) OR (
            $auction_offer_row['auction_offer_state']==2 &&
            $auction_offer_row['FK_auction_offer_last_bid_user_id']==$userdata['user_id'] )
            )
          {
                 $template->assign_block_vars('raterow', array(
                     'L_AUCTION_RATE_SELLER' => $lang['auction_rate_seller'],
                     'L_AUCTION_RATING_PERSON'=> $lang['auction_offer_offerer'],
                     'L_AUCTION_RATE_SELLER_TEXT' => $lang['auction_rate_seller_text'],
                     'L_AUCTION_RATE_NOW' => $lang['auction_rate_now'],
                     'AUCTION_OFFER_OFFERER' => $auction_offer_row['username'],
                     'L_AUCTION_RATING_CATEGORY' => $lang['auction_rating_category'],
                     'AUCTION_OFFER_RATING_CATEGORIES' => $auction_category_list,
                     'S_AUCTION_RATE_ACTION' => append_sid("auction_rating.$phpEx?mode=create&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id)));
          }
     // END RATING

     // BEGIN prepare meassage

     $message = $auction_offer_row['auction_offer_text'];
     if ( !$board_config['allow_html'] )
      {
          if ( $board_config['allow_html'] )
          {
              $message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
          }
      }


      // Parse message and/or sig for BBCode if reqd
      if ( $board_config['allow_bbcode'] )
      {
              $message = str_replace ("\n", "<br>", $message);
              $bbcode_uid = make_bbcode_uid();
              $message = bbencode_first_pass( $message, $bbcode_uid );
              $message = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($message, $bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);
//              $message = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($message, 0) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);
      }

      $message = make_clickable($message);

      // Parse smilies
      if ( $board_config['allow_smilies'] )
      {
              $message = smilies_pass($message);
      }
     // END prepare message
     $message = break_text($message);
     
     // Create time-2-end-chart
     if ( $auction_offer_row['auction_offer_time_stop'] > time() AND
          $auction_config_data['auction_show_timeline'] AND
          $auction_offer_row['auction_offer_state']<>2 )
          {
               $time_to_end = daydiff( time() ,$auction_offer_row['auction_offer_time_stop']);
               $time_total = daydiff( $auction_offer_row['auction_offer_time_start'], $auction_offer_row['auction_offer_time_stop']);
               $template->assign_block_vars('timetoend_chart', array(
                    'TIME_TOTAL' => $time_total,
                    'TIME_TO_END' => $time_to_end));

               for ( $i=0; $i<$time_total-$time_to_end; $i++)
                    {
                         $template->assign_block_vars('timetoend_chart.timetoend_chart_over', array(
                              'TIME_TOTAL' => $time_total,
                              'TIME_TO_END' => $time_to_end));
                    }

              for ( $i=0; $i<$time_to_end; $i++)
                   {
                        $template->assign_block_vars('timetoend_chart.timetoend_chart_running', array(
                             'TIME_TOTAL' => $time_total,
                             'TIME_TO_END' => $time_to_end));
                   }
          }

     // Output page
     $page_title = $lang['auction_user_rating_view_offer'] . ' (' . $lang['auction_offer_time_stop'] . ' ' . $auction_offer_time_stop . ') - ' . $auction_offer_row['auction_offer_title'];
     include($phpbb_root_path . 'includes/page_header.'.$phpEx);
     include($phpbb_root_path . 'auction/auction_header.'.$phpEx);


     // Display bid-fields only if auction is still active
     if (  $auction_offer_row['auction_offer_time_stop']>time() && $auction_offer_row['auction_offer_time_start'] < time() && $auction_offer_row['auction_offer_state']<>2 )
          {
                $template->assign_block_vars('bidnowrow', array(
                     'L_AUCTION_YOUR_NAME' => $lang['auction_your_name'],
                     'L_AUCTION_YOUR_AMOUT' => $lang['auction_your_amount'] . "  (" . $auction_config_data['currency'] . ") :",
                     'L_AUCTION_BID_NOW' => $lang['auction_bid_now']));
          }

     if ( ( $auction_config_data['auction_allow_comment'] AND
          checkBoolPermission('COMMENT') AND
          $auction_offer_row['auction_offer_state']<>2 ) )
          {
                // comment section for the seller
                // allow if user is sellter and auction is not over
                // we can now check agains users role as if they wouldnt be allowed to comment the if above would have stopped them
                if ( ( $auction_offer_row['FK_auction_offer_user_id']== $userdata['user_id'] OR getRole()=='administrator' OR getRole()=='moderator' OR $userdata['user_level']==ADMIN ) && $auction_offer_row['auction_offer_time_stop'] > time() )
                     {
                          // if comment is not set, allow commenting
                          if ( $auction_offer_row['auction_offer_comment']=="" )
                               {
                                    $template->assign_block_vars('auction_offer_comment_add', array(
                                         'L_AUCTION_OFFER_COMMENT_ADD_EDIT'=> $lang['auction_offer_comment_add_edit'],
                                         'S_ADD_EDIT_COMMENT' => append_sid("auction_offer.php?mode=add_comment&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_row['PK_auction_offer_id'] . ""),
                                         'AUCTION_OFFER_COMMENT'=> $auction_offer_row['auction_offer_comment']));
                               } // if

                          // if comment is set, just allow commenting if it is switched on in ACP
                          if ( $auction_offer_row['auction_offer_comment']<>"" && $auction_config_data['auction_allow_change_comment'] )
                               {
                                    $template->assign_block_vars('auction_offer_comment_add', array(
                                         'L_AUCTION_OFFER_COMMENT_ADD_EDIT'=> $lang['auction_offer_comment_add_edit'],
                                         'S_ADD_EDIT_COMMENT' => append_sid("auction_offer.php?mode=add_comment&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_row['PK_auction_offer_id'] . ""),
                                         'AUCTION_OFFER_COMMENT'=> $auction_offer_row['auction_offer_comment']));
                               } // if
                     } // if
          } // if
          
                 $sql = "SELECT *
                         FROM " . AUCTION_USER_STORE_TABLE . "
                         WHERE FK_user_id =" . $auction_offer_row['user_id'];

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not query user-store information', '', __LINE__, __FILE__, $sql);
                     } // if

                 $store_row = $db->sql_fetchrow($result);
                 if ( $store_row['pk_auction_store_id'] > 0 )
                      {
                           $template->assign_block_vars('store', array(
                                'L_USER_STORE'=> $lang['auction_user_store'],
                                'U_USER_STORE' => append_sid("auction_store.php?mode=info&" . POST_USERS_URL . "=" . $auction_offer_row['user_id'] . "")));
                      }

     // Create comment field if allowed
     if ( $auction_config_data['auction_allow_comment'] )
          {
               // display additional comments
               if ( $auction_offer_row['auction_offer_comment']<>"" )
                    {
                         $template->assign_block_vars('auction_offer_comment', array(
                              'L_AUCTION_OFFER_COMMENT'=> $lang['auction_offer_comment'],
                              'AUCTION_OFFER_COMMENT_TIME' => create_date($board_config['default_dateformat'], $auction_offer_row['auction_offer_comment_time'], $board_config['board_timezone']),
                              'AUCTION_OFFER_COMMENT'=> $auction_offer_row['auction_offer_comment']));
                    }
          }
          
     // Drop message that offer is over
     if ( $auction_offer_row['auction_offer_time_stop'] < time() )
          {
                $template->assign_block_vars('auction_offer_over', array(
                     'L_AUCTION_OFFER_OVER'=> $lang['auction_offer_over']));
          }
          
     // relist-option
     //if ( $auction_offer_row['auction_offer_time_stop'] < time() && $auction_offer_row['auction_offer_state'] <> 2 )
     if ( ( $auction_offer_row['FK_auction_offer_user_id']== $userdata['user_id'] OR
            getRole()=='administrator' OR
            getRole()=='moderator' OR
            $userdata['user_level']==ADMIN ) &&
            $auction_offer_row['auction_offer_time_stop'] < time() &&
            $auction_offer_row['auction_offer_state'] <> 2)
          {
                $template->assign_block_vars('auction_offer_options', array(
                     'L_AUCTION_OFFER_RELIST'=> $lang['auction_offer_relist'],
                     'U_AUCTION_OFFER_RELIST'=> append_sid("auction_offer.php?mode=add&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id) ));
          }

     $template->set_filenames(array('body' => 'auction_view_offer_body.tpl'));

     $template->assign_vars(array(
        'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
        'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
        'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
        'L_ONLINE_EXPLAIN' => $lang['Online_explain'],
        'L_AUCTION_OFFER_QUICK_VIEW_ID' => $lang['auction_offer_quick_view_id'],

        'AUCTION_OFFER_ID' => $auction_offer_row['PK_auction_offer_id'],
        'AUCTION_OFFER_TITLE' => $auction_offer_row['auction_offer_title'],
        'AUCTION_OFFER_OFFERER_RATINGS' => getRatingCount($auction_offer_row['user_id']),
        'AUCTION_OFFER_OFFERER_URL' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $auction_offer_row['user_id']),
        'AUCTION_OFFER_OFFERER' => $auction_offer_row['username'],
        'AUCTION_OFFER_OFFERER_RATING_URL' => append_sid("auction_rating.$phpEx?mode=view&" . POST_USERS_URL . "=" . $auction_offer_row['user_id'] . ""),
        'L_AUCTION_OFFER_OFFERER_RATING' => $lang['auction_user_rating'],
        'L_AUCTION_OFFER_BID_HISTORY'=> $lang['auction_offer_bid_history'],
        'AUCTION_OFFER_TEXT'=> $message,
        'AUCTION_OFFER_TIME_START'=> $auction_offer_time_start,
        'AUCTION_OFFER_TIME_STOP'=> $auction_offer_time_stop,
        'AUCTION_OFFER_TIME_REMAINING' => $auction_time_remaining,
        'AUCTION_OFFER_PRICE_START'=> $auction_offer_row['auction_offer_price_start'] . " " . $auction_config_data['currency'],
        'AUCTION_OFFER_VIEWS'=> $auction_offer_row['auction_offer_views'],
        'AUCTION_OFFER_BIDS_TOTAL' => count($auction_corresponding_bidder_matches),
        'AUCTION_OFFER_LAST_BID_PRICE'=> $auction_offer_bid_max . " " . $auction_config_data['currency'],
        'ACUTION_ROOM_YOUR_NAME' => $userdata['username'],
        'AUCTION_OFFER_BIDDER' => $auction_corresponding_bidder,
        'AUCTION_OFFER_TIME_MESSAGE' => $auction_offer_time_message,
        'AUCTION_OFFER_DELETE_IMAGE' => $auction_offer_delete_image,
        'AUCTION_OFFER_ADD_TO_WATCHLIST_IMAGE' => $auction_offer_add_to_watchlist_image,
        //'AUCTION_OFFER_PICTURE' => $auction_offer_picture,
        'AUCTION_OFFER_PICTURE' => $offer_picture_url,
        'AUCTION_OFFER_MOVE_IMAGE' =>$auction_offer_move_image,
        'AUCTION_OFFER_EDIT_IMAGE' =>$auction_offer_edit_image,
        'AUCTION_OFFER_SPECIAL_IMAGE' =>$auction_offer_special_image,
        'AUCTION_OFFER_SHIPPING_PRICE' => $auction_offer_row['auction_offer_shipping_price'] . " " . $auction_config_data['currency'],

        'AUCTION_CURRENT_BID' => ( $auction_offer_max_bidder_price>0 ) ? $auction_offer_max_bidder_price . " " . $auction_config_data['currency'] : $auction_offer_row['auction_offer_price_start'] . " " . $auction_config_data['currency'],
        'AUCTION_MINIMUM_BID' => ( $auction_offer_max_bidder_price>0) ? ($auction_offer_max_bidder_price+$auction_offer_row['auction_offer_bid_increase']) . " " . $auction_config_data['currency']: $auction_offer_row['auction_offer_price_start'] . " " . $auction_config_data['currency'],        'AUCTION_SEND_PM' => append_sid("privmsg.$phpEx?mode=post&" . POST_USERS_URL . "=" . $auction_offer_row['user_id']),
        'AUCTION_SEND_EMAIL' => ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $auction_offer_row['user_id']) : 'mailto:' . $sellerrow[0]['user_email'],
        'L_AUCTION_TIME_REMAINING' => $lang['auction_time_remaining'],
        'L_AUCTION_MINIMUM_BID' => $lang['auction_minimum_bid'],
        'L_AUCTION_CURRENT_BID' => $lang['auction_current_bid'],
        'L_AUCTION_SEND_MAIL' => $lang['auction_send_mail'],
        'L_AUCTION_SEND_PM' => $lang['auction_send_pm'],
        'L_AUCTION_VIEW_OTHER_ITEMS' => $lang['auction_view_other_items'],
        'S_AUCTION_VIEW_OTHER_ITEMS' => append_sid("auction_offer.php?mode=search_user&" . POST_USERS_URL . "=" . $auction_offer_row['user_id'] . ""),

        'L_AUCTION_OFFER_SHIPPING_PRICE' => $lang['auction_offer_shipping_price'],
        'L_AUCTION_OFFER_TIME_STATUS' => $lang['auction_offer_time_status'],
        'L_AUCTION_OFFER_OFFERER'=> $lang['auction_offer_offerer'],
        'L_AUCTION_OFFER_TEXT'=> $lang['auction_offer_text'],
        'L_AUCTION_OFFER_TIME_START' => $lang['auction_offer_time_start'],
        'L_AUCTION_OFFER_TIME_STOP' => $lang['auction_offer_time_stop'],
        'L_AUCTION_OFFER_PRICE_START' => $lang['auction_offer_price_start'],
        'L_AUCTION_OFFER_VIEWS' => $lang['auction_offer_views'],
        'L_AUCTION_OFFER_STATE' => $lang['auction_offer_state'],
        'L_AUCTION_YOUR_BID' => $lang['auction_your_bid'],
        'L_AUCTION_OFFER_BIDS_TOTAL' => $lang['auction_offer_bid_total'],

        'L_AUCTION_OFFER_SELLERS_LOCATION' => $lang['auction_offer_sellers_location'],
        'L_AUCTION_OFFER_ACCEPTED_PAYMENTS' => $lang['auction_offer_accepted_payments'],
        'AUCTION_OFFER_SELLERS_LOCATION' => $auction_offer_row['auction_offer_sellers_location'],
        'AUCTION_OFFER_ACCEPTED_PAYMENTS' => $auction_offer_row['auction_offer_accepted_payments'],


        'L_AUCTION_OFFER_LAST_BID_PRICE' => $lang['auction_offer_last_bid_price'],
        'PROFILE_IMG' => $profile_img,
        'PROFILE' => $profile,
        'SEARCH_IMG' => $search_img,
        'SEARCH' => $search,
        'PM_IMG' => $pm_img,
        'PM' => $pm,
        'EMAIL_IMG' => $email_img,
        'EMAIL' => $email,
        'WWW_IMG' => $www_img,
        'WWW' => $www,
        'ICQ_IMG' => $icq_img,
        'ICQ' => $icq,
        'AIM_IMG' => $aim_img,
        'AIM' => $aim,
        'MSN_IMG' => $msn_img,
        'MSN' => $msn,
        'YIM_IMG' => $yim_img,
        'YIM' => $yim,
        'U_AUCTION_OFFER_FEATURE_LINK' => $auction_offer_feature_link,
        'U_AUCTION_OFFER_MOVE_LINK' => $auction_offer_move_link,
        'VOTE_LEFT' => $images['vote_left'],
        'VOTE_RIGHT' => $images['vote_right'],
        'AUCTION_VOTE_RIGHT' => $images['auction_vote_right'],
        'AUCTION_VOTE' => $images['auction_vote'],
        'S_AUCTION_YOUR_BID_ACTION' => append_sid("auction_offer.$phpEx?mode=bid_confirm&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id)));

     // Generate the page
     $template->pparse('body');

     include($phpbb_root_path . 'auction/auction_footer.'.$phpEx);
     include($phpbb_root_path . 'includes/page_tail.'.$phpEx);


?>