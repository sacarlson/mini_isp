<?php
/***************************************************************************
 *                             auction_rating.php
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

     define('IN_PHPBB', 1);
     //define('SHOW_ONLINE', true);

     $phpbb_root_path = './';
     include_once($phpbb_root_path . 'auction/auction_common.php');

     // Start session management
     $userdata = session_pagestart($user_ip, AUCTION_RATING);
     init_userprefs($userdata);
     // End session management

     if ( $auction_config_data['auction_disable'] == 1 )
          {
                 message_die(GENERAL_MESSAGE, $lang['auction_disable']);
          }

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
     // includeTickerBlock();
     includeAuctionRoomBlock();
     includeCloseToEndBlock();
     includeStatisticBlock();
     includeTickerBlock();
     includeMyAuctionsBlock($userdata);
     includeCalendarBlock();
     includeSearchBlock();
     includeTermsBlock();
     includeNewsBlock();
     includeAuctionSpecialBlock();
     includeAuctionDropDownRoomBlock();
     includeLastBidsBlock();
     includeNewestOffersBlock();

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
             case 'create':


                if( isset($HTTP_GET_VARS[POST_AUCTION_OFFER_URL]))
                     {
                          $auction_offer_url = $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];
                          $auction_offer_url = htmlspecialchars($auction_offer_url);
                     }
                     
                // BEGIN find out if user is seller or buyer
                $sql = "SELECT FK_auction_offer_user_id,
                               FK_auction_offer_last_bid_user_id
                        FROM " . AUCTION_OFFER_TABLE . "
                        WHERE PK_auction_offer_id=" . $auction_offer_url . "";
                        
                if( !($result = $db->sql_query($sql)) )
                        {
                                  message_die(GENERAL_ERROR, 'Could not query seller-info', '', __LINE__, __FILE__, $sql);
                        }

                $auction_offer_row = $db->sql_fetchrow($result);
                $seller_id = $auction_offer_row['FK_auction_offer_user_id'];

                // Actual identification
                if ($seller_id<>$userdata['user_id'])
                     {
                          $rating_user = "buyer";
                     }
                else
                     {
                          $rating_user = "seller";
                     }
                // END find out if user is seller or buyer

                $buyer_id = $auction_offer_row['FK_auction_offer_last_bid_user_id'];
                
                // BEGIN check if he is first or second to rate for this offer
                $sql = "SELECT FK_auction_offer_id
                        FROM " . AUCTION_USER_RATING_TABLE . "
                        WHERE (FK_auction_offer_id=" . $auction_offer_url . ")";

                if( !($result = $db->sql_query($sql)) )
                        {
                                  message_die(GENERAL_ERROR, 'Could not query if rating does exist', '', __LINE__, __FILE__, $sql);
                        }
                $auction_rating_row = $db->sql_fetchrow($result);

                if ($auction_rating_row['FK_auction_offer_id']<>"")
                     {
                            $rating_order = "second";
                     }
                else
                     {
                          $rating_order = "first";
                     }
                // END check if he is first of second to rate for this offer
                
                if (($rating_user=="buyer") && ($rating_order=="first"))
                  {
                       $sql = "INSERT INTO " . AUCTION_USER_RATING_TABLE . "
                                          (FK_auction_offer_id,
                                           FK_auction_offer_seller_id,
                                           FK_auction_offer_buyer_id,
                                           auction_offer_seller_rating_text,
                                           FK_auction_offer_seller_rating_id,
                                           auction_user_seller_rating_time)
                               VALUES(". $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]. ",
                                      " . $seller_id . " ,
                                      " . $userdata['user_id']. ",
                                      '" . $HTTP_POST_VARS['auction_rate_text']. "',
                                      '" . $HTTP_POST_VARS['rating_category'] . "',
                                      " . time() . ")";
                                      
                       if( !($result = $db->sql_query($sql)) )
                           {
                                  message_die(GENERAL_ERROR, 'Could not insert rating', '', __LINE__, __FILE__, $sql);
                           }
                       message_die(GENERAL_MESSAGE, $lang['auction_rating_successful']);
                  }

                if (($rating_user=="buyer") && ($rating_order=="second"))
                  {
                       $sql = "UPDATE " . AUCTION_USER_RATING_TABLE . "
                               Set auction_offer_seller_rating_text='" . $HTTP_POST_VARS['auction_rate_text']. "',
                                   FK_auction_offer_seller_rating_id='" . $HTTP_POST_VARS['rating_category'] . "',
                                   auction_user_seller_rating_time=" . time() . ",
                                   FK_auction_offer_seller_id = " . $seller_id . "
                               WHERE (FK_auction_offer_id=" . $auction_offer_url . ")";
                               
                       if( !($result = $db->sql_query($sql)) )
                           {
                                  message_die(GENERAL_ERROR, 'Could not insert rating', '', __LINE__, __FILE__, $sql);
                           }

                       message_die(GENERAL_MESSAGE, $lang['auction_rating_successful']);
                  }
                  
                if (($rating_user=="seller") && ($rating_order=="first"))
                  {
                       $sql = "INSERT INTO " . AUCTION_USER_RATING_TABLE . "
                                     (FK_auction_offer_id,
                                      FK_auction_offer_buyer_id,
                                      FK_auction_offer_seller_id,
                                      auction_offer_buyer_rating_text,
                                      FK_auction_offer_buyer_rating_id,
                                      auction_user_buyer_rating_time)
                               VALUES(". $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]. ",
                                      " . $buyer_id . ",
                                      " . $userdata['user_id'] . ",
                                      '" . $HTTP_POST_VARS['auction_rate_text']. "',
                                      '" . $HTTP_POST_VARS['rating_category'] . "',
                                      " . time() . ")";
                                      
                       if( !($result = $db->sql_query($sql)) )
                           {
                                  message_die(GENERAL_ERROR, 'Could not insert rating', '', __LINE__, __FILE__, $sql);
                           }

                       message_die(GENERAL_MESSAGE, $lang['auction_rating_successful']);
                  }
                if (($rating_user=="seller") && ($rating_order=="second"))
                  {
                       $sql = "UPDATE " . AUCTION_USER_RATING_TABLE . "
                               Set auction_offer_buyer_rating_text='" . $HTTP_POST_VARS['auction_rate_text']. "',
                                   FK_auction_offer_buyer_rating_id='" . $HTTP_POST_VARS['rating_category'] . "',
                                   auction_user_buyer_rating_time=" . time() . ",
                                   FK_auction_offer_buyer_id = " . $buyer_id . "
                               WHERE (FK_auction_offer_id=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]. ")";

                       if( !($result = $db->sql_query($sql)) )
                           {
                                  message_die(GENERAL_ERROR, 'Could not insert rating', '', __LINE__, __FILE__, $sql);
                           }

                       message_die(GENERAL_MESSAGE, $lang['auction_rating_successful']);
                  }

                break; // create

             case 'view':

                    $user_id = $HTTP_GET_VARS[POST_USERS_URL];
                    $user_id = htmlspecialchars($user_id);

                    // Get username
                    $sql = "SELECT username
                            FROM " . USERS_TABLE . "
                            WHERE user_id = " . $user_id;

                    if( !($result = $db->sql_query($sql)) )
                          {
                                  message_die(GENERAL_ERROR, 'Could not query username', '', __LINE__, __FILE__, $sql);
                          } // if

                    $row = $db->sql_fetchrow($result);
                    $username = $row['username'];

                     $template->set_filenames(array('body' => 'auction_user_rating.tpl'));

                     $template->assign_vars(array(
                         'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
                         'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
                         'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
                         'L_ONLINE_EXPLAIN' => $lang['Online_explain'],
                         'USER_NAME' => $username,
                         'L_AUCTION_USER_RATING' => $lang['auction_user_rating'],
                         'L_AUCTION_USER_RATING_TITLE' => $lang['auction_user_rating_title'],
                         'L_AUCTION_USER_RATING_DESCRIPTION' => $lang['auction_user_rating_description'],
                         'L_AUCTION_USER_RATING_FROM' => $lang['auction_user_rating_from'],
                         'L_AUCTION_USER_RATING_FOR' => $lang['auction_user_rating_for'],
                         'L_AUCTION_USER_RATING_DATE' => $lang['auction_user_rating_date'],
                         'L_AUCTION_USER_SELLER_RATING' => $lang['auction_user_seller_rating'],
                         'L_AUCTION_USER_BUYER_RATING' => $lang['auction_user_buyer_rating'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_TIME_START' => $lang['auction_offer_time_start'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_TIME_STOP' => $lang['auction_offer_time_stop'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_TITLE' => $lang['auction_offer_title'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_OFFERER' => $lang['auction_offer_offerer'],
                         'MODAUTHOR' => $lang['modauthor'],
                         'MODPOWERED' => $lang['modpowered']));

                    // Rating as buyer .......
                    $sql = "SELECT ur.FK_auction_offer_id,
                                   ur.auction_offer_buyer_rating_text,
                                   u.username,
                                   r.auction_rating_title,
                                   r.auction_rating_icon,
                                   ur.auction_user_buyer_rating_time,
                                   o.PK_auction_offer_id
                            FROM (" . AUCTION_USER_RATING_TABLE . " ur
                            LEFT JOIN " . USERS_TABLE . " u ON u.user_id = ur.FK_auction_offer_seller_id
                            LEFT JOIN " . AUCTION_RATING_TABLE . " r ON ur.FK_auction_offer_buyer_rating_id  = r.PK_auction_rating_id
                            LEFT JOIN " . AUCTION_OFFER_TABLE . " o ON ur.FK_auction_offer_id=o.PK_auction_offer_id )
                            WHERE ur.FK_auction_offer_buyer_id = " . $user_id . " AND
                                  ur.auction_offer_buyer_rating_text<>''";

                    if( !($result = $db->sql_query($sql)) )
                          {
                                  //message_die(GENERAL_ERROR, 'Could not query user-rating', '', __LINE__, __FILE__, $sql);
                          } // if
                        
                    while ($auction_user_buyer_rating_row = $db->sql_fetchrow($result))
                          {
                                 $auction_user_buyer_ratings[] = $auction_user_buyer_rating_row;
                          } // while
                           
                    if ( count($auction_user_buyer_ratings) == 0 )
                          {
                               //no ratings as buyer
                          } // if
                          
                    for ($i = 0; $i < count($auction_user_buyer_ratings); $i++)
                           {
                               if ( $auction_user_buyer_ratings[$i]['auction_rating_icon'] == "1.gif" )
                                    {
                                       $rating_image = $images['icon_rating1'];
                                    }
                               if ( $auction_user_buyer_ratings[$i]['auction_rating_icon'] == "2.gif" )
                                    {
                                       $rating_image = $images['icon_rating2'];
                                    }
                               if ( $auction_user_buyer_ratings[$i]['auction_rating_icon'] == "3.gif" )
                                    {
                                       $rating_image = $images['icon_rating3'];
                                    }
                               if ( $auction_user_buyer_ratings[$i]['auction_rating_icon'] == "4.gif" )
                                    {
                                       $rating_image = $images['icon_rating4'];
                                    }
                               $template->assign_block_vars('buyerratingrow', array(
                                    'AUCTION_USER_BUYER_RATING_ICON' => $rating_image,
                                    'AUCTION_USER_BUYER_RATING_TEXT' => $auction_user_buyer_ratings[$i]['auction_offer_buyer_rating_text'],
                                    'AUCTION_USER_BUYER_RATING_TITLE' => $auction_user_buyer_ratings[$i]['auction_rating_title'],
                                    'AUCTION_USER_BUYER_RATING_FROM' => $auction_user_buyer_ratings[$i]['username'],
                                    'AUCTION_USER_BUYER_RATING_TIME' => create_date($board_config['default_dateformat'], $auction_user_buyer_ratings[$i]['auction_user_buyer_rating_time'], $board_config['board_timezone']),
                                    'AUCTION_OFFER_DELETED' => ( $auction_user_buyer_ratings[$i]['PK_auction_offer_id'] ) ? "" : $lang['auction_offer_deleted'],
                                    'AUCTION_USER_BUYER_RATING_FOR' => ( $auction_user_buyer_ratings[$i]['PK_auction_offer_id'] ) ? $lang['auction_user_rating_view_offer'] : "",
                                    'U_AUCTION_USER_BUYER_RATING_FOR' => ( $auction_user_buyer_ratings[$i]['PK_auction_offer_id'] ) ? append_sid("auction_offer_view.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $auction_user_buyer_ratings[$i]['FK_auction_offer_id'] ) : ""));
                           }

                     // Rating as seller ........
                     $sql = "SELECT ur.FK_auction_offer_id,
                                    ur.auction_offer_seller_rating_text,
                                    u.username,
                                    r.auction_rating_title,
                                    r.auction_rating_icon,
                                    ur.auction_user_seller_rating_time,
                                    o.PK_auction_offer_id
                             FROM (" . AUCTION_USER_RATING_TABLE . " ur
                             LEFT JOIN " . USERS_TABLE . " u ON u.user_id = ur.FK_auction_offer_buyer_id
                             LEFT JOIN " . AUCTION_RATING_TABLE . " r ON ur.FK_auction_offer_seller_rating_id  = r.PK_auction_rating_id
                             LEFT JOIN " . AUCTION_OFFER_TABLE . " o ON ur.FK_auction_offer_id=o.PK_auction_offer_id)
                             WHERE ur.FK_auction_offer_seller_id = " . $user_id . " AND
                                   ur.auction_offer_seller_rating_text<>''";

                    if( !($result = $db->sql_query($sql)) )
                          {
                                  message_die(GENERAL_ERROR, 'Could not query user-rating', '', __LINE__, __FILE__, $sql);
                          } // if
                        
                    while ($auction_user_seller_rating_row = $db->sql_fetchrow($result))
                          {
                                 $auction_user_seller_ratings[] = $auction_user_seller_rating_row;
                          } // if
                          
                    if ( count($auction_user_seller_ratings) == 0 )
                          {
                               // no ratings as seller information
                          }
                          
                    for ($i = 0; $i < count($auction_user_seller_ratings); $i++)
                           {
                               if ( $auction_user_seller_ratings[$i]['auction_rating_icon'] == "1.gif" )
                                    {
                                       $rating_image = $images['icon_rating1'];
                                    }
                               if ( $auction_user_seller_ratings[$i]['auction_rating_icon'] == "2.gif" )
                                    {
                                       $rating_image = $images['icon_rating2'];
                                    }
                               if ( $auction_user_seller_ratings[$i]['auction_rating_icon'] == "3.gif" )
                                    {
                                       $rating_image = $images['icon_rating3'];
                                    }
                               if ( $auction_user_seller_ratings[$i]['auction_rating_icon'] == "4.gif" )
                                    {
                                       $rating_image = $images['icon_rating4'];
                                    }
                               $template->assign_block_vars('sellerratingrow', array(
                                    'AUCTION_USER_SELLER_RATING_ICON' => $rating_image,
                                    'AUCTION_USER_SELLER_RATING_TEXT' => $auction_user_seller_ratings[$i]['auction_offer_seller_rating_text'],
                                    'AUCTION_USER_SELLER_RATING_TITLE' => $auction_user_seller_ratings[$i]['auction_rating_title'],
                                    'AUCTION_USER_SELLER_RATING_FROM' => $auction_user_seller_ratings[$i]['username'],
                                    'AUCTION_USER_SELLER_RATING_TIME' => create_date($board_config['default_dateformat'], $auction_user_seller_ratings[$i]['auction_user_seller_rating_time'], $board_config['board_timezone']),
                                    'AUCTION_OFFER_DELETED' => ( $auction_user_seller_ratings[$i]['PK_auction_offer_id'] ) ? "" : $lang['auction_offer_deleted'],
                                    'AUCTION_USER_SELLER_RATING_FOR' => ( $auction_user_seller_ratings[$i]['PK_auction_offer_id'] ) ? $lang['auction_user_rating_view_offer'] : "",
                                    'U_AUCTION_USER_SELLER_RATING_FOR' => ( $auction_user_seller_ratings[$i]['PK_auction_offer_id'] ) ? append_sid("auction_offer_view.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $auction_user_seller_ratings[$i]['FK_auction_offer_id'] ) :  ""));
                           }

                     $page_title = $lang['auction_my_ratings'];
                     include('./includes/page_header.php');
                     include($phpbb_root_path . 'auction/auction_header.'.$phpEx);
                     if ( $userdata['user_id'] == $user_id )
                          {
                               includeMyAuctionHeader('MY_RATING');
                          }

                     $template->pparse('body');
                     include($phpbb_root_path . 'auction/auction_footer.'.$phpEx);
                     include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

                  break; // view

             default:
                 message_die(GENERAL_MESSAGE, $lang['No_mode']);
                 break;
         }
}

?>