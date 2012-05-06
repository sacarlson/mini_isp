<script language="JavaScript">
<!--

function openpop(pic,picwidth,picheight) {
var x = null;
var winl = (screen.width-picwidth)/2;
var wint = (screen.height-picheight)/2;
x=window.open( pic,"UPLOAD","width="+picwidth+",height="+picheight+",top="+wint+",left="+winl+",directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=no,alwaysraised=yes");
x.focus();
}

function openview(pic,picwidth,picheight) {
var x = null;
var winl = (screen.width-picwidth)/2;
var wint = (screen.height-picheight)/2;
x=window.open("auction_pop_pic.php?pic_id="+pic+"&real_width="+picwidth+"&real_height="+picheight,"PICTURE","width="+picwidth+",height="+picheight+",top="+wint+",left="+winl+",directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=yes,alwaysraised=yes");
}

// -->
</script>     

<table width="100%" cellspacing="2" cellpadding="2" border="0">
  <tr>
	<td width="100%"><span class="maintitle">{L_PIC_MANAGER}</span><br /></td>
  </tr>
</table>
<br>
<table width="100%" cellspacing="2" cellpadding="2" border="0">
      <tr>
           <td class="nav"><span class="navw"><a href="{U_YOUR_OFFER}" class="nav">{AUCTION_OFFER_TITLE}</a> &raquo; {L_PIC_MAN}</span></td>
      </tr>
</table>

<table width="100%" cellpadding="2" cellspacing="1" border="0" class="forumline">
     <tr>
          <th class="thTop" height="25" colspan="{S_COLS}">{L_PIC_MAN}</th>
     </tr>
     <tr>
          <td class="row1"  colspan="{S_COLS}" valign="top" align="center" height="28">
               <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>{PICTABLE_1}
	                 <td class="row1"  width=25% valign="top" align="center" height="28"><span class="genmed"><b>{L_QUOTA_EXP}</b><br />{L_UPLOAD_LINK}<br />{L_QUOTA_EXPLAIN2}<br /></span></td>
                         {PICTABLE_2}
                  </tr>
               </table>
          </td>
     </tr>
     <!-- BEGIN no_pics -->
     <tr>
          <td class="row1" align="center" height="50"><span class="gen">{L_PERSONAL_GALLERY_NOT_CREATED}</span></td>
     </tr>
  <!-- END no_pics -->

  <!-- BEGIN picrow -->
  <tr>
	<td align="center"  width={picrow.IMG_PERCENT}%  class="{picrow.ROW_COLOR}"><span class="genmed"><img src="{picrow.THUMBNAIL}" {picrow.MINI_WIDTH_HEIGHT} {picrow.MINI_STYLE} border="0" alt="{picrow.DESC}" title="{picrow.DESC}" /></span></td><td align="center" width={picrow.OTHER_PERCENT}%  class="{picrow.ROW_COLOR}"><span class="genmed">{picrow.STATUS}</span></td><td align="center" width={picrow.OTHER_PERCENT}% class="{picrow.ROW_COLOR}"><span class="genmed">{picrow.MAIN_OR_THUMB}</span></td><td align="center" width={picrow.OTHER_PERCENT}%  class="{picrow.ROW_COLOR}"><span class="genmed">{picrow.SET_AS_MAIN}</span></td><td align="center" width={picrow.OTHER_PERCENT}%  class="{picrow.ROW_COLOR}"><span class="genmed"><a href="javascript:openpop('{picrow.OPEN_POP_URL}',550,{picrow.OPEN_POP_SIZE});" alt="Profil" class="textweiss"><b>{picrow.REPLACE_IMAGE}</b></a></span></td><td align="center" width={picrow.OTHER_PERCENT}% class="{picrow.ROW_COLOR}"><span class="genmed">{picrow.RECROP_IMAGE}</span></td><td align="center" width={picrow.OTHER_PERCENT}% class="{picrow.ROW_COLOR}"><span class="genmed">{picrow.DELETE_IMAGE}</span></td>{picrow.LOCK}{picrow.VALIDATE}
  </tr>
  <!-- END picrow -->
  <tr>
	<td class="catBottom" colspan="{S_COLS}" align="center" height="28">
		<span class="gensmall">&nbsp;</span>
	</td>
  </tr>
</table>

<table width="100%" cellspacing="2" cellpadding="2" border="0">
  <tr>
	<td class="nav"><span class="navw"><a href="{U_YOUR_OFFER}" class="nav">{AUCTION_OFFER_TITLE}</a> &raquo; {L_PIC_MAN}</span></td>
  </tr>
</table>


<br />
