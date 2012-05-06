<?
/***************************************************************************
 *                          ads_renewal_mailer.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_renewal_mailer.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

$filename = ADS_CHASERS_PATH .date('dMy').'.txt';

$file = fopen($filename, 'w');
if ( !$file ) 
{
	message_die(GENERAL_ERROR, 'Error creating chaser log file');
}

// Get all active ads
$sql = "SELECT * 
		FROM ". ADS_ADVERTS_TABLE ." 
		WHERE status = 'active'
		AND USER_ID <>	 ". ADS_GUEST;

$result = $db->sql_query($sql);

if ( $db->sql_numrows($result) > 0 )
{
	// Seed the random number generator
	srand((double) microtime() * 1000000);

	while ($row = $db->sql_fetchrow($result)) 
	{
		$id = $row['id'];
		$username = $row['username'];
		$expiry_date = $row['expiry_date'];
		$expiry_date2 = date($lang['DATE_FORMAT'],$row['expiry_date']);
		$status = $row['status'];

		$sql = "SELECT * 
				FROM ". ADS_CHASERS_TABLE ."
				WHERE id = '$id'";

		$result2 = $db->sql_query($sql);
		$row2 = $db->sql_fetchrow($result2);

		if ( !$row2 )
		{
			// Is first chase due?
			if ( time() >= ($expiry_date - ($ads_config['first_chase_days']*60*60*24)) )
			{
				// Get recipients details from phpBB
				$profiledata = get_userdata($username,'true'); 
				$recip_email = $profiledata['user_email']; 

				// Renewal passwords only for free ads!
				if ( $ads_config['paid_ads'] == 0 )
				{
					$renewal_password = rand(0,999999999);
					$renewal_password_link = "&renewal_password=$renewal_password";
				}
				else
				{
					$renewal_password = 0;
				}

				// Populate the email fields
				$renewal_url = 'http://'.$board_config['server_name'].$board_config['script_path'].'ads_item_renewal.'.$phpEx.'?id='.$id.$renewal_password_link;

				$message = str_replace('%exp%', $expiry_date2, $lang['first_renewal_message'])."\r\n\r\n";
				$message = $message.$renewal_url."\r\n\r\n";
				$message = $message.$board_config['board_email_sig'];

				// Send first chase
				chaser_email($board_config['board_email'], $recip_email, $lang['first_renewal_title'], $message);

				// Add a row to the chasers table
				$last_chase_type = '1';

				$sql = "INSERT INTO ". ADS_CHASERS_TABLE . "
						VALUES ($id, '$last_chase_type', $renewal_password)";

				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not insert chasers row', '', __LINE__, __FILE__, $sql);
				}

				// Create a record on the log file
				$record = $username.";".$expiry_date2.";".$lang['first_renewal_title']."\r\n";
				fwrite ($file, $record);
			}
		}
		else
		{
			// Is second chase due?
			if ( $row2['last_chase_type'] == '1' 
			and time() >= ($expiry_date - ($ads_config['second_chase_days']*60*60*24)) )
			{
				// Get recipients details from phpBB
				$profiledata = get_userdata($username,'true'); 
				$recip_email = $profiledata['user_email']; 

				// Renewal passwords only for free ads!
				if ( $ads_config['paid_ads'] == 0 )
				{
					$renewal_password = rand(0,999999999);
					$renewal_password_link = "&renewal_password=$renewal_password";
				}
				else
				{
					$renewal_password = 0;
				}

				// Populate the email fields
				$renewal_url = 'http://'.$board_config['server_name'].$board_config['script_path'].'ads_item_renewal.'.$phpEx.'?id='.$id.$renewal_password_link;

				$message = str_replace('%exp%', $expiry_date2, $lang['second_renewal_message'])."\r\n\r\n";
				$message = $message.$renewal_url."\r\n\r\n";
				$message = $message.$board_config['board_email_sig'];

				// Send second chase
				chaser_email($board_config['board_email'], $recip_email, $lang['second_renewal_title'], $message);

				// Update the chasers table
				$last_chase_type = '2';

				$sql = "UPDATE ". ADS_CHASERS_TABLE ."
						SET last_chase_type = '$last_chase_type' 
						WHERE id = $id";

				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update chasers data', '', __LINE__, __FILE__, $sql);
				}

				// Create a record on the log file
				$record = $username.";".$expiry_date2.";".$lang['second_renewal_title']."\r\n";
				fwrite ($file, $record);
			}
		
			// Is final chase due?
			if ( $row2['last_chase_type'] == '2' and time() >= $expiry_date )
			{
				// Get recipients details from phpBB
				$profiledata = get_userdata($username,'true'); 
				$recip_email = $profiledata['user_email']; 

				// Renewal passwords only for free ads!
				if ( $ads_config['paid_ads'] == 0 )
				{
					$renewal_password = rand(0,999999999);
					$renewal_password_link = "&renewal_password=$renewal_password";
				}
				else
				{
					$renewal_password = 0;
				}

				// Populate the email fields
				$renewal_url = 'http://'.$board_config['server_name'].$board_config['script_path'].'ads_item_renewal.'.$phpEx.'?id='.$id.$renewal_password_link;

				$message = str_replace('%exp%', $expiry_date2, $lang['final_renewal_message'])."\r\n\r\n";
				$message = $message.$renewal_url."\r\n\r\n";
				$message = $message.$board_config['board_email_sig'];

				// Send second chase
				chaser_email($board_config['board_email'], $recip_email, $lang['final_renewal_title'], $message);

				// Update the chasers table
				$last_chase_type = 'F';

				$sql = "UPDATE ". ADS_CHASERS_TABLE ."
						SET last_chase_type = '$last_chase_type' 
						WHERE id = $id";

				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update chasers data', '', __LINE__, __FILE__, $sql);
				}

				// Create a record on the log file
				$record = $username.";".$expiry_date2.";".$lang['final_renewal_title']."\r\n";
				fwrite ($file, $record);

				// Expire the ad if active
				$status = 'expired';

				$sql = "UPDATE ". ADS_ADVERTS_TABLE ."
						SET status = '$status' 
						WHERE id = $id";

				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update chasers data', '', __LINE__, __FILE__, $sql);
				}

				// Delete the images
				$sql = "SELECT * 
						FROM ". ADS_IMAGES_TABLE ."
						WHERE id = $id
						AND img_deleted_ind = 0";

				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result)) 
				{
					$img_seq_no = $row['img_seq_no'];

					$sql2 = "UPDATE ". ADS_IMAGES_TABLE ."
							SET img_deleted_ind = 1
							WHERE id = '$id' 
							AND img_seq_no = '$img_seq_no'";

					if ( !$result2 = $db->sql_query($sql2) )
					{
						message_die(GENERAL_ERROR, 'Could not update this image', '', __LINE__, __FILE__, $sql2);
					}

					$filename = ADS_IMAGES_PATH .'ad'.$id.'_img'.$img_seq_no.'_thumb.jpg';
					unlink($filename);

					$filename = ADS_IMAGES_PATH .'ad'.$id.'_img'.$img_seq_no.'_medium.jpg';
					unlink($filename);

					$filename = ADS_IMAGES_PATH .'ad'.$id.'_img'.$img_seq_no.'_large.jpg';
					unlink($filename);
				}
			}
		}
	}
}

$record = $lang['successful_completion']."\r\n";
fwrite ($file, $record);

fclose ($file); 
?>