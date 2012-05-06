<?php
/***************************************************************************
 *                                auction.php
 *                            -------------------
 *   begin                :   January 2004
 *   copyright            :   (C) FR
 *   email                :   fr@php-styles.com
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
     include_once($phpbb_root_path . 'auction/auction_common.php');

     // Start session management
     $userdata = session_pagestart($user_ip, AUCTION_ROOM);
     init_userprefs($userdata);
     // End session management

     // Check auction_permission
     checkPermission('VIEW_ALL');

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

      // get Categories
      $sql = "SELECT c.PK_auction_category_id,
                     c.auction_category_title,
                     c.auction_category_order,
                     c.auction_category_icon
              FROM " . AUCTION_CATEGORY_TABLE . " c
              ORDER BY c.auction_category_order";

      if( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Could not query auction-categories', '', __LINE__, __FILE__, $sql);
            }

      while( $auction_category_rows[] = $db->sql_fetchrow($result) );
     // end get Categories

     // get Auction-Room info
     if( ( $total_auction_categories = count($auction_category_rows)-1 ) )
          {
               $sql = "SELECT f.*,
                              p.auction_bid_time,
                              p.FK_auction_bid_user_id,
                              u.username,
                              u.user_id,
                              u.user_level,
                              t.auction_offer_title,
                              t.FK_auction_offer_user_id " .
                   " FROM ((( " . AUCTION_ROOM_TABLE . " f " .
                   " LEFT JOIN " . AUCTION_BID_TABLE . " p ON p.PK_auction_bid_id = f.FK_auction_room_category_id )" .
                   " LEFT JOIN " . USERS_TABLE . " u ON u.user_id = p.FK_auction_bid_user_id ) " .
                   " LEFT JOIN " . AUCTION_OFFER_TABLE . " t ON t.PK_auction_offer_id = p.FK_auction_bid_offer_id ) " .
                   " ORDER BY f.FK_auction_room_category_id, f.auction_room_order";

                if ( !($result = $db->sql_query($sql)) )
                     {
                           message_die(GENERAL_ERROR, 'Could not query auction-rooms', '', __LINE__, __FILE__, $sql);
                     } // if

                while( $auction_room_data[] = $db->sql_fetchrow($result) );

                if ( !($total_auction_rooms = count($auction_room_data)-1) )
                     {
                          message_die(GENERAL_MESSAGE, $lang['auction_no_room'] . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx")) . "\">");
                     } // if
           } // if
      // end Auction-Room info

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

    // Start output of page
    define('SHOW_ONLINE', true);
    $page_title = $lang['auction'];
    include($phpbb_root_path . 'includes/page_header.'.$phpEx);
    include($phpbb_root_path . 'auction/auction_header.'.$phpEx);

    $template->set_filenames(array('body' => 'auction_body.tpl'));
    $template->assign_vars(array(
        'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
        'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
        'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
        'L_ONLINE_EXPLAIN' => $lang['Online_explain'], 
        'L_AUCTION_ROOM' => $lang['auction_room'],
        'L_AUCTION_OFFERS' => $lang['auction_offers'],
        'L_AUCTION_LAST_OFFER_TITLE' => $lang['auction_last_offer_title'],
        'L_AUCTION_ROOM_VIEWS' => $lang['auction_room_views'],
        'MODAUTHOR' => $lang['modauthor'],
        'MODPOWERED' => $lang['modpowered']));

    for($i = 0; $i < $total_auction_categories; $i++)
    {
        $auction_category_id = $auction_category_rows[$i]['PK_auction_category_id'];

        $sql = "SELECT COUNT(PK_auction_room_id) as count_rooms
                FROM " . AUCTION_ROOM_TABLE . "
                WHERE FK_auction_room_category_id=" . $auction_category_id . "";

        if ( !($result = $db->sql_query($sql)) )
            {
                message_die(GENERAL_ERROR, 'Could not query room-count', '', __LINE__, __FILE__, $sql);
            }

        $auction_room_count = $db->sql_fetchrow($result);

            if ( $auction_room_count['count_rooms'] > 0 )
                 {
                     $template->assign_block_vars('auction_category_row', array(
                         'AUCTION_CATEGORY_DESCRIPTION' => $auction_category_rows[$i]['auction_category_title'],
                         'CATEGORY_IMAGE' => ( $auction_category_rows[$i]['auction_category_icon'] )? "<img src=\"auction/images/" . $auction_category_rows[$i]['auction_category_icon'] . "\">" : "",
                         'U_AUCTION_CATEGORY_VIEW' => append_sid("auction.$phpEx?" . POST_AUCTION_CATEGORY_URL . "=" . $auction_category_rows[$i]['PK_auction_category_id'])));
                         
                     for($j = 0; $j <= $total_auction_rooms; $j++)
                         {
                             if ( $auction_room_data[$j]['FK_auction_room_category_id'] == $auction_category_id )
                             {
                                 //$auction_room_id = $auction_room_data[$j]['FK_auction_room_category_id'];

                                     if ( $auction_room_data[$j]['auction_room_state'] == AUCTION_ROOM_LOCKED )
                                          {
                                               $auction_room_status = $phpbb_root_path . $images['auction_locked'];
                                               $folder_alt = $lang['auction_room_locked'];
                                          }
                                     else
                                          {
                                               $auction_room_status = $phpbb_root_path . $images['auction_open'];
                                               $folder_alt = $lang['auction_room_open'];
                                          }

                                     $sql = "SELECT COUNT(*) as auction_offer_count
                                            FROM " . AUCTION_OFFER_TABLE . "
                                            WHERE  FK_auction_offer_room_id=" . $auction_room_data[$j]['PK_auction_room_id'] . "
                                               AND auction_offer_paid=1
                                               ANd auction_offer_state<>2
                                               AND auction_offer_time_start<" . time() . "
                                               AND auction_offer_time_stop>" . time() . "";

                                     if ( !($result = $db->sql_query($sql)) )
                                         {
                                             message_die(GENERAL_ERROR, 'Could not query offer-count', '', __LINE__, __FILE__, $sql);
                                         }
                                     $auction_room_offer_count = $db->sql_fetchrow($result);

                                     // Get last offer info
                                     $sql = "SELECT PK_auction_offer_id,
                                                    auction_offer_title,
                                                    auction_offer_time_start
                                            FROM " . AUCTION_OFFER_TABLE . "
                                            WHERE FK_auction_offer_room_id=" . $auction_room_data[$j]['PK_auction_room_id'] . " AND
                                                  auction_offer_state= " . AUCTION_OFFER_UNLOCKED . " AND
                                                  auction_offer_paid=1 AND
                                                  auction_offer_time_start<" . time() . " AND
                                                  auction_offer_time_stop>" . time() . "
                                            ORDER BY auction_offer_time_start DESC";

                                     if ( !($result = $db->sql_query($sql)) )
                                         {
                                             message_die(GENERAL_ERROR, 'Could not query offers-info', '', __LINE__, __FILE__, $sql);
                                         }
                                     $auction_room_offer_information = $db->sql_fetchrow($result);

                                     if (count($auction_room_offer_information)-1 > 0)
                                          {
                                               $auction_room_last_offer_string = "<a href=\"" . append_sid("auction_offer_view.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $auction_room_offer_information['PK_auction_offer_id']) . "\">" . $auction_room_offer_information['auction_offer_title'] . "</a>";
                                          }
                                     else
                                          {
                                               $auction_room_last_offer_string = $lang['auction_room_no_offer'];
                                          }

                                     $template->assign_block_vars('auction_category_row.auction_room_row',    array(
                                         'AUCTION_ROOM_STATUS_IMG' => $auction_room_status,
                                         'AUCTION_ROOM_TITLE' => $auction_room_data[$j]['auction_room_title'],
                                         'AUCTION_ROOM_DESCRIPTION' => $auction_room_data[$j]['auction_room_description'],
                                         'AUCTION_ROOM_VIEWS' => $auction_room_data[$j]['auction_room_count_view'],
                                         'AUCTION_ROOM_OFFER_COUNT' => $auction_room_offer_count['auction_offer_count'],
                                         'AUCTION_LAST_OFFER' => $auction_room_last_offer_string,
                                         'L_AUCTION_ROOM_STATE_ALT' => $folder_alt,
                                         'AUCTION_ROOM_IMG' => ( $auction_room_data[$j]['auction_room_icon'] ) ? "auction/images/" . $auction_room_data[$j]['auction_room_icon'] : $auction_room_status,
                                         'U_VIEW_AUCTION_ROOM' => append_sid("auction_room.$phpEx?" . POST_AUCTION_ROOM_URL . "=" . $auction_room_data[$j]['PK_auction_room_id'])));
                          }   // auction-room
                   } // if
           }  // auction-category
     }// if ... total_auction_categories


// Generate the page
$template->pparse('body');

include($phpbb_root_path . 'auction/auction_footer.'.$phpEx);
include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>