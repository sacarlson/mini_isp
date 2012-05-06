<!-- BEGIN switch_free_ad --> 
<form action="{U_RETURN}" method="post">
<!-- END switch_free_ad --> 
<!-- BEGIN switch_paid_ad --> 
<form action="{U_PAYPAL_URL}" method="post">
<input type="hidden" name="cmd" value="_xclick" />
<input type="hidden" name="business" value="{BUSINESS}" />
<input type="hidden" name="image_url" value="{U_IMAGE_URL}" />
<input type="hidden" name="notify_url" value="{U_NOTIFY_URL}" />
<input type="hidden" name="return" value="{U_RETURN}" />
<input type="hidden" name="cancel_return" value="{U_CANCEL_RETURN}" />
<input type="hidden" name="item_name" value="{ITEM_NAME}" />
<input type="hidden" name="amount" value="{AMOUNT}" />
<input type="hidden" name="no_note" value="1" />
<input type="hidden" name="currency_code" value="{CURRENCY_CODE}" />
<input type="hidden" name="custom" value="{CUSTOM}" />
<input type="hidden" name="lc" value="{LC}" />
<input type="hidden" name="quantity" value="1" />
<input type="hidden" name="cbt" value="{CBT}" />
<input type="hidden" name="no_shipping" value="1" />
<input type="hidden" name="rm" value="2" />
<!-- END switch_paid_ad --> 

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
		<td align="center" class="row1"><span class="gen">
		<br />{L_CATEGORY_SELECTED}: {CATEGORY}<br /> 
		<br />{L_SUB_CATEGORY_SELECTED}: {SUB_CATEGORY}<br />
		<br />{L_AD_TYPE_SELECTED}: {AD_TYPE}<br />
		<br />{L_AD_DURATION_SELECTED}: {AD_DURATION} {L_MONTHS}<br />
		<!-- BEGIN switch_paid_ad --> 
		<br />{L_AD_COST}: {L_CURRENCY}{AD_COST}<br />
		<!-- END switch_paid_ad --> 
		<!-- BEGIN switch_balance --> 
		<br />{L_ALREADY_PAID}: {L_CURRENCY}{USERS_BALANCE}<br />
		<br />===============
		<br />{L_BALANCE_DUE}: {L_CURRENCY}{BALANCE_DUE}
		<br />===============<br />
		<!-- END switch_balance --> 
		<!-- BEGIN switch_free_ad --> 
		<br />{L_AD_COST}: {L_FREE}<br />
		<!-- END switch_free_ad --> 
		<br />
		</td>
	</tr>

	<tr> 
		<!-- BEGIN switch_paypal_divert --> 
		<td colspan="2" class="catBottom" align="center"><input name="submit" type="submit" value="{L_CONTINUE}" class="mainoption" style="border: solid #000000 1px"/></td>
		<!-- END switch_paypal_divert --> 
		<!-- BEGIN switch_paypal --> 
		<td colspan="2" class="catBottom" align="center"><input name="submit" type="submit" value="{L_PAY_NOW_VIA_PAYPAL}" class="mainoption" style="border: solid #000000 1px"/></td>
		<!-- END switch_paypal --> 
	</tr>

</table>
</form>

<!-- PLEASE DO NOT REMOVE THIS LINK / COPYRIGHT NOTICE -->
<div align="center" class="copyright">Classified Ads powered by <a href="http://www.phpca.net" target="_blank" class="copyright">phpCA</a> &copy; 2005, 2006 phpCA.net</div>