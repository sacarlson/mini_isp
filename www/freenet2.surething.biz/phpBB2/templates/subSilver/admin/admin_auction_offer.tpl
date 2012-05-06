<h1>{L_ADMIN_OFFER}</h1>
<P>{L_ADMIN_OFFER_EXPLAIN}</p>

<br>
<br>

<table>
     <tr>
          <td>
               <a href="{U_AUCTION_OFFER_SORT_JUST_NOT_PAID}" class="gensmall">{L_AUCTION_OFFER_SORT_JUST_NOT_PAID}</a><br>
               <a href="{U_AUCTION_OFFER_SORT_JUST_PAID}" class="gensmall">{L_AUCTION_OFFER_SORT_JUST_PAID}</a><br>
          </td>
     </tr>
</table>

<br>

<form enctype="multipart/form-data" method="post">
<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline" width="90%">
    <tr>
        <td class="row1" align="center">{L_AUCTION_OFFER_ID}</td>
        <td class="row1" align="center"><a href="{U_AUCTION_OFFER_SORT_TITLE}" class="gensmall">{L_AUCTION_OFFER_TITLE}</a></td>
        <td class="row1" align="center"><a href="{U_AUCTION_OFFER_SORT_PAID}" class="gensmall">{L_AUCTION_OFFER_PAID}</a></td>
        <td class="row1" align="center">{L_AUCTION_OFFER_MARK_PAID}</td>
        <td class="row1" align="center"><a href="{U_AUCTION_OFFER_SORT_USERNAME}" class="gensmall">{L_AUCTION_OFFER_OFFERER}</a></td>
        <td class="row1" align="center">{L_AUCTION_OFFER_VIEWS}</td>
        <td class="row1" align="center">{L_AUCTION_OFFER_PICTURE}</td>
        <td class="row1" align="center">{L_AUCTION_OFFER_SPECIAL}</td>
        <td class="row1" align="center">{L_AUCTION_OFFER_BOLD}</td>
        <td class="row1" align="center">{L_AUCTION_OFFER_ON_TOP}</td>
        <td class="row1" align="center">{L_AUCTION_OFFER_SELL_ON_FIRST}</td>
        <td class="row1" align="center">{L_AUCTION_OFFER_COMMENT}</td>
        <td class="row1" align="center"><a href="{U_AUCTION_OFFER_SORT_TIME}" class="gensmall">{L_AUCTION_OFFER_TIME_STOP}</a></td>
        <td class="row1" align="center">{L_AUCTION_OFFER_DELETE}</td>

    </tr>
    <!-- BEGIN no_offer -->
    <tr>
        <td class="row1" align="center" colspan="14">{no_offer.L_NO_OFFER}</td>
    </tr>
    <!-- END no_offer -->

    <!-- BEGIN offer -->
    <tr>
        <td class="row2"><a href="{offer.U_AUCTION_OFFER_VIEW}" target="_blank" class="gensmall">{offer.AUCTION_OFFER_ID}</a></td>
        <td class="row2"><a href="{offer.U_AUCTION_OFFER_VIEW}" target="_blank" class="gensmall">{offer.AUCTION_OFFER_TITLE}</a></td>
        <td class="row2" align="center">{offer.AUCTION_OFFER_PAID}</td>
        <td class="row2"><a href="{offer.U_AUCTION_OFFER_MARK_PAID}" class="gensmall">{offer.L_AUCTION_OFFER_MARK_PAID}</a></td>
        <td class="row2" align="center">{offer.AUCTION_OFFER_OFFERER}</td>
        <td class="row2" align="center">{offer.AUCTION_OFFER_VIEWS}</td>
        <td class="row2" align="center">{offer.AUCTION_OFFER_PICTURE}</td>
        <td class="row2" align="center">{offer.AUCTION_OFFER_SPECIAL}</td>
        <td class="row2" align="center">{offer.AUCTION_OFFER_BOLD}</td>
        <td class="row2" align="center">{offer.AUCTION_OFFER_ON_TOP}</td>
        <td class="row2" align="center">{offer.AUCTION_OFFER_SELL_ON_FIRST}</td>
        <td class="row2" align="center">{offer.AUCTION_OFFER_COMMENT}</td>
        <td class="row2">{offer.AUCTION_OFFER_TIME_END}</td>
        <td class="row2"><a href="{offer.U_AUCTION_OFFER_DELETE}" class="gensmall">{L_AUCTION_OFFER_DELETE}</a></td>
    </tr>

    <!-- END offer -->
</table>
</form>

<br>
<br>