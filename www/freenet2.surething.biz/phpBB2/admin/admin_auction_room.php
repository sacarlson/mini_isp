<?php
/***************************************************************************
 *                          admin_auction_room.php
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
               $module['Auction']['a2_roommangement'] = $file;
               return;
          } // if
     // end set admin-navigation

     $phpbb_root_path = "./../";
     include($phpbb_root_path . 'extension.inc');
     require($phpbb_root_path . 'includes/functions_admin.'.$phpEx);
     require('./pagestart.' . $phpEx);
     include($phpbb_root_path . 'auction/functions_general.php');
     include($phpbb_root_path . 'auction/auction_constants.php');

     // Start Include language file
     $language = $board_config['default_lang'];
     if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_auction.'.$phpEx) )
          {
               $language = 'english';
          }
     include($phpbb_root_path . 'language/lang_' . $language . '/lang_auction.' . $phpEx);
     // end include language file

////////////////////////////////////////////////////////////////////////////////
// End basics
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Begin mode-settings
////////////////////////////////////////////////////////////////////////////////

     if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
          {
               $mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
               $mode = htmlspecialchars($mode);
          }
     else
          {
               $mode = "";
          } // if

////////////////////////////////////////////////////////////////////////////////
// End mode-settings
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
// Begin function block
////////////////////////////////////////////////////////////////////////////////

function get_auction_info($mode, $id)
// saves all data of the table $mode in $return
{
    global $db;

    switch($mode)
      {
          case 'auction_category':
              $table = AUCTION_CATEGORY_TABLE;
              $pk_id_of_table = 'PK_auction_category_id';
              $namefield = 'auction_category_title';
              break;

          case 'auction_room':
              $table = AUCTION_ROOM_TABLE;
              $pk_id_of_table = 'PK_auction_room_id';
              $namefield = 'auction_room_title';
              break;

          default:
              message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
              break;
      } // switch

    $sql = "SELECT count(*) as total
            FROM $table";

    if( !$result = $db->sql_query($sql) )
        {
             message_die(GENERAL_ERROR, "Couldn't get Auction-Category/Auction-Room information", "", __LINE__, __FILE__, $sql);
        } // if

    $count = $db->sql_fetchrow($result);
    $count = $count['total'];

    $sql = "SELECT *
            FROM $table
            WHERE $pk_id_of_table = $id";

    if( !$result = $db->sql_query($sql) )
        {
                 message_die(GENERAL_ERROR, "Couldn't get Auction-Category/Auction-Room information", "", __LINE__, __FILE__, $sql);
        }

    if( $db->sql_numrows($result) != 1 )
        {
                 message_die(GENERAL_ERROR, "Auction-Category/Auction-Room doesn't exist or multiple Auction-Category/Auction-Room with ID $id", "", __LINE__, __FILE__);
        }

    $return = $db->sql_fetchrow($result);
    $return['number'] = $count;
    return $return;
} // end get_auction_info

function get_auction_list($mode, $id, $select)
// saves all data of table $mode in $catlist
{
    global $db;

    switch($mode)
        {
            case 'auction_category':
                $table = AUCTION_CATEGORY_TABLE;
                $pk_id_of_table = 'PK_auction_category_id';
                $namefield = 'auction_category_title';
                break;

            case 'auction_room':
                $table = AUCTION_ROOM_TABLE;
                $pk_id_of_table = 'PK_auction_room_id';
                $namefield = 'auction_room_title';
                break;

            default:
                message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
                break;
        } // switch

    $sql = "SELECT *
            FROM $table";

    if( $select == 0 )
         {
              $sql .= " WHERE $pk_id_of_table <> $id";
         } // if

    if( !$result = $db->sql_query($sql) )
         {
              message_die(GENERAL_ERROR, "Couldn't get list of Auction-Rooms/Categories", "", __LINE__, __FILE__, $sql);
         } // if

    $cat_list = "";

    while( $row = $db->sql_fetchrow($result) )
       {
           $s = "";
           if ($row[$pk_id_of_table] == $id)
                {
                     $s = " selected=\"selected\"";
                } // if
           $catlist .= "<option value=\"$row[$pk_id_of_table]\"$s>" . $row[$namefield] . "</option>\n";
       } // while

    return($catlist);
} // get_auction_list


function renumber_auction_order($mode, $cat = 0)
{
    global $db;

    switch($mode)
        {
                 case 'auction_category':
                      $table = AUCTION_CATEGORY_TABLE;
                      $pk_id_of_table = 'PK_auction_category_id';
                      $orderfield = 'auction_category_order';
                      $cat = 0;
                      break;

                 case 'auction_room':
                      $table = AUCTION_ROOM_TABLE;
                      $pk_id_of_table = 'PK_auction_room_id';
                      $orderfield = 'auction_room_order';
                      $catfield = 'FK_auction_room_category_id';
                      break;

                 default:
                      message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
                      break;
          } // switch

    $sql = "SELECT *
            FROM $table";

    if( $cat != 0)
        {
             $sql .= " WHERE $catfield = $cat";
        } // if
    $sql .= " ORDER BY $orderfield ASC";

    if( !$result = $db->sql_query($sql) )
        {
             message_die(GENERAL_ERROR, "Couldn't get list of Categories", "", __LINE__, __FILE__, $sql);
        } // if

    $i = 10;
    $inc = 10;

    while( $row = $db->sql_fetchrow($result) )
       {
           $sql = "UPDATE $table SET $orderfield = $i WHERE $pk_id_of_table = " . $row[$pk_id_of_table];
           if( !$db->sql_query($sql) )
                 {
                       message_die(GENERAL_ERROR, "Couldn't update order fields", "", __LINE__, __FILE__, $sql);
                 } // if
           $i += 10;
       } // while

}

////////////////////////////////////////////////////////////////////////////////
// End function block
////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////
// Begin auction.php
////////////////////////////////////////////////////////////////////////////////

if( isset($HTTP_POST_VARS['add_auction_room']) || isset($HTTP_POST_VARS['add_auction_category']) )
{
    $mode = ( isset($HTTP_POST_VARS['add_auction_room']) ) ? "add_auction_room" : "add_auction_category";
    $mode = htmlspecialchars($mode);
}

if( !empty($mode) ) 
{
    switch($mode)
    {
        case 'add_auction_category':
            ////////////////////////////////////////////////////////////////////
            // Show form to add an auction category
            ////////////////////////////////////////////////////////////////////
            $newmode = 'insert_auction_category';
            $buttonvalue = $lang['Create_auction_category'];
            
            $template->set_filenames(array(
                 "body" => "admin/admin_auction_category_edit_body.tpl"));

            $s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="' . POST_AUCTION_CATEGORY_ . '" value="' . $auction_category_id . '" />';

            $template->assign_vars(array(
                 'CAT_TITLE' => $HTTP_POST_VARS['auction_category_title'],
                 'CAT_ICON' => '',

                 'L_EDIT_CATEGORY' => $lang['Create_auction_category'],
                 'L_EDIT_CATEGORY_EXPLAIN' => $lang['Create_auction_category_explain'],
                 'L_CATEGORY' => $lang['Auction_Category'],
                 'L_CATEGORY_ICON' => $lang['Auction_Category_Icon'],

                 'S_HIDDEN_FIELDS' => $s_hidden_fields,
                 'S_SUBMIT_VALUE' => $buttonvalue,
                 'S_FORUM_ACTION' => append_sid("admin_auction_room.$phpEx")));

            $template->pparse("body");
            break;

        case 'add_auction_room':
            //
            // Show form to create an auction room
            //
            $l_title = $lang['Create_Auction_Room'];
            $newmode = 'insert_auction_room';
            $buttonvalue = $lang['Create_Auction_Room'];

            $auction_description = '';
            $auction_status = AUCTION_OPEN;
            $auction_room_id = ''; 

            $auction_category_list = get_auction_list('auction_category', $auction_category_id, TRUE);
            $auction_room_status == ( AUCTION_CLOSED ) ? $auction_locked = "selected=\"selected\"" : $auction_unlocked = "selected=\"selected\"";
            
            $lang['Status_unlocked'] = isset($lang['Status_unlocked']) ? $lang['Status_unlocked'] : 'Unlocked';
            $lang['Status_locked'] = isset($lang['Status_locked']) ? $lang['Status_locked'] : 'Locked';
            
            $auction_status_list = "<option value=\"" . AUCTION_ROOM_UNLOCKED . "\" $auctionunlocked>" . $lang['Status_unlocked'] . "</option>\n";
            $auction_status_list .= "<option value=\"" . AUCTION_ROOM_LOCKED . "\" $auctionlocked>" . $lang['Status_locked'] . "</option>\n";

            $template->set_filenames(array("body" => "admin/admin_auction_room_edit_body.tpl"));

            $s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode .'" /><input type="hidden" name="' . POST_AUCTION_ROOM_URL . '" value="' . $PK_auction_room_id . '" />';

            $template->assign_vars(array(
                'S_AUCTION_ACTION' => append_sid("admin_auction_room.$phpEx"),
                'AUCTION_ROOM_TITLE' => $HTTP_POST_VARS['auction_room_title'],
                'S_HIDDEN_FIELDS' => $s_hidden_fields,
                'S_SUBMIT_VALUE' => $buttonvalue, 
                'S_CAT_LIST' => $auction_category_list,
                'S_STATUS_LIST' => $auction_status_list,

                'L_AUCTION_ROOM' => $l_title,
                'L_AUCTION_ROOM_EXPLAIN' => $lang['auction_room_edit_explain'],
                'L_AUCTION_ROOM_SETTINGS' => $lang['auction_room_settings'],
                'L_AUCTION_ROOM_TITLE' => $lang['auction_room_title'],
                'L_AUCTION_ROOM_CATEGORY' => $lang['auction_room_category'],
                'L_AUCTION_ROOM_DESCRIPTION' => $lang['auction_room_description'],
                'L_AUCTION_ROOM_STATUS' => $lang['auction_room_state'],
                'L_AUCTION_ROOM_ICON' => $lang['auction_room_icon'],

                'AUCTION_ROOM_NAME' => $auction_room_title,
                'AUCTION_ROOM_DESCRIPTION' => $auction_room_desc)
            );
            $template->pparse("body");
            break;

        case 'edit_auction_room':

            if ($mode == 'edit_auction_room')
            {

                $l_title = $lang['Edit_Auction_Room'];
                $newmode = 'modify_auction_room';
                $buttonvalue = $lang['Update_Auction_Room'];

                $auction_room_id = intval($HTTP_GET_VARS[POST_AUCTION_ROOM_URL]);
                $row = get_auction_info('auction_room', $auction_room_id);

                $auction_category_id = $row['FK_auction_room_category_id'];
                $auction_room_title = $row['auction_room_title'];
                $auction_room_description = $row['auction_room_description'];
                $auction_room_status = $row['auction_room_state'];
                $auction_room_icon = $row['auction_room_icon'];
            }

            $auction_category_list = get_auction_list('auction_category', $auction_category_id, TRUE);
            $auction_room_status == ( AUCTION_CLOSED ) ? $auction_locked = "selected=\"selected\"" : $auction_unlocked = "selected=\"selected\"";
            
            if ( $auction_room_status == 1 )
                 {
                      $auctionunlocked = " selected ";
                 }

            $lang['Status_unlocked'] = isset($lang['Status_unlocked']) ? $lang['Status_unlocked'] : 'Unlocked';
            $lang['Status_locked'] = isset($lang['Status_locked']) ? $lang['Status_locked'] : 'Locked';

            $auction_status_list = "<option value=\"" . AUCTION_ROOM_UNLOCKED . "\"" . $auctionunlocked . ">" . $lang['Status_unlocked'] . "</option>\n";
            $auction_status_list .= "<option value=\"" . AUCTION_ROOM_LOCKED . "\"" . $auctionunlocked. ">" . $lang['Status_locked'] . "</option>\n";

            $template->set_filenames(array("body" => "admin/admin_auction_room_edit_body.tpl"));

            $s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode .'" /><input type="hidden" name="' . POST_AUCTION_ROOM_URL . '" value="' . $auction_room_id . '" />';


            $template->assign_vars(array(
                'S_AUCTION_ACTION' => append_sid("admin_auction_room.$phpEx"),
                'S_HIDDEN_FIELDS' => $s_hidden_fields,
                'S_SUBMIT_VALUE' => $buttonvalue, 
                'S_CAT_LIST' => $auction_category_list,
                'S_STATUS_LIST' => $auction_status_list,

                'L_AUCTION_TITLE' => $l_title, 
                'L_AUCTION_ROOM_EXPLAIN' => $lang['auction_room_edit_explain'],
                'L_AUCTION_ROOM_SETTINGS' => $lang['auction_room_settings'],
                'L_AUCTION_ROOM_TITLE' => $lang['auction_room_title'],
                'L_AUCTION_ROOM_CATEGORY' => $lang['auction_room_category'],
                'L_AUCTION_ROOM_DESCRIPTION' => $lang['auction_room_description'],
                'L_AUCTION_ROOM_STATUS' => $lang['auction_room_state'],
                'L_AUCTION_ROOM_ICON' => $lang['auction_room_icon'],

                'AUCTION_ROOM_TITLE' => $auction_room_title,
                'AUCTION_ROOM_DESCRIPTION' => $auction_room_description,
                'AUCTION_ROOM_STATE' => $auction_room_status,
                'AUCTION_ROOM_ICON' => $auction_room_icon)
            );
            $template->pparse("body");
            break;

        case 'edit_auction_category':

            $newmode = 'modify_auction_category';
            $buttonvalue = $lang['Update'];
            $auction_category_id = intval($HTTP_GET_VARS[POST_AUCTION_CATEGORY_URL]);

            $row = get_auction_info('auction_category', $auction_category_id);
            $auction_category_title = $row['auction_category_title'];
            $auction_category_icon = $row['auction_category_icon'];
            
            $template->set_filenames(array("body" => "admin/admin_auction_category_edit_body.tpl"));

            $s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="' . POST_AUCTION_CATEGORY_URL . '" value="' . $auction_category_id . '" />';

            $template->assign_vars(array(
                'CAT_TITLE' => $auction_category_title,
                'CAT_ICON' => $auction_category_icon,

                'L_EDIT_CATEGORY' => $lang['Edit_Auction_Category'], 
                'L_EDIT_CATEGORY_EXPLAIN' => $lang['Edit_Auction_Category_explain'], 
                'L_CATEGORY' => $lang['Auction_Category'], 
                'L_CATEGORY_ICON' => $lang['Auction_Category_Icon'], 

                'S_HIDDEN_FIELDS' => $s_hidden_fields, 
                'S_SUBMIT_VALUE' => $buttonvalue, 
                'S_FORUM_ACTION' => append_sid("admin_auction_room.$phpEx"))
            );

            $template->pparse("body");
            break;

        case 'modify_auction_room':

            $sql = "UPDATE " . AUCTION_ROOM_TABLE . "
                    SET auction_room_title = '" . str_replace("\'", "''", $HTTP_POST_VARS['auction_room_title']) . "', FK_auction_room_category_id = " . intval($HTTP_POST_VARS['auction_category_title']) . ",
                        auction_room_description = '" . str_replace("\'", "''", $HTTP_POST_VARS['auction_room_description']) . "',
                        auction_room_state = " . intval($HTTP_POST_VARS['auction_room_state']) . " ,
                        auction_room_icon ='" . str_replace("\'", "''", $HTTP_POST_VARS['auction_room_icon']) . "'
                    WHERE PK_auction_room_id = " . intval($HTTP_POST_VARS[POST_AUCTION_ROOM_URL]);

            if( !$result = $db->sql_query($sql) )
                {
                         message_die(GENERAL_ERROR, "Couldn't update auction-room information", "", __LINE__, __FILE__, $sql);
                }

            $message = $lang['Auction_room_updated'] . "<br /><br />" . sprintf($lang['Click_return_auctionadmin'], "<a href=\"" . append_sid("admin_auction_room.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

            message_die(GENERAL_MESSAGE, $message);

            break;


        case 'insert_auction_room':

            if( trim($HTTP_POST_VARS['auction_room_title']) == "" )
            {
                message_die(GENERAL_ERROR, "Can't create an auction-room without a name");
            }

            $sql = "SELECT MAX(auction_room_order) AS max_order
                    FROM " . AUCTION_ROOM_TABLE . "
                    WHERE FK_auction_room_category_id  = " . $HTTP_POST_VARS['auction_category_title'];


            if( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Couldn't get order number from auction table", "", __LINE__, __FILE__, $sql);
            }
            $row = $db->sql_fetchrow($result);

            $max_order = $row['max_order'];
            $next_order = $max_order + 10;

            $sql = "SELECT MAX(PK_auction_room_id) AS max_id
                    FROM " . AUCTION_ROOM_TABLE;

            if( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Couldn't get order number from auctions table", "", __LINE__, __FILE__, $sql);
            }
            $row = $db->sql_fetchrow($result);

            $max_id = $row['max_id'];
            $next_id = $max_id + 1;

            $sql = "INSERT INTO " . AUCTION_ROOM_TABLE . "
                           (auction_room_title,
                            FK_auction_room_category_id ,
                            auction_room_description,
                            auction_room_order,
                            auction_room_state, auction_room_icon)
                    VALUES ('" . str_replace("\'", "''", $HTTP_POST_VARS['auction_room_title']) . "',
                             " . $HTTP_POST_VARS['auction_category_title'] . ",
                             '" . str_replace("\'","''",$HTTP_POST_VARS['auction_room_description']) . "',
                             $next_order,
                             " . intval($HTTP_POST_VARS['auction_room_state']) . ",
                             '" . str_replace("\'", "''", $HTTP_POST_VARS['auction_room_icon']) . "')";


            if( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Couldn't insert row in auction table", "", __LINE__, __FILE__, $sql);
            }

            $message = $lang['Auction_room_updated'] . "<br /><br />" . sprintf($lang['Click_return_auctionadmin'], "<a href=\"" . append_sid("admin_auction_room.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

            message_die(GENERAL_MESSAGE, $message);

            break;

        case 'modify_auction_category':

            $sql = "UPDATE " . AUCTION_CATEGORY_TABLE . "
                    SET auction_category_title = '" . str_replace("\'", "''", $HTTP_POST_VARS['auction_category_title']) . "',
                        auction_category_icon = '" . $HTTP_POST_VARS['auction_category_icon'] . "'
                    WHERE PK_auction_category_id = " . intval($HTTP_POST_VARS[POST_AUCTION_CATEGORY_URL]);

            if( !$result = $db->sql_query($sql) )
                {
                         message_die(GENERAL_ERROR, "Couldn't update auction category information", "", __LINE__, __FILE__, $sql);
                }

            $message = $lang['Auction_room_updated'] . "<br /><br />" . sprintf($lang['Click_return_auctionadmin'], "<a href=\"" . append_sid("admin_auction_room.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

            message_die(GENERAL_MESSAGE, $message);

            break;

        case 'insert_auction_category':

            if( trim($HTTP_POST_VARS['auction_category_title']) == '')
                 {
                      message_die(GENERAL_ERROR, "Can't create an auction category without a name");
                 }

            $sql = "SELECT MAX(auction_category_order) AS max_order
                    FROM " . AUCTION_CATEGORY_TABLE;

            if( !$result = $db->sql_query($sql) )
                 {
                      message_die(GENERAL_ERROR, "Couldn't get order number from auction categories table", "", __LINE__, __FILE__, $sql);
                 }
            $row = $db->sql_fetchrow($result);
            $max_order = $row['max_order'];
            $next_order = $max_order + 10;

            $sql = "INSERT INTO " . AUCTION_CATEGORY_TABLE . "
                       (auction_category_title,
                        auction_category_order,
                        auction_category_icon)
                    VALUES ('" . str_replace("\'",
                            "''",
                            $HTTP_POST_VARS['auction_category_title']) . "',
                            $next_order,
                            '" . $HTTP_POST_VARS['auction_category_icon'] . "')";

            if( !$result = $db->sql_query($sql) )
                     {
                         message_die(GENERAL_ERROR, "Couldn't insert row in categories table", "", __LINE__, __FILE__, $sql);
                     } // if

            $message = $lang['Auction_room_updated'] . "<br /><br />" . sprintf($lang['Click_return_auctionadmin'], "<a href=\"" . append_sid("admin_auction_room.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
            message_die(GENERAL_MESSAGE, $message);

            break;

        case 'delete_auction_room':
            // Show form to delete an auction room
            $auction_room_id = intval($HTTP_GET_VARS[POST_AUCTION_ROOM_URL]);
            $from_id = $auction_room_id;

            $select_to = '<select name="to_id">';
            $select_to .= "<option value=\"-1\"$s>" . $lang['Delete_all_auction_records'] . "</option>\n";
            $select_to .= get_auction_list('auction_room', $auction_room_id, 0);
            $select_to .= '</select>';

            $buttonvalue = $lang['Move_and_Delete_Auction'];

            $newmode = 'move_delete_auction_room';

            $foruminfo = get_auction_info('auction_room', $auction_room_id);
            $name = $foruminfo['auction_room_title'];

            $template->set_filenames(array("body" => "admin/admin_auction_room_delete_body.tpl"));

            $s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="from_id" value="' . $from_id . '" />';

            $template->assign_vars(array(
                'NAME' => $name, 

                'L_AUCTION_DELETE' => $lang['Auction_room_delete'],
                'L_AUCTION_DELETE_EXPLAIN' => $lang['Auction_room_delete_explain'],
                'L_MOVE_AUCTION' => $lang['Auction_room_move_contents'],
                'L_AUCTION_TITLE' => $lang['Auction_room_title'],

                "S_HIDDEN_FIELDS" => $s_hidden_fields,
                'S_FORUM_ACTION' => append_sid("admin_auction_room.$phpEx"),
                'S_SELECT_TO' => $select_to,
                'S_SUBMIT_VALUE' => $buttonvalue)
            );

            $template->pparse("body");
            break;

// NOT DONE
        case 'move_delete_auction_room':
            // Move or delete a forum in the DB
            $from_id = intval($HTTP_POST_VARS['from_id']);
            $to_id = intval($HTTP_POST_VARS['to_id']);
            $delete_old = intval($HTTP_POST_VARS['delete_old']);

            // Either delete or move all posts in a forum
            if($to_id == -1)
            {
                // Delete bids

                $sql = "SELECT PK_auction_offer_id
                        FROM " . AUCTION_OFFER_TABLE . "
                        WHERE FK_auction_offer_room_id =" . $from_id . "";

                if (!($result = $db->sql_query($sql)))
                {
                    message_die(GENERAL_ERROR, "Couldn't obtain list of offer ids", "", __LINE__, __FILE__, $sql);
                }
                while ( $row = $db->sql_fetchrow($result) )
                    {
                           if ( $row['PK_auction_offer_id']<>"" )
                                {
                                    $sql = "DELETE FROM " . AUCTION_BID_TABLE . "
                                           WHERE FK_auction_bid_offer_id = " . $row['PK_auction_offer_id'] . " ";

                                    if (!($result2 = $db->sql_query($sql)))
                                           {
                                               message_die(GENERAL_ERROR, "Couldn't delete bids", "", __LINE__, __FILE__, $sql);
                                           }
                                 }
                    }

                // Delete offers
                $sql = "DELETE FROM " . AUCTION_OFFER_TABLE . "
                        WHERE FK_auction_offer_room_id =" . $from_id . "";

                if (!($result = $db->sql_query($sql)))
                {
                    message_die(GENERAL_ERROR, "Couldn't delete offers", "", __LINE__, __FILE__, $sql);
                }
            }
            else
            {
                $sql = "UPDATE " . AUCTION_OFFER_TABLE . "
                        SET FK_auction_offer_room_id = $to_id
                        WHERE FK_auction_offer_room_id = $from_id";
                if( !$result = $db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, "Couldn't move offers to other auction room", "", __LINE__, __FILE__, $sql);
                }
            }

            // Delete rooms
            $sql = "DELETE FROM " . AUCTION_ROOM_TABLE . "
                    WHERE PK_auction_room_id =" . $from_id . "";

            if (!($result = $db->sql_query($sql)))
               {
                   message_die(GENERAL_ERROR, "Couldn't delete auction-room", "", __LINE__, __FILE__, $sql);
               }
            
            $message = $lang['Forums_updated'] . "<br /><br />" . sprintf($lang['Click_return_forumadmin'], "<a href=\"" . append_sid("admin_auction_room.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

            message_die(GENERAL_MESSAGE, $message);

            break;

        case 'delete_auction_category':

            $auction_category_id = intval($HTTP_GET_VARS[POST_AUCTION_CATEGORY_URL]);

            $buttonvalue = $lang['Move_and_Delete_Auction'];
            $newmode = 'move_delete_auction_category';
            $auction_category_info = get_auction_info('auction_category', $auction_category_id);

            $name = $auction_category_info['auction_category_title'];

            if ($auction_category_info['number'] == 1)
            {
                $sql = "SELECT count(*) as total
                    FROM ". AUCTION_ROOM_TABLE;
                if( !$result = $db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, "Couldn't get auction-room count", "", __LINE__, __FILE__, $sql);
                }
                $count = $db->sql_fetchrow($result);
                $count = $count['total'];

                if ($count > 0)
                {
                    message_die(GENERAL_ERROR, $lang['Must_delete_auction_room']);
                }
                else
                {
                    $select_to = $lang['Nowhere_to_move_auction'];
                }
            }
            else
            {
                $select_to = '<select name="to_id">';
                $select_to .= get_auction_list('auction_category', $auction_category_id, 0);
                $select_to .= '</select>';
            }

            $template->set_filenames(array("body" => "admin/admin_auction_category_delete_body.tpl"));

            $s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="from_id" value="' . $auction_category_id . '" />';

            $template->assign_vars(array(
                'TITLE' => $name, 

                'L_AUCTION_CATEGORY_DELETE' => $lang['auction_category_delete'], 
                'L_AUCTION_CATEGORY_DELETE_EXPLAIN' => $lang['auction_category_delete_explain'], 
                'L_AUCTION_ROOM_MOVE' => $lang['auction_room_move'],
                'L_AUCTION_CATEGORY_TITLE' => $lang['Auction_name'], 
                
                'S_HIDDEN_FIELDS' => $s_hidden_fields,
                'S_FORUM_ACTION' => append_sid("admin_auction_room.$phpEx"),
                'S_SELECT_TO' => $select_to,
                'S_SUBMIT_VALUE' => $buttonvalue));

            $template->pparse("body");
            break;

        case 'move_delete_auction_category':

            $from_id = intval($HTTP_POST_VARS['from_id']);
            $to_id = intval($HTTP_POST_VARS['to_id']);

            if (!empty($to_id))
            {
                $sql = "SELECT *
                        FROM " . AUCTION_CATEGORY_TABLE . "
                        WHERE PK_auction_category_id IN ($from_id, $to_id)";

                if( !$result = $db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, "Couldn't verify existence of auction-categories", "", __LINE__, __FILE__, $sql);
                }
                if($db->sql_numrows($result) != 2)
                {
                    message_die(GENERAL_ERROR, "Ambiguous auction-category ID's", "", __LINE__, __FILE__);
                }

                $sql = "UPDATE " . AUCTION_ROOM_TABLE . "
                        SET FK_auction_room_category_id  = $to_id
                        WHERE FK_auction_room_category_id  = $from_id";
                if( !$result = $db->sql_query($sql) )
                {
                    message_die(GENERAL_ERROR, "Couldn't move auction-room to other category", "", __LINE__, __FILE__, $sql);
                }
            }

            $sql = "DELETE FROM " . AUCTION_CATEGORY_TABLE ."
                    WHERE PK_auction_category_id = $from_id";
                
            if( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Couldn't delete auction-category", "", __LINE__, __FILE__, $sql);
            }

            $message = $lang['Auction_room_updated'] . "<br /><br />" . sprintf($lang['Click_return_auctionadmin'], "<a href=\"" . append_sid("admin_auction_room.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

            message_die(GENERAL_MESSAGE, $message);

            break;

        case 'order_auction_room':
            // Change order of auction-rooms
            $move = intval($HTTP_GET_VARS['move']);
            $auction_room_id = intval($HTTP_GET_VARS[POST_AUCTION_ROOM_URL]);

            $auction_room_info = get_auction_info('auction_room', $auction_room_id);

            $auction_category_id = $auction_room_info['FK_auction_room_category_id'];

            $sql = "UPDATE " . AUCTION_ROOM_TABLE . "
                    SET auction_room_order  = auction_room_order + $move
                    WHERE PK_auction_room_id = $auction_room_id";
            if( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Couldn't change auction-category order", "", __LINE__, __FILE__, $sql);
            }

            renumber_auction_order('auction_room', $auction_room_info['PK_auction_room_id']);
            $show_index = TRUE;

            break;

        case 'order_auction_category':
            // Change order of categories in the DB
            $move = intval($HTTP_GET_VARS['move']);
            $auction_category_id = intval($HTTP_GET_VARS[POST_AUCTION_CATEGORY_URL]);

            $sql = "UPDATE " . AUCTION_CATEGORY_TABLE . "
                    SET auction_category_order = auction_category_order + $move
                    WHERE PK_auction_category_id = $auction_category_id";
            if( !$result = $db->sql_query($sql) )
            {
                message_die(GENERAL_ERROR, "Couldn't change category order", "", __LINE__, __FILE__, $sql);
            }

            renumber_auction_order('auction_category');
            $show_index = TRUE;

            break;

        case 'auction_room_sync':
            message_die(GENERAL_MESSAGE, 'No functionality so far !! Sorry');


            break;

        default:
            message_die(GENERAL_MESSAGE, $lang['No_mode']);
            break;
    }

    if ($show_index != TRUE)
    {
        include('./page_footer_admin.'.$phpEx);
        exit;
    }
}

////////////////////////////////////////////////////////////////////////////////
// Start page proper
////////////////////////////////////////////////////////////////////////////////

$template->set_filenames(array("body" => "admin/admin_auction_room_body.tpl"));

$template->assign_vars(array(
    'S_AUCTION_AUCTION' => append_sid("admin_auction_room.$phpEx"),
    'L_AUCTION_TITLE' => $lang['Auction_admin'], 
    'L_AUCTION_EXPLAIN' => $lang['Auction_admin_explain'], 
    'L_CREATE_AUCTION_ROOM' => $lang['auction_room_create'],
    'L_CREATE_AUCTION_CATEGORY' => $lang['auction_category_create'],
    'L_EDIT' => $lang['Edit'], 
    'L_DELETE' => $lang['Delete'], 
    'L_MOVE_UP' => $lang['Move_up'], 
    'L_MOVE_DOWN' => $lang['Move_down'], 
    'L_RESYNC' => $lang['Resync']));

     $sql = "SELECT PK_auction_category_id,
                    auction_category_title,
                    auction_category_order
             FROM " . AUCTION_CATEGORY_TABLE . "
             ORDER BY auction_category_order";

if( !$q_categories = $db->sql_query($sql) )
{
    message_die(GENERAL_ERROR, "Could not query categories list", "", __LINE__, __FILE__, $sql);
}

if( $total_auction_categories = $db->sql_numrows($q_auction_categories) )
{
        $template->assign_block_vars("auction_room_add_row", array());

    $auction_category_rows = $db->sql_fetchrowset($q_auction_categories);

    $sql = "SELECT *
            FROM " . AUCTION_ROOM_TABLE . "
            ORDER BY FK_auction_room_category_id, auction_room_order";

    if(!$q_auction_rooms = $db->sql_query($sql))
    {
        message_die(GENERAL_ERROR, "Could not query auction-room information", "", __LINE__, __FILE__, $sql);
    }

    if( $total_auction_rooms = $db->sql_numrows($q_auction_rooms) )
    {
        $auction_room_rows = $db->sql_fetchrowset($q_auction_rooms);
    }

    // Okay, let's build the index
    $gen_cat = array();

    for($i = 0; $i < $total_auction_categories; $i++)
    {
        $auction_category_id = $auction_category_rows[$i]['PK_auction_category_id'];

        $template->assign_block_vars("auction_category_row", array(
            'S_ADD_AUCTION_SUBMIT' => "addauctionroom[$PK_auction_category_id]", 
            'S_ADD_AUCTION_NAME' => "auctionroomname[$PK_auction_category_id]", 

            'CAT_ID' => $auction_category_id,
            'CAT_DESC' => $auction_category_rows[$i]['auction_category_title'],

            'U_CAT_EDIT' => append_sid("admin_auction_room.$phpEx?mode=edit_auction_category&amp;" . POST_AUCTION_CATEGORY_URL . "=$auction_category_id"),
            'U_CAT_DELETE' => append_sid("admin_auction_room.$phpEx?mode=delete_auction_category&amp;" . POST_AUCTION_CATEGORY_URL . "=$auction_category_id"),
            'U_CAT_MOVE_UP' => append_sid("admin_auction_room.$phpEx?mode=order_auction_category&amp;move=-15&amp;" . POST_AUCTION_CATEGORY_URL . "=$auction_category_id"),
            'U_CAT_MOVE_DOWN' => append_sid("admin_auction_room.$phpEx?mode=order_auction_category&amp;move=15&amp;" . POST_AUCTION_CATEGORY_URL . "=$auction_category_id"),
            'U_VIEWCAT' => append_sid($phpbb_root_path."auction.$phpEx?" . POST_AUCTION_CATEGORY_URL . "=$cat_id")));

        for($j = 0; $j < $total_auction_rooms; $j++)
        {
            $auction_room_id = $auction_room_rows[$j]['PK_auction_room_id'];
            
            if ($auction_room_rows[$j]['FK_auction_room_category_id'] == $auction_category_id)
            {

                            $sql = "SELECT COUNT(*) as auction_offer_count
                                   FROM " . AUCTION_OFFER_TABLE . "
                                   WHERE  FK_auction_offer_room_id=" . $auction_room_rows[$j]['PK_auction_room_id'] . "
                                      AND auction_offer_paid=1
                                      AND auction_offer_time_start<" . time() . "
                                      AND auction_offer_time_stop>" . time() . "";

                            if ( !($result = $db->sql_query($sql)) )
                                {
                                    message_die(GENERAL_ERROR, 'Could not query offer-count', '', __LINE__, __FILE__, $sql);
                                }
                            $auction_room_offer_count = $db->sql_fetchrow($result);

                $template->assign_block_vars("auction_category_row.auction_room_row",    array(
                    'AUCTION_ROOM_NAME' => $auction_room_rows[$j]['auction_room_title'],
                    'AUCTION_ROOM_ID' => $auction_room_rows[$j]['PK_auction_room_id'],
                    'AUCTION_ROOM_DESCRIPTION' => $auction_room_rows[$j]['auction_room_description'],
                    'AUCTION_ROOM_STATE' => ( $auction_room_rows[$j]['auction_room_state'] ) ? $lang['Status_locked'] : $lang['Status_unlocked'],
                    'ROW_COLOR' => $row_color,
                    'AUCTION_ROOM_OFFER_COUNT' => $auction_room_offer_count['auction_offer_count'],
                    'NUM_TOPICS' => $auction_room_rows[$j]['auction_room_topics'],
                    'NUM_POSTS' => $auction_room_rows[$j]['auction_room_posts'],

                    'U_VIEWAUCTION' => append_sid($phpbb_root_path."auction_room.$phpEx?mode=view&" . POST_AUCTION_ROOM_URL . "=$auction_room_id"),
                    'U_AUCTION_EDIT' => append_sid("admin_auction_room.$phpEx?mode=edit_auction_room&amp;" . POST_AUCTION_ROOM_URL . "=$auction_room_id"),
                    'U_AUCTION_DELETE' => append_sid("admin_auction_room.$phpEx?mode=delete_auction_room&amp;" . POST_AUCTION_ROOM_URL . "=$auction_room_id"),
                    'U_AUCTION_MOVE_UP' => append_sid("admin_auction_room.$phpEx?mode=order_auction_room&amp;move=-15&amp;" . POST_AUCTION_ROOM_URL . "=$auction_room_id"),
                    'U_AUCTION_MOVE_DOWN' => append_sid("admin_auction_room.$phpEx?mode=order_auction_room&amp;move=15&amp;" . POST_AUCTION_ROOM_URL . "=$auction_room_id"),
                    'U_AUCTION_RESYNC' => append_sid("admin_auction_room.$phpEx?mode=auction_room_sync&amp;" . POST_AUCTION_ROOM_URL . "=$auction_room_id")));

            }// if ... forumid == catid
            
        } // for ... forums

    } // for ... categories

}// if ... total_categories
$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>