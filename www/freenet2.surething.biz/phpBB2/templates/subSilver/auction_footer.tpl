          </td>
         <td width="15%" valign="top">

              <!-- BEGIN myauctions_block -->
              <table width="100%" cellpadding="1" cellspacing="1" class="forumline">
                    <tr> 
                         <th colspan="5" class="thHead">{myauctions_block.L_AUCTION_MYAUCTIONS}</th>
                    </tr>
                    <tr>
                         <td class="row1" align="left">
                           <a href="{myauctions_block.U_MY_AUCTIONS}" span class="gensmall">{myauctions_block.L_MY_AUCTIONS}</a><br>
                           <a href="{myauctions_block.U_MY_WATCHLIST}" span class="gensmall">{myauctions_block.L_MY_WATCHLIST}</a><br>
                           <a href="{myauctions_block.U_MY_RATINGS}" span class="gensmall">{myauctions_block.L_MY_RATINGS}</a><br>
                           <a href="{myauctions_block.U_MY_STORE}" span class="gensmall">{myauctions_block.L_MY_STORE}</a><br>
                           <a href="{myauctions_block.U_MY_ACCOUNT}" span class="gensmall">{myauctions_block.L_MY_ACCOUNT}</a><br>
                           <a href="{myauctions_block.U_AUCTION_FAQ}" span class="gensmall">{myauctions_block.L_AUCTION_FAQ}</a><br>
                           <a href="{myauctions_block.U_AUCTION_TERMS}" span class="gensmall">{myauctions_block.L_AUCTION_TERMS}</a><br>
                        </td>
                    </tr>
              </table>
              <br />
              <!-- END myauctions_block -->

              <!-- BEGIN special_block -->
              <table width="100%" cellpadding="1" cellspacing="1" class="forumline">
                    <tr> 
                      <th colspan="5" class="thHead">{special_block.L_AUCTION_SPECIAL_OFFERS_TITLE}</th>
                    </tr>
                    <tr>
                         <td>
                              <table width="100%" cellspacing="0">
                              <!-- BEGIN special_offer_block -->
                                   <tr>
                                        <td class="row1" align="center">
                                             {special_block.special_offer_block.AUCTION_SPECIAL_IMAGE}
                                        </td>
                                        <td class="row1" align="left">
                                             <a href="{special_block.special_offer_block.U_AUCTION_SPECIAL_TITLE}" class="forumlink">{special_block.special_offer_block.AUCTION_SPECIAL_TITLE}</a><br>
                                             <span class="gensmall">{special_block.special_offer_block.AUCTION_SPECIAL_END}</span>
                                        </td>
                                   </tr>
                              <!-- END special_offer_block -->
                              </table>
                         </td>
                    </tr>
              </table>
              <br />
              <!-- END special_block -->

              <!-- BEGIN last_bids_block -->
              <table width="100%" cellpadding="1" cellspacing="1" class="forumline">
                    <tr>
                         <th colspan="5" class="thHead">{last_bids_block.L_AUCTION_LAST_BIDS}</th>
                    </tr>
                    <!-- BEGIN last_bids_block_offer -->
                    <tr>
                         <td class="row1" align="left">
                           <a href="{last_bids_block.last_bids_block_offer.AUCTION_OFFER_LAST_BIDS_TITLE_URL}" span class="gensmall">{last_bids_block.last_bids_block_offer.AUCTION_OFFER_LAST_BIDS_TITLE}</a>&nbsp;&nbsp;
                           <span class="gensmall"><br>{last_bids_block.last_bids_block_offer.AUCTION_OFFER_LAST_BIDS_TIME}</span>
                        </td>
                    </tr>
                    <!-- END last_bids_block_offer -->
              </table>
              <br />
              <!-- END last_bids_block -->


              <!-- BEGIN calendar_block -->
              <table width="100%" class="forumline">
                      <tr>
                              <th colspan="8" class="thHead">{calendar_block.AUCTION_CALENDER_MONTH_YEAR}</span></th>
                    </tr>
                    <tr>
                          <th class="thCornerL" align="center" width="14%">{calendar_block.L_AUCTION_CALENDER_MO}</th>
                          <th class="thTop" align="center" width="14%">{calendar_block.L_AUCTION_CALENDER_TU}</th>
                          <th class="thTop" align="center" width="14%">{calendar_block.L_AUCTION_CALENDER_WE}</th>
                          <th class="thTop" align="center" width="14%">{calendar_block.L_AUCTION_CALENDER_TH}</th>
                          <th class="thTop" align="center" width="14%">{calendar_block.L_AUCTION_CALENDER_FR}</th>
                          <th class="thTop" align="center" width="14%">{calendar_block.L_AUCTION_CALENDER_SA}</th>
                          <th class="thCornerR" align="center" width="14%">{calendar_block.L_AUCTION_CALENDER_SU}</th>
                    </tr>
                    {calendar_block.AUCTION_CALENDER_DAY_BLOCKS}
                  </table>
              <br />
              <!-- END calendar_block -->

           <!-- BEGIN newest_offers_block -->
           <table width="100%" cellpadding="1" class="forumline">
                <tr>
                     <th class="thHead">{newest_offers_block.AUCTION_NEWEST_OFFER}</th>
                </tr>
                <tr>
                     <td>
                          <table cellspacing="0" width="100%">
                               <!-- BEGIN newest_offers_block_offer -->
                                    <tr>
                                         <td class="row1" align="left">
                                               <a href="{newest_offers_block.newest_offers_block_offer.U_AUCTION_NEWEST_OFFER_TITLE}" class="genmed">{newest_offers_block.newest_offers_block_offer.AUCTION_NEWEST_OFFER_TITLE}</a>
                                              <span class="gensmall"><br>{newest_offers_block.newest_offers_block_offer.AUCTION_NEWEST_OFFER_TIME}</span>
                                         </td>
                                    </tr>
                               <!-- END newest_offers_block_offer -->
                          </table>
                     </td>
                </tr>
           </table>
           <br />
           <!-- END newest_offers_block -->

              <!-- BEGIN prices_block -->
              <table width="100%" cellpadding="1" class="forumline">
                   <tr>
                      <th class="thHead">{prices_block.L_AUCTION_PRICES}</th>
                    </tr>
                    <tr>
                         <td width="100%">
                              <table cellspacing="0"  width="100%">
                                   <tr>
                                        <td class="row1" align="left"><span class="gensmall">{prices_block.L_AUCTION_PRICE_BASIC}</span></td>
                                        <td class="row1" align="right"><span class="gensmall">{prices_block.AUCTION_PRICE_BASIC}</span></td>
                                        <td class="row1" align="left"><span class="gensmall">{prices_block.AUCTION_CURRENCY}</span></td>
                                   </tr>
                                   <tr>
                                        <td class="row1" align="left"><span class="gensmall">{prices_block.L_AUCTION_PRICE_BOLD}</span></td>
                                        <td class="row1" align="right"><span class="gensmall">{prices_block.AUCTION_PRICE_BOLD}</span></td>
                                        <td class="row1" align="left"><span class="gensmall">{prices_block.AUCTION_CURRENCY}</span></td>
                                   </tr>
                                   <tr>
                                        <td class="row1" align="left"><span class="gensmall">{prices_block.L_AUCTION_PRICE_ON_TOP}</span></td>
                                        <td class="row1" align="right"><span class="gensmall">{prices_block.AUCTION_PRICE_ON_TOP}</span></td>
                                        <td class="row1" align="left"><span class="gensmall">{prices_block.AUCTION_CURRENCY}</span></td>
                                   </tr>
                                   <tr>
                                        <td class="row1" align="left"><span class="gensmall">{prices_block.L_AUCTION_PRICE_SPECIAL}</span></td>
                                        <td class="row1" align="right"><span class="gensmall">{prices_block.AUCTION_PRICE_SPECIAL}</span></td>
                                        <td class="row1" align="left"><span class="gensmall">{prices_block.AUCTION_CURRENCY}</span></td>
                                   </tr>
                                   <tr>
                                        <td class="row1" align="left"><span class="gensmall">{prices_block.L_AUCTION_PRICE_DIRECT_SELL}</span></td>
                                        <td class="row1" align="right"><span class="gensmall">{prices_block.AUCTION_PRICE_DIRECT_SELL}</span></td>
                                        <td class="row1" align="left"><span class="gensmall">{prices_block.AUCTION_CURRENCY}</span></td>
                                   </tr>
                                   <tr>
                                        <td class="row1" colspan="3" align="left"><span class="gensmall"><br>{prices_block.L_AUCTION_PAYMENT_ACCEPT}</td>
                                   </tr>
                                   <!-- BEGIN user_points -->
                                   <tr align="center">
                                        <td colspan="3" class="row1"><span class="gensmall">{prices_block.user_points.L_AUCTION_PAYMENTSYSTEM_USER_POINTS}</span></td>
                                   </tr>
                                   <!-- END user_points -->
                                   <!-- BEGIN paypal -->
                                   <tr align="center">
                                        <td colspan="3" class="row1"><img src="{prices_block.paypal.PAYPAL_IMAGE}" width="80" /></td>
                                   </tr>
                                   <!-- END paypal -->
                                   <!-- BEGIN moneybooker -->
                                   <tr align="center">
                                        <td colspan="3" class="row1"><img src="{prices_block.moneybooker.MONEYBOOKER_IMAGE}" width="80" /></td>
                                   </tr>
                                   <!-- END moneybooker -->
                                   <!-- BEGIN debit -->
                                   <tr align="center">
                                        <td colspan="3" class="row1"><span class="gensmall">{prices_block.debit.L_AUCTION_DEBIT_ACCEPT}</span></td>
                                   </tr>
                                   <!-- END debit -->
                              </table>
                         </td>
                    </tr>
               </table>
               <br />
              <!-- END prices_block -->

         </td>
     </tr>
</table>
<br clear="all" />

<!--
    Do not edit the following lines
-->
<div align="center">
<a href="http://www.phpbb-auction.com" class="gensmall" target="_new"> phpBB-Auction</a>
<a href="http://www.php-styles.com" class="gensmall" target="_new">-</a>
<a href="http://www.tememento.de" class="gensmall" target="_new">&copy; FR</a>
</div>