        <table width="100%" class="forumline">
            <tr>
                 <th colspan="2" align="center" height="25" class="thCornerL" nowrap="nowrap">&nbsp;{L_AUCTION_OFFERS}&nbsp;</th>
                 <th align="center" class="thTop" nowrap="nowrap">&nbsp;{L_AUCTION_OFFER_OFFERER}&nbsp;</th>
                 <th align="center" class="thTop" nowrap="nowrap">&nbsp;{L_AUCTION_OFFER_VIEWS}&nbsp;</th>
                 <th align="center" class="thTop" nowrap="nowrap">&nbsp;{L_AUCTION_FIRST_PRICE}&nbsp;</th>
                 <th align="center" class="thTop" nowrap="nowrap">&nbsp;{L_AUCTION_LAST_PRICE}&nbsp;</br>{L_AUCTION_OFFER_LAST_BID_USER}</th>
                 <th align="center" class="thCornerR" nowrap="nowrap">&nbsp;{L_AUCTION_OFFER_TIME_STOP}&nbsp;</th>
            </tr>
            <!-- BEGIN offer_special -->
            <tr>
                 <td class="row3" align="center" valign="middle">&nbsp;&nbsp<img src="{offer_special.AUCTION_OFFER_PICTURE}" alt="{offer_special.L_AUCTION_OFFER_PICTURE_ALT}" title="{offer_special.L_AUCTION_OFFER_PICTURE_ALT}" />&nbsp;&nbsp</td>
                 <td class="row3" width="400"><span class="genmed">&nbsp;<a href="{offer_special.U_VIEW_AUCTION_OFFER}" class="genmed">{offer_special.AUCTION_OFFER_TITLE}</a></span></td>
                 <td class="row3" align="center" valign="middle"><span class="name">{offer_special.AUCTION_OFFER_OFFERER}</span></td>
                 <td class="row3" align="center" valign="middle"><span class="postdetails">{offer_special.AUCTION_OFFER_VIEWS}</span></td>
                 <td class="row3" align="center" valign="middle"><span class="postdetails"><font color="green"><b>{offer_special.AUCTION_OFFER_FIRST_PRICE}</b></font></span></td>
                 <td class="row3" align="center" valign="middle"><span class="postdetails">{offer_special.AUCTION_OFFER_LAST_BID_PRICE}</br>{offer_special.AUCTION_OFFER_LAST_BID_USER}</span></td>
                 <td class="row3Right" align="center" valign="middle" nowrap="nowrap"><span class="postdetails">{offer_special.AUCTION_OFFER_TIME_STOP}<br />{offer_special.LAST_POST_AUTHOR}</span></td>
                                                </tr>
                                            <!-- END offer_special -->
                                            <!-- BEGIN offer_on_top -->
                                            <tr> 
                                                 <td class="row1" align="center" valign="middle">&nbsp;&nbsp<img src="{offer_on_top.AUCTION_OFFER_PICTURE}" alt="{offer_on_top.L_AUCTION_OFFER_PICTURE_ALT}" title="{offer_on_top.L_AUCTION_OFFER_PICTURE_ALT}" />&nbsp;&nbsp</td>
                                                 <td class="row1" width="400"><span class="genmed"><a href="{offer_on_top.U_VIEW_AUCTION_OFFER}" class="genmed">{offer_on_top.AUCTION_OFFER_TITLE}</a></span></td>
                                                 <td class="row3" align="center" valign="middle"><span class="name">{offer_on_top.AUCTION_OFFER_OFFERER}</span></td>
                                                 <td class="row2" align="center" valign="middle"><span class="postdetails">{offer_on_top.AUCTION_OFFER_VIEWS}</span></td>
                                                 <td class="row2" align="center" valign="middle"><span class="postdetails"><font color="green"><b>{offer_on_top.AUCTION_OFFER_FIRST_PRICE}</b></font></span></td>
                                                 <td class="row2" align="center" valign="middle"><span class="postdetails">{offer_on_top.AUCTION_OFFER_LAST_BID_PRICE} </br>{offer_on_top.AUCTION_OFFER_LAST_BID_USER}</span></td>
                                                 <td class="row3Right" align="center" valign="middle" nowrap="nowrap"><span class="postdetails">{offer_on_top.AUCTION_OFFER_TIME_STOP}<br />{offer_on_top.LAST_POST_AUTHOR}</span></td>
                                            </tr>
                                            <!-- END offer_on_top -->
                                            <!-- BEGIN offer -->
                                            <tr> 
                                                 <td class="row1" align="center" valign="middle">&nbsp;&nbsp<img src="{offer.AUCTION_OFFER_PICTURE}" alt="{offer.L_AUCTION_OFFER_PICTURE_ALT}" title="{offer.L_AUCTION_OFFER_PICTURE_ALT}" />&nbsp;&nbsp</td>
                                                 <td class="row1" width="400"><span class="genmed"><a href="{offer.U_VIEW_AUCTION_OFFER}" class="genmed">{offer.AUCTION_OFFER_TITLE}</a></span></td>
                                                 <td class="row3" align="center" valign="middle"><span class="name">{offer.AUCTION_OFFER_OFFERER}</span></td>
                                                 <td class="row2" align="center" valign="middle"><span class="postdetails">{offer.AUCTION_OFFER_VIEWS}</span></td>
                                                 <td class="row2" align="center" valign="middle"><span class="postdetails"><font color="green"><b>{offer.AUCTION_OFFER_FIRST_PRICE}</b></font></span></td>
                                                 <td class="row2" align="center" valign="middle"><span class="postdetails">{offer.AUCTION_OFFER_LAST_BID_PRICE} </br>{offer.AUCTION_OFFER_LAST_BID_USER}</span></td>
                                                 <td class="row3Right" align="center" valign="middle" nowrap="nowrap"><span class="postdetails">{offer.AUCTION_OFFER_TIME_STOP}<br />{offer.LAST_POST_AUTHOR}</span></td>
                                            </tr>
                                            <!-- END offer -->

                                            <!-- BEGIN no_offer -->
                                            <tr> 
                                                 <td class="row1" colspan="8" height="30" align="center" valign="middle"><span class="gen">{L_NO_OFFER}</span></td>
                                            </tr>
                                            <!-- END no_offer -->
                                            <tr> 
                                                 <td class="catBottom" align="center" valign="middle" colspan="8" height="28">
                                                 </td>
              </tr>
      </table>
      <br>
      <br>
      <table width="100%" cellspacing="0" border="0" align="center" cellpadding="2">
        <tr> 
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