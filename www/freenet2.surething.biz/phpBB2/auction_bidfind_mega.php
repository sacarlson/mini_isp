<HTML>
<HEAD>
<TITLE>BidFind Export for phpbb-auction</TITLE>
</HEAD>
<BODY>
<VERSION>V.1.6<BR>


<?php
     define('IN_PHPBB', true);
     $phpbb_root_path = './';
     include_once($phpbb_root_path . 'auction/auction_common.php');

     // START Grab all the offer-data
     $sql = "SELECT t.*, u.username, u.user_id, u2.username as maxbidder_user_name, u2.user_id as maxbidder_user_id
                      FROM (" . AUCTION_OFFER_TABLE . " t
                      LEFT JOIN " . USERS_TABLE . " u ON u.user_id = t.FK_auction_offer_user_id
                      LEFT JOIN " . USERS_TABLE . " u2 ON u2.user_id = t.FK_auction_offer_last_bid_user_id)
                      WHERE auction_offer_time_stop>" . time() . "
                           AND auction_offer_time_start<" . time() . "
                           AND auction_offer_paid = 1
                           AND auction_offer_state = " . AUCTION_OFFER_UNLOCKED . "
                      ORDER BY t.auction_offer_time_stop;";

     if ( !($result = $db->sql_query($sql)) )
     {
        message_die(GENERAL_ERROR, '1 Could not obtain topic information', '', __LINE__, __FILE__, $sql);
     }

     $total_offers = 0;
     while( $row = $db->sql_fetchrow($result) )
     {
         $auction_offer_rowset[] = $row;
         $total_offers++;
     }
     $db->sql_freeresult($result);
     $total_offers += $total_announcements;


// Dump out the page
if( $total_offers )
{
    for($i = 0; $i < $total_offers; $i++)
    {
        echo "<ITEM>auction";
        $auction_offer_id = $auction_offer_rowset[$i]['PK_auction_offer_id'];
        $auction_offer_title = ( count($orig_word) ) ? preg_replace($orig_word, $replacement_word, $auction_offer_rowset[$i]['auction_offer_title']) : $auction_offer_rowset[$i]['auction_offer_title'];
        $view_auction_offer_url = append_sid("auction_offer_view.$phpEx?" . POST_AUCTION_OFFER_URL . "=$auction_offer_id");
        echo "<ITEMURL>" . $view_auction_offer_url;
        echo "<ITEMTITLE>" . $auction_offer_title . "<br>";
    }
}
?>

</BODY>
</HTML>