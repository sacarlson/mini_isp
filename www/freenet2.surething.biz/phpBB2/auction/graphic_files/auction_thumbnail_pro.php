<?php
/***************************************************************************
 *                              auction_thumbnail_pro.php
 *                            -------------------
 *   begin                : May 2004
 *   copyright            : (C) Mr.Luc
 *   email                : llg@gmx.at
 *   compiled for         : phpbb-auction.com
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This hack is released under the GPL License.
 *   This hack can be freely used, but not distributed, without permission.
 *   Intellectual Property is retained by the author listed above.
 *
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


$crop_id = "";
		if( isset($HTTP_GET_VARS['crop']) )
		{
			$crop_id = intval($HTTP_GET_VARS['crop']);
		}
		else
		{
			$crop_id = "";
		}

		// 0 is normal.. 1 is square ... 2 is fixed size example: if thumbnail size is 120.. fixed size will be 120 x 80 (allways)!
		// type 3 is fixed height.. good if you line up thumbnails.. all will fit in a line.. height will allways be for example 120
		// type 4 is fixed width .. same with the width... (good for columns... portal block on the side)
		
		$album_config['thumb_type'] =  1;
		$album_config['auto_crop'] =  1;
		$album_config['crop_display'] = 1;

		// if the autocrop parameter is off
		// the $crop_id takes effect: 
		// $crop_id = 1 => left top for portrait (default for autocrop) or left for landscape
		// $crop_id = 2 => is middle for portrait or center for landscape (default for autocrop)
		// $crop_id = 3 => is bottom for portrait of right for landscape
		//  $crop_id = 1;
		
		$album_config['thumb_border'] = 1;
		$album_config['border_color'] = "000000";
		$album_config['sepia'] = 0;
		$album_config['color_shift'] = 0;
		// Sets the colorshift parameters.. unpredictable..
		$album_config['color_shift_red'] = 2;	// 0 = gray | 1 = red | 2 = green | 3 = blue
		$album_config['color_shift_green'] = 0;	// 0 = gray | 1 = red | 2 = green | 3 = blue
		$album_config['color_shift_blue'] = 1;	// 0 = gray | 1 = red | 2 = green | 3 = blue
		$album_config['colorize'] = 0;
		$album_config['colorize_color'] = "EEEEEE";
		$album_config['colorize_percent'] = 50;
		$album_config['raster'] =  0;
		$album_config['raster_line_color'] = "000000";

		/* set parameters */
			if($pic_type == 3) // its mini
			{
				$album_config['grayscale'] = $auction_config_pic['mini_pic_bw'];
				$album_config['thumb_border'] = $auction_config_pic['mini_pic_border'];
				$album_config['border_color'] = $auction_config_pic['mini_pic_border_color'];
			}
			else if ($pic_type == 2) // its a thumbnail
			{
				if($HTTP_GET_VARS['recrop'] == 1)
				{
					$album_config['grayscale'] = 0;
					$album_config['thumb_border'] = $auction_config_pic['thumb_pic_border'];
					$album_config['border_color'] = $auction_config_pic['thumb_pic_border_color'];
					$auction_config_pic['thumb_pic_sharpen'] = 1;
				}
				else
				{
					$album_config['grayscale'] = $auction_config_pic['thumb_pic_bw'];
					$album_config['thumb_border'] = $auction_config_pic['thumb_pic_border'];
					$album_config['border_color'] = $auction_config_pic['thumb_pic_border_color'];	
				}
			}
			else if ($pic_type == 1) // its the main-offer picture
			{
				$album_config['grayscale'] = $auction_config_pic['main_pic_bw'];
				$album_config['thumb_border'] = $auction_config_pic['main_pic_border'];
				$album_config['border_color'] = $auction_config_pic['main_pic_border_color'];				
			}
			else
			{
				$album_config['grayscale'] = 0;
				$album_config['thumb_border'] = 0;
				$album_config['border_color'] = '000000';
			}

		// sharpen routine
		if ( $auction_config_pic['gd_version'] > 1)
		{
			$album_config['sharpen'] = 1;
			if($pic_type == 3) // its mini
			{
				if ($auction_config_pic['mini_pic_sharpen'] == 1) //sharpen
				{
					$album_config['sharpen_amount'] = 120;
					$album_config['sharpen_radius'] = 0.5;
					$album_config['sharpen_threshold'] = 3;	
				}
				else if ($auction_config_pic['mini_pic_sharpen'] == 2) //sharpen more
				{
					$album_config['sharpen_amount'] = 150;
					$album_config['sharpen_radius'] = 0.75;
					$album_config['sharpen_threshold'] = 4;	
				}
				else if ($auction_config_pic['mini_pic_sharpen'] == 3) // blur
				{
					$album_config['sharpen_amount'] = -80;
					$album_config['sharpen_radius'] = -0.3;
					$album_config['sharpen_threshold'] = -1.5;	
				}
				else if ($auction_config_pic['mini_pic_sharpen'] == 4) // blur more
				{
					$album_config['sharpen_amount'] = -160;
					$album_config['sharpen_radius'] = -0.5;
					$album_config['sharpen_threshold'] = -3;
				}
				else
				{
					$album_config['sharpen'] = 0;
				}

			}
			else if ($pic_type == 2) // its a thumbnail
			{
				if ($auction_config_pic['thumb_pic_sharpen'] == 1) //sharpen
				{
					$album_config['sharpen_amount'] = 120;
					$album_config['sharpen_radius'] = 0.5;
					$album_config['sharpen_threshold'] = 3;	
				}
				else if ($auction_config_pic['thumb_pic_sharpen'] == 2) //sharpen more
				{
					$album_config['sharpen_amount'] = 150;
					$album_config['sharpen_radius'] = 0.75;
					$album_config['sharpen_threshold'] = 4;	
				}
				else if ($auction_config_pic['thumb_pic_sharpen'] == 3) // blur
				{
					$album_config['sharpen_amount'] = -80;
					$album_config['sharpen_radius'] = -0.3;
					$album_config['sharpen_threshold'] = -1.5;	
				}
				else if ($auction_config_pic['thumb_pic_sharpen'] == 4) // blur more
				{
					$album_config['sharpen_amount'] = -160;
					$album_config['sharpen_radius'] = -0.5;
					$album_config['sharpen_threshold'] = -3;
				}
				else
				{
					$album_config['sharpen'] = 0;
				}			

			}
			else if ($pic_type == 1) // its the main-offer picture
			{
				if ($auction_config_pic['main_pic_sharpen'] == 1) //sharpen
				{
					$album_config['sharpen_amount'] = 120;
					$album_config['sharpen_radius'] = 0.5;
					$album_config['sharpen_threshold'] = 3;	
				}
				else if ($auction_config_pic['main_pic_sharpen'] == 2) //sharpen more
				{
					$album_config['sharpen_amount'] = 150;
					$album_config['sharpen_radius'] = 0.75;
					$album_config['sharpen_threshold'] = 4;	
				}
				else if ($auction_config_pic['main_pic_sharpen'] == 3) // blur
				{
					$album_config['sharpen_amount'] = -80;
					$album_config['sharpen_radius'] = -0.3;
					$album_config['sharpen_threshold'] = -1.5;	
				}
				else if ($auction_config_pic['main_pic_sharpen'] == 4) // blur more
				{
					$album_config['sharpen_amount'] = -160;
					$album_config['sharpen_radius'] = -0.5;
					$album_config['sharpen_threshold'] = -3;
				}
				else
				{
					$album_config['sharpen'] = 0;
				}			
			}
			else
			{
				$album_config['sharpen'] = 0;
			}
		}
		else
		{
			$album_config['sharpen'] = 0;
		}

	
		// touch the next three parameters only if you know what your doing	
		// if you want to blurr the thumbnail set to minus...


               // check for pic-type 1 = main picture - 2 = thumbnail - 3 = mini icon

			if( $pic_type == 1) // its the main pic
			{
				$album_config['thumbnail_size'] = $auction_config_pic['auction_offer_main_size'];
				$album_config['thumb_type'] =  0;
			}
			else if ( $pic_type == 2) // its a normal thumb
			{
				$album_config['thumbnail_size'] = $auction_config_pic['auction_offer_thumb_size'];
				$album_config['thumb_type'] =   $auction_config_pic['thumb_pic_type'];
			}
			else if ( $pic_type == 3) // its mini
			{
				$album_config['thumbnail_size'] = $auction_config_pic['auction_offer_mini_size'];
				$album_config['thumb_type'] =  1;
			}
			else
			{
				// we should never get here.. but if someone plays around.. send him the mini
				$album_config['thumbnail_size'] = $auction_config_pic['auction_offer_mini_size'];
				$album_config['thumb_type'] =  1;
			}





		function ImageColorAllocateHEX($im,$s){
		   if ($s[0]=="#") $s=substr($s,1);
		   $bg_dec=hexdec($s);
		   return imagecolorallocate($im,
					   ($bg_dec & 0xFF0000) >> 16,
					   ($bg_dec & 0x00FF00) >>  8,
					   ($bg_dec & 0x0000FF)
					   );
		}

		function UnsharpMask($img, $amount="80", $radius="0.5", $threshold="3")	{

		////////////////////////////////////////////////////////////////////////////////////////////////
		////
		////                  p h p U n s h a r p M a s k
		////
		////	Unsharp mask algorithm by Torstein Hønsi 2003.
		////	         thoensi_at_netcom_dot_no.
		////	           Please leave this notice.
		////
		///////////////////////////////////////////////////////////////////////////////////////////////

		/* 

		WARNING! Due to a known bug in PHP 4.3.2 this script is not working well in this version. The sharpened images get too dark. The bug is fixed in version 4.3.3.


		Unsharp masking is a traditional darkroom technique that has proven very suitable for 
		digital imaging. The principle of unsharp masking is to create a blurred copy of the image
		and compare it to the underlying original. The difference in colour values
		between the two images is greatest for the pixels near sharp edges. When this 
		difference is subtracted from the original image, the edges will be
		accentuated. 
		100,
		The Amount parameter simply says how much of the effect you want. 100 is 'normal'.
		Radius is the radius of the blurring circle of the mask. 'Threshold' is the least
		difference in colour values that is allowed between the original and the mask. In practice
		this means that low-contrast areas of the picture are left unrendered whereas edges
		are treated normally. This is good for pictures of e.g. skin or blue skies.

		Any suggenstions for improvement of the algorithm, expecially regarding the speed
		and the roundoff errors in the Gaussian blur process, are welcome.

		*/

			// $img is an image that is already created within php using
			// imgcreatetruecolor. No url! $img must be a truecolor image.

			// Attempt to calibrate the parameters to Photoshop:

	// $img is an image that is already created within php using
	// imgcreatetruecolor. No url! $img must be a truecolor image.

	// Attempt to calibrate the parameters to Photoshop:
	if ($amount > 500)	$amount = 500;
	$amount = $amount * 0.016;
	if ($radius > 50)	$radius = 50;
	$radius = $radius * 2;
	if ($threshold > 255)	$threshold = 255;
	
	$radius = abs(round($radius)); 	// Only integers make sense.
	if ($radius == 0) {
		return $img; imagedestroy($img); break;		}
	$w = imagesx($img); $h = imagesy($img);
	$imgCanvas = imagecreatetruecolor($w, $h);
	$imgCanvas2 = imagecreatetruecolor($w, $h);
	$imgBlur = imagecreatetruecolor($w, $h);
	$imgBlur2 = imagecreatetruecolor($w, $h);
	imagecopy ($imgCanvas, $img, 0, 0, 0, 0, $w, $h);
	imagecopy ($imgCanvas2, $img, 0, 0, 0, 0, $w, $h);
	

	// Gaussian blur matrix:
	//						
	//	1	2	1		
	//	2	4	2		
	//	1	2	1		
	//						
	//////////////////////////////////////////////////

	// Move copies of the image around one pixel at the time and merge them with weight
	// according to the matrix. The same matrix is simply repeated for higher radii.
	for ($i = 0; $i < $radius; $i++)	{
		imagecopy ($imgBlur, $imgCanvas, 0, 0, 1, 1, $w - 1, $h - 1); // up left
		imagecopymerge ($imgBlur, $imgCanvas, 1, 1, 0, 0, $w, $h, 50); // down right
		imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 1, 0, $w - 1, $h, 33.33333); // down left
		imagecopymerge ($imgBlur, $imgCanvas, 1, 0, 0, 1, $w, $h - 1, 25); // up right
		imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 1, 0, $w - 1, $h, 33.33333); // left
		imagecopymerge ($imgBlur, $imgCanvas, 1, 0, 0, 0, $w, $h, 25); // right
		imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 1, $w, $h - 1, 20 ); // up
		imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 0, 0, $w, $h, 16.666667); // down
		imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 0, $w, $h, 50); // center
		imagecopy ($imgCanvas, $imgBlur, 0, 0, 0, 0, $w, $h);

		// During the loop above the blurred copy darkens, possibly due to a roundoff
		// error. Therefore the sharp picture has to go through the same loop to 
		// produce a similar image for comparison. This is not a good thing, as processing
		// time increases heavily.
		imagecopy ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h);
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 50);
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 33.33333);
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 25);
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 33.33333);
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 25);
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 20 );
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 16.666667);
		imagecopymerge ($imgBlur2, $imgCanvas2, 0, 0, 0, 0, $w, $h, 50);
		imagecopy ($imgCanvas2, $imgBlur2, 0, 0, 0, 0, $w, $h);
		
		}

	// Calculate the difference between the blurred pixels and the original
	// and set the pixels
	for ($x = 0; $x < $w; $x++)	{ // each row
		for ($y = 0; $y < $h; $y++)	{ // each pixel
				
			$rgbOrig = ImageColorAt($imgCanvas2, $x, $y);
			$rOrig = (($rgbOrig >> 16) & 0xFF);
			$gOrig = (($rgbOrig >> 8) & 0xFF);
			$bOrig = ($rgbOrig & 0xFF);
			
			$rgbBlur = ImageColorAt($imgCanvas, $x, $y);
			
			$rBlur = (($rgbBlur >> 16) & 0xFF);
			$gBlur = (($rgbBlur >> 8) & 0xFF);
			$bBlur = ($rgbBlur & 0xFF);
			
			// When the masked pixels differ less from the original
			// than the threshold specifies, they are set to their original value.
			$rNew = (abs($rOrig - $rBlur) >= $threshold) 
				? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig)) 
				: $rOrig;
			$gNew = (abs($gOrig - $gBlur) >= $threshold) 
				? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig)) 
				: $gOrig;
			$bNew = (abs($bOrig - $bBlur) >= $threshold) 
				? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig)) 
				: $bOrig;
			
			
						
			if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew)) {
    				$pixCol = ImageColorAllocate($img, $rNew, $gNew, $bNew);
    				ImageSetPixel($img, $x, $y, $pixCol);
				}
			}
		}

	imagedestroy($imgCanvas);
	imagedestroy($imgCanvas2);
	imagedestroy($imgBlur);
	imagedestroy($imgBlur2);
	
	return $img;

			imagedestroy($img);
}

		$auto_crop = $album_config['auto_crop'];
		
		// crop id is set here by the http_get_vars later with post.. in the upload and modify script
		// $crop_id = 2;

		if($HTTP_GET_VARS['recrop'] == 1)
		{
			if($album_config['crop_display'] == 1)
			{
				if( isset($HTTP_GET_VARS['crop']) )
				{
					$crop_id = intval($HTTP_GET_VARS['crop']);
					$auto_crop = 0;
				}
				else if( isset($HTTP_POST_VARS['crop']) )
				{
					$crop_id = intval($HTTP_POST_VARS['crop']);
					$auto_crop = 0;
				}
				else
				{
					die('No crop id specified');
				}
			}
		}
		else
		{
			$crop_id = $crop_id;
			if( (isset($crop_id)) and ($crop_id <> 0) and ($crop_id != "") )
			{
				$auto_crop = 0;
			}
			
		}
		
		$sepia = $album_config['sepia'];
		$color_shift = $album_config['color_shift'];
		
		$grayscale = $album_config['grayscale'];
		$raster = $album_config['raster'];
		$colorize = $album_config['colorize'];
		$colorize_percent= $album_config['colorize_percent'];


		$border = ($album_config['thumb_border'] == 1) ? 1 : 0;
		
		$sharpen = $album_config['sharpen'];
		
		
			
		if ($pic_width > $pic_height)
		{
			$landscape = 1;
			if($album_config['thumb_type'] == 1)
			{
				$thumbnail_width = $album_config['thumbnail_size'];
				$thumbnail_height = $album_config['thumbnail_size'];
			}
			elseif($album_config['thumb_type'] == 2)
			{
				$thumbnail_width = $album_config['thumbnail_size'];
				$thumbnail_height = (($album_config['thumbnail_size']*2)/3);
			}
			elseif ($album_config['thumb_type'] ==  3)
			{
				$thumbnail_height = $album_config['thumbnail_size'];
				$thumbnail_width = $album_config['thumbnail_size'] * ($pic_width/$pic_height);

			}
			elseif ($album_config['thumb_type'] ==  4)
			{
				$thumbnail_width = $album_config['thumbnail_size'];
				$thumbnail_height = $album_config['thumbnail_size'] * ($pic_height/$pic_width);
			}
			else
			{
				$thumbnail_width = $album_config['thumbnail_size'];
				$thumbnail_height = $album_config['thumbnail_size'] * ($pic_height/$pic_width);
			}

		}
		else
		{	
			$landscape = 0;
			if($album_config['thumb_type'] == 1)
			{
				$thumbnail_height = $album_config['thumbnail_size'];
				$thumbnail_width = $album_config['thumbnail_size'];
			}
			elseif($album_config['thumb_type'] == 2)
			{
				$thumbnail_height = $album_config['thumbnail_size'];
				$thumbnail_width = (($album_config['thumbnail_size']*2)/3);
			}
			elseif ($album_config['thumb_type'] ==  3)
			{
				$thumbnail_height = $album_config['thumbnail_size'];
				$thumbnail_width = $album_config['thumbnail_size'] * ($pic_width/$pic_height);
			}
			elseif ($album_config['thumb_type'] ==  4)
			{
				$thumbnail_width = $album_config['thumbnail_size'];
				$thumbnail_height = $album_config['thumbnail_size'] * ($pic_height/$pic_width);
			}
			else
			{
				$thumbnail_height = $album_config['thumbnail_size'];
				$thumbnail_width = $album_config['thumbnail_size'] * ($pic_width/$pic_height);
			}

		}
// autocrop on if no crop_id set
		if((!$crop_id) or (!isset($crop_id)) or ($crop_id == "") or ($crop_id == 0))
		{
			$auto_crop = 1;
		}

// print $auto_crop.$crop_id; exit;

		if($auto_crop == 1)
		{  
				/* we center on the landscape and top on the portrait */
				if($landscape == 1)
				{
					$crop_id = 0;
					if($album_config['thumb_type'] == 1)
					{
						$pseudo_pic_width = $pic_height;
						$pseudo_pic_height = $pic_height;
						$a = 0;
						$b = 0;
						$x = (int)(($pic_width-$pseudo_pic_height)/2);
						$y = 0;
						$crop_id = 2;
					}
					elseif($album_config['thumb_type'] == 2)
					{
						// for fixed size thumbs.. autocrop is on...(int)(($pic_height-$pic_width)/2)
						if($pic_width > (int)($thumbnail_width*$pic_height/$thumbnail_height))
						{
							$pseudo_pic_width = (int)($thumbnail_width*$pic_height/$thumbnail_height);
							$pseudo_pic_height = $pic_height;
							$x=(int)($thumbnail_width-$thumbnail_height/2);
							$y = 0;
						}
						else
						{
							$pseudo_pic_width = $pic_width;
							$pseudo_pic_height = (int)($thumbnail_height*$pic_width/$thumbnail_width);
							$x=0;
							$y = (int)($thumbnail_height-$thumbnail_width/2);
						}
						$a = 0;
						$b = 0;
					}
					else
					{
						$pseudo_pic_width =  $pic_width;
						$pseudo_pic_height = $pic_height;
						$x = 0;
						$y = 0;
						$a = 0;
						$b = 0;
					}

				}
				else
				{
					$crop_id = 0;
					if($album_config['thumb_type'] == 1)
					{
						$pseudo_pic_width = $pic_width;
						$pseudo_pic_height = $pic_width;
						$a = 0;
						$b = 0;
						$x = 0;
						$y = 0;
						$crop_id = 1;
					}
					elseif($album_config['thumb_type'] == 2)
					{
						if($pic_height > (int)($thumbnail_height*$pic_width/$thumbnail_width))
						{
							$pseudo_pic_width = $pic_width;
							$pseudo_pic_height = (int)($thumbnail_height*$pic_width/$thumbnail_width);

							$x=0;
							$y = (int)(($pic_height-$pseudo_pic_width)/2);
						}
						else
						{
							$pseudo_pic_height = $pic_height;
							$pseudo_pic_width = (int)($thumbnail_width*$pic_height/$thumbnail_height);
							$x=(int)(($pseudo_pic_height-$pseudo_pic_width/2));
							$x=0;
							$y =0; 
						}
						$a = 0;
						$b = 0;
					}
					else
					{
						$pseudo_pic_width = $pic_width;
						$pseudo_pic_height = $pic_height;
						$a = 0;
						$b = 0;
						$x = 0;
						$y = 0;
					}
				}
			
		}
		else
		{
			/* we use the manual crop_id there are 3 possibilities for each portrait and landscape
			   portrait: top, center and bottom. For landscape its left center and right*/
				if($landscape == 1)
				{
					if($album_config['thumb_type'] == 1)
					{
						$crop_id = ($crop_id > 3) ? 2 : $crop_id;
						$crop_id = ($crop_id < 1) ? 2 : $crop_id;
						// for square thumbs.. autocrop is off...
						$pseudo_pic_width = $pic_height;
						$pseudo_pic_height = $pic_height;
						$a = 0;
						$b = 0;
						$x = ($crop_id == 1) ? 0 : (($crop_id == 2) ? (int)(($pic_width-$pic_height)/2) : ($pic_width-$pic_height));
						$y = 0;
					}
					elseif($album_config['thumb_type'] == 2)
					{
						// for fixed size thumbs.. autocrop is on...(int)(($pic_height-$pic_width)/2)
						if($pic_width > (int)($thumbnail_width*$pic_height/$thumbnail_height))
						{
							$pseudo_pic_width = (int)($thumbnail_width*$pic_height/$thumbnail_height);
							$pseudo_pic_height = $pic_height;

							$x=(int)($thumbnail_width-$thumbnail_height/2);
							$y = 0;
						}
						else
						{
							$pseudo_pic_width = $pic_width;
							$pseudo_pic_height = (int)($thumbnail_height*$pic_width/$thumbnail_width);
							$x=0;
							$y = (int)($thumbnail_height-$thumbnail_width/2);
						}
						$a = 0;
						$b = 0;
					}
					else
					{
						$pseudo_pic_width =  $pic_width;
						$pseudo_pic_height = $pic_height;
						$x = 0;
						$y = 0;
						$a = 0;
						$b = 0;
					}
				}
				else
				{
					if($album_config['thumb_type'] == 1)
					{
						// for square thumbs.. autocrop is off...
						$pseudo_pic_width = $pic_width;
						$pseudo_pic_height = $pic_width;
						$a = 0;
						$b = 0;
						$x = 0;
						$y = ($crop_id == 1) ? 0 : (($crop_id == 2) ? (int)(($pic_height-$pic_width)/2) : ($pic_height-$pic_width));
					}
					elseif($album_config['thumb_type'] == 2)
					{
						// for fixed size thumbs.. autocrop is on...(int)(($pic_height-$pic_width)/2)
						if($pic_height > (int)($thumbnail_height*$pic_width/$thumbnail_width))
						{
							$pseudo_pic_width = $pic_width;
							$pseudo_pic_height = (int)($thumbnail_height*$pic_width/$thumbnail_width);

							$x=0;
							$y = (int)(($pic_height-$pseudo_pic_width)/2);
						}
						else
						{
							$pseudo_pic_height = $pic_height;
							$pseudo_pic_width = (int)($thumbnail_width*$pic_height/$thumbnail_height);
							$x=(int)(($pseudo_pic_height-$pseudo_pic_width)/2);
							$y =0; 
						}
						$a = 0;
						$b = 0;
					}
					else
					{
						// for normal thumbs.. autocrop is off...
						$pseudo_pic_width = $pic_width;
						$pseudo_pic_height = $pic_height;
						$a = 0;
						$b = 0;
						$x = 0;
						$y = 0;
					}
				}
		
		}

		$thumbnail = ($album_config['gd_version'] == 1) ? @imagecreate($thumbnail_width, $thumbnail_height) : @imagecreatetruecolor($thumbnail_width, $thumbnail_height);
		$resize_function = ($album_config['gd_version'] == 1) ? 'imagecopyresized' : 'imagecopyresampled';
		@$resize_function($thumbnail, $src, $a, $b, $x, $y, $thumbnail_width, $thumbnail_height, $pseudo_pic_width, $pseudo_pic_height);


		if ($sharpen == 1)
		{
			$amount = $album_config['sharpen_amount'];
			$radius = $album_config['sharpen_radius'];
			$threshold = $album_config['sharpen_threshold'];
			$thumbnail = UnsharpMask($thumbnail, $amount, $radius, $threshold);
		}

		if ($raster == 1)
		{
			if ($album_config['raster_line_color'] == "") 
			{ 
				$album_config['raster_line_color'] == "000000";
			}
			$col = $album_config['raster_line_color'];
			$col = ImageColorAllocateHEX($thumbnail,$col);
			for ($i=0; $i<$thumbnail_height+1; $i++) 
                             {
				ImageLine($thumbnail,1,$i,$thumbnail_width-1,$i,$col); $i++; 
			     }
			
		}

		if($colorize == 1)
  		{
			$border_value = 0 ;
			$border2_value =  0 ;
			if ((!isset($album_config['colorize_color'])) OR (strlen($album_config['colorize_color']) < 6)  OR ($album_config['colorize_color'] == ""))
			{ 
				$album_config['colorize_color'] = "EEEEEE";
			}
			$the_color = $album_config['colorize_color'];
			$layover = ($album_config['gd_version'] == 1) ? @imagecreate($thumbnail_width, $thumbnail_height) : @imagecreatetruecolor($thumbnail_width, $thumbnail_height);
			$colorHandle = ImageColorAllocateHEX($layover,$the_color);  
			imageFilledRectangle($layover, $border2_value, $border2_value, $thumbnail_width - $border_value, $thumbnail_height - $border_value, $colorHandle);
			ImageCopyMerge($thumbnail,$layover,0,0,0,0,$thumbnail_width,$thumbnail_height,$colorize_percent); 
			imagedestroy($layover); // Destroy the layover 
		}

		if ($sepia == 1)
		{
			$grayscale = 1;
		}
		if($grayscale == 1)
		{
			for ($y = 0; $y <$thumbnail_height; $y++)
			{ 
				for ($x = 0; $x <$thumbnail_width; $x++)
				{ 
					$red = (ImageColorAt($thumbnail, $x, $y) >> 16) & 0xFF;
					$green = (ImageColorAt($thumbnail, $x, $y) >> 8) & 0xFF;
					$blue =ImageColorAt($thumbnail, $x, $y) & 0xFF;
					$grey = (int)(($red+$green+$blue)/3);
					imagesetpixel ($thumbnail, $x, $y, ImageColorAllocate ($thumbnail, $grey,$grey,$grey));
				}
			}
		}
	
	
		if($sepia == 1)
		{
			for ($y = 0; $y <$thumbnail_height; $y++)
			{ 
				for ($x = 0; $x <$thumbnail_width; $x++)
				{ 
					$red = (ImageColorAt($thumbnail, $x, $y) >> 16) & 0xFF;
					$red = $red * .629;
					$green = (ImageColorAt($thumbnail, $x, $y) >> 8) & 0xFF;
						$green = $green * .529;
					$blue =ImageColorAt($thumbnail, $x, $y) & 0xFF;
						$blue = $blue * .214;
					$grey = (int)(($red+$green+$blue)/3);
					$sepia = (int)((($red * .529) + ($green * .587) + ($blue * .114) + 3)/3);
					imagesetpixel ($thumbnail, $x, $y, ImageColorAllocate ($thumbnail,$red,$green,$blue));
				}
			}
		}

		
		if($color_shift == 1)
		{
			for ($y = 0; $y <$thumbnail_height; $y++)
			{ 
				for ($x = 0; $x <$thumbnail_width; $x++)
				{ 
					$red = (ImageColorAt($thumbnail, $x, $y) >> 16) & 0xFF;
					$green = (ImageColorAt($thumbnail, $x, $y) >> 8) & 0xFF;
					$blue =ImageColorAt($thumbnail, $x, $y) & 0xFF;
					$grey = (int)(($red+$green+$blue)/3);
					$shift_red = $album_config['color_shift_red'];
					$shift_green = $album_config['color_shift_green'];
					$shift_blue = $album_config['color_shift_blue'];

					$color_1 = ($shift_red == 0) ? $grey : (($shift_red == 0) ? $red :(($shift_red == 1) ? $green : $blue));
					$color_2 = ($shift_green == 0) ? $grey : (($shift_green == 0) ? $red :(($shift_green == 1) ? $green : $blue));
					$color_3 = ($shift_blue == 0) ? $grey : (($shift_blue == 0) ? $red :(($shift_blue == 1) ? $green : $blue));

					imagesetpixel ($thumbnail, $x, $y, ImageColorAllocate ($thumbnail, $color_1,$color_2,$color_3));
				}
			}
		}


////// sharpen & border here after all the work is done


// we set the border after sharpen, so the border wont be sharpened
		if($border == 1)
		{
		
			if ($album_config['border_color'] == "") 
			{ 
				$album_config['border_color'] == "000000";
			}
			$bordercolor = $album_config['border_color'];
			$col = ImageColorAllocateHEX($thumbnail,$bordercolor); 
            ImageRectangle($thumbnail,0,0,$thumbnail_width-1,$thumbnail_height-1,$col); 
        } 
?>