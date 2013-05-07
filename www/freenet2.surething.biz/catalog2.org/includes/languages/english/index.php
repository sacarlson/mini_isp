<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
*/
$text_main = "<h1> Welcome to FreeNET </h1> If you are a new user please <a href=\"". $HTTP_SERVER . "/catalog2/create_account.php\"><u>create an account</u></a>.  after you have signed up you will automaticaly receive a FREE day (24 hours) trail package to try before you buy. If you are having problems loging into Freenet please feel free to contact me Scotty  at ph# 086-827-0277.  If you already have an account or just created one, go directly to  <a href=\"" . $HTTP_SERVER . "/index.php\"><u>Network login page</u></a>";
define('TEXT_MAIN', $text_main);
//define('TEXT_MAIN', '<h1> Welcome to FreeNET </h1> If you are a new user please <a href="http://freenet.surething.biz/catalog2/create_account.php"><u>create an account</u></a>.  after you have signed up you will automaticaly receive a FREE day (24 hours) trail package to try before you buy. If you are having problems loging into Freenet please feel free to contact me Scotty  at ph# 086-827-0277.  If you already have an account or just created one, go directly to  <a href="http://freenet2.surething.biz/index.php"><u>Network login page</u></a>');
define('TABLE_HEADING_NEW_PRODUCTS', 'New Products For %s');
define('TABLE_HEADING_UPCOMING_PRODUCTS', 'Upcoming Products');
define('TABLE_HEADING_DATE_EXPECTED', 'Date Expected');

if ( ($category_depth == 'products') || (isset($HTTP_GET_VARS['manufacturers_id'])) ) {
  define('HEADING_TITLE', 'Let\'s See What We Have Here');
  define('TABLE_HEADING_IMAGE', '');
  define('TABLE_HEADING_MODEL', 'Model');
  define('TABLE_HEADING_PRODUCTS', 'Product Name');
  define('TABLE_HEADING_MANUFACTURER', 'Manufacturer');
  define('TABLE_HEADING_QUANTITY', 'Quantity');
  define('TABLE_HEADING_PRICE', 'Price');
  define('TABLE_HEADING_WEIGHT', 'Weight');
  define('TABLE_HEADING_BUY_NOW', 'Buy Now');
  define('TEXT_NO_PRODUCTS', 'There are no products to list in this category.');
  define('TEXT_NO_PRODUCTS2', 'There is no product available from this manufacturer.');
  define('TEXT_NUMBER_OF_PRODUCTS', 'Number of Products: ');
  define('TEXT_SHOW', '<b>Show:</b>');
  define('TEXT_BUY', 'Buy 1 \'');
  define('TEXT_NOW', '\' now');
  define('TEXT_ALL_CATEGORIES', 'All Categories');
  define('TEXT_ALL_MANUFACTURERS', 'All Manufacturers');
} elseif ($category_depth == 'top') {
  define('HEADING_TITLE', 'What\'s New Here?');
} elseif ($category_depth == 'nested') {
  define('HEADING_TITLE', 'Categories');
}
?>
