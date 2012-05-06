<?php
/***************************************************************************
 *                            auction_offer.php
 *                            -------------------
 *   begin                :   January 2004
 *   copyright            :   (C) FR
 *   email                :   fr@php-styles.com
 *   Last update          :   DEC 2004 - FR
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

     // BEGIN session management
     $userdata = session_pagestart($user_ip, AUCTION_OFFER);
     init_userprefs($userdata);
     // END session management

     // Check auction_permission
     checkPermission('VIEW_ALL');
     
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
             case 'settle_fees' :
             
                 $sql = "SELECT *
                         FROM " . AUCTION_ACCOUNT_TABLE . "
                         WHERE fk_auction_account_creditor_id=" . $userdata['user_id'] . " AND
                               fk_auction_account_debitor_id =1 AND
                               auction_account_action='" . ACTION_CREDIT . "'";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not grab account board-credit information', '', __LINE__, __FILE__, $sql);
                     } // if

                 $board_credit_rowset = $db->sql_fetchrow($result);
                 $board_credit_amount = $board_credit_rowset['auction_account_auction_amount'];
                 $board_credit_amount_unused = $board_credit_rowset['auction_account_auction_amount']-$board_credit_rowset[$i]['auction_account_amount_paid'];
                 $board_credit_id = $board_credit_rowset['pk_auction_account_id'];

                 settle_credit($userdata['user_id'], $board_credit_amount_unused, $board_credit_id);

                 $message = $lang['auction_credit_settled'] . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_auction_room'], "<a href=\"" . append_sid("auction_room.$phpEx?ar=" . $HTTP_POST_VARS['auction_room_id']) . "\">", "</a>");
                 message_die(GENERAL_MESSAGE, $message);
                 break;

             case 'debit' :
                 $offer_id = ( isset($HTTP_POST_VARS[POST_AUCTION_OFFER_URL]) ) ? $HTTP_POST_VARS[POST_AUCTION_OFFER_URL] : $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];
                 $offer_id = htmlspecialchars($offer_id);

                 // Charge
                 $sql = "INSERT INTO " . AUCTION_ACCOUNT_TABLE . "
                         (fk_auction_account_creditor_id,
                          fk_auction_account_debitor_id,
                          auction_account_auction_amount,
                          auction_account_amount_date,
                          fk_auction_offer_id,
                          auction_account_action)
                         VALUES
                          (" . 2 . ",
                           " . $userdata['user_id']. ",
                           " . $HTTP_POST_VARS['auction_offer_amount'] . ",
                           " . time() . ",
                           " . $offer_id . ",
                           '" . ACTION_INITIAL . "')";

                 if( !($result = $db->sql_query($sql)) )
                     {
                           message_die(GENERAL_ERROR, "Couldn't debit offer", "", __LINE__, __FILE__, $sql);
                     }

                 // mark paid
                 $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                         SET auction_offer_paid  =  1
                         WHERE PK_auction_offer_id = " . $offer_id . "";

                 if( !($result = $db->sql_query($sql)) )
                     {
                           message_die(GENERAL_ERROR, "Couldn't mark offer paid", "", __LINE__, __FILE__, $sql);
                     }

                 $message = $lang['auction_offer_saved'] . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_auction_room'], "<a href=\"" . append_sid("auction_room.$phpEx?ar=" . $HTTP_POST_VARS['auction_room_id']) . "\">", "</a>");
                 message_die(GENERAL_MESSAGE, $message);
                 break;

             case 'add':

                 // Check auction_permission
                 checkPermission('NEW');

                 $room_id = ( $HTTP_GET_VARS[POST_AUCTION_ROOM_URL] );
                 $room_id = htmlspecialchars($room_id);
                    
                 // check if user is logged in
                 if ($userdata['user_id']<0)
                    {
                           redirect("login.".$phpEx."?redirect=auction_offer.".$phpEx."?mode=add&" . POST_AUCTION_ROOM_URL . "=" . $room_id);
                           exit;
                    }

                // New offers are just allowed if auction-room-stat is not locked
//                $sql = "SELECT auction_room_state
//                       FROM " . AUCTION_ROOM_TABLE . "
//                       WHERE PK_auction_room_id=" . $HTTP_GET_VARS[POST_AUCTION_ROOM_URL] . "";

//                if( !($result = $db->sql_query($sql)) )
//                     {
//                          message_die(GENERAL_ERROR, 'Could not query auction-room state-information', '', __LINE__, __FILE__, $sql);
//                     } // if

//                $auction_room_state_row = $db->sql_fetchrow($result);

//                if ($auction_room_state_row['auction_room_state']==AUCTION_ROOM_LOCKED)
//                     {
//                          message_die(GENERAL_MESSAGE,  $lang['auction_room_locked']);
//                     }

                // drop down for auction-rooms
                 $page_title = $lang['auction_new_offer'];
                 include('./includes/page_header.php');
                 include($phpbb_root_path . 'auction/auction_header.'.$phpEx);

                $sql = "SELECT PK_auction_room_id,
                               auction_room_title
                        FROM " . AUCTION_ROOM_TABLE . "
                        WHERE auction_room_state=" . AUCTION_ROOM_UNLOCKED . " ";

                if( !$result = $db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, "Couldn't get list of Auction-Rooms/Categories", "", __LINE__, __FILE__, $sql);
                } // if

               $auction_room_list_dd = "";

                while( $row = $db->sql_fetchrow($result) )
                {
                    $select = "";
                    if ($row['PK_auction_room_id'] == $room_id)
                    {
                        $select = " selected=\"selected\"";
                         } // if
                    $auction_room_list_dd .= "<option value=\"" . $row['PK_auction_room_id'] . "\" " . $select . ">" . $row['auction_room_title'] . "</option>";
                } // while

                // dropdown-menu for days
                $dayToEnd_dd = "<option value=0>" . " - " . "</option>
                                <option value=1>" . $lang['auction_1_day'] . "</option>
                                <option value=2>" . $lang['auction_2_day'] . "</option>
                                <option value=3>" . $lang['auction_3_day'] . "</option>
                                <option value=4>" . $lang['auction_4_day'] . "</option>
                                <option value=5>" . $lang['auction_5_day'] . "</option>
                                <option value=6>" . $lang['auction_6_day'] . "</option>
                                <option value=7>" . $lang['auction_7_day'] . "</option>
                                <option value=14>" . $lang['auction_14_day'] . "</option>
                                <option value=31>" . $lang['auction_31_day'] . "</option>
                                <option value=62>" . $lang['auction_62_day'] . "</option>";


                if ( $auction_config_data['auction_offer_allow_bold'] == 1)
                     {
                          $template->assign_block_vars('offer_bold', array(
                                                  'L_AUCTION_OFFER_BOLD' => $lang['auction_offer_bold'] . " ( +" . $auction_config_data['auction_offer_cost_bold'] . " " . $auction_config_data['currency'] . ")"));
                     }
                 if ( $auction_config_data['auction_offer_allow_on_top'] == 1)
                     {
                      $template->assign_block_vars('offer_on_top', array(
                                                  'L_AUCTION_OFFER_ON_TOP' => $lang['auction_offer_on_top'] . " ( +" . $auction_config_data['auction_offer_cost_on_top'] . " " . $auction_config_data['currency'] . ")"));
                     }
                 if ( $auction_config_data['auction_offer_allow_special'] == 1 AND checkBoolPermission('SPECIAL') )
                     {
                         $template->assign_block_vars('offer_special', array(
                                                  'L_AUCTION_OFFER_SPECIAL' => $lang['auction_offer_special'] . " ( +" . $auction_config_data['auction_offer_cost_special'] . " " . $auction_config_data['currency'] . ")"));
                     }
                 if ( $auction_config_data['auction_offer_allow_shipping'] == 1)
                     {
                         $template->assign_block_vars('offer_shipping', array(
                                            'L_AUCTION_OFFER_SHIPPING_PRICE' => $lang['auction_offer_shipping_price'] . "( " . $auction_config_data['currency'] . " )"));
                     }
                 if ( $auction_config_data['auction_allow_direct_sell'] == 1 AND checkBoolPermission('DIRECT_SELL') )
                     {
                      $template->assign_block_vars('direct_sell', array(
                                                  'L_AUCTION_OFFER_DIRECT_SELL' => $lang['auction_offer_direct_sell'] . " ( +" . $auction_config_data['auction_offer_cost_direct_sell'] . " " . $auction_config_data['currency'] . ")"));
                     }

				// we need the simple config values only 2 of them so we get them the direct way
				$allow_upload = get_config_parameter("auction_offer_pictures_allow");
				$allow_url_upload = get_config_parameter("allow_url_upload");

				 if ( $allow_upload == 1 AND checkBoolPermission('IMAGE_UPLOAD'))
                                     {
					$template->assign_block_vars('offer_picture', array(
						'L_AUCTION_OFFER_PICTURE' => $lang['upload_pc'],
						'L_FILE' => $lang['upload_file'] ));

					if($allow_url_upload == 1)
					{
						$template->assign_block_vars('url_upload', array(
							'L_AUCTION_OFFER_URL_PICTURE' => $lang['auction_offer_upload_net'],
							'L_URL' => $lang['upload_url'] ));

					}

				 }

                 // Allow coupons
                 if ( $auction_config_data['auction_allow_coupons'] == 1)
                     {
                         $template->assign_block_vars('offer_coupon', array(
                                            'L_AUCTION_OFFER_COUPON_EXPLAIN' => $lang['coupon_use_explain'],
                                            'L_AUCTION_OFFER_COUPON' => $lang['coupon_use']));
                     }
                 
                 $sql = "SELECT *
                         FROM " . AUCTION_BID_INCREASE_TABLE . "
                         ORDER BY bid_increase ASC";

                 if ( !($result = $db->sql_query($sql)) )
                      {
                           message_die(GENERAL_ERROR, "Couldn't query list of bid-increasements", "", __LINE__, __FILE__, $sql);
                      }

                 $bid_increasement_select ='<select name="auction_offer_bid_increase">';
                 while ( $row = $db->sql_fetchrow($result) )
                      {
                           $bid_increasement_select .= '<option value="' . $row['bid_increase'] . '"' . $selected . '>' . $row['bid_increase'] . '</option>';
                      }
                 $bid_increasement_select .= '</select>';

                 $template->set_filenames(array('body' => 'auction_add_offer.tpl'));

                 $template->assign_vars(array(
                       'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
                       'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
                       'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
                       'L_ONLINE_EXPLAIN' => $lang['Online_explain'],

	               'L_BBCODE_B_HELP' => $lang['bbcode_b_help'],
	               'L_BBCODE_I_HELP' => $lang['bbcode_i_help'],
	               'L_BBCODE_U_HELP' => $lang['bbcode_u_help'],
	               'L_BBCODE_Q_HELP' => $lang['bbcode_q_help'],
	               'L_BBCODE_C_HELP' => $lang['bbcode_c_help'],
	               'L_BBCODE_L_HELP' => $lang['bbcode_l_help'],
	               'L_BBCODE_O_HELP' => $lang['bbcode_o_help'],
	               'L_BBCODE_P_HELP' => $lang['bbcode_p_help'],
                       'L_BBCODE_W_HELP' => $lang['bbcode_w_help'],
	               'L_BBCODE_A_HELP' => $lang['bbcode_a_help'],
	               'L_BBCODE_S_HELP' => $lang['bbcode_s_help'],
	               'L_BBCODE_F_HELP' => $lang['bbcode_f_help'],
	               'L_EMPTY_MESSAGE' => $lang['Empty_message'],

	               'L_FONT_COLOR' => $lang['Font_color'],
                       'L_COLOR_DEFAULT' => $lang['color_default'],
	               'L_COLOR_DARK_RED' => $lang['color_dark_red'],
	               'L_COLOR_RED' => $lang['color_red'],
	               'L_COLOR_ORANGE' => $lang['color_orange'],
	               'L_COLOR_BROWN' => $lang['color_brown'],
	               'L_COLOR_YELLOW' => $lang['color_yellow'],
	               'L_COLOR_GREEN' => $lang['color_green'],
	               'L_COLOR_OLIVE' => $lang['color_olive'],
	               'L_COLOR_CYAN' => $lang['color_cyan'],
	               'L_COLOR_BLUE' => $lang['color_blue'],
	               'L_COLOR_DARK_BLUE' => $lang['color_dark_blue'],
	               'L_COLOR_INDIGO' => $lang['color_indigo'],
	               'L_COLOR_VIOLET' => $lang['color_violet'],
	               'L_COLOR_WHITE' => $lang['color_white'],
	               'L_COLOR_BLACK' => $lang['color_black'],

	               'L_FONT_SIZE' => $lang['Font_size'],
	               'L_FONT_TINY' => $lang['font_tiny'],
	               'L_FONT_SMALL' => $lang['font_small'],
	               'L_FONT_NORMAL' => $lang['font_normal'],
	               'L_FONT_LARGE' => $lang['font_large'],
	               'L_FONT_HUGE' => $lang['font_huge'],

	               'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'],
                       'L_AUCTION_OFFER_BID_INCREASE' => $lang['auction_offer_bid_increase'],
                       'DD_AUCTION_OFFER_BID_INCREASE'=> $bid_increasement_select,

                       'NAVIGATION_STRING' => $navigation_string,
                       'AUCTION_NEW_OFFER' => $lang['auction_new_offer'],
                       'L_AUCTION_OFFER_OFFERER'=> $lang['auction_offer_offerer'],
                       'L_AUCTION_OFFER_TITLE' => $lang['auction_offer_title'],
                       'L_AUCTION_OFFER_TITLE_EXPLAIN' => $lang['auction_offer_title_explain'],
                       'L_AUCTION_OFFER_TEXT'=> $lang['auction_offer_text'],
                       'L_AUCTION_OFFER_TEXT_EXPLAIN'=> $lang['auction_offer_text_explain'],
                       'L_AUCTION_OFFER_TIME_START' => $lang['auction_offer_time_start'],
                       'L_AUCTION_OFFER_TIME_STOP' => $lang['auction_offer_time_stop'],
                       'L_AUCTION_OFFER_PRICE_START' => $lang['auction_offer_price_start'] . "( " . $auction_config_data['currency'] . " )",
                       'L_AUCTION_NEW_OFFER' => $lang['auction_new_offer'],
                       'L_AUCTION_ROOM_TITLE' => $lang['auction_room_title'],
                       'L_AUCTION_OR_DATE' => $lang['auction_or_date'],
                       'L_AUCTION_NOW' => $lang['auction_now'],
                       'L_AUCTION_OFFER_SELLERS_LOCATION' => $lang['auction_offer_sellers_location'],
                       'L_AUCTION_OFFER_ACCEPTED_PAYMENTS' => $lang['auction_offer_accepted_payments'],
                       'AUCTION_ROOM_TITLE' => $auction_room_title,
                       'AUCTION_TIME_TO_END_DD' => $dayToEnd_dd,
                       'AUCTION_ROOM_LIST_DD' => $auction_room_list_dd,
                       'AUCTION_OFFER_OFFERER' => $userdata['username'],
                       'S_AUCTION_ADD_OFFER_ACTION' => append_sid("auction_offer.$phpEx?mode=create&" . POST_AUCTION_ROOM_URL . "=" . $room_id)));

                  // if we are relisting
                  if ( $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] <> "")
                       {
                            $auction_offer_id = $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];
                            $sql = "SELECT o.*, i.pic_filename
                                    FROM ( " . AUCTION_OFFER_TABLE . " o
                                    LEFT JOIN " . AUCTION_IMAGE_TABLE . " i on o.pk_auction_offer_id=i.pic_auction_id )
                                    WHERE PK_auction_offer_id = " . $auction_offer_id;

                            if( !($result = $db->sql_query($sql)) )
                                 {
                                      message_die(GENERAL_ERROR, 'Could not query offer', '', __LINE__, __FILE__, $sql);
                                 } // End if
                            $auction_offer_row = $db->sql_fetchrow($result);

                            // bid-increase
                            //picutres
                            $template->assign_vars(array(
                                  'AUCTION_OFFER_TITLE' => $auction_offer_row['auction_offer_title'],
                                  'AUCTION_OFFER_TEXT' => $auction_offer_row['auction_offer_text'],
                                  'AUCTION_OFFER_PRICE_START' => $auction_offer_row['auction_offer_price_start'],
                                  'AUCTION_OFFER_SHIPPING_PRICE' => ( $auction_offer_row['auction_offer_shipping_price']="0.00") ? "" : $auction_offer_row['auction_offer_shipping_price'],
                                  'AUCTION_OFFER_ACCEPTED_PAYMENTS' => $auction_offer_row['auction_offer_accepted_payments'],
                                  'AUCTION_OFFER_SELLERS_LOCATION' => $auction_offer_row['auction_offer_sellers_location'],
                                  'AUCTION_OFFER_PICTURE' => ( $auction_offer_row['pic_filename']) ? "http://" . $_SERVER['HTTP_HOST'] . $board_config['script_path'] . "auction/upload/" . $auction_offer_row['pic_filename'] : "",
                                  'AUCTION_OFFER_DIRECT_SELL_PRICE' => ( $auction_offer_row['auction_offer_direct_sell_price']='0.00' ) ? "" : $auction_offer_row['auction_offer_direct_sell_price'],
                                  'AUCTION_OFFER_BOLD_CHECKED' => ( $auction_offer_row['auction_offer_bold']) ? "checked=\"checked\"" : "",
                                  'AUCTION_OFFER_ON_TOP_CHECKED' => ( $auction_offer_row['auction_offer_bold']) ? "checked=\"checked\"" : "",
                                  'AUCTION_OFFER_SPECIAL_CHECKED' => ( $auction_offer_row['auction_offer_bold']) ? "checked=\"checked\"" : "" ));
                       }

                  $template->pparse('body');
                  include($phpbb_root_path . 'auction/auction_footer.'.$phpEx);
                  include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

                  break; // add

             case 'create':

                 $page_title = $lang['auction_new_offer'];
                 include('./includes/page_header.php');

                 // Check auction_permission
                 checkPermission('NEW');

                 $auction_offer_price = 0;

                 if ( empty($HTTP_POST_VARS['auction_offer_title']))
                      {
                           message_die(GENERAL_MESSAGE, $lang['auction_no_title']);
                      }

                 if ($HTTP_POST_VARS['auction_offer_price_start']>$auction_config_data['auction_offer_amount_max'])
                      {
                           message_die(GENERAL_MESSAGE, $lang['auction_offer_amount_to_high']);
                      }

                 if ($HTTP_POST_VARS['auction_offer_price_start']=="")
                      {
                           $auction_offer_initial_price = $auction_config_data['auction_offer_amount_min'];
                      }
                 else
                      {
                           $auction_offer_initial_price = $HTTP_POST_VARS['auction_offer_price_start'];
                      }

                 if ($HTTP_POST_VARS['auction_offer_price_start']<0)
                      {
                           message_die(GENERAL_MESSAGE, $lang['auction_offer_amount_not_negative']);
                      }

                 if (doubleval($HTTP_POST_VARS['auction_offer_shipping_price'])<0)
                      {
                           message_die(GENERAL_MESSAGE, $lang['auction_offer_amount_not_negative']);
                      }
                    
                 if (empty($HTTP_POST_VARS['offer_special']) )
                      {
                           $auction_offer_special = 0;
                      }
                 else
                      {
                           $auction_offer_special = 1;
                           $auction_offer_price += doubleval($auction_config_data['auction_offer_cost_special']);
                      }
                 if (empty($HTTP_POST_VARS['offer_on_top']) )
                      {
                           $auction_offer_on_top = 0;
                      }
                 else
                      {
                           $auction_offer_on_top = 1;
                           $auction_offer_price += doubleval($auction_config_data['auction_offer_cost_on_top']);
                      }
                 if (empty($HTTP_POST_VARS['offer_bold']) )
                      {
                           $auction_offer_bold = 0;
                      }
                 else
                      {
                           $auction_offer_bold = 1;
                           $auction_offer_price += doubleval($auction_config_data['auction_offer_cost_bold']);
                      }

                 if ($HTTP_POST_VARS['auction_offer_direct_sell_price'] <> "")
                      {
                           if ( $HTTP_POST_VARS['auction_offer_direct_sell_price'] <= $HTTP_POST_VARS['auction_offer_price_start'] )
                                {
                                     message_die(GENERAL_MESSAGE, $lang['auction_offer_direct_sell_lower_than_inital']);
                                }
                           if ($HTTP_POST_VARS['auction_offer_direct_sell_price'] < 0)
                                {
                                     message_die(GENERAL_MESSAGE, $lang['auction_offer_direct_sell_amount_not_negative']);
                                }
                           else
                                {
                                     $auction_offer_price += doubleval($auction_config_data['auction_offer_cost_direct_sell']);
                                }

                      }

                 $auction_offer_price  += doubleval($auction_config_data['auction_offer_cost_basic']);

                 // BEGIN DATE-Handling
                 if (empty($HTTP_POST_VARS['time_start_now']) )
                      {
                           if  ( $HTTP_POST_VARS['time_to_end_dd'] == 0 )
                                {
                                      checkAuctionDates($HTTP_POST_VARS['auction_offer_time_start_m'], $HTTP_POST_VARS['auction_offer_time_start_d'], $HTTP_POST_VARS['auction_offer_time_start_y'], $HTTP_POST_VARS['auction_offer_time_stop_m'], $HTTP_POST_VARS['auction_offer_time_stop_d'], $HTTP_POST_VARS['auction_offer_time_stop_y']);
                                      // Day +1 ???????? Dont know why so far - maybe the timezone
                                      $auction_offer_time_stop = mktime(0,0,0,$HTTP_POST_VARS['auction_offer_time_stop_m'],$HTTP_POST_VARS['auction_offer_time_stop_d']+1,$HTTP_POST_VARS['auction_offer_time_stop_y']);
                                      $auction_offer_time_start = mktime(0,0,0,$HTTP_POST_VARS['auction_offer_time_start_m'],$HTTP_POST_VARS['auction_offer_time_start_d']+1,$HTTP_POST_VARS['auction_offer_time_start_y']);
                                }
                           else
                                {
                                      checkAuctionDatesStart($HTTP_POST_VARS['auction_offer_time_start_m'], $HTTP_POST_VARS['auction_offer_time_start_d'], $HTTP_POST_VARS['auction_offer_time_start_y']);
                                      $auction_offer_time_start = mktime(0,0,0,$HTTP_POST_VARS['auction_offer_time_start_m'],$HTTP_POST_VARS['auction_offer_time_start_d']+1,$HTTP_POST_VARS['auction_offer_time_start_y']);
                                      $auction_offer_time_stop = DateAdd('d',$HTTP_POST_VARS['time_to_end_dd'],$auction_offer_time_start);
                                }
                      }
                 else
                      {
                           if  ( $HTTP_POST_VARS['time_to_end_dd'] == 0 )
                                {
                                      checkAuctionDatesStop($HTTP_POST_VARS['auction_offer_time_stop_m'], $HTTP_POST_VARS['auction_offer_time_stop_d'], $HTTP_POST_VARS['auction_offer_time_stop_y']);
                                      $auction_offer_time_start = time();
                                      $auction_offer_time_stop =  mktime(0,0,0,$HTTP_POST_VARS['auction_offer_time_stop_m'],$HTTP_POST_VARS['auction_offer_time_stop_d']+1,$HTTP_POST_VARS['auction_offer_time_stop_y']);
                                }
                           else
                                {
                                     $auction_offer_time_start = time();
                                     $auction_offer_time_stop = DateAdd('d',$HTTP_POST_VARS['time_to_end_dd'],time());
                                }
                      }
                 // END DATE-Handling


                 $pic_upload = 0;
                 // get the config data
                if ( $HTTP_POST_FILES['auction_offer_picture_file']['size'] > 0 )
				{

					// If the include does not exist we exit
					if(!file_exists($phpbb_root_path . 'auction/graphic_files/auction_pic_upload.' . $phpEx))
					{
						message_die(GENERAL_ERROR, $lang['auction_pic_upload_missing']);
					}
					else
					{
						// we fetch the image parameters
						$auction_config_pic = init_auction_config_pic();

						// we include the upload file
						// all the upload work is done there
						include($phpbb_root_path . 'auction/graphic_files/auction_pic_upload.' . $phpEx);
					}
				}

				elseif(($HTTP_POST_VARS['auction_offer_url_file'] != "") AND ($HTTP_POST_VARS['auction_offer_url_file'] != "http://"))
				{
					$upload_mode = 2;
					$avatar_filename = $HTTP_POST_VARS['auction_offer_url_file'];
					$error = false;
					// If the include does not exist we exit
					if(!file_exists($phpbb_root_path . 'auction/graphic_files/auction_pic_upload.' . $phpEx))
					{
						message_die(GENERAL_ERROR, $lang['auction_pic_upload_missing']);
					}
					else
					{
						// we fetch the image parameters
						$auction_config_pic = init_auction_config_pic();
						// we include the upload file. all the upload work is done there
						include($phpbb_root_path . 'auction/graphic_files/auction_pic_upload.' . $phpEx);
					}

				}


				/* TODO: Check pic approval not 100% sure if it works like i want:
				If uploader is admin he doesn't need approval.
				If uploader is mod and mod can approve pictures he doesn't need approval
				If uploader is mod and mods cannot approve pictures he needs approval by admin
				Normal users also need approval
				if approval is off then nobody needs approval
				*/

				if($auction_config_pic['auction_offer_pic_approval_admin'] == 1)
				{
					if($userdata['user_level'] == ADMIN)
					{
						$pic_approval = 0;
					}
					else if(($userdata['user_level'] == MOD) AND ($auction_config_pic['auction_offer_pic_approval_mod'] == 1))
					{
						$pic_approval = 0;
					}
					else if(($userdata['user_level'] == MOD) AND ($auction_config_pic['auction_offer_pic_approval_mod'] == 0))
					{
						$pic_approval = 1;
					}
					else /* here we could add usergroups with another else if. Like trusted users....*/
					{
						$pic_approval = 1;
					}
				}
				else
				{
					$pic_approval = 0;
				}

				$pic_main = 1; // this tells the db that this is the main picture we are uploading..

                  // if payment-system is activated then we first need to mark the offer unpaid
                  if ( ($auction_config_data['auction_paymentsystem_activate_paypal'] OR $auction_config_data['auction_paymentsystem_activate_moneybooker']) AND $auction_offer_price != 0)
                      {
                           $offer_paid = 0;
                      }
                 else
                      {
                           $offer_paid = 1;
                      }

                 // COUPON-HANDLING
                 if (!empty($HTTP_POST_VARS['auction_offer_coupon']) )
                        {
                             $sql = "SELECT  cc.auction_coupon_config_amount, c.auction_coupon_date_used
                                     FROM (" . AUCTION_COUPON_TABLE . " c
                                     LEFT JOIN " . AUCTION_COUPON_CONFIG_TABLE . " cc on c.FK_auction_coupon_config_id =cc.PK_auction_coupon_config_id)
                                     WHERE c.PK_auction_coupon_id='" . $HTTP_POST_VARS['auction_offer_coupon']. "'
                                       AND c.auction_coupon_date_used=0";

                             if( !$result = $db->sql_query($sql) )
                               {
                                 message_die(GENERAL_ERROR, "Couldn't check coupon.", "", __LINE__, __FILE__, $sql);
                               }

                             $row = $db->sql_fetchrow($result);

                             if ( $row['auction_coupon_date_used'] == "")
                                  {
                                     $coupon_valid_flag = 0; // Coupon not valid
                                  }
                             else
                                  {
                                     $coupon_valid_flag = 1; // Coupon valid
                                     $coupon_amount = doubleval($row['auction_coupon_config_amount']);

                                     // Lock this coupon
                                     $sql = "UPDATE " . AUCTION_COUPON_TABLE . "
                                             SET FK_auction_coupon_used_user_id=" . $userdata['user_id'] . ",
                                                 auction_coupon_date_used= " . time() . "
                                             WHERE PK_auction_coupon_id='" . $HTTP_POST_VARS['auction_offer_coupon']. "'";

                                     if( !$result = $db->sql_query($sql) )
                                       {
                                            message_die(GENERAL_ERROR, "Couldn't lock coupon.", "", __LINE__, __FILE__, $sql);
                                       }
                                  }

                             // if it's a 100% bonus-coupon the offer is paid
                             if ( ($row['auction_coupon_config_amount'] == 1) OR ($row['auction_coupon_config_amount'] == 1.00) )
                                  {
                                       $offer_paid = 1;
                                  }
                        }

                if ( $coupon_valid_flag == 1 )
                                  {
                                       $auction_offer_price = doubleval($auction_offer_price*$coupon_amount);
                                  } // if

                if ( $auction_config_data['auction_paymentsystem_activate_user_points'] == 1 )
                     {
                             $sql = "SELECT user_points
                                     FROM " . USERS_TABLE. "
                                     WHERE user_id=" . $userdata['user_id'] . "";

                             if( !$result = $db->sql_query($sql) )
                               {
                                 message_die(GENERAL_ERROR, "Couldn't get user\'s points.", "", __LINE__, __FILE__, $sql);
                               }

                             $row = $db->sql_fetchrow($result);

                             if ( $row['user_points'] < $auction_offer_price )
                                  {
                                       message_die(GENERAL_MESSAGE, sprintf($lang['auction_not_enough_user_points'], $board_config['points_name']));
                                  }

                             // Charge offer
                             $sql = "UPDATE " . USERS_TABLE. " Set user_points=user_points-" . round($auction_offer_price,0) . "
                                     WHERE user_id=" . $userdata['user_id'] . "";

                             if( !$result = $db->sql_query($sql) )
                               {
                                 message_die(GENERAL_ERROR, "Couldn't charge points.", "", __LINE__, __FILE__, $sql);
                               }

                     }

                 $auction_room_id = htmlspecialchars($HTTP_POST_VARS['auction_room_id']);

                 // INSERT OFFER
                 $sql = "INSERT INTO
                        " . AUCTION_OFFER_TABLE . " (FK_auction_offer_room_id,
                                                     FK_auction_offer_user_id,
                                                     auction_offer_title,
                                                     auction_offer_text,
                                                     auction_offer_time_start,
                                                     auction_offer_time_stop,
                                                     auction_offer_price_start,
                                                     auction_offer_special,
                                                     auction_offer_on_top,
                                                     auction_offer_bold,
                                                     auction_offer_shipping_price,
                                                     auction_offer_picture,
                                                     auction_offer_paid,
                                                     auction_offer_direct_sell_price,
                                                     auction_offer_sellers_location,
                                                     auction_offer_accepted_payments,
                                                     auction_offer_bid_increase )
                        VALUES (" . $auction_room_id . ",
                                " . $userdata['user_id'] . ",
                                '" . prepare_message(addslashes(unprepare_message(htmlspecialchars(trim(stripslashes($HTTP_POST_VARS['auction_offer_title']))))), $board_config['allow_html'], $board_config['allow_bbcode'], $board_config['allow_smilies'], 0) . "',
                                '" . prepare_message(addslashes(unprepare_message(htmlspecialchars(trim(stripslashes($HTTP_POST_VARS['auction_offer_text']))))), $board_config['allow_html'], $board_config['allow_bbcode'], $board_config['allow_smilies'], 0) . "',
                                " . $auction_offer_time_start . ",
                                " . $auction_offer_time_stop . ",
                                " . doubleval($auction_offer_initial_price) . ",
                                " . $auction_offer_special. ",
                                " . $auction_offer_on_top . ",
                                " . $auction_offer_bold . ",
                                " . doubleval($HTTP_POST_VARS['auction_offer_shipping_price']) . ",
                                '" . $filename_adj . "',
                                " . $offer_paid . ",
                                " . doubleval($HTTP_POST_VARS['auction_offer_direct_sell_price']) . " ,
                                '" . prepare_message(addslashes(unprepare_message(htmlspecialchars(trim(stripslashes($HTTP_POST_VARS['auction_offer_sellers_location']))))), $board_config['allow_html'], $board_config['allow_bbcode'], $board_config['allow_smilies'], 0) . "',
                                '" . prepare_message(addslashes(unprepare_message(htmlspecialchars(trim(stripslashes($HTTP_POST_VARS['auction_offer_accepted_payments']))))), $board_config['allow_html'], $board_config['allow_bbcode'], $board_config['allow_smilies'], 0) . "',
                                " . $HTTP_POST_VARS['auction_offer_bid_increase'] . ")";


                 if( !($result = $db->sql_query($sql)) )
                      {
                           message_die(GENERAL_ERROR, 'Could not insert offer', '', __LINE__, __FILE__, $sql);
                      }

                 $sql = "SELECT MAX(PK_auction_offer_id) as max_id
                         FROM " . AUCTION_OFFER_TABLE . "";


                 if( !($result = $db->sql_query($sql)) )
                      {
                           message_die(GENERAL_ERROR, 'Could not get offer id', '', __LINE__, __FILE__, $sql);
                      }

                 $auction_offer_max_id = $db->sql_fetchrow($result);

                 $auction_offer_id = $auction_offer_max_id['max_id'];

				if ($pic_upload == 1)
				{

					// todo here check variables if they are not empty!
                                        // (if a variable == "" we get a db error)

					 $sql = "INSERT INTO
							" . AUCTION_IMAGE_TABLE . " (pic_filename, pic_auction_id, pic_time, pic_cat, pic_room,  pic_approval, pic_main, pic_user_ip, pic_gd_type)
							VALUES ('" . $pic_filename . "', " . $auction_offer_id . "," . $pic_time . "," . $HTTP_POST_VARS['auction_room_id'] . "," . $HTTP_POST_VARS['auction_room_id'] . "," . $pic_approval . "," . $pic_main . ", '" . $pic_user_ip . "', " . $gd_type . ")";

					 if( !($result = $db->sql_query($sql)) )
						  {
							   message_die(GENERAL_ERROR, 'Could not insert main image', '', __LINE__, __FILE__, $sql);
						  }
				}

                  if ( (($auction_config_data['auction_paymentsystem_activate_paypal'] == 1) OR $auction_config_data['auction_paymentsystem_activated_moneybooker']) AND ($offer_paid==0) AND ($auction_offer_price != 0))
                      {

                             //$auction_offer_price += doubleval($auction_config_data['auction_offer_cost_basic']);

                            if ( $auction_config_data['auction_paymentsystem_activate_user_points'] == 1 )
                                 {
                                      $template->assign_block_vars('user_points', array(
                                                 'L_AUCTION_PAY_WITH_USER_POINTS' => sprintf($lang['auction_pay_with_user_points'], $board_config['points_name']),
                                                 'AUCTION_PAY_WITH_USER_POINTS_TOTAL_COST' => $auction_offer_price,
                                                 'AUCTION_PAY_WITH_USER_POINTS_OFFER_ID' => $auction_offer_id,
                                                 'S_AUCTION_PAY_WITH_USER_POINTS' => append_sid("auction_offer.php?mode=user_points_pay") ));
                                 } // if

                            if ( $auction_config_data['auction_paymentsystem_activate_paypal'] == 1 && $auction_config_data['auction_paymentsystem_activate_user_points'] == 0)
                                 {
                                      $template->assign_block_vars('paypal', array('PAYPAL_IMAGE' => PAYPAL_IMAGE ));
                                 } // if

                            // Accept moneybooker payments
                            if ( $auction_config_data['auction_paymentsystem_activate_moneybooker'] == 1 && $auction_config_data['auction_paymentsystem_activate_user_points'] == 0)
                               {
                                    $template->assign_block_vars('moneybooker', array('MONEYBOOKER_IMAGE' => MONEYBOOKER_IMAGE));
                               } // if

                            if ( $auction_config_data['auction_paymentsystem_activate_debit'] == 1 && $auction_config_data['auction_paymentsystem_activate_user_points'] == 0)
                               {
                                    $template->assign_block_vars('debit', array(
                                         'L_AUCTION_DEBIT' => $lang['auction_debit'],
                                         'AUCTION_PRICE_TOTAL' => $auction_offer_price,
                                         'L_AUCTION_DEBIT_AMOUNT' => $lang['auction_debit_amount'],
                                         'U_AUCTION_DEBIT_AMOUNT' => append_sid("auction_offer.php?mode=debit&" . POST_AUCTION_OFFER_URL . "=" . $auction_offer_id)));
                               } // if

                             $template->assign_vars(array(
                               'L_AUCTION_PRICE_TOTAL' => $lang['auction_price_total'],
                               'L_AUCTION_PRICE_BASIC' => $lang['auction_price_basic'],
                               'L_AUCTION_PRICE_BOLD' => $lang['auction_price_bold'],
                               'L_AUCTION_PRICE_ON_TOP' => $lang['auction_price_on_top'],
                               'L_AUCTION_PRICE_SPECIAL' => $lang['auction_price_special'],
                               'L_AUCTION_PAYMENT' => $lang['auction_payment'],
                               'L_AUCTION_PAYMENT_EXPLAIN' => $lang['auction_payment_explain'],
                               'L_AUCTION_PAYMENTSYSTEM_PAYWITH_PAYPAL' => $lang['auction_paymentsystem_paywith_paypal'],
                               'L_AUCTION_PAYMENTSYSTEM_PAYWITH_PAYPAL_NOW' => $lang['auction_paymentsystem_paywith_paypal_now'],
                               'L_AUCTION_PAYMENT_PRINT' =>$lang['auction_payment_print'],
                               'L_AUCTION_PAYMENTSYSTEM_PAYWITH_MONEYBOOKER' => $lang['auction_paymentsystem_paywith_moneybooker'],
                               'L_AUCTION_PRICE_DIRECT_SELL' => $lang['auction_price_direct_sell'],

                               'AUCTION_OFFER_ID' => $auction_offer_id,
                               'AUCTION_OFFER_TITLE' => $board_config['site_desc'] . " - ". $HTTP_POST_VARS['auction_offer_title'],
                               'AUCTION_PAYPAL_ADRESS' => $auction_config_data['auction_paymentsystem_paypal_email'],
                               'AUCTION_PAYMENT_NOTIFICATION' => "http://" . $_SERVER['HTTP_HOST'] . $board_config['script_path'] . "/auction_ipn.php",
                               'AUCTION_PAYMENT_RETURN' => "http://" . $_SERVER['HTTP_HOST'] . $board_config['script_path'] . "/auction.php",
                               'AUCTION_PAYMENT_CURRENCY' =>$auction_config_data['currency'],
                               'AUCTION_PRICE_BASIC' => $auction_config_data['auction_offer_cost_basic'] . " " . $auction_config_data['currency'],
                               'AUCTION_PRICE_BOLD' => ( $HTTP_POST_VARS['offer_bold'] ) ? "" . $auction_config_data['auction_offer_cost_bold'] . " " . $auction_config_data['currency'] : "- " . $auction_config_data['currency'],
                               'AUCTION_PRICE_ON_TOP' => ( $HTTP_POST_VARS['offer_on_top'] ) ? "" . $auction_config_data['auction_offer_cost_on_top'] . " " . $auction_config_data['currency'] : "- " . $auction_config_data['currency'],
                               'AUCTION_PRICE_SPECIAL' => ( $HTTP_POST_VARS['offer_special'] ) ? "" . $auction_config_data['auction_offer_cost_special'] . " " . $auction_config_data['currency'] : "- " . $auction_config_data['currency'],
                               'AUCTION_PRICE_DIRECT_SELL' => ( $HTTP_POST_VARS['auction_offer_direct_sell_price'] ) ? "" . $auction_config_data['auction_offer_cost_direct_sell'] . " " . $auction_config_data['currency'] : "- " . $auction_config_data['currency'],
                               'AUCTION_PRICE_TOTAL' => $auction_offer_price,
                               'AUCTION_CURRENY' =>  $auction_config_data['currency'],
                               'AUCTION_MONEYBOOKER_EMAIL' => $auction_config_data['auction_paymentsystem_moneybooker_email'],
                               
                               'S_AUCTION_ADD_OFFER_ACTION' => append_sid("auction_offer.$phpEx?mode=create&" . POST_AUCTION_ROOM_URL . "=" . $HTTP_GET_VARS[POST_AUCTION_ROOM_URL])));
                      }
                 else
                      {
                           $message = $lang['auction_offer_saved'] . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_auction_room'], "<a href=\"" . append_sid("auction_room.$phpEx?ar=" . $HTTP_POST_VARS['auction_room_id']) . "\">", "</a>");
                           message_die(GENERAL_MESSAGE, $message);
                      } // if

                 $template->set_filenames(array('body' => 'auction_add_offer_pay.tpl'));
                  $template->pparse('body');
                 include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

                 break;

             case 'delete_bid':

                   $page_title = $lang['auction_auth_delete_bid'];
                   include('./includes/page_header.php');

                   // Check auction_permission
                   checkPermission('DELETE_BID');

                  // registered and auctioneers can only move their own offers
                  $role = getRole();

                  if ( $role == 'registered' OR $role=='auctioneer' )
                      {
                           $sql = "SELECT FK_auction_bid_user_id
                                   FROM " . AUCTION_BID_TABLE . "
                                   WHERE PK_auction_bid_id=" . $HTTP_GET_VARS[POST_AUCTION_BID_URL];

                           if( !($result = $db->sql_query($sql)) )
                              {
                                   message_die(GENERAL_ERROR, 'Could not query bidder', '', __LINE__, __FILE__, $sql);
                              }

                           $auction_offer = $db->sql_fetchrow($result);
                           // stop the evil person moving the offer
                           if ( $auction_offer['FK_auction_bid_user_id'] <> $userdata['user_id'] )
                                {
                                     message_die(GENERAL_MESSAGE, $lang['auction_no_permission_delete_bid']);
                                }
                      }

                   // Step 1: Delete bid
                   $sql = "DELETE FROM " . AUCTION_BID_TABLE . "
                           WHERE PK_auction_bid_id = " . $HTTP_GET_VARS[POST_AUCTION_BID_URL] . "";

                   if( !($result = $db->sql_query($sql)) )
                             {
                                 message_die(GENERAL_ERROR, 'Could not delete bid', '', __LINE__, __FILE__, $sql);
                             }

                   // Step 2: Get new highest bid
                   $sql = "SELECT FK_auction_bid_user_id,
                                  auction_bid_price
                           FROM " . AUCTION_BID_TABLE . "
                           WHERE FK_auction_bid_offer_id = " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "
                           ORDER BY auction_bid_price DESC";

                   if( !($result = $db->sql_query($sql)) )
                             {
                                 message_die(GENERAL_ERROR, 'Could not grab new highest bid', '', __LINE__, __FILE__, $sql);
                             }
                             
                   $auction_bid_row = $db->sql_fetchrow($result);

                   $auction_highest_bid_user_id = ( $auction_bid_row['FK_auction_bid_user_id'] ) ? $auction_bid_row['FK_auction_bid_user_id'] : "0" ;
                   $auction_highest_bid_price = ( $auction_bid_row['auction_bid_price'] ) ? $auction_bid_row['auction_bid_price'] : "0" ;

                   // Step 3: Update offer-table with new highest bid
                   $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                           SET auction_offer_last_bid_price = " . $auction_highest_bid_price . ",
                               FK_auction_offer_last_bid_user_id = " . $auction_highest_bid_user_id . "
                           WHERE PK_auction_offer_id = " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];

                   if( !($result = $db->sql_query($sql)) )
                             {
                                 message_die(GENERAL_ERROR, 'Could not insert new highest bid', '', __LINE__, __FILE__, $sql);
                             }

                   $message = $lang['auction_bid_deleted'] . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_offer'], "<a href=\"" . append_sid("auction_offer_view.$phpEx?" . POST_AUCTION_OFFER_URL . "=" .  $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) . "\">", "</a>");
                   message_die(GENERAL_MESSAGE, $message);

                 break;
                 
             case 'late_pay':

                   $page_title = $lang['auction_offer_pay_now'];
                   include('./includes/page_header.php');

                   $sql = "SELECT *
                           FROM " . AUCTION_OFFER_TABLE . "
                           WHERE PK_auction_offer_id = " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";

                   if( !($result = $db->sql_query($sql)) )
                             {
                                 message_die(GENERAL_ERROR, 'Could not query offer', '', __LINE__, __FILE__, $sql);
                             }

                   $auction_offer =  $db->sql_fetchrow($result);
                   $cost_bold = ( $auction_offer['auction_offer_bold'] ) ? $auction_config_data['auction_offer_cost_bold'] : 0;
                   $cost_special = ( $auction_offer['auction_offer_special'] ) ? $auction_config_data['auction_offer_cost_special'] : 0;
                   $cost_on_top = ( $auction_offer['auction_offer_on_top'] ) ? $auction_config_data['auction_offer_cost_on_top'] : 0;
                   $cost_direct_sell = ( $auction_offer['auction_offer_direct_sell_price'] ) ? $auction_config_data['auction_offer_cost_direct_sell'] : 0;
                   $cost_total = $cost_bold + $cost_special + $cost_on_top + $cost_direct_sell + $auction_config_data['auction_offer_cost_basic'];

                   if ( $auction_config_data['auction_paymentsystem_activate_user_points'] == 1 )
                        {
                             // Charge offer
                             $sql = "UPDATE " . USERS_TABLE . "
                                     SET user_points = user_points-" . round($cost_total,0). "
                                     WHERE user_id = " . $userdata['user_id'] . "";

                             if( !($result = $db->sql_query($sql)) )
                                  {
                                       message_die(GENERAL_MESSAGE, 'Couldn\'t charge points');
                                  }

                             // Mark offer paid
                             $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                                     SET auction_offer_paid = 1
                                     WHERE PK_auction_offer_id = " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];

                             if( !($result = $db->sql_query($sql)) )
                                  {
                                       message_die(GENERAL_MESSAGE, 'Couldn\'t mark offer paid');
                                  }

                             $message = $lang['auction_offer_paid'] . "<br /><br />" . sprintf($lang['Click_return_offer'], "<a href=\"" . append_sid("auction_offer_view.$phpEx?ao=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx") . "\">", "</a>");
                             message_die(GENERAL_MESSAGE, $message);

                        }

                   $template->set_filenames(array('body' => 'auction_add_offer_pay.tpl'));

                   if ( $auction_config_data['auction_paymentsystem_activate_paypal'] == 1 AND $auction_config_data['auction_paymentsystem_activate_user_points'] == 0 )
                        {
                             $template->assign_block_vars('paypal', array(
                               'PAYPAL_IMAGE' => PAYPAL_IMAGE ));
                        }
                   // Accept moneybooker payments - show image
                   if ( $auction_config_data['auction_paymentsystem_activate_moneybooker'] == 1 AND $auction_config_data['auction_paymentsystem_activate_user_points'] == 0 )
                      {
                           $template->assign_block_vars('moneybooker', array('MONEYBOOKER_IMAGE' => MONEYBOOKER_IMAGE));
                      }

                   $template->assign_vars(array(
                     'L_AUCTION_PRICE_TOTAL' => $lang['auction_price_total'],
                     'L_AUCTION_PRICE_BASIC' => $lang['auction_price_basic'],
                     'L_AUCTION_PRICE_BOLD' => $lang['auction_price_bold'],
                     'L_AUCTION_PRICE_ON_TOP' => $lang['auction_price_on_top'],
                     'L_AUCTION_PRICE_SPECIAL' => $lang['auction_price_special'],
                     'L_AUCTION_PRICE_DIRECT_SELL' => $lang['auction_price_direct_sell'],
                     'L_AUCTION_PAYMENT' => $lang['auction_payment'],
                     'L_AUCTION_PAYMENT_EXPLAIN' => $lang['auction_payment_explain'],
                     'L_AUCTION_PAYMENT_EXPLAIN_MONEYBOOKER' => ( $auction_config_data['auction_paymentsystem_activate_moneybooker'] ) ? $lang['auction_payment_explain_moneybooker'] : "",
                     'L_AUCTION_PAYMENT_EXPLAIN_PAYPAL' => ( $auction_config_data['auction_paymentsystem_activate_paypal'] ) ? $lang['auction_payment_explain_paypal'] : "",

                     'L_AUCTION_PAYMENTSYSTEM_PAYWITH_PAYPAL' => $lang['auction_paymentsystem_paywith_paypal'],
                     'L_AUCTION_PAYMENTSYSTEM_PAYWITH_PAYPAL_NOW' => $lang['auction_paymentsystem_paywith_paypal_now'],
                     'L_AUCTION_PAYMENT_PRINT' =>$lang['auction_payment_print'],
                     'L_AUCTION_PAYMENTSYSTEM_PAYWITH_MONEYBOOKER' => $lang['auction_paymentsystem_paywith_moneybooker'],

                     'AUCTION_PAYPAL_ADRESS' => $auction_config_data['auction_paymentsystem_paypal_email'],
                     'AUCTION_PAYMENT_NOTIFICATION' => "http://" . $_SERVER['HTTP_HOST'] . $board_config['script_path'] . "auction_ipn.php",
                     'AUCTION_PAYMENT_RETURN' => "http://" . $_SERVER['HTTP_HOST'] . $board_config['script_path'] . "auction.php",
                     'AUCTION_PAYMENT_CURRENCY' =>$auction_config_data['currency'],
                     'AUCTION_CURRENY' =>  $auction_config_data['currency'],
                     'AUCTION_PRICE_BASIC' => $auction_config_data['auction_offer_cost_basic'] . " " . $auction_config_data['currency'],
                     'AUCTION_MONEYBOOKER_EMAIL' => $auction_config_data['auction_paymentsystem_moneybooker_email'],

                     'AUCTION_PRICE_BOLD' => ( $auction_offer['auction_offer_bold'] ) ? "" . $auction_config_data['auction_offer_cost_bold'] . " " . $auction_config_data['currency'] : "- " . $auction_config_data['currency'],
                     'AUCTION_PRICE_ON_TOP' => ( $auction_offer['auction_offer_on_top'] ) ? "" . $auction_config_data['auction_offer_cost_on_top'] . " " . $auction_config_data['currency'] : "- " . $auction_config_data['currency'],
                     'AUCTION_PRICE_SPECIAL' => ( $auction_offer['auction_offer_special'] ) ? "" . $auction_config_data['auction_offer_cost_special'] . " " . $auction_config_data['currency'] : "- " . $auction_config_data['currency'],
                     'AUCTION_PRICE_DIRECT_SELL' => ( $auction_offer['auction_offer_direct_sell_price'] ) ? "" . $auction_config_data['auction_offer_cost_direct_sell'] . " " . $auction_config_data['currency'] : "- " . $auction_config_data['currency'],
                     'AUCTION_PRICE_TOTAL' => $cost_total,
                     'AUCTION_OFFER_TITLE' => $board_config['site_desc'] . " - ". $auction_offer['auction_offer_title'],
                     'AUCTION_OFFER_ID' => $auction_offer['PK_auction_offer_id'],

                     'S_AUCTION_ADD_OFFER_ACTION' => append_sid("auction_offer.$phpEx?mode=create&" . POST_AUCTION_ROOM_URL . "=" . $HTTP_GET_VARS[POST_AUCTION_ROOM_URL])));

                 $template->pparse('body');
                 include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

                 break;

             case 'add_comment' :

                   $page_title = $lang['auction_offer_comment_add_edit'];
                   include('./includes/page_header.php');

                  // Check auction_permission
                  checkPermission('COMMENT');

                  // Get drop down for select box
                  $sql = "SELECT FK_auction_offer_user_id
                          FROM " . AUCTION_OFFER_TABLE . "
                          WHERE PK_auction_offer_id=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";

                  if( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query auction-offer-id', '', __LINE__, __FILE__, $sql);
                       }

                  $row = $db->sql_fetchrow($result);

                  if ( $row['FK_auction_offer_user_id'] == $userdata['user_id'] )
                       {
                            $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                                    SET auction_offer_comment= '" . $HTTP_POST_VARS['auction_offer_comment'] . "',
                                        auction_offer_comment_time = " . time() . "
                                    WHERE PK_auction_offer_id=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";

                            if( !($result = $db->sql_query($sql)) )
                                 {
                                      message_die(GENERAL_ERROR, 'Could update comment', '', __LINE__, __FILE__, $sql);
                                 }
                            $message = $lang['auction_offer_commented_successful'] . "<br /><br />" . sprintf($lang['Click_return_offer'], "<a href=\"" . append_sid("auction_offer_view.$phpEx?ao=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx") . "\">", "</a>");
                            message_die(GENERAL_MESSAGE, $message);
                       }
                  else
                       {
                            message_die(GENERAL_MESSAGE, $lang['auction_no_permission_comment']);
                       }
                 break;
                 
             case 'move_select' :

                  $page_title = $lang['auction_offer_move'];
                  include('./includes/page_header.php');

                  // Check auction_permission
                  checkPermission('MOVE');

                  // registered and auctioneers can only move their own offers
                  $role = getRole();
                  if ( $role == 'registered' OR $role=='auctioneer' )
                      {
                           $sql = "SELECT FK_auction_offer_user_id
                                   FROM " . AUCTION_OFFER_TABLE . "
                                   WHERE PK_auction_offer_id=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];

                           if( !($result = $db->sql_query($sql)) )
                              {
                                   message_die(GENERAL_ERROR, 'Could not query offer-seller', '', __LINE__, __FILE__, $sql);
                              }

                           $auction_offer = $db->sql_fetchrow($result);
                           // stop the evil person moving the offer
                           if ( $auction_offer['FK_auction_offer_user_id'] <> $userdata['user_id'] )
                                {
                                     message_die(GENERAL_MESSAGE, $lang['auction_no_permission_move']);
                                }
                      }

                           // Get drop down for select box (just open rooms)
                           $sql = "SELECT PK_auction_room_id,
                                          auction_room_title
                                   FROM " . AUCTION_ROOM_TABLE . "
                                   WHERE  auction_room_state=0";

                           if( !($result = $db->sql_query($sql)) )
                              {
                                   message_die(GENERAL_ERROR, 'Could not query auction-start and stop date', '', __LINE__, __FILE__, $sql);
                              }

                           $dd_string = "<select name=\"auction_room_id\">";
                           while( $auction_room = $db->sql_fetchrow($result) )
                               {
                                  $dd_string .= "<option value=\"" . $auction_room['PK_auction_room_id'] . "\">" . $auction_room['auction_room_title'] . "</option>";
                               }
                           $dd_string .= "</select>";

                           $template->set_filenames(array('body' => 'auction_move_offer.tpl'));

                           $template->assign_vars(array(
                              'L_AUCTION_OFFER_MOVE' => $lang['auction_offer_move'],
                              'L_AUCTION_OFFER_MOVE_NOW' => $lang['auction_offer_move_now'],
                              'DD_AUCTION_ROOM' => $dd_string,
                              'S_AUCTION_MOVE' => append_sid("auction_offer.$phpEx?mode=move&" . POST_AUCTION_OFFER_URL . "=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL])));

                         $template->pparse('body');

                         include($phpbb_root_path . 'includes/page_tail.'.$phpEx);


                  break; //move_select

             case 'move' :

                  $page_title = $lang['auction_offer_move'];
                  include('./includes/page_header.php');

                  // Check auction_permission
                  checkPermission('MOVE');

                  // registered and auctioneers can only move their own offers
                  $role = getRole();
                  if ( $role == 'registered' OR $role='auctioneer' )
                      {
                           $sql = "SELECT FK_auction_offer_user_id
                                   FROM " . AUCTION_OFFER_TABLE . "
                                   WHERE PK_auction_offer_id=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];

                           if( !($result = $db->sql_query($sql)) )
                              {
                                   message_die(GENERAL_ERROR, 'Could not query offer-seller', '', __LINE__, __FILE__, $sql);
                              }

                           $auction_offer = $db->sql_fetchrow($result);
                           // stop the evil person moving the offer
                           if ( $auction_offer['FK_auction_offer_user_id'] <> $userdata['user_id'] AND (
                                getRole() == 'registered' OR getRole() == 'auctioneer' ))
                                {
                                     message_die(GENERAL_MESSAGE, $lang['auction_no_permission_move']);
                                }
                      }

			$pic_num = 0;
			$sql = "SELECT pic_id
                                FROM ". AUCTION_IMAGE_TABLE ."
                                WHERE pic_auction_id  =  " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";

                        if( !($result = $db->sql_query($sql)) )
			     {
				message_die(GENERAL_ERROR, 'Could not select pic information', '', __LINE__, __FILE__, $sql);
			     }

			$move_row = array();
			while( $row = $db->sql_fetchrow($result) )
			     {
			          $move_row[] = $row;
			     }
			$db->sql_freeresult($result);

			// we count the pics
			$pic_num = count($move_row);
			if($pic_num > 0)
			     {
			          for ($i = 0; $i < $pic_num; $i++)
				       {
			                    $pic_id = $move_row[$i]['pic_id'];
					    $sql = "UPDATE " . AUCTION_IMAGE_TABLE . "
						    SET pic_room =" . $HTTP_POST_VARS['auction_room_id'] . "
			                            WHERE pic_id = '$pic_id'";

					    if( !($result = $db->sql_query($sql)) )
					         {
						      message_die(GENERAL_ERROR, 'Could not move offer pic pic_id=' . $pic_id, '', __LINE__, __FILE__, $sql);
                                                 } // if
                                       } // for
			     } // if

                           $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                                   SET FK_auction_offer_room_id=" . $HTTP_POST_VARS['auction_room_id'] . "
                                   WHERE PK_auction_offer_id= " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";

                           if( !($result = $db->sql_query($sql)) )
                              {
                                   message_die(GENERAL_ERROR, 'Could not move offer', '', __LINE__, __FILE__, $sql);
                              } // if

                            $navigation_text = "<br><br><a href=\"" . append_sid("auction.php") . "\">" . $lang['return_to_auction_index'] . "<br><br><a href=\"" . append_sid("auction_room.php?" . POST_AUCTION_ROOM_URL . "=" . $HTTP_POST_VARS['auction_room_id']) . "\">" . $lang['return_to_auction_room'] . "</a>";
                            message_die(GENERAL_MESSAGE, $lang['auction_offer_moved'] . $navigation_text);

                  break; // move

             case 'buy_now' :

                 $page_title = $lang['auction_offer_pay_now'];
                 include('./includes/page_header.php');

                 // Check auction_permission
                 checkPermission('BID');

                 // Check selfbid
                 $sql = "SELECT FK_auction_offer_user_id,
                                auction_offer_state,
                                auction_offer_direct_sell_price,
                                auction_offer_title
                         FROM " . AUCTION_OFFER_TABLE . "
                         WHERE PK_auction_offer_id= " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not query auction-start and stop date', '', __LINE__, __FILE__, $sql);
                     }
                 $auction_offer_row = $db->sql_fetchrow($result);

                 if ( $auction_config_data['auction_allow_self_bids'] == 0 AND $auction_offer_row['FK_auction_offer_user_id'] == $userdata['user_id'] )
                      {
                            $message = $lang['no_selfbids'] . "<br /><br />" . sprintf($lang['Click_return_offer'], "<a href=\"" . append_sid("auction_offer_view.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) . "\">", "</a>");
                            message_die(GENERAL_MESSAGE, $message);
                      }
                 if ( $auction_offer_row['auction_offer_state'] == AUCTION_OFFER_DIRECT_SOLD )
                      {
                            $message = $lang['auction_offer_sold'] . "<br /><br />" . sprintf($lang['Click_return_offer'], "<a href=\"" . append_sid("auction_offer_view.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) . "\">", "</a>");
                            message_die(GENERAL_MESSAGE, $message);
                      }

                // if point-payment is activated, charge the points now
                if ( $auction_config_data['auction_paymentsystem_activate_user_points'] == 1 )
                     {
                          // CHECK IF user has enough points
                          $sql = "SELECT user_points
                                  FROM " . USERS_TABLE . "
                                  WHERE user_id= " . $userdata['user_id'];

                          if( !($result = $db->sql_query($sql)) )
                               {
                                     message_die(GENERAL_ERROR, 'Could not query user-points', '', __LINE__, __FILE__, $sql);
                               }
                               
                          $row = $db->sql_fetchrow($result);

                          if ( $row['user_points'] < $auction_offer_row['auction_offer_direct_sell_price'])
                               {
                                     $message = sprintf($lang['auction_not_enough_points'], $board_config['points_name']) . '<br /><br />' . sprintf($lang['Click_return_offer'], '<a href="' . append_sid('auction_offer_view.'.$phpEx.'?' . POST_AUCTION_OFFER_URL . '=' . $HTTP_GET_VARS['POST_AUCTION_OFFER_URL']) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_auction_index'], '<a href="' . append_sid('auction.'.$phpEx) . '">', '</a>');
                                     message_die(GENERAL_MESSAGE, $message);
                               }
                       }

                 $template->set_filenames(array('body' => 'auction_confirm_bid.tpl'));

                 $template->assign_vars(array(
                         'L_AUCTION_CONFIRM_BID'=> $lang['auction_confirm_direct_buy'],
                         'L_AUCTION_ABOUT_TO_CONFIRM'=> $lang['auction_offer_direct_buy_confirm_now'],
                         'L_AUCTION_OFFER_TITLE' => $lang['auction_offer_title'],
                         'L_YES' => $lang['yes'],
                         'L_CANCEL'=> $lang['cancel'],
                         'BID_AMOUNT' => $auction_offer_row['auction_offer_direct_sell_price'],
                         'AUCTION_OFFER_CURRENCY' => $auction_config_data['currency'],
                         'U_RETURN'  => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL .  "=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . ""),
                         'L_RETURN' => $lang['cancel'],
                         'S_AUCTION_BID_NOW' => append_sid("auction_offer.php?mode=buy_now_confirm&" . POST_AUCTION_OFFER_URL .  "=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . ""),
                         'AUCTION_OFFER_TITLE' => $auction_offer_row['auction_offer_title']));

                $template->pparse('body');
                include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

             case 'buy_now_confirm':

                 $page_title = $lang['auction_offer_bought'];
                 include('./includes/page_header.php');

                // if pay-with-userpoints is active charge points now
                if ( $auction_config_data['auction_paymentsystem_activate_user_points'] == 1 )
                     {
                          // Charge points
                          $sql = "UPDATE " . USERS_TABLE . "
                                  SET user_points = user_points-". round($auction_offer_row['auction_offer_direct_sell_price'],0) . "
                                  WHERE user_id = " . $userdata['user_id'] . "";

                          if( !($result = $db->sql_query($sql)) )
                               {
                                    message_die(GENERAL_ERROR, 'Could not charge points', '', __LINE__, __FILE__, $sql);
                               }
                       }
                 // Store buy_now and lock offer
                 $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                         SET auction_offer_state = ". AUCTION_OFFER_DIRECT_SOLD . ",
                             auction_offer_last_bid_price = " . doubleval($HTTP_POST_VARS['auction_your_amount']) . ",
                             FK_auction_offer_last_bid_user_id  =  " . $userdata['user_id'] . "
                         WHERE PK_auction_offer_id = " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];

                 if( !($result = $db->sql_query($sql)) )
                      {
                            message_die(GENERAL_ERROR, 'Could not update offer-table', '', __LINE__, __FILE__, $sql);
                      }

                 $message = $lang['auction_offer_bought'] . "<br /><br />" . sprintf($lang['Click_return_offer'], "<a href=\"" . append_sid("auction_offer_view.$phpEx?ao=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx") . "\">", "</a>");
                 message_die(GENERAL_MESSAGE, $message);

                 break; // buy_now

             case 'bid_confirm':

                 $page_title = $lang['auction_bid_now'];
                 include('./includes/page_header.php');

                 // Check auction_permission
                 checkPermission('BID');

                 // check if user is logged in
                 if ( !$userdata['session_logged_in'] )
                      {
                           redirect("login.".$phpEx."?redirect=auction_offer.".$phpEx."?mode=bid_confirm&" . POST_AUCTION_ROOM_URL . "=" . $HTTP_GET_VARS[POST_AUCTION_ROOM_URL]);
                           exit;
                      }
         
                 // Grab offer data
                 $sql = "SELECT auction_offer_time_start,
                                auction_offer_time_stop,
                                FK_auction_offer_user_id,
                                auction_offer_price_start,
                                FK_auction_offer_last_bid_user_id,
                                auction_offer_title,
                                auction_offer_last_bid_price,
                                auction_offer_bid_increase
                         FROM " . AUCTION_OFFER_TABLE . "
                         WHERE PK_auction_offer_id= " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not query auction-start and stop date', '', __LINE__, __FILE__, $sql);
                     } // if

                 $auction_corresponding_bids_start_stop_row = $db->sql_fetchrow($result);

                 if ($auction_corresponding_bids_start_stop_row['auction_offer_time_start']>time())
                    {
                          message_die(GENERAL_MESSAGE, $lang['auction_offer_not_started']);
                    } // if

                 if ($auction_corresponding_bids_start_stop_row['auction_offer_time_stop']<time())
                    {
                          message_die(GENERAL_MESSAGE, $lang['auction_offer_over']);
                    } // if

                 // if no bid exists, the new bid needs to be higher than the initial price
                 $last = $auction_corresponding_bids_start_stop_row['auction_offer_last_bid_price'];
                 if ( ($auction_corresponding_bids_start_stop_row['auction_offer_last_bid_price'] == 0 ) || ($auction_corresponding_bids_start_stop_row['auction_offer_last_bid_price']=""))
                      {
                           if ( $HTTP_POST_VARS['auction_your_amount'] < $auction_corresponding_bids_start_stop_row['auction_offer_price_start'])
                                  {
                                      $message = $lang['auction_bid_to_low'] . "<br /><br />" . sprintf($lang['Click_return_offer'], "<a href=\"" . append_sid("auction_offer_view.$phpEx?ao=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) . "\">", "</a>");
                                      message_die(GENERAL_MESSAGE, $message);
                                  }
                      } // if

                 if ( $last > 0 )
                       {
                             if ( $HTTP_POST_VARS['auction_your_amount'] < $last+$auction_corresponding_bids_start_stop_row['auction_offer_bid_increase'])
                              {
                                  $message = $lang['auction_bid_to_low'] . "<br /><br />" . sprintf($lang['Click_return_offer'], "<a href=\"" . append_sid("auction_offer_view.$phpEx?ao=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) . "\">", "</a>");
                                  message_die(GENERAL_MESSAGE, $message);
                              } // if
                      } // if


                 if ($HTTP_POST_VARS['auction_your_amount']>$auction_config_data['auction_offer_amount_max'])
                      {
                           message_die(GENERAL_MESSAGE, $lang['auction_bid_amount_to_high']);
                      }

                 if ( $auction_config_data['auction_allow_self_bids'] == 0 AND $auction_corresponding_bids_start_stop_row['FK_auction_offer_user_id'] == $userdata['user_id'] )
                      {
                            $message = $lang['no_selfbids'] . "<br /><br />" . sprintf($lang['Click_return_offer'], "<a href=\"" . append_sid("auction_offer_view.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) . "\">", "</a>");
                            message_die(GENERAL_MESSAGE, $message);
                      }

                if ( $auction_config_data['auction_paymentsystem_activate_user_points'] == 1 )
                     {
                          // Check on user-point-payment if user has enough points
                          $sql = "SELECT user_points
                                  FROM " . USERS_TABLE . "
                                  WHERE user_id= " . $userdata['user_id'] . "";

                          if( !($result = $db->sql_query($sql)) )
                               {
                                    message_die(GENERAL_ERROR, 'Could not query user-points', '', __LINE__, __FILE__, $sql);
                               } // if

                          $row = $db->sql_fetchrow($result);
                 
                          if ( $HTTP_POST_VARS['auction_your_amount'] > $row['user_points'] )
                               {
                                    $message = $lang['auction_not_enough_points'] . "<br /><br />" . sprintf($lang['Click_return_offer'], "<a href=\"" . append_sid("auction_offer_view.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) . "\">", "</a>");
                                    message_die(GENERAL_MESSAGE, $message);
                               }
                      }

                 $template->set_filenames(array('body' => 'auction_confirm_bid.tpl'));

                 $template->assign_vars(array(
                         'L_AUCTION_CONFIRM_BID'=> $lang['auction_confirm_bid'],
                         'L_AUCTION_ABOUT_TO_CONFIRM'=> $lang['auction_offer_bid_confirm_now'],
                         'L_AUCTION_OFFER_TITLE' => $lang['auction_offer_title'],
                         'L_YES' => $lang['yes'],
                         'L_CANCEL'=> $lang['cancel'],
                         'BID_AMOUNT' => $HTTP_POST_VARS['auction_your_amount'],
                         'AUCTION_OFFER_CURRENCY' => $auction_config_data['currency'],
                         'U_RETURN'  => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL .  "=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . ""),
                         'L_RETURN' => $lang['cancel'],
                         'S_AUCTION_BID_NOW' => append_sid("auction_offer.php?mode=bid&" . POST_AUCTION_OFFER_URL .  "=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . ""),
                         'AUCTION_OFFER_TITLE' => $auction_corresponding_bids_start_stop_row['auction_offer_title']));

                $template->pparse('body');
                include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

             break; // bid_confirm

             case 'bid':

                 $page_title = $lang['auction_bid_now'];
                 include('./includes/page_header.php');

                 // Check auction_permission
                 checkPermission('BID');

                 // check if user is logged in
                 if ($userdata['user_id']<0)
                    {
                         message_die(GENERAL_MESSAGE, 'Please login in to bid');
                    } // if

                 // Check start and stop of offer
                 $sql = "SELECT auction_offer_title,
                                FK_auction_offer_user_id,
                                FK_auction_offer_last_bid_user_id
                         FROM " . AUCTION_OFFER_TABLE . "
                         WHERE PK_auction_offer_id= " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not query auction-start and stop date', '', __LINE__, __FILE__, $sql);
                     } // if

                 $auction_corresponding_bids_start_stop_row = $db->sql_fetchrow($result);


                 // Charge points if payment-system is activated
                 if ( $auction_config_data['auction_paymentsystem_activate_user_points'] == 1)
                      {
                           // Step 1: Charge points of bidder
                           $sql = "UPDATE " . USERS_TABLE . "
                                   SET user_points=user_points-" . round($HTTP_POST_VARS['auction_your_amount'],0) . "
                                   WHERE user_id = " . $userdata['user_id'] . "";

                           if( !($result = $db->sql_query($sql)) )
                                {
                                     message_die(GENERAL_ERROR, 'Could not charge points', '', __LINE__, __FILE__, $sql);
                                }

                           // Step 2: Get outbidded user and his bid to recharge his points
                           $sql = "SELECT FK_auction_offer_last_bid_user_id,
                                          auction_offer_last_bid_price
                                   FROM " . AUCTION_OFFER_TABLE . "
                                   WHERE PK_auction_offer_id = " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];

                           if( !($result = $db->sql_query($sql)) )
                                {
                                     message_die(GENERAL_ERROR, 'Could not grab outbidded user and his bid', '', __LINE__, __FILE__, $sql);
                                }

                           $auction_bid_row = $db->sql_fetchrow($result);

                           $auction_highest_bid_user_id = ( $auction_bid_row['FK_auction_offer_last_bid_user_id'] ) ? $auction_bid_row['FK_auction_offer_last_bid_user_id'] : "0" ;
                           $auction_highest_bid_price = ( $auction_bid_row['auction_offer_last_bid_price'] ) ? $auction_bid_row['auction_offer_last_bid_price'] : "0" ;

                           // Step 3: Recharge outbidded users points
                           // if it is 0 than we have a first bid
                           if ( $auction_highest_bid_user_id <> 0 )
                                {
                                     $sql = "UPDATE " . USERS_TABLE . "
                                             SET user_points=user_points+" . round($auction_highest_bid_price,0) . "
                                             WHERE user_id = " . $auction_highest_bid_user_id . "";

                                     if( !($result = $db->sql_query($sql)) )
                                          {
                                               message_die(GENERAL_ERROR, 'Could not charge points', '', __LINE__, __FILE__, $sql);
                                          }
                               }

                          $sql = "UPDATE " . USERS_TABLE . "
                                  SET user_points=user_points+" . round($HTTP_POST_VARS['auction_your_amount'],0) . "-" . round($auction_highest_bid_price,0) . "
                                  WHERE user_id=" . $auction_corresponding_bids_start_stop_row['FK_auction_offer_user_id'] ;

                          if( !($result = $db->sql_query($sql)) )
                               {
                                    message_die(GENERAL_ERROR, 'Could not transfer points to seller', '', __LINE__, __FILE__, $sql);
                               }
                     }

                 // UPDATE Last bid in offer-table. I know its not normalized, but it saves us a lot of sql-queries on the users frontend.
                 $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                         SET auction_offer_last_bid_price  =  " . doubleval($HTTP_POST_VARS['auction_your_amount']) . ",
                             FK_auction_offer_last_bid_user_id = ". $userdata['user_id'] . "
                         WHERE PK_auction_offer_id = " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";

                 if( !($result = $db->sql_query($sql)) )
                     {
                         message_die(GENERAL_ERROR, 'Could not update offer-table', '', __LINE__, __FILE__, $sql);
                     }

                 // Check start and stop of offer
//                 $sql = "SELECT auction_offer_title,
//                                FK_auction_offer_last_bid_user_id
//                         FROM " . AUCTION_OFFER_TABLE . "
//                         WHERE PK_auction_offer_id= " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";

//                 if( !($result = $db->sql_query($sql)) )
//                     {
//                          message_die(GENERAL_ERROR, 'Could not query auction-start and stop date', '', __LINE__, __FILE__, $sql);
//                     } // if

//                 $auction_corresponding_bids_start_stop_row = $db->sql_fetchrow($result);

                 if ( $auction_config_data['auction_paymentsystem_activate_user_points'] == 1 )
                      {
                           $bid_value = round(doubleval($HTTP_POST_VARS['auction_your_amount']),0);
                      }
                 else
                      {
                           $bid_value = doubleval($HTTP_POST_VARS['auction_your_amount']);
                      }
                      
                 // Insert into bid-table
                 $sql= "INSERT INTO ". AUCTION_BID_TABLE . "
                              (FK_auction_bid_offer_id,
                               FK_auction_bid_user_id,
                               auction_bid_time,
                               auction_bid_price)
                       VALUES(" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . ",
                              " . $userdata['user_id'] . ",
                              " . time() .",
                              " . $bid_value . ")";

                 if( !($result = $db->sql_query($sql)) )
                     {
                         message_die(GENERAL_ERROR, 'Could not update bid-table', '', __LINE__, __FILE__, $sql);
                     }
                 else
                     {
                          // just drop email/pm outbid-notification if it is not the first bid and not the same person
                          if ( $auction_corresponding_bids_start_stop_row['FK_auction_offer_last_bid_user_id'] <> 0 &&
                               $auction_corresponding_bids_start_stop_row['FK_auction_offer_last_bid_user_id'] <> $userdata['user_id'])
                               {
                                    // Check ACP-setting
                                    if ( $auction_config_data['auction_email_notify'] )
                                         {
                                              // BEGIN EMAIL-NOTIFY
                                              $sql = "SELECT user_email,
                                                             username
                                                      FROM " . USERS_TABLE . "
                                                      WHERE user_id=" . $auction_corresponding_bids_start_stop_row['FK_auction_offer_last_bid_user_id'] . "";

                                              if( !($result = $db->sql_query($sql)) )
                                                   {
                                                        message_die(GENERAL_ERROR, 'Could not query username of outbidded member', '', __LINE__, __FILE__, $sql);
                                                   } // if

                                              $outbidded_user = $db->sql_fetchrow($result);

                                              $server_name = trim($board_config['server_name']);
                                              $server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
                                              $server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

                                              $username= $outbidded_user['username'];
                                              $email= $outbidded_user['user_email'];
                                              include($phpbb_root_path . 'includes/emailer.'.$phpEx);
                                              $emailer = new emailer($board_config['smtp_delivery']);
                                              $emailer->from($board_config['board_email']);
                                              $emailer->replyto($board_config['board_email']);
                                              $emailer->use_template('auction_outbid', stripslashes($user_lang));
                                              $emailer->email_address($email);
                                              $emailer->set_subject($lang['outbid']);
                                              $emailer->assign_vars(array(
                                                   'AUCTION_SITENAME' => $board_config['sitename'],
                                                   'AUCTION_OUTBID_SUBJECT' => $lang['outbid'],
                                                   'AUCTION_USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
                                                   'AUCTION_OFFER' => prepare_message(addslashes(unprepare_message(htmlspecialchars(trim(stripslashes($auction_corresponding_bids_start_stop_row['auction_offer_title']))))), $board_config['allow_html'], $board_config['allow_bbcode'], $board_config['allow_smilies'], 0),
                                                   'U_AUCTION_OFFER' => $server_protocol . $server_name . $server_port . $board_config['script_path'] . '/auction_offer_view.php?ao=' . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL],
                                                   'AUCTION_EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '' ));
                                              $emailer->send();
                                              $emailer->reset();
			                      // END EMAIL-NOTIFY
                                         }

                                    // BEGIN PM-NOTIFY ON OUTBID
                                    // Check ACP-setting
                                    if ( $auction_config_data['auction_pm_notify'] )
                                         {
                                              $outbid_pm_subject = $lang['outbid'] . " - " . prepare_message(addslashes(unprepare_message(htmlspecialchars(trim(stripslashes($auction_corresponding_bids_start_stop_row['auction_offer_title']))))), $board_config['allow_html'], $board_config['allow_bbcode'], $board_config['allow_smilies'], 0);
                                              $outbid_pm = $lang['outbid_pm'];
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
                                                               '" . str_replace("\'", "''", addslashes(sprintf($outbid_pm_subject,$board_config['sitename']))) . "',
                                                               '2',
                                                               " . $auction_corresponding_bids_start_stop_row['FK_auction_offer_last_bid_user_id'] . ",
                                                               " . $privmsgs_date . ",
                                                               '0',
                                                               '1',
                                                               '1',
                                                               '0')";

                                              if ( !$db->sql_query($sql) )
                                                   {
                                                        message_die(GENERAL_ERROR, 'Could not insert private message sent info', '', __LINE__, __FILE__, $sql);
                                                   } // if

                                              $outbid_sent_id = $db->sql_nextid();
                                              $outbid_text = $lang['outbid_pm_text'];

                                              $sql = "INSERT INTO " . PRIVMSGS_TEXT_TABLE . "
                                                           (privmsgs_text_id,
                                                            privmsgs_text)
                                                      VALUES ($outbid_sent_id,
                                                              '" . str_replace("\'", "''", addslashes(sprintf($outbid_pm,$board_config['sitename']))) . "</br></br><a href=auction_offer_view.php?ao=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . '>' . prepare_message(addslashes(unprepare_message(htmlspecialchars(trim(stripslashes($auction_corresponding_bids_start_stop_row['auction_offer_title']))))), $board_config['allow_html'], $board_config['allow_bbcode'], $board_config['allow_smilies'], 0) . '</a></br>' . $board_config['board_email_sig'] . "')";

                                              if ( !$db->sql_query($sql) )
                                                   {
                                                         message_die(GENERAL_ERROR, 'Could not insert private message sent text', '', __LINE__, __FILE__, $sql);
                                                   } // if

                                              $sql = "UPDATE " . USERS_TABLE . "
                                                      SET user_new_privmsg=user_new_privmsg+1,
                                                          user_new_privmsg = user_new_privmsg +1
                                                      WHERE user_id=" . $auction_corresponding_bids_start_stop_row['FK_auction_offer_last_bid_user_id'];

                                              if ( !$db->sql_query($sql) )
                                                   {
                                                        message_die(GENERAL_ERROR, 'Could not update user table for outbid notification', '', __LINE__, __FILE__, $sql);
                                                   } // if

                                        } // if
                                        // End pm-notification
                                  } // if
                          $message = $lang['auction_room_bid_successful'] . "<br /><br />" . sprintf($lang['Click_return_offer'], "<a href=\"" . append_sid("auction_offer_view.$phpEx?ao=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx") . "\">", "</a>");
                          message_die(GENERAL_MESSAGE, $message);
                      }

                 include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
                 break; // bid

             case 'feature':

                    $page_title = $lang['auction_offer_feature'];
                    include('./includes/page_header.php');

                    // Check auction_permission
                    checkPermission('SPECIAL');
                    
                    $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                            SET auction_offer_special  =  1
                            WHERE PK_auction_offer_id = " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];

                    if( !($result = $db->sql_query($sql)) )
                         {
                              message_die(GENERAL_ERROR, 'Could not update offer-table', '', __LINE__, __FILE__, $sql);
                         }

                    $message = $lang['auction_offer_feature_successful'] . "<br /><br />" . sprintf($lang['Click_return_offer'], "<a href=\"" . append_sid("auction_offer_view.$phpEx?ao=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL]) . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx") . "\">", "</a>");
                    message_die(GENERAL_MESSAGE, $message);

                 break;  // feature

             case 'search':

                     $page_title = $lang['Search'];
                     include('./includes/page_header.php');

                     if (empty($HTTP_POST_VARS['auction_item']) && empty($HTTP_POST_VARS['auction_username']) && empty($HTTP_POST_VARS[POST_USER_URL]))
                          {
                               message_die(GENERAL_MESSAGE, $lang['auction_neither_item_nor_username_selected']);
                          }
                     // search for item and username
                     if ( !empty($HTTP_POST_VARS['auction_item']) && !empty($HTTP_POST_VARS['auction_username']))
                          {
                                $sql = "SELECT o.PK_auction_offer_id,
                                               o.auction_offer_title,
                                               o.auction_offer_time_start,
                                               o.auction_offer_time_stop,
                                               o.auction_offer_state,
                                               u.username
                                        FROM (" . AUCTION_OFFER_TABLE . " o
                                        LEFT JOIN " . USERS_TABLE . " u ON u.user_id = o.FK_auction_offer_user_id)
                                        WHERE o.auction_offer_title LIKE '%" . $HTTP_POST_VARS['auction_item'] . "%' AND
                                              u.username='" . $HTTP_POST_VARS['auction_username'] . "' AND
                                              o.auction_offer_paid = 1 AND
                                              o.auction_offer_time_stop>" . time() . " AND
                                              o.auction_offer_time_start<" . time() . " AND
                                              o.auction_offer_state<>2";
                          }
                     // search just for item
                     elseif ( !empty($HTTP_POST_VARS['auction_item']) )
                          {
                                $sql = "SELECT o.PK_auction_offer_id,
                                               o.auction_offer_title as auction_offer_title,
                                               o.auction_offer_time_start,
                                               o.auction_offer_time_stop,
                                               o.auction_offer_state,
                                               u.username
                                        FROM (" . AUCTION_OFFER_TABLE . " o
                                        LEFT JOIN " . USERS_TABLE . " u ON u.user_id = o.FK_auction_offer_user_id)
                                        WHERE o.auction_offer_title LIKE '%" . $HTTP_POST_VARS['auction_item'] . "%' AND
                                              o.auction_offer_time_start<" . time() . " AND
                                              o.auction_offer_state<>2 AND
                                              o.auction_offer_paid = 1 AND
                                              o.auction_offer_time_stop>" . time() . "";
                          }
                     // search just for username
                     elseif ( !empty($HTTP_POST_VARS['auction_username']) )
                          {
                                $sql = "SELECT o.PK_auction_offer_id,
                                               o.auction_offer_title,
                                               o.auction_offer_time_start,
                                               o.auction_offer_time_stop,
                                               o.auction_offer_state,
                                               u.username
                                        FROM (" . AUCTION_OFFER_TABLE . " o
                                        LEFT JOIN " . USERS_TABLE . " u ON u.user_id = o.FK_auction_offer_user_id)
                                        WHERE u.username='" . $HTTP_POST_VARS['auction_username'] . "' AND
                                              o.auction_offer_time_start<" . time() . " AND
                                              o.auction_offer_state<>2 AND
                                              o.auction_offer_paid = 1 AND
                                              o.auction_offer_time_stop>" . time() . "
                                        ORDER BY o.auction_offer_time_start";
                          }
                     // search for user_id
                     if ( !empty($HTTP_POST_VARS[POST_USER_URL]))
                          {
                                $sql = "SELECT o.PK_auction_offer_id,
                                               o.auction_offer_title,
                                               o.auction_offer_time_start,
                                               o.auction_offer_time_stop,
                                               o.auction_offer_state,
                                               u.username
                                        FROM (" . AUCTION_OFFER_TABLE . " o
                                        LEFT JOIN " . USERS_TABLE . " u ON u.user_id = o.FK_auction_offer_user_id)
                                        WHERE u.user_id='" . $HTTP_POST_VARS[POST_USER_URL] . "' AND
                                              o.FK_auction_offer_user_id='" . $HTTP_POST_VARS[POST_USER_URL] . "' AND
                                              o.auction_offer_time_start<" . time() . " AND
                                              o.auction_offer_state<>2 AND
                                              o.auction_offer_paid = 1 AND
                                              o.auction_offer_time_stop>" . time() . "
                                        ORDER BY o.auction_offer_time_start";
                          } // if

                     if( !($result = $db->sql_query($sql)) )
                          {
                               message_die(GENERAL_ERROR, 'Could not query search-data', '', __LINE__, __FILE__, $sql);
                          } // if

                     while ($auction_search_row = $db->sql_fetchrow($result))
                          {
                                 $auction_search_matches[] = $auction_search_row;
                          } // while
                 
                     $template->set_filenames(array('body' => 'auction_search.tpl'));

                     $template->assign_vars(array(
                         'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
                         'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
                         'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
                         'L_ONLINE_EXPLAIN' => $lang['Online_explain'],
                         'AUCTION_SEARCH_MATCHES_TITLE' => 'Searchmatches',
                         'L_AUCTION_SEARCH_MATCHES_OFFER_TIME_START' => $lang['auction_offer_time_start'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_TIME_STOP' => $lang['auction_offer_time_stop'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_TITLE' => $lang['auction_offer_title'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_OFFERER' => $lang['auction_offer_offerer']));

                     if ( count($auction_search_matches) == 0 )
                          {
                               message_die(GENERAL_MESSAGE, $lang['auction_no_offers_found']);
                          } // if
                          
                          
                     for ($i = 0; $i < count($auction_search_matches); $i++)
                           {
                               $template->assign_block_vars('offerrow', array(
                                    'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
                                    'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
                                    'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
                                    'L_ONLINE_EXPLAIN' => $lang['Online_explain'],
                                    'AUCTION_SEARCH_MATCHES_OFFER_TITLE' => $auction_search_matches[$i]['auction_offer_title'],
                                    'AUCTION_SEARCH_MATCHES_OFFER_OFFERER' => $auction_search_matches[$i]['username'],
                                    'AUCTION_SEARCH_MATCHES_OFFER_LINK' => "<a href=\"".append_sid("auction_offer_view.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $auction_search_matches[$i]['PK_auction_offer_id']) . "\">" . $auction_search_matches[$i]['auction_offer_title'] . "</a>",
                                    'AUCTION_SEARCH_MATCHES_OFFER_TIME_START' => create_date($board_config['default_dateformat'], $auction_search_matches[$i]['auction_offer_time_start'], $board_config['board_timezone']),
                                    'AUCTION_SEARCH_MATCHES_OFFER_TIME_STOP' => create_date($board_config['default_dateformat'], $auction_search_matches[$i]['auction_offer_time_stop'], $board_config['board_timezone'])));
                           }

                  include($phpbb_root_path . 'auction/auction_header.'.$phpEx);
                  $template->pparse('body');
                  include($phpbb_root_path . 'auction/auction_footer.'.$phpEx);
                  include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

                  break;

             case 'search_unbidded':

                     $page_title = $lang['Search'];
                     include('./includes/page_header.php');

                     $sql = "SELECT o.PK_auction_offer_id,
                                    o.auction_offer_title,
                                    o.auction_offer_time_start,
                                    o.auction_offer_time_stop,
                                    o.auction_offer_state,
                                    u.username
                             FROM (" . AUCTION_OFFER_TABLE . " o
                             LEFT JOIN " . USERS_TABLE . " u ON u.user_id = o.FK_auction_offer_user_id)
                             WHERE o.auction_offer_last_bid_price='0' AND
                                   o.auction_offer_paid = 1 AND
                                   o.auction_offer_time_stop>" . time() . " AND
                                   o.auction_offer_time_start<" . time() . " AND
                                   o.auction_offer_state<>2";

                     if( !($result = $db->sql_query($sql)) )
                          {
                               message_die(GENERAL_ERROR, 'Could not query search-data', '', __LINE__, __FILE__, $sql);
                          } // if

                     while ($auction_search_row = $db->sql_fetchrow($result))
                          {
                                 $auction_search_matches[] = $auction_search_row;
                          } // while

                     $template->set_filenames(array('body' => 'auction_search.tpl'));

                     $template->assign_vars(array(
                         'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
                         'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
                         'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
                         'L_ONLINE_EXPLAIN' => $lang['Online_explain'],
                         'AUCTION_SEARCH_MATCHES_TITLE' => 'Searchmatches',
                         'L_AUCTION_SEARCH_MATCHES_OFFER_TIME_START' => $lang['auction_offer_time_start'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_TIME_STOP' => $lang['auction_offer_time_stop'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_TITLE' => $lang['auction_offer_title'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_OFFERER' => $lang['auction_offer_offerer']));

                     if ( count($auction_search_matches) == 0 )
                          {
                               message_die(GENERAL_MESSAGE, $lang['auction_no_offers_found']);
                          } // if

                     for ($i = 0; $i < count($auction_search_matches); $i++)
                           {
                               $template->assign_block_vars('offerrow', array(
                                    'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
                                    'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
                                    'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
                                    'L_ONLINE_EXPLAIN' => $lang['Online_explain'],
                                    'AUCTION_SEARCH_MATCHES_OFFER_TITLE' => $auction_search_matches[$i]['auction_offer_title'],
                                    'AUCTION_SEARCH_MATCHES_OFFER_OFFERER' => $auction_search_matches[$i]['username'],
                                    'AUCTION_SEARCH_MATCHES_OFFER_LINK' => "<a href=\"".append_sid("auction_offer_view.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $auction_search_matches[$i]['PK_auction_offer_id']) . "\">" . $auction_search_matches[$i]['auction_offer_title'] . "</a>",
                                    'AUCTION_SEARCH_MATCHES_OFFER_TIME_START' => create_date($board_config['default_dateformat'], $auction_search_matches[$i]['auction_offer_time_start'], $board_config['board_timezone']),
                                    'AUCTION_SEARCH_MATCHES_OFFER_TIME_STOP' => create_date($board_config['default_dateformat'], $auction_search_matches[$i]['auction_offer_time_stop'], $board_config['board_timezone'])));
                           } // for

                   include($phpbb_root_path . 'auction/auction_header.'.$phpEx);
                   $template->pparse('body');
                   include($phpbb_root_path . 'auction/auction_footer.'.$phpEx);
                   include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

                   break;

             case 'search_newoffers':

                     $page_title = $lang['Search'];
                     include('./includes/page_header.php');

                     $sql = "SELECT o.PK_auction_offer_id,
                                    o.auction_offer_title,
                                    o.auction_offer_time_start,
                                    o.auction_offer_time_stop,
                                    o.auction_offer_state,
                                    u.username
                             FROM (" . AUCTION_OFFER_TABLE . " o
                             LEFT JOIN " . USERS_TABLE . " u ON u.user_id = o.FK_auction_offer_user_id)
                             WHERE o.auction_offer_paid = 1 AND
                                   o.auction_offer_time_stop > " . time() . " AND
                                   o.auction_offer_time_start < " . time() . " AND
                                   o.auction_offer_state <> 2 AND
                                   o.auction_offer_time_start > " . $userdata['user_lastvisit'] . "";

                     if( !($result = $db->sql_query($sql)) )
                          {
                               message_die(GENERAL_ERROR, 'Could not query search-data', '', __LINE__, __FILE__, $sql);
                          } // if

                     while ($auction_search_row = $db->sql_fetchrow($result))
                          {
                                 $auction_search_matches[] = $auction_search_row;
                          } // while

                     $template->set_filenames(array('body' => 'auction_search.tpl'));

                     $template->assign_vars(array(
                         'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
                         'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
                         'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
                         'L_ONLINE_EXPLAIN' => $lang['Online_explain'],
                         'AUCTION_SEARCH_MATCHES_TITLE' => 'Searchmatches',
                         'L_AUCTION_SEARCH_MATCHES_OFFER_TIME_START' => $lang['auction_offer_time_start'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_TIME_STOP' => $lang['auction_offer_time_stop'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_TITLE' => $lang['auction_offer_title'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_OFFERER' => $lang['auction_offer_offerer']));

                     if ( count($auction_search_matches) == 0 )
                          {
                               message_die(GENERAL_MESSAGE, $lang['auction_no_offers_found']);
                          } // if

                     for ($i = 0; $i < count($auction_search_matches); $i++)
                           {
                               $template->assign_block_vars('offerrow', array(
                                    'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
                                    'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
                                    'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
                                    'L_ONLINE_EXPLAIN' => $lang['Online_explain'],
                                    'AUCTION_SEARCH_MATCHES_OFFER_TITLE' => $auction_search_matches[$i]['auction_offer_title'],
                                    'AUCTION_SEARCH_MATCHES_OFFER_OFFERER' => $auction_search_matches[$i]['username'],
                                    'AUCTION_SEARCH_MATCHES_OFFER_LINK' => "<a href=\"".append_sid("auction_offer_view.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $auction_search_matches[$i]['PK_auction_offer_id']) . "\">" . $auction_search_matches[$i]['auction_offer_title'] . "</a>",
                                    'AUCTION_SEARCH_MATCHES_OFFER_TIME_START' => create_date($board_config['default_dateformat'], $auction_search_matches[$i]['auction_offer_time_start'], $board_config['board_timezone']),
                                    'AUCTION_SEARCH_MATCHES_OFFER_TIME_STOP' => create_date($board_config['default_dateformat'], $auction_search_matches[$i]['auction_offer_time_stop'], $board_config['board_timezone'])));
                           } // for

                   include($phpbb_root_path . 'auction/auction_header.'.$phpEx);
                   $template->pparse('body');
                   include($phpbb_root_path . 'auction/auction_footer.'.$phpEx);
                   include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

                   break;

             case 'search_user':

                     $page_title = $lang['Search'];
                     include('./includes/page_header.php');
                     include($phpbb_root_path . 'auction/auction_header.'.$phpEx);

                     if ( !isset($HTTP_GET_VARS[POST_USERS_URL]) )
                         {
                                              message_die(GENERAL_ERROR, 'No user set');
                         } // if

                     $sql = "SELECT o.PK_auction_offer_id,
                                               o.auction_offer_title,
                                               o.auction_offer_time_start,
                                               o.auction_offer_time_stop,
                                               o.auction_offer_state,
                                               u.username
                                        FROM (" . AUCTION_OFFER_TABLE . " o
                                        LEFT JOIN " . USERS_TABLE . " u ON u.user_id = o.FK_auction_offer_user_id)
                                        WHERE o.FK_auction_offer_user_id=" . htmlspecialchars($HTTP_GET_VARS[POST_USERS_URL]) . " AND
                                              o.auction_offer_time_stop>" . time() . " AND
                                              o.auction_offer_time_start<" . time() . " AND
                                              o.auction_offer_state<>2";

                     if( !($result = $db->sql_query($sql)) )
                          {
                               message_die(GENERAL_ERROR, 'Could not query search-data', '', __LINE__, __FILE__, $sql);
                          } // if

                     while ($auction_search_row = $db->sql_fetchrow($result))
                          {
                                 $auction_search_matches[] = $auction_search_row;
                          } // while

                     $template->set_filenames(array('body' => 'auction_search.tpl'));

                     $template->assign_vars(array(
                         'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
                         'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
                         'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
                         'L_ONLINE_EXPLAIN' => $lang['Online_explain'],
                         'AUCTION_SEARCH_MATCHES_TITLE' => 'Searchmatches',
                         'L_AUCTION_SEARCH_MATCHES_OFFER_TIME_START' => $lang['auction_offer_time_start'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_TIME_STOP' => $lang['auction_offer_time_stop'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_TITLE' => $lang['auction_offer_title'],
                         'L_AUCTION_SEARCH_MATCHES_OFFER_OFFERER' => $lang['auction_offer_offerer']));

                     if ( count($auction_search_matches) == 0 )
                          {
                               message_die(GENERAL_MESSAGE, $lang['auction_no_offers_found']);
                          } // if


                     for ($i = 0; $i < count($auction_search_matches); $i++)
                           {
                               $template->assign_block_vars('offerrow', array(
                                    'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
                                    'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
                                    'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'),
                                    'L_ONLINE_EXPLAIN' => $lang['Online_explain'],
                                    'AUCTION_SEARCH_MATCHES_OFFER_TITLE' => $auction_search_matches[$i]['auction_offer_title'],
                                    'AUCTION_SEARCH_MATCHES_OFFER_OFFERER' => $auction_search_matches[$i]['username'],
                                    'AUCTION_SEARCH_MATCHES_OFFER_LINK' => "<a href=\"".append_sid("auction_offer_view.$phpEx?" . POST_AUCTION_OFFER_URL . "=" . $auction_search_matches[$i]['PK_auction_offer_id']) . "\">" . $auction_search_matches[$i]['auction_offer_title'] . "</a>",
                                    'AUCTION_SEARCH_MATCHES_OFFER_TIME_START' => create_date($board_config['default_dateformat'], $auction_search_matches[$i]['auction_offer_time_start'], $board_config['board_timezone']),
                                    'AUCTION_SEARCH_MATCHES_OFFER_TIME_STOP' => create_date($board_config['default_dateformat'], $auction_search_matches[$i]['auction_offer_time_stop'], $board_config['board_timezone'])));
                           } // for

                  $template->pparse('body');
                  include($phpbb_root_path . 'auction/auction_footer.'.$phpEx);
                  include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
                  break;

             case 'delete_confirm':

                 $page_title = $lang['auction_confirm_delete'];
                 include('./includes/page_header.php');

                 // Check auction_permission
                 checkPermission('DELETE_OFFER');

                  // registered and auctioneers can only move their own offers
                  $role = getRole();
                  if ( $role == 'registered' OR $role=='auctioneer' )
                      {
                           $sql = "SELECT FK_auction_offer_user_id
                                   FROM " . AUCTION_OFFER_TABLE . "
                                   WHERE PK_auction_offer_id=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];

                           if( !($result = $db->sql_query($sql)) )
                              {
                                   message_die(GENERAL_ERROR, 'Could not query offer-seller', '', __LINE__, __FILE__, $sql);
                              } // if

                           $auction_offer = $db->sql_fetchrow($result);
                           // stop the evil person moving the offer
                           if ( $auction_offer['FK_auction_offer_user_id'] <> $userdata['user_id'] )
                                {
                                     message_die(GENERAL_MESSAGE, $lang['auction_no_permission_delete']);
                                } // if
                      } // if

                 // Check start and stop of offer
                 $sql = "SELECT auction_offer_title, FK_auction_offer_last_bid_user_id
                         FROM " . AUCTION_OFFER_TABLE . "
                         WHERE PK_auction_offer_id= " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not query auction-start and stop date', '', __LINE__, __FILE__, $sql);
                     } // if

                 $auction_corresponding_bids_start_stop_row = $db->sql_fetchrow($result);

                 // if a bid already exists just admins and mods can delete the offer
                 if ( $auction_corresponding_bids_start_stop_row['FK_auction_offer_last_bid_user_id']<>0 AND
                      getRole() <> 'administrator' AND
                      getRole() <> 'moderator' AND
                      $userdata['user_level'] <> ADMIN )
                                {
                                     message_die(GENERAL_MESSAGE, $lang['auction_no_permission_delete_bid_exists']);
                                } // if

                 $template->set_filenames(array('body' => 'auction_confirm_delete.tpl'));

                 $template->assign_vars(array(
                         'L_AUCTION_CONFIRM_DELETE'=> $lang['auction_confirm_delete'],
                         'L_AUCTION_OFFER_TITLE' => $lang['auction_offer_title'],
                         'L_YES' => $lang['yes'],
                         'U_RETURN'  => append_sid("auction_offer_view.php?" . POST_AUCTION_OFFER_URL .  "=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . ""),
                         'U_AUCTION_DELETE_NOW' => append_sid("auction_offer.php?mode=delete&" . POST_AUCTION_OFFER_URL .  "=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . ""),
                         'AUCTION_OFFER_TITLE' => $auction_corresponding_bids_start_stop_row['auction_offer_title'],
                         'L_RETURN' => $lang['cancel'],
                         'L_AUCTION_ABOUT_TO_CONFIRM'=> $lang['auction_offer_confirm_delete_now']));

                  $template->pparse('body');
                  include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
                  break;
                  
             case 'delete':

                  $page_title = $lang['auction_confirm_delete'];
                  include('./includes/page_header.php');

                  // Check auction_permission
                  checkPermission('DELETE_OFFER');

                 // Check if bid exists - this is for cheaters who are not coming via the delete_confirm
                 $sql = "SELECT FK_auction_offer_last_bid_user_id
                         FROM " . AUCTION_OFFER_TABLE . "
                         WHERE PK_auction_offer_id= " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not query auction-start and stop date', '', __LINE__, __FILE__, $sql);
                     } // if

                 $auction_corresponding_bids_start_stop_row = $db->sql_fetchrow($result);

                 // if a bid already exists just admins and mods can delete the offer
                 if ( $auction_corresponding_bids_start_stop_row['FK_auction_offer_last_bid_user_id']<>'' AND
                      getRole() <> 'administrator' AND
                      getRole() <> 'moderator' AND
                      $userdata['user_level'] <> ADMIN )
                                {
                                     message_die(GENERAL_MESSAGE, $lang['auction_no_permission_delete_bid_exists']);
                                } // if

                  // registered and auctioneers can only move their own offers
                  $role = getRole();
                  if ( $role == 'registered' OR $role=='auctioneer' )
                      {
                           $sql = "SELECT FK_auction_offer_user_id
                                   FROM " . AUCTION_OFFER_TABLE . "
                                   WHERE PK_auction_offer_id=" . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];

                           if( !($result = $db->sql_query($sql)) )
                              {
                                   message_die(GENERAL_ERROR, 'Could not query offer-seller', '', __LINE__, __FILE__, $sql);
                              }

                           $auction_offer = $db->sql_fetchrow($result);
                           // stop the evil person moving the offer
                           if ( $auction_offer['FK_auction_offer_user_id'] <> $userdata['user_id'] )
                                {
                                     message_die(GENERAL_MESSAGE, $lang['auction_no_permission_delete']);
                                }
                      }

                     $sql = "SELECT pic_filename
                             FROM ". AUCTION_IMAGE_TABLE ."
                             WHERE pic_auction_id  =  " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];
                                
                     if( !($result = $db->sql_query($sql)) )
			     {
			          message_die(GENERAL_ERROR, 'Could not select pic information', '', __LINE__, __FILE__, $sql);
		             }

                     $del_row = array();
                     while( $row = $db->sql_fetchrow($result) )
			{
				$del_row[] = $row;
			}
			$db->sql_freeresult($result);  // Please check!!! I don't know if we need a freeresult here!!!!

			// we count the pics
			$pic_num = count($del_row);
			if($pic_num > 0)
			{
				for ($i = 0; $i < $pic_num; $i++)
				{
					// now get the filename
					$pic_filename = $del_row[$i]['pic_filename'];
					// check if file exists and delete it from ALL caches (don't forget watermark caches)
					if(@file_exists($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH . $pic_filename))
					{
						@unlink($phpbb_root_path . AUCTION_PICTURE_CACHE_PATH . $pic_filename);
					}
					if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MINI_PATH . $pic_filename))
					{
						@unlink($phpbb_root_path . AUCTION_PICTURE_MINI_PATH . $pic_filename);
					}
					if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MAIN_PATH . $pic_filename))
					{
						@unlink($phpbb_root_path . AUCTION_PICTURE_MAIN_PATH . $pic_filename);
					}
					if(@file_exists($phpbb_root_path . AUCTION_PICTURE_WATERMARK_PATH . $pic_filename))
					{
						@unlink($phpbb_root_path . AUCTION_PICTURE_WATERMARK_PATH . $pic_filename);
					}
					if(@file_exists($phpbb_root_path . AUCTION_PICTURE_MAIN_WATERMARK_PATH . $pic_filename))
					{
						@unlink($phpbb_root_path . AUCTION_PICTURE_MAIN_WATERMARK_PATH . $pic_filename);
					}
					// finally delete it from main upload dir
					if(@file_exists($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename))
					{
						@unlink($phpbb_root_path . AUCTION_PICTURE_UPLOAD_PATH . $pic_filename);
					}
				}
				// And from database
				$sql = "DELETE FROM " . AUCTION_IMAGE_TABLE . "
					WHERE pic_auction_id  =  " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";

				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't delete picture. Please try again.", "", __LINE__, __FILE__, $sql);
				}
			}

                               $sql = "SELECT auction_offer_picture
                                       FROM " . AUCTION_OFFER_TABLE . "
                                       WHERE PK_auction_offer_id = " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";

                               if( !($result = $db->sql_query($sql)) )
                                         {
                                             message_die(GENERAL_ERROR, 'Could not query offer', '', __LINE__, __FILE__, $sql);
                                         }

                               $offer_picture =  $db->sql_fetchrow($result);

                               @unlink(AUCTION_PICTURE_UPLOAD_PATH . $offer_picture['auction_offer_picture']);

                               $sql = "DELETE
                                       FROM " . AUCTION_OFFER_TABLE . "
                                       WHERE PK_auction_offer_id = " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";

                               if( !($result = $db->sql_query($sql)) )
                                         {
                                             message_die(GENERAL_ERROR, 'Could not delete offer in offer-table', '', __LINE__, __FILE__, $sql);
                                         }
                               $sql = "DELETE
                                       FROM " . AUCTION_BID_TABLE . "
                                       WHERE FK_auction_bid_offer_id = " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL] . "";
                               if( !($result = $db->sql_query($sql)) )
                                         {
                                             message_die(GENERAL_ERROR, 'Could not delete corresponding bids', '', __LINE__, __FILE__, $sql);
                                         }

                               // Delete watchlist entries
                               $sql = "DELETE
                                       FROM " . AUCTION_WATCHLIST_TABLE . "
                                       WHERE FK_auction_offer_id = " . $HTTP_GET_VARS[POST_AUCTION_OFFER_URL];

                               if( !($result = $db->sql_query($sql)) )
                                    {
                                         message_die(GENERAL_ERROR, 'Could not delete offer watchlist data', '', __LINE__, __FILE__, $sql);
                                    } // if

                               $message = $lang['auction_offer_successful_deleted'] . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx") . "\">", "</a>");
                               message_die(GENERAL_MESSAGE,  $message);
                               include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

             case 'user_points_pay' :

                 $page_title = $lang['auction_offer_pay_now'];
                 include('./includes/page_header.php');

                               // Charge user for offer
                               $sql = "UPDATE " . USERS_TABLE . "
                                       SET user_points=user_points-" . $HTTP_POST_VARS['total_price'] . "
                                       WHERE user_id=" . $userdata['user_id'] . "";

                               if( !($result = $db->sql_query($sql)) )
                                    {
                                         message_die(GENERAL_ERROR, 'Could not charge for new offer', '', __LINE__, __FILE__, $sql);
                                    } // if

                               // Update offer to active
                               $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                                       SET auction_offer_paid=1
                                       WHERE PK_auction_offer_id=" . $HTTP_POST_VARS['offer_id'] . "";

                               if( !($result = $db->sql_query($sql)) )
                                    {
                                         message_die(GENERAL_ERROR, 'Could not mark offer as paid', '', __LINE__, __FILE__, $sql);
                                    } // if

                      $message = $lang['auction_offer_added_successful'] . "<br /><br />" . sprintf($lang['Click_return_offer'], "<a href=\"" . append_sid("auction_offer_view.$phpEx?ao=" . $HTTP_POST_VARS['offer_id'] ) . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_auction_index'], "<a href=\"" . append_sid("auction.$phpEx") . "\">", "</a>");
                      message_die(GENERAL_MESSAGE, $message);
               break;
                 
             default:
                 message_die(GENERAL_MESSAGE, $lang['No_mode']);
                 break;
         }
}

?>