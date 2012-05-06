<?php
/***************************************************************************
 *                          admin_auction_offer.php
 *                            -------------------
 *   begin                : Jan 2004
 *   copyright            : (C) FR
 *   email                : fr@php-styles.com
 *   Last Update          : DEC 2004 - FR
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
     // set admin-navigation
     if( !empty($setmodules) )
          {
               $filename = basename(__FILE__);
               $module['Auction']['a8_account'] = append_sid($filename);
               return;
          }

     $phpbb_root_path = "./../";
     require($phpbb_root_path . 'extension.inc');
     require('./pagestart.' . $phpEx);
     include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);
     include($phpbb_root_path . 'auction/functions_general.php');
     include($phpbb_root_path . 'auction/auction_constants.php');

     // Start Include language file
     $language = $board_config['default_lang'];
     if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_auction.'.$phpEx) )
          {
               $language = 'english';
          } // if
     include($phpbb_root_path . 'language/lang_' . $language . '/lang_auction.' . $phpEx);
     // end include language file

     $auction_config_data = init_auction_config();

     // Mode setting
     if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
          {
               $mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
               $mode = htmlspecialchars($mode);
          }
     else
          {
               $mode = "";
          } // if

     $template->set_filenames(array('body' => 'admin/admin_auction_account.tpl'));


     if( !empty($mode) )
     {
         switch($mode)
         {
             case 'credit_user':
                 // Credit the stuff in users account
                 $sql = "SELECT pk_auction_account_id
                         FROM " . AUCTION_ACCOUNT_TABLE . "
                         WHERE fk_auction_account_creditor_id=" . $HTTP_POST_VARS['auction_credit_id'] . " AND
                               fk_auction_account_debitor_id =1 AND
                               auction_account_action='" . ACTION_CREDIT . "'";
                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not identify user-account state', '', __LINE__, __FILE__, $sql);
                     } // if
                 $board_credit_row = $db->sql_fetchrow($result);

                 if ($board_credit_row['pk_auction_account_id'] )
                 // account exists
                      {
                           $sql = "UPDATE " . AUCTION_ACCOUNT_TABLE . "
                                   SET auction_account_auction_amount = auction_account_auction_amount+" . $HTTP_POST_VARS['auction_credit_amount'] . ",
                                       auction_account_amount_date = " . time() . "
                                   WHERE pk_auction_account_id=" . $board_credit_row['pk_auction_account_id'];

                            if( !($result = $db->sql_query($sql)) )
                                 {
                                      message_die(GENERAL_ERROR, 'Could not update account', '', __LINE__, __FILE__, $sql);
                                 }
                      }
                 else
                 // account doesnot exist so far
                      {
                           // item-number is user_id
                           $sql = "INSERT INTO " . AUCTION_ACCOUNT_TABLE . "( fk_auction_account_creditor_id,
                                                                    fk_auction_account_debitor_id,
                                                                    auction_account_auction_amount,
                                                                    auction_account_amount_date,
                                                                    auction_account_action)
                                   VALUES (" . $HTTP_POST_VARS['auction_credit_id'] . ",
                                           1,
                                           " . $HTTP_POST_VARS['auction_credit_amount'] . ",
                                           " . time() . ",
                                           '" . ACTION_CREDIT . "')";

                            if( !($result = $db->sql_query($sql)) )
                                 {
                                      message_die(GENERAL_ERROR, 'Could not create new account', '', __LINE__, __FILE__, $sql);
                                 }
                            $template->assign_block_vars('message', array(
                                          'L_AUCTION_SUCCESS' => $lang['auction_credit_success']));
                      }
                  break;
                  
             case 'debit_user':

                 $sql = "INSERT INTO " . AUCTION_ACCOUNT_TABLE . "
                              (fk_auction_account_creditor_id,
                               fk_auction_account_debitor_id,
                               auction_account_auction_amount,
                               auction_account_amount_date,
                               auction_account_action)
                         VALUES (2,
                                 " . $HTTP_POST_VARS['auction_debit_id'] . ",
                                 " . $HTTP_POST_VARS['auction_debit_amount'] . ",
                                 " . time() . ",
                                 '" . ACTION_INITIAL . "')";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not debit amount', '', __LINE__, __FILE__, $sql);
                     } // if
                            $template->assign_block_vars('message', array(
                                          'L_AUCTION_SUCCESS' => $lang['auction_debit_success']));

               break;
          }
     }
                 // BEGIN PERCENT-FEES
                 $sql = "SELECT acc.*,
                                ao.auction_offer_title,
                                u.username
                         FROM " . AUCTION_ACCOUNT_TABLE . " acc
                         LEFT JOIN " . AUCTION_OFFER_TABLE . " ao on acc.fk_auction_offer_id=ao.pk_auction_offer_id
                         LEFT JOIN " . USERS_TABLE . " u on acc.fk_auction_account_debitor_id=u.user_id
                         WHERE fk_auction_account_creditor_id=2 AND
                               auction_account_action='" . ACTION_PERCENT . "' OR
                               auction_account_action='" . ACTION_INITIAL . "' ";

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
                                          'USER_NAME' => $action_percent_rowset[$i]['username'],
                                          'U_USER_NAME' => append_sid("../profile.php?mode=viewprofile&" . POST_USERS_URL . "=" . $action_percent_rowset[$i]['fk_auction_account_debitor_id']),
                                          'ACTION_OFFER_TITLE' => $action_percent_rowset[$i]['auction_offer_title'],
                                          'U_ACTION_OFFER_TITLE' => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL . "=" . $action_percent_rowset[$i]['fk_auction_offer_id']),
                                          'ACTION_AMOUNT' => $action_percent_rowset[$i]['auction_account_auction_amount'] . " " . $auction_config_data['currency'],
                                          'ACTION_AMOUNT_PAID' => ( $action_percent_rowset[$i]['auction_account_amount_paid'] ) ? $action_percent_rowset[$i]['auction_account_amount_paid'] . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],
                                          'ACTION_AMOUNT_UNPAID' => $action_percent_rowset[$i]['auction_account_auction_amount'] - $action_percent_rowset[$i]['auction_account_amount_paid']  . " " . $auction_config_data['currency']));
                                }

                           $total_percent_amount = $total_percent_amount + $action_percent_rowset[$i]['auction_account_auction_amount'];
                           $total_percent_amount_paid = $total_percent_amount_paid + $action_percent_rowset[$i]['auction_account_amount_paid'];
                      }
                  // END Percent-Fees

                 // START BOARD-CREDIT
                 $sql = "SELECT acc.*,
                                u.username
                         FROM " . AUCTION_ACCOUNT_TABLE . " acc
                         LEFT JOIN " . USERS_TABLE . " u on acc.fk_auction_account_creditor_id=u.user_id
                         WHERE fk_auction_account_debitor_id=1 AND
                               auction_account_action='" . ACTION_CREDIT . "' AND
                               auction_account_auction_amount>0";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not grab account board-credit information', '', __LINE__, __FILE__, $sql);
                     } // if

                 $total_credit_action = 0;
                 while( $row = $db->sql_fetchrow($result) )
                      {
                           $board_credit_rowset[] = $row;
                           $total_credit_action++;
                      }

                 for($i = 0; $i < $total_credit_action; $i++)
                      {
                           $template->assign_block_vars('board_credit', array(
                                          'BOARD_CREDIT_TIME' => create_date($board_config['default_dateformat'], $board_credit_rowset[$i]['auction_account_amount_date'], $board_config['board_timezone']),
                                          'BOARD_CREDIT_AMOUNT' => $board_credit_rowset[$i]['auction_account_auction_amount'] . " " . $auction_config_data['currency'],
                                          'USER_NAME' => $board_credit_rowset[$i]['username'],
                                          'U_USER_NAME' => append_sid("../profile.php?mode=view&" . POST_USERS_URL . "=" . $board_credit_rowset[$i]['auction_account_auction_amount']),
                                          'BOARD_CREDIT_AMOUNT_USED' => ( $board_credit_rowset[$i]['auction_account_amount_paid'] ) ? $board_credit_rowset[$i]['auction_account_amount_paid'] . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],
                                          'BOARD_CREDIT_AMOUNT_UNUSED' => $board_credit_rowset[$i]['auction_account_auction_amount'] - $board_credit_rowset[$i]['auction_account_amount_paid']  . " " . $auction_config_data['currency']));

                           $board_credit_amount = $board_credit_amount+ $board_credit_rowset[$i]['auction_account_auction_amount'];
                           $board_credit_amount_unused = $board_credit_amount_unused + $board_credit_rowset[$i]['auction_account_auction_amount']-$board_credit_rowset[$i]['auction_account_amount_paid'];
                     }
                   // END BOARD_CREDIT

                 $template->assign_vars(array(
                      'L_ADMIN_ACCOUNT' => $lang['admin_account'],
                      'L_ADMIN_ACCOUNT_EXPLAIN' => $lang['admin_account_explain'],
                      'L_AUCTION_FEES' => $lang['auction_fee'],
                      'L_AUCTION_CREDITS' => $lang['auction_credits'],
                      'L_AUCTION_OFFER_TITLE' => $lang['auction_offer_title'],
                      'L_AUCTION_USER_ID' => $lang['auction_user_id'],
                      'L_AUCTION_AMOUNT' => $lang['auction_amount'],
                      'L_AUCTION_DEBIT_USER' => $lang['auction_debit_user'],
                      'L_AUCTION_CREDIT_USER' => $lang['auction_credit_user'],
                      'S_AUCTION_DEBIT_USER' => append_sid("admin_auction_account.php?mode=debit_user"),
                      'S_AUCTION_CREDIT_USER' => append_sid("admin_auction_account.php?mode=credit_user"),
                      'L_AUCTION_OFFER_TIME_START' => $lang['auction_offer_time_start'],
                      'L_AUCTION_ACCOUNT_AMOUNT_TOTAL' => $lang['auction_account_amount_total'],
                      'L_AUCTION_ACCOUNT_AMOUNT_PAID' => $lang['auction_account_amount_paid'],
                      'L_AUCTION_ACCOUNT_AMOUNT_UNPAID' => $lang['auction_account_amount_unpaid'],
                      'AUCTION_FONT_COLOR2' => $theme['fontcolor2'],
                      'AUCTION_FONT_COLOR3' => $theme['fontcolor3'],
                      'L_AUCTION_ACCOUNT_TOTAL' => $lang['auction_account_amount_total_consolidation'],
                      'AUCTION_ACCOUNT_AMOUNT_PERCENT_TOTAL' => ($total_percent_amount>0) ? $total_percent_amount . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],
                      'AUCTION_ACCOUNT_AMOUNT_PERCENT_PAID' => ($total_percent_amount_paid>0) ? $total_percent_amount_paid . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],
                      'AUCTION_ACCOUNT_AMOUNT_PERCENT_UNPAID' => ($total_percent_amount-$total_percent_amount_paid>0) ? $total_percent_amount-$total_percent_amount_paid . " " . $auction_config_data['currency'] : "0 " . $auction_config_data['currency'],



                      'L_AUCTION_ACCOUNT_INITIAL_FEE' => $lang['auction_account_initial_fee'],
                      'L_AUCTION_BOARD_CREDIT_AMOUNT' => $lang['auction_board_credit_amount'],
                      'L_AUCTION_BOARD_CREDIT_AMOUNT_USED' => $lang['auction_board_credit_amount_used'],
                      'L_AUCTION_BOARD_CREDIT_AMOUNT_UNUSED' => $lang['auction_board_credit_amount_unused'],
                      'L_AUCTION_BOARD_CREDIT_AMOUNT_TOTAL' => $lang['auction_board_credit_amount_total'],
                      'L_AUCTION_BOARD_CREDIT_TIME' => $lang['auction_board_credit_amount_time'],
                      'L_AUCTION_ROOM_SHORT' => $lang['auction_room_short'],
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
                      'AUCTION_BOARD_CREDIT_UNUSED' => $board_credit_amount_unused . " " . $auction_config_data['currency'],
                      'AUCTION_BOARD_CREDIT' => $board_credit_amount . " " . $auction_config_data['currency'],
                      'L_AUCTION_ACCOUNT_CREDIT' => $lang['auction_account_balance_credit'],
                      'L_AUCTION_ACCOUNT_DEBIT' => $lang['auction_account_balance_debit']));

      $template->pparse("body");
      include('./page_footer_admin.'.$phpEx);

?>