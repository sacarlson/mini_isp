<table class="forumline" width="100%">
     <tr>
          <th>
                <!-- BEGIN no_store -->
                {no_store.L_STORE_NOT_OPENED}
                <!-- END no_store -->
                <!-- BEGIN store -->
                {store.STORE_NAME}
                <!-- END store -->
          </th>
     </tr>
     <tr>
          <td class="row2">

<!-- BEGIN no_store -->
<br>
<br>
<table align="center" width="100%">
     <tr align="center">
          <td>
               <span class="gen">{no_store.L_STORE_NOT_OPENED}</span>
               <br>
               <br>
               <a href="{no_store.U_STORE_OPEN}" class="gensmall">{no_store.L_STORE_OPEN}</a>
          </td>
     </tr>
</table>
<!-- END no_store -->
<!-- BEGIN store_opened -->
<br>
<br>
<table align="center" width="100%">
     <tr align="center">
          <td>
               <span class="gen">{store_opened.L_STORE_OPENED}</span>
               <br>
          </td>
     </tr>
</table>
<!-- END store_opened -->
<!-- BEGIN store -->
<table width="100%" align="center">
     <tr>
          <td align="right">
               <a href="{store.U_STORE_VIEW}" class="gen">{store.L_STORE_VIEW}</a>
          </td>
     </tr>
</table>
<form action="{store.U_STORE_UPDATE}" method="post">
<table width="100%">
     <tr>
          <td class="row1">
               <span class="gen">{store.L_STORE_NAME}</span>
          </td>
          <td class="row2">
               <input class="post" type="text" maxlength="25" size="25" name="store_name" / value="{store.STORE_NAME}">
          </td>
     </tr>
     <tr>
          <td class="row1">
               <span class="gen">{store.L_STORE_DESCRIPTION}</span>
          </td>
          <td class="row2">
               <textarea name="store_description" rows="10" cols="50">{store.STORE_DESCRIPTION}</textarea>
          </td>
     </tr>
     <tr>
          <td class="row1">
               <span class="gen">{store.L_STORE_HEADER}</span>
          </td>
          <td class="row2">
               <textarea name="store_header" rows="10" cols="50">{store.STORE_HEADER}</textarea>
          </td>
     </tr>
    <tr>
        <td class="row1"><span class="genmed">{store.L_AUCTION_BLOCK_CLOSE_TO_END}<br /></span></td>
        <td class="row2"><input type="radio" name="show_block_closetoend" value="1" {store.SHOW_BLOCK_CLOSETOEND_YES} /> <span class="gensmall">{store.L_YES}&nbsp;&nbsp;</span><input type="radio" name="show_block_closetoend" value="0" {store.SHOW_BLOCK_CLOSETOEND_NO} /> <span class="gensmall">{store.L_NO}</span></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{store.L_AUCTION_BLOCK_TICKER}<br /></span></td>
        <td class="row2"><input type="radio" name="show_block_ticker" value="1" {store.SHOW_BLOCK_TICKER_YES} /> <span class="gensmall">{store.L_YES}&nbsp;&nbsp;</span><input type="radio" name="show_block_ticker" value="0" {store.SHOW_BLOCK_TICKER_NO} /> <span class="gensmall">{store.L_NO}</span></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{store.L_AUCTION_BLOCK_AUCTION_ROOMS}<br /></span></td>
        <td class="row2"><input type="radio" name="show_block_rooms" value="1" {store.SHOW_BLOCK_ROOMS_YES} /> <span class="gensmall">{store.L_YES}&nbsp;&nbsp;</span><input type="radio" name="show_block_rooms" value="0" {store.SHOW_BLOCK_ROOMS_NO} /> <span class="gensmall">{store.L_NO}</span></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{store.L_AUCTION_BLOCK_DROP_DOWN_AUCTION_ROOMS}<br /></span></td>
        <td class="row2"><input type="radio" name="show_block_drop_down" value="1" {store.SHOW_BLOCK_DROP_DOWN_YES} /> <span class="gensmall">{store.L_YES}&nbsp;&nbsp;</span><input type="radio" name="show_block_drop_down" value="0" {store.SHOW_BLOCK_DROP_DOWN_NO} /> <span class="gensmall">{store.L_NO}</span></td>
    </tr>

    <tr>
        <td class="row1"><span class="genmed">{store.L_AUCTION_BLOCK_STATISTICS}<br /></span></td>
        <td class="row2"><input type="radio" name="show_block_statistics" value="1" {store.SHOW_BLOCK_STATISTICS_YES} /> <span class="gensmall">{store.L_YES}&nbsp;&nbsp;</span><input type="radio" name="show_block_statistics" value="0" {store.SHOW_BLOCK_STATISTICS_NO} /> <span class="gensmall">{store.L_NO}</span></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{store.L_AUCTION_BLOCK_MYAUCTIONS}<br /></span></td>
        <td class="row2"><input type="radio" name="show_block_myauction" value="1" {store.SHOW_BLOCK_MYAUCTION_YES} /> <span class="gensmall">{store.L_YES}&nbsp;&nbsp;</span><input type="radio" name="show_block_myauction" value="0" {store.SHOW_BLOCK_MYAUCTION_NO} /> <span class="gensmall">{store.L_NO}</span></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{store.L_AUCTION_BLOCK_CALENDAR}<br /></span></td>
        <td class="row2"><input type="radio" name="show_block_calendar" value="1" {store.SHOW_BLOCK_CALENDAR_YES} /> <span class="gensmall">{store.L_YES}&nbsp;&nbsp;</span><input type="radio" name="show_block_calendar" value="0" {store.SHOW_BLOCK_CALENDAR_NO} /> <span class="gensmall">{store.L_NO}</span></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{store.L_AUCTION_BLOCK_SEARCH}<br /></span></td>
        <td class="row2"><input type="radio" name="show_block_search" value="1" {store.SHOW_BLOCK_SEARCH_YES} /> <span class="gensmall">{store.L_YES}&nbsp;&nbsp;</span><input type="radio" name="show_block_search" value="0" {store.SHOW_BLOCK_SEARCH_NO} /> <span class="gensmall">{store.L_NO}</span></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{store.L_AUCTION_BLOCK_SPECIAL}<br /></span></td>
        <td class="row2"><input type="radio" name="show_block_special" value="1" {store.SHOW_BLOCK_SPECIAL_YES} /> <span class="gensmall">{store.L_YES}&nbsp;&nbsp;</span><input type="radio" name="show_block_special" value="0" {store.SHOW_BLOCK_SPECIAL_NO} /> <span class="gensmall">{store.L_NO}</span></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{store.L_AUCTION_BLOCK_PRICE_INFO}<br /></span></td>
        <td class="row2"><input type="radio" name="show_block_priceinfo" value="1" {store.SHOW_BLOCK_PRICEINFO_YES} /> <span class="gensmall">{store.L_YES}&nbsp;&nbsp;</span><input type="radio" name="show_block_priceinfo" value="0" {store.SHOW_BLOCK_PRICEINFO_NO} /> <span class="gensmall">{store.L_NO}</span></td>
    </tr>
    <tr>
        <td class="row1"><span class="genmed">{store.L_AUCTION_BLOCK_LAST_BIDS}<br /></span></td>
        <td class="row2"><input type="radio" name="show_block_last_bids" value="1" {store.SHOW_BLOCK_LAST_BIDS_YES} /> <span class="gensmall">{store.L_YES}&nbsp;&nbsp;</span><input type="radio" name="show_block_last_bids" value="0" {store.SHOW_BLOCK_LAST_BIDS_NO} /> <span class="gensmall">{store.L_NO}</span></td>
    </tr>
     <tr>
          <td colspan="2"  align="center">
              <input type="submit" name="submit" value="{store.L_STORE_UPDATE}" class="mainoption" />
          </td>
     </tr>
</table>
</form>
<!-- END store -->
</td>
</tr>
</table>
