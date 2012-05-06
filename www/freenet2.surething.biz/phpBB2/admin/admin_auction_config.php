<?php
/***************************************************************************
 *                          admin_auction_config.php
 *                            -------------------
 *   begin                :   January 2004
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
               $module['Auction']['a1_configuration'] = "$file";
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
     $mode = isset($HTTP_GET_VARS['mode']);

     switch ($mode)
          {
              case 'save':

                     $sql = "SELECT *
                             FROM " . AUCTION_CONFIG_TABLE ." ";

                     if(!$result = $db->sql_query($sql))
                             {
                                 message_die(CRITICAL_ERROR, "Could not query auction-config information to update", "", __LINE__, __FILE__, $sql);
                             }
                     else
                             {
                                 while( $row = $db->sql_fetchrow($result) )
                                    {
                                        $config_name = $row['config_name'];
                                        $config_value = $row['config_value'];

                                        $default_config[$config_name] = $config_value;
                                        
                                        $new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];

                                            $sql = "UPDATE " . AUCTION_CONFIG_TABLE . " SET
                                                    config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
                                                    WHERE config_name = '$config_name'";

                                            if( !$db->sql_query($sql) )
                                                 {
                                                      message_die(GENERAL_ERROR, "Failed to update auction-config for $config_name", "", __LINE__, __FILE__, $sql);
                                                 } // if
                                    } // while
                             } // if

                     message_die(GENERAL_MESSAGE, $lang['auction_config_updated_successful']);

                     break;

              default:

                    // Grab all auction-config-data
                    $auction_config_data = init_auction_config();

                    // Test if user_points are active
                    $user_points_module = "TRUE";
                    $sql = "SELECT user_points
                             FROM " . USERS_TABLE ." ";
                             
                    if(!$result = $db->sql_query($sql))
                             {
                                 $user_points_module = "FALSE";
                             }
                    if ( $user_points_module == "TRUE" )
                         {
                              $template->assign_block_vars('user_points_active',array(
                                      'L_AUCTION_USER_POINTS_ACTIVE' => $lang['auction_user_points_active'],
                                      'L_AUCTION_PAYMENTSYSTEM_USER_POINTS' => $lang['auction_paymentsystem_user_points'],
                                      'AUCTION_PAYMENTSYSTEM_ACTIVATE_USER_POINTS_YES' => ( $auction_config_data['auction_paymentsystem_activate_user_points'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_PAYMENTSYSTEM_ACTIVATE_USER_POINTS_NO' => ( !$auction_config_data['auction_paymentsystem_activate_user_points'] ) ? "checked=\"checked\"" : ""));
                         }
                    else
                              $template->assign_block_vars('user_points_not_active',array(
                                      'L_AUCTION_USER_POINTS_NOT_ACTIVE' => $lang['auction_user_points_not_active']));

                    $auction_block_display_close_to_end_yes = ( $auction_config_data['auction_block_display_close_to_end'] ) ? "checked=\"checked\"" : "";
                    $auction_block_display_close_to_end_no = ( !$auction_config_data['auction_block_display_close_to_end'] ) ? "checked=\"checked\"" : "";
                    $auction_block_display_ticker_yes = ( $auction_config_data['auction_block_display_ticker'] ) ? "checked=\"checked\"" : "";
                    $auction_block_display_ticker_no = ( !$auction_config_data['auction_block_display_ticker'] ) ? "checked=\"checked\"" : "";

                    $currency_select = currency_format_select($auction_config_data['currency'], 'currency');
                    $forum_select = forum_select($auction_config_data['auction_news_forum_id'], 'auction_news_forum_id');
                    $pseudo_cron_select = pseudo_cron_select($auction_config_data['auction_pseudo_cron_frequence'], 'auction_pseudo_cron_frequence');
                    
                    $template->set_filenames(array('body' => 'admin/admin_auction_config_body.tpl'));

                    $template->assign_vars(array(
                                      'L_AUCTION_CONFIG' => $lang['auction_config'],
                                      'L_AUCTION_CONFIG_EXPLAIN' => $lang['auction_config_explain'],
                                      'L_SUBMIT' => $lang['auction_config_submit'],
                                      'L_AUCTION_GENERAL_SETTINGS' => $lang['auction_general_settings'],
                                      'L_AUCTION_CONFIG_CURRENCY' => $lang['auction_config_currency'],
                                      'L_AUCTION_CONFIG_DISABLE' => $lang['auction_config_disable'],
                                      'L_AUCTION_CONFIG_DISABLE_EXPLAIN' => $lang['auction_config_disable_explain'],
                                      'L_AUCTION_CONFIG_DISABLE_OFFER' => $lang['auction_config_disable_offer'],
                                      'L_AUCTION_CONFIG_DISABLE_OFFER_EXPLAIN' => $lang['auction_config_disable_offer_explain'],
                                      'L_AUCTION_CONFIG_CURRENCY_EXPLAIN' => $lang['auction_config_currency_explain'],
                                      'L_AUCTION_CLOSE_TO_END_NUM' => $lang['auction_close_to_end_num'],
                                      'L_AUCTION_CLOSE_TO_END_NUM_EXPLAIN' => $lang['auction_close_to_end_num_explain'],
                                      'L_AUCTION_BLOCK_CLOSE_TO_END' => $lang['auction_block_close_to_end'],
                                      'L_AUCTION_BLOCK_AUCTION_ROOMS' => $lang['auction_block_auction_rooms'],
                                      'L_AUCTION_BLOCK_STATISTICS' => $lang['auction_block_statistics'],
                                      'L_AUCTION_BLOCK_MYAUCTIONS' => $lang['auction_block_myauctions'],
                                      'L_AUCTION_BLOCK_CALENDAR' => $lang['auction_block_calendar'],
                                      'L_AUCTION_BLOCK_TICKER' => $lang['auction_block_ticker'],
                                      'L_AUCTION_BLOCK_SEARCH' => $lang['auction_block_search'],
                                      'L_AUCTION_BLOCK_CLOSE_TO_END_EXPLAIN' => $lang['auction_block_close_to_end_explain'],
                                      'L_AUCTION_BLOCK_AUCTION_ROOMS_EXPLAIN' => $lang['auction_block_auction_rooms_explain'],
                                      'L_AUCTION_BLOCK_STATISTICS_EXPLAIN' => $lang['auction_block_statistics_explain'],
                                      'L_AUCTION_BLOCK_MYAUCTIONS_EXPLAIN' => $lang['auction_block_myauctions_explain'],
                                      'L_AUCTION_BLOCK_CALENDAR_EXPLAIN' => $lang['auction_block_calendar_explain'],
                                      'L_AUCTION_BLOCK_TICKER_EXPLAIN' => $lang['auction_block_ticker_explain'],
                                      'L_AUCTION_BLOCK_SEARCH_EXPLAIN' => $lang['auction_block_search_explain'],
                                      'L_AUCTION_BLOCK_SPECIAL' => $lang['auction_block_special'],
                                      'L_AUCTION_BLOCK_SPECIAL_EXPLAIN' => $lang['auction_block_special_explain'],
                                      'L_AUCTION_BLOCK_PRICE_INFO' => $lang['auction_block_price_info'],
                                      'L_AUCTION_BLOCK_PRICE_INFO_EXPLAIN' => $lang['auction_block_price_info_explain'],
                                      'L_AUCTION_BLOCK_NEWS' => $lang['auction_block_news'],
                                      'L_AUCTION_BLOCK_NEWS_EXPLAIN' => $lang['auction_block_news_explain'],
                                      'L_AUCTION_BLOCK_NEWS_FORUM_ID' => $lang['auction_block_news_forum_id'],
                                      'L_AUCTION_BLOCK_NEWS_FORUM_ID_EXPLAIN' => $lang['auction_block_news_forum_id_explain'],
                                      'L_AUCTION_BLOCK_DROP_DOWN_AUCTION_ROOMS' => $lang['auction_block_drop_down_auction_rooms'],
                                      'L_AUCTION_BLOCK_DROP_DOWN_AUCTION_ROOMS_EXPLAIN' => $lang['auction_block_drop_down_auction_rooms_explain'],
                                      'L_AUCTION_OFFER_AMOUNT_MIN'  => $lang['auction_offer_amount_min'],
                                      'L_AUCTION_OFFER_AMOUNT_MIN_EXPLAIN' => $lang['auction_offer_amount_min_explain'],
                                      'L_AUCTION_OFFER_AMOUNT_MAX' => $lang['auction_offer_amount_max'],
                                      'L_AUCTION_OFFER_AMOUNT_MAX_EXPLAIN' => $lang['auction_offer_amount_max_explain'],
                                      'L_AUCTION_OFFER_DETAILS' => $lang['auction_offer_details'],
                                      'L_AUCTION_BLOCK_SETTING' => $lang['auction_block_settings'],
                                      'L_AUCTION_BLOCK_LAST_BIDS' => $lang['auction_block_last_bids'],
                                      'L_AUCTION_BLOCK_LAST_BIDS_EXPLAIN' => $lang['auction_block_last_bids_explain'],
                                      'L_AUCTION_BLOCK_LAST_BIDS_LIMIT' => $lang['auction_block_last_bids_limit'],
                                      'L_AUCTION_BLOCK_LAST_BIDS_LIMIT_EXPLAIN'=> $lang['auction_block_last_bids_limit_explain'],

                                      'L_AUCTION_ROOM_PAGINATION' => $lang['auction_room_pagination'],
                                      'L_AUCTION_ROOM_PAGINATION_EXPLAIN' => $lang['auction_room_pagination_explain'],

                                      'L_AUCTION_COSTS' => $lang['auction_costs'],
                                      'L_AUCTION_OFFER DETAILS' => $lang['auction_offer_details'],
                                      'L_AUCTION_OFFER_ALLOW_BOLD' => $lang['auction_offer_allow_bold'],
                                      'L_AUCTION_OFFER_ALLOW_ON_TOP' => $lang['auction_offer_allow_on_top'],
                                      'L_AUCTION_OFFER_ALLOW_SPECIAL' => $lang['auction_offer_allow_special'],
                                      'L_AUCTION_OFFER_FINAL_PERCENT' => $lang['auction_offer_allow_final_percent'],
                                      'L_AUCTION_OFFER_FINAL_PERCENT_EXPLAIN' => $lang['auction_offer_allow_final_percent_explain'],
                                      'L_AUCTION_OFFER_ALLOW_DIRECT_SELL' => $lang['auction_offer_allow_direct_sell'],
                                      'L_AUCTION_OFFER_COST_BOLD' => $lang['auction_offer_cost_bold'],
                                      'L_AUCTION_OFFER_COST_BASIC' => $lang['auction_offer_cost_basic'],
                                      'L_AUCTION_OFFER_COST_ON_TOP' => $lang['auction_offer_cost_on_top'],
                                      'L_AUCTION_OFFER_COST_SPECIAL' => $lang['auction_offer_cost_special'],
                                      'L_AUCTION_OFFER_COST_DIRECT_SELL' => $lang['auction_offer_cost_direct_sell'],
                                      'L_AUCTION_SHOW_TIMELINE' => $lang['auction_show_timline'],
                                      'L_AUCTION_ALLOW_COMMENT' => $lang['auction_allow_comment'],
                                      'L_AUCTION_ALLOW_CHANGE_COMMENT' => $lang['auction_allow_change_comment'],
                                      'L_AUCTION_PM_NOTIFY' => $lang['auction_pm_notify'],
                                      'L_AUCTION_PM_NOTIFY_EXPLAIN' => $lang['auction_pm_notify_explain'],
                                      'L_AUCTION_EMAIL_NOTIFY' => $lang['auction_email_notify'],
                                      'L_AUCTION_EMAIL_NOTIFY_EXPLAIN' => $lang['auction_email_notify_explain'],
                                      
                                      'L_AUCTION_SHIPPING_ALLOW' => $lang['auction_offer_allow_shipping'],
                                      'L_AUCTION_PAYMENTSYSTEM' => $lang['auction_paymentsystem'],
                                      'L_AUCTION_PAYMENTSYSTEM_PAYPAL_EMAIL' => $lang['auction_paymentsystem_paypal_email'],
                                      'L_AUCTION_PAYMENTSYSTEM_PAYPAL_EMAIL_EXPLAIN' => $lang['auction_paymentsystem_paypal_email_explain'],
                                      'L_AUCTION_PAYMENTSYSTEM_ACTIVATE_PAYPAL' => $lang['auction_paymentsystem_activate_paypal'],
                                      'L_AUCTION_PAYMENTSYSTEM_ACTIVATE_PAYPAL_EXPLAIN' => $lang['auction_paymentsystem_activate_paypal'],
                                      'L_AUCTION_PAYMENTSYSTEM_MONEYBOOKER_EMAIL' => $lang['auction_paymentsystem_moneybooker_email'],
                                      'L_AUCTION_PAYMENTSYSTEM_MONEYBOOKER_EMAIL_EXPLAIN' => $lang['auction_paymentsystem_moneybooker_email_explain'],
                                      'L_AUCTION_PAYMENTSYSTEM_ACTIVATE_MONEYBOOKER' => $lang['auction_paymentsystem_activate_moneybooker'],
                                      'L_AUCTION_PAYMENTSYSTEM_ACTIVATE_MONEYBOOKER_EXPLAIN' => $lang['auction_paymentsystem_activate_moneybooker'],

                                      'L_AUCTION_PAYMENTSYSTEM_ACTIVATE_DEBIT' => $lang['auction_paymentsystem_activate_debit'],
                                      'L_AUCTION_PAYMENTSYSTEM_ACTIVATE_DEBIT_EXPLAIN' => $lang['auction_paymentsystem_activate_debit_explain'],
                                      'AUCTION_PAYMENTSYSTEM_ACTIVATE_DEBIT_YES' => ( $auction_config_data['auction_paymentsystem_activate_debit'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_PAYMENTSYSTEM_ACTIVATE_DEBIT_NO' => ( !$auction_config_data['auction_paymentsystem_activate_debit'] ) ? "checked=\"checked\"" : "",

                                      'L_AUCTION_COUPONS_ALLOW' => $lang['auction_coupons_allow'],
                                      'L_AUCTION_SELFBIDS_ALLOW' => $lang['auction_selfbids_allow'],
                                      'L_AUCTION_BLOCK_SPECIALS_LIMIT' => $lang['auction_block_specials_limit'],
                                      'L_AUCTION_BLOCK_SPECIALS_LIMIT_EXPLAIN' => $lang['auction_block_specials_limit_explain'],
                                      'L_AUCTION_END_NOTIFY' => $lang['auction_end_notify'],
                                      'L_AUCTION_END_NOTIFY_EMAIL' => $lang['auction_end_notify_email'],
                                      'L_AUCTION_END_NOTIFY_EMAIL_EXPLAIN' => $lang['auction_end_notify_email_explain'],
                                      'L_AUCTION_END_NOTIFY_PM' => $lang['auction_end_notify_pm'],
                                      'L_AUCTION_END_NOTIFY_PM_EXPLAIN' => $lang['auction_end_notify_pm_explain'],

                                      'L_AUCTION_PSEUDO_CRON' => $lang['auction_pseudo_cron'],
                                      'L_AUCTION_PSEUDO_CRON_EXPLAIN' => $lang['auction_pseudo_cron_explain'],
                                      'L_AUCTION_PSEUDO_CRON_FREQEUNCE' => $lang['auction_pseudo_cron_frequence'],
                                      'L_AUCTION_PSEUDO_CRON_FREQEUNCE_EXPLAIN' => $lang['auction_pseudo_cron_frequence_explain'],
                                      'L_AUCTION_PSEUDO_CRON_FREQEUNCE_M' => $lang['auction_pseudo_cron_frequence_m'],
                                      'L_AUCTION_PSEUDO_CRON_FREQEUNCE_H' => $lang['auction_pseudo_cron_frequence_h'],
                                      'L_AUCTION_PSEUDO_CRON_FREQEUNCE_D' => $lang['auction_pseudo_cron_frequence_d'],
                                      'L_AUCTION_PSEUDO_CRON_FREQEUNCE_W' => $lang['auction_pseudo_cron_frequence_w'],

                                      'L_YES' => $lang['Yes'],
                                      'L_NO' => $lang['No'],

                                      'AUCTION_END_NOTIFY_EMAIL_YES' => ( $auction_config_data['auction_end_notify_email']) ? "checked=\"checked\"" : "",
                                      'AUCTION_END_NOTIFY_EMAIL_NO' =>( !$auction_config_data['auction_end_notify_email'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_END_NOTIFY_PM_YES' => ( $auction_config_data['auction_end_notify_pm']) ? "checked=\"checked\"" : "",
                                      'AUCTION_END_NOTIFY_PM_NO' =>( !$auction_config_data['auction_end_notify_pm'] ) ? "checked=\"checked\"" : "",

                                      'CURRENCY_DD' => $currency_select,
                                      'NEWS_FORUM' => $forum_select,
                                      'PSEUDO_CRON_FREQUENCE' => $pseudo_cron_select,
                                      
                                      'AUCTION_CLOSE_TO_END_NUM' => $auction_config_data['auction_config_close_to_end_number'],
                                      'AUCTION_OFFER_AMOUNT_MAX' => $auction_config_data['auction_offer_amount_max'],
                                      'AUCTION_OFFER_AMOUNT_MIN' => $auction_config_data['auction_offer_amount_min'],
                                      'AUCTION_OFFER_COST_BASIC' => $auction_config_data['auction_offer_cost_basic'],
                                      'AUCTION_OFFER_COST_BOLD' => $auction_config_data['auction_offer_cost_bold'],
                                      'AUCTION_OFFER_COST_ON_TOP' => $auction_config_data['auction_offer_cost_on_top'],
                                      'AUCTION_OFFER_COST_SPECIAL' => $auction_config_data['auction_offer_cost_special'],
                                      'AUCTION_OFFER_COST_DIRECT_SELL' => $auction_config_data['auction_offer_cost_direct_sell'],
                                      'AUCTION_OFFER_COST_FINAL_PERCENT' => $auction_config_data['auction_offer_cost_final_percent'],
                                      'AUCTION_ROOM_PAGINATION' => $auction_config_data['auction_room_pagination'],
                                      'AUCTION_SHOW_TIMELINE_YES' => ( $auction_config_data['auction_show_timeline']) ? "checked=\"checked\"" : "",
                                      'AUCTION_SHOW_TIMELINE_NO' => ( !$auction_config_data['auction_show_timeline']) ? "checked=\"checked\"" : "",
                                      'AUCTION_ALLOW_COMMENT_YES' => ( $auction_config_data['auction_allow_comment']) ? "checked=\"checked\"" : "",
                                      'AUCTION_ALLOW_COMMENT_NO' => ( !$auction_config_data['auction_allow_comment']) ? "checked=\"checked\"" : "",
                                      'AUCTION_ALLOW_CHANGE_COMMENT_YES' => ( $auction_config_data['auction_allow_change_comment']) ? "checked=\"checked\"" : "",
                                      'AUCTION_ALLOW_CHANGE_COMMENT_NO' => ( !$auction_config_data['auction_allow_change_comment']) ? "checked=\"checked\"" : "",

                                      'AUCTION_BLOCK_DISPLAY_CLOSE_TO_END_YES' => $auction_block_display_close_to_end_yes,
                                      'AUCTION_BLOCK_DISPLAY_CLOSE_TO_END_NO' => $auction_block_display_close_to_end_no,
                                      'AUCTION_BLOCK_DISPLAY_TICKER_YES' => $auction_block_display_ticker_yes,
                                      'AUCTION_BLOCK_DISPLAY_TICKER_NO' => $auction_block_display_ticker_no,
                                      'AUCTION_BLOCK_DISPLAY_NEWS_YES' => ( $auction_config_data['auction_block_display_news'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_NEWS_NO' => ( !$auction_config_data['auction_block_display_news'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_AUCTION_ROOMS_YES' => ( $auction_config_data['auction_block_display_auction_rooms'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_AUCTION_ROOMS_NO' => ( !$auction_config_data['auction_block_display_auction_rooms'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_DROP_DOWN_AUCTION_ROOMS_YES' => ( $auction_config_data['auction_block_display_drop_down_auction_rooms'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_DROP_DOWN_AUCTION_ROOMS_NO' => ( !$auction_config_data['auction_block_display_drop_down_auction_rooms'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_PRICE_INFO_YES' => ( $auction_config_data['auction_block_display_priceinformation'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_PRICE_INFO_NO' => ( !$auction_config_data['auction_block_display_priceinformation'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_SPECIAL_YES' => ( $auction_config_data['auction_block_display_specials'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_SPECIAL_NO' => ( !$auction_config_data['auction_block_display_specials'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_STATISTICS_YES' => ( $auction_config_data['auction_block_display_statistics'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_STATISTICS_NO' => ( !$auction_config_data['auction_block_display_statistics'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_LAST_BIDS_YES' => ( $auction_config_data['auction_block_display_last_bids'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_LAST_BIDS_NO' => ( !$auction_config_data['auction_block_display_last_bids'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_MYAUCTIONS_YES' => ( $auction_config_data['auction_block_display_myauctions'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_MYAUCTIONS_NO' => ( !$auction_config_data['auction_block_display_myauctions'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_CALENDAR_YES' => ( $auction_config_data['auction_block_display_calendar'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_CALENDAR_NO' => ( !$auction_config_data['auction_block_display_calendar'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_SEARCH_YES' => ( $auction_config_data['auction_block_display_search'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_DISPLAY_SEARCH_NO' => ( !$auction_config_data['auction_block_display_search'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_SPECIALS_LIMIT' => $auction_config_data['auction_block_specials_limit'],

                                      'L_AUCTION_BLOCK_NEWEST_OFFER' => $lang['auction_block_newest_offer'],
                                      'L_AUCTION_BLOCK_NEWEST_OFFER_EXPLAIN' => $lang['auction_block_newest_offer_explain'],
                                      'L_AUCTION_BLOCK_NEWEST_OFFER_NUMBER' => $lang['auction_block_newest_offer_number'],
                                      'AUCTION_BLOCK_NEWEST_OFFER_YES' => ( $auction_config_data['auction_block_display_newest_offers'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_NEWEST_OFFER_NO' => ( !$auction_config_data['auction_block_display_newest_offers'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_BLOCK_NEWEST_OFFER_LIMIT' => $auction_config_data['auction_config_newest_offers_number'],

                                      'AUCTION_BLOCK_LAST_BIDS_LIMIT' => $auction_config_data['auction_config_last_bids_number'],
                                      'AUCTION_OFFER_ALLOW_BOLD_YES' => ( $auction_config_data['auction_offer_allow_bold'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_OFFER_ALLOW_BOLD_NO' => ( !$auction_config_data['auction_offer_allow_bold'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_OFFER_ALLOW_ON_TOP_YES' => ( $auction_config_data['auction_offer_allow_on_top'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_OFFER_ALLOW_ON_TOP_NO' => ( !$auction_config_data['auction_offer_allow_on_top'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_OFFER_ALLOW_SPECIAL_YES' => ( $auction_config_data['auction_offer_allow_special'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_OFFER_ALLOW_SPECIAL_NO' => ( !$auction_config_data['auction_offer_allow_special'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_OFFER_ALLOW_DIRECT_SELL_YES' => ( $auction_config_data['auction_allow_direct_sell'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_OFFER_ALLOW_DIRECT_SELL_NO' => ( !$auction_config_data['auction_allow_direct_sell'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_COUPONS_ALLOW_NO' => ( !$auction_config_data['auction_allow_coupons'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_COUPONS_ALLOW_YES' => ( $auction_config_data['auction_allow_coupons'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_SELFBIDS_ALLOW_NO' => ( !$auction_config_data['auction_allow_self_bids'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_SELFBIDS_ALLOW_YES' => ( $auction_config_data['auction_allow_self_bids'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_SHIPPING_ALLOW_NO' => ( !$auction_config_data['auction_offer_allow_shipping'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_SHIPPING_ALLOW_YES' => ( $auction_config_data['auction_offer_allow_shipping'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_PM_NOTIFY_YES' => ( $auction_config_data['auction_pm_notify'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_PM_NOTIFY_NO' => ( !$auction_config_data['auction_pm_notify'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_EMAIL_NOTIFY_YES' => ( $auction_config_data['auction_email_notify'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_EMAIL_NOTIFY_NO' => ( !$auction_config_data['auction_email_notify'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_PSEUDO_CRON_YES' => ( $auction_config_data['auction_pseudo_cron'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_PSEUDO_CRON_NO' => ( !$auction_config_data['auction_pseudo_cron'] ) ? "checked=\"checked\"" : "",

                                      'AUCTION_PAYMENTSYSTEM_ACTIVATE_PAYPAL_YES' => ( $auction_config_data['auction_paymentsystem_activate_paypal'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_PAYMENTSYSTEM_ACTIVATE_PAYPAL_NO' => ( !$auction_config_data['auction_paymentsystem_activate_paypal'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_PAYMENTSYSTEM_ACTIVATE_MONEYBOOKER_YES' => ( $auction_config_data['auction_paymentsystem_activate_moneybooker'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_PAYMENTSYSTEM_ACTIVATE_MONEYBOOKER_NO' => ( !$auction_config_data['auction_paymentsystem_activate_moneybooker'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_DISABLE_YES' => ( $auction_config_data['auction_disable'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_DISABLE_NO' => ( !$auction_config_data['auction_disable'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_OFFER_DISABLE_YES' => ( $auction_config_data['auction_offer_disable'] ) ? "checked=\"checked\"" : "",
                                      'AUCTION_OFFER_DISABLE_NO' => ( !$auction_config_data['auction_offer_disable'] ) ? "checked=\"checked\"" : "",

                                      'AUCTION_PAYMENTSYSTEM_PAYPAL_EMAIL' => $auction_config_data['auction_paymentsystem_paypal_email'],
                                      'AUCTION_PAYMENTSYSTEM_MONEYBOOKER_EMAIL' => $auction_config_data['auction_paymentsystem_moneybooker_email'],

                                      'S_AUCTION_CONFIG_ACTION' => append_sid("admin_auction_config.php?mode=save")));
                                  
                    $template->pparse("body");
                 break;
          } // switch
include('./page_footer_admin.'.$phpEx);

?>