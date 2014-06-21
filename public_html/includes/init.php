<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by/2.0/fr/
*/

if (!defined('__APP_PATH'))
{
	echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>'; die;
}
define ( '__HTTP', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http');		// SSL ou pas
define ('ENABLE_LOG', true); // Log interne sont actif ? false en production
// Adresse du site // $config->getSiteUrl()
define ('__CW_PATH', __HTTP . '://beta.devphp.me');

/*** DB Configuration Host ***/
define ('DB_HOSTNAME', 'localhost'); // le chemin vers le serveur
define ('DB_DRIVER', 'mysql'); // Choix entre mysql, pgsql, OCI et sqlite
define ('DB_PORT', '3306'); // Port si n�cessaire mysql 3306/3305 pgsql 4444
define ('DB_DATABASE', 'databasename'); // le nom de votre base de donn�es
define ('DB_USERNAME', 'username'); // nom d'utilisateur pour se connecter
define ('DB_PASSWORD', 'password'); // mot de passe de l'utilisateur pour se connecter
define ('__SQL', 'cw_'); // Prefixe Au choix [A-Z_]

$_SERVER['SERVER_NAME'] = (isset($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : NULL;

// Chargement des fichiers CSS / JS
$loader = array();
