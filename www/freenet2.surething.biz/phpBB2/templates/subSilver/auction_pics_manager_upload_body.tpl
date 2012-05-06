
<table width="100%" cellspacing="2" cellpadding="2" border="0">
  <tr>
	<td width="100%"><span class="maintitle">Phpbb-Auction Picture Manager</span></td>
	
		
  </tr>
</table>
  <br />
  
   <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
	<tr> 
	  <th height="25"  class="thCornerL" align=center nowrap="nowrap">IMAGE UPLOAD</th>
	  </tr><form enctype="multipart/form-data" method="post" action="{S_UPLOAD_ACTION}">
                     <tr>
                        <td   class="row1"><span class="genmed"><b>Upload your image from your PC:</b><br />You can upload an image of following type: *.jpg, *.png or *.gif. Gif images are limited to 180 Kb. Other images are limited to 1024 Kb, but beware if you have a slow connection, your upload may timeout.</span></td>
					</tr>
                     <tr>

                        <td  class="row2"><span class=genmed>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>File:</b>&nbsp;&nbsp;<input class="post" size=35 type="file" name="auction_offer_picture_file" /></span></td>
                     </tr>
                  <!-- BEGIN urlupload -->
                     <tr>
                        <td   class="row1"><span class="genmed"><b>Upload your image from the Internet:</b><br />You can upload an image of following type: *.jpg, *.png or *.gif. Gif images are limited to 180 Kb. Other images are limited to 1024 Kb, but beware if you have a slow connection, your upload may timeout.</span></td>
							  </tr>
                     <tr>

                        <td  class="row2" ><span class=genmed>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>URL:</b>&nbsp;&nbsp;<input class="post" size=35 type="text" name="auction_offer_url_file" value="http://" /></span></td>
                     </tr>
				<!-- END urlupload -->
                    
					 	<tr> 
		<td class="catBottom"  align="center" height="28">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="Upload Image" /></td>
	</tr>

</table>

