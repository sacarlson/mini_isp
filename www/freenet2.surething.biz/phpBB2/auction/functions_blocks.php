<?php
/***************************************************************************
 *                             auction_blocks.php
 *                            -------------------
 *   begin                :   January 2004
 *   copyright            :   (C) FR
 *   email                :   fr@php-styles.com
 *
 *   Last Update          :   AUG 2004 - FR
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This hack is released under the GPL License. 
 *   This hack can be freely used, but not distributed, without permission. 
 *   Intellectual Property is retained by the author listed above. 
 *
 ***************************************************************************/

function includeTickerBlock()
     {
          global $db, $template, $lang, $theme;
          $auction_config_data = init_auction_config();

          // Just display if switched on via ACP
          if ( $auction_config_data['auction_block_display_ticker'] == 1 )
             {
               $template->assign_block_vars('ticker_block', array(
                           'AUCTION_TICKER_FONT_COLOR2' => $theme['fontcolor2'],
                           'AUCTION_TICKER_FONT_COLOR3' => $theme['fontcolor3'],
                           'L_OFFER_TICKER' => $lang['auction_ticker']));

               $sql = "SELECT PK_auction_offer_id,
                              auction_offer_title,
                              auction_offer_price_start,
                              auction_offer_last_bid_price
                       FROM " . AUCTION_OFFER_TABLE . "
                       WHERE auction_offer_paid = 1 AND
                             auction_offer_time_stop > " . time() ." AND
                             auction_offer_time_start<" . time() . " AND
                             auction_offer_state<>2
                       LIMIT 0, 20";

               if ( !($result = $db->sql_query($sql)) )
                         {
                             message_die(GENERAL_ERROR, 'Could not query ticker-information', '', __LINE__, __FILE__, $sql);
                         }

               // Loop a string with all ticker info
               while( $ticker_information = $db->sql_fetchrow($result) )
                     {
                          $template->assign_block_vars('ticker_block.ticker_block_offer', array(
                               'AUCTION_TICKER_OFFER_URL' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $ticker_information['PK_auction_offer_id']),
                               'AUCTION_TICKER_OFFER' => $ticker_information['auction_offer_title'],
                               'AUCTION_TICKER_OFFER_FIRST' => $ticker_information['auction_offer_price_start'] . " " . $auction_config_data['currency'],
                               'AUCTION_TICKER_OFFER_LAST' => ( $ticker_information['auction_offer_last_bid_price'] > 0 ) ? $ticker_information['auction_offer_last_bid_price'] . " " . $auction_config_data['currency']: $lang['auction_no_bid']));
                      }  // End while

            } // End if
     } // End function

function includeAuctionDropDownRoomBlock()
     {
           global $db, $template, $lang;
           $auction_room_block_text = "";
           $auction_config_data = init_auction_config();

           // Just display if switched on via ACP
           if ( $auction_config_data['auction_block_display_drop_down_auction_rooms'] == 1 )
              {

              $sql = "SELECT c.PK_auction_category_id ,
                             c.auction_category_title,
                             c.auction_category_order
                      FROM " . AUCTION_ROOM_TABLE . " r, " . AUCTION_CATEGORY_TABLE . " c
                      WHERE r.FK_auction_room_category_id = c.PK_auction_category_id
                      GROUP BY c.PK_auction_category_id, c.auction_category_title, c.auction_category_order
                      ORDER BY c.auction_category_order";
                      
              if ( !($result = $db->sql_query($sql)) )
              {
               message_die(GENERAL_ERROR, "Couldn't obtain auction category list.", "", __LINE__, __FILE__, $sql);
              }

              $auction_category_rows = array();
              while ( $row = $db->sql_fetchrow($result) )
              {
               $auction_category_rows[] = $row;
              }

              if ( $auction_total_categories = count($auction_category_rows) )
              {
               $sql = "SELECT auction_room_title,
                                 PK_auction_room_id, FK_auction_room_category_id
                          FROM " . AUCTION_ROOM_TABLE . "
                          ORDER BY FK_auction_room_category_id, auction_room_order";

               if ( !($result = $db->sql_query($sql)) )
                      {
                          message_die(GENERAL_ERROR, 'Could not query auction-room-block', '', __LINE__, __FILE__, $sql);
                      } // End if

               $auction_room_block_text = "<form name=\"framecombo\"><select name=\"framecombo2\"><option value=\"" . append_sid("auction.php"). "\">" . $lang['auction_room_select'] . "</option>";
               $auction_room_block_row = array();
               while( $auction_row = $db->sql_fetchrow($result) )
                       {
                     $auction_room_block_row[]= $auction_row;
                             //$auction_room_block_text = $auction_room_block_text .  "<option value='" . append_sid("auction_room.php?" . POST_AUCTION_ROOM_URL . "=" . $auction_room_block_row['PK_auction_room_id']) . "'>" . $auction_room_block_row['auction_room_title'] . "</option>";
                       } // End while
               if ( $total_auctions = count($auction_room_block_row) )
               {
                  for($i = 0; $i < $auction_total_categories; $i++)
                  {
                     $boxstring_auctions = '';
                     for($j = 0; $j < $total_auctions; $j++)
                     {
                        if ($auction_room_block_row[$j]['FK_auction_room_category_id'] == $auction_category_rows[$i]['PK_auction_category_id']) {
                           $boxstring_auctions .=  "<option value='" . append_sid("auction_room.php?" . POST_AUCTION_ROOM_URL . "=" . $auction_room_block_row[$j]['PK_auction_room_id']) . "'>" . $auction_room_block_row[$j]['auction_room_title'] . "</option>";
                        }
                     }
                     if ( $boxstring_auctions != '' )
                     {
                        $auction_room_block_text .= '<option value="' . append_sid("auction.php") . '">&nbsp;</option>';
                        $auction_room_block_text .= '<option value="' . append_sid("auction.php") . '">' . $auction_category_rows[$i]['auction_category_title'] . '</option>';
                        $auction_room_block_text .= '<option value="' . append_sid("auction.php") . '">----------------</option>';
                        $auction_room_block_text .= $boxstring_auctions;
                     }
                  }
               }
              }
              $auction_room_block_text .= "</select><input type=\"button\" value=\"" . $lang['auction_room_go'] . "\" class=\"mainoption\" onClick=\"jumpbox()\" ></form>";

                  // Write to template
                  $template->assign_block_vars('auction_drop_down_rooms_block',array(
                              'L_AUCTION_ROOM_BLOCK_TITLE' => $lang['auction_room_block_title'],
                              'AUCTION_ROOM_BLOCK_TEXT' => $auction_room_block_text));
               } // End if
     } // End function
     
function includeAuctionDropDownRoomBlock2()
     {
           global $db, $template, $lang;
           $auction_room_block_text = "";
           $auction_config_data = init_auction_config();

           // Just display if switched on via ACP
           if ( $auction_config_data['auction_block_display_drop_down_auction_rooms'] == 1 )
              {
                  $sql = "SELECT auction_room_title,
                                 PK_auction_room_id
                          FROM " . AUCTION_ROOM_TABLE . "
                          ORDER BY auction_room_title";

                  if ( !($result = $db->sql_query($sql)) )
                      {
                          message_die(GENERAL_ERROR, 'Could not query auction-room-block', '', __LINE__, __FILE__, $sql);
                      } // End if

                 $auction_room_block_text = "<form name=\"framecombo\"><select name=\"framecombo2\"><option value=\"" . append_sid("auction.php"). "\">" . $lang['auction_room_select'] . "</option>";

                  while( $auction_room_block_row = $db->sql_fetchrow($result) )
                       {
                             $auction_room_block_text = $auction_room_block_text .  "<option value='" . append_sid("auction_room.php?" . POST_AUCTION_ROOM_URL . "=" . $auction_room_block_row['PK_auction_room_id']) . "'>" . $auction_room_block_row['auction_room_title'] . "</option>";
                       } // End while

                  $auction_room_block_text = $auction_room_block_text . "</select><input type=\"button\" value=\"" . $lang['auction_room_go'] . "\" class=\"mainoption\" onClick=\"jumpbox()\" ></form>";

                  // Write to template
                  $template->assign_block_vars('auction_drop_down_rooms_block',array(
                              'L_AUCTION_ROOM_BLOCK_TITLE' => $lang['auction_room_block_title'],
                              'AUCTION_ROOM_BLOCK_TEXT' => $auction_room_block_text));
               } // End if
     } // End function

function includeAuctionRoomBlock()
     {
           global $db, $template, $lang;
           $auction_room_block_text = "";
           $auction_config_data = init_auction_config();

           // Just display if switched on via ACP
           if ( $auction_config_data['auction_block_display_auction_rooms'] == 1 )
              {
                  $template->assign_block_vars('auction_rooms_block',array(
                              'L_AUCTION_ROOM_BLOCK_TITLE' => $lang['auction_room_block_title'],
                              'U_AUCTION_HOME' => append_sid("auction.php"),
                              'L_AUCTION_HOME' => $lang['auction_home'],
                              ));

                  $sql = "SELECT auction_room_title,
                                 PK_auction_room_id
                          FROM " . AUCTION_ROOM_TABLE . "
                          ORDER BY auction_room_title";

                  if ( !($result = $db->sql_query($sql)) )
                      {
                          message_die(GENERAL_ERROR, 'Could not query auction-room-block', '', __LINE__, __FILE__, $sql);
                      } // End if

                  while( $auction_room_block_row = $db->sql_fetchrow($result) )
                       {

                            $template->assign_block_vars('auction_rooms_block.room',array(
                                 'U_AUCTION_ROOM_TITLE' => append_sid("auction_room.php?" . POST_AUCTION_ROOM_URL . "=" . $auction_room_block_row['PK_auction_room_id']),
                                 'AUCTION_ROOM_TITLE' => $auction_room_block_row['auction_room_title']));
                       } // End while

                  // Write to template
               } // End if
     } // End function

function includeCloseToEndBlock()
     {
           global $db, $template, $lang;
           $auction_config_data = init_auction_config();

           // Just display if switched on via ACP
           if ( $auction_config_data['auction_block_display_close_to_end'] == 1 )
                {
                     $template->assign_block_vars('close_to_end_block', array(
                               'AUCTION_CLOSE_TO_END' => $lang['auction_close_to_end']));

                     $sql= "SELECT o.auction_offer_title,
                                   o.PK_auction_offer_id,
                                   o.auction_offer_time_stop
                            FROM " . AUCTION_OFFER_TABLE . " o
                            WHERE ( o.auction_offer_state=0 AND
                                    o.auction_offer_time_stop>" . time() . " AND
                                    o.auction_offer_time_start>" . time() . "
                            AND o.auction_offer_paid = 1 )
                            ORDER BY o.auction_offer_time_stop
                            LIMIT 0, " . $auction_config_data['auction_config_close_to_end_number'] . "";

                     if ( !($result = $db->sql_query($sql)) )
                         {
                             message_die(GENERAL_ERROR, 'Could not query close to end block', '', __LINE__, __FILE__, $sql);
                         } // End if
                     $i=1;

                     while  ($auction_close_to_end_block = $db->sql_fetchrow($result))
                          {
                                     $auction_close_to_end_block_string = $auction_close_to_end_block_string . "<a href=\" " . append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_close_to_end_block['PK_auction_offer_id']) . "\">" . $counter . ". " . $auction_close_to_end_block['auction_offer_title'] . "</a> " . datediff( time() ,$auction_close_to_end_block['auction_offer_time_stop']) . "</br>";
                                     $template->assign_block_vars('close_to_end_block.close_to_end_block_offer', array(
                                                  'AUCTION_OFFER_CLOSE_TO_END_TITLE' => "<a href=\" " . append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_close_to_end_block['PK_auction_offer_id']) . "\" class=\"genmed\">" . $i . ". " . substr($auction_close_to_end_block['auction_offer_title'],0,15) . "</a> ",
                                                  'AUCTION_OFFER_CLOSE_TO_END_TIME' => datediff( time() ,$auction_close_to_end_block['auction_offer_time_stop'])));
                                     $i++;
                          } // End while
                 } // End if
     } // End function

function includeStatisticBlock()
     {
          global $db, $template, $lang, $images;
          $auction_config_data = init_auction_config();

          // Just display if switched on via ACP
          if ( $auction_config_data['auction_block_display_statistics'] == 1 )
                {
                       $sql = "SELECT o.auction_offer_title,
                                      b.auction_bid_price,
                                      o.PK_auction_offer_id
                               FROM " . AUCTION_OFFER_TABLE . " o
                               LEFT JOIN " . AUCTION_BID_TABLE . " b ON b.FK_auction_bid_offer_id = o.PK_auction_offer_id
                               WHERE b.FK_auction_bid_offer_id = o.PK_auction_offer_id
                                 AND o.auction_offer_state =0
                                 AND auction_offer_time_start<" . time() . "
                                 AND o.auction_offer_paid = 1
                                 AND o.auction_offer_time_stop>" . time() . "
                               ORDER BY b.auction_bid_price DESC
                               LIMIT 0, 1";

                       if ( !($result = $db->sql_query($sql)) )
                          {
                           message_die(GENERAL_ERROR, 'Could not query statistics', '', __LINE__, __FILE__, $sql);
                          }
                       $highest_bid = $db->sql_fetchrow($result);
                       $highest_bid_title = $highest_bid['auction_offer_title'];
                       $highest_bid_title_link = append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $highest_bid['PK_auction_offer_id']);
                       $highest_bid_price = $highest_bid['auction_bid_price'];


                       if ( ($highest_bid_price==0) || ($highest_bid_price=="") )
                          {
                              $highest_bid_title = $lang['No_bid_so_far'];
                              $highest_bid_price = $lang['No_bid_so_far'];
                              $highest_bid_title_link = '';
                          }

                       $sql= "SELECT auction_offer_title,
                                     auction_offer_last_bid_price,
                                     PK_auction_offer_id
                              FROM " . AUCTION_OFFER_TABLE . "
                              WHERE auction_offer_state=0
                                AND auction_offer_paid=1
                                AND auction_offer_state =0
                                AND auction_offer_time_start<" . time() . "
                                AND auction_offer_time_stop>" . time() . "
                                AND auction_offer_last_bid_price>0
                              ORDER BY auction_offer_last_bid_price ASC
                              LIMIT 0, 1";

                       if ( !($result = $db->sql_query($sql)) )
                           {
                               message_die(GENERAL_ERROR, 'Could not query statistics', '', __LINE__, __FILE__, $sql);
                           }
                       $lowest_bid = $db->sql_fetchrow($result);
                       $lowest_bid_title = $lowest_bid['auction_offer_title'];
                       $lowest_bid_title_link = append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $lowest_bid['PK_auction_offer_id']);
                       $lowest_bid_price = $lowest_bid['auction_offer_last_bid_price'];

                       if ($lowest_bid_price==0)
                          {
                              $lowest_bid_title = $lang['No_bid_so_far'];
                              $lowest_bid_price = $lang['No_bid_so_far'];
                              $lowest_bid_title_link = '';
                          }

                       // get current volume
                       $sql= "SELECT SUM(auction_offer_last_bid_price) as current_volume
                              FROM " . AUCTION_OFFER_TABLE . "
                              WHERE auction_offer_time_start<" . time() . " AND
                                    auction_offer_time_stop>" . time() . " AND
                                    auction_offer_state =0 AND
                                    auction_offer_paid=1";

                       if ( !($result = $db->sql_query($sql)) )
                           {
                               message_die(GENERAL_ERROR, 'Could not query statistics', '', __LINE__, __FILE__, $sql);
                           } // End if

                       $current_volume_row = $db->sql_fetchrow($result);
                       $current_volume = $current_volume_row['current_volume'];

                       // get overall volume
                       $sql= "SELECT SUM(auction_offer_last_bid_price) as overall_volume
                              FROM " . AUCTION_OFFER_TABLE;

                       if ( !($result = $db->sql_query($sql)) )
                           {
                               message_die(GENERAL_ERROR, 'Could not query statistics', '', __LINE__, __FILE__, $sql);
                           } // End if

                       $overall_volume_row = $db->sql_fetchrow($result);
                       $overall_volume = $overall_volume_row['overall_volume'];

                       // Get number of offers
                       $sql= "SELECT COUNT(auction_offer_title) as offer_total
                              FROM " . AUCTION_OFFER_TABLE . " o
                              WHERE o.auction_offer_paid = 1 AND
                                    o.auction_offer_state =0 AND
                                    o.auction_offer_time_start < " . time() . " AND
                                    o.auction_offer_time_stop > " . time();

                       if ( !($result = $db->sql_query($sql)) )
                           {
                               message_die(GENERAL_ERROR, 'Could not query statistics', '', __LINE__, __FILE__, $sql);
                           } // End if

                       $total_offer_row = $db->sql_fetchrow($result);
                       $total_offers = $total_offer_row['offer_total'];
                       
                       // Get number of bids
                       $sql= "SELECT COUNT(auction_bid_price) as bid_total
                              FROM " . AUCTION_BID_TABLE;

                       if ( !($result = $db->sql_query($sql)) )
                           {
                               message_die(GENERAL_ERROR, 'Could not query statistics', '', __LINE__, __FILE__, $sql);
                           } // End if

                       $total_bid_row = $db->sql_fetchrow($result);
                       $total_bids = $total_bid_row['bid_total'];

                       $template->assign_block_vars('statistic_block', array('L_AUCTION_STATISTICS' => $lang['auction_statistics'],
                               'L_STATISTIC_HIGHEST_BID_DESCRIPTION' => $lang['auction_highest_current_auction'],
                               'L_STATISTIC_LOWEST_BID_DESCRIPTION' => $lang['auction_lowest_current_auction'],
                               'L_AUCTION_LAST_OFFER_TITLE' => $lang['auction_last_offer_title'],
                               'L_AUCTION_STATISTIC_TOTAL_BIDS' => $lang['auction_total_bids'],
                               'L_AUCTION_STATISTIC_TOTAL_OFFERS' => $lang['auction_total_offers'],
                               'L_AUCTION_STATISTIC_OVERALL_VOLUME' => $lang['auction_statistic_overall_volume'],
                               'L_AUCTION_STATISTIC_CURRENT_VOLUME' => $lang['auction_statistic_current_volume'],
                               'IMAGE_UP' => $images['icon_auction_up'],
                               'IMAGE_DOWN' => $images['icon_auction_down'],

                               'AUCTION_CURRENT_VOLUME' => $current_volume . " " . $auction_config_data['currency'],
                               'AUCTION_OVERALL_VOLUME' =>$overall_volume . " " . $auction_config_data['currency'],
                               'AUCTION_STATISTIC_TOTAL_BIDS' => $total_bids,
                               'AUCTION_STATISTIC_TOTAL_OFFERS' => $total_offers,
                               'AUCTION_STATISTIC_HIGHEST_BID_OFFER_TITLE' => $highest_bid_title,
                               'AUCTION_STATISTIC_HIGHEST_BID_BID_PRICE' => $highest_bid_price . " " . $auction_config_data['currency'],
                               'AUCTION_STATISTIC_HIGHEST_BID_TITLE_LINK' => $highest_bid_title_link,
                               'AUCTION_STATISTIC_LOWEST_BID_OFFER_TITLE' => $lowest_bid_title,
                               'AUCTION_STATISTIC_LOWEST_BID_BID_PRICE' => $lowest_bid_price . " " . $auction_config_data['currency'],
                               'AUCTION_STATISTIC_LOWEST_BID_TITLE_LINK' => $lowest_bid_title_link));
               } // End if
      } // End function

function includeMyAuctionsBlock($userdata)
      {
            global $template, $lang;
            $auction_config_data = init_auction_config();

            // Just display if switched on via ACP
            if ( $auction_config_data['auction_block_display_myauctions'] == 1 )
                {
                    // If the user is not logged in redirect to login site
                    if ($userdata['user_id']==-1)
                         {
                              $myauctions_auctions_link = append_sid("login.php?redirect=auction_my_auctions.php?mode=my_auctions");
                              $myauctions_watchlist_link = append_sid("login.php?redirect=auction_my_auctions.php?mode=watchlist");
                              $mystore_link = append_sid("login.php?redirect=auction_my_store.php");
                              $my_account_link = append_sid("login.php?redirect=auction_my_account.php&mode=view");
                              $my_ratings_link = append_sid("login.php");
                         }
                    else
                         {
                              $myauctions_auctions_link= append_sid("auction_my_auctions.php?mode=my_auctions&" . POST_USERS_URL . "=" . $userdata['user_id']);
                              $myauctions_watchlist_link= append_sid("auction_my_auctions.php?mode=watchlist&" . POST_USERS_URL . "=" . $userdata['user_id']);
                              $mystore_link = append_sid("auction_my_store.php");
                              $my_account_link = append_sid("auction_my_account.php?mode=view");
                              $my_ratings_link = append_sid('auction_rating.php?mode=view&u=' . $userdata['user_id']);

                         }  // End if

                    $template->assign_block_vars('myauctions_block', array(
                           'L_AUCTION_MYAUCTIONS' => $lang['auction_myauction'],
                           'L_MY_RATINGS' => $lang['auction_my_ratings'],
                           'L_MY_AUCTIONS' => $lang['auction_myauction_auctions'],
                           'L_MY_WATCHLIST' => $lang['auction_myauction_watchlist'],
                           'L_MY_STORE' => $lang['auction_mystore'],
                           'L_MY_ACCOUNT' => $lang['auction_my_account'],
                           'L_AUCTION_FAQ' => $lang['auction_faq'],
                           'L_AUCTION_TERMS' => $lang['auction_terms'],
                           
                           'U_MY_RATINGS' => $my_ratings_link,
                           'U_MY_AUCTIONS' => $myauctions_auctions_link,
                           'U_MY_WATCHLIST' => $myauctions_watchlist_link,
                           'U_MY_STORE' => $mystore_link,
                           'U_MY_ACCOUNT' => $my_account_link,
                           'U_AUCTION_FAQ' => append_sid("auction_faq.php?mode=faq"),
                           'U_AUCTION_TERMS' => append_sid("auction_faq.php?mode=terms")));
                 } // End if
      } // end includeMyAuctionBlock

function includeCalendarBlock()
     {
          global $lang, $template;
          $auction_config_data = init_auction_config();

            // Just display if switched on via ACP
            if ( $auction_config_data['auction_block_display_calendar'] == 1 )
                {
                  $date = getdate();
                  $month_year = $date[month] . " - " . $date[year];
                  $month = date("n");
                  $year = date("Y");
                  $today =date("d");

                  $totaldays = 0;
                  while ( checkdate( $month, $totaldays + 1, $year ) ) 
                     {
                           $totaldays++;
                     } // End while

                  $offset = date( "w", mktime( 0, 0, 0, $month, 1, $year ) ) - 1;

                  // if we need to handle a sunday as 1st of month
                  if ( $offset == -1 )
                       {
                         $offset = 6;
                       }

                  $date_string .=  '<tr>';

                  // Days starting from saturday before 1st of month
                  if ( $offset > 0 )
                       {
                             $date_string .= str_repeat( "<td class=\"row1\" align=\"center\"><span class=\"genmed\">&nbsp;</span></td>", $offset );
                       }  // End if

                  // Days with a number
                  for ( $day = 1; $day <= $totaldays; $day++ )
                      { 
                          if ( $today == $day )
                               {
                                    $date_string .= "<td class=\"row3\" align=\"center\"><span class=\"genmed\"><b>" . $day . '</b></span></td>';
                               }
                          else
                               {
                                    $date_string .= "<td class=\"row2\" align=\"center\"><span class=\"genmed\">" . $day . '</span></td>';
                               }

                          $offset++; 

                          if ( $offset > 6 )
                                  { 
                                       $offset = 0;
                                       $date_string .= '</tr>';
                                       if ( $day < $totaldays ) 
                                             {
                                                  $date_string .= '<tr>';
                                             }  // End if
                                  }  // End if
                      } // End for

                      if ( $offset > 0 )
                         {
                              $offset = 7 - $offset;
                         }  // End if

                      // Days to saturday after last of month
                      if ( $offset > 0 ) 
                          {
                          $date_string .= str_repeat( "<td class=\"row1\" align=\"center\"><span class=\"genmed\">&nbsp;</span></td>", $offset );
                          }  // End if

                  $date_string .= "</tr>";

                  $template->assign_block_vars('calendar_block', array(
                           'AUCTION_CALENDER_MONTH_YEAR' => $month_year,
                           'AUCTION_CALENDER_DAY_BLOCKS' => $date_string,
                           'L_AUCTION_CALENDER_MO' => $lang['auction_calendar_mo'],
                           'L_AUCTION_CALENDER_TU' => $lang['auction_calendar_tu'],
                           'L_AUCTION_CALENDER_WE' => $lang['auction_calendar_we'],
                           'L_AUCTION_CALENDER_TH' => $lang['auction_calendar_th'],
                           'L_AUCTION_CALENDER_FR' => $lang['auction_calendar_fr'],
                           'L_AUCTION_CALENDER_SA' => $lang['auction_calendar_sa'],
                           'L_AUCTION_CALENDER_SU' => $lang['auction_calendar_su']));
               } // End if
     } // End function

function includeSearchBlock()
     {
            global $template, $lang;
            $auction_config_data = init_auction_config();

            // Just display if switched on via ACP
            if ( $auction_config_data['auction_block_display_search'] == 1 )
                {
                    $template->assign_block_vars('search_block', array(
                            'L_AUCTION_SEARCH' => $lang['auction_search'],
                            'L_AUCTION_SEARCH_ITEM' => $lang['auction_search_item'],
                            'L_AUCTION_SEARCH_SELLER' => $lang['auction_search_seller'],
                            'L_AUCTION_QUICK_VIEW_FIND' => $lang['auction_quick_view_find'],
                            'L_AUCTION_QUICK_VIEW_NUMBER' => $lang['auction_quick_view_number'],
                            'L_AUCTION_SEARCH_UNBIDDED' => $lang['auction_search_unbidded'],
                            'S_AUCTION_SEARCH_UNBIDDED' => append_sid("auction_offer.php?mode=search_unbidded"),
                            'S_AUCTION_QUICK_VIEW' => append_sid("auction_offer_view.php"),
                            'L_AUCTION_SITEMAP' => $lang['auction_sitemap'],
                            'U_AUCTION_SITEMAP' => append_sid("auction_sitemap.php"),
                            'L_AUCTION_SEARCH_NEW_OFFERS' => $lang['auction_search_new_offers'],
                            'U_AUCTION_SEARCH_NEW_OFFERS' => append_sid('auction_offer.php?mode=search_newoffers'),
                            'S_AUCTION_SEARCH' => append_sid("auction_offer.php?mode=search")));
                }    // End if
    } // End function

function includeQuickViewBlock()
     {
          global $template, $lang;
          $auction_config_data = init_auction_config();

          // Just display if switched on via ACP
          if ( $auction_config_data['auction_quick_view_search'] == 1 )
              {
                  $template->assign_block_vars('quick_view_block', array(
                          'L_AUCTION_QUICK_VIEW' => $lang['auction_quick_view'],
                          'L_AUCTION_QUICK_VIEW_NUMBER' => $lang['auction_quick_view_number'],
                          'L_AUCTION_QUICK_VIEW_FIND' => $lang['auction_quick_view_find'],
                          'S_AUCTION_QUICK_VIEW' => append_sid("auction_quickview.php")));
                } // end if
    } // End function

function includeTermsBlock()
     {
            global $template, $lang;
            $auction_config_data = init_auction_config();

            // Just display if switched on via ACP
            if ( $auction_config_data['auction_block_display_priceinformation'] == 1 )
                {
                     $auction_config_data = init_auction_config();
                     $template->assign_block_vars('prices_block', array(
                                    'L_AUCTION_PRICES' => $lang['auction_prices'],
                                    'L_AUCTION_PRICE_BOLD' => $lang['auction_price_bold'],
                                    'L_AUCTION_PRICE_ON_TOP' => $lang['auction_price_on_top'],
                                    'L_AUCTION_PRICE_SPECIAL' => $lang['auction_price_special'],
                                    'L_AUCTION_PRICE_BASIC' => $lang['auction_price_basic'],
                                    'L_AUCTION_PAYMENT_ACCEPT' => $lang['auction_payment_accept'],
                                    'L_AUCTION_PRICE_DIRECT_SELL' => $lang['auction_offer_cost_direct_sell'],
                                    'AUCTION_PRICE_BASIC' => $auction_config_data['auction_offer_cost_basic'],
                                    'AUCTION_PRICE_BOLD' => $auction_config_data['auction_offer_cost_bold'],
                                    'AUCTION_PRICE_ON_TOP' => $auction_config_data['auction_offer_cost_on_top'],
                                    'AUCTION_PRICE_SPECIAL' => $auction_config_data['auction_offer_cost_special'],
                                    'AUCTION_PRICE_DIRECT_SELL' => $auction_config_data['auction_offer_cost_direct_sell'],
                                    'AUCTION_CURRENCY' => $auction_config_data['currency']));

                     // Accept paypal payments
                     if ( $auction_config_data['auction_paymentsystem_activate_user_points'] == 1 )
                        {
                             $template->assign_block_vars('prices_block.user_points', array(
                                    'L_AUCTION_PAYMENTSYSTEM_USER_POINTS' => $lang['auction_paymentsystem_user_points_active'] ));
                        }
                     if ( $auction_config_data['auction_paymentsystem_activate_paypal'] == 1 AND $auction_config_data['auction_paymentsystem_activate_user_points'] == 0 )
                        {
                             $template->assign_block_vars('prices_block.paypal', array(
                                    'PAYPAL_IMAGE' => PAYPAL_IMAGE ));
                        }
                     // Accept debit
                     if ( $auction_config_data['auction_paymentsystem_activate_debit'] == 1 AND $auction_config_data['auction_paymentsystem_activate_user_points'] == 0 )
                        {
                             $template->assign_block_vars('prices_block.debit', array(
                                    'L_AUCTION_DEBIT_ACCEPT' => $lang['auction_debit_accept'] ));
                        }

                     // Accept moneybooker payments
                     if ( $auction_config_data['auction_paymentsystem_activate_moneybooker'] == 1 AND $auction_config_data['auction_paymentsystem_activate_user_points'] == 0)
                        {
                             $template->assign_block_vars('prices_block.moneybooker', array(
                                    'MONEYBOOKER_IMAGE' => MONEYBOOKER_IMAGE));
                        }
                 }    // End if
     } // End function

function includeNewsBlock()
     {
          global $template, $lang, $db, $board_config, $auction_config_data;
          global $orig_word, $replacement_word;
          include('./extension.inc');
          include('./includes/bbcode.'.$phpEx);

          if ( $auction_config_data['auction_block_display_news'] == 1 )
               {
                    $template->assign_block_vars('news_block', array('L_AUCTION_NEWS' => $lang['auction_news']));

                    $sql = "SELECT t.*,
                                   u.username,
                                   u.user_id,
                                   p.*,
                                   x.*
                            FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . POSTS_TEXT_TABLE . " x
                            WHERE t.forum_id = " . $auction_config_data['auction_news_forum_id'] . " AND
                                  t.topic_poster = u.user_id AND
                                  p.post_id = t.topic_first_post_id AND
                                  x.post_id = t.topic_first_post_id
                            ORDER BY t.topic_first_post_id DESC
                            LIMIT 0,3";

                    if ( !($result = $db->sql_query($sql)) )
                         {
                              message_die(GENERAL_ERROR, 'Could not query news block information', '', __LINE__, __FILE__, $sql);
                         }

                   $total_topics = 0;
                   while( $row = $db->sql_fetchrow($result) )
                        {
                             $topic_rowset[] = $row;
                             $total_topics++;
                        }
                   $db->sql_freeresult($result);

                   // Dump out the news
                   if( $total_topics )
                        {
                             for($i = 0; $i < $total_topics; $i++)
                                  {
                                       $topic_id = $topic_rowset[$i]['topic_id'];
                                       $post_subject = ( count($orig_word) ) ? preg_replace($orig_word, $replacement_word, $topic_rowset[$i]['topic_title']) : $topic_rowset[$i]['topic_title'];
                                       $topic_poster_url = ( $topic_rowset[$i]['user_id'] != ANONYMOUS ) ? append_sid("profile.$phpEx?mode=viewprofile&" . POST_USERS_URL . "=" . $topic_rowset[$i]['user_id']) : '';
                                       $topic_poster = ( $topic_rowset[$i]['user_id'] != ANONYMOUS ) ? $topic_rowset[$i]['username'] : ( ( $topic_rowset[$i]['post_username'] != "" ) ? $topic_rowset[$i]['post_username'] : $lang['Guest'] );
                                       $topic_time = create_date($board_config['default_dateformat'], $topic_rowset[$i]['topic_time'], $board_config['board_timezone']);
                                       $bbcode_uid = $topic_rowset[$i]['bbcode_uid'];

                                       // Format the message
                                       $message = $topic_rowset[$i]['post_text'];

                                       if( ( !$board_config['allow_html'] ) && ( $topic_rowset[$i]['enable_html'] ))
                                            {
                                                 $message = preg_replace("#(<)([\/]?.*?)(>)#is", "&lt;\\2&gt;", $message);
                                            }

                                       if( ( $board_config['allow_bbcode']) && ( $bbcode_uid != "" ))
                                            {
                                                 $message = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($message, $bbcode_uid) : preg_replace("/\:[0-9a-z\:]+\]/si", "]", $message);
                                            }

                                       $message = make_clickable($message);

                                       // Define censored word matches
                                       if ( empty($orig_word) && empty($replacement_word) )
                                            {
                                                 $orig_word = array();
                                                 $replacement_word = array();
                                                 obtain_word_list($orig_word, $replacement_word);
                                            }

                                       // Replace naughty words
                                       if ( count($orig_word) )
                                            {
                                                 $post_subject = preg_replace($orig_word, $replacement_word, $post_subject);
                                                 $message = preg_replace($orig_word, $replacement_word, $message);
                                            }

                                       // Parse smilies
                                       if( $board_config['allow_smilies'] )
                                       {
                                            if( $topic_rowset[$i]['enable_smilies'] )
                                                 {
                                                      $message = smilies_pass($message);
                                                 }
                                       }

                                       // Replace newlines
                                       $message = str_replace("\n", "\n<br />\n", $message);
                                       $view_topic_url = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id");

                                       $template->assign_block_vars('news_block.content_block', array(
                                            'TOPIC_TITLE' => $post_subject,
                                            'POST_TEXT' => $message,
                                            'TOPIC_POSTER' => $topic_poster,
                                            'U_TOPIC_POSTER' => $topic_poster_url,
                                            'TOPIC_TIME' => ($no_date) ? '' : $topic_time,
                                            'L_VIEW_TOPIC' => $lang['auction_news_more'],
                                            'U_VIEW_TOPIC' => $view_topic_url));
                      }
               }
          }
     }

function includeNewsBlock2()
     {
          global $template, $lang, $db, $board_config;
          include('./includes/bbcode.php');
          $auction_config_data = init_auction_config();

          // Just display if switched on via ACP
          if ( $auction_config_data['auction_block_display_news'] == 1 )
                {
                    $sql = "SELECT t.topic_title, pt.post_id, t.topic_time , u.username, u.user_id, pt.post_text
                            FROM (( " . TOPICS_TABLE . " t
                            LEFT JOIN " . POSTS_TABLE . " p ON t.topic_last_post_id = p.post_id
                            LEFT JOIN " . POSTS_TEXT_TABLE . " pt ON p.post_id = pt.post_id )
                            LEFT JOIN " . USERS_TABLE . " u ON p.poster_id = u.user_id)
                            WHERE t.forum_id= " . $auction_config_data['auction_news_forum_id'] . "
                            ORDER BY p.post_time DESC
                            LIMIT 0, 3" ;

                    if ( !($result = $db->sql_query($sql)) )
                          {
                                         message_die(GENERAL_ERROR, 'Could not query news', '', __LINE__, __FILE__, $sql);
                          } // End if

                    while( $row = $db->sql_fetchrow($result) )
                      {
                         $news_row[] = $row;
                      }   // End while

                     if (count($news_row)>2 )
                         {
                              $template->assign_block_vars('news_block', array(
                                      'L_AUCTION_QUICK_VIEW' => $lang['auction_quick_view'],
                                      'L_AUCTION_QUICK_VIEW_NUMBER' => $lang['auction_quick_view_number'],
                                      'L_AUCTION_QUICK_VIEW_FIND' => $lang['auction_quick_view_find'],
                                      'L_AUCTION_NEWS' => $lang['auction_news'],
                                      'S_AUCTION_QUICK_VIEW' => append_sid("auction_quickview.php"),

                                      'AUCTION_NEWS_TITLE1' => substr(preg_replace('/\:[0-9a-z\:]+\]/si', ']',$news_row[0]['topic_title']), 0, 200),
                                      'AUCTION_NEWS_TITLE2' => substr(preg_replace('/\:[0-9a-z\:]+\]/si', ']',$news_row[1]['topic_title']), 0, 200),
                                      'AUCTION_NEWS_TITLE3' => substr(preg_replace('/\:[0-9a-z\:]+\]/si', ']',$news_row[2]['topic_title']), 0, 200),
                                      'AUCTION_NEWS_AUTHOR1' => "<a href=\"". append_sid("profile.php?mode=viewprofile&" . POST_USERS_URL . "=" . $news_row[0]['user_id']) . "\">" . $news_row[0]['username'] . "</a>" ,
                                      'AUCTION_NEWS_AUTHOR2' => "<a href=\"". append_sid("profile.php?mode=viewprofile&" . POST_USERS_URL . "=" . $news_row[1]['user_id']) . "\">" . $news_row[1]['username'] . "</a>" ,
                                      'AUCTION_NEWS_AUTHOR3' => "<a href=\"". append_sid("profile.php?mode=viewprofile&" . POST_USERS_URL . "=" . $news_row[2]['user_id']) . "\">" . $news_row[2]['username'] . "</a>" ,
                                      'AUCTION_NEWS_DATE1' => create_date($board_config['default_dateformat'],$news_row[0]['topic_time'], $board_config['board_timezone']),
                                      'AUCTION_NEWS_DATE2' => create_date($board_config['default_dateformat'],$news_row[1]['topic_time'], $board_config['board_timezone']),
                                      'AUCTION_NEWS_DATE3' => create_date($board_config['default_dateformat'],$news_row[2]['topic_time'], $board_config['board_timezone']),
                                      'AUCTION_NEWS_TEXT1' => substr($news_row[0]['post_text'], 0, 200) . " ..." ,
                                      'AUCTION_NEWS_TEXT2' => substr($news_row[1]['post_text'], 0, 200) . " ...",
                                      'AUCTION_NEWS_TEXT3' => substr($news_row[2]['post_text'], 0, 200) . " ...",
                                      'AUCTION_NEWS_MORE1' => "<a href=\"". append_sid("viewtopic.php?" . POST_POST_URL . "=" . $news_row[0]['post_id']) . "\">" . $lang['auction_news_more'] . "</a>" ,
                                      'AUCTION_NEWS_MORE2' => "<a href=\"". append_sid("viewtopic.php?" . POST_POST_URL . "=" . $news_row[1]['post_id']) . "\">" . $lang['auction_news_more'] . "</a>" ,
                                      'AUCTION_NEWS_MORE3' => "<a href=\"". append_sid("viewtopic.php?" . POST_POST_URL . "=" . $news_row[2]['post_id']) . "\">" . $lang['auction_news_more'] . "</a>"));
                           }
                      else
                            {
                              $template->assign_block_vars('news_block_1', array(
                                      'L_AUCTION_QUICK_VIEW' => $lang['auction_quick_view'],
                                      'L_AUCTION_QUICK_VIEW_NUMBER' => $lang['auction_quick_view_number'],
                                      'L_AUCTION_QUICK_VIEW_FIND' => $lang['auction_quick_view_find'],
                                      'L_AUCTION_NEWS' => $lang['auction_news'],
                                      'S_AUCTION_QUICK_VIEW' => append_sid("auction_quickview.php"),

                                      'AUCTION_NEWS_TITLE2' => substr($news_row[0]['topic_title'], 0, 200),
                                      'AUCTION_NEWS_AUTHOR2' => "<a href=\"". append_sid("profile.php?mode=viewprofile&" . POST_USERS_URL . "=" . $news_row[0]['user_id']) . "\">" . $news_row[1]['username'] . "</a>" ,
                                      'AUCTION_NEWS_DATE2' => create_date($board_config['default_dateformat'],$news_row[0]['topic_time'], $board_config['board_timezone']),
                                      'AUCTION_NEWS_TEXT2' => substr($news_row[0]['post_text'], 0, 200) . " ...",
                                      'AUCTION_NEWS_MORE2' => "<a href=\"". append_sid("viewtopic.php?" . POST_POST_URL . "=" . $news_row[0]['post_id']) . "\">" . $lang['auction_news_more'] . "</a>"));
                           }
                }    // End if
      } // End function

function includeAuctionSpecialBlock()
     {
          global $template, $lang, $db, $board_config, $userdata, $phpEx, $images;
          $auction_config_data = init_auction_config();

          // Just display if switched on via ACP
          if ( $auction_config_data['auction_block_display_specials'] == 1 )
               {
		     $sql = "SELECT PK_auction_offer_id,
                                    auction_offer_title,
                                    auction_offer_picture,
                                    auction_offer_time_stop
                             FROM " . AUCTION_OFFER_TABLE . "
                             WHERE auction_offer_state=0 AND
                                   auction_offer_special=1 AND
                                   auction_offer_paid=1 AND
                                   auction_offer_time_start < " . time () . " AND
                                   auction_offer_time_stop > " . time () . "
                             ORDER BY auction_offer_time_stop DESC
                             LIMIT 0, " . $auction_config_data['auction_block_specials_limit'] . "";

                     if ( !($result = $db->sql_query($sql)) )
		          {
			       message_die(GENERAL_ERROR, 'Could not query news', '', __LINE__, __FILE__, $sql);
                          } // End if

                     $specials_count = 0;
		     $specials_row = array();
		     while( $row = $db->sql_fetchrow($result) )
                          {
			       $specials_row[] = $row;
			       $specials_count++;
                          } // End while

		     $template->assign_block_vars('special_block', array(
                          'L_AUCTION_SPECIAL_OFFERS_TITLE' => $lang['auction_special_offers']));

		for ( $i = 0; $i < $specials_count; $i++)
		     {
			$auction_offer_id = $specials_row[$i]['PK_auction_offer_id'];

			// BEGIN include auction-pic-config information
			$auction_config_pic = init_auction_config_pic();
			// END include auction-pic-config information
			// get info for admin and mod. Admin and mod see the offer-pic even if it has not been validated yet
			// this is only valid if validation is active
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

			// special pictures end
			$template->assign_block_vars('special_block.special_offer_block', array(
                             'AUCTION_SPECIAL_TITLE' => subStr($specials_row[$i]['auction_offer_title'],0,20),
                             'U_AUCTION_SPECIAL_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $specials_row[$i]['PK_auction_offer_id']),
			     'AUCTION_SPECIAL_END' => datediff( time() ,$specials_row[$i]['auction_offer_time_stop']),
			     'AUCTION_SPECIAL_IMAGE' => ( $pic_yes == 0 ) ? '<a href="' . append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $specials_row[$i]['PK_auction_offer_id']) . '"><img src="' . $images['icon_auction_no_pic'] . '" alt="' . $lang['auction_user_rating_view_offer'] . '" title="' . $lang['auction_user_rating_view_offer'] . '" border="0" /></a>' : '<a href="' . append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $specials_row[$i]['PK_auction_offer_id']) . '"><img src="'.$image_url . '" width="'.$pic_width.'" height="'.$pic_height.'" alt="' . $lang['auction_user_rating_view_offer'] . '" title="' . $lang['auction_user_rating_view_offer'] . '" border="0" /></a>'));
		} // End for
	} // End if
} // End function


function includeLastBidsBlock()
     {
           global $db, $template, $lang, $board_config;
           $auction_config_data = init_auction_config();

           // Just display if switched on via ACP
           if ( $auction_config_data['auction_block_display_last_bids'] == 1 )
                {
                     $template->assign_block_vars('last_bids_block', array(
                               'L_AUCTION_LAST_BIDS' => $lang['auction_last_bids']));

                     $sql= "SELECT o.auction_offer_title,
                                   o.PK_auction_offer_id,
                                   b.auction_bid_time
                            FROM ( " . AUCTION_BID_TABLE . " b
                                 LEFT JOIN " . AUCTION_OFFER_TABLE . " o
                                 ON b.FK_auction_bid_offer_id = o.PK_auction_offer_id )
                            WHERE ( o.auction_offer_state=0 AND
                                    o.auction_offer_time_stop>" . time() . "
                            AND o.auction_offer_paid = 1 )
                            ORDER BY b.auction_bid_time
                            LIMIT 0, " . $auction_config_data['auction_config_last_bids_number'] . "";

                     if ( !($result = $db->sql_query($sql)) )
                         {
                             message_die(GENERAL_ERROR, 'Could not query last bids block', '', __LINE__, __FILE__, $sql);
                         } // End if
                     $i=1;
                     while  ($auction_last_bids_block = $db->sql_fetchrow($result))
                          {
//                                     $auction_close_to_end_block_string = $auction_close_to_end_block_string . "<a href=\" " . append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_close_to_end_block['PK_auction_offer_id']) . "\">" . $counter . ". " . $auction_close_to_end_block['auction_offer_title'] . "</a> " . datediff( time() ,$auction_close_to_end_block['auction_offer_time_stop']) . "</br>";

                                     $template->assign_block_vars('last_bids_block.last_bids_block_offer', array(
                                                  'AUCTION_OFFER_LAST_BIDS_TITLE_URL' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_last_bids_block['PK_auction_offer_id']),
                                                  'AUCTION_OFFER_LAST_BIDS_TITLE' => $i . ". " . substr($auction_last_bids_block['auction_offer_title'],0,15),
                                                  'AUCTION_OFFER_LAST_BIDS_TIME' => create_date($board_config['default_dateformat'], $auction_last_bids_block['auction_bid_time'], $board_config['board_timezone'])));
                                     $i++;
                          } // End while
                 } // End if
     } // End function

function includeNewestOffersBlock()
     {
           global $db, $template, $lang, $board_config;
           $auction_config_data = init_auction_config();

           // Just display if switched on via ACP
           if ( $auction_config_data['auction_block_display_newest_offers'] == 1 )
                {
                     $template->assign_block_vars('newest_offers_block', array(
                               'AUCTION_NEWEST_OFFER' => $lang['auction_newest_offer']));

                     $sql= "SELECT o.auction_offer_title,
                                   o.PK_auction_offer_id,
                                   o.auction_offer_time_start
                            FROM " . AUCTION_OFFER_TABLE . " o
                            WHERE ( o.auction_offer_state=0 AND
                                    o.auction_offer_time_stop>" . time() . " AND
                                    o.auction_offer_time_start<" . time() . "
                            AND o.auction_offer_paid = 1 )
                            ORDER BY o.auction_offer_time_start DESC
                            LIMIT 0, " . $auction_config_data['auction_config_newest_offers_number'] . "";

                     if ( !($result = $db->sql_query($sql)) )
                         {
                             message_die(GENERAL_ERROR, 'Could not query newest offer block', '', __LINE__, __FILE__, $sql);
                         } // End if

                     while  ($auction_newest_offers_block = $db->sql_fetchrow($result))
                          {
                                     $template->assign_block_vars('newest_offers_block.newest_offers_block_offer', array(
                                                  'U_AUCTION_NEWEST_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $auction_newest_offers_block['PK_auction_offer_id']),
                                                  'AUCTION_NEWEST_OFFER_TITLE' => substr($auction_newest_offers_block['auction_offer_title'],0,15),
                                                  'AUCTION_NEWEST_OFFER_TIME' => create_date($board_config['default_dateformat'], $auction_newest_offers_block['auction_offer_time_start'], $board_config['board_timezone']) ));

                          } // End while
                 } // End if
     } // End function

function includeMyAuctionHeader($location)
     {
          global $template, $lang, $userdata;
          
          $template->set_filenames(array('auction_my_auction_header' => 'auction_my_auction_header.tpl'));
          $template->assign_vars(array(
               'MY_RATINGS_COLOR' => ( $location == 'MY_RATING' ) ? "row3" : "row2",
               'MY_WATCHLIST_COLOR' => ( $location == 'MY_WATCHLIST' ) ? "row3" : "row2",
               'MY_ACCOUNT_COLOR' => ( $location == 'MY_ACCOUNT' ) ? "row3" : "row2",
               'MY_STORE_COLOR' => ( $location == 'MY_STORE' ) ? "row3" : "row2",
               'MY_AUCTIONS_COLOR' => ( $location == 'MY_AUCTION' ) ? "row3" : "row2",

               'L_MY_RATINGS' => $lang['auction_my_ratings'],
               'L_MY_AUCTIONS' => $lang['auction_myauction_auctions'],
               'L_MY_WATCHLIST' => $lang['auction_myauction_watchlist'],
               'L_MY_STORE' => $lang['auction_mystore'],
               'L_MY_ACCOUNT' => $lang['auction_my_account'],
               'L_MY_STORE' => $lang['auction_mystore'],

               'U_MY_ACCOUNT' => append_sid("auction_my_account.php?mode=view"),
               'U_MY_AUCTION' => append_sid("auction_my_auctions.php?mode=my_auctions&" . POST_USERS_URL . "=" . $userdata['user_id']),
               'U_MY_WATCHLIST' => append_sid("auction_my_auctions.php?mode=watchlist&" . POST_USERS_URL . "=" . $userdata['user_id']),
               'U_MY_STORE' => append_sid("auction_my_store.php"),
               'U_MY_RATING' => append_sid('auction_rating.php?mode=view&u=' . $userdata['user_id']) ));






          $template->pparse('auction_my_auction_header');
     }
?>