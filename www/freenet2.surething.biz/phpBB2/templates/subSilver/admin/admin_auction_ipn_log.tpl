<h1>{L_ADMIN_AUCTION_IPN_LOG}</h1>
<P>{L_ADMIN_AUCTION_IPN_LOG_EXPLAIN}</p>

<br>
<br>
<table>
     <tr>
          <td>
               <a href="{U_AUCTION_IPN_LOG_DELETE_ALL}" class="forumlink">{L_DELETE_ALL}</a>
          </td>
     </tr>
</table>
<br>

<form enctype="multipart/form-data" method="post">
<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline" width="90%">
    <tr>
        <th class="row1" align="center">{L_AUCTION_IPN_LOG_DATE}</th>
        <th class="row1" align="center">{L_AUCTION_IPN_LOG_STATUS}</th>
        <th class="row1" align="center">{L_AUCTION_IPN_LOG_OFFER_ID}</th>
        <th class="row1" align="center">{L_DELETE}</th>
    </tr>
    <!-- BEGIN ipn_log -->
    <tr>
        <td class="row2">{ipn_log.AUCTION_IPN_LOG_DATE}</td>
        <td class="row2">{ipn_log.AUCTION_IPN_LOG_STATUS}</td>
        <td class="row2">{ipn_log.AUCTION_IPN_LOG_OFFER_ID}</td>
        <td class="row2"><a href="{ipn_log.U_AUCTION_IPN_LOG_DELETE}">{L_DELETE}</a></td>
    </tr>

    <!-- END offer -->
</table>
</form>

<br>
<br>