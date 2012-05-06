<h1>{L_ADS_CATEGORIES_TITLE}</h1>

<p>{L_ADS_CATEGORIES_EXPLAIN}</p>

<table width="100%" border="0" cellpadding="2" cellspacing="1" class="forumline">
	<tr>
		<th class="thHead" colspan="3" height="25"><b>{L_ADS_CATEGORIES_SETTINGS}</b></th>
	</tr>

	<!-- BEGIN categoryrow -->
	<tr>
	{categoryrow.ROW}
	</tr>
	<!-- END categoryrow -->
	
	<tr>
		<form action="{S_CATEGORIES_ACTION}" method="post">
		<td colspan="3" class="row2">
		<input class="post" type="text" maxlength="25" name="category" value="{SAVE_CATEGORY}" readonly="true" />
		<input class="post" type="text" maxlength="25" name="sub_category" />
		<input class="liteoption" type="submit" name="submit" value="{L_CREATE_NEW_SUB_CAT}" />
		</td>
		</form>
	</tr>

	<tr>
		<td colspan="3" height="1" class="spaceRow"><img src="templates/subSilver/images/spacer.gif" alt="" width="1" height="1" /></td>
	</tr>

	<tr>
		<form action="{S_CATEGORIES_ACTION}" method="post">
		<td colspan="3" class="row2">
		<input class="post" type="text" maxlength="25" name="category" />
		<input class="post" type="text" maxlength="25" name="sub_category" />
		<input class="liteoption" type="submit" name="submit" value="{L_CREATE_NEW_CAT_SUB_CAT}" />
		</td>
		</form>
	</tr>
</table>

<br clear="all" />