<?php
/***************************************************************************
 *                           auction_constants.php
 *                            -------------------
 *   begin                :   January 2004
 *   copyright            :   (C) FR
 *   email                :   fr@php-styles.com
 *
 *   Last Update          :   AUG 2004 - FR
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This hack is released under the GPL License. 
 *   This hack can be freely used, but not distributed, without permission. 
 *   Intellectual Property is retained by the author listed above. 
 *
 ***************************************************************************/

     define('SHOW_ONLINE', true);
     if ( !isset($phpbb_root_path))
          {
            $phpbb_root_path= './';
          }
     include_once($phpbb_root_path . 'extension.inc');
     include_once($phpbb_root_path . 'common.'.$phpEx);
     include_once($phpbb_root_path . 'auction/functions_blocks.php');
     include_once($phpbb_root_path . 'auction/functions_general.php');
     include_once($phpbb_root_path . 'auction/functions_validate.php');
     include_once($phpbb_root_path . 'auction/auction_constants.php');
     include_once($phpbb_root_path . 'includes/functions_post.php');

     // START Include language file
     $language = $board_config['default_lang'];
     if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_auction.'.$phpEx) )
          {
               $language = 'english';
          }
     include($phpbb_root_path . 'language/lang_' . $language . '/lang_auction.' . $phpEx);
     include($phpbb_root_path . 'language/lang_' . $language . '/lang_main.' . $phpEx);
     // END include language file

     // BEGIN include auction-config information
     $auction_config_data = init_auction_config();
     // END include auction-config information

     // Block is auction is disabled
     if ( $auction_config_data['auction_disable'] == 1 AND $userdata['user_level'] <> ADMIN)
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

?>