<h1>{L_ADS_PAID_ADS_TITLE}</h1>

<p>{L_ADS_PAID_ADS_EXPLAIN}</p>

<form action="{S_CONFIG_ACTION}" method="post"><table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">

	<tr>
	  <th class="thHead" colspan="2">{L_ADS_PAID_ADS_SETTINGS}</th>
	</tr>

	<tr>
		<td class="row1">{L_BASIC_ADS_ALLOWED}</td>
		<td class="row2"><input type="radio" name="basic" value="1" {S_BASIC_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="basic" value="0" {S_BASIC_NO} /> {L_NO}</td>
	</tr>

	<tr>
		<td class="row1">{L_STANDARD_ADS_ALLOWED}</td>
		<td class="row2"><input type="radio" name="standard" value="1" {S_STANDARD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="standard" value="0" {S_STANDARD_NO} /> {L_NO}</td>
	</tr>

	<tr>
		<td class="row1">{L_PHOTO_ADS_ALLOWED}</td>
		<td class="row2"><input type="radio" name="photo" value="1" {S_PHOTO_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="photo" value="0" {S_PHOTO_NO} /> {L_NO}</td>
	</tr>

	<tr>
		<td class="row1">{L_PREMIUM_ADS_ALLOWED}</td>
		<td class="row2"><input type="radio" name="premium" value="1" {S_PREMIUM_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="premium" value="0" {S_PREMIUM_NO} /> {L_NO}</td>
	</tr>

	<tr>
	  <th class="thHead" colspan="2">{L_ADS_PAYPAL_SETTINGS}</th>
	</tr>

	<tr>
		<td class="row1">{L_BUSINESS_EMAIL}</td>
		<td class="row2"><input class="post" type="text" maxlength="127" size="40" name="business_email" value="{BUSINESS_EMAIL}" /></td>
	</tr>

	<tr>
		<td class="row1">{L_CURRENCY_CODE}</td>
		<td class="row2">{CURRENCY_CODE_SELECT}</td>
	</tr>

	<tr>
		<td class="row1">{L_LANGUAGE_CODE}</td>
		<td class="row2">{LANGUAGE_CODE_SELECT}</td>
	</tr>

	<tr>
		<td class="row1">{L_SANDBOX}<br /><span class="gensmall">{L_SANDBOX_EXPLAIN}</span></td>
		<td class="row2"><input type="radio" name="sandbox" value="1" {S_SANDBOX_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="sandbox" value="0" {S_SANDBOX_NO} /> {L_NO}</td>
	</tr>

	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" />
		</td>
	</tr>
</table></form>

<br clear="all" />
