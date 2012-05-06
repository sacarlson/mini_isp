<form action="{S_POST_ACTION}" method="post">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr> 
		<td width="100%"><span class="nav"><a class="nav" href="{U_ADS_INDEX}">{SITE_NAME} {L_ADS_INDEX}</a></span></td>
	</tr>
</table>

<table border="0" cellpadding="3" cellspacing="1" width="100%" class="forumline">
	<tr>
	   <th class="thHead" colspan="2" height="25"><b>{L_RENEW_AD}</b></th>
	</tr>

	<tr>
		<td class="catLeft" height="28"><span class="cattitle">{L_SUMMARY}</span></td>
		<td class="rowpic" align="right">&nbsp;</td>
	</tr>

	<tr> 
		<td class="row1"><span class="gen">{L_AD_DURATION}</span></td>
		<td class="row2"><span class="gen">{AD_DURATION} {L_MONTHS}</span></td>
		<input type="hidden" name="ad_duration" value="{AD_DURATION}" />
	</tr>

	<tr> 
		<td colspan="2" class="catBottom" align="center"><input name="submit" type="submit" value="{L_RENEW_AD}" class="mainoption" style="border: solid #000000 1px"/></td>
	</tr>

</table>
</form>

<!-- PLEASE DO NOT REMOVE THIS LINK / COPYRIGHT NOTICE -->
<div align="center" class="copyright">Classified Ads powered by <a href="http://www.phpca.net" target="_blank" class="copyright">phpCA</a> &copy; 2005, 2006 phpCA.net</div>