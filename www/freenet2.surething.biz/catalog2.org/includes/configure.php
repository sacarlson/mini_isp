<?php
  // moved parts of configure.php values seen commented out to ../../config.php
  include(dirname(__FILE__) . '/../../config.php');
  //define('HTTP_SERVER', 'http://freenet2.surething.biz');
  //define('HTTPS_SERVER', 'http://freenet2.surething.biz');
  define('ENABLE_SSL', false);
  define('HTTP_COOKIE_DOMAIN', '');
  define('HTTPS_COOKIE_DOMAIN', '');
  define('HTTP_COOKIE_PATH', '/catalog2/');
  define('HTTPS_COOKIE_PATH', '/catalog2/');
  define('DIR_WS_HTTP_CATALOG', '/catalog2/');
  define('DIR_WS_HTTPS_CATALOG', '/catalog2/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
  define('DIR_WS_DOWNLOAD_PUBLIC', 'pub/');
  //define('DIR_FS_CATALOG', '/var/www/freenet2.surething.biz/catalog2/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

  //define('DB_SERVER', 'localhost');
  //define('DB_SERVER_USERNAME', 'sacarlson');
  //define('DB_SERVER_PASSWORD', 'password');
  //define('DB_DATABASE', 'freenet2');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', 'mysql');
  define('ENTRY_STREET_ADDRESS_DEFAULT', '482 Moo 10 Soi 13 (PBC)');
  define('ENTRY_POST_CODE_DEFAULT', '');
  define('ENTRY_CITY_DEFAULT', '');
  define('ENTRY_COUNTRY_DEFAULT', '209');
?>
