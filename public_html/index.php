<?php
session_start ();
ob_start("ob_gzhandler");

/*
Linux, c'est comme Ãªtre cÃ©lib', tu peux faire tout ce que tu veux, il faut tout faire Ã  la main mais tu fini par avoir ce que tu voulais sans problÃ¨me
Windows, c'est comme Ãªtre en couple, tu peux pas toujours faire ce que tu veux, il faut y mettre du sien, mais tu es aidÃ© et finalement c'est plutot plaisant, mÃªme si des fois tu aimerais que ca plante moins
Mac, c'est comme aller aux putes, ca coute cher, ca fait uniquement ce que tu as payÃ© pour que Ã§a fasse, mais ca le fait bien et tu n'as rien Ã  faire, et les supplÃ©ments coutent trÃ¨s cher
 */
list($usec, $sec) = explode(" ", microtime());
$__startMicrotime = $usec;

$__requestTime = (isSet($_SERVER['REQUEST_TIME']))  ? $_SERVER['REQUEST_TIME'] : time();
// Doit etre false en production
define ( '__DEV_MODE', 1 );
// Dev mode is enabled ?
$err = (__DEV_MODE) ? error_reporting ( - 1 ) : error_reporting ( 0 );
// Directory separator
define ( 'DS', DIRECTORY_SEPARATOR );
// define the site path
define ( '__SITE_PATH', realpath ( dirname ( __FILE__ ) ) );
// define the application path 
define ( '__APP_PATH', dirname ( __SITE_PATH ) . DS . 'application' );
define ('__PAGE', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']); // Page actuel
define ( '__LOADER', 'browser' );




/*** Chargement des fichiers systeme ***/

	// include systeme file
	/*	Simple config file	*/	require_once __SITE_PATH . DS . 'includes' . DS . 'init.php';
	/*	Function library	*/	require_once __APP_PATH . DS . 'function' . DS . 'function.inc.php';
	
	/*
	 * 	Router systeme
	 **/
	require_once __APP_PATH . DS . 'framework' . DS . 'Router.php';
	
	// Every all in framework file
	if ($handle = opendir(__APP_PATH . DS . 'framework')) {
	    /* This is the correct way to loop over the directory. */
	    while (false !== ($entry = readdir($handle))) {
	    	if (preg_match('#.php$#', $entry))
			{
				Log::setLog('AutoLoad: framework' . DS . $entry, 'Loader');
	        	require_once __APP_PATH . DS . 'framework' . DS . $entry;
			}
	    }
	    closedir($handle);
	}
	
	$config = Config::getInstance();
	// Adresse du site
	define ('__CW_PATH', $config->getSiteUrl()); 
	

	new Dispatcher (  );
?>