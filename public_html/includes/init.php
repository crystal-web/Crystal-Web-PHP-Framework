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

define ('__VER', 12.04);

/*** define the site path  ***/ 
define ('__CDN', 'http://cdn.crystal-web.org'); // CDN
define ('__VIEWS', __APP_PATH);	// Path View

define ('magicword', 'passphrase pour garantir un hash des mot de passe');

define ('__PAGE', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']); // Page actuel

/*** Adresse du site ***/
$http = (isSet($_SERVER['HTTPS'])) ? 'https' : 'http';
define ('__CW_PATH', $http . '://' . $_SERVER['SERVER_NAME']); //' . $_SERVER['SERVER_NAME'] Site url/~devphp

/*** DB Configuration ***/
define ('DB_host', 'localhost'); // le chemin vers le serveur
define ('DB_type', 'mysql'); // Choix entre mysql, pgsql, OCI et sqlite
define ('DB_port', '3306');	// Port si nécessaire mysql 3306/3305 pgsql 4444
define ('DB_name', 'dbname'); // le nom de votre base de données
define ('DB_user', 'root'); // nom d'utilisateur pour se connecter
define ('DB_password', ''); // mot de passe de l'utilisateur pour se connecter
define ('__SQL', 'cYw_'); // Prefixe Au choix [A-Z_]

define ('ADMIN_MAIL', 'noreply@'.$_SERVER['SERVER_NAME']); // Adresse expéditeur

include __APP_PATH . DS . 'function' . DS . 'function.inc.php';
