<form action="{S_POST_ACTION}" method="post">
<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr> 
		<td width="100%"><span class="nav"><a class="nav" href="{U_ADS_INDEX}">{SITE_NAME} {L_ADS_INDEX}</a></span></td>
	</tr>
</table>

<table border="0" cellpadding="3" cellspacing="1" width="100%" class="forumline">
	<tr>
	   <th class="thHead" colspan="2" height="25"><b>{L_CREATE_A_NEW_AD}</b></th>
	</tr>

	<tr>
		<td class="catLeft" height="28"><span class="cattitle">{L_SUMMARY}</span></td>
		<td class="rowpic" align="right">&nbsp;</td>
	</tr>

  <!-- BEGIN not_logged_in -->
	<tr>
		<td class="row1" width="22%"><span class="gen">{L_USERNAME}</span></td>
		<td class="row2" width="78%"><input name="username" type="text" style="border: solid #000000 1px" size="32" maxlength="32" class="post"/></td>
	</tr>
  <!-- END not_logged_in -->

	<tr>
		<td class="row1" width="22%"><span class="gen">{L_TITLE}</span></td>
		<td class="row2" width="78%"><input name="title" type="text" style="border: solid #000000 1px" size="60" maxlength="50" class="post"/></td>
	</tr>

	<tr> 
		<td class="row1"><span class="gen">{L_CATEGORY}</span></td>
		<td class="row2"><span class="gen">{CATEGORY}</span></td>
		<input type="hidden" name="category" value="{CATEGORY}" />
	</tr>

	<tr> 
		<td class="row1"><span class="gen">{L_SUB_CATEGORY}</span></td>
		<td class="row2"><span class="gen">{SUB_CATEGORY}</span></td>
		<input type="hidden" name="sub_category" value="{SUB_CATEGORY}" />
	</tr>

	<tr> 
		<td class="row1"><span class="gen">{L_AD_TYPE}</span></td>
		<td class="row2"><span class="gen">{AD_TYPE}</span></td>
		<input type="hidden" name="ad_type" value="{AD_TYPE}" />
	</tr>

	<tr> 
		<td class="row1"><span class="gen">{L_AD_DURATION}</span></td>
		<td class="row2"><span class="gen">{AD_DURATION} {L_MONTHS}</span></td>
		<input type="hidden" name="ad_duration" value="{AD_DURATION}" />
	</tr>

	<tr> 
		<td class="row1"><span class="gen">{L_SHORT_DESC}</span></td>
		<td class="row2"><textarea name="short_desc" cols="75" rows="2" style="border: solid #000000 1px"></textarea></td>
	</tr>

	<tr> 
		<td class="row1"><span class="gen">{L_PRICE}</span></td>
		<td class="row2"><input name="price" type="text" size="60" maxlength="50" style="border: solid #000000 1px"></td>
	</tr>

	<!-- BEGIN private_trade -->
	<tr> 
		<td class="row1"><span class="gen">{L_PRIVATE_OR_TRADE}</span></td>
		<td class="row2"><span class="gen">{L_PRIVATE}</span><input name="trade_ind" type="radio" value='0'>&nbsp;<span class="gen">{L_TRADE}</span><input name="trade_ind" type="radio" value='1'></td>
	</tr>
	<!-- END private_trade -->

  <!-- BEGIN not_basic_ad -->
	<tr>
		<td class="catLeft" height="28"><span class="cattitle">{L_DETAILS}</span></td>
		<td class="rowpic" align="right">&nbsp;</td>
	</tr>
  <!-- END not_basic_ad -->

	<!-- BEGIN custom_field -->
	<tr> 
		<td class="row1"><span class="gen">{custom_field.FIELD_DESC}</span></td>
		<td class="row2"><input name="{custom_field.FIELD_NUMBER}" type="text" size="85" maxlength="100" style="border: solid #000000 1px"></td>
	</tr>
	<!-- END custom_field -->

  <!-- BEGIN not_basic_ad -->
	<tr> 
		<td class="row1"><span class="gen">{L_ADDITIONAL_INFO}</span></td>
		<td class="row2"><textarea name="additional_info" cols="75" rows="10" style="border: solid #000000 1px"></textarea></td>
	</tr>
  <!-- END not_basic_ad -->

	<tr> 
		<td colspan="2" class="catBottom" align="center"><input name="submit" type="submit" value="{L_CREATE_AD}" class="mainoption" style="border: solid #000000 1px"/></td>
	</tr>

</table>
</form>

<!-- PLEASE DO NOT REMOVE THIS LINK / COPYRIGHT NOTICE -->
<div align="center" class="copyright">Classified Ads powered by <a href="http://www.phpca.net" target="_blank" class="copyright">phpCA</a> &copy; 2005, 2006 phpCA.net</div>