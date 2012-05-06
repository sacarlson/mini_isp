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

<table width="100%" border="0" cellpadding="2" cellspacing="1" class="forumline">
	<tr>
		<th class="thHead" colspan="2" height="25"><b>{L_ADD_IMAGES}</b></th>
	</tr>
	<form enctype="multipart/form-data" action="{U_CREATE_IMG}" method="post">
	<tr> 
		<td align="center" class="row1"><span class="gen">
		<br />{L_IMAGE_TYPES}
		<br /><br />
		<input type="file" name="image" size="50" style="border: solid #000000 1px">
		<br /><br /></span></td>
	</tr>
	<tr>
		<td align="center" class="catBottom" >
		<input type="submit" name="submit" value="{L_UPLOAD_IMAGE}" class="mainoption" style="border: solid #000000 1px">
		</td>
	</tr>
	</form>
</table>
<br />

<!-- BEGIN switch_images_found --> 
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="forumline">
	<tr>
		<th class="thHead" colspan="2" height="25"><b>{L_DELETE_IMAGES}</b></th>
	</tr>
	<tr>
		<td align="center" class="row1">
			<table>
				<tr>
<!-- END switch_images_found --> 

					<!-- BEGIN imagecolumn -->
					<td>
						<img src="{imagecolumn.IMG_URL}"><br />
						<a href="{imagecolumn.U_DELETE_IMG_URL}"><img src="{ICON_DELETE}" border="0" alt="Delete image"></a>
					</td>
					<!-- END imagecolumn -->

<!-- BEGIN switch_images_found --> 
				</tr>
			</table>
		</td>
	<tr>
</table>
<!-- END switch_images_found --> 

<!-- PLEASE DO NOT REMOVE THIS LINK / COPYRIGHT NOTICE -->
<div align="center" class="copyright">Classified Ads powered by <a href="http://www.phpca.net" target="_blank" class="copyright">phpCA</a> &copy; 2005, 2006 phpCA.net</div>