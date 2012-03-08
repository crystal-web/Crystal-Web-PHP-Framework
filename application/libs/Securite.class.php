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
		// Sécurisation des variable HTML
		$string = htmlentities($string, ENT_QUOTES, 'UTF-8');
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