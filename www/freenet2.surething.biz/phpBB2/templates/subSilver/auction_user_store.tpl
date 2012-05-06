<!-- BEGIN info_edit -->
<table>
     <tr>
          <td>
               <a href="{info_edit.U_STORE_EDIT}" class="gen">{info_edit.L_STORE_EDIT}</a>
          </td>
     </tr>
</table>
<br>
<!-- END info_edit -->

<!-- BEGIN info -->
<table>
     <tr>
          <td>
               {info.STORE_HEADER}
          </td>
     </tr>
</table>
<table align="center">
     <tr>
          <td>
               <a href="{info.U_STORE_VIEW}" class="gen">{info.L_STORE_VIEW}</a>
          </td>
     </tr>
</table>
<br>
<table width="100%" class="forumline">
     <tr width="100%">
          <th>
               {info.STORE_NAME}
          </th>
     </tr>
     <tr>
          <td>
               {info.STORE_DESCRIPTION}
          </td>
     </tr>
</table>
<!-- END info -->

<!-- BEGIN store -->
<table>
     <tr>
          <td>
               {store.STORE_HEADER}
          </td>
     </tr>
</table>

<table width="100%" cellspacing="0">
     <!-- BEGIN offer -->
     <tr>
          <td colspan="5">
              &nbsp;
          </td>
     </tr>
     <tr>
          <td colspan="5" class="row1">
              &nbsp;&nbsp;<b><a href="{store.offer.U_VIEW_AUCTION_OFFER}" class="gen">{store.offer.AUCTION_OFFER_TITLE}</a></b>
              <hr>
          </td>
     </tr>
     <tr>
          <td class="row1" align="center" width="10%">
               {store.offer.AUCTION_SPECIAL_PICTURE}
          </td>
          <td class="row1" width="40%">
               <span class="gen">{store.offer.AUCTION_OFFER_DESCRIPTION}</span>
          </td>
          <td class="row1" width="20%">
               <span class="gen">{store.offer.AUCTION_OFFER_TIME_STOP}</span>
          </td>
          <td class="row1" width="10%">
               <span class="gen">{store.offer.AUCTION_OFFER_FIRST_PRICE}</span>
          </td>
          <td class="row1" width="10%">
              <a href="{store.offer.U_AUCTION_OFFER_BUY_NOW}"><img src="{store.offer.IMG_DIRECT_SELL}" border="0"/></a>
          </td>
     </tr>
     <tr>
          <td colspan="5" class="row1">
              &nbsp;
              <hr>
          </td>
     </tr>
     <!-- END offer -->
</table>
<!-- END store -->