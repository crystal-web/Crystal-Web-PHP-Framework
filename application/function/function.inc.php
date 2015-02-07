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
$file_get_contents_curl_memory = array(); 
function file_get_contents_curl($url, $timeout = 3) {
	global $file_get_contents_curl_memory;
	if (isset($file_get_contents_curl_memory[$url])) {
		return $file_get_contents_curl_memory[$url];
	}
    $ch = curl_init($url);
	if (false === $ch) {
		$file_get_contents_curl_memory[$url] = false;
		return false;
	}
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);  // this works
	curl_setopt($ch, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15")); // request as if Firefox    
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    $data = curl_exec($ch);
	if (!$data) {
		throw new Exception(('file_get_contents_curl(' . $url . ', ' . $timeout.')' . curl_error($ch)), 1);
	}
    curl_close($ch);
	$file_get_contents_curl_memory[$url] = $data;
	return $data;
}

/**
 * Crée un ini, a partir d'un tableau bidimentionnel 
 */
function array2ini($array) {
$ini = NULL;
	foreach($array as $key => $group_n) {
	$ini.="\n[".$key."]";
		foreach($group_n as $key => $item_n) {
			$ini.="\n".$key."=".$item_n;
		}
	}
return $ini;
}

/**
 * Converti un ini en tableau
 */
function ini2array($ini) {
	foreach($ini as $line) {
		if(preg_match("#^\[(.*)\]\s+$#",$line,$matches)) {
			$group=$matches[1];
			$array[$group]=array();
		} elseif($line[0]!=';') {
			list($item,$value)=explode("=",$line,2);
			if(!isset($value)) { $value=''; }
			$array[$group][$item]=$value;
		}
	}
}

/**
 * Converti un fichier ini en tableau
 */
function file_ini2array($file) {
	if(file_exists($file)) { return ini2array(file($file)); }
	return false;
}

/**
 * @deprecated
 * @obsolete
 */
function str2slug($str) {
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
function __autoload($class_name) {

$filePathLibs = __APP_PATH . DS . 'libs' . DS . $class_name.'.class.php';
$filePathModel = __APP_PATH . DS . 'model' . DS . $class_name.'.php';
$filePathFramework = __APP_PATH . DS . 'framework' . DS . $class_name . '.php';
$filePathController = __APP_PATH . DS . 'controller' . DS . $class_name . '.php';
    if (!file_exists($filePathLibs)) {
    	if (!file_exists($filePathModel)) {
    		if (!file_exists($filePathController)) {
				if (!file_exists($filePathFramework)) {
					throw new Exception('Class file not exists <strong>'.$class_name.'</strong>');
				} else {
					require_once ($filePathFramework);
				}
    		} else {
				require_once ($filePathController);
			}
		} else {
			require_once ($filePathModel);
		}
    } else {
		require_once ($filePathLibs);
	}
}


/**
 * 
 * Chargement d'une fonction du dossier function
 * @param string $function
 */
function loadLibrary($libraryName) {
$file = __APP_PATH . DS . 'function' . DS . $libraryName;
$file .= '.php';
    if (!file_exists($file)) {
		throw new Exception('Library file not exists <strong>'.$libraryName.'</strong>');
    }
	
	require_once $file;
}
function loadFunction($function) { loadLibrary($function); }

function isAjax() {
	return ( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
			!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) ? true : false;
}
function is_ajax(){
	return isAjax();
}
/**
 * 
 * Chargement d'un model 
 * @param string $name
 * @throws Exception
 */
function loadModel($name) {
$name = $name.'Model';
// L'endroit ou le model est chargé
$file = __APP_PATH . DS . 'model' . DS . $name . '.php';
    if (!file_exists($file)) {
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
function library($lib=NULL) {
$libraryList = array();
$libraryList['country'] = array('Afghanistan', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua And Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia And Herzegovina', 'Botswana', 'Bouvet Island', 'Brazil', 'British Indian Ocean Territory', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Columbia', 'Comoros', 'Congo', 'Cook Islands', 'Costa Rica', 'Cote D\'Ivorie (Ivory Coast)', 'Croatia (Hrvatska)', 'Cuba', 'Cyprus', 'Czech Republic', 'Democratic Republic Of Congo (Zaire)', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands (Malvinas)', 'Faroe Islands', 'Fiji', 'Finland', 'France', 'France, Metropolitan', 'French Guinea', 'French Polynesia', 'French Southern Territories', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Heard And McDonald Islands', 'Honduras', 'Hong Kong', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macau', 'Macedonia', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar (Burma)', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'Netherlands Antilles', 'New Caledonia', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Niue', 'Norfolk Island', 'North Korea', 'Northern Mariana Islands', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Pitcairn', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Reunion', 'Romania', 'Russia', 'Rwanda', 'Saint Helena', 'Saint Kitts And Nevis', 'Saint Lucia', 'Saint Pierre And Miquelon', 'Saint Vincent And The Grenadines', 'San Marino', 'Sao Tome And Principe', 'Saudi Arabia', 'Senegal', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovak Republic', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Georgia And South Sandwich Islands', 'South Korea', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Svalbard And Jan Mayen', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tokelau', 'Tonga', 'Trinidad And Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Turks And Caicos Islands', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'United States Minor Outlying Islands', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City (Holy See)', 'Venezuela', 'Vietnam', 'Virgin Islands (British)', 'Virgin Islands (US)', 'Wallis And Futuna Islands', 'Western Sahara', 'Western Samoa', 'Yemen', 'Yugoslavia', 'Zambia', 'Zimbabwe');
$libraryList['flag'] = array('af'=>'Afghanistan','za'=>'Afrique du Sud','al'=>'Albanie','dz'=>'Algérie','de'=>'Allemagne','as'=>'American Samoa','ad'=>'Andorre','ao'=>'Angola','ai'=>'Anguilla','aq'=>'Antarctique','ag'=>'Antigua et Barbuda','an'=>'Antilles Neerlandaises','sa'=>'Arabie Saoudite','ar'=>'Argentine','am'=>'Arménie','aw'=>'Aruba','ac'=>'Ascension (île)','au'=>'Australie','at'=>'Autriche','az'=>'Azerbaidjan','bs'=>'Bahamas','bh'=>'Bahrein','bd'=>'Bangladesh','bb'=>'Barbade','be'=>'Belgique','bm'=>'Bermudes','bt'=>'Bhoutan','by'=>'Biélorussie','bo'=>'Bolivie','ba'=>'Bosnie Herzégovine','bw'=>'Botswana','bv'=>'Bouvet (île)','bn'=>'Brunei','br'=>'Brésil','bg'=>'Bulgarie','bf'=>'Burkina Faso','bi'=>'Burundi','bz'=>'Bélize','bj'=>'Bénin','kh'=>'Cambodge','cm'=>'Cameroun','ca'=>'Canada','cv'=>'Cap Vert','ky'=>'Caïmanes (îles)','cl'=>'Chili','cn'=>'Chine','cx'=>'Christmas (île)','cy'=>'Chypre','cc'=>'Cocos (Keeling) îles','co'=>'Colombie','km'=>'Comores','ck'=>'Cook (îles)','kp'=>'Corée du nord','kr'=>'Corée du sud','cr'=>'Costa Rica','hr'=>'Croatie','cu'=>'Cuba','ci'=>'Côte d\'Ivoire','dk'=>'Danemark','dj'=>'Djibouti','dm'=>'Dominique','eg'=>'Egypte','ae'=>'Emirats Arabes Unis','ec'=>'Equateur','er'=>'Erythrée','es'=>'Espagne','ee'=>'Estonie','us'=>'Etats-Unis','et'=>'Ethiopie','su'=>'Ex U.R.S.S.','fk'=>'Falkland (Malouines) îles','fo'=>'Faroe (îles)','fj'=>'Fidji','fi'=>'Finlande','fr'=>'France','ga'=>'Gabon','gm'=>'Gambie','gh'=>'Ghana','gi'=>'Gibraltar','gb'=>'Grande Bretagne','gd'=>'Grenade','gl'=>'Groenland','gr'=>'Grèce','gp'=>'Guadeloupe','gu'=>'Guam','gt'=>'Guatemala','gg'=>'Guernsey','gn'=>'Guinée','gq'=>'Guinée Equatoriale','gw'=>'Guinée-Bissau','gy'=>'Guyana','gf'=>'Guyane Française','ge'=>'Géorgie','gs'=>'Géorgie du sud','ht'=>'Haiti','hm'=>'Heard et McDonald (îles)','hn'=>'Honduras','hk'=>'Hong Kong','hu'=>'Hongrie','im'=>'Ile de Man','in'=>'Inde','id'=>'Indonésie','ir'=>'Iran','iq'=>'Iraq','ie'=>'Irlande','is'=>'Islande','il'=>'Israël','it'=>'Italie','jm'=>'Jamaïque','jp'=>'Japon','je'=>'Jersey','jo'=>'Jordanie','kz'=>'Kazakhstan','ke'=>'Kenya','kg'=>'Kirghizistan','ki'=>'Kiribati','kw'=>'Koweït','la'=>'Laos','ls'=>'Lesotho','lv'=>'Lettonie','lb'=>'Liban','lr'=>'Liberia','ly'=>'Libye','li'=>'Liechtenstein','lt'=>'Lituanie','lu'=>'Luxembourg','mo'=>'Macao','mk'=>'Macédoine','mg'=>'Madagascar','my'=>'Malaisie','mw'=>'Malawi','mv'=>'Maldives','ml'=>'Mali','mt'=>'Malte','mp'=>'Mariannes du nord (îles)','ma'=>'Maroc','mh'=>'Marshall (îles)','mq'=>'Martinique','mu'=>'Maurice (île)','mr'=>'Mauritanie','yt'=>'Mayotte','mx'=>'Mexique','fm'=>'Micronésie','md'=>'Moldavie','mc'=>'Monaco','mn'=>'Mongolie','ms'=>'Montserrat','mz'=>'Mozambique','mm'=>'Myanmar','na'=>'Namibie','nr'=>'Nauru','ni'=>'Nicaragua','ne'=>'Niger','ng'=>'Nigéria','nu'=>'Niue','nf'=>'Norfolk (île)','no'=>'Norvège','nc'=>'Nouvelle Calédonie','nz'=>'Nouvelle Zélande','np'=>'Népal','om'=>'Oman','ug'=>'Ouganda','uz'=>'Ouzbékistan','pk'=>'Pakistan','pw'=>'Palau','pa'=>'Panama','pg'=>'Papouasie Nvelle Guinée','py'=>'Paraguay','nl'=>'Pays Bas','ph'=>'Philippines','pn'=>'Pitcairn (île)','pl'=>'Pologne','pf'=>'Polynésie Française','pr'=>'Porto Rico','pt'=>'Portugal','pe'=>'Pérou','qa'=>'Qatar','ro'=>'Roumanie','uk'=>'Royaume Uni','ru'=>'Russie','rw'=>'Rwanda','cf'=>'Rép Centrafricaine','do'=>'Rép Dominicaine','zr'=>'Rép. Dém. du Congo (ex Zaïre)','cd'=>'Rép. du Congo','re'=>'Réunion (île de la)','eh'=>'Sahara Occidental','kn'=>'Saint Kitts et Nevis','sm'=>'Saint-Marin','lc'=>'Sainte Lucie','sb'=>'Salomon (îles)','sv'=>'Salvador','st'=>'Sao Tome et Principe','sw'=>'Serbie','cs'=>'Serbie Montenegro','sc'=>'Seychelles','sl'=>'Sierra Leone','sg'=>'Singapour','sk'=>'Slovaquie','si'=>'Slovénie','so'=>'Somalie','sd'=>'Soudan','lk'=>'Sri Lanka','vc'=>'St Vincent et les Grenadines','sh'=>'St. Hélène','pm'=>'St. Pierre et Miquelon','ch'=>'Suisse','sr'=>'Suriname','se'=>'Suède','sj'=>'Svalbard/Jan Mayen (îles)','sz'=>'Swaziland','sy'=>'Syrie','sn'=>'Sénégal','tj'=>'Tadjikistan','tw'=>'Taiwan','tz'=>'Tanzanie','td'=>'Tchad','cz'=>'Tchéquie','io'=>'Ter. Brit. Océan Indien','tf'=>'Territoires Fr du sud','th'=>'Thailande','tp'=>'Timor Oriental','tg'=>'Togo','tk'=>'Tokelau','to'=>'Tonga','tt'=>'Trinité et Tobago','tn'=>'Tunisie','tm'=>'Turkménistan','tc'=>'Turks et Caïques (îles)','tr'=>'Turquie','tv'=>'Tuvalu','um'=>'US Minor Outlying (îles)','ua'=>'Ukraine','uy'=>'Uruguay','vu'=>'Vanuatu','va'=>'Vatican','ve'=>'Venezuela','vg'=>'Vierges Brit. (îles)','vi'=>'Vierges USA (îles)','vn'=>'Viêt Nam','wf'=>'Wallis et Futuna (îles)','ws'=>'Western Samoa','ye'=>'Yemen','yu'=>'Yugoslavie','zm'=>'Zambie','zw'=>'Zimbabwe');
$libraryList['htmlentitie'] = array('À'=>'&Agrave;','à'=>'&agrave;','Á'=>'&Aacute;','á'=>'&aacute;','Â'=>'&Acirc;','â'=>'&acirc;','Ã'=>'&Atilde;','ã'=>'&atilde;','Ä'=>'&Auml;','ä'=>'&auml;','Å'=>'&Aring;','å'=>'&aring;','Æ'=>'&AElig;','æ'=>'&aelig;','Ç'=>'&Ccedil;','ç'=>'&ccedil;','Ð'=>'&ETH;','ð'=>'&eth;','È'=>'&Egrave;','è'=>'&egrave;','É'=>'&Eacute;','é'=>'&eacute;','Ê'=>'&Ecirc;','ê'=>'&ecirc;','Ë'=>'&Euml;','ë'=>'&euml;','Ì'=>'&Igrave;','ì'=>'&igrave;','Í'=>'&Iacute;','í'=>'&iacute;','Î'=>'&Icirc;','î'=>'&icirc;','Ï'=>'&Iuml;','ï'=>'&iuml;','Ñ'=>'&Ntilde;','ñ'=>'&ntilde;','Ò'=>'&Ograve;','ò'=>'&ograve;','Ó'=>'&Oacute;','ó'=>'&oacute;','Ô'=>'&Ocirc;','ô'=>'&ocirc;','Õ'=>'&Otilde;','õ'=>'&otilde;','Ö'=>'&Ouml;','ö'=>'&ouml;','Ø'=>'&Oslash;','ø'=>'&oslash;','Œ'=>'&OElig;','œ'=>'&oelig;','ß'=>'&szlig;','š'=>'&#353;', 'Š'=>'&#352;', 'Þ'=>'&THORN;','þ'=>'&thorn;','Ù'=>'&Ugrave;','ù'=>'&ugrave;','Ú'=>'&Uacute;','ú'=>'&uacute;','Û'=>'&Ucirc;','û'=>'&ucirc;','Ü'=>'&Uuml;','ü'=>'&uuml;','Ý'=>'&Yacute;','ý'=>'&yacute;','Ÿ'=>'&Yuml;','ÿ'=>'&yuml;','ž' => '&#382;', 'Ž' => '&#381;');
$libraryList['fr_regions'] = array("Alsace","Aquitaine","Auvergne","Bourgogne","Bretagne","Centre","Champagne-Ardenne","Corse","Franche-Comté","Île-de-France","Languedoc-Roussillon","Limousin","Lorraine","Midi-Pyrénées","Nord-Pas-de-Calais","Basse-Normandie","Haute-Normandie","Pays de la Loire","Picardie","Poitou-Charentes","Provence-Alpes-Côte d'Azur","Rhône-Alpes","Guyane","Guadeloupe","Martinique","Réunion");
$libraryList['fr_departements'] = array("01"=>"Ain", "02"=>"Aisne", "03"=>"Allier", "04"=>"Alpes-de-Haute-Provence", "05"=>"Hautes-Alpes", "06"=>"Alpes-Maritimes", "07"=>"Ardèche", "08"=>"Ardennes", "09"=>"Ariège", "10"=>"Aube", "11"=>"Aude", "12"=>"Aveyron", "13"=>"Bouches-du-Rhône", "14"=>"Calvados", "15"=>"Cantal", "16"=>"Charente", "17"=>"Charente-Maritime", "18"=>"Cher", "19"=>"Corrèze", "2A" => "Corse-du-Sud", "2B" => "Haute-Corse", "21"=>"Côte-d'Or", "22"=>"Côtes-d'Armor", "23"=>"Creuse", "24"=>"Dordogne", "25"=>"Doubs", "26"=>"Drôme", "27"=>"Eure", "28"=>"Eure-et-Loir", "29"=>"Finistère", "30"=>"Gard", "31"=>"Haute-Garonne", "32"=>"Gers", "33"=>"Gironde", "34"=>"Hérault", "35"=>"Ille-et-Vilaine", "36"=>"Indre", "37"=>"Indre-et-Loire", "38"=>"Isère", "39"=>"Jura", "40"=>"Landes", "41"=>"Loir-et-Cher", "42"=>"Loire", "43"=>"Haute-Loire", "44"=>"Loire-Atlantique", "45"=>"Loiret", "46"=>"Lot", "47"=>"Lot-et-Garonne", "48"=>"Lozère", "49"=>"Maine-et-Loire", "50"=>"Manche", "51"=>"Marne", "52"=>"Haute-Marne", "53"=>"Mayenne", "54"=>"Meurthe-et-Moselle", "55"=>"Meuse", "56"=>"Morbihan", "57"=>"Moselle", "58"=>"Nièvre", "59"=>"Nord", "60"=>"Oise", "61"=>"Orne", "62"=>"Pas-de-Calais", "63"=>"Puy-de-Dôme", "64"=>"Pyrénées-Atlantiques", "65"=>"Hautes-Pyrénées", "66"=>"Pyrénées-Orientales", "67"=>"Bas-Rhin", "68"=>"Haut-Rhin", "69"=>"Rhône", "70"=>"Haute-Saône", "71"=>"Saône-et-Loire", "72"=>"Sarthe", "73"=>"Savoie", "74"=>"Haute-Savoie", "75"=>"Paris", "76"=>"Seine-Maritime", "77"=>"Seine-et-Marne", "78"=>"Yvelines", "79"=>"Deux-Sèvres", "80"=>"Somme", "81"=>"Tarn", "82"=>"Tarn-et-Garonne", "83"=>"Var", "84"=>"Vaucluse", "85"=>"Vendée", "86"=>"Vienne", "87"=>"Haute-Vienne", "88"=>"Vosges", "89"=>"Yonne", "90"=>"Territoire de Belfort", "91"=>"Essonne", "92"=>"Hauts-de-Seine", "93"=>"Seine-Saint-Denis", "94"=>"Val-de-Marne", "95"=>"Val-d'Oise");
$libraryList['fr_timezone'] = array('Africa/Abidjan' => 'Africa - Abidjan','Africa/Accra' => 'Africa - Accra','Africa/Addis_Ababa' => 'Africa - Addis Ababa','Africa/Algiers' => 'Africa - Algiers','Africa/Asmara' => 'Africa - Asmara','Africa/Bamako' => 'Africa - Bamako','Africa/Bangui' => 'Africa - Bangui','Africa/Banjul' => 'Africa - Banjul','Africa/Bissau' => 'Africa - Bissau','Africa/Blantyre' => 'Africa - Blantyre','Africa/Brazzaville' => 'Africa - Brazzaville','Africa/Bujumbura' => 'Africa - Bujumbura','Africa/Cairo' => 'Africa - Cairo','Africa/Casablanca' => 'Africa - Casablanca','Africa/Ceuta' => 'Africa - Ceuta','Africa/Conakry' => 'Africa - Conakry','Africa/Dakar' => 'Africa - Dakar','Africa/Dar_es_Salaam' => 'Africa - Dar es Salaam','Africa/Djibouti' => 'Africa - Djibouti','Africa/Douala' => 'Africa - Douala','Africa/El_Aaiun' => 'Africa - El Aaiun','Africa/Freetown' => 'Africa - Freetown','Africa/Gaborone' => 'Africa - Gaborone','Africa/Harare' => 'Africa - Harare','Africa/Johannesburg' => 'Africa - Johannesburg','Africa/Kampala' => 'Africa - Kampala','Africa/Khartoum' => 'Africa - Khartoum','Africa/Kigali' => 'Africa - Kigali','Africa/Kinshasa' => 'Africa - Kinshasa','Africa/Lagos' => 'Africa - Lagos','Africa/Libreville' => 'Africa - Libreville','Africa/Lome' => 'Africa - Lome','Africa/Luanda' => 'Africa - Luanda','Africa/Lubumbashi' => 'Africa - Lubumbashi','Africa/Lusaka' => 'Africa - Lusaka','Africa/Malabo' => 'Africa - Malabo','Africa/Maputo' => 'Africa - Maputo','Africa/Maseru' => 'Africa - Maseru','Africa/Mbabane' => 'Africa - Mbabane','Africa/Mogadishu' => 'Africa - Mogadishu','Africa/Monrovia' => 'Africa - Monrovia','Africa/Nairobi' => 'Africa - Nairobi','Africa/Ndjamena' => 'Africa - Ndjamena','Africa/Niamey' => 'Africa - Niamey','Africa/Nouakchott' => 'Africa - Nouakchott','Africa/Ouagadougou' => 'Africa - Ouagadougou','Africa/Porto-Novo' => 'Africa - Porto-Novo','Africa/Sao_Tome' => 'Africa - Sao Tome','Africa/Tripoli' => 'Africa - Tripoli','Africa/Tunis' => 'Africa - Tunis','Africa/Windhoek' => 'Africa - Windhoek','America/Adak' => 'America - Adak','America/Anchorage' => 'America - Anchorage','America/Anguilla' => 'America - Anguilla','America/Antigua' => 'America - Antigua','America/Araguaina' => 'America - Araguaina','America/Argentina/Buenos_Aires' => 'America - Argentina - Buenos Aires','America/Argentina/Catamarca' => 'America - Argentina - Catamarca','America/Argentina/Cordoba' => 'America - Argentina - Cordoba','America/Argentina/Jujuy' => 'America - Argentina - Jujuy','America/Argentina/La_Rioja' => 'America - Argentina - La Rioja','America/Argentina/Mendoza' => 'America - Argentina - Mendoza','America/Argentina/Rio_Gallegos' => 'America - Argentina - Rio Gallegos','America/Argentina/Salta' => 'America - Argentina - Salta','America/Argentina/San_Juan' => 'America - Argentina - San Juan','America/Argentina/San_Luis' => 'America - Argentina - San Luis','America/Argentina/Tucuman' => 'America - Argentina - Tucuman','America/Argentina/Ushuaia' => 'America - Argentina - Ushuaia','America/Aruba' => 'America - Aruba','America/Asuncion' => 'America - Asuncion','America/Atikokan' => 'America - Atikokan','America/Bahia' => 'America - Bahia','America/Bahia_Banderas' => 'America - Bahia Banderas','America/Barbados' => 'America - Barbados','America/Belem' => 'America - Belem','America/Belize' => 'America - Belize','America/Blanc-Sablon' => 'America - Blanc-Sablon','America/Boa_Vista' => 'America - Boa Vista','America/Bogota' => 'America - Bogota','America/Boise' => 'America - Boise','America/Cambridge_Bay' => 'America - Cambridge Bay','America/Campo_Grande' => 'America - Campo Grande','America/Cancun' => 'America - Cancun','America/Caracas' => 'America - Caracas','America/Cayenne' => 'America - Cayenne','America/Cayman' => 'America - Cayman','America/Chicago' => 'America - Chicago','America/Chihuahua' => 'America - Chihuahua','America/Costa_Rica' => 'America - Costa Rica','America/Cuiaba' => 'America - Cuiaba','America/Curacao' => 'America - Curacao','America/Danmarkshavn' => 'America - Danmarkshavn','America/Dawson' => 'America - Dawson','America/Dawson_Creek' => 'America - Dawson Creek','America/Denver' => 'America - Denver','America/Detroit' => 'America - Detroit','America/Dominica' => 'America - Dominica','America/Edmonton' => 'America - Edmonton','America/Eirunepe' => 'America - Eirunepe','America/El_Salvador' => 'America - El Salvador','America/Fortaleza' => 'America - Fortaleza','America/Glace_Bay' => 'America - Glace Bay','America/Godthab' => 'America - Godthab','America/Goose_Bay' => 'America - Goose Bay','America/Grand_Turk' => 'America - Grand Turk','America/Grenada' => 'America - Grenada','America/Guadeloupe' => 'America - Guadeloupe','America/Guatemala' => 'America - Guatemala','America/Guayaquil' => 'America - Guayaquil','America/Guyana' => 'America - Guyana','America/Halifax' => 'America - Halifax','America/Havana' => 'America - Havana','America/Hermosillo' => 'America - Hermosillo','America/Indiana/Indianapolis' => 'America - Indiana - Indianapolis','America/Indiana/Knox' => 'America - Indiana - Knox','America/Indiana/Marengo' => 'America - Indiana - Marengo','America/Indiana/Petersburg' => 'America - Indiana - Petersburg','America/Indiana/Tell_City' => 'America - Indiana - Tell City','America/Indiana/Vevay' => 'America - Indiana - Vevay','America/Indiana/Vincennes' => 'America - Indiana - Vincennes','America/Indiana/Winamac' => 'America - Indiana - Winamac','America/Inuvik' => 'America - Inuvik','America/Iqaluit' => 'America - Iqaluit','America/Jamaica' => 'America - Jamaica','America/Juneau' => 'America - Juneau','America/Kentucky/Louisville' => 'America - Kentucky - Louisville','America/Kentucky/Monticello' => 'America - Kentucky - Monticello','America/Kralendijk' => 'America - Kralendijk','America/La_Paz' => 'America - La Paz','America/Lima' => 'America - Lima','America/Los_Angeles' => 'America - Los Angeles','America/Lower_Princes' => 'America - Lower Princes','America/Maceio' => 'America - Maceio','America/Managua' => 'America - Managua','America/Manaus' => 'America - Manaus','America/Marigot' => 'America - Marigot','America/Martinique' => 'America - Martinique','America/Matamoros' => 'America - Matamoros','America/Mazatlan' => 'America - Mazatlan','America/Menominee' => 'America - Menominee','America/Merida' => 'America - Merida','America/Metlakatla' => 'America - Metlakatla','America/Mexico_City' => 'America - Mexico City','America/Miquelon' => 'America - Miquelon','America/Moncton' => 'America - Moncton','America/Monterrey' => 'America - Monterrey','America/Montevideo' => 'America - Montevideo','America/Montreal' => 'America - Montreal','America/Montserrat' => 'America - Montserrat','America/Nassau' => 'America - Nassau','America/New_York' => 'America - New York','America/Nipigon' => 'America - Nipigon','America/Nome' => 'America - Nome','America/Noronha' => 'America - Noronha','America/North_Dakota/Beulah' => 'America - North Dakota - Beulah','America/North_Dakota/Center' => 'America - North Dakota - Center','America/North_Dakota/New_Salem' => 'America - North Dakota - New Salem','America/Ojinaga' => 'America - Ojinaga','America/Panama' => 'America - Panama','America/Pangnirtung' => 'America - Pangnirtung','America/Paramaribo' => 'America - Paramaribo','America/Phoenix' => 'America - Phoenix','America/Port-au-Prince' => 'America - Port-au-Prince','America/Port_of_Spain' => 'America - Port of Spain','America/Porto_Velho' => 'America - Porto Velho','America/Puerto_Rico' => 'America - Puerto Rico','America/Rainy_River' => 'America - Rainy River','America/Rankin_Inlet' => 'America - Rankin Inlet','America/Recife' => 'America - Recife','America/Regina' => 'America - Regina','America/Resolute' => 'America - Resolute','America/Rio_Branco' => 'America - Rio Branco','America/Santa_Isabel' => 'America - Santa Isabel','America/Santarem' => 'America - Santarem','America/Santiago' => 'America - Santiago','America/Santo_Domingo' => 'America - Santo Domingo','America/Sao_Paulo' => 'America - Sao Paulo','America/Scoresbysund' => 'America - Scoresbysund','America/Shiprock' => 'America - Shiprock','America/Sitka' => 'America - Sitka','America/St_Barthelemy' => 'America - St Barthelemy','America/St_Johns' => 'America - St Johns','America/St_Kitts' => 'America - St Kitts','America/St_Lucia' => 'America - St Lucia','America/St_Thomas' => 'America - St Thomas','America/St_Vincent' => 'America - St Vincent','America/Swift_Current' => 'America - Swift Current','America/Tegucigalpa' => 'America - Tegucigalpa','America/Thule' => 'America - Thule','America/Thunder_Bay' => 'America - Thunder Bay','America/Tijuana' => 'America - Tijuana','America/Toronto' => 'America - Toronto','America/Tortola' => 'America - Tortola','America/Vancouver' => 'America - Vancouver','America/Whitehorse' => 'America - Whitehorse','America/Winnipeg' => 'America - Winnipeg','America/Yakutat' => 'America - Yakutat','America/Yellowknife' => 'America - Yellowknife','Antarctica/Casey' => 'Antarctica - Casey','Antarctica/Davis' => 'Antarctica - Davis','Antarctica/DumontDUrville' => 'Antarctica - DumontDUrville','Antarctica/Macquarie' => 'Antarctica - Macquarie','Antarctica/Mawson' => 'Antarctica - Mawson','Antarctica/McMurdo' => 'Antarctica - McMurdo','Antarctica/Palmer' => 'Antarctica - Palmer','Antarctica/Rothera' => 'Antarctica - Rothera','Antarctica/South_Pole' => 'Antarctica - South Pole','Antarctica/Syowa' => 'Antarctica - Syowa','Antarctica/Vostok' => 'Antarctica - Vostok','Arctic/Longyearbyen' => 'Arctic - Longyearbyen','Asia/Aden' => 'Asia - Aden','Asia/Almaty' => 'Asia - Almaty','Asia/Amman' => 'Asia - Amman','Asia/Anadyr' => 'Asia - Anadyr','Asia/Aqtau' => 'Asia - Aqtau','Asia/Aqtobe' => 'Asia - Aqtobe','Asia/Ashgabat' => 'Asia - Ashgabat','Asia/Baghdad' => 'Asia - Baghdad','Asia/Bahrain' => 'Asia - Bahrain','Asia/Baku' => 'Asia - Baku','Asia/Bangkok' => 'Asia - Bangkok','Asia/Beirut' => 'Asia - Beirut','Asia/Bishkek' => 'Asia - Bishkek','Asia/Brunei' => 'Asia - Brunei','Asia/Choibalsan' => 'Asia - Choibalsan','Asia/Chongqing' => 'Asia - Chongqing','Asia/Colombo' => 'Asia - Colombo','Asia/Damascus' => 'Asia - Damascus','Asia/Dhaka' => 'Asia - Dhaka','Asia/Dili' => 'Asia - Dili','Asia/Dubai' => 'Asia - Dubai','Asia/Dushanbe' => 'Asia - Dushanbe','Asia/Gaza' => 'Asia - Gaza','Asia/Harbin' => 'Asia - Harbin','Asia/Ho_Chi_Minh' => 'Asia - Ho Chi Minh','Asia/Hong_Kong' => 'Asia - Hong Kong','Asia/Hovd' => 'Asia - Hovd','Asia/Irkutsk' => 'Asia - Irkutsk','Asia/Jakarta' => 'Asia - Jakarta','Asia/Jayapura' => 'Asia - Jayapura','Asia/Jerusalem' => 'Asia - Jerusalem','Asia/Kabul' => 'Asia - Kabul','Asia/Kamchatka' => 'Asia - Kamchatka','Asia/Karachi' => 'Asia - Karachi','Asia/Kashgar' => 'Asia - Kashgar','Asia/Kathmandu' => 'Asia - Kathmandu','Asia/Kolkata' => 'Asia - Kolkata','Asia/Krasnoyarsk' => 'Asia - Krasnoyarsk','Asia/Kuala_Lumpur' => 'Asia - Kuala Lumpur','Asia/Kuching' => 'Asia - Kuching','Asia/Kuwait' => 'Asia - Kuwait','Asia/Macau' => 'Asia - Macau','Asia/Magadan' => 'Asia - Magadan','Asia/Makassar' => 'Asia - Makassar','Asia/Manila' => 'Asia - Manila','Asia/Muscat' => 'Asia - Muscat','Asia/Nicosia' => 'Asia - Nicosia','Asia/Novokuznetsk' => 'Asia - Novokuznetsk','Asia/Novosibirsk' => 'Asia - Novosibirsk','Asia/Omsk' => 'Asia - Omsk','Asia/Oral' => 'Asia - Oral','Asia/Phnom_Penh' => 'Asia - Phnom Penh','Asia/Pontianak' => 'Asia - Pontianak','Asia/Pyongyang' => 'Asia - Pyongyang','Asia/Qatar' => 'Asia - Qatar','Asia/Qyzylorda' => 'Asia - Qyzylorda','Asia/Rangoon' => 'Asia - Rangoon','Asia/Riyadh' => 'Asia - Riyadh','Asia/Sakhalin' => 'Asia - Sakhalin','Asia/Samarkand' => 'Asia - Samarkand','Asia/Seoul' => 'Asia - Seoul','Asia/Shanghai' => 'Asia - Shanghai','Asia/Singapore' => 'Asia - Singapore','Asia/Taipei' => 'Asia - Taipei','Asia/Tashkent' => 'Asia - Tashkent','Asia/Tbilisi' => 'Asia - Tbilisi','Asia/Tehran' => 'Asia - Tehran','Asia/Thimphu' => 'Asia - Thimphu','Asia/Tokyo' => 'Asia - Tokyo','Asia/Ulaanbaatar' => 'Asia - Ulaanbaatar','Asia/Urumqi' => 'Asia - Urumqi','Asia/Vientiane' => 'Asia - Vientiane','Asia/Vladivostok' => 'Asia - Vladivostok','Asia/Yakutsk' => 'Asia - Yakutsk','Asia/Yekaterinburg' => 'Asia - Yekaterinburg','Asia/Yerevan' => 'Asia - Yerevan','Atlantic/Azores' => 'Atlantic - Azores','Atlantic/Bermuda' => 'Atlantic - Bermuda','Atlantic/Canary' => 'Atlantic - Canary','Atlantic/Cape_Verde' => 'Atlantic - Cape Verde','Atlantic/Faroe' => 'Atlantic - Faroe','Atlantic/Madeira' => 'Atlantic - Madeira','Atlantic/Reykjavik' => 'Atlantic - Reykjavik','Atlantic/South_Georgia' => 'Atlantic - South Georgia','Atlantic/Stanley' => 'Atlantic - Stanley','Atlantic/St_Helena' => 'Atlantic - St Helena','Australia/Adelaide' => 'Australia - Adelaide','Australia/Brisbane' => 'Australia - Brisbane','Australia/Broken_Hill' => 'Australia - Broken Hill','Australia/Currie' => 'Australia - Currie','Australia/Darwin' => 'Australia - Darwin','Australia/Eucla' => 'Australia - Eucla','Australia/Hobart' => 'Australia - Hobart','Australia/Lindeman' => 'Australia - Lindeman','Australia/Lord_Howe' => 'Australia - Lord Howe','Australia/Melbourne' => 'Australia - Melbourne','Australia/Perth' => 'Australia - Perth','Australia/Sydney' => 'Australia - Sydney','Europe/Amsterdam' => 'Europe - Amsterdam','Europe/Andorra' => 'Europe - Andorra','Europe/Athens' => 'Europe - Athens','Europe/Belgrade' => 'Europe - Belgrade','Europe/Berlin' => 'Europe - Berlin','Europe/Bratislava' => 'Europe - Bratislava','Europe/Brussels" selected="selected' => 'Europe - Brussels','Europe/Bucharest' => 'Europe - Bucharest','Europe/Budapest' => 'Europe - Budapest','Europe/Chisinau' => 'Europe - Chisinau','Europe/Copenhagen' => 'Europe - Copenhagen','Europe/Dublin' => 'Europe - Dublin','Europe/Gibraltar' => 'Europe - Gibraltar','Europe/Guernsey' => 'Europe - Guernsey','Europe/Helsinki' => 'Europe - Helsinki','Europe/Isle_of_Man' => 'Europe - Isle of Man','Europe/Istanbul' => 'Europe - Istanbul','Europe/Jersey' => 'Europe - Jersey','Europe/Kaliningrad' => 'Europe - Kaliningrad','Europe/Kiev' => 'Europe - Kiev','Europe/Lisbon' => 'Europe - Lisbon','Europe/Ljubljana' => 'Europe - Ljubljana','Europe/London' => 'Europe - London','Europe/Luxembourg' => 'Europe - Luxembourg','Europe/Madrid' => 'Europe - Madrid','Europe/Malta' => 'Europe - Malta','Europe/Mariehamn' => 'Europe - Mariehamn','Europe/Minsk' => 'Europe - Minsk','Europe/Monaco' => 'Europe - Monaco','Europe/Moscow' => 'Europe - Moscow','Europe/Oslo' => 'Europe - Oslo','Europe/Paris' => 'Europe - Paris','Europe/Podgorica' => 'Europe - Podgorica','Europe/Prague' => 'Europe - Prague','Europe/Riga' => 'Europe - Riga','Europe/Rome' => 'Europe - Rome','Europe/Samara' => 'Europe - Samara','Europe/San_Marino' => 'Europe - San Marino','Europe/Sarajevo' => 'Europe - Sarajevo','Europe/Simferopol' => 'Europe - Simferopol','Europe/Skopje' => 'Europe - Skopje','Europe/Sofia' => 'Europe - Sofia','Europe/Stockholm' => 'Europe - Stockholm','Europe/Tallinn' => 'Europe - Tallinn','Europe/Tirane' => 'Europe - Tirane','Europe/Uzhgorod' => 'Europe - Uzhgorod','Europe/Vaduz' => 'Europe - Vaduz','Europe/Vatican' => 'Europe - Vatican','Europe/Vienna' => 'Europe - Vienna','Europe/Vilnius' => 'Europe - Vilnius','Europe/Volgograd' => 'Europe - Volgograd','Europe/Warsaw' => 'Europe - Warsaw','Europe/Zagreb' => 'Europe - Zagreb','Europe/Zaporozhye' => 'Europe - Zaporozhye','Europe/Zurich' => 'Europe - Zurich','Indian/Antananarivo' => 'Indian - Antananarivo','Indian/Chagos' => 'Indian - Chagos','Indian/Christmas' => 'Indian - Christmas','Indian/Cocos' => 'Indian - Cocos','Indian/Comoro' => 'Indian - Comoro','Indian/Kerguelen' => 'Indian - Kerguelen','Indian/Mahe' => 'Indian - Mahe','Indian/Maldives' => 'Indian - Maldives','Indian/Mauritius' => 'Indian - Mauritius','Indian/Mayotte' => 'Indian - Mayotte','Indian/Reunion' => 'Indian - Reunion','Pacific/Apia' => 'Pacific - Apia','Pacific/Auckland' => 'Pacific - Auckland','Pacific/Chatham' => 'Pacific - Chatham','Pacific/Chuuk' => 'Pacific - Chuuk','Pacific/Easter' => 'Pacific - Easter','Pacific/Efate' => 'Pacific - Efate','Pacific/Enderbury' => 'Pacific - Enderbury','Pacific/Fakaofo' => 'Pacific - Fakaofo','Pacific/Fiji' => 'Pacific - Fiji','Pacific/Funafuti' => 'Pacific - Funafuti','Pacific/Galapagos' => 'Pacific - Galapagos','Pacific/Gambier' => 'Pacific - Gambier','Pacific/Guadalcanal' => 'Pacific - Guadalcanal','Pacific/Guam' => 'Pacific - Guam','Pacific/Honolulu' => 'Pacific - Honolulu','Pacific/Johnston' => 'Pacific - Johnston','Pacific/Kiritimati' => 'Pacific - Kiritimati','Pacific/Kosrae' => 'Pacific - Kosrae','Pacific/Kwajalein' => 'Pacific - Kwajalein','Pacific/Majuro' => 'Pacific - Majuro','Pacific/Marquesas' => 'Pacific - Marquesas','Pacific/Midway' => 'Pacific - Midway','Pacific/Nauru' => 'Pacific - Nauru','Pacific/Niue' => 'Pacific - Niue','Pacific/Norfolk' => 'Pacific - Norfolk','Pacific/Noumea' => 'Pacific - Noumea','Pacific/Pago_Pago' => 'Pacific - Pago Pago','Pacific/Palau' => 'Pacific - Palau','Pacific/Pitcairn' => 'Pacific - Pitcairn','Pacific/Pohnpei' => 'Pacific - Pohnpei','Pacific/Port_Moresby' => 'Pacific - Port Moresby','Pacific/Rarotonga' => 'Pacific - Rarotonga','Pacific/Saipan' => 'Pacific - Saipan','Pacific/Tahiti' => 'Pacific - Tahiti','Pacific/Tarawa' => 'Pacific - Tarawa','Pacific/Tongatapu' => 'Pacific - Tongatapu','Pacific/Wake' => 'Pacific - Wake','Pacific/Wallis' => 'Pacific - Wallis');
// Impossible de fournir une liste complète, néanmoins on rajoute au fur et a mesure les domaine polueur
$libraryList['mailjetable'] = array(
    'null', /* Pour les faux positif */
    'mailcatch.com',
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
    'get2mail.fr',
    'armyspy.com',
    'cuvox.de',
    'dayrep.com',
    'einrot.com',
    'fleckens.hu',
    'gustr.com',
    'jourrapide.com',
    'rhyta.com',
    'superrito.com',
    'teleworm.us'
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
function cwDebug () {
	$debug = debug_backtrace ();
	echo '<p>&nbsp;</p><p><a href="#" onclick="jQuery(this).parent().next(\'ol\').slideToggle(); return false;"><strong>' . $debug [0] ['file'] . ' </strong> l.' . $debug [0] ['line'] . '</a></p>';
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
    for ($i = 0; $i < $numargs; $i++) {
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
	if (__DEV_MODE) { 
		$debug = debug_backtrace ();
		echo '<p>&nbsp;</p><p><a href="#" onclick="jQuery(this).parent().next(\'ol\').slideToggle(); return false;"><strong>' . $debug [0] ['file'] . ' </strong> l.' . $debug [0] ['line'] . '</a></p>';
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
	    for ($i = 0; $i < $numargs; $i++) {
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
	
	if (__DEV_MODE) {
		echo ("<div class=\"well\">
			<p><strong>Controller :</strong> ".$error_array ['controller']."</p>
			<p><strong>Type :</strong> ".$error_array ['type']." " . $error_array ['msg'] . "</p>
			<p><strong>Ligne :</strong> ".$error_array ['errline']." ".$error_array ['errfile']."</p>
		</div>");
	}
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
function is_ie() {
$user_agent = (isSet($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT']: '';
$match=preg_match('/msie ([0-9]\.[0-9])/',strtolower($user_agent),$reg);
if($match==0) return false;
else return floatval($reg[1]);
}



/*........ ooO Date et temps Ooo ........ */

	function differenceTime($time) {
	$inMinute = $day = $hour = $minute = $seconde = 0;

	$timeWithCd = $time;
	$boolean = false;
		if ($timeWithCd >= time()) {
			// Seconde restant
			$seconde = $timeWithCd - time();
			$inMinute = ceil(($timeWithCd - time()) / 60 );
			
			// Seconde est inférieur a 0
			if ($seconde < 0) { 
				$boolean = false;
				$seconde = 0;
			} else {
				$boolean = true;
				// Nombre de jour
				$day = floor($seconde / 86400);
				// Soustraite les jours
				$seconde -= ($day * 86400);

				// Nombre d'heure
				$hour = floor($seconde/3600);
				$seconde -= ($hour*3600);

				$minute = floor($seconde/60);
				$seconde -= ($minute * 60);

				$seconde = floor($seconde);
			}
		}
		$hour = (strlen( $hour ) == 1) ? '0'.$hour : $hour;
		$minute = (strlen( $minute ) == 1) ? '0' . $minute : $minute;
		$seconde = (strlen( $seconde ) == 1) ? '0' . $seconde : $seconde;
		return array('bool' => $boolean, 'day' => $day, 'hour' => $hour, 'minute' => $minute, 'seconde' => $seconde, 'inMinute' => $inMinute);
	}

/**
 * 
 * Decoupe un timestamp en jour|heure|minute|seconde
 * @param int $time
 * @return array day,hour,minute,seconde
 */
function minute($time) {
	// Temps en secondes
	$s = time() - $time;// / 1000;
	if ($s < 0) {
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
function getmicrotime() {
    if (function_exists('gettimeofday')) {
    // retourne le timestamp Unix, avec les microsecondes.
    // Cette fonction est uniquement disponible
    //  sur les systèmes qui supportent la fonction gettimeofday().
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
    } else {
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
function ecartdate($fin) {
    if (!is_int($fin)) {
        $debut = date("d/m/y");
        list($jourDebut, $moisDebut, $anneeDebut) = explode('/', $debut);
        list($jourFin, $moisFin, $anneeFin) = explode('/', $fin);
        $timestampDebut = mktime(0,0,0,$moisDebut,$jourDebut,$anneeDebut);
        $timestampFin = mktime(0,0,0,$moisFin,$jourFin,$anneeFin);
        $ecart = abs($timestampFin - $timestampDebut)/86400;
        /*$s = ($ecart>1) ? 's' : '';
        $annonce = "Il vous reste ". $ecart ." jour" . $s . " d'offre";*/
        return $ecart; // $annonce;
    } else {
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
function getRelativeTime($date) {
     // Test si $date est numerique et donc un timestamp
    if (is_numeric($date)) {
    $time = time() - $date;
    } else {
	// Si pas c'est une date 2010-12-31 13:25:00
	// Déduction de la date donnée à la date actuelle
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
    31104000 =>  'an{s}',        // 12 * 30 * 24 * 60 * 60 secondes
    2592000  =>  'mois',          // 30 * 24 * 60 * 60 secondes
    86400    =>  'jour{s}',   // 24 * 60 * 60 secondes
    3600      =>  'heure{s}',    // 60 * 60 secondes
    60       =>  'minute{s}',   // 60 secondes
    1         =>  'seconde{s}'); // 1 seconde

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



/**
* Retourne la date et heure en français
*
* @link http://crystal-web.org
* @author Christophe BUFFET
* @param int $time|timestamp
* @param string $format|fr_date ou fr_datetime
* @return string
*/
function dates($time, $format) {
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
	if ($format=="fr_date") {
	    return $j." ".date("j",$time)." ".$m." ".date("Y",$time);
	} elseif ($format=="fr_datetime") {
	    return $j." ".date("j",$time)." ".$m." ".date("Y",$time).", &agrave; ".date("G:i",$time);
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
function nl2null($string) {
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

				$string = stripAccents($string);
				// Remplacé par stripAccents()
				//$string = preg_replace ( '#\&([A-Za-z])(?:grave|acute|circ|tilde|uml|ring|cedil)\;#', '\1', $string );
				//$string = preg_replace ( '#\&([A-Za-z]{2})(?:lig)\;#', '\1', $string );
				//$string = preg_replace ( '#\&([A-Za-z])(.*)\;#', '', $string );
				$string = str_replace ( "'", '-', $string );
				$string = str_replace ( ' ', '-', $string );
				
				$string = str_replace ( '--', '-', $string );
				//  $string = strtolower($string);
				$string = trim ( $string, '-' );
				$string = preg_replace ( '#[^A-Za-z0-9_\-]#', '', $string );
				$string = trim ( $string, '-' );
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
	
	return $string;

}


/**
*   @desc       Réecriture d'url Crystal-Web
*   @author     Christophe BUFFET <developpeur@crystal-web.org>
*   @copyright  Open Source
*   @deprecated
*/
function cleanerUrl($url) {
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
function is_serialized($value, &$result = null) {
	// Bit of a give away this one
	if (!is_string($value)) {
		return false;
	}

	// Serialized false, return true. unserialize() returns false on an
	// invalid string or it could return false if the string is serialized
	// false, eliminate that possibility.
	if ($value === 'b:0;') {
		$result = false;
		return true;
	}

	$length	= strlen($value);
	$end	= '';
	$value[0] = (isset($value[0])) ? $value[0] : 0; 
	switch ($value[0]) {
		case 's':
			if (!isset($value[$length - 2]) || $value[$length - 2] !== '"') {
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

			if ($value[1] !== ':') {
				return false;
			}
			$value[2] = (isset($value[2])) ? $value[2] : 0;
			switch ($value[2]) {
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
			
			$value[2] = (isset($value[2])) ? $value[2] : 0;
			$value[2] = (isset($value[$length - 1])) ? $value[2] : 0;
			if ($value[$length - 1] !== $end[0]) {
				return false;
			}
		break;

		default:
			return false;
	}
	noError(true);
	if (($result = @unserialize($value)) === false) {
		$result = null;
		return false;
	}
	noError(false);
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
function isURL($url) {
return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}


/**
 * 
 * Protection lors de l'ecriture sur le site
 * @param string $string
 * @param string $has
 * @deprecated
 */
function hasstr($string, $has = 'bbcode') {
    switch($has) {
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
function stripAccents($string) {
	// Assurance pour les spéciaux
	$string = preg_replace ( '#\&([A-Za-z]{2})(?:lig)\;#', '\1', $string );
	$accents = '/&([A-Za-z]{1,2})(grave|acute|circ|cedil|uml|lig);/';
	$string = preg_replace($accents,'$1',$string);
	
	// Decode les entités pour en faire les caractères originel
	$string = html_entity_decode($string, ENT_COMPAT,  'UTF-8');
	
	// Table de caractère dont je ne comprend pas toute les finisses
	$chars = array(
		// Decompositions for Latin-1 Supplement
		chr(194).chr(170) => 'a', chr(194).chr(186) => 'o',
		chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
		chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
		chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
		chr(195).chr(134) => 'AE',chr(195).chr(135) => 'C',
		chr(195).chr(136) => 'E', chr(195).chr(137) => 'E',
		chr(195).chr(138) => 'E', chr(195).chr(139) => 'E',
		chr(195).chr(140) => 'I', chr(195).chr(141) => 'I',
		chr(195).chr(142) => 'I', chr(195).chr(143) => 'I',
		chr(195).chr(144) => 'D', chr(195).chr(145) => 'N',
		chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
		chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
		chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
		chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
		chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
		chr(195).chr(158) => 'TH',chr(195).chr(159) => 's',
		chr(195).chr(160) => 'a', chr(195).chr(161) => 'a',
		chr(195).chr(162) => 'a', chr(195).chr(163) => 'a',
		chr(195).chr(164) => 'a', chr(195).chr(165) => 'a',
		chr(195).chr(166) => 'ae',chr(195).chr(167) => 'c',
		chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
		chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
		chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
		chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
		chr(195).chr(176) => 'd', chr(195).chr(177) => 'n',
		chr(195).chr(178) => 'o', chr(195).chr(179) => 'o',
		chr(195).chr(180) => 'o', chr(195).chr(181) => 'o',
		chr(195).chr(182) => 'o', chr(195).chr(184) => 'o',
		chr(195).chr(185) => 'u', chr(195).chr(186) => 'u',
		chr(195).chr(187) => 'u', chr(195).chr(188) => 'u',
		chr(195).chr(189) => 'y', chr(195).chr(190) => 'th',
		chr(195).chr(191) => 'y', chr(195).chr(152) => 'O',
		// Decompositions for Latin Extended-A
		chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
		chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
		chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
		chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
		chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
		chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
		chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
		chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
		chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
		chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
		chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
		chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
		chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
		chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
		chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
		chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
		chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
		chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
		chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
		chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
		chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
		chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
		chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
		chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
		chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
		chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
		chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
		chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
		chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
		chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
		chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
		chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
		chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
		chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
		chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
		chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
		chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
		chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
		chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
		chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
		chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
		chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
		chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
		chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
		chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
		chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
		chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
		chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
		chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
		chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
		chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
		chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
		chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
		chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
		chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
		chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
		chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
		chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
		chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
		chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
		chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
		chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
		chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
		chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
		// Decompositions for Latin Extended-B
		chr(200).chr(152) => 'S', chr(200).chr(153) => 's',
		chr(200).chr(154) => 'T', chr(200).chr(155) => 't',
		// Euro Sign
		chr(226).chr(130).chr(172) => 'E',
		// GBP (Pound) Sign
		chr(194).chr(163) => '',
		// Vowels with diacritic (Vietnamese)
		// unmarked
		chr(198).chr(160) => 'O', chr(198).chr(161) => 'o',
		chr(198).chr(175) => 'U', chr(198).chr(176) => 'u',
		// grave accent
		chr(225).chr(186).chr(166) => 'A', chr(225).chr(186).chr(167) => 'a',
		chr(225).chr(186).chr(176) => 'A', chr(225).chr(186).chr(177) => 'a',
		chr(225).chr(187).chr(128) => 'E', chr(225).chr(187).chr(129) => 'e',
		chr(225).chr(187).chr(146) => 'O', chr(225).chr(187).chr(147) => 'o',
		chr(225).chr(187).chr(156) => 'O', chr(225).chr(187).chr(157) => 'o',
		chr(225).chr(187).chr(170) => 'U', chr(225).chr(187).chr(171) => 'u',
		chr(225).chr(187).chr(178) => 'Y', chr(225).chr(187).chr(179) => 'y',
		// hook
		chr(225).chr(186).chr(162) => 'A', chr(225).chr(186).chr(163) => 'a',
		chr(225).chr(186).chr(168) => 'A', chr(225).chr(186).chr(169) => 'a',
		chr(225).chr(186).chr(178) => 'A', chr(225).chr(186).chr(179) => 'a',
		chr(225).chr(186).chr(186) => 'E', chr(225).chr(186).chr(187) => 'e',
		chr(225).chr(187).chr(130) => 'E', chr(225).chr(187).chr(131) => 'e',
		chr(225).chr(187).chr(136) => 'I', chr(225).chr(187).chr(137) => 'i',
		chr(225).chr(187).chr(142) => 'O', chr(225).chr(187).chr(143) => 'o',
		chr(225).chr(187).chr(148) => 'O', chr(225).chr(187).chr(149) => 'o',
		chr(225).chr(187).chr(158) => 'O', chr(225).chr(187).chr(159) => 'o',
		chr(225).chr(187).chr(166) => 'U', chr(225).chr(187).chr(167) => 'u',
		chr(225).chr(187).chr(172) => 'U', chr(225).chr(187).chr(173) => 'u',
		chr(225).chr(187).chr(182) => 'Y', chr(225).chr(187).chr(183) => 'y',
		// tilde
		chr(225).chr(186).chr(170) => 'A', chr(225).chr(186).chr(171) => 'a',
		chr(225).chr(186).chr(180) => 'A', chr(225).chr(186).chr(181) => 'a',
		chr(225).chr(186).chr(188) => 'E', chr(225).chr(186).chr(189) => 'e',
		chr(225).chr(187).chr(132) => 'E', chr(225).chr(187).chr(133) => 'e',
		chr(225).chr(187).chr(150) => 'O', chr(225).chr(187).chr(151) => 'o',
		chr(225).chr(187).chr(160) => 'O', chr(225).chr(187).chr(161) => 'o',
		chr(225).chr(187).chr(174) => 'U', chr(225).chr(187).chr(175) => 'u',
		chr(225).chr(187).chr(184) => 'Y', chr(225).chr(187).chr(185) => 'y',
		// acute accent
		chr(225).chr(186).chr(164) => 'A', chr(225).chr(186).chr(165) => 'a',
		chr(225).chr(186).chr(174) => 'A', chr(225).chr(186).chr(175) => 'a',
		chr(225).chr(186).chr(190) => 'E', chr(225).chr(186).chr(191) => 'e',
		chr(225).chr(187).chr(144) => 'O', chr(225).chr(187).chr(145) => 'o',
		chr(225).chr(187).chr(154) => 'O', chr(225).chr(187).chr(155) => 'o',
		chr(225).chr(187).chr(168) => 'U', chr(225).chr(187).chr(169) => 'u',
		// dot below
		chr(225).chr(186).chr(160) => 'A', chr(225).chr(186).chr(161) => 'a',
		chr(225).chr(186).chr(172) => 'A', chr(225).chr(186).chr(173) => 'a',
		chr(225).chr(186).chr(182) => 'A', chr(225).chr(186).chr(183) => 'a',
		chr(225).chr(186).chr(184) => 'E', chr(225).chr(186).chr(185) => 'e',
		chr(225).chr(187).chr(134) => 'E', chr(225).chr(187).chr(135) => 'e',
		chr(225).chr(187).chr(138) => 'I', chr(225).chr(187).chr(139) => 'i',
		chr(225).chr(187).chr(140) => 'O', chr(225).chr(187).chr(141) => 'o',
		chr(225).chr(187).chr(152) => 'O', chr(225).chr(187).chr(153) => 'o',
		chr(225).chr(187).chr(162) => 'O', chr(225).chr(187).chr(163) => 'o',
		chr(225).chr(187).chr(164) => 'U', chr(225).chr(187).chr(165) => 'u',
		chr(225).chr(187).chr(176) => 'U', chr(225).chr(187).chr(177) => 'u',
		chr(225).chr(187).chr(180) => 'Y', chr(225).chr(187).chr(181) => 'y',
		// Vowels with diacritic (Chinese, Hanyu Pinyin)
		chr(201).chr(145) => 'a',
		// macron
		chr(199).chr(149) => 'U', chr(199).chr(150) => 'u',
		// acute accent
		chr(199).chr(151) => 'U', chr(199).chr(152) => 'u',
		// caron
		chr(199).chr(141) => 'A', chr(199).chr(142) => 'a',
		chr(199).chr(143) => 'I', chr(199).chr(144) => 'i',
		chr(199).chr(145) => 'O', chr(199).chr(146) => 'o',
		chr(199).chr(147) => 'U', chr(199).chr(148) => 'u',
		chr(199).chr(153) => 'U', chr(199).chr(154) => 'u',
		// grave accent
		chr(199).chr(155) => 'U', chr(199).chr(156) => 'u',
	);
	return strtr($string, $chars);
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
 * TODO $safe_word bug, renvois la chaine complete
 * voir --> http://stackoverflow.com/questions/79960/how-to-truncate-a-string-in-php-to-the-word-closest-to-a-certain-number-of-chara
 * @author Christophe BUFFET
 * @link http://crystal-web.org
 * @param string $string|chaine a tronquer
 * @param int $lengthMax|longueur max de la chaine
 * @param bool $safe_word|Empécher le troncage de mots
 * @param string $append caractère a mettre a la fin ou NULL (default: ...)
 * @return string
 */
function truncatestr($string, $lengthMax, $safe_word=false, $append = '...') {
if (strlen($string) < $lengthMax) { return $string; }

    if ($safe_word == true) {
    // Récupération de la position du dernier espace (afin déviter de tronquer un mot)
    $position_espace = strrpos($string, " ");
        if ($position_espace) {
    	    $string = substr($string, 0, $position_espace);
        }
    } else {
 	   $string = substr($string, 0, $lengthMax);
    }
    // Ajout de $append
    $string .= $append;
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
	return truncatestr($string, $lengthMax);
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


function convert($size, $precision = 2) {
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
function is_paire($nombre) {
return ($nombre%2 == 0) ? true : false;
}


/*........ ooO Dossiers et fichiers Ooo ........ */


/**
 * Retourne les informations sur le fichier demandé
 * 
 * @link http://www.askapache.com/security/chmod-stat.html
 * @param string $file
 */
function alt_stat($file) {
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
function rmdir_recursive($dir, $delDir = false) {
	
    //Liste le contenu du répertoire dans un tableau
    $dir_content = scandir($dir);
    //Est-ce bien un répertoire?
    if($dir_content !== FALSE) {
        //Pour chaque entrée du répertoire
        foreach ($dir_content as $entry) {
            //Raccourcis symboliques sous Unix, on passe
            if(!in_array($entry, array('.','..'))) {
                //On retrouve le chemin par rapport au début
                $entry = $dir . '/' . $entry;
                //Cette entrée n'est pas un dossier: on l'efface
                if(!is_dir($entry)) {
                   unlink($entry);
                }  else {//Cette entrée est un dossier, on recommence sur ce dossier
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


/*........ ooO GUI ou presque Ooo ........ */


/**
 * 
 * Crée la barre vertical du menu administrateur
 * @param array $adminSiderbar
 */
function getOperatorSidebar($adminSiderbar) {
echo '<div class="opSiderbar" id="opSiderbar"><ul id="opMenu">';

    foreach ($adminSiderbar AS $k=>$d) {
    echo '<li class="toggleSubMenu"><span>'.$d['title'].'</span><ul class="subMenu">';
        foreach ($d['data'] AS $url => $data) {
        echo '<li><a href="'.$data.'">'.$url.'</a></li>';

        }
    echo '</ul></li>';
    }
echo '</ul><a class="settingbutton" href="#">   </a></div>';
}

/**
 * Pagination page par page
 * 
 * @param int $nb_page Nombre total de page
 * @param string $nextTxt Texte du bouton suivant
 * @param string $previousTxt Texte du bouton précédant
 * @return string html ul class=pager>li
 */
function pager($nb_page, $nextTxt = 'Page suivante', $previousTxt = 'Page pr&eacute;c&eacute;dante', $currentUrl = false) {
$page = (int) (isset($_GET['page'])) ? clean($_GET['page'], 'num') : 1;
	if ($page == $nb_page) {
		return '<div class="clearfix"></div><ul class="pager">
			<li class="next disabled"><a href="#"> '.$nextTxt.' →</a></li>
			<li class="previous"><a href="'.$currentUrl.'?page='.($page-1).'">← ' . $previousTxt. ' </a></li>
		</ul>';
	} elseif ($page == 1) {
		return '<div class="clearfix"></div><ul class="pager">
			<li class="next"><a href="'.$currentUrl.'?page='.($page+1).'"> '.$nextTxt.' →</a></li>
			<li class="previous disabled"><a href="#">← ' . $previousTxt. ' </a></li>
		</ul>';
	} else {
		return '<div class="clearfix"></div><ul class="pager">
			<li class="next"><a href="'.$currentUrl.'?page='.($page+1).'"> '.$nextTxt.' →</a></li>
			<li class="previous"><a href="'.$currentUrl.'?page='.($page-1).'">← ' . $previousTxt. ' </a></li>
		</ul>';
	}
}


/**
 * 
 * Crée une pagination
 * @param int $nb_page
 */
function pagination($lastpage, 
			$nextTxt = 'Suivant', 
			$previousTxt = 'Pr&eacute;c&eacute;dent',
			$addClass = 'pagination-centered',  
			$adjacents = 2,
			$targetpage = NULL) {

	$page = (int) (isset($_GET['page'])) ? $_GET['page'] : 1;
	unset($_GET['page']);

	//defaults
	if(!$adjacents) $adjacents = 1;
	if(!$page) $page = 1;
	if (!is_null($addClass)) $addClass = ' ' . $addClass;
	
	$pagestring = '?';
	foreach ($_GET as $key => $value) {
		$pagestring .= $key.'='.$value.'&';
	}
	$pagestring .= "page=";
	
	//other vars
	$prev = $page - 1;									//previous page is page - 1
	$next = $page + 1;									//next page is page + 1
	$lpm1 = $lastpage - 1;								//last page minus 1
	
	$pagination = "";
	if($lastpage > 1) {	
		$pagination .= "<div class=\"clearfix\"></div><ul class=\"pagination$addClass\">";
		//$pagination .= "<ul>";
		
		//previous button
		if ($page > 1) { 
			$pagination .= "<li class=\"prev\"><a href=\"$targetpage$pagestring$prev\">← $previousTxt</a></li>";
		} else {
			$pagination .= "<li class=\"prev disabled\"><a href=\"#\">← $previousTxt</a></li>";	
		}
	
		//pages	
		if ($lastpage < 7 + ($adjacents * 2)) {
			for ($counter = 1; $counter <= $lastpage; $counter++) {
				if ($counter == $page) {
					$pagination .= "<li class=\"current disabled\"><a href=\"#\">$counter</a></li>";
				} else {
					$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a></li>";
				}					
			}
		} elseif($lastpage >= 7 + ($adjacents * 2))	{
			if($page < 1 + ($adjacents * 3)) {
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
					if ($counter == $page) {
						$pagination .= "<li class=\"current disabled\"><a href=\"#\">$counter</a></li>";
					} else {
						$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a></li>";
					}					
				}
				$pagination .= "<li class=\"current disabled elipses\"><a href=\"#\">...</a></li>";
				$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a></li>";
				$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a></li>";
			} elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
				$pagination .= "<li><a href=\"" . $targetpage . $pagestring . "1\">1</a></li>";
				$pagination .= "<li><a href=\"" . $targetpage . $pagestring . "2\">2</a></li>";
				$pagination .= "<li class=\"current disabled elipses\"><a href=\"#\">...</a></li>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
					if ($counter == $page) {
						$pagination .= "<li class=\"current disabled elipses\"><a href=\"#\">$counter</a></li>";
					} else {
						$pagination .= "<li class=\"current elipses\"><a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a></li>";
					}					
				}
				$pagination .= "<li class=\"current disabled elipses\"><a href=\"#\">...</a></li>";
				$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a></li>";
				$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a></li>";
			} else {
				$pagination .= "<li><a href=\"" . $targetpage . $pagestring . "1\">1</a></li>";
				$pagination .= "<li><a href=\"" . $targetpage . $pagestring . "2\">2</a></li>";
				$pagination .= "<li class=\"current disabled elipses\"><a href=\"#\">...</a></li>";
				for ($counter = $lastpage - (1 + ($adjacents * 3)); $counter <= $lastpage; $counter++) {
					if ($counter == $page) {
						$pagination .= "<li class=\"current disabled\"><a href=\"#\">$counter</a></li>";
					} else {
						$pagination .= "<li><a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a></li>";
					}					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) { 
			$pagination .= "<li class=\"next\"><a href=\"" . $targetpage . $pagestring . ($page + 1) . "\">$nextTxt →</a></li>";
		} else {
			$pagination .= "<li class=\"next disabled\"><a href=\"#\">$nextTxt →</a></li>";
		}
		
		$pagination .= "</ul>";	

	}
	
	return $pagination;
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
function alerte($msg, $echo = false) {
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


function getAvatar($user, $avatar = null, $mail = null) {
	if (!empty($avatar) && file_exists(__PUBLIC_PATH . DS . '/media/avatar/'.$avatar)) {
		return __CW_PATH.'/media/avatar/'.$avatar;			
	} elseif (file_exists(__PUBLIC_PATH . DS . 'media' . DS . 'avatar' . DS . $user.'.png')) {
		return __CW_PATH.'/media/avatar/'.$user.'.png';
	} elseif (file_exists(__PUBLIC_PATH . DS . 'media' . DS . 'avatar' . DS . $user.'.jpg')) {
		return __CW_PATH.'/media/avatar/'.$user.'.jpg';
	} elseif (file_exists(__PUBLIC_PATH . DS . 'media' . DS . 'avatar' . DS . $user.'.jpeg')) {
		return __CW_PATH.'/media/avatar/'.$user.'.jpeg';
	} else { 
		return get_gravatar($mail);
	}
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
function array_searchRecursive( $needle, $haystack, $strict=false, $path=array() ) {
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


function getMagik() {
	$crt = file_get_contents(__APP_PATH . DS . 'cache'.DS.'site.crt');
	if (!$crt) {
		$crt = (int) exec("find ../ -type f -name '*.php' -exec wc -l {} \; | awk '{sum+=$1}END{print sum}'");
		$crt = trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, sha1($crt), sha1(__CW_PATH), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
		if (file_put_contents(__APP_PATH . DS . 'cache'.DS.'site.crt', $crt, LOCK_EX) === false) {
	        throw new Exception('Impossible d\'&eacute;crire le fichier cache');
	    }
	}
	return $crt;
}


/**
* Savoir si le cient est connecté
*
* @link http://crystal-web.org
* @author Christophe BUFFET
* @return bool
* @deprecated utilisé $this->mvc->Session->isLogged()
*/
function is_connected() {
    return Auth::isAuth();
}



function _get_bytes($asString) {
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


function _format_bytes($a_bytes) {
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
function google_pagerank($url, $server = 'toolbarqueries.google.com') {
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

	if ( ($c = @file_get_contents($requestUrl)) === false ) {
		return false;
	} elseif( empty($c) ) {
		return -1;
	} else {
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


function url_response_code($url, &$contents = null) {
    $context = null;
    if (func_num_args() == 1) {
        $context = stream_context_create(array('http' => array('method' => 'HEAD')));
    }
    $contents = @file_get_contents($url, null, $context);
    $code = false;
    if (isset($http_response_header)) { 
        foreach ($http_response_header as $header) {
            if (strpos($header, 'HTTP/') === 0) {
                list(, $code) = explode(' ', $header);
            }
        } 
    }
    return $code;
}

// http://www.php.net/manual/fr/features.commandline.php#78651
function getNaturalArgs($args) {
 $out = array();
 $last_arg = null;
    for($i = 1, $il = sizeof($args); $i < $il; $i++) {
        if( (bool)preg_match("/^--(.+)/", $args[$i], $match) ) {
         $parts = explode("=", $match[1]);
         $key = preg_replace("/[^a-z0-9]+/", "", $parts[0]);
            if(isset($parts[1])) {
             $out[$key] = $parts[1];    
            }
            else {
             $out[$key] = true;    
            }
         $last_arg = $key;
        }
        else if( (bool)preg_match("/^-([a-zA-Z0-9]+)/", $args[$i], $match) ) {
            for( $j = 0, $jl = strlen($match[1]); $j < $jl; $j++ ) {
             $key = $match[1]{$j};
             $out[$key] = true;
            }
         $last_arg = $key;
        }
        else if($last_arg !== null) {
         $out[$last_arg] = $args[$i];
        }
    }
 return $out;
}

function cliGetArguments($argv) {
	$_ARG = array();
	foreach ($argv as $arg) {
		if (preg_match('#^-{1,2}([a-zA-Z0-9]*)=?(.*)$#', $arg, $matches)) {
			$key = $matches[1];
				switch ($matches[2]) {
					case '':
					case 'true':
					$arg = true;
					break;
					case 'false':
					$arg = false;
					break;
					default:
					$arg = $matches[2];
				}
			$_ARG[$key] = $arg;
		} else {
			$_ARG['input'][] = $arg;
		}
	}
return $_ARG;
}

/**
* Replace twitter user names (@user) in a text by html links (<a href="http://twitter.com/user">)
*/
function replace_twitter_user_names($tweet) {
    return preg_replace('#\@([-A-Za-z0-9]+)#', '<a href="http://twitter.com/$1" target="_blank">@$1</a>', $tweet);
}





function translittererCp1252VersIso88591($str, $translit = true) {
	$cp1252_entite_map = array(
	   '\x80' => '&#8364;', /* EURO SIGN */
	   '\x82' => '&#8218;', /* SINGLE LOW-9 QUOTATION MARK */
	   '\x83' => '&#402;',  /* LATIN SMALL LETTER F WITH HOOK */
	   '\x84' => '&#8222;', /* DOUBLE LOW-9 QUOTATION MARK */
	   '\x85' => '&#8230;', /* HORIZONTAL ELLIPSIS */
	   '\x86' => '&#8224;', /* DAGGER */
	   '\x87' => '&#8225;', /* DOUBLE DAGGER */
	   '\x88' => '&#710;',  /* MODIFIER LETTER CIRCUMFLEX ACCENT */
	   '\x89' => '&#8240;', /* PER MILLE SIGN */
	   '\x8a' => '&#352;',  /* LATIN CAPITAL LETTER S WITH CARON */
	   '\x8b' => '&#8249;', /* SINGLE LEFT-POINTING ANGLE QUOTATION */
	   '\x8c' => '&#338;',  /* LATIN CAPITAL LIGATURE OE */
	   '\x8e' => '&#381;',  /* LATIN CAPITAL LETTER Z WITH CARON */
	   '\x91' => '&#8216;', /* LEFT SINGLE QUOTATION MARK */
	   '\x92' => '&#8217;', /* RIGHT SINGLE QUOTATION MARK */
	   '\x93' => '&#8220;', /* LEFT DOUBLE QUOTATION MARK */
	   '\x94' => '&#8221;', /* RIGHT DOUBLE QUOTATION MARK */
	   '\x95' => '&#8226;', /* BULLET */
	   '\x96' => '&#8211;', /* EN DASH */
	   '\x97' => '&#8212;', /* EM DASH */
	   '\x98' => '&#732;',  /* SMALL TILDE */
	   '\x99' => '&#8482;', /* TRADE MARK SIGN */
	   '\x9a' => '&#353;',  /* LATIN SMALL LETTER S WITH CARON */
	   '\x9b' => '&#8250;', /* SINGLE RIGHT-POINTING ANGLE QUOTATION*/
	   '\x9c' => '&#339;',  /* LATIN SMALL LIGATURE OE */
	   '\x9e' => '&#382;',  /* LATIN SMALL LETTER Z WITH CARON */
	   '\x9f' => '&#376;'   /* LATIN CAPITAL LETTER Y WITH DIAERESIS*/
	);
	$translit_map = array(
	   '&#8364;' => 'Euro', /* EURO SIGN */
	   '&#8218;' => ',',    /* SINGLE LOW-9 QUOTATION MARK */
	   '&#402;' => 'f',     /* LATIN SMALL LETTER F WITH HOOK */
	   '&#8222;' => ',,',   /* DOUBLE LOW-9 QUOTATION MARK */
	   '&#8230;' => '...',  /* HORIZONTAL ELLIPSIS */
	   '&#8224;' => '+',    /* DAGGER */
	   '&#8225;' => '++',   /* DOUBLE DAGGER */
	   '&#710;' => '^',     /* MODIFIER LETTER CIRCUMFLEX ACCENT */
	   '&#8240;' => '0/00', /* PER MILLE SIGN */
	   '&#352;' => 'S',     /* LATIN CAPITAL LETTER S WITH CARON */
	   '&#8249;' => '<',    /* SINGLE LEFT-POINTING ANGLE QUOTATION */
	   '&#338;' => 'OE',    /* LATIN CAPITAL LIGATURE OE */
	   '&#381;' => 'Z',     /* LATIN CAPITAL LETTER Z WITH CARON */
	   '&#8216;' => "'",    /* LEFT SINGLE QUOTATION MARK */
	   '&#8217;' => "'",    /* RIGHT SINGLE QUOTATION MARK */
	   '&#8220;' => '"',    /* LEFT DOUBLE QUOTATION MARK */
	   '&#8221;' => '"',    /* RIGHT DOUBLE QUOTATION MARK */
	   '&#8226;' => '*',    /* BULLET */
	   '&#8211;' => '-',    /* EN DASH */
	   '&#8212;' => '--',   /* EM DASH */
	   '&#732;' => '~',     /* SMALL TILDE */
	   '&#8482;' => '(TM)', /* TRADE MARK SIGN */
	   '&#353;' => 's',     /* LATIN SMALL LETTER S WITH CARON */
	   '&#8250;' => '>',    /* SINGLE RIGHT-POINTING ANGLE QUOTATION*/
	   '&#339;' => 'oe',    /* LATIN SMALL LIGATURE OE */
	   '&#382;' => 'z',     /* LATIN SMALL LETTER Z WITH CARON */
	   '&#376;' => 'Y'      /* LATIN CAPITAL LETTER Y WITH DIAERESIS*/
	);
	$str = strtr($str, $cp1252_entite_map);
	if ($translit) {
		$str = strtr($str, $translit_map);
	}
	return $str;
}



function is_mobile() {
	return isMobile();
}
function isMobile(){
$iphone=true;
$ipad=true;
$android=true;
$opera=true;
$blackberry=true;
$palm=true;
$windows=true;

$mobile_browser   = false;
$user_agent       = (isSet($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT']: '';
$accept           = (isSet($_SERVER['HTTP_ACCEPT'])) ? $_SERVER['HTTP_ACCEPT'] : '';

if (preg_match('/ipad/i',$user_agent)) {
$mobile_browser = $ipad;
$status = 'Apple iPad';
	if(substr($ipad,0,4)=='http'){
	$mobileredirect = $ipad;
	}
} elseif(preg_match('/ipod/i',$user_agent)||preg_match('/iphone/i',$user_agent)) {
$mobile_browser = $iphone;
$status = 'Apple';
	if(substr($iphone,0,4)=='http')
	{
	$mobileredirect = $iphone;
	}
} elseif(preg_match('/android/i',$user_agent)) {
$mobile_browser = $android;
$status = 'Android';
	if(substr($android,0,4)=='http'){
	$mobileredirect = $android;
	}
} elseif(preg_match('/opera mini/i',$user_agent)) {
$mobile_browser = $opera;
$status = 'Opera';
	if(substr($opera,0,4)=='http'){
	$mobileredirect = $opera;
	}
} elseif(preg_match('/blackberry/i',$user_agent)) {
$mobile_browser = $blackberry;
$status = 'Blackberry';
	if(substr($blackberry,0,4)=='http'){
	$mobileredirect = $blackberry;
	}
} elseif(preg_match('/(pre\/|palm os|palm|hiptop|avantgo|plucker|xiino|blazer|elaine)/i',$user_agent)) {
$mobile_browser = $palm;
$status = 'Palm';
	if(substr($palm,0,4)=='http'){
	$mobileredirect = $palm;
	}
} elseif(preg_match('/(iris|3g_t|windows ce|opera mobi|windows ce; smartphone;|windows ce; iemobile)/i',$user_agent)) {
$mobile_browser = $windows;
$status = 'Windows Smartphone';
	if(substr($windows,0,4)=='http'){
	$mobileredirect = $windows;
	}
} elseif(preg_match('/(mini 9.5|vx1000|lge |m800|e860|u940|ux840|compal|wireless| mobi|ahong|lg380|lgku|lgu900|lg210|lg47|lg920|lg840|lg370|sam-r|mg50|s55|g83|t66|vx400|mk99|d615|d763|el370|sl900|mp500|samu3|samu4|vx10|xda_|samu5|samu6|samu7|samu9|a615|b832|m881|s920|n210|s700|c-810|_h797|mob-x|sk16d|848b|mowser|s580|r800|471x|v120|rim8|c500foma:|160x|x160|480x|x640|t503|w839|i250|sprint|w398samr810|m5252|c7100|mt126|x225|s5330|s820|htil-g1|fly v71|s302|-x113|novarra|k610i|-three|8325rc|8352rc|sanyo|vx54|c888|nx250|n120|mtk |c5588|s710|t880|c5005|i;458x|p404i|s210|c5100|teleca|s940|c500|s590|foma|samsu|vx8|vx9|a1000|_mms|myx|a700|gu1100|bc831|e300|ems100|me701|me702m-three|sd588|s800|8325rc|ac831|mw200|brew |d88|htc\/|htc_touch|355x|m50|km100|d736|p-9521|telco|sl74|ktouch|m4u\/|me702|8325rc|kddi|phone|lg |sonyericsson|samsung|240x|x320|vx10|nokia|sony cmd|motorola|up.browser|up.link|mmp|symbian|smartphone|midp|wap|vodafone|o2|pocket|kindle|mobile|psp|treo)/i',$user_agent)) {
$mobile_browser = true;
$status = 'Mobile Serie';
} elseif((strpos($accept,'text/vnd.wap.wml')>0)||(strpos($accept,'application/vnd.wap.xhtml+xml')>0)) {
 $mobile_browser = true;
$status = 'Mobile Wap 2';
} elseif(isset($_SERVER['HTTP_X_WAP_PROFILE'])||isset($_SERVER['HTTP_PROFILE'])) {
$mobile_browser = true;
$status = 'Mobile Wap';
} elseif(in_array(strtolower(substr($user_agent,0,4)),array('1207'=>'1207','3gso'=>'3gso','4thp'=>'4thp','501i'=>'501i','502i'=>'502i','503i'=>'503i','504i'=>'504i','505i'=>'505i','506i'=>'506i','6310'=>'6310','6590'=>'6590','770s'=>'770s','802s'=>'802s','a wa'=>'a wa','acer'=>'acer','acs-'=>'acs-','airn'=>'airn','alav'=>'alav','asus'=>'asus','attw'=>'attw','au-m'=>'au-m','aur '=>'aur ','aus '=>'aus ','abac'=>'abac','acoo'=>'acoo','aiko'=>'aiko','alco'=>'alco','alca'=>'alca','amoi'=>'amoi','anex'=>'anex','anny'=>'anny','anyw'=>'anyw','aptu'=>'aptu','arch'=>'arch','argo'=>'argo','bell'=>'bell','bird'=>'bird','bw-n'=>'bw-n','bw-u'=>'bw-u','beck'=>'beck','benq'=>'benq','bilb'=>'bilb','blac'=>'blac','c55/'=>'c55/','cdm-'=>'cdm-','chtm'=>'chtm','capi'=>'capi','cond'=>'cond','craw'=>'craw','dall'=>'dall','dbte'=>'dbte','dc-s'=>'dc-s','dica'=>'dica','ds-d'=>'ds-d','ds12'=>'ds12','dait'=>'dait','devi'=>'devi','dmob'=>'dmob','doco'=>'doco','dopo'=>'dopo','el49'=>'el49','erk0'=>'erk0','esl8'=>'esl8','ez40'=>'ez40','ez60'=>'ez60','ez70'=>'ez70','ezos'=>'ezos','ezze'=>'ezze','elai'=>'elai','emul'=>'emul','eric'=>'eric','ezwa'=>'ezwa','fake'=>'fake','fly-'=>'fly-','fly_'=>'fly_','g-mo'=>'g-mo','g1 u'=>'g1 u','g560'=>'g560','gf-5'=>'gf-5','grun'=>'grun','gene'=>'gene','go.w'=>'go.w','good'=>'good','grad'=>'grad','hcit'=>'hcit','hd-m'=>'hd-m','hd-p'=>'hd-p','hd-t'=>'hd-t','hei-'=>'hei-','hp i'=>'hp i','hpip'=>'hpip','hs-c'=>'hs-c','htc '=>'htc ','htc-'=>'htc-','htca'=>'htca','htcg'=>'htcg','htcp'=>'htcp','htcs'=>'htcs','htct'=>'htct','htc_'=>'htc_','haie'=>'haie','hita'=>'hita','huaw'=>'huaw','hutc'=>'hutc','i-20'=>'i-20','i-go'=>'i-go','i-ma'=>'i-ma','i230'=>'i230','iac'=>'iac','iac-'=>'iac-','iac/'=>'iac/','ig01'=>'ig01','im1k'=>'im1k','inno'=>'inno','iris'=>'iris','jata'=>'jata','java'=>'java','kddi'=>'kddi','kgt'=>'kgt','kgt/'=>'kgt/','kpt '=>'kpt ','kwc-'=>'kwc-','klon'=>'klon','lexi'=>'lexi','lg g'=>'lg g','lg-a'=>'lg-a','lg-b'=>'lg-b','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-f'=>'lg-f','lg-g'=>'lg-g','lg-k'=>'lg-k','lg-l'=>'lg-l','lg-m'=>'lg-m','lg-o'=>'lg-o','lg-p'=>'lg-p','lg-s'=>'lg-s','lg-t'=>'lg-t','lg-u'=>'lg-u','lg-w'=>'lg-w','lg/k'=>'lg/k','lg/l'=>'lg/l','lg/u'=>'lg/u','lg50'=>'lg50','lg54'=>'lg54','lge-'=>'lge-','lge/'=>'lge/','lynx'=>'lynx','leno'=>'leno','m1-w'=>'m1-w','m3ga'=>'m3ga','m50/'=>'m50/','maui'=>'maui','mc01'=>'mc01','mc21'=>'mc21','mcca'=>'mcca','medi'=>'medi','meri'=>'meri','mio8'=>'mio8','mioa'=>'mioa','mo01'=>'mo01','mo02'=>'mo02','mode'=>'mode','modo'=>'modo','mot '=>'mot ','mot-'=>'mot-','mt50'=>'mt50','mtp1'=>'mtp1','mtv '=>'mtv ','mate'=>'mate','maxo'=>'maxo','merc'=>'merc','mits'=>'mits','mobi'=>'mobi','motv'=>'motv','mozz'=>'mozz','n100'=>'n100','n101'=>'n101','n102'=>'n102','n202'=>'n202','n203'=>'n203','n300'=>'n300','n302'=>'n302','n500'=>'n500','n502'=>'n502','n505'=>'n505','n700'=>'n700','n701'=>'n701','n710'=>'n710','nec-'=>'nec-','nem-'=>'nem-','newg'=>'newg','neon'=>'neon','netf'=>'netf','noki'=>'noki','nzph'=>'nzph','o2 x'=>'o2 x','o2-x'=>'o2-x','opwv'=>'opwv','owg1'=>'owg1','opti'=>'opti','oran'=>'oran','p800'=>'p800','pand'=>'pand','pg-1'=>'pg-1','pg-2'=>'pg-2','pg-3'=>'pg-3','pg-6'=>'pg-6','pg-8'=>'pg-8','pg-c'=>'pg-c','pg13'=>'pg13','phil'=>'phil','pn-2'=>'pn-2','pt-g'=>'pt-g','palm'=>'palm','pana'=>'pana','pire'=>'pire','pock'=>'pock','pose'=>'pose','psio'=>'psio','qa-a'=>'qa-a','qc-2'=>'qc-2','qc-3'=>'qc-3','qc-5'=>'qc-5','qc-7'=>'qc-7','qc07'=>'qc07','qc12'=>'qc12','qc21'=>'qc21','qc32'=>'qc32','qc60'=>'qc60','qci-'=>'qci-','qwap'=>'qwap','qtek'=>'qtek','r380'=>'r380','r600'=>'r600','raks'=>'raks','rim9'=>'rim9','rove'=>'rove','s55/'=>'s55/','sage'=>'sage','sams'=>'sams','sc01'=>'sc01','sch-'=>'sch-','scp-'=>'scp-','sdk/'=>'sdk/','se47'=>'se47','sec-'=>'sec-','sec0'=>'sec0','sec1'=>'sec1','semc'=>'semc','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','sk-0'=>'sk-0','sl45'=>'sl45','slid'=>'slid','smb3'=>'smb3','smt5'=>'smt5','sp01'=>'sp01','sph-'=>'sph-','spv '=>'spv ','spv-'=>'spv-','sy01'=>'sy01','samm'=>'samm','sany'=>'sany','sava'=>'sava','scoo'=>'scoo','send'=>'send','siem'=>'siem','smar'=>'smar','smit'=>'smit','soft'=>'soft','sony'=>'sony','t-mo'=>'t-mo','t218'=>'t218','t250'=>'t250','t600'=>'t600','t610'=>'t610','t618'=>'t618','tcl-'=>'tcl-','tdg-'=>'tdg-','telm'=>'telm','tim-'=>'tim-','ts70'=>'ts70','tsm-'=>'tsm-','tsm3'=>'tsm3','tsm5'=>'tsm5','tx-9'=>'tx-9','tagt'=>'tagt','talk'=>'talk','teli'=>'teli','topl'=>'topl','hiba'=>'hiba','up.b'=>'up.b','upg1'=>'upg1','utst'=>'utst','v400'=>'v400','v750'=>'v750','veri'=>'veri','vk-v'=>'vk-v','vk40'=>'vk40','vk50'=>'vk50','vk52'=>'vk52','vk53'=>'vk53','vm40'=>'vm40','vx98'=>'vx98','virg'=>'virg','vite'=>'vite','voda'=>'voda','vulc'=>'vulc','w3c '=>'w3c ','w3c-'=>'w3c-','wapj'=>'wapj','wapp'=>'wapp','wapu'=>'wapu','wapm'=>'wapm','wig '=>'wig ','wapi'=>'wapi','wapr'=>'wapr','wapv'=>'wapv','wapy'=>'wapy','wapa'=>'wapa','waps'=>'waps','wapt'=>'wapt','winc'=>'winc','winw'=>'winw','wonu'=>'wonu','x700'=>'x700','xda2'=>'xda2','xdag'=>'xdag','yas-'=>'yas-','your'=>'your','zte-'=>'zte-','zeto'=>'zeto','acs-'=>'acs-','alav'=>'alav','alca'=>'alca','amoi'=>'amoi','aste'=>'aste','audi'=>'audi','avan'=>'avan','benq'=>'benq','bird'=>'bird','blac'=>'blac','blaz'=>'blaz','brew'=>'brew','brvw'=>'brvw','bumb'=>'bumb','ccwa'=>'ccwa','cell'=>'cell','cldc'=>'cldc','cmd-'=>'cmd-','dang'=>'dang','doco'=>'doco','eml2'=>'eml2','eric'=>'eric','fetc'=>'fetc','hipt'=>'hipt','http'=>'http','ibro'=>'ibro','idea'=>'idea','ikom'=>'ikom','inno'=>'inno','ipaq'=>'ipaq','jbro'=>'jbro','jemu'=>'jemu','java'=>'java','kddi'=>'kddi','keji'=>'keji','kyoc'=>'kyoc','kyok'=>'kyok','leno'=>'leno','lg-c'=>'lg-c','lg-d'=>'lg-d','lg-g'=>'lg-g','lge-'=>'lge-','libw'=>'libw','m-cr'=>'m-cr','maui'=>'maui','maxo'=>'maxo','midp'=>'midp','mits'=>'mits','mmef'=>'mmef','mobi'=>'mobi','mot-'=>'mot-','moto'=>'moto','mwbp'=>'mwbp','mywa'=>'mywa','nec-'=>'nec-','newt'=>'newt','nok6'=>'nok6','noki'=>'noki','o2im'=>'o2im','opwv'=>'opwv','palm'=>'palm','pana'=>'pana','pant'=>'pant','pdxg'=>'pdxg','phil'=>'phil','play'=>'play','pluc'=>'pluc','port'=>'port','prox'=>'prox','qtek'=>'qtek','qwap'=>'qwap','rozo'=>'rozo','sage'=>'sage','sama'=>'sama','sams'=>'sams','sany'=>'sany','sch-'=>'sch-','sec-'=>'sec-','send'=>'send','seri'=>'seri','sgh-'=>'sgh-','shar'=>'shar','sie-'=>'sie-','siem'=>'siem','smal'=>'smal','smar'=>'smar','sony'=>'sony','sph-'=>'sph-','symb'=>'symb','t-mo'=>'t-mo','teli'=>'teli','tim-'=>'tim-','tosh'=>'tosh','treo'=>'treo','tsm-'=>'tsm-','upg1'=>'upg1','upsi'=>'upsi','vk-v'=>'vk-v','voda'=>'voda','vx52'=>'vx52','vx53'=>'vx53','vx60'=>'vx60','vx61'=>'vx61','vx70'=>'vx70','vx80'=>'vx80','vx81'=>'vx81','vx83'=>'vx83','vx85'=>'vx85','wap-'=>'wap-','wapa'=>'wapa','wapi'=>'wapi','wapp'=>'wapp','wapr'=>'wapr','webc'=>'webc','whit'=>'whit','winw'=>'winw','wmlb'=>'wmlb','xda-'=>'xda-',))) {
$mobile_browser = true;
$status = 'Mobile xSerie' .substr($user_agent,0,4);
} else {
$mobile_browser = false;
$status = 'Desktop';
}


return array(
	'mobile' => $mobile_browser, 
	'statut' => $status,
	);
}




/**
 * Crypte une chaine en 64bits
 * @param $data
 * @return string
 */
function encrypter($data) {
    $key = truncatestr(magicword, 8, false, NULL);  // Clé de 8 caractères max
    $data = base64_encode( serialize($data) );
    $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td,$key,$iv);
    $data = base64_encode(mcrypt_generic($td, '!'.$data));
    mcrypt_generic_deinit($td);
    return $data;
}

/**
 * Decrypte une chaine, crypter par encrypter
 * @param $data
 * @return bool|mixed
 */
function decrypter($data) {
    $key = truncatestr(magicword, 8, false, NULL);
    $td = mcrypt_module_open(MCRYPT_DES,"",MCRYPT_MODE_ECB,"");
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td,$key,$iv);
    $data = mdecrypt_generic($td, base64_decode($data));
    mcrypt_generic_deinit($td);

    if (substr($data,0,1) != '!')
        return false;

    $data = substr($data,1,strlen($data)-1);
    return unserialize(base64_decode( $data ));
}





/*
Plugin Name: AdsCaptcha
Plugin URI: http://www.minteye.com
Description: Why pay for CAPTCHAs when AdsCaptcha can make you money? AdsCaptcha provides high-level internet security, and you earn a share of every typed ad. Now that’s efficient!
Version: 1.1.0
Author: AdsCaptcha
Author URI: http://www.minteye.com
*/

$ADSCAPTCHA_API = 'api.minteye.com';

function getCaptcha($captchaId, $publicKey) {
    global $ADSCAPTCHA_API;

    if (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && ("off" != $_SERVER['HTTPS'])) {
        $protocol = "https://";
    } else {
        $protocol = "http://";
    }
    $dummy = rand(1, 9999999999);
    $urlGet = $protocol . $ADSCAPTCHA_API . "/Get.aspx";
    $urlNoScript = $protocol . $ADSCAPTCHA_API . "/NoScript.aspx";
    $params = "?CaptchaId="  . $captchaId .
        "&PublicKey=" . $publicKey .
        "&Dummy=" . $dummy;

    $result  = "<script src='" . $urlGet . $params . "' type='text/javascript'></script>\n";
    $result .= "<noscript>\n";
    $result .= "\t<iframe src='" . $urlNoScript . $params . "' width='300' height='110' frameborder='0' marginheight='0' marginwidth='0' scrolling='no'></iframe>\n";
    $result .= "\t<table>\n";
    $result .= "\t<tr><td>Type challenge here:</td><td><input type='text' name='adscaptcha_response_field' value='' /></td></tr>\n";
    $result .= "\t<tr><td>Paste code here:</td><td><input type='text' name='adscaptcha_challenge_field' value='' /></td></tr>\n";
    $result .= "\t</table>\n";
    $result .= "</noscript>\n";

    return $result;
}

function ValidateCaptcha($captchaId, $privateKey) {
    global $ADSCAPTCHA_API, $_POST, $_SERVER;

    $host = $ADSCAPTCHA_API;
    $path = "/Validate.aspx";
    $data = "CaptchaId="      . $captchaId .
        "&PrivateKey="    . $privateKey .
        "&ChallengeCode=" . $_POST['adscaptcha_challenge_field'] .
        "&UserResponse="  . $_POST['adscaptcha_response_field'] .
        "&RemoteAddress=" . $_SERVER["REMOTE_ADDR"];

    $result = HttpPost($host, $path, $data);
    return $result;
}

function FixEncoding($str) {
    $curr_encoding = mb_detect_encoding($str) ;

    if($curr_encoding == "UTF-8" && mb_check_encoding($str,"UTF-8")) {
        return $str;
    } else {
        return utf8_encode($str);
    }
}

function HttpPost($host, $path, $data, $port = 80) {
    $data = FixEncoding($data);

    $http_request  = "POST $path HTTP/1.0\r\n";
    $http_request .= "Host: $host\r\n";
    $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $http_request .= "Content-Length: " . strlen($data) . "\r\n";
    $http_request .= "\r\n";
    $http_request .= $data;

    $response = '';
    if (($fs = @fsockopen($host, $port, $errno, $errstr, 10)) == false) {
        die ('Could not open socket! ' . $errstr);
    }

    fwrite($fs, $http_request);

    while (!feof($fs))
        $response .= fgets($fs, 1160);
    fclose($fs);

    $response = explode("\r\n\r\n", $response, 2);
    return $response[1];
}



// helper functions class simple_html_dom
// -----------------------------------------------------------------------------
/**
 * All of the Defines for the classes below.
 * @author S.C. Chen <me578022@gmail.com>
 */
define('HDOM_TYPE_ELEMENT', 1);
define('HDOM_TYPE_COMMENT', 2);
define('HDOM_TYPE_TEXT',    3);
define('HDOM_TYPE_ENDTAG',  4);
define('HDOM_TYPE_ROOT',    5);
define('HDOM_TYPE_UNKNOWN', 6);
define('HDOM_QUOTE_DOUBLE', 0);
define('HDOM_QUOTE_SINGLE', 1);
define('HDOM_QUOTE_NO',     3);
define('HDOM_INFO_BEGIN',   0);
define('HDOM_INFO_END',     1);
define('HDOM_INFO_QUOTE',   2);
define('HDOM_INFO_SPACE',   3);
define('HDOM_INFO_TEXT',    4);
define('HDOM_INFO_INNER',   5);
define('HDOM_INFO_OUTER',   6);
define('HDOM_INFO_ENDSPACE',7);
define('DEFAULT_TARGET_CHARSET', 'UTF-8');
define('DEFAULT_BR_TEXT', "\r\n");
define('DEFAULT_SPAN_TEXT', " ");
define('MAX_FILE_SIZE', 600000);
// get html dom from file
// $maxlen is defined in the code as PHP_STREAM_COPY_ALL which is defined as -1.
function file_get_html($url, $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=true, $defaultBRText=DEFAULT_BR_TEXT, $defaultSpanText=DEFAULT_SPAN_TEXT)
{
    // We DO force the tags to be terminated.
    $dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
    // For sourceforge users: uncomment the next line and comment the retreive_url_contents line 2 lines down if it is not already done.
    $contents = file_get_contents($url, $use_include_path, $context, $offset);
    // Paperg - use our own mechanism for getting the contents as we want to control the timeout.
    //$contents = retrieve_url_contents($url);
    if (empty($contents) || strlen($contents) > MAX_FILE_SIZE)
    {
        return false;
    }
    // The second parameter can force the selectors to all be lowercase.
    $dom->load($contents, $lowercase, $stripRN);
    return $dom;
}

// get html dom from string
function str_get_html($str, $lowercase=true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=true, $defaultBRText=DEFAULT_BR_TEXT, $defaultSpanText=DEFAULT_SPAN_TEXT)
{
    $dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
    if (empty($str) || strlen($str) > MAX_FILE_SIZE)
    {
        $dom->clear();
        return false;
    }
    $dom->load($str, $lowercase, $stripRN);
    return $dom;
}

// dump html dom tree
function dump_html_tree($node, $show_attr=true, $deep=0)
{
    $node->dump($node);
}


/**
 * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
 *
 * @param string $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 *
 * @return string Trimmed string.
 */
function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
    if ($considerHtml) {
        // if the plain text is shorter than the maximum length, return the whole text
        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        // splits all html-tags to scanable lines
        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
        $total_length = strlen($ending);
        $open_tags = array();
        $truncate = '';
        foreach ($lines as $line_matchings) {
            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
            if (!empty($line_matchings[1])) {
                // if it's an "empty element" with or without xhtml-conform closing slash
                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                    // do nothing
                    // if tag is a closing tag
                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                    // delete tag from $open_tags list
                    $pos = array_search($tag_matchings[1], $open_tags);
                    if ($pos !== false) {
                        unset($open_tags[$pos]);
                    }
                    // if tag is an opening tag
                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                    // add tag to the beginning of $open_tags list
                    array_unshift($open_tags, strtolower($tag_matchings[1]));
                }
                // add html-tag to $truncate'd text
                $truncate .= $line_matchings[1];
            }
            // calculate the length of the plain text part of the line; handle entities as one character
            $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
            if ($total_length+$content_length> $length) {
                // the number of characters which are left
                $left = $length - $total_length;
                $entities_length = 0;
                // search for html entities
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                    // calculate the real length of all entities in the legal range
                    foreach ($entities[0] as $entity) {
                        if ($entity[1]+1-$entities_length <= $left) {
                            $left--;
                            $entities_length += strlen($entity[0]);
                        } else {
                            // no more characters left
                            break;
                        }
                    }
                }
                $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                // maximum lenght is reached, so get off the loop
                break;
            } else {
                $truncate .= $line_matchings[2];
                $total_length += $content_length;
            }
            // if the maximum length is reached, get off the loop
            if($total_length>= $length) {
                break;
            }
        }
    } else {
        if (strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = substr($text, 0, $length - strlen($ending));
        }
    }
    // if the words shouldn't be cut in the middle...
    if (!$exact) {
        // ...search the last occurance of a space...
        $spacepos = strrpos($truncate, ' ');
        if (isset($spacepos)) {
            // ...and cut the text in this position
            $truncate = substr($truncate, 0, $spacepos);
        }
    }
    // add the defined ending to the text
    $truncate .= $ending;
    if($considerHtml) {
        // close all unclosed html-tags
        foreach ($open_tags as $tag) {
            $truncate .= '</' . $tag . '>';
        }
    }
    return $truncate;
}

function truncateBBcode($bbcode, $length = 100, $ending = '...') {
    $bbcode = truncatestr($bbcode, $length, false, $ending);
    $bbcode = clean($bbcode, 'bbcode');
    return stripBBcode($bbcode);
}