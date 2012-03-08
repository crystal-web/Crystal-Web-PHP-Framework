<?php
/**
* Chargement automatique des class
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param string $class_name
* @return void
*/
function __autoload($class_name)
{
$filename = $class_name . '.class.php';
$filePath = __APP_PATH . DS . 'libs' . DS . $filename;
	if (file_exists($filePath))
	{
	include_once ($filePath);
	}
	elseif(__DEV_MODE)
	{
	die('Class file not exists '.$filePath);
	}
}

function loadSystem()
{
	// Make me protect
	if(!__DEV_MODE)
	{
	/**
	* Anti-ClickJacking
	* Pas de iFrame du site
	*/
	header('X-Frame-Options: DENY');
	}
require_once __APP_PATH . DS . 'framework' . DS . 'mvc.php';
require_once __APP_PATH . DS . 'framework' . DS . 'Model.php';
require_once __APP_PATH . DS . 'framework' . DS . 'Dispatcher.php';
require_once __APP_PATH . DS . 'framework' . DS . 'Request.php';
require_once __APP_PATH . DS . 'framework' . DS . 'Router.php';
require_once __APP_PATH . DS . 'framework' . DS . 'Controller.php';
require_once __APP_PATH . DS . 'framework' . DS . 'Page.php';
require_once __APP_PATH . DS . 'framework' . DS . 'Template.php';
require_once __APP_PATH . DS . 'framework' . DS . 'Session.php';
require_once __APP_PATH . DS . 'framework' . DS . 'Form.php';
require_once __APP_PATH . DS . 'framework' . DS . 'AccessControlList.php';
// Zone de stockage des données recurentes
require_once __APP_PATH . DS . 'framework' . DS . 'Register.php';
}
loadSystem();

function loadFunction($function)
{
$file = __APP_PATH . DS . 'function' . DS . $function;
$file .= '.php';
	if (file_exists($file))
	{
	require_once $file;
	}
	elseif (__DEV_MODE === true)
	{
	debug('File not loaded '.$file);
	}
}


/**
* Permet de transformer une chaine de caractères en sont équivalent "slug"
* @author Typhon
* @param $str: Chaine à transformer
* @return Slug de la chaine
**/
function str2slug($str) {
    $str = htmlentities($str, ENT_NOQUOTES, 'utf-8');
    $str = preg_replace('#\&([A-Za-z])(?:grave|acute|circ|tilde|uml|ring|cedil)\;#', '\1', $str);
    $str = preg_replace('#\&([A-Za-z]{2})(?:lig)\;#', '\1', $str);
    $str = str_replace("'", '-', $str);
    $str = str_replace(' ', '-', $str);
    $str = preg_replace('#[^A-Za-z0-9-]#', '', $str);
    $str = str_replace('--', '-', $str);
    $str = strtolower($str);
    $str = trim($str, '-');
    return $str;
}


function getOperatorSidebar($adminSiderbar)
{
echo '<div class="opSiderbar" id="opSiderbar"><ul id="opMenu">';

	foreach ($adminSiderbar AS $k=>$d) 
	{
	echo '<li class="toggleSubMenu"><span>'.$d['title'].'</span><ul class="subMenu">';
		foreach ($d['data'] AS $url => $data)
		{
		echo '<li><a href="'.$data.'">'.$url.'</a></li>';
		
		}
	echo '</ul></li>';
	}
echo '</ul><a class="settingbutton" href="#">	</a></div>';//*/
/*
echo '<div class="opSiderbar" id="opSiderbar"><ul id="opMenu">';
	foreach ($adminSiderbar AS $k=>$d) 
	{
	echo '<li class="opMenu">
		<a href="#">'.$d['title'].'</a>
		<ul id="m'.$k.'">';
		foreach ($d['data'] AS $url => $data)
		{
		echo '<li><a href="'.$data.'">'.$url.'</a></li>';
		
		}
	echo '</ul></li>';
	}

	echo '</ul><a class="settingbutton" href="#">	</a></div>';//*/
}

/**
* Retourne le timestamp,milleseconde
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @return float
*/
function getmicrotime()
{
    if (function_exists('gettimeofday'))
    {
    // retourne le timestamp Unix, avec les microsecondes. 
    // Cette fonction est uniquement disponible
    //  sur les systèmes qui supportent la fonction gettimeofday().
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
    }
    else
    {
    return time();
    }
}

function convert($size, $precision = 2)
{
    if (!is_numeric($size)) return '?';

    $notation = 1024;
    // Fixes large disk size overflow issue
    // Found at http://www.php.net/manual/en/function.disk-free-space.php#81207
    $types = array('B', 'KB', 'MB', 'GB', 'TB');
    $types_i = array('B', 'KiB', 'MiB', 'GiB', 'TiB');
    for($i = 0; $size >= $notation && $i < (count($types) -1 ); $size /= $notation, $i++);
    return(round($size, $precision) . ' ' . ($notation == 1000 ? $types[$i] : $types_i[$i]));
}


function pagination($nb_page)
{
$page = (int) (isset($_GET['page'])) ? $_GET['page'] : 1;
/***************************************
*	Pagination
***************************************/
$html = NULL;
if ($nb_page > 1)
{
$html	=	'<div class="pagination">';
$html	.=	'<ul>';
	// Si la page - une est suppérieur a 0
	// Il y a une page
	if ($page-1 > 0)
	{
		$html	.=	'<li class="prev"><a href="?page='.($page-1).'">Precedent</a></li>';
	}
	// Sinon, il n'y en a pas
	else
	{
		$html	.=	'<li class="prev disabled"><a href="#">Precedent</a></li>';	
	}
	
/***************************************
*	Bloucle simple multi info
***************************************/
		
			for($i=$page-5; $i<$page+5; $i++)
			{
				if ($i<=$nb_page && $i>0)
				{
					if ($page == $i)
					{
					$html	.=	'<li><a href="#" class="disabled">'.$i.'</a></li>';
					}
					else
					{
					$html	.=	'<li><a href="?page='.$i.'">'.$i.'</a></li>';
					}
				}
			}		
		
/***************************************
*	END Bloucle simple multi info
***************************************/
		
	// Si la page + une est inférieur ou egal au nombre de page
	if ($page+1 <= $nb_page)
	{
		$html	.=	'<li class="next"><a href="?page='.($page+1).'">Suivant</a></li>';
	}
	// Sinon, il n'y en a pas
	else
	{
		$html	.=	'<li class="next disabled"><a href="#">Suivant</a></li>';	
	}

	
$html	.=	'</ul>';
$html	.=	'</div>';
}

return $html;
}
/**
* Retourne une alerte "alerte"
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param string $msg|Message retourné par la fonction
* @param bool $echo|Le message doit être print ou return
* @return string
*/
function alerte($msg, $echo = false)
{
$box = '<div class="MSGbox MSGalerte"><p>' . $msg . '</p></div>';
if ($echo == true) { echo $box; } else { return $box; }
}


/**
* Retourne une alerte "astuce"
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param string $msg|Message retourné par la fonction
* @param bool $echo|Le message doit être print ou return
* @return string
*/
function astuce($msg, $echo = false)
{ 
$box = '<div class="MSGbox MSGastuce"><p>' . $msg . '</p></div>';
if ($echo == true) { echo $box; } else { return $box; }
}


/**
* Retourne une alerte "beta"
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param string $msg|Message retourné par la fonction
* @param bool $echo|Le message doit être print ou return
* @return string
*/
function beta($msg, $echo = false)
{
$box = '<div class="MSGbox MSGbeta"><p>' . $msg . '</p></div>';
if ($echo == true) { echo $box; } else { return $box; }
}


/**
* Retourne une alerte "info"
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param string $msg|Message retourné par la fonction
* @param bool $echo|Le message doit être print ou return
* @return string
*/
function info($msg, $echo = false)
{
$box = '<div class="MSGbox MSGinfo"><p>' . $msg . '</p></div>';
if ($echo == true) { echo $box; } else { return $box; }
}


/**
* Retourne une alerte "note"
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param string $msg|Message retourné par la fonction
* @param bool $echo|Le message doit être print ou return
* @return string
*/
function note($msg, $echo = false)
{
$box = '<div class="MSGbox MSGnote"><p>' . $msg . '</p></div>';
if ($echo == true) { echo $box; } else { return $box; }
}


/**
* Retourne une alerte "valide"
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param string $msg|Message retourné par la fonction
* @param bool $echo|Le message doit être print ou return
* @return string
*/
function valide($msg, $echo = false)
{
$box = '<div class="MSGbox MSGvalide"><p>' . $msg . '</p></div>';
if ($echo == true) { echo $box; } else { return $box; }
}


/**
* Test si string est une url
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param string $url|URL du site
* @return bool
*/
function isURL($url)
{
return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}


/**
* Parcourir un dossier et récupérer le contenu de chaque fichier
*
* @author Jay Salvat
* @link http://blog.jaysalvat.com/article/zipper-des-dossiers-a-la-volee-avec-php
* @param string $folder|Dossier a scanner 
* @return array
*/
function scanfolder($folder) {
    $files = array();
    $dh = opendir($folder);
    // je parcours le dossier dans lequel je me trouve
    // et j'analyse ce que je trouve...
    while (($file = readdir($dh)) !== false) {
        $path = $folder."/".$file;
        // si c'est un fichier, j'en récupère
        // le nom et le contenu
        if (is_file($path)) {
            $file = array();
            $fp = fopen($path, "r");
            $file["name"] = $path;
            $file["content"] = fread($fp, filesize($path));
            $files[] = $file;
            fclose($fp);
        // si c'est un dossier qui n'est pas . ou ..
        // je relance un scan sur son contenu.
        } else if (substr($file, 0, 1) != ".") {
           $files = array_merge($files, scanfolder($path));
        }
    }
    closedir($dh);
    return $files;
}


/**
* Debug tools
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param string $arg|Variable a dumper
* @param bool $return|Ecris ou renvois
* @return string
*/
function cwDebug ($arg=null, $return=false)
{
$var = '<p><strong>Crystal-Web Debug Tools:</strong><pre class="code">';
    if (is_array($arg) || is_object($arg))
    {
    $var .= print_r($arg, true);
    }
    else
    {
    $var .= $arg;
    }
$var .= '</pre></p>';

    // Retourne ou ecris ?
    if ($return == false)
    {
    echo $var;
    }
    else
    {
    return $var;
    }
}


function debug($var){

	if(__DEV_MODE){
		$debug = debug_backtrace(); 
		echo '<p>&nbsp;</p><p><a href="#" onclick="$(this).parent().next(\'ol\').slideToggle(); return false;"><strong>'.$debug[0]['file'].' </strong> l.'.$debug[0]['line'].'</a></p>'; 
		echo '<ol style="display:none;">';
		$lastFile = $lastLine = false;
		foreach($debug as $k=>$v){ if($k>0){
		$lastFile = isSet($v['file']) ? $v['file'] : $lastFile;
		$lastLine = isSet($v['line']) ? $v['line'] : $lastLine;
			echo '<li><strong>'.$lastFile.' </strong> l.'.$lastLine.'</li>'; 
		}}
		echo '</ol>'; 
		echo '<pre class="code">';
		var_dump($var);
		echo '</pre>'; 
	}
	
}

/**
* Enregistrement des erreurs dans un fichier cache
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param array $data|Tableau de variables
* @param string $errno|type de l'erreur
* @param string $errstr|message d'erreur
* @param string $errfile|fichier correspondant à l'erreur
* @param string $errline|ligne correspondante à l'erreur 
* @return mixed
*/
function erreur_alerte($errno,$errstr,$errfile,$errline)
{
    // On définit le type de l'erreur
    switch($errno)
    {
    case E_USER_ERROR : $type = "Fatal:"; break;
    case E_USER_WARNING : $type = "Erreur:"; break;
    case E_USER_NOTICE : $type = "Warning:"; break;
    case E_ERROR : $type = "Fatal"; break;
    case E_WARNING : $type = "Erreur:"; break;
    case E_NOTICE :	$type = "Warning:"; break;
    default : $type = "Inconnu:"; break;
    }

// On définit l'erreur.
$erreur = "Type : " . $type . "
Message d'erreur : [" . $errno . "]".$errstr."
Ligne : " . $errline . "
Fichier : " . $errfile;

/* Pour passer les valeurs des différents tableaux, nous utilisons la fonction serialize()
Le rapport d'erreur contient le type de l'erreur, la date, l'ip, et les tableaux. */
$variables = get_defined_vars(); // Donne le contenu et les valeurs de toutes les variables dans la portée actuelle 
$info = date("d/m/Y H:i:s",time())." :
    GET:".print_r($_GET, true).
    "POST:".print_r($_POST, true).
    "SERVER:".print_r($_SERVER, true).
    "COOKIE:".(isset($_COOKIE)? print_r($_COOKIE, true) : "Undefined").
    "SESSION:".(isset($_SESSION)? print_r($_SESSION, true) : "Undefined");


$error_array['more'] = $info;
$error_array['type'] = $type;
$error_array['msg'] = "[".$errno."] ".$errstr;
$error_array['errline'] = $errline;
$error_array['errfile'] = $errfile;

// Lecture du cache
$cache_error = new Cache('erreur_alerte');
$error_cache = $cache_error->getCache();
$error_cache[time()] = $error_array;

// Ecriture du cache
$cache_error_p = new Cache('erreur_alerte', $error_cache);
$cache_error_p->setCache();

    if (__DEV_MODE==true)
    {
    echo nl2br('<div class="MSGbox MSGalerte"><p>'.$erreur.'</p></div>');
    }
}
set_error_handler('erreur_alerte');



/**
* Detection de Internet Explorer
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @return mixed
*/
function is_ie() {
$user_agent = (isSet($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT']: '';
$match=preg_match('/msie ([0-9]\.[0-9])/',strtolower($user_agent),$reg);
if($match==0) return false;
else return floatval($reg[1]);
}



/**
* Réduit la chaine si elle est trop longue.
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param string $string|chaine a tronquer
* @param int $lengthMax|longueur max de la chaine
* @param bool $safe_word|Empécher le troncage de mots
* @return string
*/
function truncatestr($string, $lengthMax, $safe_word=true)
{
if (strlen($string) < $lengthMax) return $string;

	// Séléction du maximum de caractères
	$string = substr($string, 0, $lengthMax);
	if ($safe_word == true)
	{
	// Récupération de la position du dernier espace (afin déviter de tronquer un mot)
	$position_espace = strrpos($string, " ");
	$string = substr($string, 0, $position_espace);
	}
	else
	{
	$string = substr($string, 0, $lengthMax);
	}
	// Ajout des "..."
	return $string."...";
}



/**
* Réduit la chaine si elle est trop longue.
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param string $string|chaine a tronquer
* @param int $lengthMax|longueur max de la chaine
* @deprecated utiliser truncatestr() a la place
* @return string
*/
function truncate($string, $lengthMax){
 // Variable locale
    $positionDernierEspace = 0;
 
    if( strlen($string) >= $lengthMax )
    {
      $string = substr($string,0,$lengthMax); 
      $positionDernierEspace = strrpos($string,' '); 
      $string = substr($string,0,$positionDernierEspace).'...';
    }
	return $string;
}


/**
* Suppréssion des espaces inutiles
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param string $str|chaine a nettoyer
* @return string
*/
function stripspace($str){
$str = trim($str);
$str = preg_replace ("/\s+/", " ", $str);
return $str;
}


    /*******************/
    /***    Math    ***/
    /*****************/



/**
* Suppréssion des espaces inutiles
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param int $nombre|chiffre
* @return bool
*/
function is_paire($nombre){
return ($nombre%2 == 0) ? true : false;
}



/**
* parse php modules from phpinfo
*
* @author code at adspeed dot com
* @package parsePHP
* @return array
*/

function parsePHPModules() { 
 ob_start(); 
 phpinfo(INFO_MODULES); 
 $s = ob_get_contents(); 
 ob_end_clean(); 
  
 $s = strip_tags($s,'<h2><th><td>'); 
 $s = preg_replace('/<th[^>]*>([^<]+)<\/th>/',"<info>\\1</info>",$s); 
 $s = preg_replace('/<td[^>]*>([^<]+)<\/td>/',"<info>\\1</info>",$s); 
 $vTmp = preg_split('/(<h2>[^<]+<\/h2>)/',$s,-1,PREG_SPLIT_DELIM_CAPTURE); 
 $vModules = array(); 
 for ($i=1;$i<count($vTmp);$i++) { 
  if (preg_match('/<h2>([^<]+)<\/h2>/',$vTmp[$i],$vMat)) { 
   $vName = trim($vMat[1]); 
   $vTmp2 = explode("\n",$vTmp[$i+1]); 
   foreach ($vTmp2 AS $vOne) { 
    $vPat = '<info>([^<]+)<\/info>'; 
    $vPat3 = "/$vPat\s*$vPat\s*$vPat/"; 
    $vPat2 = "/$vPat\s*$vPat/"; 
    if (preg_match($vPat3,$vOne,$vMat)) { // 3cols 
     $vModules[$vName][trim($vMat[1])] = array(trim($vMat[2]),trim($vMat[3])); 
    } elseif (preg_match($vPat2,$vOne,$vMat)) { // 2cols 
     $vModules[$vName][trim($vMat[1])] = trim($vMat[2]); 
    } 
   } 
  } 
 } 
 return $vModules; 
} 



/**
* get a module setting
*
* @author code at adspeed dot com
* @package parsePHP
* @return array
*/
function getModuleSetting($pModuleName,$pSetting) { 
 $vModules = parsePHPModules(); 
 return $vModules[$pModuleName][$pSetting]; 
} 





    /*******************/
    /***    Date   ***/
    /*****************/


/**
* Fonction pour afficher les jours restants
*
* @link http://www.dyraz.com
* @author J-C
* @param string $fin|date DD/MM/YYYY
* @return string
*/
function ecartdate($fin)
{
	if (!is_int($fin))
	{
		$debut = date("d/m/y");
		list($jourDebut, $moisDebut, $anneeDebut) = explode('/', $debut); 
		list($jourFin, $moisFin, $anneeFin) = explode('/', $fin);
		$timestampDebut = mktime(0,0,0,$moisDebut,$jourDebut,$anneeDebut); 
		$timestampFin = mktime(0,0,0,$moisFin,$jourFin,$anneeFin);
		$ecart = abs($timestampFin - $timestampDebut)/86400;
		/*$s = ($ecart>1) ? 's' : '';
		$annonce = "Il vous reste ". $ecart ." jour" . $s . " d'offre";*/
		return $ecart; // $annonce;
	}
	else
	{
		$ecart = abs($fin - time())/86400;
		/*$s = ($ecart>1) ? 's' : '';
		$annonce = "Il vous reste ". $ecart ." jour" . $s . " d'offre";*/
		return $ecart; // $annonce;
	}
}



/**
* Retourne le temps relatif
*
* @link http://crystal-web.org
* @author Christophe BUFFET
* @param string $date|date YYYY-MM-DD hh:mm:ss ou timestamp
* @return string
*/
function getRelativeTime($date)
{
     // Test si $date est numerique et donc un timestamp
    if (is_numeric($date))
    {
    $time = time() - $date;
    }
    else // Si pas c'est une date 2010-12-31 13:25:00
    {// Déduction de la date donnée à la date actuelle
     $time = time() - strtotime($date);
    }
    
    
     // Calcule si le temps est passé ou à venir
    if ($time > 0)
    {
    $when = "il y a";
    }
    else if ($time < 0)
    {
    $when = "dans environ";
    }
    else
    {
    return "il y a moins d'une seconde";
    }
$time = abs($time);

// Tableau des unités et de leurs valeurs en secondes
$times = array(
    31104000 =>  'an{s}',		 // 12 * 30 * 24 * 60 * 60 secondes
    2592000  =>  'mois',		  // 30 * 24 * 60 * 60 secondes
    86400	 =>  'jour{s}',	  // 24 * 60 * 60 secondes
    3600	  =>  'heure{s}',	 // 60 * 60 secondes
    60		 =>  'minute{s}',	// 60 secondes
    1		  =>  'seconde{s}'); // 1 seconde

    foreach ($times as $seconds => $unit)
    {
    // Calcule le delta entre le temps et l'unité donnée
    $delta = round($time / $seconds);

        // Si le delta est supérieur à 1
        if ($delta >= 1)
        {
            // L'unité est au singulier ou au pluriel ?
            if ($delta == 1)
            {
            $unit = str_replace('{s}', '', $unit);
            }
            else
            {
            $unit = str_replace('{s}', 's', $unit);
            }
        // Retourne la chaine adéquate
        return $when." ".$delta." ".$unit;
        }
    }
}



/**
* Retourne la date et heure en français
*
* @link http://crystal-web.org
* @author Christophe BUFFET
* @param int $time|timestamp
* @param string $format|fr_date ou fr_datetime
* @return string
*/
function dates($time, $format)
{
/* Translation */
$jours= array("lundi", "mardi",	"mercredi", "jeudi", "vendredi","samedi","dimanche");
$joursNum=array("1", "2", "3", "4", "5", "6", "7");
$j = str_replace($joursNum, $jours, date("N",$time));
$mois = array("janvier", "fevrier", "mars",
"avril","mai", "juin",
"juillet", "août","septembre",
"octobre", "novembre", "decembre");
$moisTxt=array("Jan", "Feb", "Mar",
"Apr", "May", "Jun",
"Jul", "Aug", "Sep",
"Oct", "Nov", "Dec");
$m = str_replace($moisTxt, $mois, date("M",$time));
/* Format */
if ($format=="fr_date")
	{
	return $j." ".date("j",$time)." ".$m." ".date("Y",$time);
	}
elseif ($format=="fr_datetime")
	{
	return $j." ".date("j",$time)." ".$m." ".date("Y",$time).", a ".date("G:i",$time);
	}
$in_format= array("jour", "mois", "num", "annee", "NumSem", "12h", "24h");
$to_format=array($j, $m, date("j",$time), date("Y",$time), date("W",$time), date("g:i",$time), date("G:i",$time), );
$is_array_format = explode(" ", $format);
$t=str_replace($in_format, $to_format, $is_array_format);
return  implode(" ", $t);
}




/**
*	@desc		Réecriture d'url Crystal-Web
*	@author 	Christophe BUFFET <developpeur@crystal-web.org>
*	@copyright	Open Source
*	@version 	1.0
*	@since		1.0
*/

/* htaccess : 
RewriteEngine On
RewriteBase /
RewriteRule ^(.*)\.html index.php?rewrite=$1 [L]
*/

// De : index.php?module=forum&amp;action=lire&amp;page=2&titre=un super coup
// A : forum/lire/page_2/titre_un-super-coup.html
function cleanerUrl($url){
$cleaner = strtr($url, 
'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
return preg_replace('/([^.a-z0-9]+)/i', '-', $cleaner);
}



/**
* Savoir si le cient est connecté
*
* @link http://crystal-web.org
* @author Christophe BUFFET
* @return bool
* @deprecated utilisé Auth::isAuth()
*/
function is_connected()
{
    return Auth::isAuth();
}



/**
* Supprime des dossiers de façon recurcive
*
* @link http://crystal-web.org
* @author Christophe BUFFET
* @param string $dir
* @return void
*/
function rmdir_recursive($dir)
{
    //Liste le contenu du répertoire dans un tableau
    $dir_content = scandir($dir);
    //Est-ce bien un répertoire?
    if($dir_content !== FALSE){
        //Pour chaque entrée du répertoire
        foreach ($dir_content as $entry)
        {
            //Raccourcis symboliques sous Unix, on passe
            if(!in_array($entry, array('.','..')))
            {
                //On retrouve le chemin par rapport au début
                $entry = $dir . '/' . $entry;
                //Cette entrée n'est pas un dossier: on l'efface
                if(!is_dir($entry)){
                        unlink($entry);
                }
                //Cette entrée est un dossier, on recommence sur ce dossier
                else
                {
                   rmdir_recursive($entry);
                }
            }
        }
    }
    //On a bien effacé toutes les entrées du dossier, on peut à présent l'effacer
    rmdir($dir);
}



/**
* Caractère aléatoire
*
* @link http://crystal-web.org
* @author Christophe BUFFET
* @param int $nb
* @return string
*/
function randCar($nb=10)
{
$random_name=NULL;
// Charactère liste
$list_char = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
// Boucle 
	for($i=0; $i < $nb; $i++)
	{
	// Ajout un caractère de la liste
	/*
	Liste de charactère : $list_char[]
	NB aléatoire de 0 a ? : rand(0,(x))
	Compte tous les éléments du tableau, retire 1 (count compte 1,2,3 | rand 0,1,2,3 comme array)  : count($list_char)-1
	*/
	$random_name.=$list_char[rand(0,(count($list_char)-1))];
	}
return $random_name;
}



/**
* Retire les accents
*
* @link http://crystal-web.org
* @author Christophe BUFFET
* @param string $string
* @return str
*/
function stripAccents($str)
{
$str = htmlentities($str, ENT_NOQUOTES, "UTF-8");
$str = htmlspecialchars_decode($str);
// &eolig;
$str = preg_replace('#\&([A-Za-z]{2})(?:lig)\;#', '\1', $str);
// &aelig;  &AElig; &oelig; &OElig; vers ae  AE oe OE
$str = preg_replace('#\&([A-Za-z])(?:(.*))\;#', '\1', $str);

return $str;
}



/**
* Encodage HEXADECIMAL
*
* @link http://crystal-web.org
* @author Christophe BUFFET
* @param string $bin
* @param string $plus|Caractere devant la valeur HEXADECIMAL
* @return string
*/
function encodeHEX($bin, $plus=NULL) 
{
    $hex = '';
    for($i = 0; $i < strlen($bin); $i++)
    {
        $hex.=$plus.bin2hex($bin[$i]); 
    }
    
    return $hex;
}



/**
* Dencodage HEXADECIMAL
*
* @link http://crystal-web.org
* @author Christophe BUFFET
* @param string $bin
* @param string $moins|Caractere devant la valeur HEXADECIMAL
* @return string
*/
function decodeHEX($hex, $moins="%") 
{
$hex=strtolower($hex);
    if (preg_match("/".$moins."/i", $hex))
    {
    // On supprime les %
    $trans = array($moins => "");
    $hex=strtr($hex, $trans);
    }
$bin="";

    for ($i=0;$i<strlen($hex);$i=$i+2) 
    { 
    $bin .= chr(hexdec(substr ($hex, $i,2))); 
    } 
return $bin; 
}



/**
* Get either a Gravatar URL or complete image tag for a specified email address.
*
* @param string $email The email address
* @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
* @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
* @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
* @param boole $img True to return a complete IMG tag False for just the URL
* @param array $atts Optional, additional key/value attributes to include in the IMG tag
* @return String containing either just a URL or a complete image tag
* @source http://gravatar.com/site/implement/images/php/
*/
function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
$url = 'http://www.gravatar.com/avatar/';
$url .= md5( strtolower( trim( $email ) ) );
$url .= '?s=' . $s . '&amp;d=' . $d . '&amp;r=' . $r;

if ( $img ) {
    $url = '<img src="' . $url . '"';
    foreach ( $atts as $key => $val )
        $url .= ' ' . $key . '="' . $val . '"';
    $url .= ' />';
}
return $url;
}

/**
* Acces aux librairies
*
* @link http://crystal-web.org
* @author Christophe BUFFET
* @param string $lib|country:flag:htmlentitie:fr_regions:fr_departements
* @return array
*/
function library($lib=NULL)
{
$libraryList = array();
$libraryList['country'] = array('Afghanistan', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua And Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia And Herzegovina', 'Botswana', 'Bouvet Island', 'Brazil', 'British Indian Ocean Territory', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Columbia', 'Comoros', 'Congo', 'Cook Islands', 'Costa Rica', 'Cote D\'Ivorie (Ivory Coast)', 'Croatia (Hrvatska)', 'Cuba', 'Cyprus', 'Czech Republic', 'Democratic Republic Of Congo (Zaire)', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands (Malvinas)', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'France, Metropolitan', 'French Guinea', 'French Polynesia', 'French Southern Territories', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Heard And McDonald Islands', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macau', 'Macedonia', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar (Burma)', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'Netherlands Antilles', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'North Korea', 'Northern Mariana Islands', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Reunion', 'Romania', 'Russia', 'Rwanda', 'Saint Helena', 'Saint Kitts And Nevis', 'Saint Lucia', 'Saint Pierre And Miquelon', 'Saint Vincent And The Grenadines', 'San Marino', 'Sao Tome And Principe', 'Saudi Arabia', 'Senegal', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovak Republic', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Georgia And South Sandwich Islands', 'South Korea', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Svalbard And Jan Mayen', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tokelau', 'Tonga', 'Trinidad And Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks And Caicos Islands', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'United States Minor Outlying Islands', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City (Holy See)', 'Venezuela', 'Vietnam', 'Virgin Islands (British)', 'Virgin Islands (US)', 'Wallis And Futuna Islands', 'Western Sahara', 'Western Samoa', 'Yemen', 'Yugoslavia', 'Zambia', 'Zimbabwe'
);
$libraryList['flag'] = array('af'=>'Afghanistan','za'=>'Afrique du Sud','al'=>'Albanie','dz'=>'Algérie','de'=>'Allemagne','as'=>'American Samoa','ad'=>'Andorre','ao'=>'Angola','ai'=>'Anguilla','aq'=>'Antarctique','ag'=>'Antigua et Barbuda','an'=>'Antilles Neerlandaises','sa'=>'Arabie Saoudite','ar'=>'Argentine','am'=>'Arménie','aw'=>'Aruba','ac'=>'Ascension (île)','au'=>'Australie','at'=>'Autriche','az'=>'Azerbaidjan','bs'=>'Bahamas','bh'=>'Bahrein','bd'=>'Bangladesh','bb'=>'Barbade','be'=>'Belgique','bm'=>'Bermudes','bt'=>'Bhoutan','by'=>'Biélorussie','bo'=>'Bolivie','ba'=>'Bosnie Herzégovine','bw'=>'Botswana','bv'=>'Bouvet (île)','bn'=>'Brunei','br'=>'Brésil','bg'=>'Bulgarie','bf'=>'Burkina Faso','bi'=>'Burundi','bz'=>'Bélize','bj'=>'Bénin','kh'=>'Cambodge','cm'=>'Cameroun','ca'=>'Canada','cv'=>'Cap Vert','ky'=>'Caïmanes (îles)','cl'=>'Chili','cn'=>'Chine','cx'=>'Christmas (île)','cy'=>'Chypre','cc'=>'Cocos (Keeling) îles','co'=>'Colombie','km'=>'Comores','ck'=>'Cook (îles)','kp'=>'Corée du nord','kr'=>'Corée du sud','cr'=>'Costa Rica','hr'=>'Croatie','cu'=>'Cuba','ci'=>'Côte d\'Ivoire','dk'=>'Danemark','dj'=>'Djibouti','dm'=>'Dominique','eg'=>'Egypte','ae'=>'Emirats Arabes Unis','ec'=>'Equateur','er'=>'Erythrée','es'=>'Espagne','ee'=>'Estonie','us'=>'Etats-Unis','et'=>'Ethiopie','su'=>'Ex U.R.S.S.','fk'=>'Falkland (Malouines) îles','fo'=>'Faroe (îles)','fj'=>'Fidji','fi'=>'Finlande','fr'=>'France','ga'=>'Gabon','gm'=>'Gambie','gh'=>'Ghana','gi'=>'Gibraltar','gb'=>'Grande Bretagne','gd'=>'Grenade','gl'=>'Groenland','gr'=>'Grèce','gp'=>'Guadeloupe','gu'=>'Guam','gt'=>'Guatemala','gg'=>'Guernsey','gn'=>'Guinée','gq'=>'Guinée Equatoriale','gw'=>'Guinée-Bissau','gy'=>'Guyana','gf'=>'Guyane Française','ge'=>'Géorgie','gs'=>'Géorgie du sud','ht'=>'Haiti','hm'=>'Heard et McDonald (îles)','hn'=>'Honduras','hk'=>'Hong Kong','hu'=>'Hongrie','im'=>'Ile de Man','in'=>'Inde','id'=>'Indonésie','ir'=>'Iran','iq'=>'Iraq','ie'=>'Irlande','is'=>'Islande','il'=>'Israël','it'=>'Italie','jm'=>'Jamaïque','jp'=>'Japon','je'=>'Jersey','jo'=>'Jordanie','kz'=>'Kazakhstan','ke'=>'Kenya','kg'=>'Kirghizistan','ki'=>'Kiribati','kw'=>'Koweït','la'=>'Laos','ls'=>'Lesotho','lv'=>'Lettonie','lb'=>'Liban','lr'=>'Liberia','ly'=>'Libye','li'=>'Liechtenstein','lt'=>'Lituanie','lu'=>'Luxembourg','mo'=>'Macao','mk'=>'Macédoine','mg'=>'Madagascar','my'=>'Malaisie','mw'=>'Malawi','mv'=>'Maldives','ml'=>'Mali','mt'=>'Malte','mp'=>'Mariannes du nord (îles)','ma'=>'Maroc','mh'=>'Marshall (îles)','mq'=>'Martinique','mu'=>'Maurice (île)','mr'=>'Mauritanie','yt'=>'Mayotte','mx'=>'Mexique','fm'=>'Micronésie','md'=>'Moldavie','mc'=>'Monaco','mn'=>'Mongolie','ms'=>'Montserrat','mz'=>'Mozambique','mm'=>'Myanmar','na'=>'Namibie','nr'=>'Nauru','ni'=>'Nicaragua','ne'=>'Niger','ng'=>'Nigéria','nu'=>'Niue','nf'=>'Norfolk (île)','no'=>'Norvège','nc'=>'Nouvelle Calédonie','nz'=>'Nouvelle Zélande','np'=>'Népal','om'=>'Oman','ug'=>'Ouganda','uz'=>'Ouzbékistan','pk'=>'Pakistan','pw'=>'Palau','pa'=>'Panama','pg'=>'Papouasie Nvelle Guinée','py'=>'Paraguay','nl'=>'Pays Bas','ph'=>'Philippines','pn'=>'Pitcairn (île)','pl'=>'Pologne','pf'=>'Polynésie Française','pr'=>'Porto Rico','pt'=>'Portugal','pe'=>'Pérou','qa'=>'Qatar','ro'=>'Roumanie','uk'=>'Royaume Uni','ru'=>'Russie','rw'=>'Rwanda','cf'=>'Rép Centrafricaine','do'=>'Rép Dominicaine','zr'=>'Rép. Dém. du Congo (ex Zaïre)','cd'=>'Rép. du Congo','re'=>'Réunion (île de la)','eh'=>'Sahara Occidental','kn'=>'Saint Kitts et Nevis','sm'=>'Saint-Marin','lc'=>'Sainte Lucie','sb'=>'Salomon (îles)','sv'=>'Salvador','st'=>'Sao Tome et Principe','sw'=>'Serbie','cs'=>'Serbie Montenegro','sc'=>'Seychelles','sl'=>'Sierra Leone','sg'=>'Singapour','sk'=>'Slovaquie','si'=>'Slovénie','so'=>'Somalie','sd'=>'Soudan','lk'=>'Sri Lanka','vc'=>'St Vincent et les Grenadines','sh'=>'St. Hélène','pm'=>'St. Pierre et Miquelon','ch'=>'Suisse','sr'=>'Suriname','se'=>'Suède','sj'=>'Svalbard/Jan Mayen (îles)','sz'=>'Swaziland','sy'=>'Syrie','sn'=>'Sénégal','tj'=>'Tadjikistan','tw'=>'Taiwan','tz'=>'Tanzanie','td'=>'Tchad','cz'=>'Tchéquie','io'=>'Ter. Brit. Océan Indien','tf'=>'Territoires Fr du sud','th'=>'Thailande','tp'=>'Timor Oriental','tg'=>'Togo','tk'=>'Tokelau','to'=>'Tonga','tt'=>'Trinité et Tobago','tn'=>'Tunisie','tm'=>'Turkménistan','tc'=>'Turks et Caïques (îles)','tr'=>'Turquie','tv'=>'Tuvalu','um'=>'US Minor Outlying (îles)','ua'=>'Ukraine','uy'=>'Uruguay','vu'=>'Vanuatu','va'=>'Vatican','ve'=>'Venezuela','vg'=>'Vierges Brit. (îles)','vi'=>'Vierges USA (îles)','vn'=>'Viêt Nam','wf'=>'Wallis et Futuna (îles)','ws'=>'Western Samoa','ye'=>'Yemen','yu'=>'Yugoslavie','zm'=>'Zambie','zw'=>'Zimbabwe');
$libraryList['htmlentitie'] = array('À'=>'&Agrave;','à'=>'&agrave;','Á'=>'&Aacute;','á'=>'&aacute;','Â'=>'&Acirc;','â'=>'&acirc;','Ã'=>'&Atilde;','ã'=>'&atilde;','Ä'=>'&Auml;','ä'=>'&auml;','Å'=>'&Aring;','å'=>'&aring;','Æ'=>'&AElig;','æ'=>'&aelig;','Ç'=>'&Ccedil;','ç'=>'&ccedil;','Ð'=>'&ETH;','ð'=>'&eth;','È'=>'&Egrave;','è'=>'&egrave;','É'=>'&Eacute;','é'=>'&eacute;','Ê'=>'&Ecirc;','ê'=>'&ecirc;','Ë'=>'&Euml;','ë'=>'&euml;','Ì'=>'&Igrave;','ì'=>'&igrave;','Í'=>'&Iacute;','í'=>'&iacute;','Î'=>'&Icirc;','î'=>'&icirc;','Ï'=>'&Iuml;','ï'=>'&iuml;','Ñ'=>'&Ntilde;','ñ'=>'&ntilde;','Ò'=>'&Ograve;','ò'=>'&ograve;','Ó'=>'&Oacute;','ó'=>'&oacute;','Ô'=>'&Ocirc;','ô'=>'&ocirc;','Õ'=>'&Otilde;','õ'=>'&otilde;','Ö'=>'&Ouml;','ö'=>'&ouml;','Ø'=>'&Oslash;','ø'=>'&oslash;','Œ'=>'&OElig;','œ'=>'&oelig;','ß'=>'&szlig;','š'=>'&#353;', 'Š'=>'&#352;', 'Þ'=>'&THORN;','þ'=>'&thorn;','Ù'=>'&Ugrave;','ù'=>'&ugrave;','Ú'=>'&Uacute;','ú'=>'&uacute;','Û'=>'&Ucirc;','û'=>'&ucirc;','Ü'=>'&Uuml;','ü'=>'&uuml;','Ý'=>'&Yacute;','ý'=>'&yacute;','Ÿ'=>'&Yuml;','ÿ'=>'&yuml;','ž' => '&#382;', 'Ž' => '&#381;');
$libraryList['fr_regions'] = array("Alsace","Aquitaine","Auvergne","Bourgogne","Bretagne","Centre","Champagne-Ardenne","Corse","Franche-Comté","Île-de-France","Languedoc-Roussillon","Limousin","Lorraine","Midi-Pyrénées","Nord-Pas-de-Calais","Basse-Normandie","Haute-Normandie","Pays de la Loire","Picardie","Poitou-Charentes","Provence-Alpes-Côte d'Azur","Rhône-Alpes","Guyane","Guadeloupe","Martinique","Réunion");
$libraryList['fr_departements'] = array("01"=>"Ain", "02"=>"Aisne", "03"=>"Allier", "04"=>"Alpes-de-Haute-Provence", "05"=>"Hautes-Alpes", "06"=>"Alpes-Maritimes", "07"=>"Ardèche", "08"=>"Ardennes", "09"=>"Ariège", "10"=>"Aube", "11"=>"Aude", "12"=>"Aveyron", "13"=>"Bouches-du-Rhône", "14"=>"Calvados", "15"=>"Cantal", "16"=>"Charente", "17"=>"Charente-Maritime", "18"=>"Cher", "19"=>"Corrèze", "2A" => "Corse-du-Sud", "2B" => "Haute-Corse", "21"=>"Côte-d'Or", "22"=>"Côtes-d'Armor", "23"=>"Creuse", "24"=>"Dordogne", "25"=>"Doubs", "26"=>"Drôme", "27"=>"Eure", "28"=>"Eure-et-Loir", "29"=>"Finistère", "30"=>"Gard", "31"=>"Haute-Garonne", "32"=>"Gers", "33"=>"Gironde", "34"=>"Hérault", "35"=>"Ille-et-Vilaine", "36"=>"Indre", "37"=>"Indre-et-Loire", "38"=>"Isère", "39"=>"Jura", "40"=>"Landes", "41"=>"Loir-et-Cher", "42"=>"Loire", "43"=>"Haute-Loire", "44"=>"Loire-Atlantique", "45"=>"Loiret", "46"=>"Lot", "47"=>"Lot-et-Garonne", "48"=>"Lozère", "49"=>"Maine-et-Loire", "50"=>"Manche", "51"=>"Marne", "52"=>"Haute-Marne", "53"=>"Mayenne", "54"=>"Meurthe-et-Moselle", "55"=>"Meuse", "56"=>"Morbihan", "57"=>"Moselle", "58"=>"Nièvre", "59"=>"Nord", "60"=>"Oise", "61"=>"Orne", "62"=>"Pas-de-Calais", "63"=>"Puy-de-Dôme", "64"=>"Pyrénées-Atlantiques", "65"=>"Hautes-Pyrénées", "66"=>"Pyrénées-Orientales", "67"=>"Bas-Rhin", "68"=>"Haut-Rhin", "69"=>"Rhône", "70"=>"Haute-Saône", "71"=>"Saône-et-Loire", "72"=>"Sarthe", "73"=>"Savoie", "74"=>"Haute-Savoie", "75"=>"Paris", "76"=>"Seine-Maritime", "77"=>"Seine-et-Marne", "78"=>"Yvelines", "79"=>"Deux-Sèvres", "80"=>"Somme", "81"=>"Tarn", "82"=>"Tarn-et-Garonne", "83"=>"Var", "84"=>"Vaucluse", "85"=>"Vendée", "86"=>"Vienne", "87"=>"Haute-Vienne", "88"=>"Vosges", "89"=>"Yonne", "90"=>"Territoire de Belfort", "91"=>"Essonne", "92"=>"Hauts-de-Seine", "93"=>"Seine-Saint-Denis", "94"=>"Val-de-Marne", "95"=>"Val-d'Oise");

return isSet($libraryList[$lib]) ? $libraryList[$lib] : $libraryList;
}


/* Librairie pays */
$cw_flag = array('af'=>'Afghanistan','za'=>'Afrique du Sud','al'=>'Albanie','dz'=>'Algérie','de'=>'Allemagne','as'=>'American Samoa','ad'=>'Andorre','ao'=>'Angola','ai'=>'Anguilla','aq'=>'Antarctique','ag'=>'Antigua et Barbuda','an'=>'Antilles Neerlandaises','sa'=>'Arabie Saoudite','ar'=>'Argentine','am'=>'Arménie','aw'=>'Aruba','ac'=>'Ascension (île)','au'=>'Australie','at'=>'Autriche','az'=>'Azerbaidjan','bs'=>'Bahamas','bh'=>'Bahrein','bd'=>'Bangladesh','bb'=>'Barbade','be'=>'Belgique','bm'=>'Bermudes','bt'=>'Bhoutan','by'=>'Biélorussie','bo'=>'Bolivie','ba'=>'Bosnie Herzégovine','bw'=>'Botswana','bv'=>'Bouvet (île)','bn'=>'Brunei','br'=>'Brésil','bg'=>'Bulgarie','bf'=>'Burkina Faso','bi'=>'Burundi','bz'=>'Bélize','bj'=>'Bénin','kh'=>'Cambodge','cm'=>'Cameroun','ca'=>'Canada','cv'=>'Cap Vert','ky'=>'Caïmanes (îles)','cl'=>'Chili','cn'=>'Chine','cx'=>'Christmas (île)','cy'=>'Chypre','cc'=>'Cocos (Keeling) îles','co'=>'Colombie','km'=>'Comores','ck'=>'Cook (îles)','kp'=>'Corée du nord','kr'=>'Corée du sud','cr'=>'Costa Rica','hr'=>'Croatie','cu'=>'Cuba','ci'=>'Côte d\'Ivoire','dk'=>'Danemark','dj'=>'Djibouti','dm'=>'Dominique','eg'=>'Egypte','ae'=>'Emirats Arabes Unis','ec'=>'Equateur','er'=>'Erythrée','es'=>'Espagne','ee'=>'Estonie','us'=>'Etats-Unis','et'=>'Ethiopie','su'=>'Ex U.R.S.S.','fk'=>'Falkland (Malouines) îles','fo'=>'Faroe (îles)','fj'=>'Fidji','fi'=>'Finlande','fr'=>'France','ga'=>'Gabon','gm'=>'Gambie','gh'=>'Ghana','gi'=>'Gibraltar','gb'=>'Grande Bretagne','gd'=>'Grenade','gl'=>'Groenland','gr'=>'Grèce','gp'=>'Guadeloupe','gu'=>'Guam','gt'=>'Guatemala','gg'=>'Guernsey','gn'=>'Guinée','gq'=>'Guinée Equatoriale','gw'=>'Guinée-Bissau','gy'=>'Guyana','gf'=>'Guyane Française','ge'=>'Géorgie','gs'=>'Géorgie du sud','ht'=>'Haiti','hm'=>'Heard et McDonald (îles)','hn'=>'Honduras','hk'=>'Hong Kong','hu'=>'Hongrie','im'=>'Ile de Man','in'=>'Inde','id'=>'Indonésie','ir'=>'Iran','iq'=>'Iraq','ie'=>'Irlande','is'=>'Islande','il'=>'Israël','it'=>'Italie','jm'=>'Jamaïque','jp'=>'Japon','je'=>'Jersey','jo'=>'Jordanie','kz'=>'Kazakhstan','ke'=>'Kenya','kg'=>'Kirghizistan','ki'=>'Kiribati','kw'=>'Koweït','la'=>'Laos','ls'=>'Lesotho','lv'=>'Lettonie','lb'=>'Liban','lr'=>'Liberia','ly'=>'Libye','li'=>'Liechtenstein','lt'=>'Lituanie','lu'=>'Luxembourg','mo'=>'Macao','mk'=>'Macédoine','mg'=>'Madagascar','my'=>'Malaisie','mw'=>'Malawi','mv'=>'Maldives','ml'=>'Mali','mt'=>'Malte','mp'=>'Mariannes du nord (îles)','ma'=>'Maroc','mh'=>'Marshall (îles)','mq'=>'Martinique','mu'=>'Maurice (île)','mr'=>'Mauritanie','yt'=>'Mayotte','mx'=>'Mexique','fm'=>'Micronésie','md'=>'Moldavie','mc'=>'Monaco','mn'=>'Mongolie','ms'=>'Montserrat','mz'=>'Mozambique','mm'=>'Myanmar','na'=>'Namibie','nr'=>'Nauru','ni'=>'Nicaragua','ne'=>'Niger','ng'=>'Nigéria','nu'=>'Niue','nf'=>'Norfolk (île)','no'=>'Norvège','nc'=>'Nouvelle Calédonie','nz'=>'Nouvelle Zélande','np'=>'Népal','om'=>'Oman','ug'=>'Ouganda','uz'=>'Ouzbékistan','pk'=>'Pakistan','pw'=>'Palau','pa'=>'Panama','pg'=>'Papouasie Nvelle Guinée','py'=>'Paraguay','nl'=>'Pays Bas','ph'=>'Philippines','pn'=>'Pitcairn (île)','pl'=>'Pologne','pf'=>'Polynésie Française','pr'=>'Porto Rico','pt'=>'Portugal','pe'=>'Pérou','qa'=>'Qatar','ro'=>'Roumanie','uk'=>'Royaume Uni','ru'=>'Russie','rw'=>'Rwanda','cf'=>'Rép Centrafricaine','do'=>'Rép Dominicaine','zr'=>'Rép. Dém. du Congo (ex Zaïre)','cd'=>'Rép. du Congo','re'=>'Réunion (île de la)','eh'=>'Sahara Occidental','kn'=>'Saint Kitts et Nevis','sm'=>'Saint-Marin','lc'=>'Sainte Lucie','sb'=>'Salomon (îles)','sv'=>'Salvador','st'=>'Sao Tome et Principe','sw'=>'Serbie','cs'=>'Serbie Montenegro','sc'=>'Seychelles','sl'=>'Sierra Leone','sg'=>'Singapour','sk'=>'Slovaquie','si'=>'Slovénie','so'=>'Somalie','sd'=>'Soudan','lk'=>'Sri Lanka','vc'=>'St Vincent et les Grenadines','sh'=>'St. Hélène','pm'=>'St. Pierre et Miquelon','ch'=>'Suisse','sr'=>'Suriname','se'=>'Suède','sj'=>'Svalbard/Jan Mayen (îles)','sz'=>'Swaziland','sy'=>'Syrie','sn'=>'Sénégal','tj'=>'Tadjikistan','tw'=>'Taiwan','tz'=>'Tanzanie','td'=>'Tchad','cz'=>'Tchéquie','io'=>'Ter. Brit. Océan Indien','tf'=>'Territoires Fr du sud','th'=>'Thailande','tp'=>'Timor Oriental','tg'=>'Togo','tk'=>'Tokelau','to'=>'Tonga','tt'=>'Trinité et Tobago','tn'=>'Tunisie','tm'=>'Turkménistan','tc'=>'Turks et Caïques (îles)','tr'=>'Turquie','tv'=>'Tuvalu','um'=>'US Minor Outlying (îles)','ua'=>'Ukraine','uy'=>'Uruguay','vu'=>'Vanuatu','va'=>'Vatican','ve'=>'Venezuela','vg'=>'Vierges Brit. (îles)','vi'=>'Vierges USA (îles)','vn'=>'Viêt Nam','wf'=>'Wallis et Futuna (îles)','ws'=>'Western Samoa','ye'=>'Yemen','yu'=>'Yugoslavie','zm'=>'Zambie','zw'=>'Zimbabwe');
$cw_htmlent=array('À'=>'&Agrave;','à'=>'&agrave;','Á'=>'&Aacute;','á'=>'&aacute;','Â'=>'&Acirc;','â'=>'&acirc;','Ã'=>'&Atilde;','ã'=>'&atilde;','Ä'=>'&Auml;','ä'=>'&auml;','Å'=>'&Aring;','å'=>'&aring;','Æ'=>'&AElig;','æ'=>'&aelig;','Ç'=>'&Ccedil;','ç'=>'&ccedil;','Ð'=>'&ETH;','ð'=>'&eth;','È'=>'&Egrave;','è'=>'&egrave;','É'=>'&Eacute;','é'=>'&eacute;','Ê'=>'&Ecirc;','ê'=>'&ecirc;','Ë'=>'&Euml;','ë'=>'&euml;','Ì'=>'&Igrave;','ì'=>'&igrave;','Í'=>'&Iacute;','í'=>'&iacute;','Î'=>'&Icirc;','î'=>'&icirc;','Ï'=>'&Iuml;','ï'=>'&iuml;','Ñ'=>'&Ntilde;','ñ'=>'&ntilde;','Ò'=>'&Ograve;','ò'=>'&ograve;','Ó'=>'&Oacute;','ó'=>'&oacute;','Ô'=>'&Ocirc;','ô'=>'&ocirc;','Õ'=>'&Otilde;','õ'=>'&otilde;','Ö'=>'&Ouml;','ö'=>'&ouml;','Ø'=>'&Oslash;','ø'=>'&oslash;','Œ'=>'&OElig;','œ'=>'&oelig;','ß'=>'&szlig;','š'=>'&#353;', 'Š'=>'&#352;', 'Þ'=>'&THORN;','þ'=>'&thorn;','Ù'=>'&Ugrave;','ù'=>'&ugrave;','Ú'=>'&Uacute;','ú'=>'&uacute;','Û'=>'&Ucirc;','û'=>'&ucirc;','Ü'=>'&Uuml;','ü'=>'&uuml;','Ý'=>'&Yacute;','ý'=>'&yacute;','Ÿ'=>'&Yuml;','ÿ'=>'&yuml;','ž' => '&#382;', 'Ž' => '&#381;');
?>