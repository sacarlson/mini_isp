<table width="100%" cellspacing="0" cellpadding="2" border="0" align="center">
  <tr> 
    <td align="left" valign="bottom">
        <span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span>
    </td>
  </tr>
</table>
<br>
<br>
<table width="30%" cellspacing="0" cellpadding="2" border="0" align="center" class="forumline">
     <tr>
           <th colspan="3" class="thHead">{L_AUCTION_PAYMENT}</th>
     </tr>
     <tr>
           <td colspan="3" class="row2" align="center"><span class="genmed"><br>{L_AUCTION_PAYMENT_EXPLAIN}{L_AUCTION_PAYMENT_EXPLAIN_MONEYBOOKER}{L_AUCTION_PAYMENT_EXPLAIN_PAYPAL}</br></span></td>
     </tr>
     <tr>
          <td class="row1">
               <span class="forumlink">{L_AUCTION_PRICE_BASIC}</span>
          </td>
          <td colspan="2" class="row1">
               <span class="genmed">{AUCTION_PRICE_BASIC}</span>
          </td>
     </tr>
     <tr>
          <td class="row1">
               <span class="forumlink">{L_AUCTION_PRICE_BOLD}</span>
          </td>
          <td colspan="2" class="row1">
               <span class="genmed">{AUCTION_PRICE_BOLD}</span>
          </td>
     </tr>
     <tr>
          <td class="row1">
               <span class="forumlink">{L_AUCTION_PRICE_ON_TOP}</span>
          </td>
          <td colspan="2" class="row1">
               <span class="genmed">{AUCTION_PRICE_ON_TOP}</span>
          </td>
     </tr>
     <tr>
          <td class="row1">
               <span class="forumlink">{L_AUCTION_PRICE_SPECIAL}</span>
          </td>
          <td colspan="2" class="row1">
               <span class="genmed">{AUCTION_PRICE_SPECIAL}</span>
          </td>
     </tr>
     <tr>
          <td class="row1">
               <span class="forumlink">{L_AUCTION_PRICE_DIRECT_SELL}</span>
          </td>
          <td colspan="2" class="row1">
               <span class="genmed">{AUCTION_PRICE_DIRECT_SELL}</span>
          </td>
     </tr>
     <tr>
          <td class="row1">
               <span class="forumlink">{L_AUCTION_PRICE_TOTAL}</span>
          </td>
          <td colspan="2" class="row1">
               <span class="genmed"><b><font color="red">{AUCTION_PRICE_TOTAL}&nbsp;{AUCTION_CURRENY}</font></b></span>
          </td>
     </tr>
     <tr>
          <td class="row1">
               &nbsp;
          </td>
          <td colspan="2" class="row1">
               &nbsp;
          </td>
     </tr>
     <!-- BEGIN user_points -->
     <form method="post" action="{user_points.S_AUCTION_PAY_WITH_USER_POINTS}">
     <tr align="center">
          <td class="row2" colspan="2">
               <input type="hidden" name="total_price" value="{user_points.AUCTION_PAY_WITH_USER_POINTS_TOTAL_COST}">
               <INPUT TYPE="hidden" NAME="offer_id" VALUE="{user_points.AUCTION_PAY_WITH_USER_POINTS_OFFER_ID}">
               <input type="submit" class="mainoption" value="{user_points.L_AUCTION_PAY_WITH_USER_POINTS}" />
          </td>
     </tr>
     </form>
     <!-- END user_points -->
     <!-- BEGIN paypal -->
     <tr>
          <td class="row1">
               <span class="forumlink">{L_AUCTION_PAYMENTSYSTEM_PAYWITH_PAYPAL_NOW}</span>
          </td>
          <td class="row1" valign="middle">
                <FORM ACTION="https://www.paypal.com/cgi-bin/webscr" METHOD="POST" target=_blank>
                <INPUT TYPE="hidden" NAME="cmd" VALUE="_xclick">
                <INPUT TYPE="hidden" NAME="business" VALUE="{AUCTION_PAYPAL_ADRESS}">
                <INPUT TYPE="hidden" NAME="return" VALUE="http://www.paypal.com">
                <INPUT TYPE="hidden" NAME="item_name" VALUE="{AUCTION_OFFER_TITLE} - {AUCTION_OFFER_ID}">
                <INPUT TYPE="hidden" NAME="amount" VALUE="{AUCTION_PRICE_TOTAL}">
                <INPUT TYPE="hidden" NAME="item_number" VALUE="{AUCTION_OFFER_ID}">
                <input type="hidden" name="currency_code" value="{AUCTION_PAYMENT_CURRENCY}">
                <INPUT TYPE="hidden" NAME="return" VALUE="{AUCTION_PAYMENT_RETURN}">
                <input type="hidden" name="notify_url" value="{AUCTION_PAYMENT_NOTIFICATION}">
                <INPUT TYPE="hidden" NAME="cancel_return" VALUE="{AUCTION_PAYMENT_RETURN}">
                <INPUT TYPE="image" SRC="{paypal.PAYPAL_IMAGE}" NAME="submit" ALT="{L_AUCTION_PAYMENTSYSTEM_PAYWITH_PAYPAL}">
                </FORM>
           </td>
           <td class="row1" align="center">
                &nbsp;
          </td>
      </tr>
     <!-- END paypal -->
     <!-- BEGIN moneybooker -->
     <tr>
          <td class="row1">
               <span class="forumlink">{L_AUCTION_PAYMENTSYSTEM_PAYWITH_MONEYBOOKER}</span>
          </td>
          <td class="row1" valign="middle">
                <form action="https://www.moneybookers.com/app/payment.pl" method="post" target="_blank">
                <input type="hidden" name="pay_to_email" value="{AUCTION_MONEYBOOKER_EMAIL}">
                <input type="hidden" name="status_url" value="merchant@moneybookers.com">
                <input type="hidden" name="language" value="EN">
                <input type="hidden" name="amount" value="{AUCTION_PRICE_TOTAL}">
                <input type="hidden" name="currency" value="{AUCTION_PAYMENT_CURRENCY}">
                <input type="hidden" name="detail1_description" value="auction">
                <input type="hidden" name="detail1_text" value="{AUCTION_OFFER_TITLE} - {AUCTION_OFFER_ID}">
                <INPUT TYPE="image" SRC="{moneybooker.MONEYBOOKER_IMAGE}" NAME="submit" ALT="{L_AUCTION_PAYMENTSYSTEM_PAYWITH_MONEYBOOKER}">
                </form>
           </td>
           <td class="row1" align="center">
               &nbsp;
          </td>
      </tr>
     <!-- END moneybooker -->
     <!-- BEGIN debit -->
     <tr>
          <form action="{debit.U_AUCTION_DEBIT_AMOUNT}" method="post">
          <td class="row1">
               <span class="forumlink">{debit.L_AUCTION_DEBIT}</span>
          </td>
          <td class="row1" valign="middle">
               <input type="hidden" name="auction_offer_amount" value="{debit.AUCTION_PRICE_TOTAL}" >
               <input type="submit" class="mainoption" value="{debit.L_AUCTION_DEBIT_AMOUNT}">
          </td>
          </form>
     </tr>
     <!-- END debit -->
</table>
<br>
<br>