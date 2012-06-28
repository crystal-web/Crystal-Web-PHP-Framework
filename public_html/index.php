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
 if (preg_match('#Java#', $_SERVER['HTTP_USER_AGENT'])) { define ('__LOADER', 'java');} 
 else{define ('__LOADER', 'browser');}
/**
 * Doit etre false en production
 */
define ('__DEV_MODE', 0);

/**
 * Dev mode is enabled ?
 */
$err = (__DEV_MODE) ? error_reporting(-1) : error_reporting(0);


/**
 * include the init.php file
 */
include __SITE_PATH  . DS . 'includes' . DS . 'init.php';
$mvc = new mvc();

if ( __CW_PATH != $http . '://' . $_SERVER['SERVER_NAME'] )
{
	Router::redirect(__CW_PATH . Router::selfURL(false));die;
} 

/*** Recherche de la configuration ***/
$oCwConfig = new Cache(__SQL);
$mvc->config = $oCwConfig->getCache();

/*** load up the template ***/
$mvc->Template = new Template($mvc);
$mvc->Template->setPath(__VIEWS);



/*** load page ***/
$mvc->Page = new Page();
$mvc->Page->setSiteTitle(isSet($mvc->config['sitename']) ? $mvc->config['sitename'] : 'Crystal-Web');
$mvc->Page->setLayout('default');

$mvc->Dispatcher = new Dispatcher($mvc);


if (isSet($mvc->Page->layout))
{
require_once __APP_PATH . DS . 'layout' . DS .$mvc->Page->layout . '.phtml';
}
else
{
require_once __APP_PATH . DS . 'layout' . DS .'default.phtml';
}
?>

