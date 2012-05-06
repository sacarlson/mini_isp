<?php
/***************************************************************************
 *                          admin_auction_config.php
 *                            -------------------
 *   begin                :   January 2004
 *   copyright            :   EklipzeDesigns
 *   email                :   enquires@eklipzedesigns.com
 *   Compiled for         :   phpbb-auction.com
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


// Currency Select List
function currency_format_select($default, $select_name = 'currency')
{
  global $auction_config_data, $lang, $board_config;
  if ( $board_config['points_name'] )
       {
            $points = $board_config['points_name'];
            $currency_formats = array('USD', 'CAD', 'EUR', 'GBP', 'JPY', $points);
            $currency_display = array($lang['Currency_us'], $lang['Currency_cad'], $lang['Currency_eur'], $lang['Currency_gbp'], $lang['Currency_jpy'], $points);
       }
  else
       {
            $currency_formats = array('USD', 'CAD', 'EUR', 'GBP', 'JPY');
            $currency_display = array($lang['Currency_us'], $lang['Currency_cad'], $lang['Currency_eur'], $lang['Currency_gbp'], $lang['Currency_jpy']);
       }



  $ac_select = '<select name="' . $select_name . '">';
  for ($i = 0; $i < sizeof($currency_formats); $i++)
       {
            $format = $currency_formats[$i];
            $display = $currency_display[$i];
            $ac_select .= "<option value='" . $format . "'";
            if (isset($default) && ($default == $format))
                 {
                       $ac_select .= ' selected';
                 }
            $ac_select .= '>&nbsp;' . $display . '</option>';
       }
  $ac_select .= '</select>';
  return $ac_select;
}

// Pick a forum for news
function forum_select($default, $select_name = 'auction_news_forum_id')
{  
  global $db;
  
  $sql = "SELECT f.* 
          FROM " . FORUMS_TABLE . " f
          ORDER BY f.forum_name ASC";
          
  if ( !($result = $db->sql_query($sql)) )
       {
            // Must be excluded for s-version. in case no forum is found the drop-down in the acp will be empty
            //message_die(GENERAL_ERROR, "Couldn't get list of Forums", "", __LINE__, __FILE__, $sql);
       }

  $forum_select ='<select name="' . $select_name . '">';
  while ( $row = $db->sql_fetchrow($result) )
        {
              $selected = ( $row['forum_id'] == $default ) ? ' selected="selected"' : '';
              $forum_select .= '<option value="' . $row['forum_id'] . '"' . $selected . '>' . $row['forum_name'] . '</option>';
        }
  $forum_select .= '</select>';
  return $forum_select;
}  

// Get pseudo-cron selects
function pseudo_cron_select($default, $select_name = 'auction_pseudo_cron_frequence')
{
  global $auction_config_data, $lang, $board_config;

     $pseudo_cron_formats = array('m','h','d','w');
     $pseudo_cron_display = array($lang['auction_pseudo_cron_frequence_m'],
                                  $lang['auction_pseudo_cron_frequence_h'],
                                  $lang['auction_pseudo_cron_frequence_d'],
                                  $lang['auction_pseudo_cron_frequence_w']);

  $pseudo_cron_select = '<select name="' . $select_name . '">';
  for ($i = 0; $i < sizeof($pseudo_cron_formats); $i++)
       {
            $format = $pseudo_cron_formats[$i];
            $display = $pseudo_cron_display[$i];
            $pseudo_cron_select .= "<option value='" . $format . "'";
            if (isset($default) && ($default == $format))
                 {
                       $pseudo_cron_select .= ' selected';
                 }
            $pseudo_cron_select .= '>&nbsp;' . $display . '</option>';
       }
  $pseudo_cron_select .= '</select>';
  return $pseudo_cron_select;
}

?>