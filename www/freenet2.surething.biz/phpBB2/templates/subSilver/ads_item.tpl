<table width="100%" cellspacing="2" cellpadding="2" border="0">
	<tr>
		<td align="left" valign="bottom"><a class="maintitle" href="{U_ADS_ITEM}">{TITLE}<br /></a></td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="2" cellpadding="2">
	<tr> 
		<td><a href="{U_CREATE_AD}"><img src="{MSG_NEWAD}" border="0" alt="New ad"></a></td>
		<td width="100%"><span class="nav"><a class="nav" href="{U_ADS_INDEX}">{SITE_NAME} {L_ADS_INDEX}</a></span></td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>

		<td valign="top" width="100%">

			<table width="99%" border="0" cellpadding="2" cellspacing="1" class="forumline">
				<tr>
					<th class="thHead" colspan="2" height="25"><b>{L_ADVERT_DETAILS}</b></th>
				</tr>

				<tr>
					<td class="catLeft" height="28"><span class="cattitle">{L_SUMMARY}</span></td>
					<td class="rowpic" align="right">
						<!-- BEGIN edit_allowed --> 
						<a href="{U_EDIT_AD}"><img src="{ICON_EDIT}" border="0" alt="{L_EDIT_AD}"></a>
						<!-- END edit_allowed --> 
						<!-- BEGIN image_allowed --> 
						<a href="{U_IMAGES}"><img src="{ICON_IMAGES}" border="0" alt="{L_IMAGES}"></a>
						<!-- END image_allowed --> 
						<!-- BEGIN delete_allowed --> 
						<a href="{U_DELETE_AD}"><img src="{ICON_DELPOST}" border="0" alt="{L_DELETE}"></a>
						<!-- END delete_allowed --> 
					</td>
				</tr>

				<tr>
					<td class="row1" width="22%"><span class="gen">{L_CATEGORY}</span></td>
					<td class="row2" width="78%"><span class="gen">{CATEGORY}</span></td>
				</tr>

				<tr>
					<td class="row1"><span class="gen">{L_SUB_CATEGORY}</span></td>
					<td class="row2"><span class="gen">{SUB_CATEGORY}</span></td>
				</tr>

				<tr>
					<td class="row1"><span class="gen">{L_TITLE}</span></td>
					<td class="row2"><span class="gen">{TITLE}</span></td>
				</tr>

				<tr>
					<td class="row1"><span class="gen">{L_SHORT_DESC}</span></td>
					<td class="row2"><span class="gen">{SHORT_DESC}</span></td>
				</tr>

				<tr>
					<td class="row1"><span class="gen">{L_PRICE}</span></td>
					<td class="row2"><span class="gen">{PRICE}</span></td>
				</tr>

				<!-- BEGIN private_trade -->
				<tr>
					<td class="row1"><span class="gen">{L_SALE_TYPE}</span></td>
					<td class="row2"><span class="gen">{SALE_TYPE}</span></td>
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
					<td class="row2"><span class="gen">{custom_field.FIELD_VAL}</span></td>
				</tr>
				<!-- END custom_field -->

				<!-- BEGIN not_basic_ad -->
				<tr>
					<td class="row1"><span class="gen">{L_ADDITIONAL_INFO}</span></td>
					<td class="row2"><span class="gen">{ADDITIONAL_INFO}</span></td>
				</tr>
				<!-- END not_basic_ad -->

				<tr>
					<td class="catLeft" height="28"><span class="cattitle">{L_ADVERT_INFO}</span></td>
					<td class="rowpic" align="right">&nbsp;</td>
				</tr>

				<tr>
					<td class="row1"><span class="gen">{L_DATE_ADDED}</span></td>
					<td class="row2"><span class="gen">{DATE_ADDED}&nbsp;{EDIT_DETAILS}</span></td>
				</tr>

				<tr>
					<td class="row1"><span class="gen">{L_EXPIRY_DATE}</span></td>
					<td class="row2"><span class="gen">{EXPIRY_DATE}
					<!-- BEGIN switch_renewal_allowed --> 
					&nbsp;[ <a href="{U_RENEW_AD}"><img src="{ICON_RENEW}" border="0" align="absmiddle" alt="Renew ad"></a> ad ]
					<!-- END switch_renewal_allowed --> 		
					</span></td>
				</tr>

				<tr>
					<td class="row1"><span class="gen">{L_AD_STATUS}</span></td>
					<td class="row2"><span class="gen">{STATUS}
					<!-- BEGIN switch_active --> 
					&nbsp;[ {L_CHANGE_STATUS_TO} <a href="{U_STATUS_TO_SOLD}"><img src="{ICON_SOLD}" border="0" align="absmiddle" alt="Sold"></a> ]
					<!-- END switch_active --> 		
					<!-- BEGIN switch_sold --> 
					&nbsp;[ {L_CHANGE_STATUS_TO} <a href="{U_STATUS_TO_ACTIVE}"><img src="{ICON_ACTIVE}" border="0" align="absmiddle" alt="Active"></a> ]
					<!-- END switch_sold --> 		
					</span></td>
				</tr>

				<tr>
					<td class="row1"><span class="gen">{L_VIEWS}</span></td>
					<td class="row2"><span class="gen">{VIEWS}</span></td>
				</tr>

				<tr>
					<td class="row1"><span class="gen"><a href="{U_COMMENTS}">{L_COMMENTS}</a></span></td>
					<td class="row2"><span class="gen">{TOTAL_COMMENTS}</span></td>
				</tr>

				<tr>
					<td class="row1"><span class="gen">{L_ADVERTISER}</span></td>
					<td class="row2"><span class="gen">{USERNAME}&nbsp;
						 [ {L_ALL_SELLERS_ADS} <a href="{U_USER_SEARCH}">{USERNAME}</a> ]</span></td>
				</tr>

				<!-- BEGIN non_guest_ad --> 
				<tr>
					<td class="row1"><span class="gen">{L_CONTACT} {USERNAME}</span></td>
					<td class="row2" valign="top"><span class="gen">{PROFILE_IMG} {PM_IMG} {EMAIL_IMG} {WWW_IMG} {ICQ_STATUS_IMG} {ICQ_IMG} {AIM_IMG} {MSN_IMG} {YIM_IMG} </span></td>
				</tr>
				<!-- END non_guest_ad --> 
			</table>
		</td>

		<!-- BEGIN switch_images_found --> 
		<td valign="top">
			<table width="0%" border="0" cellpadding="2" cellspacing="1" class="forumline">
				<tr> 
					<td class="catHead"><span class="cattitle">{L_IMAGES}</span></td>
				</tr>
		<!-- END switch_images_found --> 

				<!-- BEGIN imagerow -->
				<tr>
					<td class="row1">{imagerow.IMAGE}</td>
				</tr>
				<!-- END imagerow -->

		<!-- BEGIN switch_images_found --> 
			</table>
		</td>
		<!-- END switch_images_found --> 

	</tr>
</table>

<!-- PLEASE DO NOT REMOVE THIS LINK / COPYRIGHT NOTICE -->
<div align="center" class="copyright"><br />Classified Ads powered by <a href="http://www.phpca.net" target="_blank" class="copyright">phpCA</a> &copy; 2005, 2006 phpCA.net</div>