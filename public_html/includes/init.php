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

define ('__VER', 12.05);

/*** define the site path  ***/ 
define ('__CDN', 'http://cdn.crystal-web.org'); // CDN
define ('__VIEWS', __APP_PATH);	// Path View

define ('magicword', 'passphrase pour garantir un hash des mot de passe');

define ('__PAGE', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']); // Page actuel

/*** Adresse du site ***/
$http = (isSet($_SERVER['HTTPS'])) ? 'https' : 'http';
define ('__CW_PATH', $http . '://' . $_SERVER['SERVER_NAME']); //' . $_SERVER['SERVER_NAME'] Site url/~devphp

/*** DB Configuration ***/
define ('DB_HOSTNAME', 'localhost'); // le chemin vers le serveur
define ('DB_DRIVER', 'mysql'); // Choix entre mysql, pgsql, OCI et sqlite
define ('DB_PORT', '3306'); // Port si n�cessaire mysql 3306/3305 pgsql 4444
define ('DB_DATABASE', 'DBNAME'); // le nom de votre base de donn�es
define ('DB_USERNAME', 'USER'); // nom d'utilisateur pour se connecter
define ('DB_PASSWORD', 'PASSWORD'); // mot de passe de l'utilisateur pour se connecter


/*** Temporary Define ***/
define ('TEAM_NAME', 'Team Crystal-Web');
define ('SITENAME', 'Crystal-Web');


define ('__SQL', 'git_'); // Prefixe Au choix [A-Z_]

define ('ADMIN_MAIL', 'noreply@'.$_SERVER['SERVER_NAME']); // Adresse exp�diteur

include __APP_PATH . DS . 'function' . DS . 'function.inc.php';
