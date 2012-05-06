<h1>{L_AUCTION_TITLE}</h1>
<p>{L_AUCTION_EXPLAIN}</p>

<form method="post" action="{S_AUCTION_ACTION}">
<table width="90%" align="center" cellpadding="2" cellspacing="2" border="0">
     <tr>
        <td valign="bottom" class="nav">
        </td>
        <td valign="bottom" align="right">
           <table width="400" cellpadding="2" cellspacing="1" class="bodyline">
                <form method="post" action="{S_AUCTION_ACTION}">
                 <tr>
                    <td class="row1" align="center"><input class="post" type="text" style="width: 150px" name="auction_category_title" /></td>
                    <td class="row2" align="center"><input type="submit" class="liteoption" style="width: 220px" name="add_auction_category" value="{L_CREATE_AUCTION_CATEGORY}" /></td>
                </tr>
                <!-- BEGIN auction_room_add_row -->
                <tr>
                    <td class="row1" align="center"><input class="post" type="text" style="width: 150px" name="auction_room_title" /></td>
                    <td class="row2" align="center"><input type="submit" class="liteoption" style="width: 220px" name="add_auction_room" value="{L_CREATE_AUCTION_ROOM}" /></td>
                </tr>        
                <!-- END auction_room_add_row -->
                </form>
            </table>
        </td>
    </tr>
</table>
</form>

<table width="90%" cellpadding="3" cellspacing="1" border="0" class="forumline" align="center">
    <tr>
        <th class="thHead" colspan="10">{L_AUCTION_TITLE}{PARENT_AUCTION_TITLE}</th>
    </tr>
    <!-- BEGIN auction_category_row -->
    <tr>
        <td class="cat" colspan="5"><span class="cattitle"><b><a href="{auction_category_row.U_VIEWCAT}" class="forumlink">{auction_category_row.CAT_DESC}</a></b></span></td>
        <td class="cat" align="center" valign="middle"><span class="gen"><a href="{auction_category_row.U_CAT_EDIT}" class="gensmall">{L_EDIT}</a></span></td>
        <td class="cat" align="center" valign="middle"><span class="gen"><a href="{auction_category_row.U_CAT_DELETE}" class="gensmall">{L_DELETE}</a></span></td>
        <td class="cat" align="center" valign="middle" nowrap><span class="gen"><a href="{auction_category_row.U_CAT_MOVE_UP}" class="gensmall">{L_MOVE_UP}</a> <a href="{auction_category_row.U_CAT_MOVE_DOWN}" class="gensmall">{L_MOVE_DOWN}</a></span></td>
    </tr>
        <!-- BEGIN auction_room_row -->
        <tr>
            <td class="row1" colspan="3"><span class="gen"><a href="{auction_category_row.auction_room_row.U_VIEWAUCTION}" target="_new" class="gen">{auction_category_row.auction_room_row.AUCTION_ROOM_NAME}</a> <a href="{auction_category_row.auction_room_row.U_AUCTION_VIEWASROOT}">{auction_category_row.auction_room_row.L_AUCTION_VIEWASROOT}</a></span> &nbsp;<span class="gensmall">({auction_category_row.auction_room_row.AUCTION_ROOM_OFFER_COUNT})<br />{auction_category_row.auction_room_row.AUCTION_ROOM_DESCRIPTION}</span></td>
            <td class="row1" align="center" valign="middle"><span class="gen">{auction_category_row.auction_room_row.AUCTION_ROOM_STATE}</span></td>
            <td class="row2" align="center" valign="middle"><span class="gen">ID:{auction_category_row.auction_room_row.AUCTION_ROOM_ID}</span></td>
            <td class="row1" align="center" valign="middle"><span class="gen"><a href="{auction_category_row.auction_room_row.U_AUCTION_EDIT}" class="gensmall">{L_EDIT}</a></span></td>
            <td class="row2" align="center" valign="middle"><span class="gen"><a href="{auction_category_row.auction_room_row.U_AUCTION_DELETE}" class="gensmall">{L_DELETE}</a></span></td>
            <td class="row1" align="center" valign="middle"><span class="gen"><a href="{auction_category_row.auction_room_row.U_AUCTION_MOVE_UP}" class="gensmall">{L_MOVE_UP}</a><br /><a href="{auction_category_row.auction_room_row.U_AUCTION_MOVE_DOWN}" class="gensmall">{L_MOVE_DOWN}</a></span></td>
        </tr>
        <!-- END auction_room_row -->
    <tr>
        <td colspan="10" height="1" class="spaceRow"><img src="../templates/{T_THEME}/images/spacer.gif" alt="" width="1" height="1" /></td>
    </tr>
    <!-- END auction_category_row -->
</table>
<br />
</table>

<table align="Center">
<tr>
<td>
<span class="gensmall">(C) FR</span>
</td>
</tr>
</table>