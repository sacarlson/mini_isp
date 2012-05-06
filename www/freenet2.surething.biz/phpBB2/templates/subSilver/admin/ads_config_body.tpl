<h1>{L_ADS_CONFIGURATION_TITLE}</h1>

<p>{L_ADS_CONFIGURATION_EXPLAIN}</p>

<form action="{S_CONFIG_ACTION}" method="post"><table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
	  <th class="thHead" colspan="2">{L_ADS_GENERAL_SETTINGS}</th>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_VIEW_LEVEL}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {VIEW_GUEST} name="view_level" value="-1" />{L_GUEST}&nbsp;&nbsp;
	  													 <input type="radio" {VIEW_REG} name="view_level" value="0" />{L_REG}&nbsp;&nbsp;
	  													 <input type="radio" {VIEW_MOD} name="view_level" value="2" />{L_MOD}&nbsp;&nbsp;
	  													 <input type="radio" {VIEW_ADMIN} name="view_level" value="1" />{L_ADMIN}</span></td>
	</tr>
   <tr> 
     <td class="row1"><span class="genmed">{L_MOVE_LEVEL}</span></td> 
     <td class="row2"><span class="genmed"><input type="radio" {MOVE_REG} name="move_level" value="0" />{L_REG}&nbsp;&nbsp; 
														 <input type="radio" {MOVE_MOD} name="move_level" value="2" />{L_MOD}&nbsp;&nbsp; 
														 <input type="radio" {MOVE_ADMIN} name="move_level" value="1" />{L_ADMIN}</span></td> 
   </tr> 
	<tr>
	  <td class="row1"><span class="genmed">{L_SEARCH_LEVEL}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {SEARCH_GUEST} name="search_level" value="-1" />{L_GUEST}&nbsp;&nbsp;
	  													 <input type="radio" {SEARCH_REG} name="search_level" value="0" />{L_REG}&nbsp;&nbsp;
	  													 <input type="radio" {SEARCH_MOD} name="search_level" value="2" />{L_MOD}&nbsp;&nbsp;
	  													 <input type="radio" {SEARCH_ADMIN} name="search_level" value="1" />{L_ADMIN}</span></td>
	</tr>
	<tr>
		<td class="row1">{L_ADS_PER_PAGE}</td>
		<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="ads_per_page" value="{ADS_PER_PAGE}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_AD_DURATION_MONTHS}</td>
		<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="ad_duration_months" value="{AD_DURATION_MONTHS}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_MAX_ADS_PER_USER}</td>
		<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="max_ads_per_user" value="{MAX_ADS_PER_USER}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_MAX_IMAGES_PER_AD}</td>
		<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="max_images_per_ad" value="{MAX_IMAGES_PER_AD}" /></td>
	</tr>
	<tr>
	  <th class="thHead" colspan="2">{L_ADS_IMAGE_SETTINGS}</th>
	</tr>
	<tr>
		<td class="row1">{L_ENABLE_IMAGES}</td>
	  <td class="row2"><span class="genmed"><input type="radio" {IMAGES_ENABLED} name="images" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {IMAGES_DISABLED} name="images" value="0" />{L_NO}</span></td>
	</tr>
	<tr>
		<td class="row1">{L_THUMB_IMG_WIDTH}</td>
		<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="thumb_img_width" value="{THUMB_IMG_WIDTH}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_THUMB_IMG_HEIGHT}</td>
		<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="thumb_img_height" value="{THUMB_IMG_HEIGHT}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_MEDIUM_IMG_WIDTH}</td>
		<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="medium_img_width" value="{MEDIUM_IMG_WIDTH}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_MEDIUM_IMG_HEIGHT}</td>
		<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="medium_img_height" value="{MEDIUM_IMG_HEIGHT}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_LARGE_IMG_WIDTH}</td>
		<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="large_img_width" value="{LARGE_IMG_WIDTH}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_LARGE_IMG_HEIGHT}</td>
		<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="large_img_height" value="{LARGE_IMG_HEIGHT}" /></td>
	</tr>
	<tr>
	  <th class="thHead" colspan="2">{L_ADS_CHASE_SETTINGS}</th>
	</tr>
	<tr>
		<td class="row1">{L_FIRST_CHASE_DAYS}</td>
		<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="first_chase_days" value="{FIRST_CHASE_DAYS}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_SECOND_CHASE_DAYS}</td>
		<td class="row2"><input class="post" type="text" maxlength="4" size="4" name="second_chase_days" value="{SECOND_CHASE_DAYS}" /></td>
	</tr>
	<tr>
	  <th class="thHead" colspan="2">{L_EXTRA_SETTINGS}</th>
	</tr>
	<!-- BEGIN paid_ads_installed --> 
	<tr>
		<td class="row1">{L_PAID_ADS}</td>
	  <td class="row2"><span class="genmed"><input type="radio" {PAID_ADS_ENABLED} name="paid_ads" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {PAID_ADS_DISABLED} name="paid_ads" value="0" />{L_NO}</span></td>
	</tr>
	<!-- END paid_ads_installed --> 
	<tr>
	  <td class="row1"><span class="genmed">{L_COMMENT_SYSTEM}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {COMMENT_ENABLED} name="comment" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {COMMENT_DISABLED} name="comment" value="0" />{L_NO}</span></td>
	</tr>
<!--
	<tr>
	  <td class="row1"><span class="genmed">{L_RATE_SYSTEM}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {RATE_ENABLED} name="rate" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {RATE_DISABLED} name="rate" value="0" />{L_NO}</span></td>
	</tr>
	<tr>
	  <td class="row1"><span class="genmed">{L_RATE_SCALE}</span></td>
	  <td class="row2"><input class="post" type="text" name="rate_scale" value="{RATE_SCALE}" size="3" /></td>
	</tr>
--> 
	<tr>
	  <td class="row1"><span class="genmed">{L_PRIVATE_TRADE}</span></td>
	  <td class="row2"><span class="genmed"><input type="radio" {PRIVATE_TRADE_ENABLED} name="private_trade_ind" value="1" />{L_YES}&nbsp;&nbsp;<input type="radio" {PRIVATE_TRADE_DISABLED} name="private_trade_ind" value="0" />{L_NO}</span></td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" />
		</td>
	</tr>
</table></form>

<br clear="all" />