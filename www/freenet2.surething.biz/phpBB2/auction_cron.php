<?php
/***************************************************************************
 *                              auction_cron.php
 *                            -------------------
 *   begin                :   July 2004
 *   copyright            :   (C) FR
 *   email                :   fr@php-styles.com
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

function notifyUser($user_id, $notify_type, $offer_id, $offer_title)
     {
          global $db, $lang, $auction_config_data, $board_config;

      if ( $auction_config_data['auction_end_notify_email'] )
        {
          // BEGIN EMAIL-NOTIFY
          $sql = "SELECT user_email,
                         username
                  FROM " . USERS_TABLE . "
                  WHERE user_id=" . $user_id . "";

          if( !($result = $db->sql_query($sql)) )
               {} // if

          $user = $db->sql_fetchrow($result);

          $server_name = trim($board_config['server_name']);
          $server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
          $server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

          $username= $user['username'];
          $email= $user['user_email'];
          $emailer = new emailer($board_config['smtp_delivery']);
          $emailer->from($board_config['board_email']);
          $emailer->replyto($board_config['board_email']);

          if ( $notify_type == 'WON' )
               {
                    $emailer->use_template('auction_won', stripslashes($user_lang));
                    $emailer->set_subject($lang['auction_won']);
                    $emailer->assign_vars(array(
                         'AUCTION_WON' => $lang['auction_offer_won'],
                         'AUCTION_SITENAME' => $board_config['sitename'],
                         'AUCTION_OFFER' => prepare_message(addslashes(unprepare_message(htmlspecialchars(trim(stripslashes($offer_title))))), $board_config['allow_html'], $board_config['allow_bbcode'], $board_config['allow_smilies'], 0),
                         'U_AUCTION_OFFER' => $server_protocol . $server_name . $board_config['script_path'] . 'auction_offer_view.php?ao=' . $offer_id,
                         'AUCTION_EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '' ));
               }
          if ( $notify_type == 'SOLD' )
               {
                    $emailer->use_template('auction_sold', stripslashes($user_lang));
                    $emailer->set_subject($lang['auction_sold']);
                    $emailer->assign_vars(array(
                         'AUCTION_SOLD' => $lang['auction_offer_sold'],
                         'AUCTION_SITENAME' => $board_config['sitename'],
                         'AUCTION_OFFER' => prepare_message(addslashes(unprepare_message(htmlspecialchars(trim(stripslashes($offer_title))))), $board_config['allow_html'], $board_config['allow_bbcode'], $board_config['allow_smilies'], 0),
                         'U_AUCTION_OFFER' => $server_protocol . $server_name . $board_config['script_path'] . 'auction_offer_view.php?ao=' . $offer_id,
                         'AUCTION_EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '' ));
               }
          if ( $notify_type == 'NOT_SOLD' )
               {
                    $emailer->use_template('auction_not_sold', stripslashes($user_lang));
                    $emailer->set_subject($lang['auction_not_sold']);
                    $emailer->assign_vars(array(
                         'AUCTION_NOT_SOLD' => $lang['auction_offer_not_sold'],
                         'AUCTION_SITENAME' => $board_config['sitename'],
                         'AUCTION_OFFER' => prepare_message(addslashes(unprepare_message(htmlspecialchars(trim(stripslashes($offer_title))))), $board_config['allow_html'], $board_config['allow_bbcode'], $board_config['allow_smilies'], 0),
                         'U_AUCTION_OFFER' => $server_protocol . $server_name . $board_config['script_path'] . 'auction_offer_view.php?ao=' . $offer_id,
                         'AUCTION_EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '' ));
               }

          $emailer->email_address($email);

          // Try to send email...
          $emailer->send();
//          $emailer->reset();

        }  // END EMAIL-NOTIFY

      if ( $auction_config_data['auction_end_notify_pm'] )
        {

          // BEGIN PM-NOTIFY ON OUTBID
          if ( $notify_type == 'WON' )
               {
                     $pm_subject = $lang['auction_won'];
                     $pm_text = $lang['auction_won_text'];
               }
          if ( $notify_type == 'SOLD' )
               {
                     $pm_subject = $lang['auction_sold'];
                     $pm_text = $lang['auction_sold_text'];
               }
          if ( $notify_type == 'NOT_SOLD' )
               {
                     $pm_subject = $lang['auction_not_sold'];
                     $pm_text = $lang['auction_not_sold_text'];
               }

          $privmsgs_date = date("U");

          $sql = "INSERT INTO " . PRIVMSGS_TABLE . "
                     (privmsgs_type,
                      privmsgs_subject,
                      privmsgs_from_userid,
                      privmsgs_to_userid,
                      privmsgs_date,
                      privmsgs_enable_html,
                      privmsgs_enable_bbcode,
                      privmsgs_enable_smilies,
                      privmsgs_attach_sig)
                  VALUES ('0',
                          '" . str_replace("\'", "''", addslashes(sprintf($pm_subject,$board_config['sitename']))) . "',
                          '2',
                          " . $user_id . ",
                          " . $privmsgs_date . ",
                          '0',
                          '1',
                          '1',
                          '0')";

           if ( !$db->sql_query($sql) )
                {} // if

           $outbid_sent_id = $db->sql_nextid();

           $sql = "INSERT INTO " . PRIVMSGS_TEXT_TABLE . "
                      (privmsgs_text_id,
                       privmsgs_text)
                   VALUES (" . $outbid_sent_id .",
                           '" . str_replace("\'", "''", $pm_text . "</br></br><a href=\"auction_offer_view.php?ao=" . $offer_id . "\">" . prepare_message(addslashes(unprepare_message(htmlspecialchars(trim(stripslashes($offer_title))))), $board_config['allow_html'], $board_config['allow_bbcode'], $board_config['allow_smilies'], 0) . "</a></br>" . $board_config['board_email_sig'] ) . "')";

           if ( !$db->sql_query($sql) )
                {
                } // if

           $sql = "UPDATE " . USERS_TABLE . "
                   SET user_new_privmsg=user_new_privmsg+1
                   WHERE user_id=" . $user_id;

           if ( !$db->sql_query($sql) )
                {} // if

      } // End pm-notification

   }  // end function


/////////////////////////////////////////////////
//
/////////////////////////////////////////////////

     include($phpbb_root_path . 'includes/emailer.php');

     // Grab all offers to notify buyer if auction is over and won
     $sql = "SELECT FK_auction_offer_user_id,
                    PK_auction_offer_id,
                    auction_offer_title,
                    FK_auction_offer_last_bid_user_id
             FROM " . AUCTION_OFFER_TABLE . "
             WHERE auction_offer_time_stop<" . time() ." AND
                   FK_auction_offer_last_bid_user_id<>'' AND
                   auction_offer_notified_buyer=''";

     if( !$result = $db->sql_query($sql) )
          {}

     $total_notifications = 0;

     while( $row = $db->sql_fetchrow($result) )
     {
         $rowset[] = $row;
         $total_notifications++;
     } // while

     for($i = 0; $i < $total_notifications; $i++)
             {
                  // Write pms and emails
                  notifyUser($rowset[$i]['FK_auction_offer_last_bid_user_id'], 'WON',$rowset[$i]['PK_auction_offer_id'], $rowset[$i]['auction_offer_title']);

                  // mark offer buyer-notified
                  $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                          SET auction_offer_notified_buyer=1
                          WHERE PK_auction_offer_id=" . $rowset[$i]['PK_auction_offer_id'];

                  if( !$result = $db->sql_query($sql) )
                       {}// if
             }


     // NOTIFY SELLER ON USUAL SOLD
     $sql = "SELECT FK_auction_offer_user_id,
                    PK_auction_offer_id,
                    auction_offer_title,
                    FK_auction_offer_last_bid_user_id
             FROM " . AUCTION_OFFER_TABLE . "
             WHERE auction_offer_time_stop<" . time() ." AND
                   auction_offer_notified_seller=''";

     if( !$result = $db->sql_query($sql) )
          {}

     $total_notifications = 0;

     while( $row = $db->sql_fetchrow($result) )
     {
         $rowset[] = $row;
         $total_notifications++;
     } // while

     for($i = 0; $i < $total_notifications; $i++)
             {
                  // if no bid
                  if ( $rowset[$i]['FK_auction_offer_last_bid_user_id'] <>'0')
                       {
                            // Write pms and emails
                            notifyUser($rowset[$i]['FK_auction_offer_user_id'], 'SOLD',$rowset[$i]['PK_auction_offer_id'], $rowset[$i]['auction_offer_title']);
                       }
                  else
                       {
                            // Write pms and emails
                            notifyUser($rowset[$i]['FK_auction_offer_user_id'], 'NOT_SOLD',$rowset[$i]['PK_auction_offer_id'], $rowset[$i]['auction_offer_title']);
                       }

                  // mark offer buyer-notified
                  $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                          SET auction_offer_notified_seller=1
                          WHERE PK_auction_offer_id=" . $rowset[$i]['PK_auction_offer_id'];

                  if( !$result = $db->sql_query($sql) )
                       {}// if
             }


     // NOTIFY SELLER ON DIRECT SELLS
     $sql = "SELECT FK_auction_offer_user_id,
                    PK_auction_offer_id,
                    auction_offer_title,
                    FK_auction_offer_last_bid_user_id
             FROM " . AUCTION_OFFER_TABLE . "
             WHERE auction_offer_state=2 AND
                   auction_offer_notified_seller=''";

     if( !$result = $db->sql_query($sql) )
          {}

     $total_notifications = 0;

     while( $row = $db->sql_fetchrow($result) )
     {
         $rowset[] = $row;
         $total_notifications++;
     } // while

     for($i = 0; $i < $total_notifications; $i++)
             {
                  // Write pms and emails
                  notifyUser($rowset[$i]['FK_auction_offer_user_id'], 'SOLD', $rowset[$i]['PK_auction_offer_id'], $rowset[$i]['auction_offer_title']);

                  // mark offer buyer-notified
                  $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                          SET auction_offer_notified_seller=1
                          WHERE PK_auction_offer_id=" . $rowset[$i]['PK_auction_offer_id'];

                  if( !$result = $db->sql_query($sql) )
                       {}// if
             }

    // BEGIN charge percentage
     if ( $auction_config_data['auction_offer_cost_final_percent'] > 0 )
          {
               $sql = "SELECT PK_auction_offer_id,
                              auction_offer_last_bid_price,
                              FK_auction_offer_user_id
                       FROM " . AUCTION_OFFER_TABLE . "
                       WHERE auction_offer_time_stop<" . time() ." AND
                             auction_offer_percentage_charged=0";

                 if( !($result4 = $db->sql_query($sql)) )
                     {
                     } // if

                 $total_offers = 0;
                 while( $row4 = $db->sql_fetchrow($result4) )
                      {
                           $total_offer_rowset[] = $row4;
                           $total_offers++;
                      }
                 $leverage = ($auction_config_data['auction_offer_cost_final_percent']/100);
                 for($i = 0; $i < $total_offers; $i++)
                      {
                           $sql = "INSERT INTO " . AUCTION_ACCOUNT_TABLE . "
                                          (fk_auction_account_creditor_id,
                                           fk_auction_account_debitor_id,
                                           auction_account_auction_amount,
                                           auction_account_amount_date,
                                           fk_auction_offer_id,
                                           auction_account_action)
                                   VALUES (2,
                                           " . $total_offer_rowset[$i]['FK_auction_offer_user_id'] . ",
                                           " . $total_offer_rowset[$i]['auction_offer_last_bid_price']*$leverage . ",
                                           " . time() . ",
                                           " . $total_offer_rowset[$i]['PK_auction_offer_id'] . ",
                                           '" . ACTION_PERCENT . "')";

                           if( !($result = $db->sql_query($sql)) )
                                {
                                } // if

                           $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                                   SET auction_offer_percentage_charged=1
                                   WHERE pk_auction_offer_it =" . $total_offer_rowset[$i]['PK_auction_offer_id'];

                           if( !($result = $db->sql_query($sql)) )
                                {
                                } // if
                      }
           }
    // END charge percentage

?>