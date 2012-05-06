<?php
/***************************************************************************
 *                              auction_footer.php
 *                            -------------------
 *   begin                :   JULY 2004
 *   copyright            :   (C) FR
 *   email                :   fr@php-styles.com
 *
 *   Last Update          :   Dex 2004 - FR
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This hack is released under the GPL License.
 *   This hack can be freely used, but not distributed, without permission.
 *   Intellectual Property is retained by the author listed above.
 *
 ***************************************************************************/

     if ( !defined('IN_PHPBB') )
          {
               die("Hacking attempt");
          }

     define('HEADER_INC', TRUE);
     if ( $auction_config_data['auction_pseudo_cron'] )
          {
               // Start pseudo-cron
               // if last update is < time right now minus pseudo-cron-frequence than run auction_cron.php!
               $bRun = FALSE;
               switch($auction_config_data['auction_pseudo_cron_frequence'])
                    {
                         case 'm':
                              if ( DateAdd(n,1,$auction_config_data['auction_pseudo_cron_last']) < time() )
                                   {
                                        $bRun = TRUE;
                                   }
                              break;
                         case 'h':
                              if ( DateAdd(h,1,$auction_config_data['auction_pseudo_cron_last']) < time() )
                                   {
                                        $bRun = TRUE;
                                   }
                              break;
                         case 'd':
                              if ( DateAdd(d,1,$auction_config_data['auction_pseudo_cron_last']) < time() )
                                   {
                                        $bRun = TRUE;
                                   }
                              break;
                         case 'w':
                              if ( DateAdd(d,7,$auction_config_data['auction_pseudo_cron_last']) < time() )
                                   {
                                        $bRun = TRUE;
                                   }
                              break;
                    }
               // if the last cron has been longer ago than the frequence, run the cron
               if  ( $bRun == TRUE )
                    {
                         // Execute cron
                         include($phpbb_root_path . "auction_cron.php");

                         // update last-cron-time
                         $sql = "UPDATE " . AUCTION_CONFIG_TABLE . "
                                 SET config_value = '" . time() . "'
                                 WHERE config_name='auction_pseudo_cron_last'";

                         if ( !($auction_config_result = $db->sql_query($sql)) )
                              {
                                   message_die(GENERAL_ERROR, 'Could not update last cron time', '', __LINE__, __FILE__, $sql);
                              }  // End if
                    }
               // End pseudo-cron
          }

     $template->set_filenames(array('auction_footer' => 'auction_footer.tpl'));
     $template->assign_vars(array());
     $template->pparse('auction_footer');

?>