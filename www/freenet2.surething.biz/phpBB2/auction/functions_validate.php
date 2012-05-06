<?php
/***************************************************************************
 *                           functions_validate.php
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


function checkAuctionDates($month_start, $day_start, $year_start, $month_stop, $day_stop, $year_stop)
// Checks the a start and stop-date
     {
          global $lang;

          checkAuctionDatesStart($month_start, $day_start, $year_start);

          if ( ( $month_stop>12 ) || ( $month_stop<1 ) || ( $month_start>12 ) || ( $month_start<1 ))
              {
                   message_die(GENERAL_MESSAGE, 1 . $lang['auction_invalid_date']);
              } // End if
          if ( ( $day_stop>31 ) || ( $day_stop<1 ) || ( $day_start>31 ) || ( $day_start<1 ))
              {
                   message_die(GENERAL_MESSAGE, 2 . $lang['auction_invalid_date']);
              } // End if
          if ( ($month_stop==2 ) || ($month_stop==4 ) || ($month_stop==6 ) || ($month_stop==9 ) || ($month_stop==11) )
               {
                   if ($day_stop>30)
                       {
                            message_die(GENERAL_MESSAGE, 3 . $lang['auction_invalid_date']);
                       }  // End if
               }  // End if
          if ( ($month_start==2 ) || ($month_start==4 ) || ($month_start==6 ) || ($month_start==9 ) || ($month_start==11) )
               {
                   if ($day_start>30)
                       {
                            message_die(GENERAL_MESSAGE, 4 . $lang['auction_invalid_date']);
                       } // End if
               } // End if
          if ( ($year_start==$year_stop) && ($month_start>$month_stop) )
                       {
                            message_die(GENERAL_MESSAGE, 5 . $lang['auction_invalid_date']);
                       } // End if
          if ( ($year_start==$year_stop) && ($month_start==$month_stop) && ($day_start>$day_stop))
                       {
                            message_die(GENERAL_MESSAGE, 6 . $lang['auction_invalid_date']);
                       } // End if
     } // End function

function checkAuctionDatesStart($month_start, $day_start, $year_start)
     {
          global $lang;
          $date_time_array = getdate();
          $month = $date_time_array['mon'];
          $day = $date_time_array['mday'];
          $year = $date_time_array['year'];
    
          if ( ( $month_start>12 ) || ( $month_start<1 ))
              {
                   message_die(GENERAL_MESSAGE, 7 . $lang['auction_invalid_date']);
              }  // End if
          if ( ( $day_start>31 ) || ( $day_start<1 ))
              {
                   message_die(GENERAL_MESSAGE, 8 . $lang['auction_invalid_date']);
              } // End if

          if ( ($month_start==2 ) || ($month_start==4 ) || ($month_start==6 ) || ($month_start==9 ) || ($month_start==11) )
               {
                    //          if ( mcal_is_leap_year($year_start) )
                    //               function is not working
                    // Should be a better solution till next version otherwise you shouldnt run that programm after 2044 ;)
                    // Get Febs for leap year
                    if (  $month_start==2 AND
                        ( $year_start==2008 OR
                          $year_start==2012 OR
                          $year_start==2016 OR
                          $year_start==2020 OR
                          $year_start==2024 OR
                          $year_start==2028 OR
                          $year_start==2032 OR
                          $year_start==2036 OR
                          $year_start==2040 OR
                          $year_start==2044 ))
                         {
                              if ($day_start>29)
                                   {
                                        message_die(GENERAL_MESSAGE, $lang['auction_invalid_date']);
                                   } // End if
                         }
                     // Get Febs for not leap-years
                     elseif ($month_start==2 AND $day_start>28)
                       {
                            message_die(GENERAL_MESSAGE, $lang['auction_invalid_date']);
                       } // End if
                     // Get other 30 days month
                     elseif ($day_start>30)
                       {
                            message_die(GENERAL_MESSAGE, 9 . $lang['auction_invalid_date']);
                       } // End if
                       
               } // End if
          if ( $year>$year_start)
            {
                            message_die(GENERAL_MESSAGE, 10 . $lang['auction_invalid_date']);
            } // End if
          if (( $year<=$year_start ) && ( $month > $month_start) )
            {
                            message_die(GENERAL_MESSAGE, 11 . $lang['auction_invalid_date']);
            } // End if

          if (( $year<=$year_start ) && ( $month == $month_start) && ( $day>$day_start) )
            {
                            message_die(GENERAL_MESSAGE, 12 . $lang['auction_invalid_date']);
            } // End if
     } // End function

function checkAuctionDatesStop($month_stop, $day_stop, $year_stop)
     {
          global $lang;

          $date_time_array = getdate();
          $month = $date_time_array['mon'];
          $day = $date_time_array['mday'];
          $year = $date_time_array['year'];
    
          if ( ( $month_stop>12 ) || ( $month_stop<1 ))
              {
                   message_die(GENERAL_MESSAGE, $lang['auction_invalid_date'] . "3.1");
              } // End if
          if ( ( $day_stop>31 ) || ( $day_stop<1 ))
              {
                   message_die(GENERAL_MESSAGE, $lang['auction_invalid_date']. "3.2");
              }  // End if
          if ( ($month_stop==2 ) || ($month_stop==4 ) || ($month_stop==6 ) || ($month_stop==9 ) || ($month_stop==11) )
               {
                    //          if ( mcal_is_leap_year($year_start) ) - function is not working
                    // Should be a better solution till next version otherwise you shouldnt run that programm after 2044 ;)
                    // Get Febs for leap year
                    if (  $month_stop==2 AND ( $year_stop==2008 OR $year_stop==2012 OR
                          $year_stop==2016 OR $year_stop==2020 OR $year_stop==2024 OR $year_stop==2028 OR
                          $year_stop==2032 OR $year_stop==2036 OR $year_stop==2040 OR $year_stop==2044 ))
                         {
                              if ($day_start>29)
                                   {
                                        message_die(GENERAL_MESSAGE, $lang['auction_invalid_date']);
                                   } // End if
                         }
                     // Get Febs for not leap-years
                     elseif ($month_stop==2 AND $day_stop>28)
                       {
                            message_die(GENERAL_MESSAGE, $lang['auction_invalid_date']);
                       } // End if
                     // Get other 30 days month
                     elseif ($day_stop>30)
                       {
                            message_die(GENERAL_MESSAGE, $lang['auction_invalid_date']);
                       } // End if
             }
          if ( $year>$year_stop)
            {
                            message_die(GENERAL_MESSAGE, $lang['auction_invalid_date']. "3.4");
            } // End if
          if (( $year<=$year_stop ) && ( $month > $month_stop) )
            {
                            message_die(GENERAL_MESSAGE, $lang['auction_invalid_date']. "3.5");
            } // End if
          if (( $year<=$year_stop ) && ( $month == $month_stop) && ( $day>$day_stop) )
            {
                            message_die(GENERAL_MESSAGE, $lang['auction_invalid_date']. $day ."-". $day_stop . "3.5");
            } // End if
     }  // End function
         
?>