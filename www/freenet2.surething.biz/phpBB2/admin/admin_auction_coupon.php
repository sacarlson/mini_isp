<?php
/***************************************************************************
 *                          admin_auction_coupon.php
 *                            -------------------
 *   begin                : today
 *   copyright            : (C) FR
 *   email                : fr@php-styles.com
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
               $module['Auction']['a4_coupons'] = append_sid($filename);
               return;
          } // if

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

     if ( $mode == "create" )
          {
              $sql = "INSERT INTO " . AUCTION_COUPON_TABLE . "
                         (PK_auction_coupon_id,
                          FK_auction_coupon_config_id,
                          FK_auction_coupon_created_user_id,
                          auction_coupon_date_created)
                      VALUES('" . substr(md5(uniqid(rand())),0,8). "',
                              " . $HTTP_POST_VARS['coupon_id'] . " ,
                              " . $userdata['user_id'] .",
                              " . time(). ")";

              if( !$result = $db->sql_query($sql) )
                  {
                    message_die(GENERAL_ERROR, "Couldn't create coupon. Please try again.", "", __LINE__, __FILE__, $sql);
                  }
          } // if

     if ( $mode == "delete" )
          {
              $coupon_id = ( isset($HTTP_GET_VARS[POST_COUPON_URL]) ) ? $HTTP_GET_VARS[POST_COUPON_URL] : $HTTP_POST_VARS[POST_COUPON_URL];

              $sql = "DELETE FROM " . AUCTION_COUPON_TABLE . "
                      WHERE PK_auction_coupon_id='" . $coupon_id . "'";

              if( !$result = $db->sql_query($sql) )
                  {
                    message_die(GENERAL_ERROR, "Couldn't delete coupon. Please try again.", "", __LINE__, __FILE__, $sql);
                  } // if
          } // if

     if ( $mode == "send" )
          {
              // Grab user-id if name is set
              $user_id = "";

              if( $HTTP_POST_VARS['user_name']<>'' || $HTTP_GET_VARS['user_name']<>'' )
                     {
                          $user_name = ( isset($HTTP_POST_VARS['user_name']) ) ? $HTTP_POST_VARS['user_name'] : $HTTP_GET_VARS['user_name'];
                          $user_name = htmlspecialchars($user_name);

                          $sql = "SELECT user_id
                                  FROM " . USERS_TABLE . "
                                  WHERE username='" . $user_name . "'";

                          if ( !$result = $db->sql_query($sql) )
                               {
                                    message_die(GENERAL_ERROR, 'Could not get user-id', '', __LINE__, __FILE__, $sql);
                               } // if
                          $user_id_row = $db->sql_fetchrow($result);
                          $user_id = $user_id_row['user_id'];
                     }
              else
                   {
                         if( $HTTP_POST_VARS['user_id']<>'' || $HTTP_GET_VARS['user_id']<>'' )
                              {
                                   $user_id = ( isset($HTTP_POST_VARS['user_id']) ) ? $HTTP_POST_VARS['user_id'] : $HTTP_GET_VARS['user_id'];
                                   $user_id = htmlspecialchars($user_id);
                              }
                         else
                              {
                                    message_die(GENERAL_ERROR, "No user-id selected.");
                              } // if
                   }

              $coupon_id = ( isset($HTTP_GET_VARS[POST_COUPON_URL]) ) ? $HTTP_GET_VARS[POST_COUPON_URL] : $HTTP_POST_VARS[POST_COUPON_URL];
              $coupon_id = htmlspecialchars($coupon_id);

              // Check coupon-id
              $sql = "SELECT c.*,
                             cc.auction_coupon_config_name
                      FROM " . AUCTION_COUPON_TABLE . " c
                      LEFT JOIN " . AUCTION_COUPON_CONFIG_TABLE . " cc on c.FK_auction_coupon_config_id=cc.PK_auction_coupon_config_id
                      WHERE c.PK_auction_coupon_id='" . $coupon_id . "'";

              if ( !$result = $db->sql_query($sql) )
                     {
                          message_die(GENERAL_ERROR, 'Could not get coupon info', '', __LINE__, __FILE__, $sql);
                     } // if
              $coupon_row = $db->sql_fetchrow($result);

              // Check user-id
              $sql = "SELECT username
                      FROM " . USERS_TABLE . "
                      WHERE user_id='" . $user_id . "'";

              if ( !$result = $db->sql_query($sql) )
                     {
                          message_die(GENERAL_ERROR, 'Could not get user info', '', __LINE__, __FILE__, $sql);
                     } // if

              $user_row = $db->sql_fetchrow($result);
              if ( !$user_row['username'] )
                     {
                          message_die(GENERAL_ERROR, 'This user does not exist.');
                     } // if

              // Notify outbid

              $coupon_pm_subject = $lang['coupon_received'];
              $coupon_pm = $lang['coupon_received_pm'];
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
                              '" . str_replace("\'", "''", addslashes(sprintf($coupon_pm_subject,$board_config['sitename']))) . "',
                              " . $userdata['user_id'] . ",
                              " . $user_id . ",
                              " . $privmsgs_date . ",
                              '0',
                              '1',
                              '1',
                              '0')";
              if ( !$result = $db->sql_query($sql) )
                   {
                        message_die(GENERAL_ERROR, 'Could not insert private message info', '', __LINE__, __FILE__, $sql);
                   }  // if

              $coupon_sent_id = $db->sql_nextid();
              $coupon_text = $lang['coupon_pm_text'];

              $sql = "INSERT INTO " . PRIVMSGS_TEXT_TABLE . "
                           (privmsgs_text_id,
                            privmsgs_text)
                      VALUES ($coupon_sent_id,
                              '" . str_replace("\'", "''", $coupon_pm . "</br>" . $coupon_row['auction_coupon_config_name'] . "</br>ID: " .  $coupon_row['PK_auction_coupon_id'] . "</br></br>" . $board_config['board_email_sig']) . "')";

              if ( !$result = $db->sql_query($sql) )
               {
                   message_die(GENERAL_ERROR, 'Could not insert private message text', '', __LINE__, __FILE__, $sql);
               } // if

              $sql = "UPDATE " . USERS_TABLE . "
                      Set user_new_privmsg=user_new_privmsg+1
                      WHERE user_id=" . $user_id ;

              if ( !$db->sql_query($sql) )
               {
                   message_die(GENERAL_ERROR, 'Could not update user table for outbid notification', '', __LINE__, __FILE__, $sql);
               } // if
         } // if


     // drop for create coupons
     $sql = "SELECT PK_auction_coupon_config_id,
                    auction_coupon_config_name
             FROM " . AUCTION_COUPON_CONFIG_TABLE;

     if( !$result = $db->sql_query($sql) )
            {
                    message_die(GENERAL_ERROR, "Couldn't get coupon-config", "", __LINE__, __FILE__, $sql);
            } // if

     $coupon_list_dd = "";
     while( $row = $db->sql_fetchrow($result) )
         {
             $coupon_list_dd .= "<option value=\"" . $row['PK_auction_coupon_config_id'] . "\" " . $select . ">" . $row['auction_coupon_config_name'] . "</option>";
         } // while


     // Get all created coupons
     $sql = "SELECT c.*,
                    u.username as coupon_creator,
                    u2.username as coupon_user,
                    cc.auction_coupon_config_name
            FROM (((" . AUCTION_COUPON_TABLE . " c
            LEFT JOIN " . USERS_TABLE . " u on c.FK_auction_coupon_created_user_id=u.user_id )
            LEFT JOIN " . USERS_TABLE . " u2 on c.FK_auction_coupon_used_user_id=u2.user_id )
            LEFT JOIN " . AUCTION_COUPON_CONFIG_TABLE . " cc on c.FK_auction_coupon_config_id=cc.PK_auction_coupon_config_id)
            ORDER BY c.auction_coupon_date_created";

     $result = $db->sql_query( $sql );

     if ( !$result )
        {
            message_die(GENERAL_ERROR, "Could not query coupons.", "",__LINE__, __FILE__, $sql);
        } // if

     $total_coupons = 0;
     while( $row = $db->sql_fetchrow($result) )
        {
            $coupon_rowset[] = $row;
            $total_coupons++;
        } // while

     $db->sql_freeresult($result);


     // Display page
     $template->set_filenames(array('body' => 'admin/admin_auction_coupon.tpl'));

     if ( $total_coupons < 1 )
         {
                 $template->assign_block_vars('no_coupon', array(
                     'L_NO_COUPON' => $lang['coupon_no']));
         }
     else
         {
             for($i = 0; $i < $total_coupons; $i++)
             {
                 $template->assign_block_vars('coupon', array(
                     'COUPON_ID' => $coupon_rowset[$i]['PK_auction_coupon_id'],
                     'COUPON_NAME' => $coupon_rowset[$i]['auction_coupon_config_name'],
                     'COUPON_DATE_CREATED' => create_date("m/d/Y - h:i:s", $coupon_rowset[$i]['auction_coupon_date_created'], $board_config['board_timezone']),
                     'COUPON_USER_CREATED' => $coupon_rowset[$i]['coupon_creator'],
                     'COUPON_DATE_USED' => ( $coupon_rowset[$i]['auction_coupon_date_used']>0 ) ? create_date("m/d/Y - h:i:s", $coupon_rowset[$i]['auction_coupon_date_used'], $board_config['board_timezone']) : $lang['coupon_not_used'],
                     'COUPON_USER_USED' => ( $coupon_rowset[$i]['coupon_user']<>"" ) ? $coupon_rowset[$i]['coupon_user'] : $lang['coupon_not_used'],

                     'U_COUPON_SEND' => append_sid("admin_auction_coupon.$phpEx?mode=send&" . POST_COUPON_URL . "=" . $coupon_rowset[$i]['PK_auction_coupon_id'] . ""),
                     'U_COUPON_DELETE' => append_sid("admin_auction_coupon.$phpEx?" . POST_COUPON_URL . "=" . $coupon_rowset[$i]['PK_auction_coupon_id'] . "&mode=delete")));
             } // for
          } // if

     $template->assign_vars(array(
            'L_ADMIN_COUPON' => $lang['coupon_admin'],
            'L_ADMIN_COUPON_EXPLAIN' => $lang['coupon_admin_explain'],
            'L_COUPON_ID' => $lang['coupon_id'],
            'L_COUPON_NAME' => $lang['coupon_name'],
            'L_COUPON_DELETE' => $lang['coupon_delete'],
            'L_COUPON_DATE_CREATED' => $lang['coupon_date_created'],
            'L_COUPON_USER_CREATED' => $lang['coupon_user_created'],
            'L_COUPON_DATE_USED' => $lang['coupon_date_used'],
            'L_COUPNG_USER_USED'=> $lang['coupon_user_used'],
            'L_COUPON_CREATE' => $lang['coupon_create'],
            'L_COUPON_SEND' => $lang['coupon_send'],
            'L_COUPON_USER_ID' => $lang['user_id'],
            'L_CHOOSE_COUPON_TYPE' => $lang['coupon_choose_type'],
            'L_COUPON_USER_NAME' => $lang['auction_user_name'],

            'S_AUCTION_COUPON_ACTION' => append_sid("admin_auction_coupon.$phpEx?mode=create"),

            'COUPON_LIST_DD' => $coupon_list_dd));


$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>