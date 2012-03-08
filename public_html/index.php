<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
$debut = microtime(true); 

/**
 * Directory separator
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * define the site path
 */
define ('__SITE_PATH', realpath(dirname(__FILE__)));

/**
 *  define the application path 
 */
define ('__APP_PATH', dirname(__SITE_PATH) . DS . 'application');

/**
 * define loader type
 */
define ('__LOADER', 'browser');

/**
 * Doit etre false en production
 */
define ('__DEV_MODE', true);

/**
 * Dev mode is enabled ?
 */
$err = (__DEV_MODE) ? error_reporting(-1) : error_reporting(0);

$http = (isSet($_SERVER['HTTPS'])) ? 'https' : 'http';
//$sitePath = $http . '://' . $_SERVER['SERVER_NAME'] . preg_replace('#(index\.php)#i', '', $_SERVER['REQUEST_URI']);
//$sitePath = trim($sitePath, '/');
define ('__CW_PATH', $http . '://' . $_SERVER['SERVER_NAME']); // Site url/~hurricane


/**
 * include the init.php file
 */
include __SITE_PATH  . DS . 'includes' . DS . 'init.php';
$mvc = new mvc();

/*** Recherche de la configuration ***/
$oCwConfig = new Cache(__SQL);
$mvc->config = $oCwConfig->getCache();

/*** load up the template ***/
$mvc->Template = new Template($mvc);
$mvc->Template->setPath(__VIEWS);

/*** load html ***/
$mvc->html = new html($mvc);
$mvc->html->setSrcCss(__CDN . '/files/css/common.css');
$mvc->html->setSrcScript(__CDN . '/files/js/common.js');

/*** load page ***/
$mvc->Page = new Page();
$mvc->Page->setSiteTitle(isSet($mvc->config['sitename']) ? $mvc->config['sitename'] : 'Crystal-Web');


$mvc->Dispatcher = new Dispatcher($mvc);


if (isSet($mvc->Page->layout))
{
require_once __APP_PATH . DS . 'layout' . DS .$mvc->Page->layout . '.phtml';
}
else
{
require_once __APP_PATH . DS . 'layout' . DS .'default' . '.phtml';
}

?>