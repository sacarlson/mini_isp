<?php
/***************************************************************************
 *                          admin_auction_offer.php
 *                         -------------------------
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
               $module['Auction']['a5_ipn_log'] = append_sid($filename);
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
    $mode = '';
}

if ( $mode == "delete" )
{
    $ipn_log_id = ( isset($HTTP_GET_VARS[POST_IPN_LOG_URL]) ) ? $HTTP_GET_VARS[POST_IPN_LOG_URL] : $HTTP_POST_VARS[POST_IPN_LOG_URL];
    $ipn_log_id = htmlspecialchars($ipn_log_id);

    // Delete the offer
    $sql = "DELETE FROM " . AUCTION_IPN_LOG . "
        WHERE PK_auction_ipn_log_id = '" . $ipn_log_id . "'";

    if( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not delete ipn log', '', __LINE__, __FILE__, $sql);
    } 
}  

if ( $mode == "delete_all" )
{
    // Delete the offer
    $sql = "TRUNCATE TABLE " . AUCTION_IPN_LOG;

    if( !$result = $db->sql_query($sql) )
    {
        message_die(GENERAL_ERROR, 'Could not delete all ipn logs', '', __LINE__, __FILE__, $sql);
    } 
    if( isset($HTTP_POST_VARS['submit']) )
    {
        $message = $lang['ipn_log_updated'] . "<br /><br />" . sprintf($lang['Click_return_ipn_log'], "<a href=\"" . append_sid("admin_auction_ipn_log.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

        message_die(GENERAL_MESSAGE, $message);
    }
} 

// START Grab all the offer-data
$sql = "SELECT *
    FROM " . AUCTION_IPN_LOG;

if( !$result = $db->sql_query($sql) )
{
    message_die(GENERAL_ERROR, 'Could not obatin ipn log data', '', __LINE__, __FILE__, $sql);
} 

$total_ipn_logs = 0;
while( $row = $db->sql_fetchrow($result) )
{
    $ipn_logs_rowset[] = $row;
    $total_ipn_logs++;
} 

$db->sql_freeresult($result);

// Display page
$template->set_filenames(array(
    'body' => 'admin/admin_auction_ipn_log.tpl')
);

if ( $total_ipn_logs < 1 )
{
    $template->assign_block_vars('no_ipn_logs', array(
        'L_NO_IPN_LOGS' => $lang['ipn_log_no'])
    );
}
else
{
    for($i = 0; $i < $total_ipn_logs; $i++)
    {
        $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
        $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

        $template->assign_block_vars('ipn_log', array(
            'ROW_COLOR' => '#' . $row_color,
            'ROW_CLASS' => $row_class,
            'AUCTION_IPN_LOG_DATE' => create_date($board_config['default_dateformat'], $ipn_logs_rowset[$i]['auction_ipn_log_date'], $board_config['board_timezone']),
            'AUCTION_IPN_LOG_STATUS' => $ipn_logs_rowset[$i]['auction_ipn_log_status'],
            'AUCTION_IPN_LOG_OFFER_ID' => $ipn_logs_rowset[$i]['FK_auction_offer_id'],
    
            'U_AUCTION_IPN_LOG_DELETE' => append_sid("admin_auction_ipn_log.php?mode=delete&" . POST_IPN_LOG_URL . "=" . $ipn_logs_rowset[$i]['PK_auction_ipn_log_id'] . ""))
        );
    } 
}
$template->assign_vars(array(
    'L_ADMIN_AUCTION_IPN_LOG' => $lang['admin_auction_ipn_log'],
    'L_ADMIN_AUCTION_IPN_LOG_EXPLAIN' => $lang['admin_auction_ipn_log_explain'],
    'L_AUCTION_IPN_LOG_DATE' => $lang['Date'],
    'L_AUCTION_IPN_LOG_STATUS' => $lang['auction_ipn_log_status'],
    'L_AUCTION_IPN_LOG_OFFER_ID' => $lang['auction_quick_view_number'],
    'L_DELETE' => $lang['Delete'],
    'L_ACTION' => $lang['Action'],
    'L_DELETE_ALL' => $lang['Delete_all'],

    'U_DELETE_ALL' => append_sid("admin_auction_ipn_log.php?mode=delete_all"))
);


$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>