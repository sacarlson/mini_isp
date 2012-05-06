<form action="{S_POST_ACTION}" method="post">
<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
	<tr>
		<td width="100%"><span class="nav"><a class="nav" href="{U_ADS_INDEX}">{SITE_NAME} {L_ADS_INDEX}</a></span></td>
	</tr>
</table>

<table border="0" cellpadding="3" cellspacing="1" width="100%" class="forumline">
	
	<tr>
		<th class="thHead" colspan="2" height="25"><b>{L_SEARCH_QUERY}</b></th>
	</tr>

	<tr>
		<td width="50%" class="row1"><span class="gen">{L_SEARCH_FOR_KEYWORDS}:</span></td>
		<td width="50%" class="row2"><span class="genmed">
			<input name="search_term" type="text" style="border: solid #000000 1px" size="50" maxlength="50" class="post" /><br />
			<label><input name="search_terms" type="radio" value="any" checked /></label>{L_SEARCH_FOR_ANY_TERMS}<br />
			<label><input type="radio" name="search_terms" value="all" /></label>{L_SEARCH_FOR_ALL_TERMS}<br /></span>
		</td>
	</tr>

	<tr> 
		<td class="row1"><span class="gen">{L_SEARCH_FOR_USERNAME}:</span></td>
		<td class="row2"><input name="search_name" type="seller" style="border: solid #000000 1px" size="50" maxlength="50" class="post" /></td>
	</tr>

	<tr> 
		<td class="row1"><span class="gen">{L_SEARCH_FOR_AD_ID}:</span></td>
		<td class="row2"><input name="search_id" type="id" style="border: solid #000000 1px" size="10" maxlength="10" class="post" /></td>
	</tr>

	<tr>
		<th class="thHead" colspan="2" height="25"><b>{L_SEARCH_OPTIONS}</b></th>
	</tr>

	<tr> 
		<td class="row1"><span class="gen">{L_CAT_SUB_CAT}:</span></td>
		<td class="row2"><select name="cat_sub_cat" style="border: solid #000000 1px">
			<option value="all">{L_ALL}</option>
			<!-- BEGIN optionlist -->
			<option>{optionlist.OPTION}</option>
			<!-- END optionlist -->
	      </select>
		</td>
	</tr>

	<tr> 
		<td class="row1"><span class="gen">{L_AD_STATUS}:</span></td>
		<td class="row2"><select name="status">
			<option value="all">{L_ALL}</option>
			<option value="active">{L_ACTIVE}</option>
			<option value="sold">{L_SOLD}</option>
			<option value="expired">{L_EXPIRED}</option>
			</select>
		</td>
	</tr>

	<tr>
		<td width="50%" class="row1"><span class="gen">{L_SORT_BY}:</span></td>
		
	   <td width="50%" class="row2">
			<span class="genmed">
			<select name="sort_by">
			<option value="title">{L_TITLE}</option>
			<option value="time">{L_DATE_ADDED}</option>
			<option value="username">{L_USERNAME}</option>
			<option value="views">{L_VIEWS}</option>
			</select><br />
			
			<label><input name="sort_dir" type="radio" value="ASC" checked /></label>{L_ASCENDING}<br />
			<label><input name="sort_dir" type="radio" value="DESC" /></label>{L_DESCENDING}<br />
			</span>
		</td>
	</tr>

	<tr> 
		<td colspan="2" class="catBottom" align="center"><input name="submit" type="submit" value="{L_SEARCH}" class="mainoption" style="border: solid #000000 1px" /></td>
	</tr>

</table>
</form>

<!-- PLEASE DO NOT REMOVE THIS LINK / COPYRIGHT NOTICE -->
<div align="center" class="copyright">Classified Ads powered by <a href="http://www.phpca.net" target="_blank" class="copyright">phpCA</a> &copy; 2005, 2006 phpCA.net</div>