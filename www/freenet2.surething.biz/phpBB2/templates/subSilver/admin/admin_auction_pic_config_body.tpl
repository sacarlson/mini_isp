<SCRIPT language=JavaScript>
<!--
function openpop(picwidth,picheight,type)
     {
           var x = null;
           var winl = (screen.width-picwidth)/2;
           var wint = (screen.height-picheight)/2;
           x=window.open("test_water_pop.php?real_width="+picwidth+"&real_height="+picheight+"&file="+type,"PICTURE","width="+picwidth+",height="+picheight+",top="+wint+",left="+winl+",directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=yes,alwaysraised=yes");
      }
function openpop2(picwidth,picheight)
     {
          var x = null;
          var winl = (screen.width-picwidth)/2;
          var wint = (screen.height-picheight)/2;
          x=window.open("test_water2_pop.php?real_width="+picwidth+"&real_height="+picheight,"PICTURE","width="+picwidth+",height="+picheight+",top="+wint+",left="+winl+",directories=no,location=no,menubar=no,scrollbars=no,status=no,toolbar=no,resizable=yes,alwaysraised=yes");
     }
var jump='#'+'{REDIRECTOR}';
if (jump.length > 3)
location.href = jump;
//-->
</SCRIPT>
<h1>{L_AUCTION_PIC_CONFIG}</h1>

<p>{L_AUCTION_PIC_CONFIG_EXPLAIN} {RESET_TO_DEFAULTS}</p>

<form action="{S_AUCTION_PIC_CONFIG_ACTION}" method="post">
<table width="90%" cellpadding="4" cellspacing="1" border="0" class="forumline" align="center">
	<tr>
	  <th class="thHead" colspan="2"><a name="part1">{L_BASE_PIC_CONFIG}</a></th>
	</tr>
	<tr>
	  <td class="row1" width="100%" colspan="2 ><span class="genmed"><div align=center>{L_GD_NOTICE}</div></span></td>

	</tr>
	<tr>
	  <td class="row1" width="45%"><span class="genmed">{L_GD_VERSION}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {NO_GD} name="gd_version" value="0" />{L_MANUAL_THUMBNAIL}&nbsp;&nbsp;<input type="radio" {GD_V1} name="gd_version" value="1" />GD1&nbsp;&nbsp;<input type="radio" {GD_V2} name="gd_version" value="2" />GD2&nbsp;&nbsp;<input type="radio" {GD_V3} name="gd_version" value="3" /> {L_AUTO_GD}</span></td>
	</tr>

	<tr>
	  <td class="row1" valign=top width="45%"><div valign=top><span class="genmed">{L_DIRECTORY_WRITE}</span></div></td>
	  <td class="row2"><span class="genmed">{DIRECTORY_WRITE_PROCEED}</span></td>
	</tr>

    <tr>
        <td class="row1">{L_AUCTION_PICTURE_ALLOW}{WORKS_WITH_ALL}</td>
        <td class="row2"><span class="genmed"><input type="radio" {AUCTION_OFFER_PICTURE_ALLOW_YES} name="auction_offer_pictures_allow" value="1"  /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_offer_pictures_allow" value="0" {AUCTION_OFFER_PICTURE_ALLOW_NO} /> {L_NO}</span></td>
    </tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_URL_UPLOAD_ALLOW}{WORKS_WITH_ALL}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {URL_UPLOAD_ENABLED} name="allow_url_upload" value="1" /> {L_YES}&nbsp;&nbsp;<input type="radio" {URL_UPLOAD_DISABLED} name="allow_url_upload" value="0" /> {L_NO}</span></td>
	</tr>

    <tr>
        <td class="row1">{L_AUCTION_PICTURE_JPEG_ALLOW}{WORKS_WITH_ALL}</td>
        <td class="row2"><input type="radio" name="auction_offer_picture_jpeg_allow" value="1" {AUCTION_OFFER_PICTURE_JPEG_ALLOW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_offer_picture_jpeg_allow" value="0" {AUCTION_OFFER_PICTURE_JPEG_ALLOW_NO} /> {L_NO}</td>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_PICTURE_PNG_ALLOW}{WORKS_WITH_ALL}</td>
        <td class="row2"><input type="radio" name="auction_offer_picture_png_allow" value="1" {AUCTION_OFFER_PICTURE_PNG_ALLOW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_offer_picture_png_allow" value="0" {AUCTION_OFFER_PICTURE_PNG_ALLOW_NO} /> {L_NO}</td>
    </tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_PNG_CONVERT}{WORKS_WITH_GD1}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {PNG_CONVERT_ENABLED} name="png_convert" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {PNG_CONVERT_DISABLED} name="png_convert" value="0" />{L_NO}</span></td>
	</tr>
    <tr>
        <td class="row1">{L_AUCTION_PICTURE_GIF_ALLOW}{WORKS_WITH_ALL}</td>
        <td class="row2"><input type="radio" name="auction_offer_picture_gif_allow" value="1" {AUCTION_OFFER_PICTURE_GIF_ALLOW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="auction_offer_picture_gif_allow" value="0" {AUCTION_OFFER_PICTURE_GIF_ALLOW_NO} /> {L_NO}</td>
    </tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_GIF_CONVERT}{WORKS_WITH_GD1}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {GIF_CONVERT_ENABLED} name="gif_convert" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {GIF_CONVERT_DISABLED} name="gif_convert" value="0" />{L_NO}</span></td>
	</tr>
    <tr>
        <td class="row1">{L_AUCTION_GIF_SIZE_ALLOW}{WORKS_WITH_GD1}<br /></span></td>
        <td class="row2"><input class="post" type="text" maxlength="6" size="7" name="auction_gif_max_size" / value="{AUCTION_GIF_SIZE_ALLOW}"></td>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_PICTURE_SIZE_ALLOW}<br /></span></td>
        <td class="row2"><input class="post" type="text" maxlength="6" size="7" name="auction_offer_picture_size_allow" / value="{AUCTION_OFFER_PICTURE_SIZE_ALLOW}"></td>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_SERVER_PICTURE_SIZE_ALLOW}{WORKS_WITH_GD1}<br /></span></td>
        <td class="row2"><input class="post" type="text" maxlength="7" size="8" name="auction_offer_server_picture_size" / value="{AUCTION_OFFER_SERVER_PICTURE_SIZE_ALLOW}"></td>
    </tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_THUMBNAIL_CACHE}{WORKS_WITH_GD1}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {THUMBNAIL_CACHE_ENABLED} name="auction_offer_thumbnail_cache" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {THUMBNAIL_CACHE_DISABLED} name="auction_offer_thumbnail_cache" value="0" />{L_NO}</span></td>
	</tr>
	<tr>
	  <td class="catBottom" colspan="2" align="center"><input type="hidden" name="redirector" value="part1"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr></form>
	
	<tr><form action="{S_AUCTION_PIC_CONFIG_ACTION}" method="post">
	  <th class="thHead" colspan="2"><a name="part2">{L_BASE_PICTURE_SETTINGS}</a></th>
	</tr>
	<tr>
	  <td class="row1" width="45%"><span class="genmed">{L_MAX_WIDTH_BIG_PIC}</span></td>
	  <td class="row2"><input class="post" type="text" maxlength="4" size="5" name="auction_offer_pic_max_width" value="{MAX_WIDTH_BIG_PIC}" /></td>
	</tr>
	<tr>
	  <td class="row1" width="45%"><span class="genmed">{L_MAX_HEIGHT_BIG_PIC}</span></td>
	  <td class="row2"><input class="post" type="text" maxlength="4" size="5" name="auction_offer_pic_max_height" value="{MAX_HEIGHT_BIG_PIC}" /></td>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_QUALITY_BIG_PIC}{WORKS_WITH_GD1}</span></td>
	  <td class="row2"><input class="post" type="text" maxlength="2" size="5" name="offer_auction_pic_quality" value="{QUALITY_BIG_PIC}" /></td>
	</tr>
	<tr>
	  <td class="catBottom" colspan="2" align="center"><input type="hidden" name="redirector" value="part2"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr></form>
    <tr><form action="{S_AUCTION_PIC_CONFIG_ACTION}" method="post">
      <th class="thHead" colspan="2"><a name="part3">{L_MAIN_OFFER_PICTURE}</a></th>
    </tr>
	<tr>
	  <td class="row1" colspan=2><span class="genmed">{L_MAIN_SETTINGS_EXP} {CLEAR_MAIN_CACHE}. {L_CLEAR_ALL_CACHE} {CLEAR_ALL_CACHE}</span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_MAX_SIZE_MAIN_PIC}</span></td>
	  <td class="row2"><input class="post" type="text" maxlength="4" size="5" name="auction_offer_main_size" value="{MAX_SIZE_MAIN_PIC}" /></td>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_QUALITY_MAIN_PIC}{WORKS_WITH_GD1}</span></td>
	  <td class="row2"><input class="post" type="text" maxlength="2" size="5" name="auction_offer_main_quality" value="{QUALITY_MAIN_PIC}" /></td>
	</tr>

    <tr>
        <td class="row1">{L_MAIN_PIC_BORDER}{WORKS_WITH_GD1}</td>
        <td class="row2"><span class="genmed"><input type="radio" name="main_pic_border" value="1" {MAIN_PIC_BORDER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="main_pic_border" value="0" {MAIN_PIC_BORDER_NO} /> {L_NO}</span></td>
    </tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_MAIN_PIC_BORDER_COL}{WORKS_WITH_GD1}</span></td>
	  <td class="row2"><span class="genmed"><b>#&nbsp;</b></span><input class="post" type="text" maxlength="6" size="7" name="main_pic_border_color" value="{MAIN_PIC_BORDER_COL}" /></td>
	</tr>

    <tr>
        <td class="row1">{L_MAIN_PIC_SHARPEN}{WORKS_WITH_GD2}</td>
        <td class="row2">&nbsp;<select class="post" size="1" name="main_pic_sharpen">
	<option {SHSMAIN_SEL_2} value="2">{SH_SEL_2}</option>
	<option {SHSMAIN_SEL_1} value="1">{SH_SEL_1}</option>
	<option {SHSMAIN_SEL_0} value="0">{SH_SEL_0}</option>
	<option {SHSMAIN_SEL_3} value="3">{SH_SEL_3}</option>
	<option {SHSMAIN_SEL_4} value="4">{SH_SEL_4}</option>
	</select></td>
    </tr>
    <tr>
        <td class="row1">{L_MAIN_PIC_BW}{WORKS_WITH_GD1}</td>
        <td class="row2"><span class="genmed"><input type="radio" name="main_pic_bw" value="1" {MAIN_PIC_BW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="main_pic_bw" value="0" {MAIN_PIC_BW_NO} /> {L_NO}</span></td>
    </tr>
    <tr>
        <td class="row1">{L_MAIN_PIC_JS_BW}{WORKS_WITH_ALL}</td>
        <td class="row2"><span class="genmed"><input type="radio" name="main_pic_js_bw" value="1" {MAIN_PIC_JS_BW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="main_pic_js_bw" value="0" {MAIN_PIC_JS_BW_NO} /> {L_NO}</span></td>
    </tr>
	<tr>
	  <td class="catBottom" colspan="2" align="center"><input type="hidden" name="redirector" value="part3"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr></form>
	<tr><form action="{S_AUCTION_PIC_CONFIG_ACTION}" method="post">
	  <th class="thHead" colspan="2"><a name="part4">{L_MINI_ICON_SETTINGS}</a></th>
	</tr>
	<tr>
	  <td class="row1" colspan=2><span class="genmed">{L_MINI_ICON_SETTINGS_EXP} {CLEAR_MINI_ICON_CACHE}. {L_CLEAR_ALL_CACHE} {CLEAR_ALL_CACHE}</span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_MAX_SIZE_MINI_PIC}</span></td>
	  <td class="row2"><input class="post" type="text" maxlength="4" size="5" name="auction_offer_mini_size" value="{MAX_SIZE_MINI_PIC}" /></td>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_QUALITY_MINI_PIC}{WORKS_WITH_GD1}</span></td>
	  <td class="row2"><input class="post" type="text" maxlength="2" size="5" name="auction_offer_mini_quality" value="{QUALITY_MINI_PIC}" /></td>
	</tr>
    <tr>
        <td class="row1">{L_MINI_PIC_BORDER}{WORKS_WITH_GD1}</td>
        <td class="row2"><span class="genmed"><input type="radio" name="mini_pic_border" value="1" {MINI_PIC_BORDER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="mini_pic_border" value="0" {MINI_PIC_BORDER_NO} /> {L_NO}</span></td>
    </tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_MINI_PIC_BORDER_COL}{WORKS_WITH_GD1}</span></td>
	  <td class="row2"><span class="genmed"><b>#&nbsp;</b></span><input class="post" type="text" maxlength="6" size="7" name="mini_pic_border_color" value="{MINI_PIC_BORDER_COL}" /></td>
	</tr>

    <tr>
        <td class="row1">{L_MINI_PIC_SHARPEN}{WORKS_WITH_GD2}</td>
        <td class="row2">&nbsp;<select class="post" size="1" name="mini_pic_sharpen">
	<option {SHSMINI_SEL_2} value="2">{SH_SEL_2}</option>
	<option {SHSMINI_SEL_1} value="1">{SH_SEL_1}</option>
	<option {SHSMINI_SEL_0} value="0">{SH_SEL_0}</option>
	<option {SHSMINI_SEL_3} value="3">{SH_SEL_3}</option>
	<option {SHSMINI_SEL_4} value="4">{SH_SEL_4}</option>
	</select></td>
    </tr>
    <tr>
        <td class="row1">{L_MINI_PIC_BW}{WORKS_WITH_GD1}</td>
        <td class="row2"><span class="genmed"><input type="radio" name="mini_pic_bw" value="1" {MINI_PIC_BW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="mini_pic_bw" value="0" {MINI_PIC_BW_NO} /> {L_NO}</span></td>
    </tr>
	<tr>
	  <td class="catBottom" colspan="2" align="center"><input type="hidden" name="redirector" value="part4"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr></form>


	<tr><form action="{S_AUCTION_PIC_CONFIG_ACTION}" method="post">
	  <th class="thHead" colspan="2"><a name="part5">{L_OFFER_GAL_SETTINGS}</a></th>
	</tr>

	<tr>
	  <td class="row1" colspan=2><span class="genmed">{L_THUMB_SETTINGS_EXP} {CLEAR_THUMB_CACHE}. {L_CLEAR_ALL_CACHE} {CLEAR_ALL_CACHE}</span></td>
	</tr>

    <tr>
        <td class="row1">{L_AUCTION_PICTURE_THUMBS_ALLOW}{WORKS_WITH_ALL}</td>
        <td class="row2"><span class="genmed"><input type="radio" name="allow_thumb_gallery" value="1" {AUCTION_PICTURE_THUMBS_ALLOW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="allow_thumb_gallery" value="0" {AUCTION_PICTURE_THUMBS_ALLOW_NO} /> {L_NO}</span></td>
    </tr>
    <tr>
        <td class="row1">{L_AUCTION_PICTURE_THUMBS_AMOUNT}{WORKS_WITH_ALL}</td>
        <td class="row2">&nbsp;<select class="post" size="1" name="amount_of_thumbs">
	<option {AM_SEL_0} value="0">none</option>
	<option {AM_SEL_1} value="1">1</option>
	<option {AM_SEL_2} value="2">2</option>
	<option {AM_SEL_3} value="3">3</option>
	<option {AM_SEL_4} value="4">4</option>
	<option {AM_SEL_5} value="5">5</option>
	<option {AM_SEL_6} value="6">6</option>
	<option {AM_SEL_7} value="7">7</option>
	<option {AM_SEL_8} value="8">8</option>
	<option {AM_SEL_9} value="9">9</option>
	<option {AM_SEL_10} value="10">10</option>
	</select></td>
    </tr>
    <tr>
        <td class="row1">{L_GALLERY_COLUMS}{WORKS_WITH_ALL}</td>
        <td class="row2">&nbsp;<select class="post" size="1" name="amount_of_thumb_per_line">
	<option {COL_SEL_3} value="3">3</option>
	<option {COL_SEL_4} value="4">4</option>
	<option {COL_SEL_5} value="5">5</option>
	</select></td>

	<tr>
	  <td class="row1"><span class="genmed">{L_THUMBNAIL_TYPE}{WORKS_WITH_GD1}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {THUMB_PIC_NORMAL} name="thumb_pic_type" value="0" />{L_THUMB_PIC_NORMAL}&nbsp;&nbsp;<input type="radio" {THUMB_PIC_SQUARE} name="thumb_pic_type" value="1" />{L_THUMB_PIC_SQUARE}</span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_MAX_SIZE_THUMB_PIC}{WORKS_WITH_ALL}</span></td>
	  <td class="row2"><input class="post" type="text" maxlength="4" size="5" name="auction_offer_thumb_size" value="{MAX_SIZE_THUMB_PIC}" /></td>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_QUALITY_THUMB_PIC}{WORKS_WITH_GD1}</span></td>
	  <td class="row2"><input class="post" type="text" maxlength="2" size="5" name="auction_offer_thumb_quality" value="{QUALITY_THUMB_PIC}" /></td>
	</tr>
    <tr>
        <td class="row1">{L_THUMB_PIC_BORDER}{WORKS_WITH_GD1}</td>
        <td class="row2"><span class="genmed"><input type="radio" name="thumb_pic_border" value="1" {THUMB_PIC_BORDER_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="thumb_pic_border" value="0" {THUMB_PIC_BORDER_NO} /> {L_NO}</span></td>
    </tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_THUMB_PIC_BORDER_COL}{WORKS_WITH_GD1}</span></td>
	  <td class="row2"><span class="genmed"><b>#&nbsp;</b></span><input class="post" type="text" maxlength="6" size="7" name="thumb_pic_border_color" value="{THUMB_PIC_BORDER_COL}" /></td>
	</tr>

    <tr>
        <td class="row1">{L_THUMB_PIC_SHARPEN}{WORKS_WITH_GD2}</td>
        <td class="row2">&nbsp;<select class="post" size="1" name="thumb_pic_sharpen">
	<option {SHS_SEL_2} value="2">{SH_SEL_2}</option>
	<option {SHS_SEL_1} value="1">{SH_SEL_1}</option>
	<option {SHS_SEL_0} value="0">{SH_SEL_0}</option>
	<option {SHS_SEL_3} value="3">{SH_SEL_3}</option>
	<option {SHS_SEL_4} value="4">{SH_SEL_4}</option>
	</select></td>
    </tr>
    <tr>
        <td class="row1">{L_THUMB_PIC_BW}{WORKS_WITH_GD1}</td>
        <td class="row2"><span class="genmed"><input type="radio" name="thumb_pic_bw" value="1" {THUMB_PIC_BW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="thumb_pic_bw" value="0" {THUMB_PIC_BW_NO} /> {L_NO}</span></td>
    </tr>
    <tr>
        <td class="row1">{L_THUMB_PIC_JS_BW}{WORKS_WITH_ALL}</td>
        <td class="row2"><span class="genmed"><input type="radio" name="thumb_pic_js_bw" value="1" {THUMB_PIC_JS_BW_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="thumb_pic_js_bw" value="0" {THUMB_PIC_JS_BW_NO} /> {L_NO}</span></td>
    </tr>
	<tr>
	  <td class="catBottom" colspan="2" align="center"><input type="hidden" name="redirector" value="part5"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr></form>
	<tr><form action="{S_AUCTION_PIC_CONFIG_ACTION}" method="post">
	  <th class="thHead" colspan="2"><a name="part6">{L_SECURITY_SETTINGS}</a></th>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_HOTLINK_PREVENT}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {HOTLINK_PREVENT_ENABLED} name="auction_offer_hotlink_prevent" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {HOTLINK_PREVENT_DISABLED} name="auction_offer_hotlink_prevent" value="0" />{L_NO}</span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_HOTLINK_ALLOWED}</span></td>
	  <td class="row2"><input class="post" type="text" size="40" name="auction_offer_hotlink_allowed" value="{HOTLINK_ALLOWED}" /></td>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_PIC_APPROVAL_ADMIN}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {PIC_APPROVAL_ADMIN_ENABLED} name="auction_offer_pic_approval_admin" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {PIC_APPROVAL_ADMIN_DISABLED} name="auction_offer_pic_approval_admin" value="0" />{L_NO}</span></td>
	</tr>
<!--
	<tr>
	  <td class="row1"><span class="genmed">{L_PIC_APPROVAL_MOD}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {PIC_APPROVAL_MOD_ENABLED} name="auction_offer_pic_approval_mod" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {PIC_APPROVAL_MOD_DISABLED} name="auction_offer_pic_approval_mod" value="0" />{L_NO}</span></td>
	</tr>
-->
    <tr>
        <td class="row1"><span class="genmed">{L_ALLOW_EDITING}</span></td>
        <td class="row2">&nbsp;<select class="post" size="1" name="edit_level">
	<option {EL_SEL_2} value="2">{L_EL_SEL_2}</option>
	<option {EL_SEL_1} value="1">{L_EL_SEL_1}</option>
	<option {EL_SEL_0} value="0">{L_EL_SEL_0}</option>

	</select></td>
    </tr>
	<tr>
	  <td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="hidden" name="redirector" value="part6"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr></form>
	
<form  enctype="multipart/form-data" action="{S_AUCTION_PIC_CONFIG_ACTION}" method="post">
	<tr>
	  <th class="thHead" colspan="2"><a name="part7">{L_WATER_SETTINGS}</a></th>
	</tr>
		<tr>
	  <td colspan=2 class="row1"><span class="genmed">{L_WATER_SETTINGS_EXP} {CLEAR_BIG_WATER_CACHE} {OR_ALSO} {CLEAR_MAIN_WATER_CACHE}</span></td>

	</tr>
		<tr>
	  <td class="row1"><span class="genmed">{L_MAIN_PIC_USE_WATER}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {MAIN_PIC_USE_WATER_ENABLED} name="main_pic_use_water" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {MAIN_PIC_USE_WATER_DISABLED} name="main_pic_use_water" value="0" />{L_NO}</span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_MAIN_PIC_FOR_ALL_WATER}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {MAIN_PIC_FOR_ALL_WATER_ENABLED} name="main_pic_for_all_water" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {MAIN_PIC_FOR_ALL_WATER_DISABLED} name="main_pic_for_all_water" value="0" />{L_NO}</span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_MAIN_PIC_FOR_GUEST_WATER}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {MAIN_PIC_FOR_GUEST_WATER_ENABLED} name="main_pic_for_guest_water" value="1" />{GUEST}&nbsp;&nbsp;<input type="radio" {MAIN_PIC_FOR_GUEST_WATER_DISABLED} name="main_pic_for_guest_water" value="0" />{ALL_USERS}</span></td>
	</tr>
		<tr>
	  <td class="row1"><span class="genmed">{L_MAIN_PIC_CURRENT_WATER}</span></td>
	  <td class="row2"><span class="genmed">{MAIN_PIC_CURRENT_WATER}</span></td>
	</tr>
		<tr>
	  <td class="row1"><span class="genmed">{L_MAIN_WATER_UPLOAD}</span></td>
	  <td class="row2"><span class="genmed"><input class="post" type="file" name="auction_offer_picture_file" /></span></td>
	</tr>

	<tr>
	  <td class="row1"><span class="genmed">{L_MAIN_WATER_IMG_QUAL}</span></td>
	  <td class="row2"><input class="post" type="text" maxlength="2" size="3" name="main_water_img_qual" value="{MAIN_WATER_IMG_QUAL}" /></td>
	</tr>
		<tr>
	  <td class="row1"><span class="genmed">{L_MAIN_WATER_IMG_POS}</span></td>
	  <td  class="row2"><table style="border: 1px solid #000000;"cellpadding="0" cellspacing="0">
<tr><td >
	<table cellpadding="0" cellspacing="0">
			<tr>
			<td><input type="radio" name="main_watermarkpos" value="1" {MWMP_1} ></td>
			<td><input type="radio" name="main_watermarkpos" value="5" {MWMP_5} ></td>
			<td><input type="radio" name="main_watermarkpos" value="2" {MWMP_2} ></td>
			</tr>
			<tr>
			<td><input type="radio" name="main_watermarkpos" value="8" {MWMP_8} ></td>
			<td><input type="radio" name="main_watermarkpos" value="0" {MWMP_0} ></td>
			<td><input type="radio" name="main_watermarkpos" value="6" {MWMP_6} ></td>
			</tr>
			<tr>
			<td><input type="radio" name="main_watermarkpos" value="4" {MWMP_4} ></td>
			<td><input type="radio" name="main_watermarkpos" value="7" {MWMP_7} ></td>
			<td><input type="radio" name="main_watermarkpos" value="3" {MWMP_3} ></td>
			</tr>
	</table>
</td>

</tr>
</table></td>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_MAIN_WATER_IMG_TRANS}</span></td>
	  <td class="row2"><input class="post" type="text" maxlength="3" size="4" name="main_water_img_trans" value="{MAIN_WATER_IMG_TRANS}" /></td>
	</tr>
		<tr>
	  <td class="row1"><span class="genmed">{L_MAIN_PIC_TEST_WATER}</span></td>
	  <td class="row2"><span class="genmed">{MAIN_PIC_TEST_WATER}</span></td>
	</tr>
	
	<tr>
	  <td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDSW1}<input type="hidden" name="redirector" value="part7"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
</form>
<form  enctype="multipart/form-data" action="{S_AUCTION_PIC_CONFIG_ACTION}" method="post">
	<tr>
	  <th class="thHead" colspan="2"><a name="part8">{L_WATER_BIG_SETTINGS}</a></th>
	</tr>
		<tr>
	  <td colspan=2 class="row1"><span class="genmed">{L_WATER_SETTINGS_EXP} {CLEAR_BIG_WATER_CACHE} {OR_ALSO} {CLEAR_MAIN_WATER_CACHE}</span></td>

	</tr>
	</tr>
		<tr>
	  <td class="row1"><span class="genmed">{L_BIG_PIC_USE_WATER}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {BIG_PIC_USE_WATER_ENABLED} name="big_pic_use_water" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {BIG_PIC_USE_WATER_DISABLED} name="big_pic_use_water" value="0" />{L_NO}</span></td>
	</tr>

	<tr>
	  <td class="row1"><span class="genmed">{L_BIG_PIC_FOR_GUEST_WATER}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {BIG_PIC_FOR_GUEST_WATER_ENABLED} name="big_pic_for_guest_water" value="1" />{GUEST}&nbsp;&nbsp;<input type="radio" {BIG_PIC_FOR_GUEST_WATER_DISABLED} name="big_pic_for_guest_water" value="0" />{ALL_USERS}</span></td>
	</tr>
		<tr>
	  <td class="row1"><span class="genmed">{L_BIG_PIC_CURRENT_WATER}</span></td>
	  <td class="row2"><span class="genmed">{BIG_PIC_CURRENT_WATER}</span></td>
	</tr>
		<tr>
	  <td class="row1"><span class="genmed">{L_BIG_WATER_UPLOAD}</span></td>
	  <td class="row2"><span class="genmed"><input class="post" type="file" name="big_wm_picture_file" /></span></td>
	</tr>

	<tr>
	  <td class="row1"><span class="genmed">{L_BIG_WATER_IMG_QUAL}</span></td>
	  <td class="row2"><input class="post" type="text" maxlength="2" size="3" name="big_water_img_qual" value="{BIG_WATER_IMG_QUAL}" /></td>
	</tr>
		<tr>
	  <td class="row1"><span class="genmed">{L_MAIN_WATER_IMG_POS}</span></td>
	  <td  class="row2"><table style="border: 1px solid #000000;"cellpadding="0" cellspacing="0">
<tr><td >
	<table cellpadding="0" cellspacing="0">
			<tr>
			<td><input type="radio" name="big_watermarkpos" value="1" {BWMP_1} ></td>
			<td><input type="radio" name="big_watermarkpos" value="5" {BWMP_5} ></td>
			<td><input type="radio" name="big_watermarkpos" value="2" {BWMP_2} ></td>
			</tr>
			<tr>
			<td><input type="radio" name="big_watermarkpos" value="8" {BWMP_8} ></td>
			<td><input type="radio" name="big_watermarkpos" value="0" {BWMP_0} ></td>
			<td><input type="radio" name="big_watermarkpos" value="6" {BWMP_6} ></td>
			</tr>
			<tr>
			<td><input type="radio" name="big_watermarkpos" value="4" {BWMP_4} ></td>
			<td><input type="radio" name="big_watermarkpos" value="7" {BWMP_7} ></td>
			<td><input type="radio" name="big_watermarkpos" value="3" {BWMP_3} ></td>
			</tr>
	</table>
</td>

</tr>
</table></td>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_MAIN_WATER_IMG_TRANS}</span></td>
	  <td class="row2"><input class="post" type="text" maxlength="3" size="4" name="big_water_img_trans" value="{BIG_WATER_IMG_TRANS}" /></td>
	</tr>
		<tr>
	  <td class="row1"><span class="genmed">{L_BIG_PIC_TEST_WATER}</span></td>
	  <td class="row2"><span class="genmed">{BIG_PIC_TEST_WATER}</span></td>
	</tr>

	
	<tr>
	  <td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDSW2}<input type="hidden" name="redirector" value="part8"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
	</tr>
</form>
</table>
</form>
<br clear="all" />