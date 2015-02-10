<?php
/**
 * @title Crystal-Web Framework
 * @author Christophe BUFFET <developpeur@crystal-web.org>
 * @license Creative Commons By
 * @license http://creativecommons.org/licenses/by/2.0/fr/
 * /!\ push this file in .gitignore before commit
 */
if (!defined('__APP_PATH'))
{
	echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>'; die;
}
define ('ENABLE_LOG', true); // Log interne sont actif ? false en production

/*** DB Configuration Host ***/
define ('DB_HOSTNAME', 'localhost'); // le chemin vers le serveur
define ('DB_DRIVER', 'mysql'); // Choix entre mysql, pgsql, OCI et sqlite
define ('DB_PORT', '3306'); // Port si necessaire mysql 3306/3305 pgsql 4444
define ('DB_DATABASE', 'My-DatabaseName-SQL'); // le nom de votre base de donn�es
define ('DB_USERNAME', 'My-User-SQL'); // nom d'utilisateur pour se connecter
define ('DB_PASSWORD', 'My-Secret-Password'); // mot de passe de l'utilisateur pour se connecter
define ('__SQL', 'cwf_'); // Prefixe Au choix [A-Z_]
