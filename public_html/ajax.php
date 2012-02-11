<?php
session_start();
if (preg_match('#MSIE#', $_SERVER["HTTP_USER_AGENT"])){header('Cache-Control: no-cache');}

/*** Doit etre false en production ***/
define ('__DEV_MODE', true);

/*** define the site path  ***/
define ('__SITE_PATH', realpath(dirname(__FILE__)));

/*** define loader type ***/
define ('__LOADER', 'ajax');

/*** include the init.php file ***/
include __SITE_PATH . '/includes/init.php';
$temps = getmicrotime(); //temps au debut du chargemennt

/*** Dev mode is enabled ? ***/
$err = (__DEV_MODE===true) ? error_reporting(-1) : error_reporting(0);
?>