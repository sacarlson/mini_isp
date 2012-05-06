
<h1>{L_ADMIN_COUPON}</h1>

<P>{L_ADMIN_COUPON_EXPLAIN}</p>


<br>
<br>
<form action="{S_AUCTION_COUPON_ACTION}" method="post">
<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline" >
    <tr>
         <td class="row1">
              <span class="forumlink">{L_CHOOSE_COUPON_TYPE}</span>
         </td>
         <td colspan="2" class="row1">
              <span class="forumlink" align="center" valign="center">&nbsp;&nbsp;<select name="coupon_id">{COUPON_LIST_DD}</select></span>
         </td>
         <td colspan="2" class="row1">
              <input type="submit" name="submit" value="{L_COUPON_CREATE}" class="mainoption" />
        </td>

    </tr>
</table>
</form>

<br>
<br>

<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline" width="90%">
    <tr>
        <th class="thCornerL">{L_COUPON_ID}</th>
        <th class="thTop">{L_COUPON_NAME}</th>
        <th class="thTop">{L_COUPON_DATE_CREATED}</th>
        <th class="thTop">{L_COUPON_USER_CREATED}</th>
        <th class="thTop">{L_COUPON_DATE_USED}</th>
        <th class="thTop">{L_COUPNG_USER_USED}</th>
        <th class="thTop">{L_COUPON_DELETE}</th>
        <th class="thTop">{L_COUPON_USER_ID}</th>
        <th class="thTop">{L_COUPON_USER_NAME}</th>
        <th class="thCornerR">{L_COUPON_SEND}</th>
    </tr>
    <!-- BEGIN no_coupon -->
    <tr>
        <td class="{styles.ROW_CLASS}" colspan="10">{no_coupon.L_NO_COUPON}</td>
    </tr>
    <!-- END no_coupon -->

    <!-- BEGIN coupon -->
    <tr>
        <td class="{styles.ROW_CLASS}">{coupon.COUPON_ID}</td>
        <td class="{styles.ROW_CLASS}">{coupon.COUPON_NAME}</td>
        <td class="{styles.ROW_CLASS}">{coupon.COUPON_DATE_CREATED}</td>
        <td class="{styles.ROW_CLASS}">{coupon.COUPON_USER_CREATED}</td>
        <td class="{styles.ROW_CLASS}">{coupon.COUPON_DATE_USED}</td>
        <td class="{styles.ROW_CLASS}">{coupon.COUPON_USER_USED}</td>
        <td class="{styles.ROW_CLASS}"><a href="{coupon.U_COUPON_DELETE}">{L_COUPON_DELETE}</a></td>
        <td class="{styles.ROW_CLASS}"><form enctype="multipart/form-data" action="{coupon.U_COUPON_SEND}" method="post">
             <input class="post" type="text" maxlength="5" size="5" name="user_id">
        </td>
        <td class="{styles.ROW_CLASS}"><input class="post" type="text" size="9" name="user_name"></td>
        <td class="{styles.ROW_CLASS}"><input type="submit" name="submit" value="{L_COUPON_SEND}" class="mainoption" /></form></td>
    </tr>
    <!-- END coupon -->
</table>

<br>
<br>