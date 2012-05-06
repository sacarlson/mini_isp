<?php
/***************************************************************************
 *                             lang_auction.php
 *                            -------------------
 *   begin                :   January 2004
 *   copyright            :   (C) FR
 *   email                :   fr@php-styles.com
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This hack is released under the GPL License. 
 *   This hack can be freely used, but not distributed, without permission. 
 *   Intellectual Property is retained by the author listed above. 
 *
 ***************************************************************************/


// Picture Admin
$lang['Auction_pic_config'] = 'Picture Upload Main Configuration';
$lang['Auction_pic_config_explain'] = 'Here you can set the main settings for your Picture upload. The main 2 problems to get the picture upload working reside in the chmod of the upload direcories AND in the propper settings of the GD library. We tried to automate this. Give it a try by setting on AUTO. If you have played around to much with the graphic settings, click on the "Reset to defaults" link. That should do the trick. - If you are not familiar with the settings click on "Automatic configuration" so the settings will be made according to your server.';
$lang['reset_to_defaults'] = 'Reset to defaults';
$lang['Auto_config'] = 'Automatic configuration';
$lang['base_pic_config'] = 'Main configuration';
$lang['Auction_pic_gd_yes'] = 'You seem to have GD library installed on your server: <b>GD version ';
$lang['Auction_pic_gd_yes2'] = '<BR />Proceed to the according settings below. If you don\'t know what to do, set to AUTO';
$lang['Auction_pic_gd_no'] = 'You do <b>NOT</b> seem to have GD library installed on your server! <br />Contact you server admin or try the settings at your own risk!!';
$lang['GD_version'] = '<b>GD settings</b><br />GD should be installed on your server. AUTO GD will check automatically for your GD version installed and activate the apropriate features on your server. You can also proceed to manual settings of your GD version.';
$lang['Manual_thumbnail'] = 'No GD installed';
$lang['Auto_GD'] = 'AUTO GD';
$lang['Dir_write'] = '<b>Manual settings</b><br />On your server some directories must be created and set to writable (often with chmod 777). We try to do this automatically for you. Depending on your server settings (safe mode) you will have,  in some cases, to proceed manually.';
$lang['dir_is_ok'] = '<b style="color:#006600">Everything seems OK.</b>';
$lang['The_dir'] = 'The directory';
$lang['Dir_write_manual'] = 'was <b style="color:#006600">found</b> but <b style="color:#FF0000">is not writable</b>!<br />Please proceed to manual settings (chmod 777)</b>';
$lang['Dir_write_create'] ='<b style="color:#FF0000"> ATTENTION! Directory not found!<br />Please proceed to manual creation and (chmod 777)</b>';
$lang['Dir_write_ok'] = 'Directory found and is writable. <b style="color:#006600">OK.</b>';
$lang['separ'] = '<hr />'; 
$lang['auction_offer_picture_allow'] = '<b>Activate Image upload</b><br />Allow to add pictures to an offer. This switches all the picture upload possibilities on or off. The main picture as well as the thumbnail gallery and the mini-icons.';
$lang['allow_url_upload'] = '<b>Url upload</b><br />Allow upload of an Image from the net. There will be an additional input field so users can either specify a file or an url to upload an image.';
$lang['auction_offer_picture_jpeg_allow'] = '<b>Allow to upload JPEG-Files</b><br />Allow users  to upload JPEG-Files. This should always be on. *.jpg files are the most used graphic files on the internet.';
$lang['auction_offer_picture_png_allow'] = '<b>Allow to upload PNG-Files</b><br />Allow users  to upload PNG-Files. PNG graphics are here to replace gif files. This format is supported by most browsers and can use transparency and other features not supported by jpg.';
$lang['png_convert'] = '<b>Convert PNG-Files to JPEG</b><br />When a user uploads a PNG file, store it in JPEG format. JPEG has much higher compression than PNG. You will loose any alpha-transparency but your disk-space will thank you for activating this feature.';
$lang['auction_offer_picture_gif_allow'] = '<b>Allow to upload GIF-Files</b><br />Allow users  to upload GIF-Files. GIF graphics are less and less used on the web. They only support 256 colors and Compuserve copyrighted the standard, so alway less free software support GIF. As GD does not support GIF, the pictures cannot be resized or manipulated.';
$lang['gif_convert_expl'] = '<b>Convert Gif files to JPEG</b><br />This feature is experimental. As GD does not work with gif files you can try to convert the gifs to jpg. It is slow and resource intensive, but it works. Gif files will not be allowed over a certain size in order not to overload your server. Gif upload has to be enabled above for this feature to work.';
$lang['auction_offer_gif_size_allow']  = '<b>Maximum Gif file size</b><br /> If Gif file conversion is enabled, you should limit the filesize of Gif files to under 180000 (180K) in order not to overload your server. As Gif conversion can take a long time, there may also be php script timeouts with bigger files (usually 30 seconds). Play around and set this to a size satisfying your needs.';
$lang['works_with_all'] = '<span class="gensmall"><br /><i>No GD needed</i></span>';
$lang['works_with_gd1'] = '<span class="gensmall"><br /><i>GD1 and higher</i></span>';
$lang['works_with_gd2'] = '<span class="gensmall"><br /><i>GD2 only</i></span>';
$lang['auction_offer_picture_size_allow'] = '<b>Limit the filesize to the following</b> (in bytes):<br /><u>If GD is enabled</u>: This is the filesize above which JPEG and PNG pictures are automatically resized. Gif Pictures cannot be uploaded above this size unless "convert gif to jpeg" is working. Then the upload limit for gifs will be set to 180K.<br /><u>If GD is not enabled</u>: This is the filesize above which no upload will be possible.';
$lang['server_image_limit'] = '<b>Server side filesize limit</b> (in bytes):<br />This is the size limit above which no upload is allowed. Pictures cannot be resized and will be rejected. (Only goes into effect if GD is enabled and only affects JPEG and PNG files - Default is 1MB.)';
$lang['Thumbnail_cache'] = '<b>Thumbnail cache</b><br />Use thumbnail cache. Main picture, thumbnails and mini-icons are cached if GD is enabled. If you have GD support on your server you should also activate the cache. Gif pictures are not cached unless "convert to jpeg" is running.';
// section 2
$lang['Base_picture_settings'] = 'Upload Picture basic settings';
$lang['max_width_big_pic'] = '<b>Maximum width</b><br /><u>If GD is enabled</u>: Set the maximum width (in pixels) allowed for the big picture (in the pop-up). The picture will be resized if its width exceeds the this width in pixels.<br /><u>If GD is not enabled</u>: Picture upload is not allowed for pictures exceeding this width.';
$lang['max_height_big_pic'] = '<b>Maximum height</b><br /><u>If GD is enabled</u>: Set the maximum height (in pixels) allowed for the big picture (in the pop-up). The picture will be resized if its width exceeds the this height in pixels.<br /><u>If GD is not enabled</u>: Picture upload is not allowed for pictures exceeding this height.';
$lang['quality_big_pic'] = '<b>Quality for resized pop-up</b><br />Set the quality for the resized pop-up picture in percent. Use 1 to 99. (100% does not work with some versions of GD that is why you can only fill in 2 digits.) <font color=red><b>Attention:</b></font> As this picture is used as a base to generate the other  images (main picture, thumbnails, mini-icons etc..) we recomend at least <b>75</b>%.';
$lang['main_offer_image'] = 'Main offer image';
$lang['clear_main_cache'] = '<b>Clear main picture cache</b>';
$lang['clear_main_cache_expl'] = 'If GD is enabled and if you are using the cache feature, you <b>must</b> clear the main picture cache so that the changes in this section can take effect. Images will then be re-generated: ';
$lang['clear_all_cache_expl'] = 'If you have made many changes in different sections you can also clear all the caches: ';
$lang['clear_all_cache'] = '<b>Clear all caches</b>';
$lang['max_size_main_pic'] = '<b>Main picture size</b><br />Set the size you wish (in pixels) allowed for the main offer picture. If GD is enabled the picture will be resized to this width or height whichever is the biggest. If GD is not enabled the picture will have height and width settings according to this size.';
$lang['quality_main_pic'] = '<b>Main picture Quality</b><br />Set the quality for the main offer picture in percent. Use 1 to 99. (100% does not work with some versions of GD that is why you can only fill in 2 digits.)';
$lang['main_offer_image_border_width'] = '<b>Main picture border width</b><br />Set width of the border of the main offer picture in pixels.';
$lang['main_offer_image_border'] = '<b>Main picture border</b><br />You can set a border around the main picture. If you do set a border it will be 1 pixel wide. You can set the border color below.';
$lang['main_offer_image_border_col'] = '<b>Main picture border color</b><br />You can set a color to the border around the main picture. If you do set a color set the hex code without # at the beginning.';
$lang['main_pic_sharpen'] = '<b>Main picture sharpen</b><br />The sharpening routine only works if GD is enabled and only for jpg files. The settings are hardcoded. In a future release there will be advanced settings where you can manipulate the sharpening of images.';
$lang['main_pic_bw'] = '<b>Main picture grayscale</b><br />This transforms the main picture to a grayscale picture. It only affects the displayed picture. The big pop-up picture will still have its colors. This overrides the javascript setting below.';
$lang['main_pic_js_bw'] = '<b>Main picture Javascript Mouseover</b><br />This is a javascript special effect that works only with IE. The picture gets set to black & white and on mouseover it regains its colors. Only works if the GD black and white option is disabled.';
$lang['leave_as_is'] = 'No sharpening';
$lang['sharpen'] = 'Sharpen';
$lang['sharpen_more'] = 'Sharpen more';
$lang['blur'] = 'Blur';
$lang['blur_more'] = 'Blur more';
$lang['mini_icon_settings'] = 'Mini icon settings';
$lang['clear_mini_icon_cache_expl'] = 'If GD is enabled and if you are using the cache feature, you <b>must</b> clear the mini icon cache so that the changes in this section can take effect. Images will then be re-generated: ';
$lang['clear_mini_icon_cache'] = '<b>Clear mini icon cache</b>';
$lang['max_size_mini_pic'] = '<b>Mini icon picture size</b><br />Set the size you wish (in pixels) allowed for the mini offer icon picture (also used in specials). If GD is enabled the picture will be resized to this width or height whichever is the biggest. If GD is not enabled the picture will have height and width settings according to this size.';
$lang['quality_mini_pic'] = '<b>Mini icon picture Quality</b><br />Set the quality for the mini offer icon picture in percent (also used in specials). Use 1 to 99. (100% does not work with some versions of GD that is why you can only fill in 2 digits.)';
$lang['mini_offer_image_border'] = '<b>Mini icon border</b><br />You can set a border around the mini icon. If you do set a border it will be 1 pixel wide. You can set the border color below.';
$lang['mini_offer_image_border_col'] =  '<b>Mini icon border color</b><br />You can set a color to the border around the mini icon. If you do set a color set the hex code without # at the beginning.';
$lang['mini_pic_border_width'] = '<b>Mini icon border width</b><br />Set width of the border of the Mini icon in pixels.';
$lang['mini_pic_sharpen'] = '<b>Mini icon sharpen</b><br />The sharpening routine only works if GD is enabled and only for jpg files. The settings are hardcoded. In a future release there will be advanced settings where you can manipulate the sharpening of images.';
$lang['mini_pic_bw'] = '<b>Mini icon grayscale</b><br />This transforms the mini icon to a grayscale picture. This setting only affects the mini icon.';
$lang['offer_gallery_settings'] = 'Offer gallery settings';
$lang['clear_thumb_cache'] = '<b>Clear thumbnail cache</b>';
$lang['auction_offer_thumbs_allow'] = '<b>Gallery activation</b><br />Allow the use of additional pictures to the main picture. These pictures will be shown beneath the offer and the main picture as thumbnails. This feature can only be activated if you have allowed to add pictures above.';
$lang['picture_thumbs_amount'] = '<b>Amount of gallery pictures</b><br />Set the allowed amount of additional pictures. As there is no pagination inside an offer, a maximum of 10 thumbnails is possible. Setting to "none" has the same effect than switching the use of additional thumbnails off above.';
$lang['auction_offer_thumbs_colums'] = '<b>Gallery columns</b><br />If the gallery above has been activated, and you set the amount of images (thumbnails) higher than 4, you might want to have the thumbnails on several lines. Therefor you can choose here howmany columns of thumbnails you want to have in a row. Only 3, 4 or 5 are possible for layout reasons. Example: If you allow 6 thumbnails you will want to set columns to 3 wich will give you 2 rows of 3 thumbnails each.';
$lang['thumbnail_type'] = '<b>Thumbnail picture type</b><br />This is the aspect of the Thumbnail pictures if you enabled the offer gallery above. Default setting is to square so the page has a cleaner look. It will then be either autocropped or manually cropped according to your settings. Attention: if GD is not enabled or if you are allowing gif pictures you could have distortions with settings other than normal!';
$lang['thumb_pic_square'] = 'Square';
$lang['thumb_pic_normal'] = 'Normal';
$lang['max_size_thumb_pic'] = '<b>Thumbnail size</b><br />Set the size you wish (in pixels) allowed for the thumbnail  pictures in the gallery (only if offer gallery has been enabled above). If GD is enabled the picture will be resized to this width or height whichever is the biggest. If GD is not enabled the picture will have height and width settings according to this size.';
$lang['quality_thumb_pic'] = '<b>Thumbnail Quality</b><br />Set the quality for the thumbnail  pictures in percent in the gallery (only if offer gallery has been enabled above). Use 1 to 99. (100% does not work with some versions of GD that is why you can only fill in 2 digits.)';
$lang['thumb_offer_image_border'] = '<b>Thumbnail border</b><br />You can set a border around the mini icon. If you do set a border it will be 1 pixel wide. You can set the border color below.';
$lang['thumb_offer_image_border_col'] =  '<b>Thumbnail border color</b><br />You can set a color to the border around the mini icon. If you do set a color set the hex code without # at the beginning.';
$lang['thumb_pic_sharpen'] = '<b>Thumbnail sharpen</b><br />The sharpening routine only works if GD is enabled and only for jpg files. The settings are hardcoded. In a future release there will be advanced settings where you can manipulate the sharpening of images.';
$lang['thumb_pic_bw'] = '<b>Thumbnail grayscale</b><br />This transforms the mini icon to a grayscale picture. This setting only affects the mini icon.';
$lang['thumb_border_width'] = '<b>Thumbnail border width</b><br />Set width of the border of the thumbnails in pixels.';
$lang['thumb_pic_js_bw'] = '<b>Thumbnail Javascript Mouseover</b><br />This is a javascript special effect that works only with IE. The thumbnail gets set to black & white and on mouseover it regains its colors. Only works if the GD black and white option is disabled.';
$lang['bytes'] = 'bytes';
$lang['Hotlink_prevent'] = '<b>Hotlink Prevention</b><br />This will keep other sites from linking to your pictures. If you switch hotlink prevention on, no one will be able to link to your pictures except the domains specified below.';
$lang['Hotlink_allowed'] = '<b>Allowed domains</b> for hotlink<br />If you wish to allow some domains to link to your pictures, you can specify them here (separated by a comma). Attention only 255 characters are possible.';
$lang['Pics_Approval_A'] = '<b>Pic Approval</b><br />If you activate this feature, the Admin must manually approve pictures before they will be visible on the site. Can be useful if your users sell adult stuff and you don\'t want any hardcore pics on your site. In this version the pic appoval can only be switched on and off for the entire site with all its categories.';
$lang['Pics_Approval_M'] = '<b>Pic Approval by Moderators</b><br />If you switch this feature on, moderators of your forums can also approve or reject pictures. (Will only work if the main pic approval above ist switched on.)';
$lang['security_settings'] = 'Security settings';
$lang['Watermark_big_settings'] = 'Watermark settings base (popup) image';
$lang['big_pic_use_water'] = '<b>Activate Watermark for popup picture</b><br />You can use a Watermark Picture for the popup picture. The file must be smaller than the maximum size set to the popup picture. For this feature to work, you have to upload a watermark image below.';
$lang['big_pic_for_guest_water'] = '<b>Use this watermark for guest users only</b><br />Use this watermark for guest users. Registered Users will see no watermark. Good if you use a big watermark and want to make users register to see the pictures.';
$lang['big_pic_current_water'] = '<b>Your current Watermark graphic</b><br />This is your current Watermark picture. If there is no picture uploaded, you will see "NO PICTURE UPLOADED". In this case upload your watermark graphic to make the watermark feature work.';
$lang['no_watermark'] = "NO WATERMARK PICTURE UPLOADED!";
$lang['big_water_img_qual'] = '<b>Watermarked popup image quality</b><br />Set the image quality for the watermarked image.  Use 1 to 99. (100% does not work with some versions of GD that is why you can only fill in 2 digits.).';
$lang['big_pic_test_water'] = '<b>Test your current Watermark</b><br />By clicking on the link you can test your watermark settings. For this, make sure you have not removed the "test picture" in your upload directory. It is called: ';
$lang['clear_thumb_cache_expl'] = 'If GD is enabled and if you are using the cache feature, you <b>must</b> clear the thumbnail cache so that the changes in this section can take effect. Images will then be re-generated: ';
$lang['Confirm'] = 'Confirm';
$lang['clear_all_cache_confirm'] = 'You are about to clear all your cache directories. All images will be regenerated.<br /><br /> Do you want to clear them now?';
$lang['clear_mini_cache_confirm'] = 'You are about to clear your mini icon cache directory. All images will be regenerated.<br /><br /> Do you want to clear your mini icon cache now?';
$lang['clear_thumb_cache_confirm'] = 'You are about to clear your thumbnail cache directory. All images will be regenerated.<br /><br /> Do you want to clear your thumbnail cache now?';
$lang['clear_main_cache_confirm'] = 'You are about to clear your main picture cache directory. All images will be regenerated.<br /><br /> Do you want to clear your main picture cache now?';
$lang['clear_water_cache_confirm'] = 'You are about to clear your main picture watermark cache directory. All images will be regenerated.<br /><br /> Do you want to clear your watermark picture cache now?';
$lang['clear_big_water_cache_confirm'] = 'You are about to clear your watermark popup picture cache directory. All images will be regenerated.<br /><br /> Do you want to clear your watermark picture cache now?';
$lang['All_cache_cleared_successfully'] = '<br />All your cache directories have been cleared successfully<br />&nbsp;';
$lang['mini_cache_cleared_successfully'] = '<br />Your mini icon cache directory has been cleared successfully<br />&nbsp;';
$lang['Thumbnail_cache_cleared_successfully'] = '<br />Your thumbnail cache directory has been cleared successfully<br />&nbsp;';
$lang['main_cache_cleared_successfully'] = '<br />Your main picture cache directory has been cleared successfully<br />&nbsp;';
$lang['water_cache_cleared_successfully'] = '<br />Your watermark popup picture cache directory has been cleared successfully<br />&nbsp;';
$lang['water_main_cache_cleared_successfully'] = '<br />Your watermark main picture cache directory has been cleared successfully<br />&nbsp;';
$lang['Clear_all_Cache'] = 'Clear all cache directories';
$lang['clear_water_cache_expl'] =  'If GD is enabled and if you modified the watermark images, you <b>must</b> clear the two watermark caches so that the changes in this section can take effect. Images will then be re-generated: ';
$lang['clear_big_water_cache'] = '<b>Clear watermark cache for the popup picture</b>';
$lang['clear_main_water_cache'] = '<b>Clear watermark cache for the main offer picture</b>';
$lang['Watermark_settings'] = 'Watermark settings main image';
$lang['not_impl'] = '<br /><b>NOT IMPLEMENTED YET... COMING SOON...</b><br /><br />';
$lang['or_also'] = ' or you can ';
$lang['Click_return_auction_pic_config'] = 'Click %sHere%s to return to the Auction Image Configuration';
$lang['Auction_pic_config_updated'] = 'Auction Image Configuration has been updated successfully';
$lang['main_pic_use_water'] = '<b>Activate Watermark for main offer picture</b><br />You can use a Watermark Picture for the main offer picture. The file must be smaller than the maximum size set to the main offer picture. For this feature to work, you have to upload a watermark image below.';
$lang['main_pic_current_water'] = '<b>Your current Watermark graphic</b><br />This is your current Watermark picture. If there is no picture uploaded, you will see "NO PICTURE UPLOADED". In this case upload your watermark graphic to make the watermark feature work.';
$lang['main_pic_test_water'] = '<b>Test your current Watermark</b><br />By clicking on the link you can test your watermark settings. For this, make sure you have not removed the "test picture" in your upload directory. It is called: ';
$lang['main_water_img_qual'] = '<b>Watermarked main image quality</b><br />Set the image quality for the watermarked image.  Use 1 to 99. (100% does not work with some versions of GD that is why you can only fill in 2 digits.).';
$lang['main_water_img_trans'] = '<b>Watermark transparency</b><br />Set the opacity of the watermark image. Settings are from 1 to 100%. 1% is see-through. 100% is opaque.';
$lang['main_water_upload'] = '<b>Upload Watermark file</b><br />You can upload png files (transparency set to alpha) or gif files (test it, they should get converted to png) or jpeg files (no transparency is available for jpeg). Make sure the watermark does not exceed the size of your allowed images.';
$lang['main_water_img_pos'] = '<b>Position Watermark </b><br />You have 9 possible positions for your watermark file. From top left to bottom right. Place your watermark by clicking on the postion in the square on the right.';
$lang['main_pic_for_all_water'] = '<b>Use this watermark for all images</b><br />Use this watermark for all images. If this is enabled the watermark defined in the next section has no affect. This watermark will be used on main picture and on the popup picture.';
$lang['main_pic_for_guest_water'] = '<b>Use this watermark for guest users only</b><br />Use this watermark for guest users. Registered Users will see no watermark. Good if you use a big watermark and want to make users register to see the pictures.';
$lang['guest_only'] = 'Guests only';
$lang['All_users'] = 'All Users';
$lang['allow_editing'] = '<b>Allow offer picture editing</b><br />There are three levels: <br />1. Allow all users to edit their images at all times.<br />2. Allow users to edit their pictures until first bid.<br /> 3. Don\'t allow picture editing.';
$lang['edit_level_2'] = 'Always allow';
$lang['edit_level_1'] = 'Allow until first bid';
$lang['edit_level_0'] = 'Never allow';

// not used

$lang['special_effects_explain'] = 'If you have GD2 enabled, you have a powerfull tool to manipulate pictures. Some features will work with GD1 also (like resizing and picture borders). Some features will only work on jpg  and some only with GD 2 (like image sharpening). When you activate a feature, be sure to test it before you use it in a production environment. <hr />If you committed an error and have image distortions you do not know how to switch back (because maybe you forgot the initial settings), you can set back the configuration to defaults by clicking here (This only affects the parameters in this section!):';
$lang['mini_icon_type'] = '<b>Mini icon type</b><br />This is the aspect of the mini-icons used in the special-offer block or in some listings. Default setting is to square so the page has a cleaner look. It will then be either autocropped or manually cropped according to your settings. Attention: if GD is not enabled or if you are allowing gif pictures you could have distortions!';
$lang['wmk_test_for_big'] = 'Test Big Watermark';
$lang['wmk_test_for_main'] = 'Test Main Watermark';

?>