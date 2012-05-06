<script language="javascript">
<!--
function jumpbox(){
var thebox=document.framecombo
window.parent.location=thebox.framecombo2.options[thebox.framecombo2.selectedIndex].value
}
//-->
</script>

<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
     <tr>
          <td align="left" valign="bottom">
               <span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span>
          </td>
     </tr>
</table>

<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
     <tr>
        <td width="15%" valign="top">
           <!-- BEGIN auction_drop_down_rooms_block -->
           <table width="100%" cellpadding="1" class="forumline">
                <tr>
                     <th class="thHead">{auction_drop_down_rooms_block.L_AUCTION_ROOM_BLOCK_TITLE}</th>
                </tr>
                <tr>
                     <td>
                          <table cellspacing="0" width="100%">
                               <tr>
                                    <td class="row1" align="left">
                                        {auction_drop_down_rooms_block.AUCTION_ROOM_BLOCK_TEXT}
                                    </td>
                               </tr>
                          </table>
                     </td>
                </tr>
           </table>
           <br />
           <!-- END auction_drop_down_rooms_block -->

           <!-- BEGIN auction_rooms_block -->
           <table width="100%" cellpadding="1" class="forumline">
                <tr>
                     <th class="thHead">{auction_rooms_block.L_AUCTION_ROOM_BLOCK_TITLE}</th>
                </tr>
                <tr>
                     <td>
                          <table cellspacing="0" width="100%">
                               <tr>
                                    <td class="row1" align="left">
                                        <a href="{auction_rooms_block.U_AUCTION_HOME}" class="genmed">{auction_rooms_block.L_AUCTION_HOME}</a>
                                    </td>
                               </tr>

                               <!-- BEGIN room -->
                               <tr>
                                    <td class="row1" align="left">
                                        <a href="{auction_rooms_block.room.U_AUCTION_ROOM_TITLE}" class="genmed">{auction_rooms_block.room.AUCTION_ROOM_TITLE}</a>
                                    </td>
                               </tr>
                               <!-- END room -->
                          </table>
                     </td>
                </tr>
           </table>
           <br />
           <!-- END auction_rooms_block -->

           <!-- BEGIN close_to_end_block -->
           <table width="100%" cellpadding="1" class="forumline">
                <tr>
                     <th class="thHead">{close_to_end_block.AUCTION_CLOSE_TO_END}</th>
                </tr>
                <tr>
                     <td>
                          <table cellspacing="0" width="100%">
                               <!-- BEGIN close_to_end_block_offer -->
                                    <tr>
                                         <td class="row1" align="left">
                                               <span class="genmed">{close_to_end_block.close_to_end_block_offer.AUCTION_OFFER_CLOSE_TO_END_TITLE}</span>
                                         </td>
                                         <td  class="row1" align="right">
                                              <span class="gensmall">{close_to_end_block.close_to_end_block_offer.AUCTION_OFFER_CLOSE_TO_END_TIME}</span>
                                         </td>
                                    </tr>
                               <!-- END close_to_end_block_offer -->
                          </table>
                     </td>
                </tr>
           </table>
           <br />
           <!-- END close_to_end_block -->

           <!-- BEGIN statistic_block -->
           <table width="100%" cellpadding="1" cellspacing="1" class="forumline">
                <tr>
                     <th colspan="5" class="thHead">{statistic_block.L_AUCTION_STATISTICS}</th>
                </tr>
                <tr>
                     <td class="row1" align="left">
                           <span class="gensmall">{statistic_block.L_AUCTION_STATISTIC_TOTAL_OFFERS}</span><span class="gensmall">&nbsp;{statistic_block.AUCTION_STATISTIC_TOTAL_OFFERS}<br></span>
                           <span class="gensmall">{statistic_block.L_AUCTION_STATISTIC_TOTAL_BIDS}</span><span class="gensmall">&nbsp;{statistic_block.AUCTION_STATISTIC_TOTAL_BIDS}<br></span>
                           <span class="gensmall">{statistic_block.L_AUCTION_STATISTIC_CURRENT_VOLUME}</span><span class="gensmall">&nbsp;{statistic_block.AUCTION_CURRENT_VOLUME}<br></span>
                           <span class="gensmall">{statistic_block.L_AUCTION_STATISTIC_OVERALL_VOLUME}</span><span class="gensmall">&nbsp;{statistic_block.AUCTION_OVERALL_VOLUME}<br></span>
                     </td>
                </tr>
                <tr>
                     <th colspan="5" class="thHead">{statistic_block.L_STATISTIC_HIGHEST_BID_DESCRIPTION}</th>
                </tr>
                <tr>
                     <td class="row1" align="left">
                           <a href="{statistic_block.AUCTION_STATISTIC_HIGHEST_BID_TITLE_LINK}" class="gen">{statistic_block.AUCTION_STATISTIC_HIGHEST_BID_OFFER_TITLE}</a><br>
                           <img src="{statistic_block.IMAGE_UP}" /><span class="gensmall"><font color="red">&nbsp;{statistic_block.AUCTION_STATISTIC_HIGHEST_BID_BID_PRICE}</font></span><br>
                     </td>
                </tr>
                <tr>
                     <th colspan="5" class="thHead">{statistic_block.L_STATISTIC_LOWEST_BID_DESCRIPTION}</th>
                </tr>
                <tr>
                     <td class="row1" align="left">
                           <a href="{statistic_block.AUCTION_STATISTIC_LOWEST_BID_TITLE_LINK}" class="gen">{statistic_block.AUCTION_STATISTIC_LOWEST_BID_OFFER_TITLE}</a><br>
                           <img src="{statistic_block.IMAGE_DOWN}" /><span class="genmed"><font color="lightgreen">&nbsp;{statistic_block.AUCTION_STATISTIC_LOWEST_BID_BID_PRICE}</font></span><br>
                     </td>
                </tr>
           </table>
           <br />
           <!-- END statistic_block -->


           <!-- BEGIN search_block -->
           <table width="100%" cellpadding="1" cellspacing="0" class="forumline">
                <form method="post" action="{search_block.S_AUCTION_SEARCH}">
                     <tr>
                          <th colspan="5" class="thHead">{search_block.L_AUCTION_SEARCH}</th>
                     </tr>
                     <tr>
                          <td class="row1" align="left" valign="middle" height="28"><span class="gensmall">{search_block.L_AUCTION_SEARCH_ITEM}</span></td>
                          <td class="row1" align="left" valign="middle" height="28"><input class="post" type="text" name="auction_item" size="15" /></td>
                     </tr>
                     <tr>
                          <td class="row1" align="left" valign="middle" height="28"><span class="gensmall">{search_block.L_AUCTION_SEARCH_SELLER}</span></td>
                          <td class="row1" align="left" valign="middle" height="28"><input class="post" type="text" name="auction_username" size="15" /></td>
                     </tr>
                     <tr>
                          <td class="row1" colspan="2" align="center" valign="middle" height="28"><input type="submit" class="mainoption" name="login" value={search_block.L_AUCTION_SEARCH} /></td>
                     </tr>
                </form>
                <form method="post" action="{search_block.S_AUCTION_QUICK_VIEW}">
                     <tr>
                          <td class="row1" align="left" valign="middle" height="28"><span class="gensmall">{search_block.L_AUCTION_QUICK_VIEW_NUMBER}</span></td>
                          <td class="row1" align="left" valign="middle" height="28"><input class="post" type="text" name="auction_quickview_id" size="15" /></td>
                     </tr>
                     <tr>
                          <td class="row1" colspan="2" align="center" valign="middle" height="28"><input type="submit" class="mainoption" name="login" value={search_block.L_AUCTION_QUICK_VIEW_FIND} /></td>
                     </tr>
                </form>
                     <tr>
                          <td class="row1" colspan="2" align="center">
                               <a href="{search_block.S_AUCTION_SEARCH_UNBIDDED}" class="gensmall">{search_block.L_AUCTION_SEARCH_UNBIDDED}</a>
                          </td>
                     </tr>
                     <tr>
                          <td class="row1" colspan="2" align="center">
                               <a href="{search_block.U_AUCTION_SITEMAP}" class="gensmall">{search_block.L_AUCTION_SITEMAP}</a><br>
                               <a href="{search_block.U_AUCTION_SEARCH_NEW_OFFERS}" class="gensmall">{search_block.L_AUCTION_SEARCH_NEW_OFFERS}</a>
                          </td>
                     </tr>


           </table>
           <br />
           <!-- END search_block -->

        </td>
         <td align="right" valign="top" width="60%">

                <table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
                <!-- BEGIN ticker_block -->
                     <tr>
                          <th colspan="5" class="thHead">{ticker_block.L_OFFER_TICKER}</th>
                     </tr>
                     <tr>
                         <td colspan="5" height="28" align="center" class="row1"><marquee direction="left" scrollamount="3" >
                         <!-- BEGIN ticker_block_offer -->
                              <a href="{ticker_block.ticker_block_offer.AUCTION_TICKER_OFFER_URL}" class="gensmall">{ticker_block.ticker_block_offer.AUCTION_TICKER_OFFER}</a>
                              <span class="gensmall"><font color="{ticker_block.AUCTION_TICKER_FONT_COLOR2}">{ticker_block.ticker_block_offer.AUCTION_TICKER_OFFER_FIRST}</font></span>
                              <span class="gensmall"><font color="{ticker_block.AUCTION_TICKER_FONT_COLOR3}">( {ticker_block.ticker_block_offer.AUCTION_TICKER_OFFER_LAST} )</font></span>
                              <span class="gensmall">&nbsp;&nbsp;&nbsp;</span>
                         <!-- END ticker_block_offer -->
                         </marquee></td>
                     </tr>
                <!-- END ticker_block -->
                </table>
                <br>