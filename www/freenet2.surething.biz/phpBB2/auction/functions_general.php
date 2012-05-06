<?php
/***************************************************************************
 *                              functions_general.php
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

function init_auction_config()
// grab all auction-config-data which can be used anywhere
     {
          global $db;
          $auction_config_data = array();

          $sql = "SELECT *
                  FROM " . AUCTION_CONFIG_TABLE;

           if ( !($auction_config_result = $db->sql_query($sql)) )
               {
                   message_die(GENERAL_ERROR, 'Could not query auction-config', '', __LINE__, __FILE__, $sql);
               }  // End if

          while ( $row = $db->sql_fetchrow($auction_config_result))
                {
                 $auction_config_data[$row['config_name']] = $row['config_value'];
                } // End while

          return $auction_config_data;

     } // End function

function DateAdd($interval, $number, $date)
// this function adds a $number of e.g. days to a specific $date
     {
       $date_time_array = getdate($date);
       $hours = $date_time_array['hours'];
       $minutes = $date_time_array['minutes'];
       $seconds = $date_time_array['seconds'];
       $month = $date_time_array['mon'];
       $day = $date_time_array['mday'];
       $year = $date_time_array['year'];

       switch ($interval)
            {
                  case 'yyyy':
                      $year+=$number;
                      break;
                  case 'q':
                      $year+=($number*3);
                      break;
                  case 'm':
                      $month+=$number;
                      break;
                  case 'y':
                  case 'd':
                  case 'w':
                      $day+=$number;
                      break;
                  case 'ww':
                      $day+=($number*7);
                      break;
                  case 'h':
                      $hours+=$number;
                      break;
                  case 'n':
                      $minutes+=$number;
                      break;
                  case 's':
                      $seconds+=$number; 
                      break;
          } // End switch

          $timestamp= mktime($hours,$minutes,$seconds,$month,$day,$year);
          return $timestamp;

  } // End function


function unixTm($strDT) 
     {
        $arrDT = explode(" ", $strDT); 
        $arrD = explode("-", $arrDT[0]); 
        $arrT = explode(":", $arrDT[1]); 
        return mktime($arrT[0], $arrT[1], $arrT[2], $arrD[0], $arrD[1], $arrD[2]);
     } // End function


function dateDiff($date1,$date2) 
// dateDiff(past, future)
     {
        global $lang;

        $dt2 = $date2;
        $dt1 = $date1;

        $r = $dt2 - $dt1; 
         
        $yy=floor($r / 31536000); 
        while ($r > 31536000)
              {
                  $r -= 31536000;
              } // End while
        $dd=floor($r / 86400); 
        while ($r > 86400)
              {
                   $r -= 86400;
              } // End while
        $hh=floor($r/3600); 

        if ($hh<=9) $hh="0".$hh;

        while ($r > 3600)
              {
                   $r -= 3600;
              } // End while
        $mm=floor($r/60); 

        if ($mm<=9) $mm="0".$mm;

        while ($r > 60)
              {
                  $r -= 60;
              } // End while

        $ss=$r ; 
        if ($ss<=9) $ss="0".$ss;

        //$retval="$yy year(s), $dd day(s) $hh:$mm:$ss";
        $retval="$dd" . $lang['auction_day_short']. " $hh:$mm";
        return $retval; 

     } // End function

function dayDiff($date1,$date2)
// dateDiff(past, future)
     {
        global $lang;

        $dt2 = $date2;
        $dt1 = $date1;

        $r = $dt2 - $dt1;

        $yy=floor($r / 31536000);
        while ($r > 31536000)
              {
                  $r -= 31536000;
              } // End while
        $dd=floor($r / 86400);
        while ($r > 86400)
              {
                   $r -= 86400;
              } // End while
        $hh=floor($r/3600);

        if ($hh<=9) $hh="0".$hh;

        while ($r > 3600)
              {
                   $r -= 3600;
              } // End while
        $mm=floor($r/60);

        if ($mm<=9) $mm="0".$mm;

        while ($r > 60)
              {
                  $r -= 60;
              } // End while

        $ss=$r ;
        if ($ss<=9) $ss="0".$ss;

        //$retval="$yy year(s), $dd day(s) $hh:$mm:$ss";
        $retval="$dd";
        return $retval;

     } // End function

function getRatingCount($user_id)
{
          global $db;

          $iRatingCount = 0;
          
          $sql = "SELECT COUNT(auction_offer_seller_rating_text) as ratingCount
                  FROM " . AUCTION_USER_RATING_TABLE. "
                  WHERE FK_auction_offer_seller_id=" . $user_id . " AND
                         auction_offer_seller_rating_text<>''";

          if ( !($rating_result = $db->sql_query($sql)) )
               {
                   message_die(GENERAL_ERROR, 'Could not query rating-number', '', __LINE__, __FILE__, $sql);
               }  // End if

          $arrRatingCount = $db->sql_fetchrow($rating_result);
          $iRatingCount = $arrRatingCount['ratingCount'];

          $sql = "SELECT COUNT(auction_offer_buyer_rating_text) as ratingCount
                  FROM " . AUCTION_USER_RATING_TABLE. "
                  WHERE FK_auction_offer_buyer_id=" . $user_id . " AND
                        auction_offer_buyer_rating_text<>'' ";

          if ( !($rating_result = $db->sql_query($sql)) )
               {
                   message_die(GENERAL_ERROR, 'Could not query rating-number', '', __LINE__, __FILE__, $sql);
               }  // End if

          $arrRatingCount = $db->sql_fetchrow($rating_result);
          $iRatingCount = $iRatingCount + $arrRatingCount['ratingCount'];

          if ( $iRatingCount == 0 || $iRatingCount == "")
               {
                   $iRatingCount = 0;
               }
          
          return $iRatingCount;

}

function init_auction_config_pic()
// grab all auction-config-pic-data which can be used anywhere
     {
          global $db;
          $auction_config_pic = array();

          $sql = "SELECT *
                  FROM " . AUCTION_IMAGE_CONFIG_TABLE;

           if ( !($auction_config_pic_result = $db->sql_query($sql)) )
               {
                   message_die(GENERAL_ERROR, 'Could not query auction-config-pic', '', __LINE__, __FILE__, $sql);
               }  // End if

          while ( $row = $db->sql_fetchrow($auction_config_pic_result))
                {
                 $auction_config_pic[$row['config_name']] = $row['config_value'];
                } // End while

          return $auction_config_pic;

     } // End function


function get_config_parameter($param)
// grab just the auction-config-pic-data which is used for displaying . Is it faster than grabbing all 60 parameters??? I dunno.
{
          global $db;

          $sql = "SELECT config_value
                  FROM " . AUCTION_IMAGE_CONFIG_TABLE . "
                  WHERE config_name = '$param' ";

           if ( !($config_pic_gd_result = $db->sql_query($sql)) )
               {
                   message_die(GENERAL_ERROR, 'Could not query auction-display-config-pic', '', __LINE__, __FILE__, $sql);
               }  // End if

          $row = $db->sql_fetchrow($config_pic_gd_result);

          return $row['config_value'];

} // End function


function getRole()
{
     global $db,$userdata;
     if ( $userdata['user_id'] < 1 )
          {
               $role = "guest";
          }
     else
          {
               $role = "registered";
          }
     $sql = "SELECT FK_auction_role
             FROM " . AUCTION_USER_ROLE_TABLE . "
             WHERE FK_user_id =" . $userdata['user_id'] . "
             ORDER BY FK_auction_role DESC
             LIMIT 0,1";

     if ( !($result = $db->sql_query($sql)) )
          {
               message_die(GENERAL_ERROR, 'Could not query auctioneer role', '', __LINE__, __FILE__, $sql);
          }  // End if

     $row = $db->sql_fetchrow($result);

     if ( $row['FK_auction_role'] == 5 OR $userdata['user_level']==1 )
          {
               $role = "administrator";
          }
     else if ( $row['FK_auction_role'] == 4 )
          {
               $role = "moderator";
          }
     else if ( $row['FK_auction_role'] == 3 )
          {
               $role = "auctioneer";
          }

    return $role;
} // end getRole()

function checkPermission($action)
{
     global $db,$userdata, $lang;
     $role = getRole();

     switch($action)
         {
             case 'VIEW_ALL':

                  $sql = "SELECT view_all
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['view_all'] == 0 )
                       {
                             message_die(GENERAL_MESSAGE, $lang['auction_no_permission_view_all']);
                       }

                  break;

             case 'VIEW_OFFER':

                  $sql = "SELECT view_offer
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['view_offer'] == 0 )
                       {
                             message_die(GENERAL_MESSAGE, $lang['auction_no_permission_view_offer']);
                       }

                  break;

             case 'NEW':

                  $sql = "SELECT new
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['new'] == 0 )
                       {
                             message_die(GENERAL_MESSAGE, $lang['auction_no_permission_new']);
                       }
                  break;

             case 'BID':

                  $sql = "SELECT bid
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['bid'] == 0 )
                       {
                             message_die(GENERAL_MESSAGE, $lang['auction_no_permission_bid']);
                       }

                  break;

             case 'COMMENT':

                  $sql = "SELECT comment
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['comment'] == 0 )
                       {
                             message_die(GENERAL_MESSAGE, $lang['auction_no_permission_comment']);
                       }

                  break;

             case 'MOVE':

                  $sql = "SELECT move
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['move'] == 0 )
                       {
                             message_die(GENERAL_MESSAGE, $lang['auction_no_permission_move']);
                       }

                  break;

             case 'DELETE_OFFER':

                  $sql = "SELECT delete_offer
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['delete_offer'] == 0 )
                       {
                             message_die(GENERAL_MESSAGE, $lang['auction_no_permission_delete_offer']);
                       }

                  break;

             case 'DELETE_BID':

                  $sql = "SELECT delete_bid
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['delete_bid'] == 0 )
                       {
                             message_die(GENERAL_MESSAGE, $lang['auction_no_permission_delete_bid']);
                       }

                  break;

             case 'SPECIAL':

                  $sql = "SELECT special
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['special'] == 0 )
                       {
                             message_die(GENERAL_MESSAGE, $lang['auction_no_permission_special']);
                       }

                  break;

         }
}

function checkBoolPermission($action)
{
     global $db,$userdata, $lang;
     $role = getRole();

     switch($action)
         {
             case 'VIEW_ALL':

                  $sql = "SELECT view_all
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['view_all'] == 0 )
                       {
                             return 0;
                       }
                  else
                       {
                             return 1;
                       }

                  break;

             case 'VIEW_OFFER':

                  $sql = "SELECT view_offer
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['view_offer'] == 0 )
                       {
                             return 0;
                       }
                  else
                       {
                             return 1;
                       }

                  break;

             case 'VIEW_BID_HISTORY':

                  $sql = "SELECT view_bid_history
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['view_bid_history'] == 0 )
                       {
                             return 0;
                       }
                  else
                       {
                             return 1;
                       }

                  break;

             case 'NEW':

                  $sql = "SELECT new
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['new'] == 0 )
                       {
                             return 0;
                       }
                  else
                       {
                             return 1;
                       }

                  break;

             case 'BID':

                  $sql = "SELECT bid
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['bid'] == 0 )
                       {
                             return 0;
                       }
                  else
                       {
                             return 1;
                       }

                  break;

             case 'COMMENT':

                  $sql = "SELECT comment
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['comment'] == 0 )
                       {
                             return 0;
                       }
                  else
                       {
                             return 1;
                       }

                  break;

             case 'MOVE':

                  $sql = "SELECT move
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['move'] == 0 )
                       {
                             return 0;
                       }
                  else
                       {
                             return 1;
                       }

                  break;

             case 'DELETE_OFFER':

                  $sql = "SELECT delete_offer
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['delete_offer'] == 0 )
                       {
                             return 0;
                       }
                  else
                       {
                             return 1;
                       }

                  break;

             case 'DELETE_BID':

                  $sql = "SELECT delete_bid
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['delete_bid'] == 0 )
                       {
                             return 0;
                       }
                  else
                       {
                             return 1;
                       }

                  break;

             case 'SPECIAL':

                  $sql = "SELECT special
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['special'] == 0 )
                       {
                             return 0;
                       }
                  else
                       {
                             return 1;
                       }

                  break;

             case 'DIRECT_SELL':

                  $sql = "SELECT direct_sell
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['direct_sell'] == 0 )
                       {
                             return 0;
                       }
                  else
                       {
                             return 1;
                       }

                  break;

             case 'IMAGE_UPLOAD':

                  $sql = "SELECT image_upload
                          FROM " . AUCTION_ROLE_TABLE . "
                          WHERE  auction_role_title='" . $role . "'";

                  if ( !($result = $db->sql_query($sql)) )
                       {
                            message_die(GENERAL_ERROR, 'Could not query role permissions', '', __LINE__, __FILE__, $sql);
                       }  // End if

                  $row = $db->sql_fetchrow($result);

                  if ( $row['image_upload'] == 0 )
                       {
                             return 0;
                       }
                  else
                       {
                             return 1;
                       }

                  break;
         }
}

function break_text($text)
{
   // Max length of the words
   $maxlenword = 80;

   $search   = array();
   $search[] = "/\[\/?(quote|code|list|img|size|url)\]/";
   $search[] = "/\[(quote|list|size|url)=[^\]]+\]/";

   $text = preg_replace($search, '', $text);

   $text = str_replace('\'', '\\\'', $text);
   $text = preg_replace("/([^ \t]{{$maxlenword},})/e", "wordwrap('\\1', $maxlenword, ' ', 1);", $text);
   $text = str_replace('\\\'', '\'', $text);

   return $text;
}

function room_pagination($room_id, $start)
     {
          global $db, $auction_config_data, $template, $lang;

          $sql = "SELECT auction_offer_title
                  FROM " . AUCTION_OFFER_TABLE . "
                  WHERE FK_auction_offer_room_id = " . $room_id . " AND
                        auction_offer_time_stop>" . time() . " AND
                        auction_offer_time_start<" . time() . " AND
                        auction_offer_paid = 1 AND
                        auction_offer_state = " . AUCTION_OFFER_UNLOCKED . "";

          if ( !($result = $db->sql_query($sql)) )
               {
                    message_die(GENERAL_ERROR, 'Could not query pagination-info', '', __LINE__, __FILE__, $sql);
               }  // End if

          $offer_row = $db->sql_fetchrow($result);
          $offer_count = count($offer_row)+1;

          $pages = ceil($offer_count/$auction_config_data['auction_room_pagination']);
          if ($offer_count <= $auction_config_data['auction_room_pagination'] )
               {
                   $pages = 1;
               }

          for ($i=0; $i< $pages;$i++)
               {
                    if ( $start > $i )
                         {
                              $template->assign_block_vars('pagination_before',array(
                                   'L_AUCTION_ROOM_PAGE_NUMBER' => $i+1,
                                   'U_AUCTION_ROOM_PAGE_NUMBER' => append_sid("auction_room.php?" . POST_AUCTION_ROOM_URL . "=" . $room_id . "&start=" . $i*$auction_config_data['auction_room_pagination'] . "") ));
                         }

                    if ( $start == $i )
                         {
                              $template->assign_block_vars('pagination_active',array(
                                   'L_AUCTION_ROOM_PAGE_NUMBER' => $i+1 ));
                         }
                    if ( $start < $i )
                         {
                              $template->assign_block_vars('pagination_after',array(
                                   'L_AUCTION_ROOM_PAGE_NUMBER' => $i+1,
                                   'U_AUCTION_ROOM_PAGE_NUMBER' => append_sid("auction_room.php?" . POST_AUCTION_ROOM_URL . "=" . $room_id . "&start=" . $i*$auction_config_data['auction_room_pagination'] . "") ));
                         }
               }

         $template->assign_vars(array(
              'L_AUCTION_ROOM_PAGE' => $lang['auction_room_page']));
     }

function settle_credit($user_id,$settle_amount, $credit_id)
     {
          global $db, $auction_config_data, $template, $lang, $userdata;

                 $sql = "SELECT *
                         FROM " . AUCTION_ACCOUNT_TABLE . "
                         WHERE fk_auction_account_creditor_id=2 AND
                               fk_auction_account_debitor_id =" . $user_id . " AND
                                auction_account_auction_amount> auction_account_amount_paid AND
                               auction_account_action='" . ACTION_PERCENT . "' OR
                               auction_account_action='" . ACTION_INITIAL . "'";

                 if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not grab account debit information', '', __LINE__, __FILE__, $sql);
                     } // if

                 $total_debits = 0;
                 while( $row = $db->sql_fetchrow($result) )
                      {
                           $debit_rowset[] = $row;
                           $total_debits++;
                      }

                 for($i = 0; $i < $total_debits; $i++)
                      {
                        if ( $debit_rowset[$i]['auction_account_auction_amount']-$debit_rowset[$i]['auction_account_amount_paid']>0 AND
                             $settle_amount>0 )
                           {
                             if ($debit_rowset[$i]['auction_account_auction_amount']-$debit_rowset[$i]['auction_account_amount_paid'] < $settle_amount)
                                  {
                                        // Settle fee
                                        $sql = "UPDATE " . AUCTION_ACCOUNT_TABLE . "
                                                SET auction_account_amount_paid=auction_account_auction_amount,
                                     	            auction_account_amount_paid_by= " . $userdata['user_id'] . "
                                                WHERE  pk_auction_account_id=" .  $debit_rowset[$i]['pk_auction_account_id'] . "";

                                        if( !($result = $db->sql_query($sql)) )
                                             {
                                                  message_die(GENERAL_ERROR, 'Could not settle fee', '', __LINE__, __FILE__, $sql);
                                             } // if
                                        $deduction = $debit_rowset[$i]['auction_account_auction_amount']-$debit_rowset[$i]['auction_account_amount_paid'];

                                  }
                             else
                                  {
                                        // Settle fee
                                        $sql = "UPDATE " . AUCTION_ACCOUNT_TABLE . "
                                                SET auction_account_amount_paid=auction_account_amount_paid+ " . $settle_amount . ",
                                     	            auction_account_amount_paid_by= " . $userdata['user_id'] . "
                                                WHERE  pk_auction_account_id=" .  $debit_rowset[$i]['pk_auction_account_id'] . "";

                                        if( !($result = $db->sql_query($sql)) )
                                             {
                                                  message_die(GENERAL_ERROR, 'Could not settle fee', '', __LINE__, __FILE__, $sql);
                                             } // if
                                        $deduction = $settle_amount;

                                  }
                             // deduct credit
                                        $sql = "UPDATE " . AUCTION_ACCOUNT_TABLE . "
                                                SET auction_account_amount_paid=auction_account_amount_paid+" . $deduction . ",
                                             	    auction_account_amount_paid_by= " . $userdata['user_id'] . "
                                                WHERE  pk_auction_account_id=" .  $credit_id . "";

                                        if( !($result = $db->sql_query($sql)) )
                                             {
                                                  message_die(GENERAL_ERROR, 'Could not deduct fee from credit', '', __LINE__, __FILE__, $sql);
                                             } // if
                                        $settle_amount = $settle_amount-$debit_rowset[$i]['auction_account_auction_amount']-$debit_rowset[$i]['auction_account_amount_paid'];
                             }
                      }
                // clean credit
                $sql = "UPDATE " . AUCTION_ACCOUNT_TABLE . "
                        SET auction_account_auction_amount=auction_account_auction_amount-auction_account_amount_paid,
                            auction_account_amount_paid=0
                        WHERE  pk_auction_account_id=" .  $credit_id . "";

                if( !($result = $db->sql_query($sql)) )
                     {
                          message_die(GENERAL_ERROR, 'Could not clean credit', '', __LINE__, __FILE__, $sql);
                     } // if

     }
?>