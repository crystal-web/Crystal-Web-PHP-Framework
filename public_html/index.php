<?php
session_start ();
header ( 'Content-Type: text/html; charset=utf-8' );
/*
Linux, c'est comme être célib', tu peux faire tout ce que tu veux, il faut tout faire à la main mais tu fini par avoir ce que tu voulais sans problème
Windows, c'est comme être en couple, tu peux pas toujours faire ce que tu veux, il faut y mettre du sien, mais tu es aidé et finalement c'est plutot plaisant, même si des fois tu aimerais que ca plante moins
Mac, c'est comme aller aux putes, ca coute cher, ca fait uniquement ce que tu as payé pour que ça fasse, mais ca le fait bien et tu n'as rien à faire, et les suppléments coutent très cher
 */
$debut = microtime ( true );

/**
 * Directory separator
 */
define ( 'DS', DIRECTORY_SEPARATOR );

/**
 * define the site path
 */
define ( '__SITE_PATH', realpath ( dirname ( __FILE__ ) ) );

/**
 * define the application path 
 */
define ( '__APP_PATH', dirname ( __SITE_PATH ) . DS . 'application' );

/**
 * define loader type
 */
if (preg_match ( '#Java#', $_SERVER ['HTTP_USER_AGENT'] )) {
	define ( '__LOADER', 'java' );
} else {
	define ( '__LOADER', 'browser' );
}
/**
 * Doit etre false en production
 */
define ( '__DEV_MODE', 1 );

/**
 * Dev mode is enabled ?
 */
$err = (__DEV_MODE) ? error_reporting ( - 1 ) : error_reporting ( 0 );

/**
 * include the init.php file
 */
include __SITE_PATH . DS . 'includes' . DS . 'init.php';
$mvc = new mvc ();

if (__CW_PATH != $http . '://' . $_SERVER ['SERVER_NAME']) {
	Router::redirect ( __CW_PATH . Router::selfURL ( false ) );
	die ();
}

/*** Recherche de la configuration ***/
$oCwConfig = new Cache ( __SQL );
$mvc->config = $oCwConfig->getCache ();

/*** load up the template ***/
$mvc->Template = new Template ( $mvc );
$mvc->Template->setPath ( __VIEWS );

/*** load page ***/
$mvc->Page = new Page ();
$mvc->Page->setSiteTitle ( isSet ( $mvc->config ['sitename'] ) ? $mvc->config ['sitename'] : 'Crystal-Web' );

$mvc->Dispatcher = new Dispatcher ( $mvc );
if (! isSet ( $_GET ['rpc'] )) {
	$mvc->Page->setLayout ( 'default' );
} else {
	$mvc->Page->setLayout ( 'empty' );
}
require_once __APP_PATH . DS . 'layout' . DS . $mvc->Page->getLayout () . '.phtml';
?>

