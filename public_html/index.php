<?php
session_start ();
ob_start("ob_gzhandler");
header('X-Powered-By: Crystal-Web/3.1.1 devphp.me');
// Patch $_SERVER for CLI usage
$_SERVER['SERVER_NAME'] = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : '127.0.0.1';
$_SERVER['REQUEST_URI'] = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '/';
$_SERVER['HTTP_USER_AGENT'] = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : 'console';
// Default define
define ( '__START_MICROTIME', -microtime(true));
define ( '__REQUEST_TIME', (isSet($_SERVER['REQUEST_TIME']))  ? $_SERVER['REQUEST_TIME'] : time());
define ( 'DS', DIRECTORY_SEPARATOR );									// Directory separator

define ( '__SITE_PATH', realpath ( dirname ( __FILE__ ) ) );			// define the site path
define ( '__PUBLIC_PATH', dirname ( __SITE_PATH ) . DS . 'www' );						// define the public folder

define ( '__APP_PATH', dirname ( __SITE_PATH ) . DS . 'application' );	// define the application path 
define ( '__ABS_PATH', dirname ( __SITE_PATH ) );						// define the absolute path 
define ( '__PAGE', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);	// Page actuel
define ( '__LOADER', 'browser' );
require_once __SITE_PATH . DS . 'includes' . DS . 'init.php';			// Variable init
if (isset($_GET['dd']) || isset($_SESSION['dd'])) {						// Switch debug
	$_SESSION['dd'] = true;												// Sauvegarde l'etat
	define ( '__DEV_MODE', 1 );											// Switch a true
} else {define ( '__DEV_MODE', 0);}							        	// Par defaut
unset($devMode);
$err = (__DEV_MODE) ? error_reporting ( - 1 ) : error_reporting ( 0 );	// Report pour les erreurs
require_once __APP_PATH . DS . 'function' . DS . 'function.inc.php';	//	Function library
try {define ('magicword',getMagik()); } 						// Hash string for password
catch (Exception $e) { die($e->getMessage()); } 						// Hash exception
require_once __APP_PATH . DS . 'framework' . DS . 'Router.php';			// Router systeme
// Every all in framework file
if ($handle = opendir(__APP_PATH . DS . 'framework')) { 
    /* This is the correct way to loop over the directory. */
    while (false !== ($entry = readdir($handle))) {
    	if (preg_match('#.php$#', $entry)) { 
        	require_once __APP_PATH . DS . 'framework' . DS . $entry;
		}
	}
    closedir($handle);
}

$isAjax = false;
if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$isAjax = true;
}
define('__ISAJAX', $isAjax);
// Lancement de l'application enjoy
new Dispatcher ( );

