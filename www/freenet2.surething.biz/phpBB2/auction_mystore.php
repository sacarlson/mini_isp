<?php
/***************************************************************************
 *                          auction_mystore.php
 *                            -------------------
 *   begin                : Oct 2004
 *   copyright            : (C)  FR
 *   email                : fr@php-styles.com
 *   Last update          : November 2004 - FR
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
               redirect("login.".$phpEx."?redirect=auction_mystore.".$phpEx);
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

                 $page_title = $lang['auction_myauction_auctions'];
                 include('./includes/page_header.php');
                 include($phpbb_root_path . 'auction/auction_header.'.$phpEx);
                 $template->set_filenames(array('body' => 'auction_my_user_store.tpl'));

     if( !empty($mode) ) 
     {
         switch($mode)
         {
             case 'open':

                 $sql = "INSERT INTO " . AUCTION_USER_STORE_TABLE . " (fk_user_id)
                         VALUES (" . $userdata['user_id'] . ")";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not add user store', '', __LINE__, __FILE__, $sql);
                     } // if

                            $template->assign_block_vars('store_opened', array(
                                 'L_STORE_OPENED' => $lang['store_opened'] ));
                 break;

             case 'update':

                 $store_name = $HTTP_POST_VARS['store_name'];
                 $store_description = $HTTP_POST_VARS['store_description'];
                 $store_header = $HTTP_POST_VARS['store_header'];
                 $store_name = addslashes($store_name);
                 $store_description = addslashes($store_description);
                 $store_header = addslashes($store_header);

                 $sql = "UPDATE " . AUCTION_USER_STORE_TABLE . "
                         SET store_name= '" . $store_name . "',
                             store_description= '" . $store_description . "',
                             store_header= '" . $store_header . "'
                         WHERE  fk_user_id=" . $userdata['user_id'] . "";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not update user store', '', __LINE__, __FILE__, $sql);
                     } // if

                 break;
         }
    }


                 $sql = "SELECT *
                         FROM " . AUCTION_USER_STORE_TABLE . "
                         WHERE FK_user_id =" . $userdata['user_id'] . "";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not your user-store information', '', __LINE__, __FILE__, $sql);
                     } // if

                 $row = $db->sql_fetchrow($result);
                 
                 if ( $row['pk_auction_store_id'] == "" )
                 // if the user has no store so far
                      {
                            $template->assign_block_vars('no_store', array(
                                 'L_STORE_OPEN' => $lang['store_open'],
                                 'U_STORE_OPEN' => append_sid("auction_mystore.php?mode=open"),
                                 'L_STORE_NOT_OPENED' => $lang['store_not_opened'] ));
                      }
                 else
                 // if the user has an user store
                      {
                            $template->assign_block_vars('store', array(
                                 'STORE_NAME' => stripslashes($row['store_name']),
                                 'STORE_DESCRIPTION' => stripslashes($row['store_description']),
                                 'STORE_HEADER' => stripslashes($row['store_header']),

                                      'L_YES' => $lang['Yes'],
                                      'L_NO' => $lang['No'],

                                      'L_AUCTION_BLOCK_CLOSE_TO_END' => $lang['auction_block_close_to_end'],
                                      'L_AUCTION_BLOCK_AUCTION_ROOMS' => $lang['auction_block_auction_rooms'],
                                      'L_AUCTION_BLOCK_STATISTICS' => $lang['auction_block_statistics'],
                                      'L_AUCTION_BLOCK_MYAUCTIONS' => $lang['auction_block_myauctions'],
                                      'L_AUCTION_BLOCK_CALENDAR' => $lang['auction_block_calendar'],
                                      'L_AUCTION_BLOCK_TICKER' => $lang['auction_block_ticker'],
                                      'L_AUCTION_BLOCK_SEARCH' => $lang['auction_block_search'],
                                      'L_AUCTION_BLOCK_SPECIAL' => $lang['auction_block_special'],
                                      'L_AUCTION_BLOCK_PRICE_INFO' => $lang['auction_block_price_info'],
                                      'L_AUCTION_BLOCK_DROP_DOWN_AUCTION_ROOMS' => $lang['auction_block_drop_down_auction_rooms'],
                                      'L_AUCTION_BLOCK_LAST_BIDS' => $lang['auction_block_last_bids'],

                                 'SHOW_BLOCK_DROP_DOWN_YES' => ( $row['show_block_drop_down']) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_DROP_DOWN_NO' =>( !$row['show_block_drop_down'] ) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_ROOMS_YES' => ( $row['show_block_rooms']) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_ROOMS_NO' =>( !$row['show_block_rooms'] ) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_CLOSETOEND_YES' => ( $row['show_block_closetoend']) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_CLOSETOEND_NO' =>( !$row['show_block_closetoend'] ) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_STATISTICS_YES' => ( $row['show_block_statistics']) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_STATISTICS_NO' =>( !$row['show_block_statistics'] ) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_SEARCH_YES' => ( $row['show_block_search']) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_SEARCH_NO' =>( !$row['show_block_search'] ) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_MYAUCTION_YES' => ( $row['show_block_myauction']) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_MYAUCTION_NO' =>( !$row['show_block_myauction'] ) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_SPECIAL_YES' => ( $row['show_block_specials']) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_SPECIAL_NO' =>( !$row['show_block_specials'] ) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_CALENDAR_YES' => ( $row['show_block_calendar']) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_CALENDAR_NO' =>( !$row['show_block_calendar'] ) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_PRICEINFO_YES' => ( $row['show_block_priceinfo']) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_PRICEINFO_NO' =>( !$row['show_block_priceinfo'] ) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_TICKER_YES' => ( $row['show_block_ticker']) ? "checked=\"checked\"" : "",
                                 'SHOW_BLOCK_TICKER_NO' =>( !$row['show_block_ticker'] ) ? "checked=\"checked\"" : "",

                                 'L_STORE_VIEW' => $lang['store_view'],
                                 'U_STORE_VIEW' => append_sid("auction_store.php?mode=info&" . POST_USERS_URL . "=" . $userdata['user_id']. ""),
                                 'L_STORE_UPDATE' => $lang['store_update'],
                                 'U_STORE_UPDATE' => append_sid("auction_mystore.php?mode=update"),
                                 'L_STORE_NAME' => $lang['store_name'],
                                 'L_STORE_DESCRIPTION' => $lang['store_description'],
                                 'L_STORE_HEADER' => $lang['store_header'] ));
                      }

                 $template->pparse('body');
                 include($phpbb_root_path . 'auction/auction_footer.'.$phpEx);
                 include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>