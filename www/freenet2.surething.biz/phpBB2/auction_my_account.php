<?php
/***************************************************************************
 *                          auction_my_account.php
 *                            -------------------
 *   begin                : Jan 2005
 *   copyright            : (C)  FR
 *   email                : fr@php-styles.com
 *   Last update          : Jan 2005 - FR
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
     $userdata = session_pagestart($user_ip, AUCTION_MY_USER_STORE);
     init_userprefs($userdata);
     // End session management

     if ( !$userdata['session_logged_in'] )
          {
               redirect("login.".$phpEx."?redirect=auction_my_account.".$phpEx);
               exit;
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
     includeLastBidsBlock();
     includeNewestOffersBlock();
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

                 $page_title = $lang['auction_my_account'];
                 include('./includes/page_header.php');
                 include($phpbb_root_path . 'auction/auction_header.'.$phpEx);

                 includeMyAuctionHeader('MY_ACCOUNT');

                 $template->set_filenames(array('body' => 'auction_my_account.tpl'));

     if( !empty($mode) ) 
     {
         switch($mode)
         {
             case 'transaction':

             case 'view':

                 $sql = "SELECT acc.*,
                                ao.auction_offer_title
                         FROM " . AUCTION_ACCOUNT_TABLE . " acc
                         LEFT JOIN " . AUCTION_OFFER_TABLE . " ao on acc.fk_auction_offer_id=ao.pk_auction_offer_id
                         WHERE fk_auction_account_creditor_id=2 AND
                               fk_auction_account_debitor_id =" . $userdata['user_id' ] . " AND
                               auction_account_action='" . ACTION_INITIAL . "'";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not grab account information', '', __LINE__, __FILE__, $sql);
                     } // if

                 $total_action = 0;
                 while( $row = $db->sql_fetchrow($result) )
                      {
                           $action_rowset[] = $row;
                           $total_action++;
                      }

                 for($i = 0; $i < $total_action; $i++)
                      {
                           if ( $action_rowset[$i]['auction_account_auction_amount'] > $action_rowset[$i]['auction_account_auction_amount_paid'] )
                                {
                                     $template->assign_block_vars('action_init', array(
                                          'ACTION_TIME' => create_date($board_config['default_dateformat'], $action_rowset[$i]['auction_account_amount_date'], $board_config['board_timezone']),
                                          'ACTION_OFFER_TITLE' => $action_rowset[$i]['auction_offer_title'],
                                          'U_ACTION_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $action_rowset[$i]['fk_auction_offer_id']),
                                          'ACTION_AMOUNT' => $action_rowset[$i]['auction_account_auction_amount'] . " " . $auction_config_data['currency'],
                                          'ACTION_AMOUNT_PAID' => ( $action_rowset[$i]['auction_account_amount_paid'] ) ? $action_rowset[$i]['auction_account_amount_paid'] . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],
                                          'ACTION_AMOUNT_UNPAID' => $action_rowset[$i]['auction_account_auction_amount'] - $action_rowset[$i]['auction_account_amount_paid']  . " " . $auction_config_data['currency']
                                          ));
                                }

                           $total_amount = $total_amount + $action_rowset[$i]['auction_account_auction_amount'];
                           $total_amount_paid = $total_amount_paid + $action_rowset[$i]['auction_account_amount_paid'];
                      }

                 // BEGIN PERCENT-FEES
                 $sql = "SELECT acc.*,
                                ao.auction_offer_title
                         FROM " . AUCTION_ACCOUNT_TABLE . " acc
                         LEFT JOIN " . AUCTION_OFFER_TABLE . " ao on acc.fk_auction_offer_id=ao.pk_auction_offer_id
                         WHERE fk_auction_account_creditor_id=2 AND
                               fk_auction_account_debitor_id =" . $userdata['user_id'] . " AND
                               auction_account_action='" . ACTION_PERCENT . "'";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not grab account information', '', __LINE__, __FILE__, $sql);
                     } // if

                 $total_action = 0;
                 while( $row = $db->sql_fetchrow($result) )
                      {
                           $action_percent_rowset[] = $row;
                           $total_percent_action++;
                      }

                 for($i = 0; $i < $total_percent_action; $i++)
                      {
                           if ( $action_percent_rowset[$i]['auction_account_auction_amount'] > $action_percent_rowset[$i]['auction_account_auction_amount_paid'] )
                                {
                                     $template->assign_block_vars('action_percent', array(
                                          'ACTION_TIME' => create_date($board_config['default_dateformat'], $action_percent_rowset[$i]['auction_account_amount_date'], $board_config['board_timezone']),
                                          'ACTION_OFFER_TITLE' => $action_percent_rowset[$i]['auction_offer_title'],
                                          'U_ACTION_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $action_percent_rowset[$i]['fk_auction_offer_id']),
                                          'ACTION_AMOUNT' => $action_percent_rowset[$i]['auction_account_auction_amount'] . " " . $auction_config_data['currency'],
                                          'ACTION_AMOUNT_PAID' => ( $action_percent_rowset[$i]['auction_account_amount_paid'] ) ? $action_percent_rowset[$i]['auction_account_amount_paid'] . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],
                                          'ACTION_AMOUNT_UNPAID' => $action_percent_rowset[$i]['auction_account_auction_amount'] - $action_percent_rowset[$i]['auction_account_amount_paid']  . " " . $auction_config_data['currency']
                                          ));
                                }

                           $total_percent_amount =      $total_percent_amount + $action_percent_rowset[$i]['auction_account_auction_amount'];
                           $total_percent_amount_paid = $total_percent_amount_paid + $action_percent_rowset[$i]['auction_account_amount_paid'];
                      }
                  // END Percent-Fees

                 // Selection auction-offer credit
                 $sql = "SELECT o.auction_offer_title,
                                o.pk_auction_offer_id,
                                u.username,
                                o.FK_auction_offer_last_bid_user_id,
                                o.auction_offer_last_bid_price,
                                ar.auction_room_title
                         FROM ((" . AUCTION_OFFER_TABLE . " o
                         LEFT JOIN " . USERS_TABLE . " u ON o.FK_auction_offer_last_bid_user_id=u.user_id)
                         LEFT JOIN " . AUCTION_ROOM_TABLE . " ar ON o.FK_auction_offer_room_id=ar.PK_auction_room_id )
                         WHERE o.FK_auction_offer_user_id=" . $userdata['user_id' ] . " AND
                               ( o.auction_offer_time_start<" . time() . " AND
                               o.auction_offer_last_bid_price>o.auction_offer_price_start ) OR
                               auction_offer_state=2";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not grab account information', '', __LINE__, __FILE__, $sql);
                     } // if

                 $total_auction_credit_action = 0;
                 while( $row = $db->sql_fetchrow($result) )
                      {
                           $action_auction_credit_rowset[] = $row;
                           $total_auction_credit_action++;
                      }

                 for($i = 0; $i < $total_auction_credit_action; $i++)
                      {
                                     $template->assign_block_vars('action_selling_credit', array(
                                          'ACTION_OFFER_TITLE' => $action_auction_credit_rowset[$i]['auction_offer_title'],
                                          'U_ACTION_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $action_auction_credit_rowset[$i]['pk_auction_offer_id']),
                                          'ACTION_USER' => $action_auction_credit_rowset[$i]['username'],
                                          'U_ACTION_USER' => append_sid("profile.php?mode=profile&mode=viewprofile&" . POST_USERS_URL . "=" . $action_auction_credit_rowset[$i]['FK_auction_offer_last_bid_user_id']),
                                          'ACTION_ROOM_TITLE' => $action_auction_credit_rowset[$i]['auction_room_title'],
                                          'U_ACTION_ROOM_TITLE' => append_sid("auction_room.php?" . POST_AUCTION_ROOM_URL . "=" . $action_auction_credit_rowset[$i]['FK_auction_offer_room_id']),
                                          'ACTION_AMOUNT_UNPAID' => $action_auction_credit_rowset[$i]['auction_offer_last_bid_price']  . " " . $auction_config_data['currency']));
                           $total_auction_credit_amount = $total_auction_credit_amount + $action_auction_credit_rowset[$i]['auction_offer_last_bid_price'];
                      }

                 // START BOARD-CREDIT
                 $sql = "SELECT *
                         FROM " . AUCTION_ACCOUNT_TABLE . " acc
                         WHERE fk_auction_account_creditor_id=" . $userdata['user_id' ] . " AND
                               fk_auction_account_debitor_id =1 AND
                               auction_account_action='" . ACTION_CREDIT . "'";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not grab account board-credit information', '', __LINE__, __FILE__, $sql);
                     } // if

                 $board_credit_rowset = $db->sql_fetchrow($result);

                 $template->assign_block_vars('board_credit', array(
                                          'BOARD_CREDIT_TIME' => create_date($board_config['default_dateformat'], $board_credit_rowset['auction_account_amount_date'], $board_config['board_timezone']),
                                          'BOARD_CREDIT_AMOUNT' => $board_credit_rowset['auction_account_auction_amount'] . " " . $auction_config_data['currency'],
                                          'BOARD_CREDIT_AMOUNT_USED' => ( $board_credit_rowset['auction_account_amount_paid'] ) ? $board_credit_rowset['auction_account_amount_paid'] . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],
                                          'BOARD_CREDIT_AMOUNT_UNUSED' => $board_credit_rowset['auction_account_auction_amount'] - $board_credit_rowset['auction_account_amount_paid']  . " " . $auction_config_data['currency']
                                          ));

                           $board_credit_amount = $board_credit_rowset['auction_account_auction_amount'];
                           $board_credit_amount_unused = $board_credit_rowset['auction_account_auction_amount']-$board_credit_rowset['auction_account_amount_paid'];
                   // END BOARD_CREDIT

                 // Selection auction-offer credit
                 $sql = "SELECT o.auction_offer_title,
                                o.pk_auction_offer_id,
                                u.username,
                                o.FK_auction_offer_user_id,
                                o.auction_offer_last_bid_price,
                                ar.auction_room_title
                         FROM ((" . AUCTION_OFFER_TABLE . " o
                         LEFT JOIN " . USERS_TABLE . " u ON o.FK_auction_offer_user_id=u.user_id)
                         LEFT JOIN " . AUCTION_ROOM_TABLE . " ar ON o.FK_auction_offer_room_id=ar.PK_auction_room_id )
                         WHERE o.FK_auction_offer_last_bid_user_id=" . $userdata['user_id' ] . " AND
                               ( o.auction_offer_time_start<" . time() . " AND
                               o.auction_offer_last_bid_price>o.auction_offer_price_start ) OR
                               auction_offer_state=2";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not grab account information', '', __LINE__, __FILE__, $sql);
                     } // if

                 $total_auction_debit_action = 0;
                 while( $row = $db->sql_fetchrow($result) )
                      {
                           $action_auction_debit_rowset[] = $row;
                           $total_auction_debit_action++;
                      }

                 for($i = 0; $i < $total_auction_debit_action; $i++)
                      {
                                     $template->assign_block_vars('action_selling_debit', array(
                                          'ACTION_OFFER_TITLE' => $action_auction_debit_rowset[$i]['auction_offer_title'],
                                          'U_ACTION_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $action_auction_debit_rowset[$i]['pk_auction_offer_id']),
                                          'ACTION_USER' => $action_auction_credit_rowset[$i]['username'],
                                          'U_ACTION_USER' => append_sid("profile.php?mode=profile&mode=viewprofile&" . POST_USERS_URL . "=" . $action_auction_debit_rowset[$i]['FK_auction_offer_last_bid_user_id']),
                                          'ACTION_ROOM_TITLE' => $action_auction_debit_rowset[$i]['auction_room_title'],
                                          'U_ACTION_ROOM_TITLE' => append_sid("auction_room.php?" . POST_AUCTION_ROOM_URL . "=" . $action_auction_debit_rowset[$i]['FK_auction_offer_room_id']),
                                          'ACTION_AMOUNT_UNPAID' => $action_auction_debit_rowset[$i]['auction_offer_last_bid_price']  . " " . $auction_config_data['currency']));
                           $total_auction_debit_amount = $total_auction_debit_amount + $action_auction_debit_rowset[$i]['auction_offer_last_bid_price'];
                      }
                 // Fill page
                 $template->assign_vars(array(
                      'L_AUCTION_ACCOUNT_INITIAL_FEE' => $lang['auction_account_initial_fee'],
                      'L_AUCTION_ACCOUNT_AMOUNT_TOTAL' => $lang['auction_account_amount_total'],
                      'L_AUCTION_ACCOUNT_AMOUNT_PAID' => $lang['auction_account_amount_paid'],
                      'L_AUCTION_ACCOUNT_AMOUNT_UNPAID' => $lang['auction_account_amount_unpaid'],
                      'L_AUCTION_BOARD_CREDIT_AMOUNT' => $lang['auction_board_credit_amount'],
                      'L_AUCTION_BOARD_CREDIT_AMOUNT_USED' => $lang['auction_board_credit_amount_used'],
                      'L_AUCTION_BOARD_CREDIT_AMOUNT_UNUSED' => $lang['auction_board_credit_amount_unused'],
                      'L_AUCTION_BOARD_CREDIT_AMOUNT_TOTAL' => $lang['auction_board_credit_amount_total'],
                      'L_AUCTION_BOARD_CREDIT_TIME' => $lang['auction_board_credit_amount_time'],
                      'L_AUCTION_ACCOUNT_TOTAL' => $lang['auction_account_amount_total_consolidation'],
                      'L_AUCTION_OFFER_TITLE' => $lang['auction_offer_title'],
                      'L_AUCTION_ROOM_SHORT' => $lang['auction_room_short'],
                      'L_AUCTION_OFFER_TIME_START' => $lang['auction_offer_time_start'],
                      'L_AUCTION_OFFER_BUYER' => $lang['auction_offer_buyer'],
                      'L_AUCTION_OFFER' => $lang['auction_offer'],
                      'L_AUCTION_ACCOUNT_TOTAL_DEBIT' => $lang['auction_account_total_debit'],
                      'L_AUCTION_ACCOUNT_TOTAL_CREDIT' => $lang['auction_account_total_credit'],
                      'L_AUCTION_ACCOUNT_AUCTION_BALANCE' => $lang['auction_account_auction_balance'],
                      'AUCTION_ACCOUNT_AMOUNT_TOTAL_DEBIT' => ( $total_auction_debit_amount > 0 ) ?  round($total_auction_debit_amount,2) . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],
                      'AUCTION_ACCOUNT_AMOUNT_TOTAL_CREDIT' => ( $total_auction_credit_amount > 0 ) ? round($total_auction_credit_amount,2) . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],
                      'AUCTION_ACCOUNT_AMOUNT_TOTAL' => ($total_amount>0) ? $total_amount . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],
                      'AUCTION_ACCOUNT_AMOUNT_PAID' => ($total_amount_paid>0) ? $total_amount_paid . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],
                      'AUCTION_ACCOUNT_AMOUNT_UNPAID' => ($total_amount-$total_amount_paid>0) ? $total_amount-$total_amount_paid . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],
                      'AUCTION_ACCOUNT_AMOUNT_PERCENT_TOTAL' => ($total_percent_amount>0) ? $total_percent_amount . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],
                      'AUCTION_ACCOUNT_AMOUNT_PERCENT_PAID' => ($total_percent_amount_paid>0) ? $total_percent_amount_paid . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],
                      'AUCTION_ACCOUNT_AMOUNT_PERCENT_UNPAID' => ($total_percent_amount-$total_amount_percent_paid>0) ? $total_percent_amount-$total_amount_percent_paid . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],
                      'AUCTION_BOARD_CREDIT_UNUSED' => $board_credit_amount_unused . " " . $auction_config_data['currency'],
                      'AUCTION_BOARD_CREDIT' => $board_credit_amount . " " . $auction_config_data['currency'],
                      'L_AUCTION_ACCOUNT_CREDIT' => $lang['auction_account_balance_credit'],
                      'L_AUCTION_ACCOUNT_DEBIT' => $lang['auction_account_balance_debit'],
                      'AUCTION_FONT_COLOR2' => $theme['fontcolor2'],
                      'AUCTION_FONT_COLOR3' => $theme['fontcolor3'],
                      'L_AUCTION_ACCOUNT_FINAL_PERCENT_FEE' => $lang['auction_account_final_percent_fee'] ));

                if ( $auction_config_data['auction_paymentsystem_activate_paypal'] == 1 AND $auction_config_data['auction_paymentsystem_activate_user_points'] == 0 )
                        {
                             $template->assign_block_vars('action_credit_paypal', array(
                                  'L_AUCTION_CREDIT'=> $lang['auction_credit_paypal'],
                                  'L_AUCTION_CREDIT_ALL'=> $lang['auction_credit_paypal_all'],
                                  'AUCTION_ACCOUNT_TOTAL_DEBIT' => ($total_amount-$total_amount_paid+$total_percent_amount-$total_amount_percent_paid),
                                  'AUCTION_CREDITOR_USER_ID'=> $userdata['user_id'],
                                  'PAYPAL_IMAGE' => PAYPAL_IMAGE,
                                  'AUCTION_PAYPAL_ADRESS' => $auction_config_data['auction_paymentsystem_paypal_email'],
                                  'AUCTION_PAYMENT_NOTIFICATION' => "http://" . $_SERVER['HTTP_HOST'] . $board_config['script_path'] . "auction_credit_ipn.php",
                                  'AUCTION_PAYMENT_RETURN' => "http://" . $_SERVER['HTTP_HOST'] . $board_config['script_path'] . "auction.php",
                                  'AUCTION_PAYMENT_CURRENCY' =>$auction_config_data['currency']));
                        }

                if ( $board_credit_amount_unused>0 )
                        {
                             $template->assign_block_vars('settle_fees', array(
                                  'L_AUCTION_SETTLE_FEES'=> $lang['auction_settle_fees'],
                                  'U_AUCTION_SETTLE_FEES' => append_sid("auction_offer.php?mode=settle_fees")));
                        }

                 $template->pparse('body');
                 include($phpbb_root_path . 'auction/auction_footer.'.$phpEx);
                 include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
          }
     }

?>