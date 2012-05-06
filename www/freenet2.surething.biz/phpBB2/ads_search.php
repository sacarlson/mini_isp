<?
/***************************************************************************
 *                              ads_search.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: ads_search.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

$ads_root_path = $phpbb_root_path . 'ads_mod/';
include($ads_root_path . 'ads_common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

// Check the permissions
if ( search_allowed($ads_config['search_level']) == FALSE )
{
	if ( !$userdata['session_logged_in'] ) 
	{
		redirect(append_sid("login.$phpEx?redirect=ads_search.$phpEx"));
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Not_Authorised']);
	}
}

if ( !isset($HTTP_POST_VARS['submit'] ) and !isset($HTTP_GET_VARS['search_name']) )
{
	// Search form

	$page_title = $lang['Search'];

	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array('search_page' => 'ads_search.tpl')); 

	$sql = 'SELECT * 
			FROM '. ADS_CATEGORIES_TABLE .'
			ORDER BY cat_category, cat_sub_category ASC';

	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) 
	{
		$option = $row['cat_category'].'>>'.$row['cat_sub_category'];
		$template->assign_block_vars('optionlist', array('OPTION' => $option));
	}

	$template->assign_vars(array(	

		'U_ADS_INDEX' => append_sid("adverts.$phpEx"),

		'S_POST_ACTION' => append_sid("ads_search.$phpEx"),

		'L_ADS_INDEX' => $lang['ads_index'],
		'L_SEARCH_QUERY' => $lang['search_query'],
		'L_SEARCH_FOR_KEYWORDS' => $lang['search_for_keywords'],
		'L_SEARCH_FOR_ANY_TERMS' => $lang['search_for_any_terms'],
		'L_SEARCH_FOR_ALL_TERMS' => $lang['search_for_all_terms'],
		'L_SEARCH_FOR_USERNAME' => $lang['search_for_username'],
		'L_SEARCH_FOR_AD_ID' => $lang['search_for_ad_id'], 
		'L_SEARCH_OPTIONS' => $lang['search_options'],
		'L_CAT_SUB_CAT' => $lang['cat_sub_cat'],
		'L_ALL' => $lang['all'],
		'L_AD_STATUS' => $lang['ad_status'],
		'L_ACTIVE' => $lang['active'],										
		'L_SOLD' => $lang['sold'],										
		'L_EXPIRED' => $lang['expired'],										
		'L_SORT_BY' => $lang['sort_by'],

		'L_TITLE' => $lang['title'],
		'L_DATE_ADDED' => $lang['date_added'],
		'L_USERNAME' => $lang['username'],
		'L_VIEWS' => $lang['views'],

		'L_ASCENDING' => $lang['ascending'],
		'L_DESCENDING' => $lang['descending'],
		'L_SEARCH' => $lang['Search'],

		'SITE_NAME' => $board_config['sitename']));

	$template->pparse('search_page'); 

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);	
}
else
{
	// Process search
	
	// Check for a search parameter
	if ( !isset($HTTP_POST_VARS['search_term']) 
	and  !isset($HTTP_GET_VARS['search_term'])
	and  !isset($HTTP_POST_VARS['search_name']) 
	and  !isset($HTTP_GET_VARS['search_name']) 
	and  !isset($HTTP_POST_VARS['search_id']) 
	and  !isset($HTTP_GET_VARS['search_id']) ) 
	{
		message_die(GENERAL_ERROR, $lang['invalid_request']);
	}

	// Check for an empty string
	if ( ( $HTTP_POST_VARS['search_term'] == '' and $HTTP_GET_VARS['search_term'] == '' )
	and  ( $HTTP_POST_VARS['search_name'] == '' and $HTTP_GET_VARS['search_name'] == '' )
	and  ( $HTTP_POST_VARS['search_id'] == '' and $HTTP_GET_VARS['search_id'] == '' ) )
	{
		message_die(GENERAL_ERROR, $lang['no_keywords']);
	}

	// Put out header
	$page_title = $lang['Search'];

	include($phpbb_root_path . 'includes/page_header.'.$phpEx);
	
	$template->set_filenames(array('search_results' => 'ads_search_results.tpl')); 

	// Sanitize input variables
	if ( isset($HTTP_POST_VARS['search_term']) )
	{
		$search_term = htmlspecialchars($HTTP_POST_VARS['search_term']);
	}

	if ( isset($HTTP_GET_VARS['search_term']) )
	{
		$search_term = htmlspecialchars($HTTP_GET_VARS['search_term']);
	}

	if ( isset($HTTP_POST_VARS['search_name']) ) 
	{
		$search_name = htmlspecialchars($HTTP_POST_VARS['search_name']);
	}

	if ( isset($HTTP_GET_VARS['search_name']) )
	{
		$search_name = htmlspecialchars($HTTP_GET_VARS['search_name']);
	}

	if ( isset($HTTP_POST_VARS['search_id']) ) 
	{
		$search_id = intval($HTTP_POST_VARS['search_id']);
	}

	if ( isset($HTTP_GET_VARS['search_id']) )
	{
		$search_id = intval($HTTP_GET_VARS['search_id']);
	}

	if ( isset($HTTP_POST_VARS['sort_by']) )
	{
		$sort_by = htmlspecialchars($HTTP_POST_VARS['sort_by']);
	}

	if ( isset($HTTP_GET_VARS['sort_by']) )
	{
		$sort_by = htmlspecialchars($HTTP_GET_VARS['sort_by']);
	}

	if ( isset($HTTP_POST_VARS['sort_dir']) )
	{
		$sort_dir = htmlspecialchars($HTTP_POST_VARS['sort_dir']);
	}

	if ( isset($HTTP_GET_VARS['sort_dir']) )
	{
		$sort_dir = htmlspecialchars($HTTP_GET_VARS['sort_dir']);
	}

	if ( isset($HTTP_POST_VARS['status']) )
	{
		$status = htmlspecialchars($HTTP_POST_VARS['status']);
	}

	if ( isset($HTTP_GET_VARS['status']) )
	{
		$status = htmlspecialchars($HTTP_GET_VARS['status']);
	}

	if ( $HTTP_POST_VARS['cat_sub_cat'] )
	{
		$cat_sub_cat = htmlspecialchars($HTTP_POST_VARS['cat_sub_cat']);

		list ($category, $sub_category) = split('>>', $HTTP_POST_VARS['cat_sub_cat']);
		$category = htmlspecialchars($category);
	 	$sub_category = htmlspecialchars($sub_category);
	}

	if ( $HTTP_GET_VARS['cat_sub_cat'] )
	{
		$cat_sub_cat = htmlspecialchars($HTTP_GET_VARS['cat_sub_cat']);

		list ($category, $sub_category) = split('>>', $HTTP_GET_VARS['cat_sub_cat']);
 		$category = htmlspecialchars($category);
	 	$sub_category = htmlspecialchars($sub_category);
	}

	// Extra sanitize for SQL variables
	$search_term = str_replace("\'", "''", $search_term);
	$search_name = str_replace("\'", "''", $search_name);
	$sort_by = str_replace("\'", "''", $sort_by);
	$sort_dir = str_replace("\'", "''", $sort_dir);
	$status = str_replace("\'", "''", $status);
	$category = str_replace("\'", "''", $category);
	$sub_category = str_replace("\'", "''", $sub_category);

	// Set defaults for sorting if not already set
	if ( empty($sort_by) )
	{
		$sort_by = 'title';
	}

	if ( empty($sort_dir) )
	{
		$sort_dir = 'ASC';
	}

	// Search by search term
	if ( !empty($search_term) )
	{
		// Trim whitespace from the search term
		$search_term = trim($search_term);
	
		// Separate key-phrases into keywords
		$trimmed_array = explode(' ',$search_term); 

		if ( $HTTP_POST_VARS['search_terms'] ) // This is the any/all indicator
		{
			$search_terms = htmlspecialchars($HTTP_POST_VARS['search_terms']);
		}

		if ( $HTTP_GET_VARS['search_terms'] ) // This is the any/all indicator
		{
			$search_terms = htmlspecialchars($HTTP_GET_VARS['search_terms']);
		}

		// Build query
		if ( $search_terms == 'any' )
		{
			$sql1 = "(title LIKE '%".implode('%\' OR title LIKE \'%',$trimmed_array)."%'";
			$sql2 = "short_desc LIKE '%".implode('%\' OR short_desc LIKE \'%',$trimmed_array)."%')";
		}
		else
		{
			$sql1 = "(title LIKE '%".implode('%\' AND title LIKE \'%',$trimmed_array)."%'";
			$sql2 = "short_desc LIKE '%".implode('%\' AND short_desc LIKE \'%',$trimmed_array)."%')";
		}

		$sql= "SELECT * 
				FROM ". ADS_ADVERTS_TABLE ."
				WHERE $sql1 
				OR $sql2";
	}

	// Search by user name
	if ( !empty($search_name) )
	{
		// Trim whitespace from the search name
		$search_name = trim($search_name);
		
		$sql= "SELECT * 
				FROM ". ADS_ADVERTS_TABLE ."
				WHERE username = '$search_name'";
	}

	// Search by ad id
	if ( !empty($search_id) )
	{
		$sql= "SELECT * 
				FROM ". ADS_ADVERTS_TABLE ."
				WHERE id = '$search_id'";
	}

	// Append status predicate
	if ( !empty($status) and $status != 'all' )
	{
		$sql = $sql." AND status = '".$status."'";
	}

	// Append the category/sub_category predicate
	if ( !empty($category) and $category != 'all' )
	{
		$sql = $sql." AND category = '".$category."'";
	}

	if ( !empty($sub_category) )
	{
		$sql = $sql." AND sub_category = '".$sub_category."'";
	}

	// Leave this in for diagnostics
	//echo $sql,'<br>';

	// Get the total number of ads
	$result = $db->sql_query($sql);
	$ads_count = $db->sql_numrows($result);

	if ( $ads_count == 0 )
	{
		message_die(GENERAL_ERROR, $lang['no_search_results']);
	}

	if ( $ads_count == 1 )
	{
		$matches = sprintf($lang['Found_search_match'], mysql_num_rows($result));
	}
	else if ( $ads_count > 1 )
	{
		$matches = sprintf($lang['Found_search_matches'], mysql_num_rows($result));
	}

	// Set the sort_by
	$sql = $sql." ORDER BY ".$sort_by." ".$sort_dir;

	// Set the start page
	if ( $HTTP_GET_VARS['start'] )
	{
		$start = intval($HTTP_GET_VARS['start']);
	}
	else
	{
		$start = 0;
	}

	// Leave this in for diagnostics
	//echo $sql,'<br>';

	// Now re-run the query with the limit set.
	$sql = $sql." LIMIT ".$start.", ".$ads_config['ads_per_page'];
	$result = $db->sql_query($sql);

	while ($row = $db->sql_fetchrow($result)) 
	{
		$id = $row['id'];
		$title = $row['title'];
		$user_id = $row['user_id'];
		$short_desc = $row['short_desc'];
		$time = $row['time'];
		$price = $row['price'];
		$views = $row['views'];

		$profiledata = get_userdata($user_id); 

		if ( $user_id != ANONYMOUS )
		{
			$profile = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id");
		}
		else
		{
			$profile = '';
		}

		// Check to see if an image exists for this ad

		$sql2 = "SELECT * 
				FROM ". ADS_IMAGES_TABLE ."
				WHERE id = '$id' 
				AND img_deleted_ind = 0
				LIMIT 0,1";

		$result2 = $db->sql_query($sql2);

		if ($db->sql_numrows($result2)>0)
		{
			while ($row2 = $db->sql_fetchrow($result2)) 
			{
				$img_url = ADS_IMAGES_PATH ."ad".$id."_img".$row2["img_seq_no"]."_thumb.jpg";
			}
		}		
		else
		{
			$img_url = $images['noimage'];
		} 

		if ( $row['status'] == 'sold' ) 
		{ 
			$status2 = $lang['sold']; 
		} 
			elseif ( $row['status'] == 'expired' ) 
		{ 
			$status2 = $lang['expired']; 
		} 
			elseif ( $row['status'] == 'active' ) 
		{ 
			$status2 = ''; 
		} 
		
		$template->assign_block_vars('advertrow', array(

			'U_ADS_ITEM' => append_sid("ads_item.$phpEx?id=".$row['id']),
			'U_PROFILE' => $profile,

			'ID' => $row['id'],
			'USERNAME' => $profiledata['username'],
			'TITLE' => $row['title'],
			'SHORT_DESC' => nl2br($row['short_desc']),
			'DATE_ADDED' => date($lang['DATE_FORMAT'],$time),
			'STATUS' => $status2, 
			'PRICE' => $row['price'],
			'VIEWS' => $row['views'],
			'IMAGE' => $img_url)); 
	}

	if ( $ads_count == 0 )
	{
		$ads_count = 1;
	} 

	// Encode the fields
	$u_cat_sub_cat = urlencode(stripslashes(htmlspecialchars_decode_php4($cat_sub_cat)));

	$goto_string = generate_pagination("ads_search.$phpEx?search_term=$search_term&search_terms=$search_terms&search_name=$search_name&cat_sub_cat=$u_cat_sub_cat&status=$status&sort_by=$sort_by&sort_dir=$sort_dir", $ads_count, $ads_config['ads_per_page'], $start);
	$page_string = sprintf($lang['Page_of'], ( floor( $start / $ads_config['ads_per_page'] ) + 1 ), ceil( $ads_count / $ads_config['ads_per_page'] ));

	$template->assign_vars(array(
	
		'U_ADS_INDEX' => append_sid("adverts.$phpEx"),

		'L_ADS_INDEX' => $lang['ads_index'],
		'L_CATEGORIES' => $lang['categories'],
		'L_TITLE' => $lang['title'],
		'L_SHORT_DESC' => $lang['short_desc'],
		'L_DATE_ADDED' => $lang['date_added'],
		'L_PRICE' => $lang['price'],
		'L_AD_STATUS' => $lang['ad_status'], 
		'L_USERNAME' => $lang['username'],
		'L_IMAGE' => $lang['image'],
		'L_VIEWS' => $lang['views'],

		'MATCHES' => $matches,
		'SITE_NAME' => $board_config['sitename'],
		'GOTO_STRING' => append_sid("$goto_string"),
		'PAGE_STRING' => $page_string));

	$template->pparse('search_results'); 

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);		
}	
?>