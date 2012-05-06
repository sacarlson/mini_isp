<style>
.iexbutton {
 padding:1px; background:#cccccc;  border-top:1px solid #ffffff; border-left:1px solid #ffffff; border-right:1px solid #000000; border-bottom:1px solid #000000; filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1); cursor:hand;} 

.iexbuttonover {
padding:1px; background:#cccccc;  border-top:1px solid #ffffff; border-left:1px solid #ffffff; border-right:1px solid #000000; border-bottom:1px solid #000000; filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=0); cursor:hand;} 
    
.iexbuttondown{
 padding:1px; background:#cccccc;  border-top:1px solid #ffffff; border-left:1px solid #ffffff; border-right:1px solid #000000;border-bottom:1px solid #000000; cursor:hand; filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=0); }
</style>

<SCRIPT language=JavaScript><!--
function openpop(pic,picwidth,picheight)
{
     var x = null;
     var winl = (screen.width-picwidth)/2;
     var wint = (screen.height-picheight)/2;
     x=window.open("auction_pop_pic.php?pic_id="+pic+"&real_width="+picwidth+"&real_height="+picheight,"PICTURE","width="+picwidth+",height="+picheight+",top="+wint+",left="+winl+",directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=yes,alwaysraised=yes");
}
//-->
</SCRIPT>

                <table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
                     <tr>
                         <th colspan="2" class="thHead" align="center">{AUCTION_OFFER_TITLE}</th>
                     </tr>
                     <tr>
                         <td valign="middle" align="right" colspan="2" nowrap="nowrap" class="row2">
                             {AUCTION_OFFER_ADD_TO_WATCHLIST_IMAGE}&nbsp;&nbsp;<a href="{U_AUCTION_OFFER_MOVE_LINK}">{AUCTION_OFFER_MOVE_IMAGE}</a>&nbsp;&nbsp;{AUCTION_OFFER_DELETE_IMAGE}&nbsp;&nbsp;<a href="{U_AUCTION_OFFER_FEATURE_LINK}">{AUCTION_OFFER_SPECIAL_IMAGE}</a>
                             <!-- BEGIN auction_offer_options -->
                             <a href="{auction_offer_options.U_AUCTION_OFFER_RELIST}" class="gen">{auction_offer_options.L_AUCTION_OFFER_RELIST}</a>
                             <!-- END auction_offer_options -->
                         </td>
                     </tr>
                     <!-- BEGIN sold -->
                     <tr>
                          <td class="row2" align="center">
                               <br><span class="gen">{sold.L_AUCTION_OFFER_SOLD_DIRECT}</span>
                               <a href="{sold.U_AUCTION_OFFER_BUYER}" class="forumlink">{sold.AUCTION_OFFER_BUYER}</a><br><br>
                          </td>
                     </tr>
                     <!-- END sold -->
                     <!-- BEGIN auction_offer_over -->
                     <tr>
                          <td class="row1" align="center">
                               <br><br><span class="forumlink">{auction_offer_over.L_AUCTION_OFFER_OVER}</span><br><br>
                          </td>
                     </tr>
                     <!-- END auction_offer_over -->
                     <tr>
                          <td>
                              <table cellspacing="0" width="100%">
                                   <tr>
                                      <td colspan="2" class="row1">&nbsp;</td>
                                   </tr>
                                   <tr>
                                        <td align="center" width="50%" class="row1" valign="valign">
                                           {AUCTION_OFFER_PICTURE}<br>
                                           <!-- BEGIN bidnowrow -->
                                           <span class="topictitle">{L_AUCTION_CURRENT_BID}</span>&nbsp;<span class="topictitle">{AUCTION_CURRENT_BID}<span></br>
                                           <!-- END bidnowrow -->
                                        </td>
                                        <td class="row1">
                                            <span class="topictitle">{L_AUCTION_OFFER_QUICK_VIEW_ID}</span>
                                            <span class="name">&nbsp;{AUCTION_OFFER_ID}</span><br><br>
                                            <!-- BEGIN timetoend_chart -->
                                            <table cellspacing="0" cellpadding="0">
                                                 <tr>
                                                      <td>
                                                          <img src="{VOTE_LEFT}" height="12" width="7" alt="" />
                                                      </td>
                                                      <!-- BEGIN timetoend_chart_over -->
                                                      <td>
                                                          <img src="{AUCTION_VOTE_RIGHT}" height="12" width="7" alt="{timetoend_chart.TIME_TO_END}/{timetoend_chart.TIME_TOTAL}" />
                                                      </td>
                                                      <!-- END timetoend_chart_over -->
                                                      <!-- BEGIN timetoend_chart_running -->
                                                      <td>
                                                          <img src="{AUCTION_VOTE}" height="12" width="7" alt="{timetoend_chart.TIME_TO_END}/{timetoend_chart.TIME_TOTAL}" />
                                                      </td>
                                                      <!-- END timetoend_chart_running -->
                                                      <td>
                                                          <img src="{VOTE_RIGHT}" height="12" width="7" />
                                                      </td>
                                                 </tr>
                                            </table>
                                            <!-- END timetoend_chart -->
                                            <br>
                                           <span class="topictitle">{L_AUCTION_OFFER_TEXT}</span><br>
                                           <span class="genmed">{AUCTION_OFFER_TEXT}</span><br><br>
                                           <!-- BEGIN auction_offer_comment -->
                                           <span class="topictitle">{auction_offer_comment.L_AUCTION_OFFER_COMMENT}</span><br>
                                           <span class="genmed">{auction_offer_comment.AUCTION_OFFER_COMMENT}</span><br>
                                           <span class="gensmall"><i>{auction_offer_comment.AUCTION_OFFER_COMMENT_TIME}</i></span><br><br>
                                           <!-- END auction_offer_comment -->
                                           <!-- BEGIN auction_offer_comment_add -->
                                           <span class="gensmall"><b>{auction_offer_comment_add.L_AUCTION_OFFER_COMMENT_ADD_EDIT}</b></span>
                                           <form enctype="multipart/form-data" method="post" action="{auction_offer_comment_add.S_ADD_EDIT_COMMENT}">
                                           <textarea rows="5" cols="55" class="post" type="text" name="auction_offer_comment" maxlength="255" size="255" />{auction_offer_comment_add.AUCTION_OFFER_COMMENT}</textarea>
                                           <input type="submit" class="mainoption" name="login" value="{auction_offer_comment_add.L_AUCTION_OFFER_COMMENT_ADD_EDIT}" />
                                           </form>
                                           <!-- END auction_offer_comment_add -->
                                       </td>
                                   </tr>
                                   <tr>
                                        <td colspan="2" class="row1"><hr width="90%">
                                        </td>
                                   </tr>
                                   <tr>
                                        <td align=center colspan=2 class=row1>
                                             <table cellspacing=0 cellpadding=0 width="90%" border=0>
                                                  <tr>
                                                       <td align=left>
	                                                    <!-- BEGIN hr -->
                                                            <span class=genmed>{hr.L_GALLERY_OR_EDIT_LINK}</span>
                                                            <!-- END hr -->
                                                            <table cellspacing=2  border=0>
                                                                 <tr>
                                                                 <!-- insert gallery START -->
                                                                 <!-- BEGIN no_pics -->
                                                                      <td class="row1" align="center" colspan=2 height="50">
                                                                           <span class="gen">{L_PERSONAL_GALLERY_NOT_CREATED}</span>
                                                                      </td>
                                                                 <!-- END no_pics -->
                                                                 </tr>
                                                                 <tr>
                                                                 <!-- BEGIN piccol -->
                                                                 {piccol.TABLE1}{piccol.TABLE}
	                                                         <td align="center" class="row1">{piccol.THUMBNAIL}</td>
                                                                 <!-- END piccol -->
                                                                 <!-- insert gallery FINISH -->
                                                                 </tr>
                                                          </table>
                                                     </td>
                                                  </tr>
                                             </table>
                                        </td>
                                   </tr>
                                   <!-- BEGIN hr -->
                                   {hr.HR}
                                   <!-- END hr -->
                                   <tr>
                                       <td class="row1" valign="top">
                                           <table border="0" align="left" valign="top" cellspacing="0" width="100%" bgcolor="row1">
                                                       <!-- BEGIN bidnowrow -->
                                                       <form method="post" action="{S_AUCTION_YOUR_BID_ACTION}">
                                                       <tr>
                                                          <td class="row1" align="right">
                                                              <span class="topictitle">{bidnowrow.L_AUCTION_YOUR_AMOUT}</span>
                                                          </td>
                                                          <td class="row1">
                                                             <span class="genmed">&nbsp;&nbsp;&nbsp;</span>
                                                             <input class="post" type="number" name="auction_your_amount" size="10" />
                                                             <input type="submit" class="mainoption" name="login" value="{bidnowrow.L_AUCTION_BID_NOW}" />
                                                          </td>
                                                       </tr>
                                                       </form>
                                                       <tr>
                                                           <td class="row1" align="right">
                                                              <span class="topictitle">{L_AUCTION_MINIMUM_BID}</span>
                                                           </td>
                                                           <td class="row1">
                                                               <span class="genmed">&nbsp;&nbsp;&nbsp;{AUCTION_MINIMUM_BID}</span>
                                                          </td>
                                                      </tr>

                                                       <!-- END bidnowrow -->
                                                       <!-- BEGIN buy_now -->
                                                       <form method="post" action="{buy_now.S_AUCTION_OFFER_BUY_NOW_ACTION}">
                                                       <tr>
                                                          <td class="row1" align="right">
                                                              <span class="topictitle">{buy_now.L_AUCTION_OFFER_DIRECT_SELL}&nbsp;</span>
                                                          </td>
                                                          <td class="row1">
                                                             <span class="genmed">&nbsp;&nbsp;&nbsp;</span><span class="genmed">{buy_now.AUCTION_OFFER_DIRECT_SELL_PRICE}&nbsp;</span>
                                                             <input type="submit" class="mainoption" name="login" value="{buy_now.L_AUCTION_OFFER_BUY_NOW}" />
                                                          </td>
                                                       </tr>
                                                       </form>
                                                       <!-- END buy_now -->


                                                  <tr>
                                                       <td class="row1">&nbsp;
                                                       </td>
                                                       <td class="row1">
                                                      </td>
                                                  </tr>
                                                  <tr>
                                                           <td class="row1" align="right">
                                                               <span class="topictitle">{L_AUCTION_TIME_REMAINING}</span>
                                                           </td>
                                                           <td class="row1" align="left">
                                                               <span class="genmed">&nbsp;&nbsp;&nbsp;<font color="red"><b>{AUCTION_OFFER_TIME_REMAINING}</b></font></span>
                                                           </td>
                                                  </tr>
                                                  <tr>
                                                           <td class="row1" align="right">
                                                               <span class="topictitle">{L_AUCTION_OFFER_TIME_STATUS}</span>
                                                           </td>
                                                           <td class="row1" align="left">
                                                               <span class="genmed">&nbsp;&nbsp;&nbsp;{AUCTION_OFFER_TIME_MESSAGE}</span>
                                                           </td>
                                                  </tr>
                                                  <tr>
                                                      <td class="row1" align="right">
                                                          <span class="topictitle">{L_AUCTION_OFFER_TIME_START}</span>
                                                      </td>
                                                      <td class="row1" align="left">
                                                          <span class="genmed">&nbsp;&nbsp;&nbsp;{AUCTION_OFFER_TIME_START}</span>
                                                      </td>
                                                  </tr>
                                                       <tr>
                                                           <td class="row1" align="right">
                                                               <span class="topictitle">{L_AUCTION_OFFER_TIME_STOP}</span>
                                                           </td>
                                                           <td class="row1" align="left">
                                                               <span class="genmed">&nbsp;&nbsp;&nbsp;{AUCTION_OFFER_TIME_STOP}</span>
                                                           </td>
                                                       </tr>
                                                  <tr>
                                                       <td class="row1">&nbsp;
                                                       </td>
                                                       <td class="row1">
                                                      </td>
                                                  </tr>
                                                       <tr>
                                                           <td class="row1" align="right">
                                                               <span class="topictitle">{L_AUCTION_OFFER_PRICE_START}</span>
                                                           </td>
                                                           <td class="row1" align="left" valign="top">
                                                               <span class="genmed">&nbsp;&nbsp;&nbsp;{AUCTION_OFFER_PRICE_START}</span>
                                                           </td>
                                                       </tr>
                                                       <tr>
                                                           <td class="row1" align="right">
                                                               <span class="topictitle">{L_AUCTION_OFFER_SHIPPING_PRICE}</span>
                                                           </td>
                                                           <td class="row1" align="left" valign="top">
                                                               <span class="genmed">&nbsp;&nbsp;&nbsp;{AUCTION_OFFER_SHIPPING_PRICE}</span>
                                                           </td>
                                                       </tr>
                                                       <tr>
                                                           <td class="row1" align="right">
                                                               <span class="topictitle">{L_AUCTION_OFFER_SELLERS_LOCATION}</span>
                                                           </td>
                                                           <td class="row1" align="left" valign="top">
                                                               <span class="genmed">&nbsp;&nbsp;&nbsp;{AUCTION_OFFER_SELLERS_LOCATION}</span>
                                                           </td>
                                                       </tr>
                                                       <tr>
                                                           <td class="row1" align="right">
                                                               <span class="topictitle">{L_AUCTION_OFFER_ACCEPTED_PAYMENTS}</span>
                                                           </td>
                                                           <td class="row1" align="left" valign="top">
                                                               <span class="genmed">&nbsp;&nbsp;&nbsp;{AUCTION_OFFER_ACCEPTED_PAYMENTS}</span>
                                                           </td>
                                                       </tr>

                                                   <tr>
                                                       <td class="row1">&nbsp;
                                                       </td>
                                                       <td class="row1">
                                                      </td>
                                                  </tr>
                                                       <tr>
                                                           <td class="row1" align="right">
                                                               <span class="topictitle">{L_AUCTION_OFFER_VIEWS}</span>
                                                           </td>
                                                           <td class="row1" align="left" valign="TOP">
                                                               <span class="genmed">&nbsp;&nbsp;&nbsp;{AUCTION_OFFER_VIEWS}</span>
                                                           </td>
                                                       </tr>

                                                       <tr>
                                                           <td class="row1" align="right">
                                                               <span class="topictitle" align="center">{L_AUCTION_OFFER_BIDS_TOTAL}</span>
                                                           </td>
                                                           <td class="row1" align="left" valign="TOP">
                                                               <span class="genmed">&nbsp;&nbsp;&nbsp;{AUCTION_OFFER_BIDS_TOTAL}</span>
                                                           </td>
                                                       </tr>
                                                    <tr>
                                                       <td class="row1">&nbsp;
                                                       </td>
                                                       <td class="row1">
                                                      </td>
                                                  </tr>

                                                    <tr>
                                                          <td class="row1">
                                                              &nbsp;
                                                          </td>
                                                          <td class="row1">
                                                          </td>
                                                      </tr>

                                           </table>
                                       </td>
                                       <td class="row1" valign="top">
                                           <table border="0" align="left" valign="top" cellspacing="0" width="100%" bgcolor="row1">
                                                       <tr>
                                                           <td class="row1">&nbsp;
                                                           </td>
                                                           <td class="row1">
                                                           </td>
                                                      </tr>
                                                       <tr>
                                                           <td class="row1">&nbsp;
                                                           </td>
                                                           <td class="row1">
                                                           </td>
                                                      </tr>
                                                      <tr>
                                                          <td class="row1">&nbsp;
                                                          </td>
                                                          <td class="row1">
                                                          </td>
                                                      </tr>
                                                       <tr>
                                                           <td class="row1" width="200" align="right" valign="middle">
                                                               <span class="topictitle">&nbsp;&nbsp;{L_AUCTION_OFFER_OFFERER}</span>
                                                           </td>
                                                           <td class="row1" align="left" width="200"  valign="top">
                                                               <span class="genmed">&nbsp;&nbsp;&nbsp;</span><a href="{AUCTION_OFFER_OFFERER_URL}" class="genmed">{AUCTION_OFFER_OFFERER}</a> <a href="{AUCTION_OFFER_OFFERER_RATING_URL}" class="genmed"> ( {AUCTION_OFFER_OFFERER_RATINGS} )</a>
                                                           </td>
                                                       </tr>
                                                       <tr>
                                                          <td class="row1">&nbsp;
                                                          </td>
                                                          <td class="row1">
                                                          </td>
                                                      </tr>
                                                       <tr>
                                                           <td class="row1" width="200" align="right">
                                                               &nbsp;
                                                           </td>
                                                           <td class="row1" align="left" valign="middle" width="200">
                                                               <!-- BEGIN store -->
                                                               <span class="name">&nbsp;&nbsp;&nbsp;<a href="{store.U_USER_STORE}" class="genmed">{store.L_USER_STORE}</a></span><br>
                                                               <!-- END store -->
                                                               <span class="name">&nbsp;&nbsp;&nbsp;<a href="{S_AUCTION_VIEW_OTHER_ITEMS}" class="genmed">{L_AUCTION_VIEW_OTHER_ITEMS}</a></span><br>
                                                               <span class="name">&nbsp;&nbsp;&nbsp;<a href="{AUCTION_SEND_EMAIL}" class="genmed">{L_AUCTION_SEND_MAIL}</a></span><br>
                                                               <span class="name">&nbsp;&nbsp;&nbsp;<a href="{AUCTION_SEND_PM}" class="genmed">{L_AUCTION_SEND_PM}</a></span><br>
                                                               <span class="name">&nbsp;&nbsp;&nbsp;<a href="{AUCTION_OFFER_OFFERER_RATING_URL}" class="genmed">{L_AUCTION_OFFER_OFFERER_RATING}</a></span>
                                                           </td>
                                                       </tr>
                                                       <tr>
                                                          <td class="row1">&nbsp;
                                                          </td>
                                                          <td class="row1">
                                                          </td>
                                                      </tr>
                                                      <tr>
                                                           <td colspan="2" class="row1" align="left" valign="middle">
                                                                <span class="topictitle">&nbsp;&nbsp;{L_AUCTION_OFFER_BID_HISTORY}</span>
                                                           </td>
                                                      </tr>
                                                      <tr>
                                                         <td colspan="2" class="row1">
                                                             <table align="left" class="forumline" width="90%">
                                                                    <!-- BEGIN bidrow -->
                                                                    <tr>
                                                                          <td class="{bidrow.AUCTION_OFFER_BID_CLASS}" align="right" valign="middle" width="50%">
                                                                                          <span class="genmed" align="center"><b><font color="red">&nbsp;&nbsp;{bidrow.AUCTION_OFFER_BID_PRICE}</font></b></span>&nbsp; &nbsp;
                                                                          </td>
                                                                          <td class="{bidrow.AUCTION_OFFER_BID_CLASS}" align="left" valign="middle"  width="50%">
                                                                                          <span class="genmed" align="center"><b><font color="red">&nbsp;&nbsp;{bidrow.AUCTION_OFFER_BID_NO}<a class="genmed" href="{bidrow.AUCTION_OFFER_BID_BIDDER_NAME_URL}">{bidrow.AUCTION_OFFER_BID_BIDDER_NAME}</a>&nbsp;&nbsp;</font></b><a class="genmed" href="{bidrow.AUCTION_OFFER_BID_BIDDER_NAME_URL}">{bidrow.AUCTION_OFFER_BID_BIDDER_RATING_COUNT}</a></span>
                                                                          </td>
                                                                          <!-- BEGIN delete_bidrow -->
                                                                          <td align="center">
                                                                               <a href="{bidrow.delete_bidrow.U_AUCTION_OFFER_BID_DELETE}" class="gensmall">{bidrow.delete_bidrow.L_AUCTION_OFFER_BID_DELETE}</a>
                                                                          </td>
                                                                          <!-- END delete_bidrow -->
                                                                    </tr>
                                                                    <!-- END bidrow -->
                                                              </table>
                                                         </td>
                                                    </tr>
                                                    <tr>
                                                          <td class="row1">&nbsp;
                                                          </td>
                                                          <td class="row1">
                                                          </td>
                                                      </tr>

                                           </table>
                                       </td>
                                   </tr>
                             </table>
                            </td>
                         </tr>
                         <tr>
                         <td valign="middle" colspan="2" nowrap="nowrap" class="row2">
                             &nbsp;{PROFILE_IMG} {PM_IMG} {EMAIL_IMG} {WWW_IMG} {AIM_IMG} {YIM_IMG} {MSN_IMG} {ICQ_IMG}
                         </td>
                    </tr>
              </table>
              <br />
              <!-- BEGIN raterow -->
              <table class="forumline" width="100%">
                     <tr>
                         <th class="thHead" align="center">{raterow.L_AUCTION_RATE_SELLER}</th>
                     </tr>
                     <tr>
                         <td>
                             <form method="post" action="{raterow.S_AUCTION_RATE_ACTION}">
                             <table class="row1" cellspacing="0" border="0" width="100%">
                                    <tr>
                                        <td class="row1">
                                            <span class="topictitle">{raterow.L_AUCTION_RATING_PERSON}</span>
                                        </td>
                                        <td class="row1" align="left">
                                            <span class="name">{raterow.AUCTION_OFFER_OFFERER}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="row1">
                                            <span class="topictitle">{raterow.L_AUCTION_RATING_CATEGORY}</span>
                                        </td>
                                        <td class="row1" align="left">
                                            <select name="rating_category">{raterow.AUCTION_OFFER_RATING_CATEGORIES}</select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="row1">
                                            <span class="topictitle">{raterow.L_AUCTION_RATE_SELLER_TEXT}</span>
                                        </td>
                                        <td class="row1" align="left">
                                            <textarea rows="10" cols="35" class="post" type="number" name="auction_rate_text" maxlength="255" size="255" /></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="row1" cellpadding="3" colspan="2" align="center">
                                            <input type="submit" class="mainoption" name="login" value="{raterow.L_AUCTION_RATE_NOW}" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="row1">
                                            &nbsp;
                                        </td>
                                        <td class="row1" align="left">
                                            &nbsp;
                                        </td>
                                    </tr>
                             </table>
                             </form>
                         </td>
                     </tr>
              </table>
              <br />
              <!-- END raterow -->

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