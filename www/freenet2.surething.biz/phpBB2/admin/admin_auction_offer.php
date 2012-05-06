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
               $module['Auction']['a3_offer'] = append_sid($filename);
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

     // Sort setting
     if( isset($HTTP_POST_VARS['sort']) || isset($HTTP_GET_VARS['sort']) )
     {
         $sort = ( isset($HTTP_POST_VARS['sort']) ) ? $HTTP_POST_VARS['sort'] : $HTTP_GET_VARS['sort'];
         $sort = htmlspecialchars($sort);
     }
     else
     {
         $sort = "";
     } // if

     if ( $mode == "delete" )
          {
              $offer_id = ( isset($HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) ) ? $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] : $HTTP_POST_VARS[POST_AUCTION_OFFER_URL];

              // Delete the offer
              $sql = "DELETE FROM " . AUCTION_OFFER_TABLE . "
                      WHERE PK_auction_offer_id='" . $offer_id . "'";

              if( !$result = $db->sql_query($sql) )
                  {
                    message_die(GENERAL_ERROR, "Couldn't delete offer. Please try again.", "", __LINE__, __FILE__, $sql);
                  } // if

              // Delete all corresponding bids
              $sql = "DELETE FROM " . AUCTION_BID_TABLE . "
                      WHERE FK_auction_bid_offer_id='" . $offer_id . "'";

              if( !$result = $db->sql_query($sql) )
                  {
                    message_die(GENERAL_ERROR, "Couldn't delete corresponding bids. Please try again.", "", __LINE__, __FILE__, $sql);
                  }// if

              // Delete all corresponding watches
              $sql = "DELETE
                      FROM " . AUCTION_WATCHLIST_TABLE . "
                      WHERE FK_auction_offer_id = " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];

              if( !($result = $db->sql_query($sql)) )
                   {
                        message_die(GENERAL_ERROR, 'Could not delete offer watchlist data', '', __LINE__, __FILE__, $sql);
                   }

          }// if

     if ( $mode == "delete_all_unpaid" )
          {

                // Get all offer-ids
                // Delete all corresponding bids
                // Delete all offers
          } // if

     if ( $mode == "mark_paid" )
          {
              $offer_id = ( isset($HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) ) ? $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] : $HTTP_POST_VARS[POST_AUCTION_OFFER_URL];

              $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                      Set auction_offer_paid = 1
                      WHERE PK_auction_offer_id='" . $offer_id . "'";

              if( !$result = $db->sql_query($sql) )
                  {
                    message_die(GENERAL_ERROR, "Couldn't mark offer paid. Please try again.", "", __LINE__, __FILE__, $sql);
                  } // if

          } // if

     if ( $mode == "mark_unpaid" )
          {
              $offer_id = ( isset($HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) ) ? $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] : $HTTP_POST_VARS[POST_AUCTION_OFFER_URL];

              $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                      Set auction_offer_paid = 0
                      WHERE PK_auction_offer_id='" . $offer_id . "'";

              if( !$result = $db->sql_query($sql) )
                  {
                    message_die(GENERAL_ERROR, "Couldn't mark offer paid. Please try again.", "", __LINE__, __FILE__, $sql);
                  } // if
          } // if


     if ( $mode == "mark_undebit" )
          {
              $offer_id = ( isset($HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) ) ? $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] : $HTTP_POST_VARS[POST_AUCTION_OFFER_URL];

              // Change Account
              $sql = "UPDATE " . AUCTION_ACCOUNT_TABLE . "
                      Set auction_account_amount_paid=auction_account_auction_amount,
                          auction_account_amount_paid_by=2
                      WHERE FK_auction_offer_id='" . $offer_id . "'";

              if( !$result = $db->sql_query($sql) )
                  {
                    message_die(GENERAL_ERROR, "Couldn't mark offer paid in Account. Please try again.", "", __LINE__, __FILE__, $sql);
                  } // if
          } // if

     if ( $mode == "mark_debit" )
          {
              $offer_id = ( isset($HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) ) ? $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] : $HTTP_POST_VARS[POST_AUCTION_OFFER_URL];

              // Change Account
              $sql = "UPDATE " . AUCTION_ACCOUNT_TABLE . "
                      Set auction_account_amount_paid = 0,
                          auction_account_amount_paid_by=0
                      WHERE FK_auction_offer_id='" . $offer_id . "'";

              if( !$result = $db->sql_query($sql) )
                  {
                    message_die(GENERAL_ERROR, "Couldn't mark offer paid in Account. Please try again.", "", __LINE__, __FILE__, $sql);
                  } // if

          } // if

     // START Grab all the offer-data
     $sql = "SELECT t.*,
                    u.username,
                    u.user_id,
                    u2.username as maxbidder_user_name,
                    u2.user_id as maxbidder_user_id,
                    i.pic_id,
                    acc.auction_account_auction_amount,
                    acc.auction_account_amount_paid
             FROM (" . AUCTION_OFFER_TABLE . " t
             LEFT JOIN " . USERS_TABLE . " u ON u.user_id = t.FK_auction_offer_user_id
             LEFT JOIN " . USERS_TABLE . " u2 ON u2.user_id = t.FK_auction_offer_last_bid_user_id
             LEFT JOIN " . AUCTION_IMAGE_TABLE . " i ON t.pk_auction_offer_id=i.pic_auction_id
             LEFT JOIN " . AUCTION_ACCOUNT_TABLE . " acc ON t.pk_auction_offer_id=acc.fk_auction_offer_id)
             ORDER BY t.auction_offer_time_stop;";

     if ( $sort == 'title' )
          {
              $sql = "SELECT t.*, u.username, u.user_id, u2.username as maxbidder_user_name, u2.user_id as maxbidder_user_id, i.pic_id, acc.auction_account_auction_amount, acc.auction_account_amount_paid
                      FROM (" . AUCTION_OFFER_TABLE . " t
                      LEFT JOIN " . USERS_TABLE . " u ON u.user_id = t.FK_auction_offer_user_id
                      LEFT JOIN " . USERS_TABLE . " u2 ON u2.user_id = t.FK_auction_offer_last_bid_user_id
                      LEFT JOIN " . AUCTION_IMAGE_TABLE . " i ON t.pk_auction_offer_id=i.pic_auction_id
                      LEFT JOIN " . AUCTION_ACCOUNT_TABLE . " acc ON t.pk_auction_offer_id=acc.fk_auction_offer_id)
                      ORDER BY t.auction_offer_title;";
          } // if

     if ( $sort == 'username' )
          {
              $sql = "SELECT t.*, u.username, u.user_id, u2.username as maxbidder_user_name, u2.user_id as maxbidder_user_id, i.pic_id, acc.auction_account_auction_amount, acc.auction_account_amount_paid
                      FROM (" . AUCTION_OFFER_TABLE . " t
                      LEFT JOIN " . USERS_TABLE . " u ON u.user_id = t.FK_auction_offer_user_id
                      LEFT JOIN " . USERS_TABLE . " u2 ON u2.user_id = t.FK_auction_offer_last_bid_user_id
                      LEFT JOIN " . AUCTION_IMAGE_TABLE . " i ON t.pk_auction_offer_id=i.pic_auction_id
                      LEFT JOIN " . AUCTION_ACCOUNT_TABLE . " acc ON t.pk_auction_offer_id=acc.fk_auction_offer_id)
                      ORDER BY u.username;";
          } // if

     if ( $sort == 'paid' )
          {
              $sql = "SELECT t.*, u.username, u.user_id, u2.username as maxbidder_user_name, u2.user_id as maxbidder_user_id, i.pic_id, acc.auction_account_auction_amount, acc.auction_account_amount_paid
                      FROM (" . AUCTION_OFFER_TABLE . " t
                      LEFT JOIN " . USERS_TABLE . " u ON u.user_id = t.FK_auction_offer_user_id
                      LEFT JOIN " . USERS_TABLE . " u2 ON u2.user_id = t.FK_auction_offer_last_bid_user_id
                      LEFT JOIN " . AUCTION_IMAGE_TABLE . " i ON t.pk_auction_offer_id=i.pic_auction_id
                      LEFT JOIN " . AUCTION_ACCOUNT_TABLE . " acc ON t.pk_auction_offer_id=acc.fk_auction_offer_id)
                      ORDER BY t.auction_offer_paid;";
          } // if

     if ( $sort == 'just_paid' )
          {
              $sql = "SELECT t.*, u.username, u.user_id, u2.username as maxbidder_user_name, u2.user_id as maxbidder_user_id,i.pic_id, acc.auction_account_auction_amount, acc.auction_account_amount_paid
                      FROM (" . AUCTION_OFFER_TABLE . " t
                      LEFT JOIN " . USERS_TABLE . " u ON u.user_id = t.FK_auction_offer_user_id
                      LEFT JOIN " . USERS_TABLE . " u2 ON u2.user_id = t.FK_auction_offer_last_bid_user_id
                      LEFT JOIN " . AUCTION_IMAGE_TABLE . " i ON t.pk_auction_offer_id=i.pic_auction_id
                      LEFT JOIN " . AUCTION_ACCOUNT_TABLE . " acc ON t.pk_auction_offer_id=acc.fk_auction_offer_id)
                      WHERE t.auction_offer_paid = 1
                      ORDER BY t.auction_offer_title;";
          } // if

     if ( $sort == 'just_not_paid' )
          {
              $sql = "SELECT t.*, u.username, u.user_id, u2.username as maxbidder_user_name, u2.user_id as maxbidder_user_id, i.pic_id, acc.auction_account_auction_amount, acc.auction_account_amount_paid
                      FROM (" . AUCTION_OFFER_TABLE . " t
                      LEFT JOIN " . USERS_TABLE . " u ON u.user_id = t.FK_auction_offer_user_id
                      LEFT JOIN " . USERS_TABLE . " u2 ON u2.user_id = t.FK_auction_offer_last_bid_user_id
                      LEFT JOIN " . AUCTION_IMAGE_TABLE . " i ON t.pk_auction_offer_id=i.pic_auction_id
                      LEFT JOIN " . AUCTION_ACCOUNT_TABLE . " acc ON t.pk_auction_offer_id=acc.fk_auction_offer_id)
                      WHERE t.auction_offer_paid = 0
                      ORDER BY t.auction_offer_title;";
          }// if

     if( !$result = $db->sql_query($sql) )
            {
                    message_die(GENERAL_ERROR, "Couldn't get all offers", "", __LINE__, __FILE__, $sql);
            }// if

     $total_offers = 0;
     while( $row = $db->sql_fetchrow($result) )
     {
         $offer_rowset[] = $row;
         $total_offers++;
     } // while

     $db->sql_freeresult($result);
     $template->set_filenames(array('body' => 'admin/admin_auction_offer.tpl'));

    if ( $total_offers < 1 )
         {
                 $template->assign_block_vars('no_offer', array(
                     'L_NO_OFFER' => $lang['no_offer_exist']));
         }
    else
         {
             for($i = 0; $i < $total_offers; $i++)
             {
                  if (!$offer_rowset[$i]['auction_offer_paid'] )
                       {
                            $mark = $lang['auction_offer_mark_paid'];
                            $u_mark = append_sid("admin_auction_offer.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $offer_rowset[$i]['PK_auction_offer_id'] . "&mode=mark_paid");
                            $paid = $lang['auction_offer_not_paid'];
                       }
                  else
                      {
                           if ( $offer_rowset[$i]['auction_account_auction_amount'] > 0 )
                                {
                                     if ( $offer_rowset[$i]['auction_account_auction_amount']>$offer_rowset[$i]['auction_account_amount_paid'] )
                                          {
                                               $mark = $lang['auction_offer_mark_undebit'];
                                               $u_mark = append_sid("admin_auction_offer.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $offer_rowset[$i]['PK_auction_offer_id'] . "&mode=mark_undebit");
                                               $paid = $lang['auction_offer_debitted'];
                                          }
                                     if ( $offer_rowset[$i]['auction_account_auction_amount']==$offer_rowset[$i]['auction_account_amount_paid'] )
                                          {
                                               $mark = $lang['auction_offer_put_on_debit'];
                                               $u_mark = append_sid("admin_auction_offer.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $offer_rowset[$i]['PK_auction_offer_id'] . "&mode=mark_debit");
                                               $paid = $lang['auction_offer_undebitted'];
                                          }
                                }
                           else
                                 {
                                     $mark = $lang['auction_offer_mark_unpaid'];
                                     $u_mark = append_sid("admin_auction_offer.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $offer_rowset[$i]['PK_auction_offer_id'] . "&mode=mark_unpaid");
                                     $paid = $lang['auction_offer_paid'];
                                 }
                      }

                 $template->assign_block_vars('offer', array(
                     'L_AUCTION_OFFER_MARK_PAID' => $mark,
                     'U_AUCTION_OFFER_MARK_PAID' => $u_mark,

                     'AUCTION_OFFER_TITLE' => $offer_rowset[$i]['auction_offer_title'],
                     'AUCTION_OFFER_ID' => $offer_rowset[$i]['PK_auction_offer_id'],
                     'AUCTION_OFFER_OFFERER' => $offer_rowset[$i]['username'],
                     'AUCTION_OFFER_VIEWS' => $offer_rowset[$i]['auction_offer_views'],
                     'AUCTION_OFFER_PICTURE' => ( $offer_rowset[$i]['pic_id'] ) ? "X" : "",
                     'AUCTION_OFFER_COMMENT' => ( $offer_rowset[$i]['auction_offer_comment'] ) ? "X" : "",
                     'AUCTION_OFFER_SPECIAL' => ( $offer_rowset[$i]['auction_offer_special'] ) ? "X" : "",
                     'AUCTION_OFFER_BOLD' => ( $offer_rowset[$i]['auction_offer_bold'] )  ? "X" : "",
                     'AUCTION_OFFER_ON_TOP' => ( $offer_rowset[$i]['auction_offer_on_top'] )  ? "X" : "",
                     'AUCTION_OFFER_SELL_ON_FIRST' => ( $offer_rowset[$i]['auction_offer_direct_sell_price']<>0 )  ? "X" : "",
                     'AUCTION_OFFER_PAID' => $paid,
                     'AUCTION_OFFER_TIME_END' => create_date("m/d/Y - h:i:s", $offer_rowset[$i]['auction_offer_time_stop'], $board_config['board_timezone']),
                     'COUPON_USER_CREATED' => $coupon_rowset[$i]['coupon_creator'],
                     'COUPON_DATE_USED' => ( $coupon_rowset[$i]['auction_coupon_date_used']>0 ) ? create_date("m/d/Y - h:i:s", $coupon_rowset[$i]['auction_coupon_date_used'], $board_config['board_timezone']) : $lang['coupon_not_used'],
                     'COUPON_USER_USED' => ( $coupon_rowset[$i]['coupon_user']<>"" ) ? $coupon_rowset[$i]['coupon_user'] : $lang['coupon_not_used'],

                     'U_AUCTION_OFFER_VIEW' => append_sid("../auction_offer_view.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $offer_rowset[$i]['PK_auction_offer_id']),
                     'U_AUCTION_OFFER_DELETE' => append_sid("admin_auction_offer.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $offer_rowset[$i]['PK_auction_offer_id'] . "&mode=delete")));
             } // for
          } // if

     $template->assign_vars(array(
            'L_ADMIN_OFFER' => $lang['offer_admin'],
            'L_AUCTION_OFFER_ID' => $lang['auction_offer_id'],
            'L_ADMIN_OFFER_EXPLAIN' => $lang['offer_admin_explain'],
            'L_AUCTION_OFFER_TITLE' => $lang['auction_offer_title'],
            'L_AUCTION_OFFER_TIME_STOP' => $lang['auction_offer_time_stop'],
            'L_AUCTION_OFFER_PAID' => $lang['auction_offer_paid_status'],
            'L_AUCTION_OFFER_ON_TOP' => $lang['auction_offer_on_top_short'],
            'L_AUCTION_OFFER_PICTURE' => $lang['auction_offer_picture_short'],
            'L_AUCTION_OFFER_VIEWS' => $lang['auction_offer_views_short'],
            'L_AUCTION_OFFER_SPECIAL' => $lang['auction_offer_special_short'],
            'L_AUCTION_OFFER_BOLD' => $lang['auction_offer_bold_short'],
            'L_AUCTION_OFFER_SELL_ON_FIRST' => $lang['auction_offer_sell_on_comment_short'],
            'L_AUCTION_OFFER_COMMENT' => $lang['auction_offer_comment_short'],
            'L_AUCTION_OFFER_OFFERER' => $lang['auction_offer_offerer'],
            'L_AUCTION_OFFER_DELETE' => $lang['auction_offer_delete'],
            'L_AUCTION_OFFER_MARK_PAID' => $lang['auction_offer_function'],
            'L_AUCTION_OFFER_DELETE' => $lang['auction_offer_delete'],
            'L_AUCTION_OFFER_SORT_JUST_PAID' => $lang['auction_offer_sort_just_paid'],
            'L_AUCTION_OFFER_SORT_JUST_NOT_PAID' => $lang['auction_offer_sort_just_not_paid'],

            'L_COUPON_USER_CREATED' => $lang['coupon_user_created'],
            'L_COUPON_DATE_USED' => $lang['coupon_date_used'],
            'L_COUPNG_USER_USED'=> $lang['coupon_user_used'],
            'L_COUPON_CREATE' => $lang['coupon_create'],
            'L_CHOOSE_COUPON_TYPE' => $lang['coupon_choose_type'],

            'S_AUCTION_COUPON_ACTION' => append_sid("admin_auction_coupon.$phpEx?mode=create"),

            'COUPON_LIST_DD' => $coupon_list_dd,
            'U_AUCTION_OFFER_SORT_TITLE' => append_sid("admin_auction_offer.$phpEx?sort=title"),
            'U_AUCTION_OFFER_SORT_USERNAME' => append_sid("admin_auction_offer.$phpEx?sort=username"),
            'U_AUCTION_OFFER_SORT_PAID' => append_sid("admin_auction_offer.$phpEx?sort=paid"),        
            'U_AUCTION_OFFER_SORT_JUST_PAID' => append_sid("admin_auction_offer.$phpEx?sort=just_paid"),
            'U_AUCTION_OFFER_SORT_JUST_NOT_PAID' => append_sid("admin_auction_offer.$phpEx?sort=just_not_paid"),
            'U_AUCTION_OFFER_SORT_TIME' => append_sid("admin_auction_offer.$phpEx")));


      $template->pparse("body");
      include('./page_footer_admin.'.$phpEx);

?>