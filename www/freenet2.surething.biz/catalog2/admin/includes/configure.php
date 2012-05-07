<?php
  // moved parts of configure.php values seen commented out to ../../../config.php
  include(dirname(__FILE__) . '/../../../config.php');
  //define('HTTP_SERVER', 'http://freenet2.surething.biz');
  //define('HTTP_CATALOG_SERVER', 'http://freenet2.surething.biz');
  //define('HTTPS_CATALOG_SERVER', 'http://freenet2.surething.biz');
  define('ENABLE_SSL_CATALOG', 'false');
  //define('DIR_FS_DOCUMENT_ROOT', '/var/www/freenet2.surething.biz/catalog2/');
  define('DIR_WS_ADMIN', '/catalog2/admin/');
  //define('DIR_FS_ADMIN', '/var/www/freenet2.surething.biz/catalog2/admin/');
  define('DIR_WS_CATALOG', '/catalog2/');
  //define('DIR_FS_CATALOG', '/var/www/freenet2.surething.biz/catalog2/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
  define('DIR_WS_INCLUDES', 'includes/');
  define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
  define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
  define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
  define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
  define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');

  //define('DB_SERVER', 'localhost');
  //define('DB_SERVER_USERNAME', 'sacarlson');
  //define('DB_SERVER_PASSWORD', 'password');
  //define('DB_DATABASE', 'freenet2');
  define('USE_PCONNECT', 'false');
  define('STORE_SESSIONS', 'mysql');
?>
