<?php
  //catalog2 configs
  $username="sacarlson";
  $password="scottc";
  $database="freenet3";
  $hostname="localhost";

  
    define('HTTP_SERVER', 'http://freenet.surething.biz');
    define('HTTPS_SERVER', 'http://freenet.surething.biz');
    define('HTTP_CATALOG_SERVER', 'http://freenet.surething.biz');
    define('HTTPS_CATALOG_SERVER', 'http://freenet.surething.biz');
 
    define('DIR_FS_CATALOG', '/var/www/freenet.surething.biz/catalog2/');
    define('DIR_FS_DOCUMENT_ROOT', '/var/www/freenet.surething.biz/catalog2/');
    define('DIR_FS_ADMIN', '/var/www/freenet.surething.biz/catalog2/admin/');

    define('DB_SERVER', $hostname );
    define('DB_SERVER_USERNAME', $username );
    define('DB_SERVER_PASSWORD', $password );
    define('DB_DATABASE', $database );
  
  //freenet custom configs
  $mysql_hostname		= $hostname;			// mysql hostname
  $mysql_database		= $database;			// mysql database
  $mysql_username		= $username;			// mysql username
  $mysql_password		= $password;			// mysql password 

  //phpBB2 configs  
  $dbms = 'mysql4';
  $dbhost = $hostname;
  $dbname = 'phpbb2';
  $dbuser = $username;
  $dbpasswd = $password;
  $table_prefix = 'phpbb_';
  define('PHPBB_INSTALLED', true);

  //daloradius settings
  $configValues['enable_radius_support'] = 1;
  $configValues['FREERADIUS_VERSION'] = '2';
  $configValues['CONFIG_DB_HOST'] = $hostname;
  $configValues['CONFIG_DB_PORT'] = '3306';
  $configValues['CONFIG_DB_USER'] = $username;
  $configValues['CONFIG_DB_PASS'] = $password;
  $configValues['CONFIG_DB_NAME'] = 'radius';
  $configValues['CONFIG_DB_TBL_RADCHECK'] = 'radcheck';

?>
