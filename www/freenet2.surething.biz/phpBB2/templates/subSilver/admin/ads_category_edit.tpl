
<h1>{L_ADS_EDIT_CATEGORY_TITLE}</h1>

<p>{L_ADS_EDIT_CATEGORY_EXPLAIN}</p>

<form action="{S_CATEGORIES_ACTION}" method="post">
  <table cellpadding="4" cellspacing="1" border="0" class="forumline" align="center">
	<tr> 
	  <th class="thHead" colspan="2">{L_ADS_EDIT_CATEGORY_SETTINGS}</th>
	</tr>
	<tr> 
	  <td class="row1">{L_CATEGORY}</td>
	  <td class="row2"><input class="post" type="text" size="25" name="category" value="{CATEGORY}" readonly="true" /></td>
	</tr>
	<tr> 
	  <td class="row1">{L_SUB_CATEGORY}</td>
	  <td class="row2"><input class="post" type="text" size="25" name="sub_category" value="{SUB_CATEGORY}" readonly="true" /></td>
	</tr>
	<!-- BEGIN paid_ads_installed --> 
	<tr> 
	  <td class="row1">{L_BASIC_COST}</td>
	  <td class="row2"><input class="post" type="text" size="25" name="basic_cost" value="{BASIC_COST}" /></td>
	</tr>
	<tr> 
	  <td class="row1">{L_STANDARD_COST}</td>
	  <td class="row2"><input class="post" type="text" size="25" name="standard_cost" value="{STANDARD_COST}" /></td>
	</tr>
	<tr> 
	  <td class="row1">{L_PHOTO_COST}</td>
	  <td class="row2"><input class="post" type="text" size="25" name="photo_cost" value="{PHOTO_COST}" /></td>
	</tr>
	<tr> 
	  <td class="row1">{L_PREMIUM_COST}</td>
	  <td class="row2"><input class="post" type="text" size="25" name="premium_cost" value="{PREMIUM_COST}" /></td>
	</tr>
	<!-- END paid_ads_installed --> 
	<tr> 
	  <th class="thHead" colspan="2">{L_ADS_CUSTOM_FIELDS}</th>
	</tr>
	<tr> 
	  <td class="row1">{L_FIELD_1_DESC}</td>
	  <td class="row2"><input class="post" type="text" size="50" name="field_1_desc" value="{FIELD_1_DESC}" /></td>
	</tr>
	<tr> 
	  <td class="row1">{L_FIELD_2_DESC}</td>
	  <td class="row2"><input class="post" type="text" size="50" name="field_2_desc" value="{FIELD_2_DESC}" /></td>
	</tr>
	<tr> 
	  <td class="row1">{L_FIELD_3_DESC}</td>
	  <td class="row2"><input class="post" type="text" size="50" name="field_3_desc" value="{FIELD_3_DESC}" /></td>
	</tr>
	<tr> 
	  <td class="row1">{L_FIELD_4_DESC}</td>
	  <td class="row2"><input class="post" type="text" size="50" name="field_4_desc" value="{FIELD_4_DESC}" /></td>
	</tr>
	<tr> 
	  <td class="row1">{L_FIELD_5_DESC}</td>
	  <td class="row2"><input class="post" type="text" size="50" name="field_5_desc" value="{FIELD_5_DESC}" /></td>
	</tr>
	<tr> 
	  <td class="row1">{L_FIELD_6_DESC}</td>
	  <td class="row2"><input class="post" type="text" size="50" name="field_6_desc" value="{FIELD_6_DESC}" /></td>
	</tr>
	<tr> 
	  <td class="row1">{L_FIELD_7_DESC}</td>
	  <td class="row2"><input class="post" type="text" size="50" name="field_7_desc" value="{FIELD_7_DESC}" /></td>
	</tr>
	<tr> 
	  <td class="row1">{L_FIELD_8_DESC}</td>
	  <td class="row2"><input class="post" type="text" size="50" name="field_8_desc" value="{FIELD_8_DESC}" /></td>
	</tr>
	<tr> 
	  <td class="row1">{L_FIELD_9_DESC}</td>
	  <td class="row2"><input class="post" type="text" size="50" name="field_9_desc" value="{FIELD_9_DESC}" /></td>
	</tr>
	<tr> 
	  <td class="row1">{L_FIELD_10_DESC}</td>
	  <td class="row2"><input class="post" type="text" size="50" name="field_10_desc" value="{FIELD_10_DESC}" /></td>
	</tr>

	<tr> 
	  <th class="thHead" colspan="2">{L_PERMISSIONS}</th>
	</tr>
	<tr> 
	  <td class="row1"><span class="genmed">{L_CREATE_LEVEL}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {CREATE_ALL} name="cat_create_level" value="{S_GUEST}" />{L_GUEST}&nbsp;&nbsp;
														 <input type="radio" {CREATE_REG} name="cat_create_level" value="{S_USER}" />{L_REG}&nbsp;&nbsp;
														 <input type="radio" {CREATE_MOD} name="cat_create_level" value="{S_MOD}" />{L_MOD}&nbsp;&nbsp;
														 <input type="radio" {CREATE_ADMIN} name="cat_create_level" value="{S_ADMIN}" />{L_ADMIN}</span></td>
	</tr> 
	<tr> 
	  <td class="row1"><span class="genmed">{L_EDIT_LEVEL}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {EDIT_REG} name="cat_edit_level" value="{S_USER}" />{L_REG}&nbsp;&nbsp;
														 <input type="radio" {EDIT_MOD} name="cat_edit_level" value="{S_MOD}" />{L_MOD}&nbsp;&nbsp;
														 <input type="radio" {EDIT_ADMIN} name="cat_edit_level" value="{S_ADMIN}" />{L_ADMIN}</span></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="genmed">{L_DELETE_LEVEL}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {DELETE_REG} name="cat_delete_level" value="{S_USER}" />{L_REG}&nbsp;&nbsp;
														 <input type="radio" {DELETE_MOD} name="cat_delete_level" value="{S_MOD}" />{L_MOD}&nbsp;&nbsp;
														 <input type="radio" {DELETE_ADMIN} name="cat_delete_level" value="{S_ADMIN}" />{L_ADMIN}</span></td>
	</tr>
	<tr> 
	  <td class="row1"><span class="genmed">{L_IMAGE_LEVEL}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {IMAGE_REG} name="cat_image_level" value="{S_USER}" />{L_REG}&nbsp;&nbsp;
														 <input type="radio" {IMAGE_MOD} name="cat_image_level" value="{S_MOD}" />{L_MOD}&nbsp;&nbsp;
														 <input type="radio" {IMAGE_ADMIN} name="cat_image_level" value="{S_ADMIN}" />{L_ADMIN}</span></td>
	</tr> 
	<tr> 
	  <td class="row1"><span class="genmed">{L_COMMENT_LEVEL}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {COMMENT_ALL} name="cat_comment_level" value="{S_GUEST}" />{L_GUEST}&nbsp;&nbsp;
														 <input type="radio" {COMMENT_REG} name="cat_comment_level" value="{S_USER}" />{L_REG}&nbsp;&nbsp;
														 <input type="radio" {COMMENT_MOD} name="cat_comment_level" value="{S_MOD}" />{L_MOD}&nbsp;&nbsp;
														 <input type="radio" {COMMENT_ADMIN} name="cat_comment_level" value="{S_ADMIN}" />{L_ADMIN}</span></td>
	</tr> 
	<tr> 
	  <td class="row1"><span class="genmed">{L_RATE_LEVEL}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {RATE_ALL} name="cat_rate_level" value="{S_GUEST}" />{L_GUEST}&nbsp;&nbsp;
														 <input type="radio" {RATE_REG} name="cat_rate_level" value="{S_USER}" />{L_REG}&nbsp;&nbsp;
														 <input type="radio" {RATE_MOD} name="cat_rate_level" value="{S_MOD}" />{L_MOD}&nbsp;&nbsp;
														 <input type="radio" {RATE_ADMIN} name="cat_rate_level" value="{S_ADMIN}" />{L_ADMIN}</span></td>
	</tr> 
	<tr> 
	  <td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></td>
	</tr>
  </table>
</form>
		
<br clear="all" />
