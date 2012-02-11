<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by/2.0/fr/
*/
/*** define the site path  ***/ 
define ('__APP', '../application');					// Path Application
define ('__CDN', 'http://cdn.crystal-web.org'); 	// CDN
define ('__VIEWS', '../application'); 	// Path View


$_SESSION['theme'] = (isSet($_GET['theme'])) ? urlencode($_GET['theme']) : 'boot';
define ('__TEMPLATE', $_SESSION['theme']); // Theme a appliquer
define ('__TEMPLATE_ADMIN', 'admin'); // Theme a appliquer

define ('__PAGE', $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']); // Page actuel
define ('__CW_PATH', 'http://www.imagineyourcraft.com/~minecraft'); // Site url/~hurricane

/*** DB Configuration ***/
define ('DB_host', 'localhost'); // le chemin vers le serveur
define ('DB_type', 'mysql'); // Choix entre mysql, pgsql, OCI et sqlite
define ('DB_port', '3306'); // Port si nécessaire mysql 3306/3305 pgsql 4444
define ('DB_name', 'manager'); // le nom de votre base de données
define ('DB_user', 'devphp'); // nom d'utilisateur pour se connecter
define ('DB_password', 'tAnr632qnwBVzc5t'); // mot de passe de l'utilisateur pour se connecter
define ('__SQL', 'iyc_'); // Prefixe Au choix [A-Z_]

/***  ***/
define ('ADMIN_TEAM', 'Beta site'); // Adresse expéditeur
define ('ADMIN_MAIL', 'noreply@'.$_SERVER['SERVER_NAME']); // Adresse expéditeur

define ('AVATAR_WIDTH', 100);
define ('AVATAR_HEIGHT', 100);
define ('SITE_NAME', 'Team Crystal-web');


/******************
 x * 1024 = x KB 
 x * 1048576 = x MB 
******************/
define ('AVATAR_OCTAL_SIZE', 0.5 * 1048576); 
/*** ^_^ Google friendly ;-) ***/
define ('REWIRTE_URL', false);
  
define ('magicword', '005deeacbe9be383bd92d7d29727dcd5');
define ('__VER', 12.02);
// Variable header
header('Server: Crystal-Web');
header('X-Powered-By: Crystal-Web.org Solution Developpement IT/'.__VER);

define ('__CGU', "Conditions g&eacute;n&eacute;ral d'utilisation du site

Les mod&eacute;rateurs de ce site s'efforceront de supprimer ou &eacute;diter tous les messages &agrave; caract&egrave;re r&eacute;pr&eacute;hensible aussi rapidement que possible. Toutefois, il leur est impossible de passer en revue tous les messages. Vous admettez donc que tous les messages post&eacute;s sur ce site expriment la vue et opinion de leurs auteurs respectifs, et non celles des mod&eacute;rateurs ou du webmestre (except&eacute; des messages post&eacute;s par eux-m&ecirc;mes) et par cons&eacute;quent qu'ils ne peuvent pas &ecirc;tre tenus pour responsables des discussions. 

L'adresse e-mail est uniquement utilis&eacute;e afin de confirmer les d&eacute;tails de votre inscription ainsi que votre mot de passe (et aussi pour vous renvoyer votre mot de passe en cas d'oubli). 

- les messages agressifs ou diffamatoires, les insultes et critiques personnelles, les grossi&egrave;ret&eacute;s et vulgarit&eacute;s, et plus g&eacute;n&eacute;ralement tout message contrevenant aux lois sont interdits 
- les messages incitant &agrave; - ou &eacute;voquant - des pratiques ill&eacute;gales sont interdits ;
- si vous diffusez des informations provenant d'un autre site web, v&eacute;rifiez auparavant si le site en question ne vous l'interdit pas. Mentionnez l'adresse du site en question par respect du travail de ses administrateurs !
- merci de poster vos messages une seule fois. Les r&eacute;p&eacute;titions sont d&eacute;sagr&eacute;ables et inutiles !
- merci de faire un effort sur la grammaire et l'orthographe. Style SMS fortement d&eacute;conseill&eacute; !
- aucun compte ouvert ne pourra &ecirc;tre supprim&eacute; ! (ceci pour des raisons technique)

Tout message contrevenant aux dispositions ci-dessus sera &eacute;dit&eacute; ou supprim&eacute; sans pr&eacute;avis ni justification suppl&eacute;mentaire dans des d&eacute;lais qui d&eacute;pendront de la disponibilit&eacute; des mod&eacute;rateurs. Tout abus entraînera le bannisment de votre compte, e-mail, adresse IP. 
Internet n'est ni un espace anonyme, ni un espace de non-droit ! Nous nous r&eacute;servons la possibilit&eacute; d'informer votre fournisseur d'acc&egrave;s et/ou les autorit&eacute;s judiciaires de tout comportement malveillant. L'adresse IP de chaque intervenant est enregistr&eacute;e afin d'aider &agrave; faire respecter ces conditions.

En vous inscrivant sur le site vous reconnaissez avoir lu dans son int&eacute;gralit&eacute; le pr&eacute;sent r&egrave;glement. Vous vous engagez &agrave; respecter sans r&eacute;serve le pr&eacute;sent r&egrave;glement. Vous accordez aux mod&eacute;rateurs de ce site le droit de supprimer, d&eacute;placer ou &eacute;diter n'importe quel sujet de discussion &agrave; tout moment.

Nous prot&eacute;geons la vie priv&eacute;e de nos utilisateurs en respectant la l&eacute;gislation en vigueur.
Ainsi, vos donn&eacute;es personnelles restent strictement confidentielles et ne seront donc pas distribu&eacute;es &agrave; des tierces parties sans votre accord.");

include __APP . '/function/function.inc.php';
include __APP . '/framework/' . 'mvc.class.php';
include __APP . '/framework/' . 'controller_base.class.php';
include __APP . '/framework/' . 'router.class.php';
include __APP . '/framework/' . 'template.class.php';
include __APP . '/framework/' . 'Laucher.php';

/***
Chargement automatique des class
***/
function __autoload($class_name)
{

$filename = $class_name . '.class.php';
$file = __APP . '/libs/' . $filename;
	if (file_exists($file) == false)
	{
	exit('Class '.$class_name.' not found in '.__APP . '/libs/' . $filename);
	}
include ($file);
}
/***
END Chargement automatique des class
***/
?>