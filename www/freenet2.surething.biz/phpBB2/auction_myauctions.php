<?php
/***************************************************************************
 *                          auction_myauctions.php
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
     $userdata = session_pagestart($user_ip, AUCTION_MYAUCTION);
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

     // START Include-Blocks
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
     // END Include-Blocks

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

     if( !empty($mode) ) 
     {
         switch($mode)
         {
             case 'my_auctions':

                 $page_title = $lang['auction_myauction_auctions'];
                 include('./includes/page_header.php');
                 include($phpbb_root_path . 'auction/auction_header.'.$phpEx);

                 // check if user is logged in
                 if ( !$userdata['session_logged_in'] ) 
                      {
                           redirect("login.".$phpEx."?redirect=auction_myauctions.".$phpEx."?mode=my_auctions");
                           exit;
                      }
                      
                  $page_title = $lang['auction_myauction_auctions'];

                 // grab all offers for the specific user
                 $sql = "SELECT *
                         FROM " . AUCTION_OFFER_TABLE . "
                         WHERE FK_auction_offer_user_id =" . $userdata['user_id'] . "";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not your auction-offers', '', __LINE__, __FILE__, $sql);
                     } // if

                 $total_offers=0;

                 // load all offers into an array
                 while( $row = $db->sql_fetchrow($result) )
                    {
                        $auction_myoffers[] = $row;
                        $total_offers++;
                    } // while

                 $template->set_filenames(array('body' => 'auction_myauction_my_auctions.tpl'));

                 for ($i=0; $i<$total_offers; $i++)
                     {
                      // not started offers
                      if ( $auction_myoffers[$i]['auction_offer_time_start']>time() )
                        {
                            $template->assign_block_vars('notstartedrow',array(
                                 'AUCTION_OFFER_TITLE' => $auction_myoffers[$i]['auction_offer_title'],
                                 'U_AUCTION_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_myoffers[$i]['PK_auction_offer_id']),
                                 'AUCTION_OFFER_PRICE_LAST' => ( $auction_myoffers[$i]['auction_offer_last_bid_price'] > $auction_myoffers[$i]['auction_offer_price_start'] ) ? $auction_myoffers[$i]['auction_offer_last_bid_price'] . " " . $auction_config_data['currency'] : $lang['auction_no_bid'],
                                 'AUCTION_OFFER_TIME_START' => create_date($board_config['default_dateformat'], $auction_myoffers[$i]['auction_offer_time_start'], $board_config['board_timezone']),
                                 'AUCTION_OFFER_PAID' => ( $auction_myoffers[$i]['auction_offer_paid'] == 1 ) ? $lang['auction_offer_paid'] : "<a href='" . append_sid("auction_offer.php?mode=late_pay&" . POST_AUCTION_OFFER_URL . "=" . $auction_myoffers[$i]['PK_auction_offer_id']) . "'><span style=\"color:#" . $theme['fontcolor3'] . "\">" . $lang['auction_offer_pay_now'] . "</font></a>",
                                 'AUCTION_OFFER_TIME_STOP' => create_date($board_config['default_dateformat'], $auction_myoffers[$i]['auction_offer_time_stop'], $board_config['board_timezone'])));
                        }
                      // offers which are over
                      elseif ($auction_myoffers[$i]['auction_offer_time_stop']<time() OR $auction_myoffers[$i]['auction_offer_state']==2 )
                        {
                            $template->assign_block_vars('alreadyoverrow',array(
                                 'AUCTION_OFFER_TITLE' => $auction_myoffers[$i]['auction_offer_title'],
                                 'U_AUCTION_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_myoffers[$i]['PK_auction_offer_id']),
                                 'AUCTION_OFFER_PRICE_LAST' => ( $auction_myoffers[$i]['auction_offer_last_bid_price'] > $auction_myoffers[$i]['auction_offer_price_start'] ) ? $auction_myoffers[$i]['auction_offer_last_bid_price'] . " " . $auction_config_data['currency'] : $lang['auction_no_bid'],
                                 'AUCTION_OFFER_TIME_START' => create_date($board_config['default_dateformat'], $auction_myoffers[$i]['auction_offer_time_start'], $board_config['board_timezone']),
                                 'AUCTION_OFFER_PAID' => ( $auction_myoffers[$i]['auction_offer_paid'] == 1 ) ? $lang['auction_offer_paid'] : $lang['auction_offer_status_already_over'],
                                 'AUCTION_OFFER_TIME_STOP' => create_date($board_config['default_dateformat'], $auction_myoffers[$i]['auction_offer_time_stop'], $board_config['board_timezone'])));
                        }
                      // running offers
                      else
                        {
                           $template->assign_block_vars('activerow',array(
                                 'AUCTION_OFFER_TITLE' => $auction_myoffers[$i]['auction_offer_title'],
                                 'U_AUCTION_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_myoffers[$i]['PK_auction_offer_id']),
                                 'AUCTION_OFFER_PRICE_LAST' => ( $auction_myoffers[$i]['auction_offer_last_bid_price'] > $auction_myoffers[$i]['auction_offer_price_start'] ) ? "<span class=\"genmed\"><span style=\"color:#" . $theme['fontcolor2'] . "\">" . $auction_myoffers[$i]['auction_offer_last_bid_price'] . " " . $auction_config_data['currency'] . "</font></span>" : "<span class=\"genmed\"><span style=\"color:#" . $theme['fontcolor2'] . "\">" . $lang['auction_no_bid'] . "</font></span>",
                                 'AUCTION_OFFER_TIME_START' => create_date($board_config['default_dateformat'], $auction_myoffers[$i]['auction_offer_time_start'], $board_config['board_timezone']),
                                 'AUCTION_OFFER_STATUS' => ( $auction_myoffers[$i]['auction_offer_paid'] == 1 ) ? $lang['auction_offer_status_active'] : '<span style="color: #FF0000">' . $lang['auction_offer_pay_now'] . '</span>',
                                 'AUCTION_OFFER_STATUS_ACTIVE' => ( $auction_myoffers[$i]['auction_offer_paid'] == 1 ) ? $lang['auction_offer_status_active'] :  $lang['auction_offer_status_not_paid'],
                                 'AUCTION_OFFER_PAID' => ( $auction_myoffers[$i]['auction_offer_paid'] == 1 ) ? $lang['auction_offer_paid'] : "<a href='" . append_sid("auction_offer.php?mode=late_pay&" . POST_AUCTION_OFFER_URL . "=" . $auction_myoffers[$i]['PK_auction_offer_id']) . "' class=\"gensmall\"><span style=\"color:#" . $theme['fontcolor3'] . "\">" . $lang['auction_offer_pay_now'] . "</font></a>",
                                 'AUCTION_OFFER_TIME_STOP' => create_date($board_config['default_dateformat'], $auction_myoffers[$i]['auction_offer_time_stop'], $board_config['board_timezone'])));
                        }
                     }

                 if ( $auction_config_data['auction_allow_direct_sell'] == 1 )
                       {
                         $template->assign_block_vars('direct_sellings',array());
                         // Grab offers the user has direct bought
                         $sql = "SELECT o.auction_offer_title,
                                        o.PK_auction_offer_id,
                                        o.auction_offer_direct_sell_price,
                                        u.user_id,
                                        u.username
                                 FROM (" . AUCTION_OFFER_TABLE . " o
                                 LEFT JOIN " . USERS_TABLE . " u ON o.FK_auction_offer_user_id=u.user_id)
                                 WHERE o.auction_offer_state = 2 AND
                                       o.FK_auction_offer_last_bid_user_id=" . $userdata['user_id'] . "
                                 ORDER BY o.auction_offer_title ASC";

                         if( !($result = $db->sql_query($sql)) )
                               {
                                     message_die(GENERAL_ERROR, 'Could not your direct boughts', '', __LINE__, __FILE__, $sql);
                               } // if

                         $total_direct_boughts=0;

                         // load all offers into an array
                         while( $row = $db->sql_fetchrow($result) )
                               {
                                     $auction_direct_boughts[] = $row;
                                     $total_direct_boughts++;
                               } // while

                         for ($i=0; $i<$total_direct_boughts; $i++)
                              {
                                   $template->assign_block_vars('direct_sellings.direct_boughts',array(
                                        'AUCTION_OFFER_TITLE' => $auction_direct_boughts[$i]['auction_offer_title'],
                                        'U_AUCTION_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_direct_boughts[$i]['PK_auction_offer_id']),
                                        'AUCTION_OFFER_USERNAME' => $auction_direct_boughts[$i]['username'],
                                        'U_AUCTION_OFFER_USERNAME' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $auction_direct_boughts[$i]['user_id']. ""),
                                        'AUCTION_OFFER_DIRECT_SELL_PRICE' => $auction_direct_boughts[$i]['auction_offer_direct_sell_price'] . " " . $auction_config_data['currency']));
                              } // for

                       } // if
                       
                 // grab all bids for the specific user
                 $sql = "SELECT b.*,
                                o.auction_offer_title,
                                o.FK_auction_offer_last_bid_user_id,
                                o.PK_auction_offer_id,
                                o.auction_offer_time_start,
                                o.auction_offer_time_stop,
                                o.auction_offer_last_bid_price,
                                o.auction_offer_price_start
                         FROM (" . AUCTION_BID_TABLE . " b
                         LEFT JOIN " . AUCTION_OFFER_TABLE . " o ON b.FK_auction_bid_offer_id=o.PK_auction_offer_id)
                         WHERE b.FK_auction_bid_user_id =" . $userdata['user_id'] . "
                         GROUP BY o.PK_auction_offer_id
                         ORDER BY b.auction_bid_price DESC, o.auction_offer_last_bid_price ASC";
                         
                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not your auction-bids', '', __LINE__, __FILE__, $sql);
                     } // if

                 $total_bids=0;

                 // load all bids into an array
                 while( $row = $db->sql_fetchrow($result) )
                    {
                        $auction_mybids[] = $row;
                        $total_bids++;
                    } // while

                 $template->set_filenames(array('body' => 'auction_myauction_my_auctions.tpl'));

                 for ($i=0; $i<$total_bids; $i++)
                     {
                      // not started offers
                      if ( $auction_mybids[$i]['auction_offer_time_start']>time() )
                        {
                                       $template->assign_block_vars('notstartedrow_bid',array(
                                            'AUCTION_OFFER_TITLE' => $auction_mybids[$i]['auction_offer_title'],
                                            'U_AUCTION_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_mybids[$i]['PK_auction_offer_id']),
                                            'AUCTION_OFFER_PRICE_LAST' => ( $auction_mybids[$i]['auction_offer_last_bid_price'] > 0 ) ? $auction_mybids[$i]['auction_offer_last_bid_price'] . " " . $auction_config_data['currency'] : $lang['auction_no_bid'],
                                            'AUCTION_OFFER_TIME_START' => create_date($board_config['default_dateformat'], $auction_mybids[$i]['auction_offer_time_start'], $board_config['board_timezone']),
                                            'AUCTION_OFFER_PRICE_MYBID' => ( $auction_mybids[$i]['FK_auction_offer_last_bid_user_id'] <> $userdata['user_id'] ) ? "<span class=\"genmed\"><span style=\"color:#" . $theme['fontcolor2'] . "'\">" . $lang['auction_offer_outbid'] . "</font></span>" : "<span class=\"genmed\"><span style=\"color:#" . $theme['fontcolor2'] . "\">" . $lang['auction_offer_you_have_highest'] . "</font></span>",
                                            'AUCTION_OFFER_TIME_STOP' => create_date($board_config['default_dateformat'], $auction_mybids[$i]['auction_offer_time_stop'], $board_config['board_timezone'])));
                        }
                      // offers which are over
                      elseif ($auction_mybids[$i]['auction_offer_time_stop']<time() OR $auction_mybids[$i]['auction_offer_state']==2)
                        {
                             // Deactivated FR July 04
                             // Just show the bids of the last 10 days
                             //if ( $auction_mybids[$i]['auction_offer_time_stop'] > DateAdd('d',-10,time())  )
                             //     {
                                       $template->assign_block_vars('alreadyoverrow_bid',array(
                                             'AUCTION_OFFER_TITLE' => $auction_mybids[$i]['auction_offer_title'],
                                             'U_AUCTION_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_mybids[$i]['PK_auction_offer_id']),
                                             'AUCTION_OFFER_PRICE_LAST' => ( $auction_mybids[$i]['auction_offer_last_bid_price'] > 0 ) ? $auction_mybids[$i]['auction_offer_last_bid_price'] . " " . $auction_config_data['currency'] : $lang['auction_no_bid'],
                                             'AUCTION_OFFER_PRICE_MYBID' => ( $auction_mybids[$i]['FK_auction_offer_last_bid_user_id'] <> $userdata['user_id'] ) ? "<span class=\"genmed\"><span style=\"color:#" . $theme['fontcolor2'] . "'\">" . $lang['auction_offer_outbid'] . "</font></span>" : "<span class=\"genmed\"><span style=\"color:#" . $theme['fontcolor2'] . "\">" . $lang['auction_offer_you_have_highest'] . "</font></span>",
                                             'AUCTION_OFFER_TIME_START' => create_date($board_config['default_dateformat'], $auction_mybids[$i]['auction_offer_time_start'], $board_config['board_timezone']),
                                             'AUCTION_OFFER_TIME_STOP' => create_date($board_config['default_dateformat'], $auction_mybids[$i]['auction_offer_time_stop'], $board_config['board_timezone'])));
                              //    }
                        }
                      // running offers
                      else
                        {
                           $template->assign_block_vars('activerow_bid',array(
                                 'AUCTION_OFFER_TITLE' => $auction_mybids[$i]['auction_offer_title'],
                                 'U_AUCTION_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_mybids[$i]['PK_auction_offer_id']),
                                 'AUCTION_OFFER_PRICE_LAST' => ( $auction_mybids[$i]['auction_offer_last_bid_price'] > 0 ) ? $auction_mybids[$i]['auction_offer_last_bid_price'] . " " . $auction_config_data['currency']  : $lang['auction_no_bid'],
                                 'AUCTION_OFFER_PRICE_MYBID' => ( $auction_mybids[$i]['FK_auction_offer_last_bid_user_id'] <> $userdata['user_id'] ) ? "<span class=\"genmed\"><span style=\"color:#" . $theme['fontcolor2'] . "'\">" . $lang['auction_offer_outbid'] . "</font></span>" : "<span class=\"genmed\"><span style=\"color:#" . $theme['fontcolor2'] . "\">" . $lang['auction_offer_you_have_highest'] . "</font></span>",
                                 'AUCTION_OFFER_TIME_START' => create_date($board_config['default_dateformat'], $auction_mybids[$i]['auction_offer_time_start'], $board_config['board_timezone']),
                                 'AUCTION_OFFER_TIME_STOP' => create_date($board_config['default_dateformat'], $auction_mybids[$i]['auction_offer_time_stop'], $board_config['board_timezone'])));
                        }
                     }

                 $template->assign_vars(array(
                                 'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
                                 'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
                                 'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
                                 'L_ONLINE_EXPLAIN' => $lang['Online_explain'],
                                 'L_AUCTION_OFFER_PAID' => $lang['auction_offer_paid_status'],
                                 'L_AUCTION_OFFER_STATUS_ACTIV' => $lang['auction_offer_status_active'],
                                 'L_AUCTION_OFFER_STATUS_NOT_STARTED_YET' => $lang['auction_offer_status_not_started_yet'],
                                 'L_AUCTION_OFFER_STATUS_ALREADY_OVER' => $lang['auction_offer_status_already_over'],
                                 'L_AUCTION_MYBIDS' => $lang['auction_mybids'],
                                 'L_MYOFFERS' => $lang['auction_myoffers'],
                                 'L_AUCTION_OFFER_TITLE' => $lang['auction_offer_title'],
                                 'L_AUCTION_OFFER_START' => $lang['auction_offer_time_start'],
                                 'L_AUCTION_OFFER_STOP' => $lang['auction_offer_time_stop'],
                                 'L_AUCTION_OFFER_STATUS' => $lang['auction_offer_time_status'],
                                 'L_AUCTION_OFFER_PRICE_LAST' => $lang['auction_offer_last_price'],
                                 'L_AUCTION_OFFER_PRICE_MYBID' => $lang['auction_offer_price_mybid'],
                                 'L_AUCTION_OFFER_DIRECT_SELL_PRICE' => $lang['auction_offer_direct_sell_price'],
                                 'L_AUCTION_OFFER_SELLER' => $lang['auction_search_seller'],
                                 'L_DIRECT_BOUGHTS' => $lang['auction_offer_direct_boughts']));
                      
                 $template->pparse('body');
                 include($phpbb_root_path . 'auction/auction_footer.'.$phpEx);
                 include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

                 break;

             case 'watchlist':

                 $page_title = $lang['auction_myauction_watchlist'];
                 include('./includes/page_header.php');
                 include($phpbb_root_path . 'auction/auction_header.'.$phpEx);

                 $sql = "SELECT w.*, o.*
                         FROM " . AUCTION_WATCHLIST_TABLE . " w
                         LEFT JOIN " . AUCTION_OFFER_TABLE . " o ON w.FK_auction_offer_id = o.PK_auction_offer_id
                         WHERE FK_auction_user_id =" . $userdata['user_id'] . "";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not your auction-offers', '', __LINE__, __FILE__, $sql);
                     }

                 $total_offers=0;

                 while( $row = $db->sql_fetchrow($result) )
                    {
                        $auction_mywatchlist[] = $row;
                        $total_offers++;
                    }

                 $template->set_filenames(array('body' => 'auction_myauction_watchlist.tpl'));

                 for ($i=0; $i<$total_offers; $i++)
                     {
                      if ( $auction_mywatchlist[$i]['auction_offer_time_start']>time() )
                        {
                            $template->assign_block_vars('notstartedrow',array(
                                 'AUCTION_OFFER_TITLE' => $auction_mywatchlist[$i]['auction_offer_title'],
                                 'U_AUCTION_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_mywatchlist[$i]['PK_auction_offer_id']),
                                 'AUCTION_OFFER_TIME_START' => create_date($board_config['default_dateformat'], $auction_mywatchlist[$i]['auction_offer_time_start'], $board_config['board_timezone']),
                                 'AUCTION_OFFER_TIME_STOP' => create_date($board_config['default_dateformat'], $auction_mywatchlist[$i]['auction_offer_time_stop'], $board_config['board_timezone']),
                                 'AUCTION_OFFER_ADDED_TO_WATCHLIST_TIME' => create_date($board_config['default_dateformat'], $auction_mywatchlist[$i]['auction_watchlist_time'], $board_config['board_timezone']),
                                 'AUCTION_OFFER_PRICE_LAST' => ( $auction_mywatchlist[$i]['auction_offer_last_bid_price'] > $auction_mywatchlist[$i]['auction_offer_price_start'] ) ?  $auction_mywatchlist[$i]['auction_offer_last_bid_price'] . " " . $auction_config_data['currency'] : $auction_mywatchlist[$i]['auction_offer_price_start'] . " " . $auction_config_data['currency'],
                                 'AUCTION_OFFER_DELETE_FROM_WATCHLIST_IMAGE' => $phpbb_root_path . $images['icon_auction_delete'],
                                 'U_AUCTION_OFFER_DELETE_FROM_WATCHLIST_IMAGE' => append_sid("auction_myauctions.php?mode=delete_from_watchlist&" . POST_AUCTION_OFFER_URL . "=" . $auction_mywatchlist[$i]['PK_auction_offer_id'] . ""),
                                 'L_AUCTION_OFFER_DELETE_FROM_WATCHLIST_IMAGE' => $lang['auction_offer_watchlist_delete_from']));
                        }
                      elseif ($auction_mywatchlist[$i]['auction_offer_time_stop']<time() OR $auction_myoffers[$i]['auction_offer_state']==2)
                        {
                            $template->assign_block_vars('alreadyoverrow',array(
                                 'AUCTION_OFFER_TITLE' => $auction_mywatchlist[$i]['auction_offer_title'],
                                 'U_AUCTION_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_mywatchlist[$i]['PK_auction_offer_id']),
                                 'AUCTION_OFFER_TIME_START' => create_date($board_config['default_dateformat'], $auction_mywatchlist[$i]['auction_offer_time_start'], $board_config['board_timezone']),
                                 'AUCTION_OFFER_TIME_STOP' => create_date($board_config['default_dateformat'], $auction_mywatchlist[$i]['auction_offer_time_stop'], $board_config['board_timezone']),
                                 'AUCTION_OFFER_PRICE_LAST' => ( $auction_mywatchlist[$i]['auction_offer_last_bid_price'] > $auction_mywatchlist[$i]['auction_offer_price_start'] ) ? $auction_mywatchlist[$i]['auction_offer_last_bid_price'] . " " . $auction_config_data['currency'] : $auction_mywatchlist[$i]['auction_offer_price_start'] . " " . $auction_config_data['currency'],
                                 'AUCTION_OFFER_ADDED_TO_WATCHLIST_TIME' => create_date($board_config['default_dateformat'], $auction_mywatchlist[$i]['auction_watchlist_time'], $board_config['board_timezone']),
                                 'AUCTION_OFFER_DELETE_FROM_WATCHLIST_IMAGE' => $phpbb_root_path . $images['icon_auction_delete'],
                                 'U_AUCTION_OFFER_DELETE_FROM_WATCHLIST_IMAGE' => append_sid("auction_myauctions.php?mode=delete_from_watchlist&" . POST_AUCTION_OFFER_URL . "=" . $auction_mywatchlist[$i]['PK_auction_offer_id'] . ""),
                                 'L_AUCTION_OFFER_DELETE_FROM_WATCHLIST_IMAGE' => $lang['auction_offer_watchlist_delete_from']));
                        }
                      else
                        {
                           $template->assign_block_vars('activerow',array(
                                 'AUCTION_OFFER_TITLE' => $auction_mywatchlist[$i]['auction_offer_title'],
                                 'U_AUCTION_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_mywatchlist[$i]['PK_auction_offer_id']),
                                 'AUCTION_OFFER_TIME_START' => create_date($board_config['default_dateformat'], $auction_mywatchlist[$i]['auction_offer_time_start'], $board_config['board_timezone']),
                                 'AUCTION_OFFER_TIME_STOP' => create_date($board_config['default_dateformat'], $auction_mywatchlist[$i]['auction_offer_time_stop'], $board_config['board_timezone']),
                                 'AUCTION_OFFER_PRICE_LAST' => ( $auction_mywatchlist[$i]['auction_offer_last_bid_price'] > $auction_mywatchlist[$i]['auction_offer_price_start'] ) ? $auction_mywatchlist[$i]['auction_offer_last_bid_price'] . " " . $auction_config_data['currency'] : $auction_mywatchlist[$i]['auction_offer_price_start'] . " " . $auction_config_data['currency'],
                                 'AUCTION_OFFER_ADDED_TO_WATCHLIST_TIME' => create_date($board_config['default_dateformat'], $auction_mywatchlist[$i]['auction_watchlist_time'], $board_config['board_timezone']),
                                 'AUCTION_OFFER_DELETE_FROM_WATCHLIST_IMAGE' => $phpbb_root_path . $images['icon_auction_delete'],
                                 'U_AUCTION_OFFER_DELETE_FROM_WATCHLIST_IMAGE' => append_sid("auction_myauctions.php?mode=delete_from_watchlist&" . POST_AUCTION_OFFER_URL . "=" . $auction_mywatchlist[$i]['PK_auction_offer_id'] . ""),
                                 'L_AUCTION_OFFER_DELETE_FROM_WATCHLIST_IMAGE' => $lang['auction_offer_watchlist_delete_from']));
                        }
                     }

                 $template->assign_vars(array(
                                  'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
                                  'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
                                  'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
                                  'L_ONLINE_EXPLAIN' => $lang['Online_explain'],
                                 'L_AUCTION_OFFER_STATUS_ACTIV' => $lang['auction_offer_status_active'],
                                 'L_AUCTION_OFFER_STATUS_NOT_STARTED_YET' => $lang['auction_offer_status_not_started_yet'],
                                 'L_AUCTION_OFFER_STATUS_ALREADY_OVER' => $lang['auction_offer_status_already_over'],
                                 'L_AUCTION_OFFER_ADDED_TO_WATCHLIST_TIME' => $lang['auction_offer_added_to_watchlist_time'],
                                 'L_WATCHLIST' => $lang['auction_myauction_watchlist'],
                                 'L_AUCTION_OFFER_TITLE' => $lang['auction_offer_title'],
                                 'L_AUCTION_OFFER_TIME_START' => $lang['auction_offer_time_start'],
                                 'L_AUCTION_OFFER_TIME_STOP' => $lang['auction_offer_time_stop'],
                                 'L_AUCTION_OFFER_STATUS' => $lang['auction_offer_time_status'],
                                 'L_AUCTION_OFFER_PRICE_LAST' => $lang['auction_offer_last_price'],
                                 'L_DELETE' => $lang['delete']));

                 $template->pparse('body');
                 include($phpbb_root_path . 'auction/auction_footer.'.$phpEx);
                 include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

                 break;

             case 'add_to_watchlist':

                  $page_title = $lang['auction_myauction_watchlist'];
                  include('./includes/page_header.php');

                  if ( !$userdata['session_logged_in'] ) 
                      { 
                        redirect("login.".$phpEx."?redirect=auction_myauctions.".$phpEx."?mode=watclist"); 
                        exit; 
                      } 

                  $sql = "SELECT auction_watchlist_time
                          FROM " . AUCTION_WATCHLIST_TABLE . "
                          WHERE FK_auction_offer_id=". $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . " AND
                          FK_auction_user_id=" . $userdata['user_id'] . "";

                  if( !($result = $db->sql_query($sql)) )
                      {
                          message_die(GENERAL_ERROR, 'Could not query if offer is already in watchlist', '', __LINE__, __FILE__, $sql);
                      }

                  $watchlist_row = $db->sql_fetchrow($result);

                  if (count($watchlist_row)>1)
                        {
                            $message = $lang['auction_watchlist_already_in'] . "<br /><br />" . sprintf($lang['Click_return_offer'], "<a href=\"" . append_sid("auction_offer_view.$phpEx?ao=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx") . "\">", "</a>");
                            message_die(GENERAL_MESSAGE, $message);
                        }
                    
                  $sql = "INSERT INTO " . AUCTION_WATCHLIST_TABLE . "
                            (FK_auction_offer_id,
                             FK_auction_user_id,
                             auction_watchlist_time)
                          VALUES (". $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . ",
                                  " . $userdata['user_id'] . ",
                                  ". time() . ")";

                  if( !($result = $db->sql_query($sql)) )
                      {
                          message_die(GENERAL_ERROR, 'Could not insert offer into watchlist-table', '', __LINE__, __FILE__, $sql);
                      }

                  $message = $lang['auction_watchlist_added_successful'] . "<br /><br />" . sprintf($lang['Click_return_offer'], "<a href=\"" . append_sid("auction_offer_view.$phpEx?ao=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx") . "\">", "</a>");
                  message_die(GENERAL_MESSAGE, $message);

                  include($phpbb_root_path . 'auction/auction_footer.'.$phpEx);
                  include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

                  break;

             case 'delete_from_watchlist':

                  $page_title = $lang['auction_myauction_watchlist'];
                  include('./includes/page_header.php');

                  $sql = "DELETE FROM " . AUCTION_WATCHLIST_TABLE . "
                          WHERE FK_auction_offer_id=". $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . " AND  FK_auction_user_id=" . $userdata['user_id'] . "";
                          
                  if( !($result = $db->sql_query($sql)) )
                      {
                          message_die(GENERAL_ERROR, 'Could not delete offer from watchlist-table', '', __LINE__, __FILE__, $sql);
                      }

                  $message = $lang['auction_watchlist_deleted_successful'] . "<br /><br />" . sprintf($lang['Click_return_watchlist'], "<a href=\"" . append_sid("auction_myauctions.$phpEx?mode=watchlist") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx") . "\">", "</a>");
                  message_die(GENERAL_MESSAGE, $message);
                  include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

                  break;

             default:
                 message_die(GENERAL_MESSAGE, $lang['No_mode'] . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx") . "\">"));
                 break;
         }
}

?>