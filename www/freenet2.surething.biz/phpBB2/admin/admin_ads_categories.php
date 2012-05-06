<?
/***************************************************************************
 *                         admin_ads_categories.php
 *                            -------------------
 *   begin                : Wednesday, Feb 15, 2006
 *   copyright            : (C) 2006 Peter Mansion
 *   email                : support@phpca.net
 *
 *   $Id: admin_ads_categories.php,v0.5.0 2005/10/30 20:10:00 pmansion Exp $
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

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Classified_Ads']['Categories'] = "$file";
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
require($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_ads.' . $phpEx);

//
// Mode setting
//
if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = "";
}

if ( $HTTP_POST_VARS[cancel] )
{
	$mode = "";
}

//
// Main processing
//
switch($mode)

{
	case 'create':

		//Checks to see if the category, name, message or email fields are empty.
		if ( empty($HTTP_POST_VARS[category]) or empty($HTTP_POST_VARS[sub_category]) )
		{
			message_die(GENERAL_ERROR, $lang['create_category_instructions'], "", __LINE__, __FILE__);
		}

		// Sanitize input data
		$category = htmlspecialchars($HTTP_POST_VARS[category]);
		$sub_category = htmlspecialchars($HTTP_POST_VARS[sub_category]);

		// Extra sanitize for SQL variables
		$category = str_replace("\'", "''", $category);
		$sub_category = str_replace("\'", "''", $sub_category);

		$sql = "INSERT INTO ". ADS_CATEGORIES_TABLE ." (cat_category, cat_sub_category)
				VALUES ('$category','$sub_category')";

		if ( $db->sql_query($sql) )
		{
			$message = $lang['category_creation_conf'] . "<br /><br />" . sprintf($lang['ads_click_return_categories'], "<a href=\"" . append_sid("admin_ads_categories.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{	
			message_die(GENERAL_ERROR, "Failed to insert categories row", "", __LINE__, __FILE__, $sql);
		}

		break;

	case 'confirm':
	
		// Strip slashes
		$category = stripslashes($HTTP_GET_VARS['category']);
		$sub_category = stripslashes($HTTP_GET_VARS['sub_category']);

		// Encode the fields
		if (function_exists('get_html_translation_table'))
		{
			$category = urlencode(strtr($category, array_flip(get_html_translation_table(HTML_ENTITIES))));
			$sub_category = urlencode(strtr($sub_category, array_flip(get_html_translation_table(HTML_ENTITIES))));
		}
		else
		{
			$category = urlencode(str_replace(array('&amp;', '&#039;', '&quot;', '&lt;', '&gt;'), array('&', "'", '"', '<', '>'), $category));
			$sub_category = urlencode(str_replace(array('&amp;', '&#039;', '&quot;', '&lt;', '&gt;'), array('&', "'", '"', '<', '>'), $sub_category));		
		}

		$template->set_filenames(array('delete_cat' => 'admin/ads_category_delete.tpl')); 

		$template->assign_vars(array(

			'S_CATEGORIES_ACTION' => append_sid("admin_ads_categories.$phpEx?mode=delete&category=$category&sub_category=$sub_category"),

			'L_INFORMATION' => $lang['information'],
			'L_DELETE_QUESTION' => $lang['delete_cat_question'],
			'L_YES' => $lang['yes'],
			'L_NO' => $lang['no'],

			'CATEGORY' => $category,
			'SUB_CATEGORY' => $sub_category));

		$template->pparse('delete_cat'); 

		break;

	case 'delete':

		if ( empty($HTTP_GET_VARS[category]) )
		{
			message_die(GENERAL_ERROR, "Category code missing", "", __LINE__, __FILE__);
		}

		// Sanitize input data
		$category = htmlspecialchars($HTTP_GET_VARS['category']);
		$sub_category = htmlspecialchars($HTTP_GET_VARS['sub_category']);

		// Extra sanitize for SQL variables
		$category = str_replace("\'", "''", $category);
		$sub_category = str_replace("\'", "''", $sub_category);

		if ( $HTTP_POST_VARS[confirm] )
		{
			if ( $sub_category )
			{
				$sql = "DELETE FROM ". ADS_CATEGORIES_TABLE ." 
						WHERE cat_category = '$category' 
						AND cat_sub_category = '$sub_category'";
			}
			else
			{
				$sql = "DELETE FROM ". ADS_CATEGORIES_TABLE ." 
						WHERE cat_category = '$category'";
			}

			if ( $db->sql_query($sql) )
			{
				$message = $lang['category_deletion_conf'] . "<br /><br />" . sprintf($lang['ads_click_return_categories'], "<a href=\"" . append_sid("admin_ads_categories.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				message_die(GENERAL_ERROR, "Failed to delete categories row", "", __LINE__, __FILE__, $sql);
			}
		}
		else
		{	
			message_die(GENERAL_ERROR, "Invalid request", "", __LINE__, __FILE__);		
		}

		break;

	case 'edit':

		if ( isset($HTTP_POST_VARS['submit']) )
		{
			if ( empty($HTTP_POST_VARS[category] ) 
			or  empty($HTTP_POST_VARS[sub_category]) )
			{
				message_die(GENERAL_ERROR, "Invalid edit request", "", __LINE__, __FILE__);
			}

			if ( file_exists("admin_ads_paid_ads.$phpEx") )
			{
				$basic_cost = intval($HTTP_POST_VARS[basic_cost]);
				$standard_cost = intval($HTTP_POST_VARS[standard_cost]);
				$photo_cost = intval($HTTP_POST_VARS[photo_cost]);
				$premium_cost = intval($HTTP_POST_VARS[premium_cost]);
			}
			else
			{
				$basic_cost = '0';
				$standard_cost = '0';
				$photo_cost = '0';
				$premium_cost = '0';
			}

			$category = htmlspecialchars($HTTP_POST_VARS[category]);
			$sub_category = htmlspecialchars($HTTP_POST_VARS[sub_category]);
			$field_1_desc = htmlspecialchars($HTTP_POST_VARS[field_1_desc]);
			$field_2_desc = htmlspecialchars($HTTP_POST_VARS[field_2_desc]);
			$field_3_desc = htmlspecialchars($HTTP_POST_VARS[field_3_desc]);
			$field_4_desc = htmlspecialchars($HTTP_POST_VARS[field_4_desc]);
			$field_5_desc = htmlspecialchars($HTTP_POST_VARS[field_5_desc]);			
			$field_6_desc = htmlspecialchars($HTTP_POST_VARS[field_6_desc]);			
			$field_7_desc = htmlspecialchars($HTTP_POST_VARS[field_7_desc]);			
			$field_8_desc = htmlspecialchars($HTTP_POST_VARS[field_8_desc]);			
			$field_9_desc = htmlspecialchars($HTTP_POST_VARS[field_9_desc]);			
			$field_10_desc = htmlspecialchars($HTTP_POST_VARS[field_10_desc]);
			$cat_create_level = htmlspecialchars($HTTP_POST_VARS[cat_create_level]);
			$cat_edit_level = htmlspecialchars($HTTP_POST_VARS[cat_edit_level]);
			$cat_delete_level = htmlspecialchars($HTTP_POST_VARS[cat_delete_level]);
			$cat_image_level = htmlspecialchars($HTTP_POST_VARS[cat_image_level]);
			$cat_comment_level = htmlspecialchars($HTTP_POST_VARS[cat_comment_level]);
			$cat_rate_level = htmlspecialchars($HTTP_POST_VARS[cat_rate_level]);			

			// Extra sanitize for SQL variables
			$category = str_replace("\'", "''", $category);
			$sub_category = str_replace("\'", "''", $sub_category);
			$field_1_desc = str_replace("\'", "''", $field_1_desc);
			$field_2_desc = str_replace("\'", "''", $field_2_desc);
			$field_3_desc = str_replace("\'", "''", $field_3_desc);
			$field_4_desc = str_replace("\'", "''", $field_4_desc);
			$field_5_desc = str_replace("\'", "''", $field_5_desc);
			$field_6_desc = str_replace("\'", "''", $field_6_desc);
			$field_7_desc = str_replace("\'", "''", $field_7_desc);
			$field_8_desc = str_replace("\'", "''", $field_8_desc);
			$field_9_desc = str_replace("\'", "''", $field_9_desc);
			$field_10_desc = str_replace("\'", "''", $field_10_desc);
			$cat_create_level = str_replace("\'", "''", $cat_create_level);
			$cat_edit_level = str_replace("\'", "''", $cat_edit_level);
			$cat_delete_level = str_replace("\'", "''", $cat_delete_level);
			$cat_image_level = str_replace("\'", "''", $cat_image_level);
			$cat_comment_level = str_replace("\'", "''", $cat_comment_level);
			$cat_rate_level = str_replace("\'", "''", $cat_rate_level);
					
			$sql = "UPDATE ". ADS_CATEGORIES_TABLE ." SET 
				cat_basic_cost = $basic_cost, 
				cat_standard_cost = $standard_cost, 
				cat_photo_cost = $photo_cost, 
				cat_premium_cost = $premium_cost,
				cat_field_1_desc = '$field_1_desc',
				cat_field_2_desc = '$field_2_desc',
				cat_field_3_desc = '$field_3_desc',
				cat_field_4_desc = '$field_4_desc',
				cat_field_5_desc = '$field_5_desc',				
				cat_field_6_desc = '$field_6_desc',
				cat_field_7_desc = '$field_7_desc',
				cat_field_8_desc = '$field_8_desc',
				cat_field_9_desc = '$field_9_desc',
				cat_field_10_desc = '$field_10_desc',
				cat_create_level = '$cat_create_level',
				cat_edit_level = '$cat_edit_level',
				cat_delete_level = '$cat_delete_level',
				cat_image_level = '$cat_image_level',
				cat_comment_level = '$cat_comment_level',				
				cat_rate_level = '$cat_rate_level'				
				WHERE cat_category = '$category'
				AND cat_sub_category = '$sub_category'";

			if ( $db->sql_query($sql) )
			{
				$message = $lang['category_edit_conf'] . "<br /><br />" . sprintf($lang['ads_click_return_categories'], "<a href=\"" . append_sid("admin_ads_categories.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{	
				message_die(GENERAL_ERROR, "Failed to update categories row", "", __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			//Checks to see if the category, name, message or email fields are empty.
			if ( empty($HTTP_GET_VARS[category]) 
			or   empty($HTTP_GET_VARS[sub_category]) )
			{
				message_die(GENERAL_ERROR, "Invalid edit request", "", __LINE__, __FILE__);
			}

			// Sanitize input data
			$category = htmlspecialchars($HTTP_GET_VARS['category']);
			$sub_category = htmlspecialchars($HTTP_GET_VARS['sub_category']);

			// Extra sanitize for SQL variables
			$category = str_replace("\'", "''", $category);
			$sub_category = str_replace("\'", "''", $sub_category);

			$sql = "SELECT * 
					FROM ". ADS_CATEGORIES_TABLE ." 
					WHERE cat_category = '$category'
					AND cat_sub_category = '$sub_category'";

			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain category', '', __LINE__, __FILE__, $sql);
			}

			if ( !($row = $db->sql_fetchrow($result)) )
			{
				message_die(GENERAL_ERROR, "Cannot find category/sub-category", "", __LINE__, __FILE__, $sql);
			}

			$category = $row['cat_category'];
			$sub_category = $row['cat_sub_category'];
			$basic_cost = $row['cat_basic_cost'];
			$standard_cost = $row['cat_standard_cost'];
			$photo_cost = $row['cat_photo_cost'];
			$premium_cost = $row['cat_premium_cost'];						
			$field_1_desc = $row['cat_field_1_desc'];						
			$field_2_desc = $row['cat_field_2_desc'];						
			$field_3_desc = $row['cat_field_3_desc'];						
			$field_4_desc = $row['cat_field_4_desc'];						
			$field_5_desc = $row['cat_field_5_desc'];						
			$field_6_desc = $row['cat_field_6_desc'];						
			$field_7_desc = $row['cat_field_7_desc'];						
			$field_8_desc = $row['cat_field_8_desc'];						
			$field_9_desc = $row['cat_field_9_desc'];						
			$field_10_desc = $row['cat_field_10_desc'];						
			$cat_create_level = $row['cat_create_level'];						
			$cat_edit_level = $row['cat_edit_level'];									
			$cat_delete_level = $row['cat_delete_level'];									
			$cat_image_level = $row['cat_image_level'];						
			$cat_comment_level = $row['cat_comment_level'];						
			$cat_rate_level = $row['cat_rate_level'];						
									
			$template->set_filenames(array('edit_cat' => 'admin/ads_category_edit.tpl')); 

			if ( file_exists("admin_ads_paid_ads.$phpEx") )
			{
				$template->assign_block_vars('paid_ads_installed',array());
			}

			$template->assign_vars(array(

				'S_CATEGORIES_ACTION' => append_sid("admin_ads_categories.$phpEx?mode=edit"),

				'L_SUBMIT' => $lang['Submit'], 

				'L_ADS_EDIT_CATEGORY_TITLE' => $lang['ads_edit_category_title'],
				'L_ADS_EDIT_CATEGORY_EXPLAIN' => $lang['ads_edit_category_explain'],

				'L_ADS_EDIT_CATEGORY_SETTINGS' => $lang['ads_edit_category_settings'],

				'L_CATEGORY' => $lang['category'],
				'L_SUB_CATEGORY' => $lang['sub_category'],

				'L_BASIC_COST' => $lang['basic_cost'],
				'L_STANDARD_COST' => $lang['standard_cost'],
				'L_PHOTO_COST' => $lang['photo_cost'],
				'L_PREMIUM_COST' => $lang['premium_cost'],

				'L_PERMISSIONS' => $lang['permissions'],

				'L_CREATE_LEVEL' => $lang['create_level'],
				'L_EDIT_LEVEL' => $lang['edit_level'],
				'L_DELETE_LEVEL' => $lang['delete_level'],
				'L_IMAGE_LEVEL' => $lang['image_level'],
				'L_COMMENT_LEVEL' => $lang['comment_level'],
				'L_RATE_LEVEL' => $lang['rate_level'],

				'L_ADS_CUSTOM_FIELDS' => $lang['ads_custom_fields'],

				'L_FIELD_1_DESC' => $lang['field_1_desc'],
				'L_FIELD_2_DESC' => $lang['field_2_desc'],
				'L_FIELD_3_DESC' => $lang['field_3_desc'],
				'L_FIELD_4_DESC' => $lang['field_4_desc'],
				'L_FIELD_5_DESC' => $lang['field_5_desc'],
				'L_FIELD_6_DESC' => $lang['field_6_desc'],
				'L_FIELD_7_DESC' => $lang['field_7_desc'],
				'L_FIELD_8_DESC' => $lang['field_8_desc'],
				'L_FIELD_9_DESC' => $lang['field_9_desc'],
				'L_FIELD_10_DESC' => $lang['field_10_desc'],

				'L_GUEST' => $lang['Forum_ALL'], 
				'L_REG' => $lang['Forum_REG'], 
				'L_MOD' => $lang['Forum_MOD'], 
				'L_ADMIN' => $lang['Forum_ADMIN'],

				'CATEGORY' => $category,
				'SUB_CATEGORY' => $sub_category,

				'BASIC_COST' => $basic_cost,
				'STANDARD_COST' => $standard_cost,
				'PHOTO_COST' => $photo_cost,
				'PREMIUM_COST' => $premium_cost,
				
				'FIELD_1_DESC' => $field_1_desc,
				'FIELD_2_DESC' => $field_2_desc,
				'FIELD_3_DESC' => $field_3_desc,
				'FIELD_4_DESC' => $field_4_desc,
				'FIELD_5_DESC' => $field_5_desc,
				'FIELD_6_DESC' => $field_6_desc,
				'FIELD_7_DESC' => $field_7_desc,
				'FIELD_8_DESC' => $field_8_desc,
				'FIELD_9_DESC' => $field_9_desc,
				'FIELD_10_DESC' => $field_10_desc,

				'CREATE_ALL' => ($cat_create_level == ADS_GUEST) ? 'checked="checked"' : '',
				'CREATE_REG' => ($cat_create_level == ADS_USER) ? 'checked="checked"' : '',
				'CREATE_MOD' => ($cat_create_level == ADS_MOD) ? 'checked="checked"' : '',
				'CREATE_ADMIN' => ($cat_create_level == ADS_ADMIN) ? 'checked="checked"' : '',

				'EDIT_REG' => ($cat_edit_level == ADS_USER) ? 'checked="checked"' : '',
				'EDIT_MOD' => ($cat_edit_level == ADS_MOD) ? 'checked="checked"' : '',
				'EDIT_ADMIN' => ($cat_edit_level == ADS_ADMIN) ? 'checked="checked"' : '',

				'DELETE_REG' => ($cat_delete_level == ADS_USER) ? 'checked="checked"' : '',
				'DELETE_MOD' => ($cat_delete_level == ADS_MOD) ? 'checked="checked"' : '',
				'DELETE_ADMIN' => ($cat_delete_level == ADS_ADMIN) ? 'checked="checked"' : '',

				'IMAGE_REG' => ($cat_image_level == ADS_USER) ? 'checked="checked"' : '',
				'IMAGE_MOD' => ($cat_image_level == ADS_MOD) ? 'checked="checked"' : '',
				'IMAGE_ADMIN' => ($cat_image_level == ADS_ADMIN) ? 'checked="checked"' : '',

				'COMMENT_ALL' => ($cat_comment_level == ADS_GUEST) ? 'checked="checked"' : '',
				'COMMENT_REG' => ($cat_comment_level == ADS_USER) ? 'checked="checked"' : '',
				'COMMENT_MOD' => ($cat_comment_level == ADS_MOD) ? 'checked="checked"' : '',
				'COMMENT_ADMIN' => ($cat_comment_level == ADS_ADMIN) ? 'checked="checked"' : '',

				'RATE_ALL' => ($cat_rate_level == ADS_GUEST) ? 'checked="checked"' : '',
				'RATE_REG' => ($cat_rate_level == ADS_USER) ? 'checked="checked"' : '',
				'RATE_MOD' => ($cat_rate_level == ADS_MOD) ? 'checked="checked"' : '',
				'RATE_ADMIN' => ($cat_rate_level == ADS_ADMIN) ? 'checked="checked"' : '',
				
				'S_GUEST' => ADS_GUEST,
				'S_USER' => ADS_USER,
				'S_MOD' => ADS_MOD,
				'S_ADMIN' => ADS_ADMIN));

			$template->pparse('edit_cat'); 
		}

		break;

	default:

		$template->set_filenames(array(
			"body" => "admin/ads_categories_body.tpl")
		);

		$sql = "SELECT *
				FROM " . ADS_CATEGORIES_TABLE ."
				ORDER BY cat_category, cat_sub_category ASC";

		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result)) 
		{
			$category = $row["cat_category"];
			$sub_category = $row["cat_sub_category"];

			// Encode the fields
			if (function_exists('get_html_translation_table'))
			{
				$u_category = urlencode(strtr($category, array_flip(get_html_translation_table(HTML_ENTITIES))));
				$u_sub_category = urlencode(strtr($sub_category, array_flip(get_html_translation_table(HTML_ENTITIES))));
			}
			else
			{
				$u_category = urlencode(str_replace(array('&amp;', '&#039;', '&quot;', '&lt;', '&gt;'), array('&', "'", '"', '<', '>'), $category));
				$u_sub_category = urlencode(str_replace(array('&amp;', '&#039;', '&quot;', '&lt;', '&gt;'), array('&', "'", '"', '<', '>'), $sub_category));
			}

			if ( $category != $save_category )
			{
				if ( $save_category )
				{
					$row = 
					'<form action="'.append_sid("admin_ads_categories.$phpEx?mode=create").'" method="post">
	         	 <td colspan="3" class="row2">
					 <input class="post" type="text" maxlength="25" name="category" value="'.$save_category.'" readonly="true">
					 <input class="post" type="text" maxlength="25" name="sub_category" >
					 <input class="liteoption" type="submit" name="submit" value="'.$lang['create_new_sub_cat'].'">
					 </td>
					 </form>';
					$template->assign_block_vars('categoryrow', array('ROW' => $row));

					$row = 
					'<td colspan="3" height="1" class="spaceRow"><img src="templates/subSilver/images/spacer.gif" alt="" width="1" height="1" /></td>';
					$template->assign_block_vars('categoryrow', array('ROW' => $row));
				}

			$row = 
				'<td class="catLeft"><span class="cattitle">'.$category.'</span></td>
				 <td class="catLeft"><span class="cattitle">&nbsp;</span></td>
				 <td class="catLeft"><span class="cattitle"><a href="'.append_sid("admin_ads_categories.$phpEx").'&mode=confirm&category='.$u_category.'">'.$lang['Delete'].'</a></span></td>';
			$template->assign_block_vars('categoryrow', array('ROW' => $row));
			$save_category = $category;
			}

		$row = 
			'<td class="row2"><span class="gen">'.$sub_category.'</span></td>
			 <td class="row2"><span class="gen"><a href="'.append_sid("admin_ads_categories.$phpEx").'&mode=edit&category='.$u_category.'&sub_category='.$u_sub_category.'">'.$lang['Edit'].'</a></span></td>
			 <td class="row2"><span class="gen"><a href="'.append_sid("admin_ads_categories.$phpEx").'&mode=confirm&category='.$u_category.'&sub_category='.$u_sub_category.'">'.$lang['Delete'].'</a></span></td>';
		$template->assign_block_vars('categoryrow', array('ROW' => $row));
		}

		$template->assign_vars(array(

			"S_CATEGORIES_ACTION" => append_sid("admin_ads_categories.$phpEx?mode=create"),
	
			"L_ADS_CATEGORIES_TITLE" => $lang['ads_categories_title'],
			"L_ADS_CATEGORIES_EXPLAIN" => $lang['ads_categories_explain'],

			"L_ADS_CATEGORIES_SETTINGS" => $lang['ads_categories_settings'],

			'L_AD_INDEX' => $lang['ad_index'],
			'L_CATEGORIES' => $lang['categories'],
			'L_CREATE_NEW_SUB_CAT' => $lang['create_new_sub_cat'],
			'L_CREATE_NEW_CAT_SUB_CAT' => $lang['create_new_cat_sub_cat'],

			'SITE_NAME' => $site_name,
			'SAVE_CATEGORY' => $save_category));

		$template->pparse("body");

		break;
}

include('./page_footer_admin.'.$phpEx);

?>