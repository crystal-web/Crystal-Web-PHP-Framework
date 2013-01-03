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


/*** define the CDN server  ***/ 
define ('__CDN', 'http://cdn.pichax.fr'); // CDN
//define ('__VIEWS', __APP_PATH);	// Path View

/*** Hash string for password ***/
define ('magicword', '');


/*** DB Configuration ***/
define ('DB_HOSTNAME', 'localhost'); // le chemin vers le serveur
define ('DB_DRIVER', 'mysql'); // Choix entre mysql, pgsql, OCI et sqlite
define ('DB_PORT', '3306'); // Port si n�cessaire mysql 3306/3305 pgsql 4444
define ('DB_USERNAME', 'username'); // nom d'utilisateur pour se connecter
define ('DB_PASSWORD', 'password'); // mot de passe de l'utilisateur pour se connecter 3F6fpVC3aK4eadZv
define ('DB_DATABASE', 'databasename'); // le nom de votre base de donn�es
define ('__SQL', 'cw_'); // Prefixe Au choix [A-Z_]

