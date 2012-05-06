<?php
/***************************************************************************
 *                              auction_faq.php
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

     define('IN_PHPBB', true);
     $phpbb_root_path = './';
     include($phpbb_root_path . 'extension.inc');
     include($phpbb_root_path . 'common.'.$phpEx);
     include_once($phpbb_root_path . 'auction/functions_blocks.php');
     include_once($phpbb_root_path . 'auction/functions_general.php');
     include_once($phpbb_root_path . 'auction/functions_validate.php');
     include_once($phpbb_root_path . 'auction/auction_constants.php');

     $auction_config_data = init_auction_config();

     // Start session management
     $userdata = session_pagestart($user_ip, AUCTION_FAQ);
     init_userprefs($userdata);
     // End session management

    // Start Include language file
     $language = $board_config['default_lang'];
     if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_auction.'.$phpEx) )
          {
               $language = 'english';
          }
     include($phpbb_root_path . 'language/lang_' . $language . '/lang_auction.' . $phpEx);
     // end include language file


     // Load the appropriate faq file
     if( isset($HTTP_GET_VARS['mode']) )
          {
              switch( $HTTP_GET_VARS['mode'] )
                   {
                       case 'terms':
                            $lang_file = 'lang_auction_terms';
                            $l_title = $lang['auction_terms'];
                            break;
                       case 'faq':
                            $lang_file = 'lang_auction_faq';
                            $l_title = $lang['auction_faq'];
                            break;
                       default:
                            $lang_file = 'lang_auction_faq';
                            $l_title = $lang['auction_faq'];
                            break;
                   }
          }
     else
         {
              $lang_file = 'lang_auction_faq';
              $l_title = $lang['FAQ'];
         }

     include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/' . $lang_file . '.' . $phpEx);

     // Pull the array data from the lang pack
     $j = 0;
     $counter = 0;
     $counter_2 = 0;
     $faq_block = array();
     $faq_block_titles = array();

     for($i = 0; $i < count($faq); $i++)
     {
         if( $faq[$i][0] != '--' )
         {
             $faq_block[$j][$counter]['id'] = $counter_2;
             $faq_block[$j][$counter]['question'] = $faq[$i][0];
             $faq_block[$j][$counter]['answer'] = $faq[$i][1];

             $counter++;
             $counter_2++;
         }
         else
         {
             $j = ( $counter != 0 ) ? $j + 1 : 0;

             $faq_block_titles[$j] = $faq[$i][1];

             $counter = 0;
         }
     }

     // Lets build a page ...
     $page_title = $l_title;
     include($phpbb_root_path . 'includes/page_header.'.$phpEx);

     $template->set_filenames(array(
         'body' => 'auction_faq_body.tpl'
         ));
//     make_jumpbox('viewforum.'.$phpEx, $forum_id);

     $template->assign_vars(array(
         'L_FAQ_TITLE' => $l_title, 
         'L_BACK_TO_TOP' => $lang['Back_to_top'],
         'MODAUTHOR' => $lang['modauthor'],
         'MODPOWERED' => $lang['modpowered']
         ));

     for($i = 0; $i < count($faq_block); $i++)
     {
         if( count($faq_block[$i]) )
         {
             $template->assign_block_vars('faq_block', array(
                 'BLOCK_TITLE' => $faq_block_titles[$i]));

             $template->assign_block_vars('faq_block_link', array( 
                 'BLOCK_TITLE' => $faq_block_titles[$i]));

             for($j = 0; $j < count($faq_block[$i]); $j++)
             {
                 $row_color = ( !($j % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
                 $row_class = ( !($j % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

                 $template->assign_block_vars('faq_block.faq_row', array(
                     'ROW_COLOR' => '#' . $row_color,
                     'ROW_CLASS' => $row_class,
                     'FAQ_QUESTION' => $faq_block[$i][$j]['question'], 
                     'FAQ_ANSWER' => $faq_block[$i][$j]['answer'], 
                     'U_FAQ_ID' => $faq_block[$i][$j]['id']));

                 $template->assign_block_vars('faq_block_link.faq_row_link', array(
                     'ROW_COLOR' => '#' . $row_color,
                     'ROW_CLASS' => $row_class,
                     'FAQ_LINK' => $faq_block[$i][$j]['question'], 
                     'U_FAQ_LINK' => '#' . $faq_block[$i][$j]['id']));
             }
         }
     }

     $template->pparse('body');
     include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>