                <!-- BEGIN direct_sellings -->
                <table width="100%" cellspacing="1" border="0" class="forumline">
                     <tr>
                         <th colspan="6" class="thHead">{L_DIRECT_BOUGHTS}</th>
                     </tr>
                     <tr>
                        <td class="catLeft"><span class="cattitle">{L_AUCTION_OFFER_TITLE}</span></td>
                        <td class="catLeft"><span class="cattitle">{L_AUCTION_OFFER_SELLER}</span></td>
                        <td class="catLeft"><span class="cattitle">{L_AUCTION_OFFER_DIRECT_SELL_PRICE}</span></td>
                     </tr>
                     <!-- BEGIN direct_boughts -->
                     <tr>
                       <td class="row1"><a href={direct_sellings.direct_boughts.U_AUCTION_OFFER_TITLE} class="genmed">{direct_sellings.direct_boughts.AUCTION_OFFER_TITLE}</a></td>
                       <td class="row1" align="center"><span class="genmed"><a href="{direct_sellings.direct_boughts.U_AUCTION_OFFER_USERNAME}" class="gensmall">{direct_sellings.direct_boughts.AUCTION_OFFER_USERNAME}</a></td>
                       <td class="row1" align="right"><span class="genmed">{direct_sellings.direct_boughts.AUCTION_OFFER_DIRECT_SELL_PRICE}</span></td>
                     </tr>
                     <!-- END direct_boughts -->
                </table>
                <br>
                <!-- END direct_sellings -->

                <table width="100%" cellspacing="1" border="0" class="forumline">
                     <tr>
                         <th colspan="6" class="thHead">{L_AUCTION_MYBIDS}</th>
                     </tr>
                     <tr>
                        <td class="catLeft"><span class="cattitle">{L_AUCTION_OFFER_TITLE}</span></td>
                        <td class="catLeft"><span class="cattitle">{L_AUCTION_OFFER_START}</span></td>
                        <td class="catLeft"><span class="cattitle">{L_AUCTION_OFFER_STOP}</span></td>
                        <td class="catLeft"><span class="cattitle">{L_AUCTION_OFFER_PRICE_LAST}</span></td>
                        <td class="catLeft"><span class="cattitle">{L_AUCTION_OFFER_PRICE_MYBID}</span></td>
                        <td class="catLeft"><span class="cattitle">{L_AUCTION_OFFER_STATUS}</span></td>
                     </tr>
                     <!-- BEGIN notstartedrow_bid -->
                     <tr> 
                       <td class="row1"><a href="{notstartedrow_bid.U_AUCTION_OFFER_TITLE}" class="genmed">{notstartedrow_bid.AUCTION_OFFER_TITLE}</a>
                            <br>
                            <span class="gensmall">{L_AUCTION_ROOM}</span>
                            <a href="{notstartedrow_bid.U_AUCTION_OFFER_ROOM_TITLE}" class="genmed">{notstartedrow_bid.AUCTION_OFFER_ROOM_TITLE}</a>
                       </td>
                       <td class="row1"><span class="genmed"><font color="red">{notstartedrow_bid.AUCTION_OFFER_TIME_START}</font></span></td>
                       <td class="row1"><span class="genmed"><font color="red">{notstartedrow_bid.AUCTION_OFFER_TIME_STOP}</font></span></td>
                       <td class="row1" align="right"><span class="genmed"><font color="red">{notstartedrow_bid.AUCTION_OFFER_PRICE_LAST}&nbsp;&nbsp;&nbsp;</font></span></td>
                       <td class="row1" align="center"><span class="genmed"><font color="red">{notstartedrow_bid.AUCTION_OFFER_PRICE_MYBID}&nbsp;&nbsp;&nbsp;</font></span></td>
                       <td class="row1" align="center"><span class="genmed"><font color="red">{L_AUCTION_OFFER_STATUS_NOT_STARTED_YET}</font></span></td>
                         </tr>
                     <!-- END notstartedrow_bid -->
                     <!-- BEGIN activerow_bid -->
                     <tr> 
                       <td class="row1"><a href="{activerow_bid.U_AUCTION_OFFER_TITLE}" class="genmed"><b>{activerow_bid.AUCTION_OFFER_TITLE}</b></a>
                            <br>
                            <span class="gensmall">{L_AUCTION_ROOM}</span>
                            <a href="{activerow_bid.U_AUCTION_OFFER_ROOM_TITLE}" class="genmed">{activerow_bid.AUCTION_OFFER_ROOM_TITLE}</a>
                       </td>
                       <td class="row1"><span class="genmed"><font color="{AUCTION_COLOR_3}"><b>{activerow_bid.AUCTION_OFFER_TIME_START}</b></font></span></td>
                       <td class="row1"><span class="genmed"><font color="{AUCTION_COLOR_3}"><b>{activerow_bid.AUCTION_OFFER_TIME_STOP}</b></font></span></td>
                       <td class="row1" align="right"><span class="genmed"><font color="{AUCTION_COLOR_3}"><b>{activerow_bid.AUCTION_OFFER_PRICE_LAST}&nbsp;&nbsp;&nbsp;</b></font></span></td>
                       <td class="row1" align="center"><span class="genmed"><font color="{AUCTION_COLOR_3}">{activerow_bid.AUCTION_OFFER_PRICE_MYBID}&nbsp;&nbsp;&nbsp;</font></span></td>
                       <td class="row1" align="center"><span class="genmed"><font color="{AUCTION_COLOR_3}"><b>{L_AUCTION_OFFER_STATUS_ACTIV}</b></font></span></td>
                     </tr>
                     <!-- END activerow_bid -->
                     <!-- BEGIN alreadyoverrow_bid -->
                     <tr> 
                       <td class="row1"><a href="{alreadyoverrow_bid.U_AUCTION_OFFER_TITLE}" class="genmed">{alreadyoverrow_bid.AUCTION_OFFER_TITLE}</a>
                            <br>
                            <span class="gensmall">{L_AUCTION_ROOM}</span>
                            <a href="{alreadyoverrow_bid.U_AUCTION_OFFER_ROOM_TITLE}" class="genmed">{alreadyoverrow_bid.AUCTION_OFFER_ROOM_TITLE}</a>
                       </td>
                       <td class="row1"><span class="genmed">{alreadyoverrow_bid.AUCTION_OFFER_TIME_START}</span></td>
                       <td class="row1"><span class="genmed">{alreadyoverrow_bid.AUCTION_OFFER_TIME_STOP}</span></td>
                       <td class="row1" align="right"><span class="genmed">{alreadyoverrow_bid.AUCTION_OFFER_PRICE_LAST}&nbsp;&nbsp;&nbsp;</span></td>
                       <td class="row1" align="center"><span class="genmed">{alreadyoverrow_bid.AUCTION_OFFER_PRICE_MYBID}&nbsp;&nbsp;&nbsp;</span></td>
                       <td class="row1" align="center"><span class="genmed">{L_AUCTION_OFFER_STATUS_ALREADY_OVER}</span></td>
                     </tr>
                     <!-- END alreadyoverrow_bid -->

                   </table>
                   <br>
                <table width="100%" cellspacing="1" border="0" class="forumline">
                     <tr>
                         <th colspan="6" class="thHead">{L_MYOFFERS}</th>
                     </tr>
                     <tr>
                        <td class="catLeft"><span class="cattitle">{L_AUCTION_OFFER_TITLE}</span></td>
                        <td class="catLeft"><span class="cattitle">{L_AUCTION_OFFER_START}</span></td>
                        <td class="catLeft"><span class="cattitle">{L_AUCTION_OFFER_STOP}</span></td>
                        <td class="catLeft"><span class="cattitle">{L_AUCTION_OFFER_PRICE_LAST}</span></td>
                        <td class="catLeft"><span class="cattitle">{L_AUCTION_OFFER_STATUS}</span></td>
                        <td class="catLeft"><span class="cattitle">{L_AUCTION_OFFER_PAID}</span></td>
                     </tr>
                     <!-- BEGIN notstartedrow -->
                     <tr> 
                       <td class="row1"><a href="{notstartedrow.U_AUCTION_OFFER_TITLE}" class="genmed">{notstartedrow.AUCTION_OFFER_TITLE}</a>
                            <br>
                            <span class="gensmall">{L_AUCTION_ROOM}</span>
                            <a href="{notstartedrow.U_AUCTION_OFFER_ROOM_TITLE}" class="genmed">{notstartedrow.AUCTION_OFFER_ROOM_TITLE}</a>
                       </td>
                       <td class="row1"><span class="genmed"><font color="red">{notstartedrow.AUCTION_OFFER_TIME_START}</font></span></td>
                       <td class="row1"><span class="genmed"><font color="red">{notstartedrow.AUCTION_OFFER_TIME_STOP}</font></span></td>
                       <td class="row1" align="right"><span class="genmed"><font color="red">{notstartedrow.AUCTION_OFFER_PRICE_LAST}&nbsp;&nbsp;&nbsp;</font></span></td>
                       <td class="row1" align="center"><span class="genmed"><font color="red">{L_AUCTION_OFFER_STATUS_NOT_STARTED_YET}</font></span></td>
                       <td class="row1" align="center"><span class="genmed"><font color="{AUCTION_COLOR_3}"><b>{notstartedrow.AUCTION_OFFER_PAID}</b></font></span></td>
                         </tr>
                     <!-- END notstartedrow -->
                     <!-- BEGIN activerow -->
                     <tr> 
                       <td class="row1"><a href="{activerow.U_AUCTION_OFFER_TITLE}" class="genmed"><b>{activerow.AUCTION_OFFER_TITLE}</b></a>
                            <br>
                            <span class="gensmall">{L_AUCTION_ROOM}</span>
                            <a href="{activerow.U_AUCTION_OFFER_ROOM_TITLE}" class="genmed">{activerow.AUCTION_OFFER_ROOM_TITLE}</a>
                       </td>
                       <td class="row1"><span class="genmed"><font color="{AUCTION_COLOR_3}"><b>{activerow.AUCTION_OFFER_TIME_START}</b></font></span></td>
                       <td class="row1"><span class="genmed"><font color="{AUCTION_COLOR_3}"><b>{activerow.AUCTION_OFFER_TIME_STOP}</b></font></span></td>
                       <td class="row1" align="right"><span class="genmed"><font color="{AUCTION_COLOR_3}"><b>{activerow.AUCTION_OFFER_PRICE_LAST}&nbsp;&nbsp;&nbsp;</b></font></span></td>
                       <td class="row1" align="center"><span class="genmed"><font color="{AUCTION_COLOR_3}"><b>{activerow.AUCTION_OFFER_STATUS_ACTIVE}</b></font></span></td>
                       <td class="row1" align="center"><span class="genmed"><font color="{AUCTION_COLOR_3}"><b>{activerow.AUCTION_OFFER_PAID}</b></font></span></td>
                     </tr>
                     <!-- END activerow -->
                     <!-- BEGIN alreadyoverrow -->
                     <tr> 
                       <td class="row1"><a href="{alreadyoverrow.U_AUCTION_OFFER_TITLE}" class="genmed">{alreadyoverrow.AUCTION_OFFER_TITLE}</a>
                            <br>
                            <span class="gensmall">{L_AUCTION_ROOM}</span>
                            <a href="{alreadyoverrow.U_AUCTION_OFFER_ROOM_TITLE}" class="genmed">{alreadyoverrow.AUCTION_OFFER_ROOM_TITLE}</a>
                       </td>
                       <td class="row1"><span class="genmed">{alreadyoverrow.AUCTION_OFFER_TIME_START}</span></td>
                       <td class="row1"><span class="genmed">{alreadyoverrow.AUCTION_OFFER_TIME_STOP}</span></td>
                       <td class="row1" align="right"><span class="genmed">{alreadyoverrow.AUCTION_OFFER_PRICE_LAST}&nbsp;&nbsp;&nbsp;</span></td>
                       <td class="row1" align="center"><span class="genmed">{L_AUCTION_OFFER_STATUS_ALREADY_OVER}</span></td>
                       <td class="row1" align="center"><span class="genmed"><font color="{AUCTION_COLOR_3}"><b>{alreadyoverrow.AUCTION_OFFER_PAID}</b></font></span></td>
                     </tr>
                     <!-- END alreadyoverrow -->
                </table>
                <br>

                   <table width="100%" cellspacing="0" border="0" align="center" cellpadding="2">
                     <tr> 
                       <td align="left"><span class="gensmall"><a href="{U_MARK_READ}" class="gensmall">{L_MARK_FORUMS_READ}</a></span></td>
                       <td align="right"><span class="gensmall">{S_TIMEZONE}</span></td>
                     </tr>
                   </table>

                   <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
                     <tr> 
                       <td class="catHead" colspan="2" height="28"><span class="cattitle"><a href="{U_VIEWONLINE}" class="cattitle">{L_WHO_IS_ONLINE}</a></span></td>
                     </tr>
                     <tr> 
                       <td class="row1" align="center" valign="middle" rowspan="2"><img src="templates/subSilver/images/whosonline.gif" alt="{L_WHO_IS_ONLINE}" /></td>
                       <td class="row1" align="left" width="100%"><span class="gensmall">{TOTAL_POSTS}<br />{TOTAL_USERS}<br />{NEWEST_USER}</span>
                       </td>
                     </tr>
                     <tr> 
                       <td class="row1" align="left"><span class="gensmall">{TOTAL_USERS_ONLINE} &nbsp; [ {L_WHOSONLINE_ADMIN} ] &nbsp; [ {L_WHOSONLINE_MOD} ]<br />{RECORD_USERS}<br />{LOGGED_IN_USER_LIST}</span></td>
                     </tr>
                   </table>

                   <table width="100%" cellpadding="1" cellspacing="1" border="0">
                   <tr>
                       <td align="left" valign="top"><span class="gensmall">{L_ONLINE_EXPLAIN}</span></td>
                   </tr>
                   </table>

                   <!-- BEGIN switch_user_logged_out -->
                   <form method="post" action="{S_LOGIN_ACTION}">
                     <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
                       <tr> 
                         <td class="catHead" height="28"><a name="login"></a><span class="cattitle">{L_LOGIN_LOGOUT}</span></td>
                       </tr>
                       <tr> 
                         <td class="row1" align="center" valign="middle" height="28"><span class="gensmall">{L_USERNAME}: 
                           <input class="post" type="text" name="username" size="10" />
                           &nbsp;&nbsp;&nbsp;{L_PASSWORD}: 
                           <input class="post" type="password" name="password" size="10" maxlength="32" />
                           &nbsp;&nbsp; &nbsp;&nbsp;{L_AUTO_LOGIN} 
                           <input class="text" type="checkbox" name="autologin" />
                           &nbsp;&nbsp;&nbsp; 
                           <input type="submit" class="mainoption" name="login" value="{L_LOGIN}" />
                           </span> </td>
                       </tr>
                     </table>
                   </form>
                   <!-- END switch_user_logged_out -->