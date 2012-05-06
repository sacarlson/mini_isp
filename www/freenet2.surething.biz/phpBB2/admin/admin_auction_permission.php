<?php
/***************************************************************************
 *                          admin_auction_permission.php
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

     define('IN_PHPBB', 1);
     // set admin-navigation
     if( !empty($setmodules) )
          {
               $file = basename(__FILE__);
               $module['Auction']['a7_permission'] = "$file";
               return;
          } // if

     $phpbb_root_path = "./../";
     require($phpbb_root_path . 'extension.inc');
     require('./pagestart.' . $phpEx);
     include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);
     include($phpbb_root_path . 'auction/functions_general.php');
     include($phpbb_root_path . 'auction/auction_constants.php');
     include($phpbb_root_path . 'auction/functions_selects.php');

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
          }

     if( !empty($mode) )
     {
         switch($mode)
         {
             case 'add_moderator':

                       $role_inserted = "FALSE";

                       // if id was set in the form
                       if ( $HTTP_POST_VARS['add_moderator_id']<>"" )
                            {
                                 // Check if user exists
                                 $sql = "SELECT username
                                         FROM " . USERS_TABLE . "
                                         WHERE user_id=" . $HTTP_POST_VARS['add_moderator_id'];

                                 if ( !($result = $db->sql_query($sql)) )
                                      {
                                           message_die(GENERAL_ERROR, 'Could not verify user-id', '', __LINE__, __FILE__, $sql);
                                      } // if

                                 $row = $db->sql_fetchrow($result);

                                 // insert new user if user_id exists
                                 if ( !isset($row['username']) )
                                      {
                                          $template->assign_block_vars('error_row', array(
                                                'ERROR_MESSAGE' => $lang['auction_invalid_user_id'] ));
                                        } // if
                                 else
                                      {
                                           $sql = "INSERT INTO " . AUCTION_USER_ROLE_TABLE . "
                                                   VALUES (" . $HTTP_POST_VARS['add_moderator_id'] . ", 4, " . time() . ")";

                                           if ( !($result = $db->sql_query($sql)) )
                                               {
                                                   message_die(GENERAL_ERROR, 'Could not insert new moderator', '', __LINE__, __FILE__, $sql);
                                               } // if
                                           $role_inserted = "TRUE";

                                      } // else
                             } // if
                        elseif ( ( $HTTP_POST_VARS['add_moderator_name']<>"" ) AND
                                 ( $role_inserted == "FALSE" ) )
                             {
                                 // Check if user exists
                                 $sql = "SELECT user_id
                                         FROM " . USERS_TABLE . "
                                         WHERE username='" . $HTTP_POST_VARS['add_moderator_name'] . "'";

                                 if ( !($result = $db->sql_query($sql)) )
                                      {
                                           message_die(GENERAL_ERROR, 'Could not verify user-id', '', __LINE__, __FILE__, $sql);
                                      } // if

                                 $row = $db->sql_fetchrow($result);

                                 // insert new user if user_id exists
                                 if ( !isset($row['user_id']) )
                                      {
                                          $template->assign_block_vars('error_row', array(
                                                'ERROR_MESSAGE' => $lang['auction_invalid_user_name'] ));
                                        } // if
                                 else
                                      {
                                           $sql = "INSERT INTO " . AUCTION_USER_ROLE_TABLE . "
                                                   VALUES (" . $row['user_id'] . ", 4, " . time() . ")";

                                           if ( !($result = $db->sql_query($sql)) )
                                               {
                                                   message_die(GENERAL_ERROR, 'Could not insert new moderator', '', __LINE__, __FILE__, $sql);
                                               } // if

                                      } // else

                             } // elseif

                     break; // end add_moderator
                     
             case 'add_auctioneer':

                       $role_inserted = "FALSE";

                       // if id was set in the form
                       if ( $HTTP_POST_VARS['add_auctioneer_id']<>"" )
                            {
                                 // Check if user exists
                                 $sql = "SELECT username
                                         FROM " . USERS_TABLE . "
                                         WHERE user_id=" . $HTTP_POST_VARS['add_auctioneer_id'];

                                 if ( !($result = $db->sql_query($sql)) )
                                      {
                                           message_die(GENERAL_ERROR, 'Could not verify user-id', '', __LINE__, __FILE__, $sql);
                                      } // if

                                 $row = $db->sql_fetchrow($result);

                                 // insert new user if user_id exists
                                 if ( !isset($row['username']) )
                                      {
                                          $template->assign_block_vars('error_row', array(
                                                'ERROR_MESSAGE' => $lang['auction_invalid_user_id'] ));
                                        } // if
                                 else
                                      {
                                           $sql = "INSERT INTO " . AUCTION_USER_ROLE_TABLE . "
                                                   VALUES (" . $HTTP_POST_VARS['add_auctioneer_id'] . ", 3, " . time() . ")";

                                           if ( !($result = $db->sql_query($sql)) )
                                               {
                                                   message_die(GENERAL_ERROR, 'Could not insert new auctioneer', '', __LINE__, __FILE__, $sql);
                                               } // if
                                           $role_inserted = "TRUE";

                                      } // else
                             } // if
                        elseif ( ( $HTTP_POST_VARS['add_auctioneer_name']<>"" ) AND
                                 ( $role_inserted == "FALSE" ) )
                             {
                                 // Check if user exists
                                 $sql = "SELECT user_id
                                         FROM " . USERS_TABLE . "
                                         WHERE username='" . $HTTP_POST_VARS['add_auctioneer_name'] . "'";

                                 if ( !($result = $db->sql_query($sql)) )
                                      {
                                           message_die(GENERAL_ERROR, 'Could not verify user-id', '', __LINE__, __FILE__, $sql);
                                      } // if

                                 $row = $db->sql_fetchrow($result);

                                 // insert new user if user_id exists
                                 if ( !isset($row['user_id']) )
                                      {
                                          $template->assign_block_vars('error_row', array(
                                                'ERROR_MESSAGE' => $lang['auction_invalid_user_name'] ));
                                        } // if
                                 else
                                      {
                                           $sql = "INSERT INTO " . AUCTION_USER_ROLE_TABLE . "
                                                   VALUES (" . $row['user_id'] . ", 3, " . time() . ")";

                                           if ( !($result = $db->sql_query($sql)) )
                                               {
                                                   message_die(GENERAL_ERROR, 'Could not insert new auctioneer', '', __LINE__, __FILE__, $sql);
                                               } // if

                                      } // else

                             } // elseif

                     break; // end add_auctioneer

             case 'add_admin':

                       $role_inserted = "FALSE";
                       // if id was set in the form
                       if ( $HTTP_POST_VARS['add_admin_id']<>"" )
                            {
                                 // Check if user exists
                                 $sql = "SELECT username
                                         FROM " . USERS_TABLE . "
                                         WHERE user_id=" . $HTTP_POST_VARS['add_admin_id'];

                                 if ( !($result = $db->sql_query($sql)) )
                                      {
                                           message_die(GENERAL_ERROR, 'Could not verify user-id', '', __LINE__, __FILE__, $sql);
                                      } // if

                                 $row = $db->sql_fetchrow($result);

                                 // insert new user if user_id exists
                                 if ( !isset($row['username']) )
                                      {
                                          $template->assign_block_vars('error_row', array(
                                                'ERROR_MESSAGE' => $lang['auction_invalid_user_id'] ));
                                        } // if
                                 else
                                      {
                                           $sql = "INSERT INTO " . AUCTION_USER_ROLE_TABLE . "
                                                   VALUES (" . $HTTP_POST_VARS['add_admin_id'] . ", 5, " . time() . ")";

                                           if ( !($result = $db->sql_query($sql)) )
                                               {
                                                   message_die(GENERAL_ERROR, 'Could not insert new admin', '', __LINE__, __FILE__, $sql);
                                               } // if

                                           $role_inserted = "TRUE";

                                      } // else
                             } // if
                        elseif ( ( $HTTP_POST_VARS['add_admin_name']<>"" ) AND
                                 ( $role_inserted == "FALSE" ) )
                             {
                                 // Check if user exists
                                 $sql = "SELECT user_id
                                         FROM " . USERS_TABLE . "
                                         WHERE username='" . $HTTP_POST_VARS['add_admin_name'] . "'";

                                 if ( !($result = $db->sql_query($sql)) )
                                      {
                                           message_die(GENERAL_ERROR, 'Could not verify user-id', '', __LINE__, __FILE__, $sql);
                                      } // if

                                 $row = $db->sql_fetchrow($result);

                                 // insert new user if user_id exists
                                 if ( !isset($row['user_id']) )
                                      {
                                          $template->assign_block_vars('error_row', array(
                                                'ERROR_MESSAGE' => $lang['auction_invalid_user_name'] ));
                                        } // if
                                 else
                                      {
                                           $sql = "INSERT INTO " . AUCTION_USER_ROLE_TABLE . "
                                                   VALUES (" . $row['user_id'] . ", 5, " . time() . ")";

                                           if ( !($result = $db->sql_query($sql)) )
                                               {
                                                   message_die(GENERAL_ERROR, 'Could not insert new admin', '', __LINE__, __FILE__, $sql);
                                               } // if

                                      } // else

                             } // elseif

                     break; // end add_auctioneer

             case 'delete_from_auctioneer':

                       $user_id = ( $HTTP_POST_VARS[POST_USERS_URL] ) ? $HTTP_POST_VARS[POST_USERS_URL] : $HTTP_GET_VARS[POST_USERS_URL];

                       $sql = "DELETE FROM " . AUCTION_USER_ROLE_TABLE . "
                               WHERE FK_user_id = " . $user_id . " AND
                                     FK_auction_role = 3";

                       if ( !($result = $db->sql_query($sql)) )
                            {
                                 message_die(GENERAL_ERROR, 'Could not delete auctioneer', '', __LINE__, __FILE__, $sql);
                            }

                     break;

             case 'delete_from_moderator':

                       $user_id = ( $HTTP_POST_VARS[POST_USERS_URL] ) ? $HTTP_POST_VARS[POST_USERS_URL] : $HTTP_GET_VARS[POST_USERS_URL];

                       $sql = "DELETE FROM " . AUCTION_USER_ROLE_TABLE . "
                               WHERE FK_user_id = " . $user_id . " AND
                                     FK_auction_role = 4";

                       if ( !($result = $db->sql_query($sql)) )
                            {
                                 message_die(GENERAL_ERROR, 'Could not delete moderator', '', __LINE__, __FILE__, $sql);
                            }

                     break;

             case 'delete_from_admin':

                       $user_id = ( $HTTP_POST_VARS[POST_USERS_URL] ) ? $HTTP_POST_VARS[POST_USERS_URL] : $HTTP_GET_VARS[POST_USERS_URL];

                       $sql = "DELETE FROM " . AUCTION_USER_ROLE_TABLE . "
                               WHERE FK_user_id = " . $user_id . " AND
                                     FK_auction_role = 5";

                       if ( !($result = $db->sql_query($sql)) )
                            {
                                 message_die(GENERAL_ERROR, 'Could not delete admin', '', __LINE__, __FILE__, $sql);
                            }

                     break;
             case 'update_auth':

                       $view_all = ( $HTTP_POST_VARS['guest_view_all'] ) ? 1 : 0 ;
                       $view_offer = ( $HTTP_POST_VARS['guest_view_offer'] ) ? 1 : 0;
                       $view_bid_history = ( $HTTP_POST_VARS['guest_view_bid_history'] ) ? 1 : 0;
                       $new = ( $HTTP_POST_VARS['guest_new'] ) ? 1 : 0;
                       $bid = ( $HTTP_POST_VARS['guest_bid'] ) ? 1 : 0 ;
                       $direct_sell = ( $HTTP_POST_VARS['guest_direct_sell'] ) ? 1 : 0;
                       $image_upload = ( $HTTP_POST_VARS['guest_image_upload']  ) ? 1 : 0;
                       $comment = ( $HTTP_POST_VARS['guest_comment'] ) ? 1 : 0;
                       $move = ( $HTTP_POST_VARS['guest_move'] ) ? 1 : 0;
                       $delete_offer = ( $HTTP_POST_VARS['guest_delete_offer'] ) ? 1 : 0;
                       $delete_bid = ( $HTTP_POST_VARS['guest_delete_bid'] ) ? 1 : 0;
                       $special = ( $HTTP_POST_VARS['guest_special'] ) ? 1 : 0;
                       
                       $sql = "UPDATE " . AUCTION_ROLE_TABLE . "
                               SET  view_all = " . $view_all . ",
                                    view_offer = " . $view_offer . ",
                                    view_bid_history = " . $view_bid_history . ",
                                    new = " . $new . ",
                                    bid = " . $bid . ",
                                    direct_sell = " . $direct_sell . ",
                                    image_upload = " . $image_upload . ",
                                    comment = " . $comment . ",
                                    move = " . $move . ",
                                    delete_offer = " . $delete_offer . ",
                                    delete_bid = " . $delete_bid . ",
                                    special = " .  $special . "
                               WHERE PK_auction_role_id=1";

                       if ( !($result = $db->sql_query($sql)) )
                            {
                                 message_die(GENERAL_ERROR, 'Could not update ', '', __LINE__, __FILE__, $sql);
                            }

                       $view_all = ( $HTTP_POST_VARS['reg_view_all'] ) ? 1 : 0 ;
                       $view_offer = ( $HTTP_POST_VARS['reg_view_offer'] ) ? 1 : 0;
                       $view_bid_history = ( $HTTP_POST_VARS['reg_view_bid_history'] ) ? 1 : 0;
                       $new = ( $HTTP_POST_VARS['reg_new'] ) ? 1 : 0;
                       $bid = ( $HTTP_POST_VARS['reg_bid'] ) ? 1 : 0 ;
                       $direct_sell = ( $HTTP_POST_VARS['reg_direct_sell'] ) ? 1 : 0;
                       $image_upload = ( $HTTP_POST_VARS['reg_image_upload']  ) ? 1 : 0;
                       $comment = ( $HTTP_POST_VARS['reg_comment'] ) ? 1 : 0;
                       $move = ( $HTTP_POST_VARS['reg_move'] ) ? 1 : 0;
                       $delete_offer = ( $HTTP_POST_VARS['reg_delete_offer'] ) ? 1 : 0;
                       $delete_bid = ( $HTTP_POST_VARS['reg_delete_bid'] ) ? 1 : 0;
                       $special = ( $HTTP_POST_VARS['reg_special'] ) ? 1 : 0;

                       $sql = "UPDATE " . AUCTION_ROLE_TABLE . "
                               SET  view_all = " . $view_all . ",
                                    view_offer = " . $view_offer . ",
                                    view_bid_history = " . $view_bid_history . ",
                                    new = " . $new . ",
                                    bid = " . $bid . ",
                                    direct_sell = " . $direct_sell . ",
                                    image_upload = " . $image_upload . ",
                                    comment = " . $comment . ",
                                    move = " . $move . ",
                                    delete_offer = " . $delete_offer . ",
                                    delete_bid = " . $delete_bid . ",
                                    special = " .  $special . "
                               WHERE PK_auction_role_id=2";

                       if ( !($result = $db->sql_query($sql)) )
                            {
                                 message_die(GENERAL_ERROR, 'Could not update ', '', __LINE__, __FILE__, $sql);
                            }

                       $view_all = ( $HTTP_POST_VARS['auctioneer_view_all'] ) ? 1 : 0 ;
                       $view_offer = ( $HTTP_POST_VARS['auctioneer_view_offer'] ) ? 1 : 0;
                       $view_bid_history = ( $HTTP_POST_VARS['auctioneer_view_bid_history'] ) ? 1 : 0;
                       $new = ( $HTTP_POST_VARS['auctioneer_new'] ) ? 1 : 0;
                       $bid = ( $HTTP_POST_VARS['auctioneer_bid'] ) ? 1 : 0 ;
                       $direct_sell = ( $HTTP_POST_VARS['auctioneer_direct_sell'] ) ? 1 : 0;
                       $image_upload = ( $HTTP_POST_VARS['auctioneer_image_upload']  ) ? 1 : 0;
                       $comment = ( $HTTP_POST_VARS['auctioneer_comment'] ) ? 1 : 0;
                       $move = ( $HTTP_POST_VARS['auctioneer_move'] ) ? 1 : 0;
                       $delete_offer = ( $HTTP_POST_VARS['auctioneer_delete_offer'] ) ? 1 : 0;
                       $delete_bid = ( $HTTP_POST_VARS['auctioneer_delete_bid'] ) ? 1 : 0;
                       $special = ( $HTTP_POST_VARS['auctioneer_special'] ) ? 1 : 0;

                       $sql = "UPDATE " . AUCTION_ROLE_TABLE . "
                               SET  view_all = " . $view_all . ",
                                    view_offer = " . $view_offer . ",
                                    view_bid_history = " . $view_bid_history . ",
                                    new = " . $new . ",
                                    bid = " . $bid . ",
                                    direct_sell = " . $direct_sell . ",
                                    image_upload = " . $image_upload . ",
                                    comment = " . $comment . ",
                                    move = " . $move . ",
                                    delete_offer = " . $delete_offer . ",
                                    delete_bid = " . $delete_bid . ",
                                    special = " .  $special . "
                               WHERE PK_auction_role_id=3";

                       if ( !($result = $db->sql_query($sql)) )
                            {
                                 message_die(GENERAL_ERROR, 'Could not update ', '', __LINE__, __FILE__, $sql);
                            }

                       $view_all = ( $HTTP_POST_VARS['moderator_view_all'] ) ? 1 : 0 ;
                       $view_offer = ( $HTTP_POST_VARS['moderator_view_offer'] ) ? 1 : 0;
                       $view_bid_history = ( $HTTP_POST_VARS['moderator_view_bid_history'] ) ? 1 : 0;
                       $new = ( $HTTP_POST_VARS['moderator_new'] ) ? 1 : 0;
                       $bid = ( $HTTP_POST_VARS['moderator_bid'] ) ? 1 : 0 ;
                       $direct_sell = ( $HTTP_POST_VARS['moderator_direct_sell'] ) ? 1 : 0;
                       $image_upload = ( $HTTP_POST_VARS['moderator_image_upload']  ) ? 1 : 0;
                       $comment = ( $HTTP_POST_VARS['moderator_comment'] ) ? 1 : 0;
                       $move = ( $HTTP_POST_VARS['moderator_move'] ) ? 1 : 0;
                       $delete_offer = ( $HTTP_POST_VARS['moderator_delete_offer'] ) ? 1 : 0;
                       $delete_bid = ( $HTTP_POST_VARS['moderator_delete_bid'] ) ? 1 : 0;
                       $special = ( $HTTP_POST_VARS['moderator_special'] ) ? 1 : 0;

                       $sql = "UPDATE " . AUCTION_ROLE_TABLE . "
                               SET  view_all = " . $view_all . ",
                                    view_offer = " . $view_offer . ",
                                    view_bid_history = " . $view_bid_history . ",
                                    new = " . $new . ",
                                    bid = " . $bid . ",
                                    direct_sell = " . $direct_sell . ",
                                    image_upload = " . $image_upload . ",
                                    comment = " . $comment . ",
                                    move = " . $move . ",
                                    delete_offer = " . $delete_offer . ",
                                    delete_bid = " . $delete_bid . ",
                                    special = " .  $special . "
                               WHERE PK_auction_role_id=4";

                       if ( !($result = $db->sql_query($sql)) )
                            {
                                 message_die(GENERAL_ERROR, 'Could not update ', '', __LINE__, __FILE__, $sql);
                            }

                       $view_all = ( $HTTP_POST_VARS['admin_view_all'] ) ? 1 : 0 ;
                       $view_offer = ( $HTTP_POST_VARS['admin_view_offer'] ) ? 1 : 0;
                       $view_bid_history = ( $HTTP_POST_VARS['admin_view_bid_history'] ) ? 1 : 0;
                       $new = ( $HTTP_POST_VARS['admin_new'] ) ? 1 : 0;
                       $bid = ( $HTTP_POST_VARS['admin_bid'] ) ? 1 : 0 ;
                       $direct_sell = ( $HTTP_POST_VARS['admin_direct_sell'] ) ? 1 : 0;
                       $image_upload = ( $HTTP_POST_VARS['admin_image_upload']  ) ? 1 : 0;
                       $comment = ( $HTTP_POST_VARS['admin_comment'] ) ? 1 : 0;
                       $move = ( $HTTP_POST_VARS['admin_move'] ) ? 1 : 0;
                       $delete_offer = ( $HTTP_POST_VARS['admin_delete_offer'] ) ? 1 : 0;
                       $delete_bid = ( $HTTP_POST_VARS['admin_delete_bid'] ) ? 1 : 0;
                       $special = ( $HTTP_POST_VARS['admin_special'] ) ? 1 : 0;

                       $sql = "UPDATE " . AUCTION_ROLE_TABLE . "
                               SET  view_all = " . $view_all . ",
                                    view_offer = " . $view_offer . ",
                                    view_bid_history = " . $view_bid_history . ",
                                    new = " . $new . ",
                                    bid = " . $bid . ",
                                    direct_sell = " . $direct_sell . ",
                                    image_upload = " . $image_upload . ",
                                    comment = " . $comment . ",
                                    move = " . $move . ",
                                    delete_offer = " . $delete_offer . ",
                                    delete_bid = " . $delete_bid . ",
                                    special = " .  $special . "
                               WHERE PK_auction_role_id=5";

                       if ( !($result = $db->sql_query($sql)) )
                            {
                                 message_die(GENERAL_ERROR, 'Could not update ', '', __LINE__, __FILE__, $sql);
                            }

                  break;

              default:

                 break;
          } // switch
    }

     // Get admins
     $sql = "SELECT u.username, u.user_id
             FROM ( " . AUCTION_USER_ROLE_TABLE . " ur LEFT JOIN " . USERS_TABLE . " u
                  ON ur.FK_user_id=u.user_id )
             WHERE ur.FK_auction_role=5
             ORDER BY u.username";

     if ( !($result = $db->sql_query($sql)) )
          {
               message_die(GENERAL_ERROR, 'Could not query adminlist', '', __LINE__, __FILE__, $sql);
          }

     $total_admins = 0;
     while( $row = $db->sql_fetchrow($result) )
          {
               $admin_rowset[] = $row;
               $total_admins++;
          }

     $db->sql_freeresult($result);

     for($i = 0; $i < $total_admins; $i++)
          {
               $template->assign_block_vars('admin_row', array(
                    'REGISTRATION_DATE' => create_date($board_config['default_dateformat'], $member_rowset[$i]['user_regdate'], $board_config['board_timezone']),
                    'U_USER_NAME' => append_sid("../profile.php?mode=viewprofile&" . POST_USERS_URL . "=" . $admin_rowset[$i]['user_id']),
                    'U_DELETE_FROM_ROLE' => append_sid("admin_auction_permission.php?mode=delete_from_admin&" . POST_USERS_URL . "=" . $admin_rowset[$i]['user_id']),
                    'USER_NAME' => $admin_rowset[$i]['username']));
          }

     // Get moderators
     $sql = "SELECT u.username, u.user_id
             FROM ( " . AUCTION_USER_ROLE_TABLE . " ur LEFT JOIN " . USERS_TABLE . " u
                  ON ur.FK_user_id=u.user_id )
             WHERE ur.FK_auction_role=4
             ORDER BY u.username";

     if ( !($result = $db->sql_query($sql)) )
          {
               message_die(GENERAL_ERROR, 'Could not query moderatorlist', '', __LINE__, __FILE__, $sql);
          }

     $total_moderators = 0;
     while( $row = $db->sql_fetchrow($result) )
          {
               $moderator_rowset[] = $row;
               $total_moderators++;
          }

     $db->sql_freeresult($result);

     for($i = 0; $i < $total_moderators; $i++)
          {
               $template->assign_block_vars('moderator_row', array(
                    'REGISTRATION_DATE' => create_date($board_config['default_dateformat'], $member_rowset[$i]['user_regdate'], $board_config['board_timezone']),
                    'U_USER_NAME' => append_sid("../profile.php?mode=viewprofile&" . POST_USERS_URL . "=" . $moderator_rowset[$i]['user_id']),
                    'U_DELETE_FROM_ROLE' => append_sid("admin_auction_permission.php?mode=delete_from_moderator&" . POST_USERS_URL . "=" . $moderator_rowset[$i]['user_id']),
                    'USER_NAME' => $moderator_rowset[$i]['username']));
          }

     // Get auctioneers
     $sql = "SELECT u.username, u.user_id
             FROM ( " . AUCTION_USER_ROLE_TABLE . " ur LEFT JOIN " . USERS_TABLE . " u
                  ON ur.FK_user_id=u.user_id )
             WHERE ur.FK_auction_role=3
             ORDER BY u.username";

     if ( !($result = $db->sql_query($sql)) )
          {
               message_die(GENERAL_ERROR, 'Could not query auctioneer-list', '', __LINE__, __FILE__, $sql);
          }

     $total_auctioneers = 0;
     while( $row = $db->sql_fetchrow($result) )
          {
               $auctioneer_rowset[] = $row;
               $total_auctioneers++;
          }

     $db->sql_freeresult($result);

     for($i = 0; $i < $total_auctioneers; $i++)
          {
               $template->assign_block_vars('auctioneer_row', array(
                    'REGISTRATION_DATE' => create_date($board_config['default_dateformat'], $member_rowset[$i]['user_regdate'], $board_config['board_timezone']),
                    'U_USER_NAME' => append_sid("../profile.php?mode=viewprofile&" . POST_USERS_URL . "=" . $auctioneer_rowset[$i]['user_id']),
                    'U_DELETE_FROM_ROLE' => append_sid("admin_auction_permission.php?mode=delete_from_auctioneer&" . POST_USERS_URL . "=" . $auctioneer_rowset[$i]['user_id']),
                    'USER_NAME' => $auctioneer_rowset[$i]['username']));
          }

     // Get auth
     $sql = "SELECT *
             FROM " . AUCTION_ROLE_TABLE . "
             ORDER BY PK_auction_role_id";
             
     if( !($result = $db->sql_query($sql)) )
          {
               message_die(GENERAL_ERROR, 'Could not query role-information', '', __LINE__, __FILE__, $sql);
          } // if

     while( $row = $db->sql_fetchrow($result) )
          {
               $auction_role_rowset[] = $row;
          }

     $template->set_filenames(array('body' => 'admin/admin_auction_permission.tpl'));

     $template->assign_vars(array(
             'L_ADMIN_PERMISSION_EXPLAIN' => $lang['auction_admin_permission_explain'],
             'L_ADMIN_PERMISSION' => $lang['auction_admin_permission'],
             'L_AUCTION_DELETE_FROM_ROLE'=> $lang['auction_delete_from_role'],
             'L_AUCTIONEERS' => $lang['auction_auctioneers'],
             'L_MODERATORS' => $lang['auction_moderators'],
             'L_ADMINS' => $lang['auction_admins'],
             'L_AUCTION_JUST_ON' => $lang['auction_just_own'],

             'AUCTION_AUTH_GUEST_VIEW_ALL' => ( $auction_role_rowset['0']['view_all'] == 1 )? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_REGISTERED_VIEW_ALL' => ( $auction_role_rowset['1']['view_all']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_AUCTIONEER_VIEW_ALL' => ( $auction_role_rowset['2']['view_all']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_MODERATOR_VIEW_ALL' => ( $auction_role_rowset['3']['view_all']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_ADMIN_VIEW_ALL' => ( $auction_role_rowset['4']['view_all']  == 1)? "checked='on'" : "unchecked=''",

             'AUCTION_AUTH_GUEST_VIEW_OFFER' => ( $auction_role_rowset['0']['view_offer']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_REGISTERED_VIEW_OFFER' => ( $auction_role_rowset['1']['view_offer']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_AUCTIONEER_VIEW_OFFER' => ( $auction_role_rowset['2']['view_offer']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_MODERATOR_VIEW_OFFER' => ( $auction_role_rowset['3']['view_offer']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_ADMIN_VIEW_OFFER' => ( $auction_role_rowset['4']['view_offer']  == 1)? "checked='on'" : "unchecked=''",

             'AUCTION_AUTH_GUEST_VIEW_BID_HISTORY' => ( $auction_role_rowset['0']['view_bid_history']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_REGISTERED_VIEW_BID_HISTORY' => ( $auction_role_rowset['1']['view_bid_history']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_AUCTIONEER_VIEW_BID_HISTORY' => ( $auction_role_rowset['2']['view_bid_history'] == 1 )? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_MODERATOR_VIEW_BID_HISTORY' => ( $auction_role_rowset['3']['view_bid_history']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_ADMIN_VIEW_BID_HISTORY' => ( $auction_role_rowset['4']['view_bid_history']  == 1)? "checked='on'" : "unchecked=''",

             'AUCTION_AUTH_GUEST_NEW' => ( $auction_role_rowset['0']['new']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_REGISTERED_NEW' => ( $auction_role_rowset['1']['new']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_AUCTIONEER_NEW' => ( $auction_role_rowset['2']['new']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_MODERATOR_NEW' => ( $auction_role_rowset['3']['new']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_ADMIN_NEW' => ( $auction_role_rowset['4']['new']  == 1)? "checked='on'" : "unchecked=''",

             'AUCTION_AUTH_GUEST_BID' => ( $auction_role_rowset['0']['bid']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_REGISTERED_BID' => ( $auction_role_rowset['1']['bid']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_AUCTIONEER_BID' => ( $auction_role_rowset['2']['bid']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_MODERATOR_BID' => ( $auction_role_rowset['3']['bid']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_ADMIN_BID' => ( $auction_role_rowset['4']['bid']  == 1)? "checked='on'" : "unchecked=''",

             'AUCTION_AUTH_GUEST_DIRECT_SELL' => ( $auction_role_rowset['0']['direct_sell']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_REGISTERED_DIRECT_SELL' => ( $auction_role_rowset['1']['direct_sell']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_AUCTIONEER_DIRECT_SELL' => ( $auction_role_rowset['2']['direct_sell']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_MODERATOR_DIRECT_SELL' => ( $auction_role_rowset['3']['direct_sell']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_ADMIN_DIRECT_SELL' => ( $auction_role_rowset['4']['direct_sell']  == 1)? "checked='on'" : "unchecked=''",

             'AUCTION_AUTH_GUEST_IMAGE_UPLOAD' => ( $auction_role_rowset['0']['image_upload']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_REGISTERED_IMAGE_UPLOAD' => ( $auction_role_rowset['1']['image_upload']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_AUCTIONEER_IMAGE_UPLOAD' => ( $auction_role_rowset['2']['image_upload']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_MODERATOR_IMAGE_UPLOAD' => ( $auction_role_rowset['3']['image_upload']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_ADMIN_IMAGE_UPLOAD' => ( $auction_role_rowset['4']['image_upload']  == 1)? "checked='on'" : "unchecked=''",

             'AUCTION_AUTH_GUEST_COMMENT' => ( $auction_role_rowset['0']['comment']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_REGISTERED_COMMENT' => ( $auction_role_rowset['1']['comment'] == 1 )? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_AUCTIONEER_COMMENT' => ( $auction_role_rowset['2']['comment']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_MODERATOR_COMMENT' => ( $auction_role_rowset['3']['comment']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_ADMIN_COMMENT' => ( $auction_role_rowset['4']['comment']  == 1)? "checked='on'" : "unchecked=''",

             'AUCTION_AUTH_GUEST_MOVE' => ( $auction_role_rowset['0']['move']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_REGISTERED_MOVE' => ( $auction_role_rowset['1']['move']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_AUCTIONEER_MOVE' => ( $auction_role_rowset['2']['move'] == 1 )? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_MODERATOR_MOVE' => ( $auction_role_rowset['3']['move']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_ADMIN_MOVE' => ( $auction_role_rowset['4']['move']  == 1)? "checked='on'" : "unchecked=''",

             'AUCTION_AUTH_GUEST_DELETE_OFFER' => ( $auction_role_rowset['0']['delete_offer']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_REGISTERED_DELETE_OFFER' => ( $auction_role_rowset['1']['delete_offer']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_AUCTIONEER_DELETE_OFFER' => ( $auction_role_rowset['2']['delete_offer']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_MODERATOR_DELETE_OFFER' => ( $auction_role_rowset['3']['delete_offer']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_ADMIN_DELETE_OFFER' => ( $auction_role_rowset['4']['delete_offer']  == 1)? "checked='on'" : "unchecked=''",

             'AUCTION_AUTH_GUEST_DELETE_BID' => ( $auction_role_rowset['0']['delete_bid']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_REGISTERED_DELETE_BID' => ( $auction_role_rowset['1']['delete_bid']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_AUCTIONEER_DELETE_BID' => ( $auction_role_rowset['2']['delete_bid']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_MODERATOR_DELETE_BID' => ( $auction_role_rowset['3']['delete_bid']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_ADMIN_DELETE_BID' => ( $auction_role_rowset['4']['delete_bid']  == 1)? "checked='on'" : "unchecked=''",

             'AUCTION_AUTH_GUEST_SPECIAL' => ( $auction_role_rowset['0']['special']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_REGISTERED_SPECIAL' => ( $auction_role_rowset['1']['special']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_AUCTIONEER_SPECIAL' => ( $auction_role_rowset['2']['special']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_MODERATOR_SPECIAL' => ( $auction_role_rowset['3']['special']  == 1)? "checked='on'" : "unchecked=''",
             'AUCTION_AUTH_ADMIN_SPECIAL' => ( $auction_role_rowset['4']['special']  == 1)? "checked='on'" : "unchecked=''",

             'L_SUBMIT' => $lang['auction_submit'],
             'L_ADD_USER_TO_ROLE' => $lang['auction_add_user_to_role'],
             'L_ADD_AUCTIONEER' => $lang['auction_add_auctioneer'],
             'L_ADD_MODERATOR' => $lang['auction_add_moderator'],
             'L_ADD_ADMIN' => $lang['auction_add_admin'],
             'L_USER_NAME' => $lang['auction_user_name'],
             'L_USER_ID' => $lang['auction_user_id'],
              'L_ROLE' => $lang['auction_role'],
              'L_AUTH_VIEW_ALL' => $lang['auction_auth_view_all'],
             'L_AUTH_VIEW_OFFER' => $lang['auction_auth_view_offer'],
             'L_AUTH_NEW' => $lang['auction_auth_new'],
             'L_AUTH_BID' => $lang['auction_auth_bid'],
             'L_ROLE_REGISTERED' => $lang['auction_role_registered'],
             'L_ROLE_GUEST' => $lang['auction_role_guest'],
             'L_ROLE_AUCTIONEER' => $lang['auction_role_auctioneer'],
             'L_ROLE_MODERATOR' => $lang['auction_role_moderator'],
             'L_ROLE_ADMIN' => $lang['auction_role_admin'],
             'L_AUTH_DELETE_OFFER' => $lang['auction_auth_delete_offer'],
             'L_AUTH_VIEW_BID_HISTORY' => $lang['auction_auth_view_bid_history'],
             'L_AUTH_SPECIAL' => $lang['auction_auth_special'],
             'L_AUTH_MOVE' => $lang['auction_auth_move'],
             'L_AUTH_DIRECT_SELL' => $lang['auction_auth_direct_sell'],
             'L_AUTH_IMAGE_UPLOAD' => $lang['auction_auth_image_upload'],
             'L_AUTH_DELETE_BID' => $lang['auction_auth_delete_bid'],
             'L_AUTH_COMMENT' => $lang['auction_auth_comment'],
             'S_ADD_MODERATOR_ACTION' => append_sid("admin_auction_permission.php?mode=add_moderator"),
             'S_ADD_AUCTIONEER_ACTION' => append_sid("admin_auction_permission.php?mode=add_auctioneer"),
             'S_UPDATE_AUTH_ACTION' => append_sid("admin_auction_permission.php?mode=update_auth"),
             'S_ADD_ADMIN_ACTION' => append_sid("admin_auction_permission.php?mode=add_admin")));

     $template->pparse("body");

     include('./page_footer_admin.'.$phpEx);

?>