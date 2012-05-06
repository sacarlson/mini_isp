<?php
/***************************************************************************
 *                              auction_header.php
 *                            -------------------
 *   begin                :   JULY 2004
 *   copyright            :   (C) FR
 *   email                :   fr@php-styles.com
 *
 *   Last Update          :   AUG 2004 - FR
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

define('HEADER_INC', TRUE);
$template->set_filenames(array('auction_header' => 'auction_header.tpl'));
$template->assign_vars(array(
     'AUCTION_COLOR_1'=> $theme['fontcolor2'],
     'AUCTION_COLOR_2'=> $theme['fontcolor3']));
     
$template->pparse('auction_header');

?>