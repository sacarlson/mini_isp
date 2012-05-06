
<h1>{L_AUCTION_ROOM_TITLE}</h1>

<p>{L_AUCTION_ROOM_EXPLAIN}</p>

<form action="{S_AUCTION_ACTION}" method="post">
  <table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline" align="center">
    <tr> 
      <th class="thHead" colspan="2">{L_AUCTION_ROOM_SETTINGS}</th>
    </tr>
    <tr> 
      <td class="row1">{L_AUCTION_ROOM_TITLE}</td>
      <td class="row2"><input type="text" size="25" name="auction_room_title" value="{AUCTION_ROOM_TITLE}" class="post" /></td>
    </tr>
    <tr> 
      <td class="row1">{L_AUCTION_ROOM_DESCRIPTION}</td>
      <td class="row2"><textarea rows="5" cols="45" wrap="virtual" name="auction_room_description" class="post">{AUCTION_ROOM_DESCRIPTION}</textarea></td>
    </tr>
    <tr> 
      <td class="row1">{L_AUCTION_ROOM_CATEGORY}</td>
      <td class="row2"><select name="auction_category_title">{S_CAT_LIST}</select></td>
    </tr>
    <tr> 
      <td class="row1">{L_AUCTION_ROOM_STATUS}</td>
      <td class="row2"><select name="auction_room_state">{S_STATUS_LIST}</select></td>
    </tr>
    <tr> 
      <td class="row1">{L_AUCTION_ROOM_ICON}</td>
      <td class="row2"><input type="text" size="25" name="auction_room_icon" value="{AUCTION_ROOM_ICON}" class="post" /></td>
    </tr>
    <tr> 
      <td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{S_SUBMIT_VALUE}" class="mainoption" /></td>
    </tr>
  </table>
</form>
<br clear="all" />
<table>
<tr>
<td>
<span class="gensmall">(C) FR</span>
</td>
</tr>
</table>