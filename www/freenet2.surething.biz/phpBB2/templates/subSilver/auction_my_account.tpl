<table width="100%">
     <tr>
          <td  width="50%"  valign="top">
               <table class="forumline" width="100%">
                    <tr>
                         <td class="catleft">
                              <span class="gensmall">&nbsp;</span>
                         </td>
                         <td class="catleft">
                         <span class="gensmall">{L_AUCTION_ACCOUNT_AMOUNT_TOTAL}</span>
                         </td>
                         <td class="catleft">
                         <span class="gensmall">{L_AUCTION_ACCOUNT_AMOUNT_PAID}</span>
                         </td>
                         <td class="catleft">
                         <span class="gensmall">{L_AUCTION_ACCOUNT_AMOUNT_UNPAID}</span>
                         </td>
                    </tr>
                    <tr>
                         <td class="row2" align="right">
                              <span class="gensmall">{L_AUCTION_ACCOUNT_INITIAL_FEE}</span>
                         </td>
                         <td class="row2" align="right">
                              <span class="gensmall"><b>{AUCTION_ACCOUNT_AMOUNT_TOTAL}&nbsp;</b></span>
                         </td>
                         <td class="row2" align="right">
                              <span class="gensmall"><font color="{AUCTION_FONT_COLOR2}"><b>{AUCTION_ACCOUNT_AMOUNT_PAID}&nbsp;</b></font></span>
                         </td>
                         <td class="row2" align="right">
                              <span class="gensmall"><font color="{AUCTION_FONT_COLOR3}"><b>{AUCTION_ACCOUNT_AMOUNT_UNPAID}&nbsp;</b></font></span>
                         </td>
                    </tr>
                    <tr>
                         <td class="row2" align="right">
                              <span class="gensmall">{L_AUCTION_ACCOUNT_FINAL_PERCENT_FEE}</span>
                         </td>
                         <td class="row2" align="right">
                              <span class="gensmall"><b>{AUCTION_ACCOUNT_AMOUNT_PERCENT_TOTAL}&nbsp;</b></span>
                         </td>
                         <td class="row2" align="right">
                              <span class="gensmall"><font color="{AUCTION_FONT_COLOR2}"><b>{AUCTION_ACCOUNT_AMOUNT_PERCENT_PAID}&nbsp;</b></font></span>
                         </td>
                         <td class="row2" align="right">
                              <span class="gensmall"><font color="{AUCTION_FONT_COLOR3}"><b>{AUCTION_ACCOUNT_AMOUNT_PERCENT_UNPAID}&nbsp;</b></font></span>
                         </td>
                    </tr>
                    <tr>
                         <td colspan="4" class="row1">
                         &nbsp;
                         </td>
                    </tr>
                    <tr>
                         <td class="row2" align="right">
                              <span class="gensmall">{L_AUCTION_ACCOUNT_TOTAL_CREDIT}</span>
                         </td>
                         <td class="row2" align="right">
                         </td>
                         <td class="row2" align="right">
                              <span class="gensmall"><font color="{AUCTION_FONT_COLOR2}"><b>{AUCTION_ACCOUNT_AMOUNT_TOTAL_CREDIT}&nbsp;</b></font></span>
                         </td>
                         <td class="row2" align="right">
                         </td>
                    </tr>
                    <tr>
                         <td class="row2" align="right">
                              <span class="gensmall">{L_AUCTION_ACCOUNT_TOTAL_DEBIT}</span>
                         </td>
                         <td class="row2" align="right">
                         </td>
                         <td class="row2" align="right">
                         </td>
                         <td class="row2" align="right">
                              <span class="gensmall"><font color="{AUCTION_FONT_COLOR3}"><b>{AUCTION_ACCOUNT_AMOUNT_TOTAL_DEBIT}&nbsp;</b></font></span>
                         </td>
                    </tr>
               </table>
          </td>
          <td width="50%" valign="top">
               <table class="forumline"  width="100%">
                    <tr>
                         <td class="catleft" align="center">
                              <span class="gensmall">{L_AUCTION_BOARD_CREDIT_TIME}</span>
                         </td>
                         <td class="catleft" align="center">
                              <span class="gensmall">{L_AUCTION_BOARD_CREDIT_AMOUNT}</span>
                         </td>
                         <td class="catleft" align="center">
                              <span class="gensmall">{L_AUCTION_BOARD_CREDIT_AMOUNT_USED}</span>
                         </td>
                         <td class="catleft" align="center">
                              <span class="gensmall">{L_AUCTION_BOARD_CREDIT_AMOUNT_UNUSED}</span>
                         </td>
                    </tr>
                    <!-- BEGIN board_credit -->
                    <tr>
                         <td class="row1" align="right">
                              <span class="gensmall">{board_credit.BOARD_CREDIT_TIME}</span>
                         </td>
                         <td class="row1" align="right">
                              <span class="gensmall">{board_credit.BOARD_CREDIT_AMOUNT}</span>
                         </td>
                         <td class="row1" align="right">
                              <span class="gensmall">{board_credit.BOARD_CREDIT_AMOUNT_USED}</span>
                         </td>
                         <td class="row1" align="right">
                              <span class="gensmall">{board_credit.BOARD_CREDIT_AMOUNT_UNUSED}</span>
                         </td>
                    </tr>
                    <!-- END board_credit -->
                    <tr>
                         <td class="row2" align="right">
                              <span class="gensmall">{L_AUCTION_BOARD_CREDIT_AMOUNT_TOTAL}
                         </td>
                         <td class="row2" align="right">
                              <span class="gensmall">{AUCTION_BOARD_CREDIT}</span>
                         </td>
                         <td class="row2" align="right">
                              <span class="gensmall"></span>
                         </td>
                         <td class="row2" align="right">
                              <span class="gensmall">{AUCTION_BOARD_CREDIT_UNUSED}</span>
                         </td>
                    </tr>
               </table>
               <br>

               <!-- BEGIN settle_fees -->
               <table align="right">
                    <tr>
                         <td>
                              <form method="post" action="{settle_fees.U_AUCTION_SETTLE_FEES}">
                              <input type="submit" class="mainoption" value="{settle_fees.L_AUCTION_SETTLE_FEES}">
                              </form>
                         </td>
                    </tr>
               </table>
               <!-- END settle_fees -->
          </td>
     </tr>
</table>
<br>

<table class="forumline" width="100%">
     <tr>
          <th class="row1" colspan="5">
               {L_AUCTION_ACCOUNT_INITIAL_FEE}
          </th>
     </tr>
     <tr>
          <td class="catleft">
               <span class="gensmall">{L_AUCTION_OFFER_TITLE}</span>
          </td>
          <td class="catleft">
               <span class="gensmall">{L_AUCTION_OFFER_TIME_START}</span>
          </td>
          <td class="catleft">
               <span class="gensmall">{L_AUCTION_ACCOUNT_AMOUNT_TOTAL}</span>
          </td>
          <td class="catleft">
               <span class="gensmall">{L_AUCTION_ACCOUNT_AMOUNT_PAID}</span>
          </td>
          <td class="catleft">
               <span class="gensmall">{L_AUCTION_ACCOUNT_AMOUNT_UNPAID}</span>
          </td>
     </tr>
     <!-- BEGIN action_init -->
     <tr>
          <td class="row1">
               <span class="gensmall">&nbsp;</span><a href="{action_init.U_ACTION_OFFER_TITLE}" class="gensmall">{action_init.ACTION_OFFER_TITLE}</a>
          </td>
          <td class="row1">
               <span class="gensmall">{action_init.ACTION_TIME}</span>
          </td>
          <td class="row1" align="right">
               <span class="gensmall">{action_init.ACTION_AMOUNT}&nbsp;</span>
          </td>
          <td class="row1" align="right">
               <span class="gensmall"><font color="{AUCTION_FONT_COLOR2}">{action_init.ACTION_AMOUNT_PAID}&nbsp;</font></span>
          </td>
          <td class="row1" align="right">
               <span class="gensmall"><font color="{AUCTION_FONT_COLOR3}">{action_init.ACTION_AMOUNT_UNPAID}&nbsp;</font></span>
          </td>

     </tr>
     <!-- END action_init -->
     <tr>
          <td class="row2" colspan="2">
               <span class="gensmall"><b>{L_AUCTION_ACCOUNT_TOTAL}</b></span>
          </td>
          <td class="row2" align="right">
               <span class="gensmall"><b>{AUCTION_ACCOUNT_AMOUNT_TOTAL}&nbsp;</b></span>
          </td>
          <td class="row2" align="right">
               <span class="gensmall"><font color="{AUCTION_FONT_COLOR2}"><b>{AUCTION_ACCOUNT_AMOUNT_PAID}&nbsp;</b></font></span>
          </td>
          <td class="row2" align="right">
               <span class="gensmall"><font color="{AUCTION_FONT_COLOR3}"><b>{AUCTION_ACCOUNT_AMOUNT_UNPAID}&nbsp;</b></font></span>
          </td>
     </tr>
</table>
<br>
<table class="forumline"  width="100%">
     <tr>
          <th class="row1" colspan="5">
               {L_AUCTION_ACCOUNT_FINAL_PERCENT_FEE}
          </th>
     </tr>
     <tr>
          <td class="catleft">
               <span class="gensmall">{L_AUCTION_OFFER_TITLE}</span>
          </td>
          <td class="catleft">
               <span class="gensmall">{L_AUCTION_OFFER_TIME_START}</span>
          </td>
          <td class="catleft">
               <span class="gensmall">{L_AUCTION_ACCOUNT_AMOUNT_TOTAL}</span>
          </td>
          <td class="catleft">
               <span class="gensmall">{L_AUCTION_ACCOUNT_AMOUNT_PAID}</span>
          </td>
          <td class="catleft">
               <span class="gensmall">{L_AUCTION_ACCOUNT_AMOUNT_UNPAID}</span>
          </td>
     </tr>
     <!-- BEGIN action_percent -->
     <tr>
          <td class="row1">
               <span class="gensmall">&nbsp;</span><a href="{action_percent.U_ACTION_OFFER_TITLE}" class="gensmall">{action_percent.ACTION_OFFER_TITLE}</a>
          </td>
          <td class="row1">
               <span class="gensmall">&nbsp;{action_percent.ACTION_TIME}</span>
          </td>
          <td class="row1" align="right">
               <span class="gensmall">{action_percent.ACTION_AMOUNT}&nbsp;</span>
          </td>
          <td class="row1" align="right">
               <span class="gensmall"><font color="{AUCTION_FONT_COLOR2}">{action_percent.ACTION_AMOUNT_PAID}&nbsp;</font></span>
          </td>
          <td class="row1" align="right">
               <span class="gensmall"><font color="{AUCTION_FONT_COLOR3}">{action_percent.ACTION_AMOUNT_UNPAID}&nbsp;</font></span>
          </td>

     </tr>
     <!-- END action_init -->
     <tr>
          <td colspan="5" class="row2">
               &nbsp;
          </td>
     </tr>
     <tr>
          <td class="row2" colspan="2">
               <span class="gensmall"><b>{L_AUCTION_ACCOUNT_TOTAL}</b></span>
          </td>
          <td class="row2" align="right">
               <span class="gensmall"><b>{AUCTION_ACCOUNT_AMOUNT_PERCENT_TOTAL}&nbsp;</b></span>
          </td>
          <td class="row2" align="right">
               <span class="gensmall"><font color="{AUCTION_FONT_COLOR2}"><b>{AUCTION_ACCOUNT_AMOUNT_PERCENT_PAID}&nbsp;</b></font></span>
          </td>
          <td class="row2" align="right">
               <span class="gensmall"><font color="{AUCTION_FONT_COLOR3}"><b>{AUCTION_ACCOUNT_AMOUNT_PERCENT_UNPAID}&nbsp;</b></font></span>
          </td>
     </tr>
</table>
<br>
<!-- BEGIN action_credit_paypal -->
<table>
     <tr>
          <td>
                <FORM ACTION="https://www.paypal.com/cgi-bin/webscr" METHOD="POST" target=_blank>
                <INPUT TYPE="hidden" NAME="cmd" VALUE="_xclick">
                <INPUT TYPE="hidden" NAME="business" VALUE="{action_credit_paypal.AUCTION_PAYPAL_ADRESS}">
                <INPUT TYPE="hidden" NAME="return" VALUE="http://www.paypal.com">
                <INPUT TYPE="hidden" NAME="item_name" VALUE="{action_credit_paypal.L_AUCTION_CREDIT}">
                <INPUT NAME="amount" VALUE="1" align="right">
                <INPUT TYPE="hidden" NAME="item_number" VALUE="{action_credit_paypal.AUCTION_CREDITOR_USER_ID}">
                <input type="hidden" name="currency_code" value="{action_credit_paypal.AUCTION_PAYMENT_CURRENCY}">
                <INPUT TYPE="hidden" NAME="return" VALUE="{action_credit_paypal.AUCTION_PAYMENT_RETURN}">
                <input type="hidden" name="notify_url" value="{action_credit_paypal.AUCTION_PAYMENT_NOTIFICATION}">
                <INPUT TYPE="hidden" NAME="cancel_return" VALUE="{action_credit_paypal.AUCTION_PAYMENT_RETURN}">
                <INPUT TYPE="submit" class="mainoption" SRC="{action_credit_paypal.PAYPAL_IMAGE}" NAME="submit" VALUE="{action_credit_paypal.L_AUCTION_CREDIT}">
                </FORM>
          </td>
       </tr>
       <tr>
          <td>
                <FORM ACTION="https://www.paypal.com/cgi-bin/webscr" METHOD="POST" target=_blank>
                <INPUT TYPE="hidden" NAME="cmd" VALUE="_xclick">
                <INPUT TYPE="hidden" NAME="business" VALUE="{action_credit_paypal.AUCTION_PAYPAL_ADRESS}">
                <INPUT TYPE="hidden" NAME="return" VALUE="http://www.paypal.com">
                <INPUT TYPE="hidden" NAME="item_name" VALUE="{action_credit_paypal.L_AUCTION_CREDIT}">
                <INPUT NAME="amount" VALUE="{action_credit_paypal.AUCTION_ACCOUNT_TOTAL_DEBIT}" readonly  align="right">
                <INPUT TYPE="hidden" NAME="item_number" VALUE="{action_credit_paypal.AUCTION_CREDITOR_USER_ID}">
                <input type="hidden" name="currency_code" value="{action_credit_paypal.AUCTION_PAYMENT_CURRENCY}">
                <INPUT TYPE="hidden" NAME="return" VALUE="{action_credit_paypal.AUCTION_PAYMENT_RETURN}">
                <input type="hidden" name="notify_url" value="{action_credit_paypal.AUCTION_PAYMENT_NOTIFICATION}">
                <INPUT TYPE="hidden" NAME="cancel_return" VALUE="{action_credit_paypal.AUCTION_PAYMENT_RETURN}">
                <INPUT TYPE="submit" class="mainoption" SRC="{action_credit_paypal.PAYPAL_IMAGE}" NAME="submit" VALUE="{action_credit_paypal.L_AUCTION_CREDIT_ALL}">
                </FORM>
          </td>
     </tr>
</table>
<!-- END action_credit_paypal -->


<br>
<table width="100%" class="forumline">
     <tr>
          <th colspan="2">
               {L_AUCTION_ACCOUNT_AUCTION_BALANCE}
          </th>
     </tr>
     <tr>
          <td width="50%" valign="top" class="row1">
               <table width="100%">
                    <tr>
                         <td class="catleft" colspan="2">
                              <span class="gensmall">{L_AUCTION_ACCOUNT_CREDIT}</span>
                         </td>
                    </tr>
                    <!-- BEGIN action_selling_credit -->
                    <tr>
                         <td class="row1" width="70%">
                              <span class="gensmall">{L_AUCTION_OFFER_BUYER}</span><a href="{action_selling_credit.U_ACTION_USER}" class="gensmall">{action_selling_credit.ACTION_USER}</a><br>
                              <span class="gensmall">{L_AUCTION_OFFER}</span><a href="{action_selling_credit.U_ACTION_OFFER_TITLE}" class="gensmall">{action_selling_credit.ACTION_OFFER_TITLE}</a><br>
                              <span class="gensmall">{L_AUCTION_ROOM_SHORT}</span><a href="{action_selling_credit.U_ACTION_ROOM_TITLE}" class="gensmall">{action_selling_credit.ACTION_ROOM_TITLE}</a><br>

                         </td>
                         <td class="row1" align="right" width="30%">
                              <span class="gensmall"><font color="{AUCTION_FONT_COLOR3}">{action_selling_credit.ACTION_AMOUNT_UNPAID}&nbsp;</font></span>
                         </td>
                    </tr>
                    <!-- END action_selling_credit -->
               </table>
          </td>
          <td width="50%" valign="top" class="row1">
               <table width="100%">
                    <tr>
                         <td class="catleft" colspan="2">
                              <span class="gensmall">{L_AUCTION_ACCOUNT_DEBIT}</span>
                         </td>
                    </tr>
                    <!-- BEGIN action_selling_debit -->
                    <tr>
                         <td class="row1" width="70%">
                              <span class="gensmall">{L_AUCTION_OFFER_BUYER}</span><a href="{action_selling_debit.U_ACTION_USER}" class="gensmall">{action_selling_debit.ACTION_USER}</a><br>
                              <span class="gensmall">{L_AUCTION_OFFER}</span><a href="{action_selling_debit.U_ACTION_OFFER_TITLE}" class="gensmall">{action_selling_debit.ACTION_OFFER_TITLE}</a><br>
                              <span class="gensmall">{L_AUCTION_ROOM_SHORT}</span><a href="{action_selling_debit.U_ACTION_ROOM_TITLE}" class="gensmall">{action_selling_debit.ACTION_ROOM_TITLE}</a><br>

                         </td>
                         <td class="row1" align="right" width="30%">
                              <span class="gensmall"><font color="{AUCTION_FONT_COLOR2}">{action_selling_debit.ACTION_AMOUNT_UNPAID}&nbsp;</font></span>
                         </td>
                    </tr>
                    <!-- END action_selling_debit -->
               </table>
          </td>
     </tr>
     <tr>
          <td colspan="2" class="row1">
                <table width="100%">
                    <tr>
                         <td width="50%" class="row1">
                              <table width="100%">
                                   <tr>
                                        <td class="row1" width="70%">
                                             <span class="gensmall"><b>{L_AUCTION_ACCOUNT_TOTAL}</b></span>
                                        </td>
                                        <td class="row1" width="30%" align="right">
                                             <span class="gensmall"><font color="{AUCTION_FONT_COLOR2}"><b>{AUCTION_ACCOUNT_AMOUNT_TOTAL_CREDIT}&nbsp;</b></font></span>
                                        </td>
                                   </tr>
                              </table>
                         </td>
                         <td width="50%" class="row1">
                              <table width="100%">
                                   <tr>
                                        <td class="row1" width="70%">
                                             <span class="gensmall"><b>{L_AUCTION_ACCOUNT_TOTAL}</b></span>
                                        </td>
                                        <td class="row1" width="30%" align="right">
                                             <span class="gensmall"><font color="{AUCTION_FONT_COLOR3}"><b>{AUCTION_ACCOUNT_AMOUNT_TOTAL_DEBIT}&nbsp;</b></font></span>
                                        </td>
                                   </tr>
                              </table>
                         </td>
                    </tr>
                </table>
          </td>

     </tr>
</table>