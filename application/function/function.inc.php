<?php
/*##################################################
 *                             function.inc.php
 *                            -------------------
 *   begin                : 2012-03-08
 *   copyright            : (C) 2012 DevPHP
 *   email                : developpeur@crystal-web.org
 *
 *
###################################################
 *
 *   This program is free softwar		echo nl2br();e; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

/**
 * @obsolete
 */
function str2slug($str)
{
	return clean($str, 'slug');
} 
  
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

$filePathLibs = __APP_PATH . DS . 'libs' . DS . $class_name.'.class.php';
$filePathModel = __APP_PATH . DS . 'model' . DS . $class_name.'.php';
$filePathFramework = __APP_PATH . DS . 'framework' . DS . $class_name . '.php';
    if (!file_exists($filePathLibs))
    {
    	if (!file_exists($filePathModel))
		{
			if (!file_exists($filePathFramework))
			{
				throw new Exception('Class file not exists <strong>'.$class_name.'</strong>');
			}
			else
			{
				require_once ($filePathFramework);
			}
		}
		else
		{
			require_once ($filePathModel);
		}
    }
	else
	{
		require_once ($filePathLibs);
	}
}


/**
 * 
 * Chargement d'une fonction du dossier function
 * @param string $function
 */
function loadLibrary($libraryName){
$file = __APP_PATH . DS . 'function' . DS . $libraryName;
$file .= '.php';
    if (!file_exists($file))
    {
		throw new Exception('Library file not exists <strong>'.$libraryName.'</strong>');
    }
	
	require_once $file;
}
function loadFunction($function) { loadLibrary($function); }


/**
 * 
 * Chargement d'un model 
 * @param string $name
 * @throws Exception
 */
function loadModel($name)
    {
    $name = $name.'Model';
    // L'endroit ou le model est chargé
    $file = __APP_PATH . DS . 'model' . DS . $name . '.php';
        if (!file_exists($file))
        {
			throw new Exception('Model file not exists <strong>'.$name.'</strong>');
        }

		require_once $file;
	    return new $name();
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
$libraryList['country'] = array('Afghanistan', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua And Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia And Herzegovina', 'Botswana', 'Bouvet Island', 'Brazil', 'British Indian Ocean Territory', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Columbia', 'Comoros', 'Congo', 'Cook Islands', 'Costa Rica', 'Cote D\'Ivorie (Ivory Coast)', 'Croatia (Hrvatska)', 'Cuba', 'Cyprus', 'Czech Republic', 'Democratic Republic Of Congo (Zaire)', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands (Malvinas)', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'France, Metropolitan', 'French Guinea', 'French Polynesia', 'French Southern Territories', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Heard And McDonald Islands', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macau', 'Macedonia', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar (Burma)', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'Netherlands Antilles', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'North Korea', 'Northern Mariana Islands', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Reunion', 'Romania', 'Russia', 'Rwanda', 'Saint Helena', 'Saint Kitts And Nevis', 'Saint Lucia', 'Saint Pierre And Miquelon', 'Saint Vincent And The Grenadines', 'San Marino', 'Sao Tome And Principe', 'Saudi Arabia', 'Senegal', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovak Republic', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Georgia And South Sandwich Islands', 'South Korea', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Svalbard And Jan Mayen', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tokelau', 'Tonga', 'Trinidad And Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks And Caicos Islands', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'United States Minor Outlying Islands', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City (Holy See)', 'Venezuela', 'Vietnam', 'Virgin Islands (British)', 'Virgin Islands (US)', 'Wallis And Futuna Islands', 'Western Sahara', 'Western Samoa', 'Yemen', 'Yugoslavia', 'Zambia', 'Zimbabwe');
$libraryList['flag'] = array('af'=>'Afghanistan','za'=>'Afrique du Sud','al'=>'Albanie','dz'=>'Algérie','de'=>'Allemagne','as'=>'American Samoa','ad'=>'Andorre','ao'=>'Angola','ai'=>'Anguilla','aq'=>'Antarctique','ag'=>'Antigua et Barbuda','an'=>'Antilles Neerlandaises','sa'=>'Arabie Saoudite','ar'=>'Argentine','am'=>'Arménie','aw'=>'Aruba','ac'=>'Ascension (île)','au'=>'Australie','at'=>'Autriche','az'=>'Azerbaidjan','bs'=>'Bahamas','bh'=>'Bahrein','bd'=>'Bangladesh','bb'=>'Barbade','be'=>'Belgique','bm'=>'Bermudes','bt'=>'Bhoutan','by'=>'Biélorussie','bo'=>'Bolivie','ba'=>'Bosnie Herzégovine','bw'=>'Botswana','bv'=>'Bouvet (île)','bn'=>'Brunei','br'=>'Brésil','bg'=>'Bulgarie','bf'=>'Burkina Faso','bi'=>'Burundi','bz'=>'Bélize','bj'=>'Bénin','kh'=>'Cambodge','cm'=>'Cameroun','ca'=>'Canada','cv'=>'Cap Vert','ky'=>'Caïmanes (îles)','cl'=>'Chili','cn'=>'Chine','cx'=>'Christmas (île)','cy'=>'Chypre','cc'=>'Cocos (Keeling) îles','co'=>'Colombie','km'=>'Comores','ck'=>'Cook (îles)','kp'=>'Corée du nord','kr'=>'Corée du sud','cr'=>'Costa Rica','hr'=>'Croatie','cu'=>'Cuba','ci'=>'Côte d\'Ivoire','dk'=>'Danemark','dj'=>'Djibouti','dm'=>'Dominique','eg'=>'Egypte','ae'=>'Emirats Arabes Unis','ec'=>'Equateur','er'=>'Erythrée','es'=>'Espagne','ee'=>'Estonie','us'=>'Etats-Unis','et'=>'Ethiopie','su'=>'Ex U.R.S.S.','fk'=>'Falkland (Malouines) îles','fo'=>'Faroe (îles)','fj'=>'Fidji','fi'=>'Finlande','fr'=>'France','ga'=>'Gabon','gm'=>'Gambie','gh'=>'Ghana','gi'=>'Gibraltar','gb'=>'Grande Bretagne','gd'=>'Grenade','gl'=>'Groenland','gr'=>'Grèce','gp'=>'Guadeloupe','gu'=>'Guam','gt'=>'Guatemala','gg'=>'Guernsey','gn'=>'Guinée','gq'=>'Guinée Equatoriale','gw'=>'Guinée-Bissau','gy'=>'Guyana','gf'=>'Guyane Française','ge'=>'Géorgie','gs'=>'Géorgie du sud','ht'=>'Haiti','hm'=>'Heard et McDonald (îles)','hn'=>'Honduras','hk'=>'Hong Kong','hu'=>'Hongrie','im'=>'Ile de Man','in'=>'Inde','id'=>'Indonésie','ir'=>'Iran','iq'=>'Iraq','ie'=>'Irlande','is'=>'Islande','il'=>'Israël','it'=>'Italie','jm'=>'Jamaïque','jp'=>'Japon','je'=>'Jersey','jo'=>'Jordanie','kz'=>'Kazakhstan','ke'=>'Kenya','kg'=>'Kirghizistan','ki'=>'Kiribati','kw'=>'Koweït','la'=>'Laos','ls'=>'Lesotho','lv'=>'Lettonie','lb'=>'Liban','lr'=>'Liberia','ly'=>'Libye','li'=>'Liechtenstein','lt'=>'Lituanie','lu'=>'Luxembourg','mo'=>'Macao','mk'=>'Macédoine','mg'=>'Madagascar','my'=>'Malaisie','mw'=>'Malawi','mv'=>'Maldives','ml'=>'Mali','mt'=>'Malte','mp'=>'Mariannes du nord (îles)','ma'=>'Maroc','mh'=>'Marshall (îles)','mq'=>'Martinique','mu'=>'Maurice (île)','mr'=>'Mauritanie','yt'=>'Mayotte','mx'=>'Mexique','fm'=>'Micronésie','md'=>'Moldavie','mc'=>'Monaco','mn'=>'Mongolie','ms'=>'Montserrat','mz'=>'Mozambique','mm'=>'Myanmar','na'=>'Namibie','nr'=>'Nauru','ni'=>'Nicaragua','ne'=>'Niger','ng'=>'Nigéria','nu'=>'Niue','nf'=>'Norfolk (île)','no'=>'Norvège','nc'=>'Nouvelle Calédonie','nz'=>'Nouvelle Zélande','np'=>'Népal','om'=>'Oman','ug'=>'Ouganda','uz'=>'Ouzbékistan','pk'=>'Pakistan','pw'=>'Palau','pa'=>'Panama','pg'=>'Papouasie Nvelle Guinée','py'=>'Paraguay','nl'=>'Pays Bas','ph'=>'Philippines','pn'=>'Pitcairn (île)','pl'=>'Pologne','pf'=>'Polynésie Française','pr'=>'Porto Rico','pt'=>'Portugal','pe'=>'Pérou','qa'=>'Qatar','ro'=>'Roumanie','uk'=>'Royaume Uni','ru'=>'Russie','rw'=>'Rwanda','cf'=>'Rép Centrafricaine','do'=>'Rép Dominicaine','zr'=>'Rép. Dém. du Congo (ex Zaïre)','cd'=>'Rép. du Congo','re'=>'Réunion (île de la)','eh'=>'Sahara Occidental','kn'=>'Saint Kitts et Nevis','sm'=>'Saint-Marin','lc'=>'Sainte Lucie','sb'=>'Salomon (îles)','sv'=>'Salvador','st'=>'Sao Tome et Principe','sw'=>'Serbie','cs'=>'Serbie Montenegro','sc'=>'Seychelles','sl'=>'Sierra Leone','sg'=>'Singapour','sk'=>'Slovaquie','si'=>'Slovénie','so'=>'Somalie','sd'=>'Soudan','lk'=>'Sri Lanka','vc'=>'St Vincent et les Grenadines','sh'=>'St. Hélène','pm'=>'St. Pierre et Miquelon','ch'=>'Suisse','sr'=>'Suriname','se'=>'Suède','sj'=>'Svalbard/Jan Mayen (îles)','sz'=>'Swaziland','sy'=>'Syrie','sn'=>'Sénégal','tj'=>'Tadjikistan','tw'=>'Taiwan','tz'=>'Tanzanie','td'=>'Tchad','cz'=>'Tchéquie','io'=>'Ter. Brit. Océan Indien','tf'=>'Territoires Fr du sud','th'=>'Thailande','tp'=>'Timor Oriental','tg'=>'Togo','tk'=>'Tokelau','to'=>'Tonga','tt'=>'Trinité et Tobago','tn'=>'Tunisie','tm'=>'Turkménistan','tc'=>'Turks et Caïques (îles)','tr'=>'Turquie','tv'=>'Tuvalu','um'=>'US Minor Outlying (îles)','ua'=>'Ukraine','uy'=>'Uruguay','vu'=>'Vanuatu','va'=>'Vatican','ve'=>'Venezuela','vg'=>'Vierges Brit. (îles)','vi'=>'Vierges USA (îles)','vn'=>'Viêt Nam','wf'=>'Wallis et Futuna (îles)','ws'=>'Western Samoa','ye'=>'Yemen','yu'=>'Yugoslavie','zm'=>'Zambie','zw'=>'Zimbabwe');
$libraryList['htmlentitie'] = array('À'=>'&Agrave;','à'=>'&agrave;','Á'=>'&Aacute;','á'=>'&aacute;','Â'=>'&Acirc;','â'=>'&acirc;','Ã'=>'&Atilde;','ã'=>'&atilde;','Ä'=>'&Auml;','ä'=>'&auml;','Å'=>'&Aring;','å'=>'&aring;','Æ'=>'&AElig;','æ'=>'&aelig;','Ç'=>'&Ccedil;','ç'=>'&ccedil;','Ð'=>'&ETH;','ð'=>'&eth;','È'=>'&Egrave;','è'=>'&egrave;','É'=>'&Eacute;','é'=>'&eacute;','Ê'=>'&Ecirc;','ê'=>'&ecirc;','Ë'=>'&Euml;','ë'=>'&euml;','Ì'=>'&Igrave;','ì'=>'&igrave;','Í'=>'&Iacute;','í'=>'&iacute;','Î'=>'&Icirc;','î'=>'&icirc;','Ï'=>'&Iuml;','ï'=>'&iuml;','Ñ'=>'&Ntilde;','ñ'=>'&ntilde;','Ò'=>'&Ograve;','ò'=>'&ograve;','Ó'=>'&Oacute;','ó'=>'&oacute;','Ô'=>'&Ocirc;','ô'=>'&ocirc;','Õ'=>'&Otilde;','õ'=>'&otilde;','Ö'=>'&Ouml;','ö'=>'&ouml;','Ø'=>'&Oslash;','ø'=>'&oslash;','Œ'=>'&OElig;','œ'=>'&oelig;','ß'=>'&szlig;','š'=>'&#353;', 'Š'=>'&#352;', 'Þ'=>'&THORN;','þ'=>'&thorn;','Ù'=>'&Ugrave;','ù'=>'&ugrave;','Ú'=>'&Uacute;','ú'=>'&uacute;','Û'=>'&Ucirc;','û'=>'&ucirc;','Ü'=>'&Uuml;','ü'=>'&uuml;','Ý'=>'&Yacute;','ý'=>'&yacute;','Ÿ'=>'&Yuml;','ÿ'=>'&yuml;','ž' => '&#382;', 'Ž' => '&#381;');
$libraryList['fr_regions'] = array("Alsace","Aquitaine","Auvergne","Bourgogne","Bretagne","Centre","Champagne-Ardenne","Corse","Franche-Comté","Île-de-France","Languedoc-Roussillon","Limousin","Lorraine","Midi-Pyrénées","Nord-Pas-de-Calais","Basse-Normandie","Haute-Normandie","Pays de la Loire","Picardie","Poitou-Charentes","Provence-Alpes-Côte d'Azur","Rhône-Alpes","Guyane","Guadeloupe","Martinique","Réunion");
$libraryList['fr_departements'] = array("01"=>"Ain", "02"=>"Aisne", "03"=>"Allier", "04"=>"Alpes-de-Haute-Provence", "05"=>"Hautes-Alpes", "06"=>"Alpes-Maritimes", "07"=>"Ardèche", "08"=>"Ardennes", "09"=>"Ariège", "10"=>"Aube", "11"=>"Aude", "12"=>"Aveyron", "13"=>"Bouches-du-Rhône", "14"=>"Calvados", "15"=>"Cantal", "16"=>"Charente", "17"=>"Charente-Maritime", "18"=>"Cher", "19"=>"Corrèze", "2A" => "Corse-du-Sud", "2B" => "Haute-Corse", "21"=>"Côte-d'Or", "22"=>"Côtes-d'Armor", "23"=>"Creuse", "24"=>"Dordogne", "25"=>"Doubs", "26"=>"Drôme", "27"=>"Eure", "28"=>"Eure-et-Loir", "29"=>"Finistère", "30"=>"Gard", "31"=>"Haute-Garonne", "32"=>"Gers", "33"=>"Gironde", "34"=>"Hérault", "35"=>"Ille-et-Vilaine", "36"=>"Indre", "37"=>"Indre-et-Loire", "38"=>"Isère", "39"=>"Jura", "40"=>"Landes", "41"=>"Loir-et-Cher", "42"=>"Loire", "43"=>"Haute-Loire", "44"=>"Loire-Atlantique", "45"=>"Loiret", "46"=>"Lot", "47"=>"Lot-et-Garonne", "48"=>"Lozère", "49"=>"Maine-et-Loire", "50"=>"Manche", "51"=>"Marne", "52"=>"Haute-Marne", "53"=>"Mayenne", "54"=>"Meurthe-et-Moselle", "55"=>"Meuse", "56"=>"Morbihan", "57"=>"Moselle", "58"=>"Nièvre", "59"=>"Nord", "60"=>"Oise", "61"=>"Orne", "62"=>"Pas-de-Calais", "63"=>"Puy-de-Dôme", "64"=>"Pyrénées-Atlantiques", "65"=>"Hautes-Pyrénées", "66"=>"Pyrénées-Orientales", "67"=>"Bas-Rhin", "68"=>"Haut-Rhin", "69"=>"Rhône", "70"=>"Haute-Saône", "71"=>"Saône-et-Loire", "72"=>"Sarthe", "73"=>"Savoie", "74"=>"Haute-Savoie", "75"=>"Paris", "76"=>"Seine-Maritime", "77"=>"Seine-et-Marne", "78"=>"Yvelines", "79"=>"Deux-Sèvres", "80"=>"Somme", "81"=>"Tarn", "82"=>"Tarn-et-Garonne", "83"=>"Var", "84"=>"Vaucluse", "85"=>"Vendée", "86"=>"Vienne", "87"=>"Haute-Vienne", "88"=>"Vosges", "89"=>"Yonne", "90"=>"Territoire de Belfort", "91"=>"Essonne", "92"=>"Hauts-de-Seine", "93"=>"Seine-Saint-Denis", "94"=>"Val-de-Marne", "95"=>"Val-d'Oise");

$libraryList['mailjetable'] = array(
'null', /* Pour les faux positif */
'yopmail.com',
'yopmail.fr',
'yopmail.net',
'cool.fr.nf',
'jetable.fr.nf',
'jetable.org',
'nospam.ze.tc',
'nomail.xl.cx',
'mega.zik.dj',
'speed.1s.fr',
'courriel.fr.nf',
'moncourrier.fr.nf',
'monemail.fr.nf',
'monmail.fr.nf',
'spamgourmet.com',
'tempomail.fr',
'rppkn.com',
'get2mail.fr'
);
return isSet($libraryList[$lib]) ? $libraryList[$lib] : $libraryList;
}
    
    
/**
* Debug tools
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param string $arg|Variable a dumper
* @return string
*/
function cwDebug ()
{
	$debug = debug_backtrace ();
	echo '<p>&nbsp;</p><p><a href="#" onclick="$(this).parent().next(\'ol\').slideToggle(); return false;"><strong>' . $debug [0] ['file'] . ' </strong> l.' . $debug [0] ['line'] . '</a></p>';
	echo '<ol style="display:none;">';
	$lastFile = $lastLine = false;
	foreach ( $debug as $k => $v ) {
		if ($k > 0) {
			$lastFile = isSet ( $v ['file'] ) ? $v ['file'] : $lastFile;
			$lastLine = isSet ( $v ['line'] ) ? $v ['line'] : $lastLine;
			echo '<li><strong>' . $lastFile . ' </strong> l.' . $lastLine . '</li>';
		}
	}
	echo '</ol>';
	
    $numargs = func_num_args();
    $arg_list = func_get_args();
    for ($i = 0; $i < $numargs; $i++)
    {
		echo '<pre class="code">';
		var_dump ( $arg_list[$i] );
		echo '</pre>';
    }
}


/**
* Debug tools identique a cwDebug()
* a l'exception, que le debug ne s'affiche pas en production
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param string $arg|Variable a dumper
* @param bool $return|Ecris ou renvois
* @return string
*/
function debug() {
	if (__DEV_MODE)
	{ 
		$debug = debug_backtrace ();
		echo '<p>&nbsp;</p><p><a href="#" onclick="$(this).parent().next(\'ol\').slideToggle(); return false;"><strong>' . $debug [0] ['file'] . ' </strong> l.' . $debug [0] ['line'] . '</a></p>';
		echo '<ol style="display:none;">';
		$lastFile = $lastLine = false;
		foreach ( $debug as $k => $v ) {
			if ($k > 0) {
				$lastFile = isSet ( $v ['file'] ) ? $v ['file'] : $lastFile;
				$lastLine = isSet ( $v ['line'] ) ? $v ['line'] : $lastLine;
				echo '<li><strong>' . $lastFile . ' </strong> l.' . $lastLine . '</li>';
			}
		}
		echo '</ol>';
		

	    $numargs = func_num_args();
	    $arg_list = func_get_args();
	    for ($i = 0; $i < $numargs; $i++)
	    {
			echo '<pre class="code">';
			var_dump ( $arg_list[$i] );
			echo '</pre>';
	    }
	}
}


/**
 * Enregistrement des erreurs dans un fichier cache
 *
 * @author Christophe BUFFET
 * @link http://crystal-web.org
 * @param string $errno|type de l'erreur
 * @param string $errstr|message d'erreur
 * @param string $errfile|fichier correspondant à l'erreur
 * @param string $errline|ligne correspondante à l'erreur
 * @return mixed
 */
function erreur_alerte($errno, $errstr, $errfile, $errline) {
	global $sys__bool;
	if ($sys__bool) {
		return;
	}
	
	// On définit le type de l'erreur
	switch ($errno) {
		case E_USER_ERROR :
			$type = "Fatal:";
			break;
		case E_USER_WARNING :
			$type = "Erreur:";
			break;
		case E_USER_NOTICE :
			$type = "Warning:";
			break;
		case E_ERROR :
			$type = "Fatal";
			break;
		case E_WARNING :
			$type = "Erreur:";
			break;
		case E_NOTICE :
			$type = "Warning:";
			break;
		default :
			$type = "Inconnu:";
			break;
	}
	
	$request = Request::getInstance();
	// On définit l'erreur.
	$erreur = "Type : " . $type . "
Message d'erreur : [" . $errno . "]" . $errstr . "
Ligne : " . $errline . "
Fichier : " . $errfile;
	
	/* Pour passer les valeurs des différents tableaux, nous utilisons la fonction serialize()
Le rapport d'erreur contient le type de l'erreur, la date, l'ip, et les tableaux. */
	
	$info = date ( "d/m/Y H:i:s", time () ) . " :
GET:" . print_r ( $_GET, true ) . "POST:" . print_r ( $_POST, true ) . "SERVER:" . print_r ( $_SERVER, true ) . "COOKIE:" . (isset ( $_COOKIE ) ? print_r ( $_COOKIE, true ) : "Undefined") . "SESSION:" . (isset ( $_SESSION ) ? print_r ( $_SESSION, true ) : "Undefined");
	//"LOG:" . print_r(Log::console(), true);
	

	$error_array ['date'] = time ();
	$error_array ['more'] = $info;
	$error_array ['type'] = $type;
	$error_array ['controller'] = $request->getController().'.'.$request->getAction();
	$error_array ['msg'] = "[" . $errno . "] " . $errstr;
	$error_array ['errline'] = $errline;
	$error_array ['errfile'] = $errfile;
//	$error_array ['errlog'] = Log::console();
	// Lecture du cache
	$cache_error = new Cache ( 'erreur_alerte' );
	$error_cache = $cache_error->getCache ();
	$error_cache [md5 ( $erreur )] = $error_array;
	
	// Ecriture du cache
	$cache_error_p = new Cache ( 'erreur_alerte', $error_cache );
	$cache_error_p->setCache ();
}
set_error_handler ( 'erreur_alerte' );

$sys__bool = false;
/**
 * 
 * Empeche l'ecriture du fichier, exemple lors de l'appal de fopen() ou file()
 * @param bool $bool
 */
function noError($bool) {
	global $sys__bool;
	$sys__bool = $bool;
}


/**
* Detection de Internet Explorer
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @return mixed IE version or false
*/
function is_ie()
{
$user_agent = (isSet($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT']: '';
$match=preg_match('/msie ([0-9]\.[0-9])/',strtolower($user_agent),$reg);
if($match==0) return false;
else return floatval($reg[1]);
}



/*........ ooO Date et temps Ooo ........ */


/**
 * 
 * Decoupe un timestamp en jour|heure|minute|seconde
 * @param int $time
 * @return array day,hour,minute,seconde
 */
function minute($time)
{
	// Temps en secondes
	$s = time() - $time;// / 1000;
	if ($s < 0)
	{
		$s = 0;
	}
	
	// Nombre de jour
	$d = floor($s / 86400);
	// Soustraite les jours
	$s -= ($d * 86400);
	
	// Nombre d'heure
	$h = floor($s/3600);
	$s -= ($h*3600);
	
	$m = floor($s/60);
	$s -= ($m * 60);
	
	$s = floor($s);
	
	return array('day' => $d, 'hour' => $h, 'minute' => $m, 'seconde' => $s);
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
    31104000 =>  'an{s}',        // 12 * 30 * 24 * 60 * 60 secondes
    2592000  =>  'mois',          // 30 * 24 * 60 * 60 secondes
    86400    =>  'jour{s}',   // 24 * 60 * 60 secondes
    3600      =>  'heure{s}',    // 60 * 60 secondes
    60       =>  'minute{s}',   // 60 secondes
    1         =>  'seconde{s}'); // 1 seconde

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
$jours= array("lundi", "mardi", "mercredi", "jeudi", "vendredi","samedi","dimanche");
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



/*........ ooO Chaine de caractère Ooo ........ */

/**
 * Inverse de nl2br
 */
function nl2null($string)
{
	return preg_replace("%\n%", "",  $string);
}

/**
 * 
 * Nettoyage d'une chaine
 * @param string $string
 * @param string $rules
 */
function clean($string, $rules) {
	if (is_string ( $string )) {
		$string = stripslashes ( $string );
		
		switch ($rules) :
			
			/***************************************
			 * Cas commun
			 ***************************************/
			case 'html' :
				return htmlspecialchars_decode ( $string );
				break;
			case 'str' :
				return $string;
				break;
			case 'stripbbcode' :
				loadFunction ( 'bbcode' );
				return stripBBcode ( $string );
				break;
			case 'bbcode' :
				loadFunction ( 'bbcode' );
				return bbcode ( $string );
				break;
			case 'slug' :
				$string = strtolower($string);
				$string = preg_replace ( '#\&([A-Za-z])(?:grave|acute|circ|tilde|uml|ring|cedil)\;#', '\1', $string );
				$string = preg_replace ( '#\&([A-Za-z]{2})(?:lig)\;#', '\1', $string );
				$string = preg_replace ( '#\&([A-Za-z])(.*)\;#', '', $string );
				$string = str_replace ( "'", '-', $string );
				$string = str_replace ( ' ', '-', $string );
				
				$string = str_replace ( '--', '-', $string );
				//  $string = strtolower($string);
				$string = trim ( $string, '-' );
				$string = preg_replace ( '#[^A-Za-z0-9_\-]#', '', $string );
				return (strlen($string)) ? $string : /*pour supprimer les liens mort*/ 'empty';
				break;
			/***************************************
			 * Cas particulier
			 ***************************************/
			case 'alphanum' :
				// éèê
				$string = preg_replace ( '#\&([A-Za-z])(?:grave|acute|circ|tilde|uml|ring|cedil)\;#', '\1', $string );
				// OE oe
				$string = preg_replace ( '#\&([A-Za-z]{2})(?:lig)\;#', '\1', $string );
				$string = preg_replace ( '#\&([A-Za-z])(.*)\;#', '', $string );
				// A-Z 0-9 - +
				return preg_replace ( '#[^A-Za-z0-9\-\+]#', '', $string );
				break;
			
			case 'alpha' :
				return preg_replace ( '#[^A-Za-z]#', '', $string );
				break;
			case 'num' :
				// 0-9
				return preg_replace ( '#[^0-9]#', '', $string );
				break;
			case 'alphaNumUnder' :
				// éèê
				$string = preg_replace ( '#\&([A-Za-z])(?:grave|acute|circ|tilde|uml|ring|cedil)\;#', '\1', $string );
				// OE oe
				$string = preg_replace ( '#\&([A-Za-z]{2})(?:lig)\;#', '\1', $string );
				$string = preg_replace ( '#\&([A-Za-z])(.*)\;#', '', $string );
				// A-Z 0-9 - + _
				return preg_replace ( '#[^A-Za-z0-9_]#', '', $string );
				break;
			case 'extra' :
				// éèê
				$string = preg_replace ( '#\&([A-Za-z])(?:grave|acute|circ|tilde|uml|ring|cedil)\;#', '\1', $string );
				// OE oe
				$string = preg_replace ( '#\&([A-Za-z]{2})(?:lig)\;#', '\1', $string );
				$string = preg_replace ( '#\&([A-Za-z])(.*)\;#', '', $string );
				// A-Z 0-9 - + _
				return preg_replace ( '#[^A-Za-z0-9_\-\+]#', '', $string );
				break;
			default :
				return preg_replace ( '#[^A-Za-z]#', '', $string );
				break;
		endswitch
		;
	}
	
	return false;

}


/**
*   @desc       Réecriture d'url Crystal-Web
*   @author     Christophe BUFFET <developpeur@crystal-web.org>
*   @copyright  Open Source
*   @deprecated
*/
function cleanerUrl($url){
$cleaner = strtr($url,
'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ',
'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
return preg_replace('/([^.a-z0-9]+)/i', '-', $cleaner);
}


/**
 * Tests if an input is valid PHP serialized string.
 *
 * Checks if a string is serialized using quick string manipulation
 * to throw out obviously incorrect strings. Unserialize is then run
 * on the string to perform the final verification.
 *
 * Valid serialized forms are the following:
 * <ul>
 * <li>boolean: <code>b:1;</code></li>
 * <li>integer: <code>i:1;</code></li>
 * <li>double: <code>d:0.2;</code></li>
 * <li>string: <code>s:4:"test";</code></li>
 * <li>array: <code>a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}</code></li>
 * <li>object: <code>O:8:"stdClass":0:{}</code></li>
 * <li>null: <code>N;</code></li>
 * </ul>
 *
 * @author		Chris Smith <code+php@chris.cs278.org>
 * @copyright	Copyright (c) 2009 Chris Smith (http://www.cs278.org/)
 * @license		http://sam.zoy.org/wtfpl/ WTFPL
 * @param		string	$value	Value to test for serialized form
 * @param		mixed	$result	Result of unserialize() of the $value
 * @return		boolean			True if $value is serialized data, otherwise false
 */
function is_serialized($value, &$result = null)
{
	// Bit of a give away this one
	if (!is_string($value))
	{
		return false;
	}

	// Serialized false, return true. unserialize() returns false on an
	// invalid string or it could return false if the string is serialized
	// false, eliminate that possibility.
	if ($value === 'b:0;')
	{
		$result = false;
		return true;
	}

	$length	= strlen($value);
	$end	= '';

	switch ($value[0])
	{
		case 's':
			if ($value[$length - 2] !== '"')
			{
				return false;
			}
		case 'b':
		case 'i':
		case 'd':
			// This looks odd but it is quicker than isset()ing
			$end .= ';';
		case 'a':
		case 'O':
			$end .= '}';

			if ($value[1] !== ':')
			{
				return false;
			}

			switch ($value[2])
			{
				case 0:
				case 1:
				case 2:
				case 3:
				case 4:
				case 5:
				case 6:
				case 7:
				case 8:
				case 9:
				break;

				default:
					return false;
			}
		case 'N':
			$end .= ';';

			if ($value[$length - 1] !== $end[0])
			{
				return false;
			}
		break;

		default:
			return false;
	}

	if (($result = @unserialize($value)) === false)
	{
		$result = null;
		return false;
	}
	return true;
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
 * 
 * Protection lors de l'ecriture sur le site
 * @param string $string
 * @param string $has
 * @deprecated
 */
function hasstr($string, $has = 'bbcode')
{
    switch($has)
    {
    case 'html':
    return htmlspecialchars_decode(stripcslashes($string));
    break;
    case 'bbcode':
    loadFunction('bbcode');
    return bbcode(stripcslashes($string));
    break;
    default:
    return stripcslashes($string);
    break;
    }

}


/**
 * Caractère aléatoire
 *
 * @link http://crystal-web.org
 * @author Christophe BUFFET
 * @param int $nb
 * @return string
 */
function randCar($nb = 10) {
	$random_name = NULL;
	// Charactère liste
	$list_char = array ("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9" );
	// Boucle
	for($i = 0; $i < $nb; $i ++) {
		// Ajout un caractère de la liste
		/*
    Liste de charactère : $list_char[]
    NB aléatoire de 0 a ? : rand(0,(x))
    Compte tous les éléments du tableau, retire 1 (count compte 1,2,3 | rand 0,1,2,3 comme array)  : count($list_char)-1
    */
		$random_name .= $list_char [rand ( 0, (count ( $list_char ) - 1) )];
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
function stripAccents($str) {
	$str = htmlentities ( $str, ENT_NOQUOTES, "UTF-8" );
	$str = htmlspecialchars_decode ( $str );
	// &eolig;
	$str = preg_replace ( '#\&([A-Za-z]{2})(?:lig)\;#', '\1', $str );
	// &aelig;  &AElig; &oelig; &OElig; vers ae  AE oe OE
	$str = preg_replace ( '#\&([A-Za-z])(?:(.*))\;#', '\1', $str );
	
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
function encodeHEX($bin, $plus = NULL) {
	$hex = '';
	for($i = 0; $i < strlen ( $bin ); $i ++) {
		$hex .= $plus . bin2hex ( $bin [$i] );
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
function decodeHEX($hex, $moins = "%") {
	$hex = strtolower ( $hex );
	if (preg_match ( "/" . $moins . "/i", $hex )) {
		// On supprime les %
		$trans = array ($moins => "" );
		$hex = strtr ( $hex, $trans );
	}
	$bin = "";
	
	for($i = 0; $i < strlen ( $hex ); $i = $i + 2) {
		$bin .= chr ( hexdec ( substr ( $hex, $i, 2 ) ) );
	}
	return $bin;
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
if (strlen($string) < $lengthMax) { return $string; }

    if ($safe_word == true)
    {
    // Récupération de la position du dernier espace (afin déviter de tronquer un mot)
    $position_espace = strrpos($string, " ");
        if ($position_espace)
        {
        $string = substr($string, 0, $position_espace);
        }
    }
    else
    {
    $string = substr($string, 0, $lengthMax);
    }
    // Ajout des "..."
    $string .= '...';
    return $string;
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
function truncate($string, $lengthMax) {
	// Variable locale
	$positionDernierEspace = 0;
	
	if (strlen ( $string ) >= $lengthMax) {
		$string = substr ( $string, 0, $lengthMax );
		$positionDernierEspace = strrpos ( $string, ' ' );
		$string = substr ( $string, 0, $positionDernierEspace ) . '...';
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
function stripspace($str) {
	$str = trim ( $str );
	$str = preg_replace ( "#\s+#", " ", $str );
	return $str;
}


/*........ ooO Chiffre et math Ooo ........ */


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


/**
* Paire ou impaire ?
*
* @author Christophe BUFFET
* @link http://crystal-web.org
* @param int $nombre|chiffre
* @return bool
*/
function is_paire($nombre){
return ($nombre%2 == 0) ? true : false;
}


/*........ ooO Dossiers et fichiers Ooo ........ */


/**
 * Retourne les informations sur le fichier demandé
 * 
 * @link http://www.askapache.com/security/chmod-stat.html
 * @param string $file
 */
function alt_stat($file)
{
 clearstatcache();
 $ss=@stat($file);
 if(!$ss) return false; //Couldnt stat file
 
 $ts=array(
  0140000=>'ssocket',
  0120000=>'llink',
  0100000=>'-file',
  0060000=>'bblock',
  0040000=>'ddir',
  0020000=>'cchar',
  0010000=>'pfifo'
 );
 
 $p=$ss['mode'];
 $t=decoct($ss['mode'] & 0170000); // File Encoding Bit
 
 $str =(array_key_exists(octdec($t),$ts))?$ts[octdec($t)]{0}:'u';
 $str.=(($p&0x0100)?'r':'-').(($p&0x0080)?'w':'-');
 $str.=(($p&0x0040)?(($p&0x0800)?'s':'x'):(($p&0x0800)?'S':'-'));
 $str.=(($p&0x0020)?'r':'-').(($p&0x0010)?'w':'-');
 $str.=(($p&0x0008)?(($p&0x0400)?'s':'x'):(($p&0x0400)?'S':'-'));
 $str.=(($p&0x0004)?'r':'-').(($p&0x0002)?'w':'-');
 $str.=(($p&0x0001)?(($p&0x0200)?'t':'x'):(($p&0x0200)?'T':'-'));
 
 $s=array(
 'perms'=>array(
  'umask'=>sprintf("%04o",@umask()),
  'human'=>$str,
  'octal1'=>sprintf("%o", ($ss['mode'] & 000777)),
  'octal2'=>sprintf("0%o", 0777 & $p),
  'decimal'=>sprintf("%04o", $p),
  'fileperms'=>@fileperms($file),
  'mode1'=>$p,
  'mode2'=>$ss['mode']),
 
 'owner'=>array(
  'fileowner'=>$ss['uid'],
  'filegroup'=>$ss['gid'],
  'owner'=>
  (function_exists('posix_getpwuid'))?
  @posix_getpwuid($ss['uid']):'',
  'group'=>
  (function_exists('posix_getgrgid'))?
  @posix_getgrgid($ss['gid']):''
  ),
 
 'file'=>array(
  'filename'=>$file,
  'realpath'=>@realpath($file),
  'dirname'=>@dirname($file),
  'basename'=>@basename($file)
  ),

 'filetype'=>array(
  'type'=>substr($ts[octdec($t)],1),
  'type_octal'=>sprintf("%07o", octdec($t)),
  'is_file'=>@is_file($file),
  'is_dir'=>@is_dir($file),
  'is_link'=>@is_link($file),
  'is_readable'=> @is_readable($file),
  'is_writable'=> @is_writable($file)
  ),
  
 'device'=>array(
  'device'=>$ss['dev'], //Device
  'device_number'=>$ss['rdev'], //Device number, if device.
  'inode'=>$ss['ino'], //File serial number
  'link_count'=>$ss['nlink'], //link count
  'link_to'=>(filetype($file)=='link') ? @readlink($file) : ''
  ),
 
 'size'=>array(
  'size'=>$ss['size'], //Size of file, in bytes.
  'blocks'=>$ss['blocks'], //Number 512-byte blocks allocated
  'block_size'=> $ss['blksize'] //Optimal block size for I/O.
  ), 
 
 'time'=>array(
  'mtime'=>$ss['mtime'], //Time of last modification
  'atime'=>$ss['atime'], //Time of last access.
  'ctime'=>$ss['ctime'], //Time of last status change
  'accessed'=>@date('Y M D H:i:s',$ss['atime']),
  'modified'=>@date('Y M D H:i:s',$ss['mtime']),
  'created'=>@date('Y M D H:i:s',$ss['ctime'])
  ),
 );
 
 clearstatcache();
 return $s;
}


/**
* Supprime des dossiers de façon recurcive
*
* @link http://crystal-web.org
* @author Christophe BUFFET
* @param string $dir
* @return void
*/
function rmdir_recursive($dir, $delDir = false)
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
    
    if ($delDir) { rmdir($dir); }
}


/**
* Parcourir un dossier et récupérer le contenu de chaque fichier
*
* @author Jay Salvat
* @link http://blog.jaysalvat.com/article/zipper-des-dossiers-a-la-volee-avec-php
* @param string $folder|Dossier a scanner
* @return array
*/
function scanfolder($folder)
{
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


/*........ ooO GUI ou presque Ooo ........ */


/**
 * 
 * Crée la barre vertical du menu administrateur
 * @param array $adminSiderbar
 */
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
echo '</ul><a class="settingbutton" href="#">   </a></div>';
}


/**
 * 
 * Crée une pagination
 * @param int $nb_page
 */
function pagination($nb_page)
{
$page = (int) (isset($_GET['page'])) ? clean($_GET['page'], 'num') : 1;
/***************************************
*   Pagination
***************************************/
unset($_GET['page']);

$qstring = null;
foreach($_GET as $key => $val) 
{ 
    $qstring .= "&" . $key . "=" . $val;
} 


$html = NULL;
if ($nb_page > 1)
{
$html   =   '<div class="pagination">';
$html   .=  '<ul>';

	if ($nb_page > 5)
	{
		if ($page == 1)
		{
			$html   .=  '<li class="prev disabled"><a href="#">Premi&egrave;re</a></li>';
		}
		else
		{
			$html   .=  '<li class="prev"><a href="?page=1' . $qstring . '">Premi&egrave;re</a></li>';
		}
	}
	
    // Si la page - une est suppérieur a 0
    // Il y a une page
    if ($page-1 > 0)
    {
        $html   .=  '<li class="prev"><a href="?page='.($page-1) . $qstring . '">Precedent</a></li>';
    }
    // Sinon, il n'y en a pas
    else
    {
        $html   .=  '<li class="prev disabled"><a href="#">Precedent</a></li>';
    }

/***************************************
*   Bloucle simple multi info
***************************************/

            for($i=$page-3; $i<$page+3; $i++)
            {
                if ($i<=$nb_page && $i>0)
                {
                    if ($page == $i)
                    {
                    $html   .=  '<li><a href="#" class="disabled">'.$i.'</a></li>';
                    }
                    else
                    {
                    $html   .=  '<li><a href="?page='.$i . $qstring . '">'.$i.'</a></li>';
                    }
                }
            }

/***************************************
*   END Bloucle simple multi info
***************************************/

    // Si la page + une est inférieur ou egal au nombre de page
    if ($page+1 <= $nb_page)
    {
        $html   .=  '<li class="next"><a href="?page='.($page+1) . $qstring . '">Suivant</a></li>';
    }
    // Sinon, il n'y en a pas
    else
    {
        $html   .=  '<li class="next disabled"><a href="#">Suivant</a></li>';
    }

	if ($nb_page > 5)
	{
		if ($page == $nb_page)
		{
			$html   .=  '<li class="next disabled"><a href="#">Derni&egrave;re</a></li>';
		}
		else
		{
			$html   .=  '<li class="next"><a href="?page=' . $nb_page . $qstring . '">Derni&egrave;re</a></li>';
		}
	}

$html   .=  '</ul>';
$html   .=  '</div>';
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
* @deprecated utilisé $this->mvc->Session->setFlash(str, type)
*/
function alerte($msg, $echo = false)
{
$box = '<div class="MSGbox MSGalerte"><p>' . $msg . '</p></div>';
if ($echo == true) { echo $box; } else { return $box; }
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
function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array()) {
	$url = 'http://www.gravatar.com/avatar/';
	$url .= md5 ( strtolower ( trim ( $email ) ) );
	$url .= '?s=' . $s . '&amp;d=' . $d . '&amp;r=' . $r;
	
	if ($img) {
		$url = '<img src="' . $url . '"';
		foreach ( $atts as $key => $val )
			$url .= ' ' . $key . '="' . $val . '"';
		$url .= ' />';
	}
	return $url;
}


/*........ ooO Array Ooo ........ */


/**
 * Searches haystack for needle and
 * returns an array of the key path if
 * it is found in the (multidimensional)
 * array, FALSE otherwise.
 *
 * @mixed array_searchRecursive ( mixed needle,
 * array haystack [, bool strict[, array path]] )
 * @url http://greengaloshes.cc/2007/04/recursive-multidimensional-array-search-in-php/
 */
function array_searchRecursive( $needle, $haystack, $strict=false, $path=array() )
{
    if( !is_array($haystack) ) {
        return false;
    }

    foreach( $haystack as $key => $val ) {
        if( is_array($val) && $subPath = array_searchRecursive($needle, $val, $strict, $path) ) {
            $path = array_merge($path, array($key), $subPath);
            return $path;
        } elseif( (!$strict && $val == $needle) || ($strict && $val === $needle) ) {
            $path[] = $key;
            return $path;
        }
    }
    return false;
}





/**
* Savoir si le cient est connecté
*
* @link http://crystal-web.org
* @author Christophe BUFFET
* @return bool
* @deprecated utilisé $this->mvc->Session->isLogged()
*/
function is_connected()
{
    return Auth::isAuth();
}



function _get_bytes($asString)
{
	$val = trim($asString);
	$last = strtolower($val[strlen($val)-1]);
	switch($last) {
		// The 'G' modifier is available since PHP 5.1.0
		case 'g':
			$val *= 1024;
		case 'm':
			$val *= 1024;
		case 'k':
			$val *= 1024;
	}
	
	return $val;
}


function _format_bytes($a_bytes)
{
    if ($a_bytes < 1024) {
        return $a_bytes .' B';
    } elseif ($a_bytes < 1048576) {
        return round($a_bytes / 1024, 2) .' KB';
    } elseif ($a_bytes < 1073741824) {
        return round($a_bytes / 1048576, 2) . ' MB';
    } elseif ($a_bytes < 1099511627776) {
        return round($a_bytes / 1073741824, 2) . ' GB';
    } elseif ($a_bytes < 1125899906842624) {
        return round($a_bytes / 1099511627776, 2) .' TB';
    } elseif ($a_bytes < 1152921504606846976) {
        return round($a_bytes / 1125899906842624, 2) .' PB';
    } elseif ($a_bytes < 1180591620717411303424) {
        return round($a_bytes / 1152921504606846976, 2) .' EB';
    } elseif ($a_bytes < 1208925819614629174706176) {
        return round($a_bytes / 1180591620717411303424, 2) .' ZB';
    } else {
        return round($a_bytes / 1208925819614629174706176, 2) .' YB';
    }
}



/**
 * google_pagerank <http://code.seebz.net/p/google-pagerank/>
 *
 * Copyright (c) 2010 Sébastien Corne, <http://seebz.net>
 *
 * This script is an adaptation of the GooglePR Class made by FloBaoti.
 * <http://www.phpcs.com/codes/GOOGLE-PAGERANK-CHECKSUM-ALGORITHM_40649.aspx>
 */
function google_pagerank($url, $server = 'toolbarqueries.google.com')
{
	// Usefulls functions
	$fStrToNum = create_function('$str, $check, $magic',
	'
		$int32Unit = 4294967296; // 2^32
		$length = strlen($str);
		for ($i = 0; $i < $length; $i++){
			$check *= $magic;
			if ($check >= $int32Unit){
				$check = ($check - $int32Unit * (int) ($check / $int32Unit));
				$check = ($check < -2147483648) ? ($check + $int32Unit) : $check;
			}
			$check += ord($str{$i});
		}
		
		return $check;
	');
	$fHashURL = create_function('$str',
	'
		$fStrToNum = "'.$fStrToNum.'";
		$check1 = $fStrToNum($str, 0x1505, 0x21);
		$check2 = $fStrToNum($str, 0, 0x1003F);
		
		$check1 >>= 2;
		$check1 = (($check1 >> 4) & 0x3FFFFC0 ) | ($check1 & 0x3F);
		$check1 = (($check1 >> 4) & 0x3FFC00 ) | ($check1 & 0x3FF);
		$check1 = (($check1 >> 4) & 0x3C000 ) | ($check1 & 0x3FFF);
		$t1 = (((($check1 & 0x3C0) << 4) | ($check1 & 0x3C)) <<2 ) | ($check2 & 0xF0F );
		$t2 = (((($check1 & 0xFFFFC000) << 4) | ($check1 & 0x3C00)) << 0xA) | ($check2 & 0xF0F0000 );
		
		return ($t1 | $t2);
	');
	$fCheckHash = create_function('$hashNum',
	'
		$checkByte = 0; $flag = 0;
		$hashStr = sprintf("%u", $hashNum) ;
		$length = strlen($hashStr);
		for ($i = $length-1; $i >= 0; $i--){
			$re = $hashStr{$i};
			if (1 === ($flag % 2)){
				$re += $re;
				$re = (int)($re / 10) + ($re % 10);
			}
			$checkByte += $re;
			$flag ++;
		}
		$checkByte %= 10;		echo nl2br();
		if (0 !== $checkByte){
			$checkByte = 10 - $checkByte;
			if (1 === ($flag % 2) ){
				if (1 === ($checkByte % 2)){
					$checkByte += 9;
				}
				$checkByte >>= 1;
			}
		}
		
		return "7" . $checkByte . $hashStr;
	');
	
	// Checksum calcul
	$checksum = $fCheckHash($fHashURL($url));
	
	// Google request
	$requestUrl = sprintf(
		'http://%s/tbr?client=navclient-auto&ch=%s&ie=UTF-8&oe=UTF-8&features=Rank&q=info:%s',
		$server,
		$checksum,
		urlencode($url)
	);

	if ( ($c = @file_get_contents($requestUrl)) === false )
	{
		return false;
	}
	elseif( empty($c) )
	{
		return -1;
	}
	else
	{
		return intval(substr($c, strrpos($c, ':')+1));
	}
	
	/* Usage */
	
	/*
$url = "http://php.net/";
$pr  = google_pagerank($url);
 
if($pr === false) {
    echo "Erreur";
} elseif($pr == -1) {
    echo "N/A";
} else {
    echo $pr;
}
	 */
}

?>
