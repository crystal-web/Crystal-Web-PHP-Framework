<?php
class Securite
{

	/**
	 * 
	 * Prépare les valeurs pour la base de donnée
	 * @deprecated
	 * @param Sting|int $string
	 */
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

	
	/**
	 * 
	 * Protége le code
	 * @deprecated
	 * @param String $string
	 */
	static function html($string)
	{
		// S�curisation des variable HTML
		$string = htmlentities($string, ENT_QUOTES, 'UTF-8');
		return $string;
	}
	
	
	/**
	 * 
	 * Déprotége le code
	 * @deprecated
	 */
	static function unhtml($string)
	{
	return html_entity_decode($string);
	}
	
	// Cryptage irr�versible
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

	
	/**
	 * 
	 * Récupération de l'adresse ip
	 */ 
	static function ipX() {
		// On test si $_SERVER exist
		if (isSet($_SERVER)) {
			
			if (isSet( $_SERVER ['HTTP_X_FORWARDED_FOR'] ))
			{
				$ip = $_SERVER ['HTTP_X_FORWARDED_FOR'];
			}
			elseif (isSet( $_SERVER ['HTTP_CLIENT_IP'] ))
			{
				$ip = $_SERVER ['HTTP_CLIENT_IP'];
			}
			else
			{
				if (isSet($_SERVER ['REMOTE_ADDR']))
				{
				$ip = $_SERVER ['REMOTE_ADDR'];
				}
			}
		// Sinon on utilise une ancienne methode
		} else {
			// getenv � Retourne la valeur d'une variable d'environnement
			if (c( 'HTTP_X_FORWARDED_FOR' ))
			{
				$ip = getenv( 'HTTP_X_FORWARDED_FOR' );
			}
			elseif (getenv( 'HTTP_CLIENT_IP' ))
			{
				$ip = getenv( 'HTTP_CLIENT_IP' );
			}
			else
			{
				$ip = getenv( 'REMOTE_ADDR' );
			}
			
		}
		
		return trim($ip);
	}
	
	
	/**
	 * 
	 * Retourne un tableau dont le premier champ est boolean et le seconde le type
	 */
	static function detect_proxy($myIP = false) {
		noError(true);
		if (!$myIP)
		{
			$myIP = self::ipX();
		}
		
		$scan_headers = array(	
						'HTTP_VIA',
						'HTTP_X_FORWARDED_FOR',
						'HTTP_FORWARDED_FOR',
						'HTTP_X_FORWARDED',
						'HTTP_FORWARDED',
						'HTTP_CLIENT_IP',
						'HTTP_FORWARDED_FOR_IP',
						'VIA',
						'X_FORWARDED_FOR',
						'FORWARDED_FOR',
						'X_FORWARDED',
						'FORWARDED',
						'CLIENT_IP',
						'FORWARDED_FOR_IP',
						'HTTP_PROXY_CONNECTION'
						);
	 
		
		$flagProxy = false;
		$libProxy = array(false, 'No');
	 
		
		foreach($scan_headers as $i)
		{
			if (isSet($_SERVER[$i]))
			{
				if($_SERVER[$i]) { $flagProxy = true; }
			}
		}
		
		
		if (isSet($_SERVER['REMOTE_PORT']))
		{
			
		if (
			in_array($_SERVER['REMOTE_PORT'], array(8080,80,6588,8000,3128,553,554))
				OR
			@fsockopen($_SERVER['REMOTE_ADDR'], 80, $errno, $errstr, 30)
				)
			{
				$flagProxy = true;
			}	
		}
		
		// Proxy LookUp
		if ($flagProxy == true
			&&
			isset ( $_SERVER ['REMOTE_ADDR'] )
			&&
			!empty ( $_SERVER ['REMOTE_ADDR'] )
			)
		{
			// Transparent Proxy
			// REMOTE_ADDR = proxy IP
			// HTTP_X_FORWARDED_FOR = your IP   
			if (isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )
				&&
				!empty ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )
				&&
				$_SERVER ['HTTP_X_FORWARDED_FOR'] == $myIP)
			{
				
				$libProxy = array(true, 'Transparent Proxy');
			}
			// Simple Anonymous Proxy            
			// REMOTE_ADDR = proxy IP
			// HTTP_X_FORWARDED_FOR = proxy IP
			elseif (isset ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )
				&&
				!empty( $_SERVER ['HTTP_X_FORWARDED_FOR'] )
				&&
				$_SERVER ['HTTP_X_FORWARDED_FOR'] == $_SERVER ['REMOTE_ADDR']
				)
			{
				$libProxy = array(true, 'Simple Anonymous (Transparent) Proxy');
			}
			// Distorting Anonymous Proxy            
			// REMOTE_ADDR = proxy IP
			// HTTP_X_FORWARDED_FOR = random IP address
			elseif (isset( $_SERVER ['HTTP_X_FORWARDED_FOR'] )
				&&
				!empty ( $_SERVER ['HTTP_X_FORWARDED_FOR'] )
				&&
				$_SERVER['HTTP_X_FORWARDED_FOR'] != $_SERVER ['REMOTE_ADDR']
				)
			{
				$libProxy = array(true, 'Distorting Anonymous (Transparent) Proxy');
			}
			// Anonymous Proxy
			// HTTP_X_FORWARDED_FOR = not determined
			// HTTP_CLIENT_IP = not determined
			// HTTP_VIA = determined
			elseif ($_SERVER ['HTTP_X_FORWARDED_FOR'] == ''
				&&
				$_SERVER ['HTTP_CLIENT_IP'] == ''
				&&
				!empty ( $_SERVER ['HTTP_VIA'] )
				)
			{
				$libProxy = array(true, 'Anonymous Proxy');
			}
			// High Anonymous Proxy            
			// REMOTE_ADDR = proxy IP
			// HTTP_X_FORWARDED_FOR = not determined                    
			else
			{
				$libProxy = array(true, 'High Anonymous Proxy');
			}
		}
		noError(false);
		return $libProxy;
	}

	/**
	 * 
	 * Intérroge le cache pour savoir si une ip est bloqué
	 * @param String $ip
	 */
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
	
	
	/**
	 * 
	 * Vérrouille le site, a une ip
	 * A utilisé avec prudence, si l'IP n'est pas défini, c'est celle du client
	 * Qui sera utilisé
	 * @param String $ip
	 */
	static function toLockIp($ip=NULL)
	{
		$ip=(empty($ip)) ? Securite::ipX() : $ip;
	
		$oCache = new Cache('iplock');
		$tableIpLock = $oCache->getCache();
		$tableIpLock[$ip]=true;
		$oCache->setCache($tableIpLock);
		return true;
	}

	
	/**
	 * 
	 * Retourne un Array contenant les informations du site référent
	 * Dans le cas ou celui-ci n'hexiste pas, les champ sont NULL
	 */
	static function referer()
	{
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
	

	/**
	 * 
	 * Test si une adresse e-mail est correct
	 * @param string $string
	 */
	static function isMail($string)
	{
		Log::setLog('Check if @ existe in mail', 'Securite');
		// Charge la librairie des email jetable
		$mail = library ( 'mailjetable' );
		// Explose l'url, pour n'avoir que le serveur mail
		$explode = explode ( '@', strtolower ( $string) );
		$serveur = isSet( $explode[1] ) ?  $explode[1] : 'NULL';	
		
			/*
			 * Recherche dans la librairie des mails jetables
			 */
			if (( bool ) array_search ( $serveur, $mail ))
			{
				Log::setLog('Mail has jetable', 'Securite');
				return false;
			}
			
			/*
			 * Demande a filter_var si c'est une adresse mail
			 */
			if (! filter_var ( $string, FILTER_VALIDATE_EMAIL ))
			{
				Log::setLog('Filter var respon no mail', 'Securite');
				return false;
			}
		Log::setLog('Ok it\'s mail', 'Securite');
		return true;
	}
}
?>