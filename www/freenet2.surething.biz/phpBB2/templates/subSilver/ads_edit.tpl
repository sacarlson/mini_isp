<table width="100%" cellspacing="2" cellpadding="2" border="0">
	<tr>
		<td align="left" valign="bottom"><a class="maintitle" href="{U_ADS_ITEM}">{TITLE}<br /></a></td>
	</tr>
</table>	

<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
		<td width="100%"><span class="nav"><a class="nav" href="{U_ADS_INDEX}">{SITE_NAME} {L_ADS_INDEX}</a></span></td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellpadding="2" cellspacing="1" class="forumline">

				<tr>
					<th class="thHead" colspan="2" height="25"><b>{L_EDIT_AD}</b></th>
				</tr>

				<tr>
					<td class="catLeft" height="28"><span class="cattitle">{L_SUMMARY}</span></td>
					<td class="rowpic" align="right">&nbsp;</td>
				</tr>

				<form action="{S_POST_ACTION}" method="post">

				<tr> 
					<td class="row1" width="22%"><span class="gen">{L_CATEGORY}</span></td>
					<td class="row2" width="78%"><span class="gen">{CATEGORY}</span></td>
				</tr>

				<tr> 
					<td class="row1" width="22%"><span class="gen">{L_SUB_CATEGORY}</span></td>
					<td class="row2" width="78%"><span class="gen">{SUB_CATEGORY}</span></td>
				</tr>

				<!-- BEGIN move_allowed --> 
				<tr> 
					<td class="row1" width="22%"><span class="gen">{L_SELECT_CATEGORY}</span></td> 
					<td class="row2" width="78%"><span class="gen">
						<select name="cat_sub_cat" style="border: solid #000000 1px"> 
						<option>{L_PLEASE_SELECT}</option> 
				<!-- END move_allowed --> 
				<!-- BEGIN categorylist --> 
						<option>{categorylist.OPTION}</option> 
				<!-- END categorylist --> 
				<!-- BEGIN move_allowed -->       
					</select></span></td>                               
				</tr> 
				<!-- END move_allowed --> 

				<tr> 
					<td class="row1"><span class="gen">{L_TITLE}</span></td>
					<td class="row2"><span class="gen"><input name="title" type="text" value="{TITLE}" size="60" maxlength="50" style="border: solid #000000 1px"></span></td>
				</tr>
				
				<tr> 
					<td class="row1"><span class="gen">{L_SHORT_DESC}</span></td>
					<td class="row2"><span class="gen"><textarea name="short_desc" cols="75" rows="2" style="border: solid #000000 1px">{SHORT_DESC}</textarea></span></td>
    			</tr>

			    <tr> 
					<td class="row1"><span class="gen">{L_PRICE}</span></td>
					<td class="row2"><span class="gen"><input name="price" type="text" value="{PRICE}" size="60" maxlength="50" style="border: solid #000000 1px"></span></td>
				</tr>

				<!-- BEGIN private_trade -->
				<tr> 
					<td class="row1"><span class="gen">{L_PRIVATE_OR_TRADE}</span></td>
					<td class="row2">
						<span class="gen">{L_PRIVATE}</span><input name="trade_ind" type="radio" value='0' {PRIVATE_CHECKED}>&nbsp;
						<span class="gen">{L_TRADE}</span><input name="trade_ind" type="radio" value='1' {TRADE_CHECKED}>
					</td>
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
					<td class="row2"><span class="gen"><input name="{custom_field.FIELD_NUMBER}" type="text" value="{custom_field.FIELD_VAL}" size="85" maxlength="100" style="border: solid #000000 1px"></span></td>
				</tr>
				<!-- END custom_field -->

				<!-- BEGIN not_basic_ad -->
				<tr> 
					<td class="row1"><span class="gen">{L_ADDITIONAL_INFO}</span></td>
					<td class="row2"><span class="gen"><textarea name="additional_info" cols="75" rows="10" style="border: solid #000000 1px">{ADDITIONAL_INFO}</textarea></span></td>
    			</tr>
				<!-- END not_basic_ad -->

				<tr> 
					<td colspan="2" class="catBottom" align="center"><input name="submit" type="submit" value="{L_EDIT_AD}" class="mainoption" style="border: solid #000000 1px"></td>
				</tr>

				</form>

			</table>
		</td>
	</tr>
</table>

<!-- PLEASE DO NOT REMOVE THIS LINK / COPYRIGHT NOTICE -->
<div align="center" class="copyright"><br />Classified Ads powered by <a href="http://www.phpca.net" target="_blank" class="copyright">phpCA</a> &copy; 2005, 2006 phpCA.net</div>