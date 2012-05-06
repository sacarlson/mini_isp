<h1>{L_ADMIN_PERMISSION}</h1>
<P>{L_ADMIN_PERMISSION_EXPLAIN}</p>

<br>
<br>
<!-- BEGIN error_row -->
<table align="center" class="forumline" width="90%">
     <tr width="90%" align="center">
          <td class="row1">
               <span class="forumlink">{error_row.ERROR_MESSAGE}</span>
          </td>
     </tr>
</table>
<!-- END error_row -->

<br>
<br>

<form enctype="multipart/form-data" method="post" action="{S_UPDATE_AUTH_ACTION}">
<table align="center" class="forumline" width="90%">
    <tr>
         <th class="row1">
              {L_ROLE}
         </th>
         <th class="row1">
              {L_AUTH_VIEW_ALL}
         </th>
         <th class="row1">
             {L_AUTH_VIEW_OFFER}
         </th>
         <th class="row1">
             {L_AUTH_VIEW_BID_HISTORY}
         </th>
         <th class="row1">
             {L_AUTH_NEW}
         </th>
         <th class="row1">
             {L_AUTH_BID}
         </th>
         <th class="row1">
             {L_AUTH_DIRECT_SELL}
         </th>
         <th class="row1">
             {L_AUTH_IMAGE_UPLOAD}
         </th>
         <th class="row1">
             {L_AUTH_COMMENT}
         </th>
         <th class="row1">
             {L_AUTH_MOVE}
         </th>
         <th class="row1">
             {L_AUTH_DELETE_OFFER}
         </th>
         <th class="row1">
             {L_AUTH_DELETE_BID}
         </th>
         <th class="row1">
             {L_AUTH_SPECIAL}
         </th>
    </tr>
    <tr>
         <td class="row2">
              {L_ROLE_GUEST}
         </td>
         <td class="row1" align="center">
              <input type="checkbox" class="post" name="guest_view_all" {AUCTION_AUTH_GUEST_VIEW_ALL} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" class="post" name="guest_view_offer" {AUCTION_AUTH_GUEST_VIEW_OFFER} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" class="post" name="guest_view_bid_history" {AUCTION_AUTH_GUEST_VIEW_BID_HISTORY} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" class="post" name="guest_new" {AUCTION_AUTH_GUEST_NEW} disabled />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" class="post" name="guest_bid" {AUCTION_AUTH_GUEST_BID} disabled />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" class="post" name="guest_direct_sell" {AUCTION_AUTH_GUEST_DIRECT_SELL} disabled />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" class="post" name="guest_image_upload" {AUCTION_AUTH_GUEST_IMAGE_UPLOAD} disabled />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" class="post" name="guest_comment" {AUCTION_AUTH_GUEST_COMMENT} disabled />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" class="post" name="guest_move" {AUCTION_AUTH_GUEST_MOVE} disabled />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" class="post" name="guest_delete_offer" {AUCTION_AUTH_GUEST_DELETE_OFFER} disabled />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" class="post" name="guest_delete_bid" {AUCTION_AUTH_GUEST_DELETE_BID} disabled />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" class="post" name="guest_special" {AUCTION_AUTH_GUEST_SPECIAL} disabled />
         </td>
    </tr>
    <tr>
         <td class="row2">
              {L_ROLE_REGISTERED}
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="reg_view_all" {AUCTION_AUTH_REGISTERED_VIEW_ALL}"/>
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="reg_view_offer" {AUCTION_AUTH_REGISTERED_VIEW_OFFER} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="reg_view_bid_history" {AUCTION_AUTH_REGISTERED_VIEW_BID_HISTORY} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="reg_new" {AUCTION_AUTH_REGISTERED_NEW} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="reg_bid" {AUCTION_AUTH_REGISTERED_BID} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="reg_direct_sell" {AUCTION_AUTH_REGISTERED_DIRECT_SELL} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="reg_image_upload" {AUCTION_AUTH_REGISTERED_IMAGE_UPLOAD} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="reg_comment" {AUCTION_AUTH_REGISTERED_COMMENT} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="reg_move" {AUCTION_AUTH_REGISTERED_MOVE} />
              <br>
              <span class="gensmall">( {L_AUCTION_JUST_ON} )</span>
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="reg_delete_offer" {AUCTION_AUTH_REGISTERED_DELETE_OFFER} />
              <br>
              <span class="gensmall">( {L_AUCTION_JUST_ON} )</span>
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="reg_delete_bid" {AUCTION_AUTH_REGISTERED_DELETE_BID} />
              <br>
              <span class="gensmall">( {L_AUCTION_JUST_ON} )</span>
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="reg_special" {AUCTION_AUTH_REGISTERED_SPECIAL} />
         </td>
    </tr>
    <tr>
         <td class="row2">
              {L_ROLE_AUCTIONEER}
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="auctioneer_view_all" {AUCTION_AUTH_AUCTIONEER_VIEW_ALL}"/>
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="auctioneer_view_offer" {AUCTION_AUTH_AUCTIONEER_VIEW_OFFER} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="auctioneer_view_bid_history" {AUCTION_AUTH_AUCTIONEER_VIEW_BID_HISTORY} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="auctioneer_new" {AUCTION_AUTH_AUCTIONEER_NEW} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="auctioneer_bid" {AUCTION_AUTH_AUCTIONEER_BID} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="auctioneer_direct_sell" {AUCTION_AUTH_AUCTIONEER_DIRECT_SELL} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="auctioneer_image_upload" {AUCTION_AUTH_AUCTIONEER_IMAGE_UPLOAD} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="auctioneer_comment" {AUCTION_AUTH_AUCTIONEER_COMMENT} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="auctioneer_move" {AUCTION_AUTH_AUCTIONEER_MOVE} />
              <br>
              <span class="gensmall">( {L_AUCTION_JUST_ON} )</span>
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="auctioneer_delete_offer" {AUCTION_AUTH_AUCTIONEER_DELETE_OFFER} />
              <br>
              <span class="gensmall">( {L_AUCTION_JUST_ON} )</span>
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="auctioneer_delete_bid" {AUCTION_AUTH_AUCTIONEER_DELETE_BID} />
              <br>
              <span class="gensmall">( {L_AUCTION_JUST_ON} )</span>
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="auctioneer_special" {AUCTION_AUTH_AUCTIONEER_SPECIAL} />
         </td>
    </tr>
    <tr>
         <td class="row2">
              {L_ROLE_MODERATOR}
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="moderator_view_all" {AUCTION_AUTH_MODERATOR_VIEW_ALL}"/>
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="moderator_view_offer" {AUCTION_AUTH_MODERATOR_VIEW_OFFER} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="moderator_view_bid_history" {AUCTION_AUTH_MODERATOR_VIEW_BID_HISTORY} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="moderator_new" {AUCTION_AUTH_MODERATOR_NEW} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="moderator_bid" {AUCTION_AUTH_MODERATOR_BID} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="moderator_direct_sell" {AUCTION_AUTH_MODERATOR_DIRECT_SELL} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="moderator_image_upload" {AUCTION_AUTH_MODERATOR_IMAGE_UPLOAD} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="moderator_comment" {AUCTION_AUTH_MODERATOR_COMMENT} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="moderator_move" {AUCTION_AUTH_MODERATOR_MOVE} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="moderator_delete_offer" {AUCTION_AUTH_MODERATOR_DELETE_OFFER} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="moderator_delete_bid" {AUCTION_AUTH_MODERATOR_DELETE_BID} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="moderator_special" {AUCTION_AUTH_MODERATOR_SPECIAL} />
         </td>
    </tr>
    <tr>
         <td class="row2">
              {L_ROLE_ADMIN}
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="admin_view_all" {AUCTION_AUTH_ADMIN_VIEW_ALL}"/>
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="admin_view_offer" {AUCTION_AUTH_ADMIN_VIEW_OFFER} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="admin_view_bid_history" {AUCTION_AUTH_ADMIN_VIEW_BID_HISTORY} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="admin_new" {AUCTION_AUTH_ADMIN_NEW} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="admin_bid" {AUCTION_AUTH_ADMIN_BID} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="admin_direct_sell" {AUCTION_AUTH_ADMIN_DIRECT_SELL} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="admin_image_upload" {AUCTION_AUTH_ADMIN_IMAGE_UPLOAD} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="admin_comment" {AUCTION_AUTH_ADMIN_COMMENT} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="admin_move" {AUCTION_AUTH_ADMIN_MOVE} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="admin_delete_offer" {AUCTION_AUTH_ADMIN_DELETE_OFFER} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="admin_delete_bid" {AUCTION_AUTH_ADMIN_DELETE_BID} />
         </td>
         <td class="row1" align="center">
              <input type="checkbox" name="admin_special" {AUCTION_AUTH_ADMIN_SPECIAL} />
         </td>
    </tr>
    <tr>
         <td colspan="13" class="row2" align="center">
              <input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
         </td>
    </tr>
</table>
</form>

<br>
<table align="center" class="forumline">
    <tr>
         <th colspan="3" class="row1">
              {L_ADD_USER_TO_ROLE}
         </th>
    </tr>
    <tr>
         <td class="row1">
              <span class="gen">{L_USER_ID}</span>
         </td>
         <td class="row1">
              <span class="gen">{L_USER_NAME}</span>
         </td>
         <td class="row1">
              <span class="gen"></span>
         </td>
    </tr>
    <tr>
         <form enctype="multipart/form-data" method="post" action="{S_ADD_AUCTIONEER_ACTION}">
         <td class="row1">
              <input type="text" name="add_auctioneer_id" value="" />
         </td>
         <td class="row1">
              <input type="text" name="add_auctioneer_name" value="" />
         </td>
         <td class="row1">
              <input type="submit" name="submit" value="{L_ADD_AUCTIONEER}" class="mainoption" />
         </td>
         </form>
    </tr>
    <tr>
         <form enctype="multipart/form-data" method="post" action="{S_ADD_MODERATOR_ACTION}">
         <td class="row1">
              <input type="text" name="add_moderator_id" value="" />
         </td>
         <td class="row1">
              <input type="text" name="add_moderator_name" value="" />
         </td>
         <td class="row1">
               <input type="submit" name="submit" value="{L_ADD_MODERATOR}" class="mainoption" />
         </td>
         </form>
    </tr>
    <tr>
         <form enctype="multipart/form-data" method="post" action="{S_ADD_ADMIN_ACTION}">
         <td class="row1">
              <input type="text" name="add_admin_id" value="" />
         </td>
         <td class="row1">
              <input type="text" name="add_admin_name" value="" />
         </td>
         <td class="row1">
              <input type="submit" name="submit" value="{L_ADD_ADMIN}" class="mainoption" />
         </td>
         </form>
    </tr>
</table>
<br>

<table align="center" class="forumline" width="90%">
     <tr>
          <th class="row1" colspan="2">
              {L_ADMINS}
          </th>
     </tr>
     <!-- BEGIN admin_row -->
     <tr>
          <td class="row1">
               <a href="{admin_row.U_USER_NAME}" class="gensmall">{admin_row.USER_NAME}</a>
          </td>
          <td class="row1" width="20%">
               <a href="{admin_row.U_DELETE_FROM_ROLE}" class="gensmall">{L_AUCTION_DELETE_FROM_ROLE}</a>
          </td>
     </tr>
     <!-- END admin_row -->
</table>
<br>

<table align="center" class="forumline" width="90%">
     <tr>
          <th class="row1" colspan="2">
              {L_MODERATORS}
          </th>
     </tr>
     <!-- BEGIN moderator_row -->
     <tr>
          <td class="row1">
               <a href="{moderator_row.U_USER_NAME}" class="gensmall">{moderator_row.USER_NAME}</a>
          </td>
          <td class="row1" width="20%">
               <a href="{moderator_row.U_DELETE_FROM_ROLE}" class="gensmall">{L_AUCTION_DELETE_FROM_ROLE}</a>
          </td>
     </tr>
     <!-- END moderator_row -->
</table>
<br>

<table align="center" class="forumline" width="90%">
     <tr>
          <th class="row1" colspan="2">
              {L_AUCTIONEERS}
          </th>
     </tr>
     <!-- BEGIN auctioneer_row -->
     <tr>
          <td class="row1">
              <a href="{auctioneer_row.U_USER_NAME}" class="gensmall">{auctioneer_row.USER_NAME}</a>
          </td>
          <td class="row1" width="20%">
               <a href="{auctioneer_row.U_DELETE_FROM_ROLE}" class="gensmall">{L_AUCTION_DELETE_FROM_ROLE}</a>
          </td>
     </tr>
     <!-- END auctioneer_row -->
</table>
<br>
<br>