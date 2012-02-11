<?php
class Securite
{
	// Données entrantes
	static function bdd($string)
	{
	// On regarde si le type de string est un nombre entier (int)
	if(ctype_digit($string))
		{
			$string = intval($string);
		}
	// Pour tous les autres types
	else
		{
			$string = trim($string);
			$string = mysql_real_escape_string($string);
		}
	return $string;
	}
	// Données sortantes
	static function html($string)
	{
	/*
		// Liste des caractéres spéciaux et de leurs valeurs
	    $html_entities = array ( 
		// Caractères très spécifique
		"¡" =>  "&iexcl;",     #inverted exclamation mark
		"¢" =>  "&cent;",     #cent
		"£" =>  "&pound;",     #pound
		"¤" =>  "&curren;",     #currency
		"¥" =>  "&yen;",     #yen
		"¦" =>  "&brvbar;",     #broken vertical bar
		"§" =>  "&sect;",     #section
		"¨" =>  "&uml;",     #spacing diaeresis
		"©" =>  "&copy;",     #copyright
		"ª" =>  "&ordf;",     #feminine ordinal indicator
		"«" =>  "&laquo;",     #angle quotation mark (left)
		"¬" =>  "&not;",     #negation
		"®" =>  "&reg;",     #registered trademark
		"¯" =>  "&macr;",     #spacing macron
		"°" =>  "&deg;",     #degree
		"±" =>  "&plusmn;",     #plus-or-minus 
		"²" =>  "&sup2;",     #superscript 2
		"³" =>  "&sup3;",     #superscript 3
		"´" =>  "&acute;",     #spacing acute
		"µ" =>  "&micro;",     #micro
		"¶" =>  "&para;",     #paragraph
		"·" =>  "&middot;",     #middle dot
		"¸" =>  "&cedil;",     #spacing cedilla
		"¹" =>  "&sup1;",     #superscript 1
		"º" =>  "&ordm;",     #masculine ordinal indicator
		"»" =>  "&raquo;",     #angle quotation mark (right)
		"¼" =>  "&frac14;",     #fraction 1/4
		"½" =>  "&frac12;",     #fraction 1/2
		"¾" =>  "&frac34;",     #fraction 3/4
		"¿" =>  "&iquest;",     #inverted question mark
		"×" =>  "&times;",     #multiplication
		"÷" =>  "&divide;",     #division
		// Caracteres
		"À" =>  "&Agrave;",     #capital a, grave accent
		"Á" =>  "&Aacute;",     #capital a, acute accent
		"Â" =>  "&Acirc;",     #capital a, circumflex accent
		"Ã" =>  "&Atilde;",     #capital a, tilde
		"Ä" =>  "&Auml;",     #capital a, umlaut mark
		"Å" =>  "&Aring;",     #capital a, ring
		"Æ" =>  "&AElig;",     #capital ae
		"Ç" =>  "&Ccedil;",     #capital c, cedilla
		"È" =>  "&Egrave;",     #capital e, grave accent
		"É" =>  "&Eacute;",     #capital e, acute accent
		"Ê" =>  "&Ecirc;",     #capital e, circumflex accent
		"Ë" =>  "&Euml;",     #capital e, umlaut mark
		"Ì" =>  "&Igrave;",     #capital i, grave accent
		"Í" =>  "&Iacute;",     #capital i, acute accent
		"Î" =>  "&Icirc;",     #capital i, circumflex accent
		"Ï" =>  "&Iuml;",     #capital i, umlaut mark
		"Ð" =>  "&ETH;",     #capital eth, Icelandic
		"Ñ" =>  "&Ntilde;",     #capital n, tilde
		"Ò" =>  "&Ograve;",     #capital o, grave accent
		"Ó" =>  "&Oacute;",     #capital o, acute accent
		"Ô" =>  "&Ocirc;",     #capital o, circumflex accent
		"Õ" =>  "&Otilde;",     #capital o, tilde
		"Ö" =>  "&Ouml;",     #capital o, umlaut mark
		"Ø" =>  "&Oslash;",     #capital o, slash
		"Ù" =>  "&Ugrave;",     #capital u, grave accent
		"Ú" =>  "&Uacute;",     #capital u, acute accent
		"Û" =>  "&Ucirc;",     #capital u, circumflex accent
		"Ü" =>  "&Uuml;",     #capital u, umlaut mark
		"Ý" =>  "&Yacute;",     #capital y, acute accent
		"Þ" =>  "&THORN;",     #capital THORN, Icelandic
		"Ÿ" =>  "&Yuml;",     #latin capital letter Y
		
		"ß" =>  "&szlig;",     #small sharp s, German

		"à" =>  "&agrave;",     #small a, grave accent
		"á" =>  "&aacute;",     #small a, acute accent
		"â" =>  "&acirc;",     #small a, circumflex accent
		"ã" =>  "&atilde;",     #small a, tilde
		"ä" =>  "&auml;",     #small a, umlaut mark
		"å" =>  "&aring;",     #small a, ring
		"æ" =>  "&aelig;",     #small ae
		"ç" =>  "&ccedil;",     #small c, cedilla
		"è" =>  "&egrave;",     #small e, grave accent
		"é" =>  "&eacute;",     #small e, acute accent
		"ê" =>  "&ecirc;",     #small e, circumflex accent
		"ë" =>  "&euml;",     #small e, umlaut mark
		"ì" =>  "&igrave;",     #small i, grave accent
		"í" =>  "&iacute;",     #small i, acute accent
		"î" =>  "&icirc;",     #small i, circumflex accent
		"ï" =>  "&iuml;",     #small i, umlaut mark
		"ð" =>  "&eth;",     #small eth, Icelandic
		"ñ" =>  "&ntilde;",     #small n, tilde
		"ò" =>  "&ograve;",     #small o, grave accent
		"ó" =>  "&oacute;",     #small o, acute accent
		"ô" =>  "&ocirc;",     #small o, circumflex accent
		"õ" =>  "&otilde;",     #small o, tilde
		"ö" =>  "&ouml;",     #small o, umlaut mark
		"ø" =>  "&oslash;",     #small o, slash
		"ù" =>  "&ugrave;",     #small u, grave accent
		"ú" =>  "&uacute;",     #small u, acute accent
		"û" =>  "&ucirc;",     #small u, circumflex accent
		"ü" =>  "&uuml;",     #small u, umlaut mark
		"ý" =>  "&yacute;",     #small y, acute accent
		"þ" =>  "&thorn;",     #small thorn, Icelandic
		"ÿ" =>  "&yuml;",     #small y, umlaut mark
    );
	// Convertis les caractéres
	foreach ($html_entities as $key => $value)
	{
	$string = str_replace($key, $value, $string);
	}*/
		// Sécurisation des variable HTML
		$string = htmlentities($string);
		return $string;
	}
	// Déprotège le code
	static function unhtml($string)
	{
	return html_entity_decode($string);
	}
	// Cryptage irréversible
	static function Hcrypt ($str){
	return str_rot13(base64_encode(md5(magicword.$str)));
	}
	// Fonctions de cryptage simple
	static function crypt ($str){
	return str_rot13(base64_encode($str));
	}
	static function decrypt ($str){
	return base64_decode(str_rot13($str));
	}	

	//Ici on regarde si il existe la variable globale $_SERVEUR 
	static function ipX() {
	// On test si $_SERVER exist
	if (isSet($_SERVER))
		{
		if (isSet($_SERVER["HTTP_X_FORWARDED_FOR"]))
			{
			$ipx = $_SERVER["HTTP_X_FORWARDED_FOR"];
			}
		elseif (isSet($_SERVER["HTTP_CLIENT_IP"]))
			{
			$ipx = $_SERVER["HTTP_CLIENT_IP"];
			}
		else
			{
			$ipx = $_SERVER["REMOTE_ADDR"];
			}
		}
	// Sinon on utilise une ancienne methode
	else
		{
		// getenv — Retourne la valeur d'une variable d'environnement
		if ( getenv( 'HTTP_X_FORWARDED_FOR' ) )
			{
			$ipx = getenv( 'HTTP_X_FORWARDED_FOR' );
			}
		elseif ( getenv( 'HTTP_CLIENT_IP' ) )
			{
			$ipx = getenv( 'HTTP_CLIENT_IP' );
			}
		else
			{
			$ipx = getenv( 'REMOTE_ADDR' );
			}
		}
	return trim($ipx);
	}

	static function isLockIp($ip=NULL)
	{
	$ip=(empty($ip)) ? Securite::ipX() : $ip;

	$oCache = new Cache('iplock');
	$tableIpLock = $oCache->getCache();
	
		if (array_key_exists($ip, $tableIpLock))
		{
		return true;
		}
		else
		{
		return false;
		}
	}
	
	static function toLockIp($ip=NULL)
	{
	$ip=(empty($ip)) ? Securite::ipX() : $ip;

	$oCache = new Cache('iplock');
	$tableIpLock = $oCache->getCache();
	
		if (array_key_exists($ip, $tableIpLock))
		{
		$tableIpLock[$ip]=true;
		$oCache->setCache($tableIpLock);
		return true;
		}
		else
		{
		$tableIpLock[$ip]=true;
		$oCache->setCache($tableIpLock);
		return true;
		}
	}
	
	
	static function referer(){
	$part = array(0=>"scheme","host","port","user","pass","path","query","fragment");
	$result = array_flip($part);
		if(isset($_SERVER['HTTP_REFERER']))
		{
		$parse_url = parse_url($_SERVER['HTTP_REFERER']);
			if(get_magic_quotes_gpc() == 1)
			{
				while(list($key,$val) = each($parse_url))
				{
				$result["$key"] = $val;
				}
			}
			else
			{
				while(list($key,$val) = each($parse_url))
				{
				$result["$key"] = addslashes($val);
				}
			}
		}
	return $result;
	}

	static function isMail($string){
	if (preg_match("/^[a-z0-9._-]+@[a-z0-9.-]{2,}[.][a-z]{2,3}$/", $string)) {
		return true;
	} else {
		return false;
	}
	
	}
}

?>