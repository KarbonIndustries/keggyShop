<?php
define('HTTP_PFX','http://');
define('HTTPS_PFX','https://');
define('DS',DIRECTORY_SEPARATOR);
define('FS', '/');
define('PD','..' . DS);
require_once('_host.php');
require_once('_root.php');
require_once('dir_config.php');
# MISC
define('EOL',PHP_EOL);
define('NL',"\n");
define('RN',"\r\n");
define('BR','<br />');
define('HOUR_SECS',360);
define('DAY_SECS',86400);
define('WEEK_SECS',604800);
define('JQUERY_VERSION','1.7.1');
define('USD','USD');
# CLIENT
define('CLIENT_SHORT_NAME','Keggy');
define('CLIENT_LONG_NAME','Keggy Enterprises LLC');
#CREDITS [must come after misc constants]
define('DEV_SHORT_NAME','Karbon');
define('DEV_LONG_NAME','Karbon Interaktiv Inc');
define('DEV_URL',HTTP_PFX . 'karboninc.com' . FS);
# URLS
define('MAIN_SITE_URL',HTTP_PFX . 'keggy.com' . FS);
# PRIVATE
require_once(INC_DIR . 'paypal.php');
?>