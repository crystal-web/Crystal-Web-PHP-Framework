<?php
function getmicrotime()
{
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}

function convert($size, $precision = 2)
{
if (!is_numeric($size))
	return '?';
$notation = 1024;
// Fixes large disk size overflow issue
// Found at http://www.php.net/manual/en/function.disk-free-space.php#81207
$types = array('B', 'KB', 'MB', 'GB', 'TB');
$types_i = array('B', 'KiB', 'MiB', 'GiB', 'TiB');
for($i = 0; $size >= $notation && $i < (count($types) -1 ); $size /= $notation, $i++);
return(round($size, $precision) . ' ' . ($notation == 1000 ? $types[$i] : $types_i[$i]));
}

function alerte($msg, $echo = false)
{
$box = '<div class="MSGbox MSGalerte"><p>' . $msg . '</p></div>';
if ($echo == true) { echo $box; } else { return $box; }
}

function astuce($msg, $echo = false)
{ 
$box = '<div class="MSGbox MSGastuce"><p>' . $msg . '</p></div>';
if ($echo == true) { echo $box; } else { return $box; }
}

function beta($msg, $echo = false)
{
$box = '<div class="MSGbox MSGbeta"><p>' . $msg . '</p></div>';
if ($echo == true) { echo $box; } else { return $box; }
}

function info($msg, $echo = false)
{
$box = '<div class="MSGbox MSGinfo"><p>' . $msg . '</p></div>';
if ($echo == true) { echo $box; } else { return $box; }
}

function note($msg, $echo = false)
{
$box = '<div class="MSGbox MSGnote"><p>' . $msg . '</p></div>';
if ($echo == true) { echo $box; } else { return $box; }
}

function valide($msg, $echo = false)
{
$box = '<div class="MSGbox MSGvalide"><p>' . $msg . '</p></div>';
if ($echo == true) { echo $box; } else { return $box; }
}
function db($var)
{
echo '<p><strong>Debug:</strong><pre class="code">';
var_dump($var);
echo '</pre></p>';
}


function isURL($url)
{
return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

/**
 * Parcourir un dossier et récupérer le contenu de chaque fichier
 * par Jay Salvat - http://blog.jaysalvat.com/article/zipper-des-dossiers-a-la-volee-avec-php */
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

/***
Debuging visuel
***/
function debug ($text=null, $return=false)
{

$var = '<p><strong>Crystal-Web Debug Tools:</strong><pre class="code">';
	if (is_array($text) || is_object($text)) {
	$var .= print_r($text, true);
	} elseif($text==null) {
	$var .= print_r($GLOBALS, true);
	}
	else {
	$var .= $text;
	}
$var .= '</pre></p>';
if ($return == false) {echo $var;}else{return $var;}
}
/***
END Debuging visuel
***/


/***
Protection des variables POST & GET
***/
function protectMethod($data)
{

if (is_array($data)) {
	foreach ($data AS $cle => $valeur) {
		if (is_array($data)) {
			$data[$cle] = protectMethod($data[$cle]);
		} else {
			if (is_numeric($valeur)) {
				//cast pour les nombres
				$data[$cle] = intval($valeur);
			} else {
				//protection des chaines
				$data[$cle] = htmlentities($valeur);
			}
		}
	}
		if (isSet($_SERVER['HTTP_REFERER']))
		{
			if (!preg_match('#'.$_SERVER['SERVER_NAME'].'#', $_SERVER['HTTP_REFERER']))
			{
			$data['__internal'] = false;
			$data['__external'] = true;
			}
			else
			{
			$data['__internal'] = true;
			$data['__external'] = false;
			}
		}
		else
		{
			$data['__direct'] = true;
		}

} else {
	$data = htmlentities($data);
}
return $data;
}
if (!empty($_GET))	$_GET = protectMethod($_GET);
if (!empty($_POST))	$_POST = protectMethod($_POST);
if (!empty($_COOKIE))	$_COOKIE = protectMethod($_COOKIE);
// //*/

/*if(get_magic_quotes_gpc()) {
        $_POST = array_map('htmlentities', $_POST);
        $_GET = array_map('htmlentities', $_GET);
        $_COOKIE = array_map('htmlentities', $_COOKIE);
}//*/

/***
END Protection des variables POST & GET
***/

/* $errno : type de l'erreur
$errstr : message d'erreur
$errfile : fichier correspondant à l'erreur
$errline : ligne correspondante à l'erreur */
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

if (__DEV_MODE==true) {echo nl2br('<div class="MSGbox MSGalerte"><p>'.$erreur.'</p></div>');}
}
set_error_handler('erreur_alerte');

/***
Detection de Internet Explorer 
***/
function is_ie() {
$user_agent = (isSet($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT']: '';
$match=preg_match('/msie ([0-9]\.[0-9])/',strtolower($user_agent),$reg);
if($match==0) return false;
else return floatval($reg[1]);
}
/***
END Detection de Internet Explorer 
***/

/***
Fin des fonctions des base
***/


// Réduit la chaine si elle est trop longue.
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

/***
Création d'un arbre ul li 
***/
function build_tree($tree) 
{
$listgrp='';
$result = '<ul>';
foreach ($tree as $key => $valeur) 
{
	//Si un fils existe
	if (is_array($tree[$key]))
	{
	//Nouveau groupe
	$result .= '<li class="closed">'.$key;
	$result .= build_tree($tree[$key]);
	}
	else
	{
	$result .= '<li class="closed"><a href="' . $key . '">'.$valeur.'</a>';
	}
}
$result .= '</ul>';
return $result;
}
/***
END Création d'un arbre ul li
***/

/***
Suppréssion des espaces inutiles
***/
function stripspace($str){
$str = trim($str);
$str = preg_replace ("/\s+/", " ", $str);
return $str;
}
/***
END Suppréssion des espaces inutiles
***/

/***
Math
***/

function is_paire($nombre){
return ($nombre%2 == 0) ? true : false;
}






/** parse php modules from phpinfo by code at adspeed dot com **/ 
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
/** get a module setting */ 
function getModuleSetting($pModuleName,$pSetting) { 
 $vModules = parsePHPModules(); 
 return $vModules[$pModuleName][$pSetting]; 
} 




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


/* Les dates */

/* Fonction pour afficher les jours restants (ajouté par j-c)
@ www.dyraz.com
*/
function ecartdate($fin)
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
	 if ($time > 0) {
		  $when = "il y a";
	 } else if ($time < 0) {
		  $when = "dans environ";
	 } else {
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

		foreach ($times as $seconds => $unit) {
			  // Calcule le delta entre le temps et l'unité donnée
			  $delta = round($time / $seconds);

			  // Si le delta est supérieur à 1
			  if ($delta >= 1) {
					// L'unité est au singulier ou au pluriel ?
					if ($delta == 1) {
						 $unit = str_replace('{s}', '', $unit);
					} else {
						 $unit = str_replace('{s}', 's', $unit);
					}
					// Retourne la chaine adéquate
					return $when." ".$delta." ".$unit;
			  }
		}
	}
	
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
function url($url=null){

if (empty($url) or $url=='index.php')
{
return __CW_PATH;
}
$url = stripAccents(html_entity_decode($url));
	if (REWIRTE_URL==false)
	{
	$url = preg_replace('/ /','-',$url);
	return __CW_PATH.'/'.$url;
	}
	elseif (REWIRTE_URL==true)
	{
	$patterns = array();		/* to */	$replacements = array();
	$patterns[0] = '/&amp;/';	/* to */	$replacements[0] = '&';
	$patterns[1] = '/index\.php/';	/* to */	$replacements[1] = '';
	$patterns[2] = '/\?/';	/* to */	$replacements[2] = '';
	$patterns[3] = '/ /';	/* to */	$replacements[3] = '-';
	$patterns[4] = '/_/';	/* to */	$replacements[4] = '-';
	$patterns[5] = '/=/';	/* to */	$replacements[5] = '_';
	$prepare=preg_replace($patterns,$replacements,$url);
	// On obtiens module_forum&action_lire&page_2&titre_un-super-coup
	$explode_me=explode("&", $prepare);
	// Array ( [0] => module_forum [1] => action_lire [2] => page_2 [3] => titre_un-super-coup)

	// Initialise
	$module=null;
	$action=null;
	$get=null;
	$nb_act=0;
	foreach ($explode_me as $key => $value){
	$next=explode("_", $value);
		if ($next[0] == 'module'){
		$module=$next[1].'/';
		$nb_act++;
		}
		elseif ($next[0] == 'action'){
		$action=$next[1].'/';
		$nb_act++;
		}
		elseif ($next[0] != 'action' and $next[0] != 'module' and array_key_exists(1, $next)){
		$get.=$next[0].'_'.cleanerUrl($next[1]).'/';
		//$get.=$next[0].'_'.$next[1].'/';
		$nb_act++;
		}
		elseif ($next[0] != 'action' and $next[0] != 'module' and !array_key_exists(1, $next)){
		$get.=$next[0].'/';
		}
	}
	$rewirte_url=$module.$action.$get;

	
		/*if ($nb_act>3){
		$rewirte_url=substr_replace($rewirte_url,"",-1).'.htm';
		}*/
	}

return __CW_PATH.'/'.$rewirte_url;
}

function is_connected(){
	if ($_SESSION['user']['power_level'] < 2){
	return false;
	}
	else
	{
	return true;
	}
}

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
			if(!in_array($entry, array('.','..'))){
				//On retrouve le chemin par rapport au début
				$entry = $dir . '/' . $entry;
				//Cette entrée n'est pas un dossier: on l'efface
				if(!is_dir($entry)){
					unlink($entry);
				}
				//Cette entrée est un dossier, on recommence sur ce dossier
				else{
					rmdir_recursive($entry);
				}
			}
		}
	}
	//On a bien effacé toutes les entrées du dossier, on peut à présent l'effacer
	rmdir($dir);
}


/***
Caractère aléatoire
***/
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
/***
END Caractère aléatoire
***/


function stripAccents($string){
	return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ',
'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
}

/***
Encodage HEX
***/ 
function encodeHEX($bin, $plus=NULL) 
{
	$hex = '';
    for($i = 0; $i < strlen($bin); $i++)
		$hex.=$plus.bin2hex($bin[$i]); 
	return $hex;
}
/***
END Encodage HEX
***/

/***
Decodage HEX
***/
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
/***
END Decodage HEX
***/


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

/* Librairie pays */
$cw_flag=array('af'=>'Afghanistan',
'za'=>'Afrique du Sud',
'al'=>'Albanie',
'dz'=>'Algérie',
'de'=>'Allemagne',
'as'=>'American Samoa',
'ad'=>'Andorre',
'ao'=>'Angola',
'ai'=>'Anguilla',
'aq'=>'Antarctique',
'ag'=>'Antigua et Barbuda',
'an'=>'Antilles Neerlandaises',
'sa'=>'Arabie Saoudite',
'ar'=>'Argentine',
'am'=>'Arménie',
'aw'=>'Aruba',
'ac'=>'Ascension (île)',
'au'=>'Australie',
'at'=>'Autriche',
'az'=>'Azerbaidjan',
'bs'=>'Bahamas',
'bh'=>'Bahrein',
'bd'=>'Bangladesh',
'bb'=>'Barbade',
'be'=>'Belgique',
'bm'=>'Bermudes',
'bt'=>'Bhoutan',
'by'=>'Biélorussie',
'bo'=>'Bolivie',
'ba'=>'Bosnie Herzégovine',
'bw'=>'Botswana',
'bv'=>'Bouvet (île)',
'bn'=>'Brunei',
'br'=>'Brésil',
'bg'=>'Bulgarie',
'bf'=>'Burkina Faso',
'bi'=>'Burundi',
'bz'=>'Bélize',
'bj'=>'Bénin',
'kh'=>'Cambodge',
'cm'=>'Cameroun',
'ca'=>'Canada',
'cv'=>'Cap Vert',
'ky'=>'Caïmanes (îles)',
'cl'=>'Chili',
'cn'=>'Chine',
'cx'=>'Christmas (île)',
'cy'=>'Chypre',
'cc'=>'Cocos (Keeling) îles',
'co'=>'Colombie',
'km'=>'Comores',
'ck'=>'Cook (îles)',
'kp'=>'Corée du nord',
'kr'=>'Corée du sud',
'cr'=>'Costa Rica',
'hr'=>'Croatie',
'cu'=>'Cuba',
'ci'=>'Côte d\'Ivoire',
'dk'=>'Danemark',
'dj'=>'Djibouti',
'dm'=>'Dominique',
'eg'=>'Egypte',
'ae'=>'Emirats Arabes Unis',
'ec'=>'Equateur',
'er'=>'Erythrée',
'es'=>'Espagne',
'ee'=>'Estonie',
'us'=>'Etats-Unis',
'et'=>'Ethiopie',
'su'=>'Ex U.R.S.S.',
'fk'=>'Falkland (Malouines) îles',
'fo'=>'Faroe (îles)',
'fj'=>'Fidji',
'fi'=>'Finlande',
'fr'=>'France',
'ga'=>'Gabon',
'gm'=>'Gambie',
'gh'=>'Ghana',
'gi'=>'Gibraltar',
'gb'=>'Grande Bretagne',
'gd'=>'Grenade',
'gl'=>'Groenland',
'gr'=>'Grèce',
'gp'=>'Guadeloupe',
'gu'=>'Guam',
'gt'=>'Guatemala',
'gg'=>'Guernsey',
'gn'=>'Guinée',
'gq'=>'Guinée Equatoriale',
'gw'=>'Guinée-Bissau',
'gy'=>'Guyana',
'gf'=>'Guyane Française',
'ge'=>'Géorgie',
'gs'=>'Géorgie du sud',
'ht'=>'Haiti',
'hm'=>'Heard et McDonald (îles)',
'hn'=>'Honduras',
'hk'=>'Hong Kong',
'hu'=>'Hongrie',
'im'=>'Ile de Man',
'in'=>'Inde',
'id'=>'Indonésie',
'ir'=>'Iran',
'iq'=>'Iraq',
'ie'=>'Irlande',
'is'=>'Islande',
'il'=>'Israël',
'it'=>'Italie',
'jm'=>'Jamaïque',
'jp'=>'Japon',
'je'=>'Jersey',
'jo'=>'Jordanie',
'kz'=>'Kazakhstan',
'ke'=>'Kenya',
'kg'=>'Kirghizistan',
'ki'=>'Kiribati',
'kw'=>'Koweït',
'la'=>'Laos',
'ls'=>'Lesotho',
'lv'=>'Lettonie',
'lb'=>'Liban',
'lr'=>'Liberia',
'ly'=>'Libye',
'li'=>'Liechtenstein',
'lt'=>'Lituanie',
'lu'=>'Luxembourg',
'mo'=>'Macao',
'mk'=>'Macédoine',
'mg'=>'Madagascar',
'my'=>'Malaisie',
'mw'=>'Malawi',
'mv'=>'Maldives',
'ml'=>'Mali',
'mt'=>'Malte',
'mp'=>'Mariannes du nord (îles)',
'ma'=>'Maroc',
'mh'=>'Marshall (îles)',
'mq'=>'Martinique',
'mu'=>'Maurice (île)',
'mr'=>'Mauritanie',
'yt'=>'Mayotte',
'mx'=>'Mexique',
'fm'=>'Micronésie',
'md'=>'Moldavie',
'mc'=>'Monaco',
'mn'=>'Mongolie',
'ms'=>'Montserrat',
'mz'=>'Mozambique',
'mm'=>'Myanmar',
'na'=>'Namibie',
'nr'=>'Nauru',
'ni'=>'Nicaragua',
'ne'=>'Niger',
'ng'=>'Nigéria',
'nu'=>'Niue',
'nf'=>'Norfolk (île)',
'no'=>'Norvège',
'nc'=>'Nouvelle Calédonie',
'nz'=>'Nouvelle Zélande',
'np'=>'Népal',
'om'=>'Oman',
'ug'=>'Ouganda',
'uz'=>'Ouzbékistan',
'pk'=>'Pakistan',
'pw'=>'Palau',
'pa'=>'Panama',
'pg'=>'Papouasie Nvelle Guinée',
'py'=>'Paraguay',
'nl'=>'Pays Bas',
'ph'=>'Philippines',
'pn'=>'Pitcairn (île)',
'pl'=>'Pologne',
'pf'=>'Polynésie Française',
'pr'=>'Porto Rico',
'pt'=>'Portugal',
'pe'=>'Pérou',
'qa'=>'Qatar',
'ro'=>'Roumanie',
'uk'=>'Royaume Uni',
'ru'=>'Russie',
'rw'=>'Rwanda',
'cf'=>'Rép Centrafricaine',
'do'=>'Rép Dominicaine',
'zr'=>'Rép. Dém. du Congo (ex Zaïre)',
'cd'=>'Rép. du Congo',
're'=>'Réunion (île de la)',
'eh'=>'Sahara Occidental',
'kn'=>'Saint Kitts et Nevis',
'sm'=>'Saint-Marin',
'lc'=>'Sainte Lucie',
'sb'=>'Salomon (îles)',
'sv'=>'Salvador',
'st'=>'Sao Tome et Principe',
'sw'=>'Serbie',
'cs'=>'Serbie Montenegro',
'sc'=>'Seychelles',
'sl'=>'Sierra Leone',
'sg'=>'Singapour',
'sk'=>'Slovaquie',
'si'=>'Slovénie',
'so'=>'Somalie',
'sd'=>'Soudan',
'lk'=>'Sri Lanka',
'vc'=>'St Vincent et les Grenadines',
'sh'=>'St. Hélène',
'pm'=>'St. Pierre et Miquelon',
'ch'=>'Suisse',
'sr'=>'Suriname',
'se'=>'Suède',
'sj'=>'Svalbard/Jan Mayen (îles)',
'sz'=>'Swaziland',
'sy'=>'Syrie',
'sn'=>'Sénégal',
'tj'=>'Tadjikistan',
'tw'=>'Taiwan',
'tz'=>'Tanzanie',
'td'=>'Tchad',
'cz'=>'Tchéquie',
'io'=>'Ter. Brit. Océan Indien',
'tf'=>'Territoires Fr du sud',
'th'=>'Thailande',
'tp'=>'Timor Oriental',
'tg'=>'Togo',
'tk'=>'Tokelau',
'to'=>'Tonga',
'tt'=>'Trinité et Tobago',
'tn'=>'Tunisie',
'tm'=>'Turkménistan',
'tc'=>'Turks et Caïques (îles)',
'tr'=>'Turquie',
'tv'=>'Tuvalu',
'um'=>'US Minor Outlying (îles)',
'ua'=>'Ukraine',
'uy'=>'Uruguay',
'vu'=>'Vanuatu',
'va'=>'Vatican',
've'=>'Venezuela',
'vg'=>'Vierges Brit. (îles)',
'vi'=>'Vierges USA (îles)',
'vn'=>'Viêt Nam',
'wf'=>'Wallis et Futuna (îles)',
'ws'=>'Western Samoa',
'ye'=>'Yemen',
'yu'=>'Yugoslavie',
'zm'=>'Zambie',
'zw'=>'Zimbabwe'
);


$cw_htmlent=array('À'=>'&Agrave;',
'à'=>'&agrave;',
'Á'=>'&Aacute;',
'á'=>'&aacute;',
'Â'=>'&Acirc;',
'â'=>'&acirc;',
'Ã'=>'&Atilde;',
'ã'=>'&atilde;',
'Ä'=>'&Auml;',
'ä'=>'&auml;',
'Å'=>'&Aring;',
'å'=>'&aring;',
'Æ'=>'&AElig;',
'æ'=>'&aelig;',
'Ç'=>'&Ccedil;',
'ç'=>'&ccedil;',
'Ð'=>'&ETH;',
'ð'=>'&eth;',
'È'=>'&Egrave;',
'è'=>'&egrave;',
'É'=>'&Eacute;',
'é'=>'&eacute;',
'Ê'=>'&Ecirc;',
'ê'=>'&ecirc;',
'Ë'=>'&Euml;',
'ë'=>'&euml;',
'Ì'=>'&Igrave;',
'ì'=>'&igrave;',
'Í'=>'&Iacute;',
'í'=>'&iacute;',
'Î'=>'&Icirc;',
'î'=>'&icirc;',
'Ï'=>'&Iuml;',
'ï'=>'&iuml;',
'Ñ'=>'&Ntilde;',
'ñ'=>'&ntilde;',
'Ò'=>'&Ograve;',
'ò'=>'&ograve;',
'Ó'=>'&Oacute;',
'ó'=>'&oacute;',
'Ô'=>'&Ocirc;',
'ô'=>'&ocirc;',
'Õ'=>'&Otilde;',
'õ'=>'&otilde;',
'Ö'=>'&Ouml;',
'ö'=>'&ouml;',
'Ø'=>'&Oslash;',
'ø'=>'&oslash;',
'Œ'=>'&OElig;',
'œ'=>'&oelig;',
'ß'=>'&szlig;',
'š'=>'&#353;', 
'Š'=>'&#352;', 
'Þ'=>'&THORN;',
'þ'=>'&thorn;',
'Ù'=>'&Ugrave;',
'ù'=>'&ugrave;',
'Ú'=>'&Uacute;',
'ú'=>'&uacute;',
'Û'=>'&Ucirc;',
'û'=>'&ucirc;',
'Ü'=>'&Uuml;',
'ü'=>'&uuml;',
'Ý'=>'&Yacute;',
'ý'=>'&yacute;',
'Ÿ'=>'&Yuml;',
'ÿ'=>'&yuml;',
'ž' => '&#382;', 
'Ž' => '&#381;'
);
?>