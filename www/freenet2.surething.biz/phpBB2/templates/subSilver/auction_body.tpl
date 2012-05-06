                <!-- BEGIN news_block -->
                <table width="100%">
                     <tr>
                          <td colspan="5" align="center">
                               <table width="100%" cellpadding="3" cellspacing="1">
                                    <tr>
                                         <td align="center" colspan="3">
                                              <span class="gen">{news_block.L_AUCTION_NEWS}<br><br></span>
                                         </td>
                                    </tr>
                                    <tr>
                                    <!-- BEGIN content_block -->
                                         <td valign="top" align="center">
                                              <span class="forumlink">{news_block.content_block.TOPIC_TITLE}</span><br /><span class="genmed">{news_block.content_block.POST_TEXT}</span>
                                              <br>
                                              <br>
                                              <a href="{news_block.content_block.U_VIEW_TOPIC}" class="gensmall">{news_block.content_block.L_VIEW_TOPIC}</a>
                                              <hr width="75%" align="center" />
                                              <a href="{news_block.content_block.U_TOPIC_POSTER}" class="gensmall">{news_block.content_block.TOPIC_POSTER}</a><span class="gensmall"> - {news_block.content_block.TOPIC_TIME}</span>
                                         </td>
                                         <!-- END content_block -->
                                    </tr>
                               </table>
                          </td>
                     </tr>
                </table>
                <!-- END news_block -->
                <br>
                
                <table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
                 <tr>
                  <th colspan="2" class="thCornerL" height="25" nowrap="nowrap">&nbsp;{L_AUCTION_ROOM}&nbsp;</th>
                  <th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_AUCTION_OFFERS}&nbsp;</th>
                  <th width="50" class="thTop" nowrap="nowrap">&nbsp;{L_AUCTION_ROOM_VIEWS}&nbsp;</th>
                  <th class="thCornerR" nowrap="nowrap">&nbsp;{L_AUCTION_LAST_OFFER_TITLE}&nbsp;</th>
                </tr>


                <!-- BEGIN auction_category_row -->
                <tr> 
                  <td class="catLeft" colspan="2" height="28">{auction_category_row.CATEGORY_IMAGE}&nbsp;<span class="cattitle"><a href="{auction_category_row.U_AUCTION_CATEGORY_VIEW}" class="cattitle">{auction_category_row.AUCTION_CATEGORY_DESCRIPTION}</a></span></td>
                  <td class="rowpic" colspan="3" align="right">&nbsp;</td>
                </tr>


                <!-- BEGIN auction_room_row -->
                <tr> 
                  <td class="row1" align="center" valign="middle" height="50"><img src="{auction_category_row.auction_room_row.AUCTION_ROOM_IMG}" alt="{auction_category_row.auction_room_row.L_AUCTION_ROOM_STATE_ALT}" title="{auction_category_row.auction_room_row.L_AUCTION_ROOM_STATE_ALT}" /></td>
                  <td class="row1" width="100%" height="50"><span class="forumlink"> <a href="{auction_category_row.auction_room_row.U_VIEW_AUCTION_ROOM}" class="forumlink">{auction_category_row.auction_room_row.AUCTION_ROOM_TITLE}</a><br />
                    </span> <span class="genmed">{auction_category_row.auction_room_row.AUCTION_ROOM_DESCRIPTION}<br />
                    </span><span class="gensmall">{auction_category_row.auction_room_row.L_MODERATOR} {auction_category_row.auction_room_row.MODERATORS}</span></td>
                  <td class="row2" align="center" valign="middle" height="50"><span class="gensmall">{auction_category_row.auction_room_row.AUCTION_ROOM_OFFER_COUNT}</span></td>
                  <td class="row2" align="center" valign="middle" height="50"><span class="gensmall">{auction_category_row.auction_room_row.AUCTION_ROOM_VIEWS}</span></td>
                  <td class="row2" align="center" valign="middle" height="50" nowrap="nowrap"> <span class="gensmall">{auction_category_row.auction_room_row.AUCTION_LAST_OFFER}</span></td>
                </tr>
                <!-- END auction_room_row -->
                <!-- END auction_category_row -->
                </table>

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