                <table width="100%" cellspacing="1" border="0" class="forumline">
                  <tr>
                       <tr>
                            <th colspan="4" class="thHead">{AUCTION_SEARCH_MATCHES_TITLE}</th>
                       </tr>
                       <tr> 
                            <td class="catLeft" align="center"><span class="cattitle">{L_AUCTION_SEARCH_MATCHES_OFFER_TITLE}</span></td>
                            <td class="catLeft" align="center"><span class="cattitle">{L_AUCTION_SEARCH_MATCHES_OFFER_OFFERER}</span></td>
                            <td class="catLeft" align="center"><span class="cattitle">{L_AUCTION_SEARCH_MATCHES_OFFER_TIME_START}</span></td>
                            <td class="catLeft" align="center"><span class="cattitle">{L_AUCTION_SEARCH_MATCHES_OFFER_TIME_STOP}</span></td>
                       </tr> 
                       <!-- BEGIN offerrow -->
                       <tr width="100%">
                            <td class="row1"><span class="genmed">{offerrow.AUCTION_SEARCH_MATCHES_OFFER_LINK}</span></td>
                            <td class="row1"><span class="genmed">{offerrow.AUCTION_SEARCH_MATCHES_OFFER_OFFERER}</span></td>
                            <td class="row1"><span class="genmed">{offerrow.AUCTION_SEARCH_MATCHES_OFFER_TIME_START}</span></td>
                            <td class="row1"><span class="genmed">{offerrow.AUCTION_SEARCH_MATCHES_OFFER_TIME_STOP}</span></td>
                    </tr>
                    <!-- END offerrow -->

                                                              </tr>
                  </table>
                  <table>
                  <br>
                  </table>
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
