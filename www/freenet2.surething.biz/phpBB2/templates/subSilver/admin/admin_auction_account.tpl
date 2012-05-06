<h1>{L_ADMIN_ACCOUNT}</h1>
<P>{L_ADMIN_ACCOUNT_EXPLAIN}</p>
<!-- BEGIN message -->
<table class="forumline">
     <tr>
          <td class="row1">
                 <span class="gen">{message.L_AUCTION_SUCCESS}</span>
          </td>
     </tr>
</table>
<!-- END message -->
<br>
<br>
           <table width="400" cellpadding="2" cellspacing="1" class="bodyline">
                <form action="{S_AUCTION_CREDIT_USER}" method="post" >
                 <tr>
                    <td class="row1" align="center">{L_AUCTION_USER_ID}<input class="post" type="text" style="width: 150px" name="auction_credit_id" /></td>
                    <td class="row1" align="center">{L_AUCTION_AMOUNT}<input class="post" type="text" style="width: 150px" name="auction_credit_amount" /></td>
                    <td class="row2" align="center"><input type="submit" class="liteoption" style="width: 220px" value="{L_AUCTION_CREDIT_USER}" /></td>
                </tr>
                </form>
                <form action="{S_AUCTION_DEBIT_USER}" method="post" >
                <tr>
                    <td class="row1" align="center">{L_AUCTION_USER_ID}<input class="post" type="text" style="width: 150px" name="auction_debit_id" /></td>
                    <td class="row1" align="center">{L_AUCTION_AMOUNT}<input class="post" type="text" style="width: 150px" name="auction_debit_amount" /></td>
                    <td class="row2" align="center"><input type="submit" class="liteoption" style="width: 220px" value="{L_AUCTION_DEBIT_USER}" /></td>
                </tr>
                </form>
            </table>
<br>
<table>
      <tr>
            <td valign="top">
<table class="forumline"  width="100%">
     <tr>
          <th class="row1" colspan="5">
               {L_AUCTION_FEES}
          </th>
     </tr>
     <tr>
          <td class="catleft">
               <span class="gensmall">{L_AUCTION_OFFER_TITLE}</span>
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
               <a href="{action_percent.U_ACTION_OFFER_TITLE}" class="gensmall">{action_percent.ACTION_OFFER_TITLE}</a>
               <span class="gensmall"> - </span>
               <a href="{action_percent.U_USER_NAME}" class="gensmall">{action_percent.USER_NAME}</a>
               <span class="gensmall"> {action_percent.ACTION_TIME}</span>
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
          <td class="row2">
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

            </td>
            <td valign="top">
               <table class="forumline">
                    <tr>
                          <th class="row1" colspan="5">
                               {L_AUCTION_CREDITS}
                          </th>
                    </tr>
                    <tr>
                         <td class="catleft" align="center">
                         </td>
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
                              <a href="{board_credit.U_USER_NAME}" class="gensmall">{board_credit.USER_NAME}</a>
                         </td>
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
                         <td class="row2" align="center">
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
            </td>
      </tr>
</table>
<br>
<br>