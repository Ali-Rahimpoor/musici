<?php
session_start();

define('ABSPATH', __DIR__.'/');
define('INC_PATCH', ABSPATH.'includes/');
require (ABSPATH.'includes/functions_settings.php');
require (ABSPATH . "config.php");
require (INC_PATCH . "jdf.php");

date_default_timezone_set(SITE_TIMEZONE);

require (INC_PATCH."database.php");
require (INC_PATCH."functions.php");
require (INC_PATCH."functions-database.php");
require (INC_PATCH.'functions-user.php');
require (INC_PATCH.'functions_sms.php');
