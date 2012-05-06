<form action="{S_ADS_ACTION}" method="post">
<table width="100%" cellspacing="2" cellpadding="2" border="0">
  <tr>
	<td class="nav"><span class="nav"><a class="nav" href="{U_ADS_INDEX}">{SITE_NAME} {L_ADS_INDEX}</a></span></td>
  </tr>
</table>

<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
  <tr>
	<th class="thTop" height="25" colspan="2">{L_COMMENTS}</th>
  </tr>
  <tr>
	<td class="row1" align="center" width="35%"><a href="{U_ITEM}"><img src="{U_THUMBNAIL}" border="0" vspace="20" hspace="20"></a></td>
	<td class="row1" valign="top"><table width="100%" align="left" cellspacing="2" cellpadding="2" border="0">
		<tr>
		  <td align="right" valign="top" class="genmed" nowrap="nowrap"><b>{L_TITLE}:</b></td>
		  <td valign="top" class="genmed" width="100%">{TITLE}</td>
		</tr>
		<tr>
		  <td align="right" valign="top" class="genmed" nowrap="nowrap"><b>{L_SHORT_DESC}:</b></td>
		  <td valign="top" class="genmed">{SHORT_DESC}</td>
		</tr>
		<tr>
		  <td align="right" valign="top" class="genmed" nowrap="nowrap"><b>{L_ADVERTISER}:</b></td>
		  <td valign="top" class="genmed">{POSTER}</td>
		</tr>
		<tr>
		  <td align="right" valign="top" class="genmed" nowrap="nowrap"><b>{L_DATE_ADDED}:</b></td>
		  <td valign="top" class="genmed">{DATE_ADDED}</td>
		</tr>
		<tr>
		  <td align="right" valign="top" class="genmed" nowrap="nowrap"><b>{L_VIEWS}:</b></td>
		  <td valign="top" class="genmed">{VIEWS}</td>
		</tr>
		<tr>
		  <td align="right" valign="top" class="genmed" nowrap="nowrap"><b>{L_COMMENTS}:</b></td>
		  <td valign="top" class="genmed">{TOTAL_COMMENTS}</td>
		</tr>
	</table></td>
  </tr>
<!-- BEGIN commentrow -->
  <tr>
	<td class="row3" colspan="2" height="25"><a name="#{commentrow.ID}"></a><span class="genmed"><b>{L_POSTER}: {commentrow.POSTER} @ {commentrow.TIME}</b></span></td>
  </tr>
  <tr>
	<td class="row1" colspan="2"><span class="postbody">{commentrow.TEXT}</span><br />
		<span class="gensmall">{commentrow.EDIT_INFO}</span><br />
		<span class="genmed">{commentrow.IP}<br /><b>{commentrow.EDIT}&nbsp;{commentrow.DELETE}</b></span></td>
  </tr>
<!-- END commentrow -->
<!-- BEGIN switch_comment -->
  <tr>
	<td class="catBottom" align="center" height="28" colspan="2"><span class="gensmall">{L_ORDER}:</span>
	<select name="sort_order"><option {SORT_ASC} value='ASC'>{L_ASC}</option><option {SORT_DESC} value='DESC'>{L_DESC}</option></select>&nbsp;<input type="submit" name="submit" value="{L_SORT}" class="liteoption" /></td>
  </tr>
<!-- END switch_comment -->
</table>
<!-- BEGIN switch_comment -->
<table width="100%" cellspacing="2" border="0" cellpadding="2">
  <tr>
	<td width="100%"><span class="nav">{PAGE_NUMBER}</span></td>
	<td align="right" nowrap="nowrap"><span class="gensmall">{S_TIMEZONE}</span><br /><span class="nav">{PAGINATION}</span></td>
  </tr>
</table>
<!-- END switch_comment -->
</form>

<script language="JavaScript" type="text/javascript">
<!--
function checkForm() {
	formErrors = false;

	if (document.commentform.comment.value.length < 2)
	{
		formErrors = "{L_COMMENT_NO_TEXT}";
	}
	else if (document.commentform.comment.value.length > {S_MAX_LENGTH})
	{
		formErrors = "{L_COMMENT_TOO_LONG}";
	}

	if (formErrors) {
		alert(formErrors);
		return false;
	} else {
		return true;
	}
}
// -->
</script>

<!-- BEGIN switch_comment_post -->
<form name="commentform" action="{S_ADS_ACTION}" method="post" onsubmit="return checkForm();">
<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
  <tr>
	<th class="thTop" height="25" colspan="2">{L_POST_YOUR_COMMENT}</th>
  </tr>
  <!-- BEGIN logout -->
  <tr>
	<td class="row1" width="30%" height="28"><span class="genmed">{L_USERNAME}</span></td>
	<td class="row2"><input class="post" type="text" name="comment_username" size="32" maxlength="32" /></td>
  </tr>
  <!-- END logout -->
  <tr>
	<td class="row1" valign="top" width="30%"><span class="genmed">{L_MESSAGE}<br />
		{L_MAX_LENGTH}: <b>{S_MAX_LENGTH}</b></span></td>
	<td class="row2" valign="top"><textarea name="comment" class="post" cols="60" rows="7">{S_MESSAGE}</textarea></td>
  </tr>
  <tr>
	<td class="catBottom" align="center" colspan="2" height="28"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></td>
  </tr>
</table>
</form>
<!-- END switch_comment_post -->

<!-- PLEASE DO NOT REMOVE THIS LINK / COPYRIGHT NOTICE -->
<div align="center" class="copyright">Classified Ads powered by <a href="http://www.phpca.net" target="_blank" class="copyright">phpCA</a> &copy; 2005, 2006 phpCA.net</div>