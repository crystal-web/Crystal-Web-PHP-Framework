<?php
/**
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
if (!defined('__APP_PATH'))
{
	echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>'; die;
}

class Router{

	static $routes = array(); 
	static $language = 'fr';
	static $multilingue = false;
	static $targetReverse = NULL;
	
	/**
	 * Permet de parser une url
	 * @param $url Url a parser
	 * @param Request $request
	 * @return tableau contenant les param�tres
	 */
	static function parse(Request $request) {
		Log::setLog('Initialisation','ROUTER');
			
		// Nettoye l'url
		if (isset($_GET['cw'])) {
			$url = $_GET['cw'];
			unset($_GET['cw']);
			$url = trim($url,'/');
		} else {
			$url = trim($request->url,'/');
		}
		$url = explode('/', $url);
		// Si le paramettre 0 est d'une longueur de 2 alors c'est la définition du langage
		if (strlen($url[0]) == 2) {
			self::$language = $url[0];
			array_shift($url);
		}
		// On recolle les morceaux, $params sera utilisé par la suite
		$url = implode('/', $url);
	
		// Initialise match a false
		$match = FALSE;
		// Si $url est vide (pas d'action défini), alors index
		if(empty($url)){ 
			$url = 'index';
		}else{
			self::$targetReverse = $url;
			Log::setLog('Url reçue: ' . $url, 'ROUTER');
			
			// on initialise asMatch a false
			$asMatch = false; 
			// On parcour les rĝles défini
			foreach(Router::$routes as $v){
				// Si une regle correspond
				if(!$asMatch && preg_match($v['redirreg'],$url,$match)){
					// On enregistre
					$url = $v['origin'];
					// On remplace les cl" par leur valeurs"
					foreach($match as $k=>$v) {
						$url = str_replace(':'.$k.':',$v,$url); 
					}
					self::$targetReverse = $url;
					Log::setLog('Url réèl: ' . $url, 'ROUTER');
					// On indique qu'on a trouvé
					$asMatch = true; 
				}
			}
		}

		$params = explode('/',$url);
		if (strlen($params[0]) == 2) {
			array_shift($params);
		}
		$request->controller = $params[0];
		$request->action = isset($params[1]) ? $params[1] : 'index';
		$request->params = $match;
		Log::setLog('Controller: ' . $request->controller . ' Action: ' . $request->action . ' Params: ' . print_r($request->params, true),'ROUTER');
		return true; 
	}


	/**
	* Permet de connecter une url & une action particuli�re
	**/
	static function connect($redir,$url) {
		$r = array();
		$r['params'] = array();
		$r['url'] = $url;
		
		$r['originreg'] = preg_replace('/([a-z0-9]+):([^\/]+)/','${1}:(?P<${1}>${2})',$url);
		$r['originreg'] = str_replace('/*','(?P<args>/?.*)',$r['originreg']);
		$r['originreg'] = '/^'.str_replace('/','\/',$r['originreg']).'$/'; 
		// MODIF
		$r['origin'] = preg_replace('/([a-z0-9]+):([^\/]+)/',':${1}:',$url);
		$r['origin'] = str_replace('/*',':args:',$r['origin']); 

		$params = explode('/',$url);
		foreach($params as $k=>$v){
			if(strpos($v,':')){
				$p = explode(':',$v);
				$r['params'][$p[0]] = $p[1]; 
			}
		} 

		$r['redirreg'] = $redir;
		$r['redirreg'] = str_replace('/*','(?P<args>/?.*)',$r['redirreg']);
		foreach($r['params'] as $k=>$v){
			$r['redirreg'] = str_replace(":$k","(?P<$k>$v)",$r['redirreg']);
		}
		$r['redirreg'] = '/^'.str_replace('/','\/',$r['redirreg']).'$/';

		$r['redir'] = preg_replace('/:([a-z0-9]+)/',':${1}:',$redir);
		$r['redir'] = str_replace('/*',':args:',$r['redir']); 

		self::$routes[] = $r; 
	}

	/**
	 * Permet de g�n�rer une url � partir d'une url originale
	 * controller/action(/:param/:param/:param...)
	 * 
	 * @param string $url
	 * @return string url
	 */
	static function url($url=null) {
	
	// C'est une url complete ?
	if (preg_match('#^(https?|ftp)://#i', $url)) {
		return $url;
	}
	
	trim($url,'/'); 

    if (is_null($url) || $url == 'index') {return __CW_PATH;}

	$url = preg_replace('#\&([A-Za-z])(?:grave|acute|circ|tilde|uml|ring|cedil)\;#', '\1', $url);
    $url = preg_replace('#\&([A-Za-z]{2})(?:lig)\;#', '\1', $url);

		/* Supprimer pour test*/
		$url = html_entity_decode($url);
		$url=preg_replace('# #', '-', $url);
		$url=stripAccents($url);
        $url = htmlentities(preg_replace('#([^a-zA-Z0-9_\/ ~%:_-])#i', '', $url), ENT_QUOTES, 'UTF-8');
		//*/
   
    $url = str_replace('--', '-', $url);
    $url = trim($url, '-');
	
		foreach(self::$routes as $v){
			if(preg_match($v['originreg'],$url,$match)){
				$url = $v['redir']; 
				foreach($match as $k=>$w){
					$url = str_replace(":$k:",$w,$url); 
				}
			}
		}
		
		return (self::$multilingue) ? __CW_PATH.'/'.self::$language.'/'.$url :  __CW_PATH.'/'.$url; 
	}
	
	/**
	 * Permet de mettre un controller en mode déplace
	 * Pas encore adapté pour la production, trop simple
	 * Utilisez Router::redirect($url, 301)
	 */
	static function moved($controller, $touri) {
		$request = Request::getInstance();
		if ($request->getController() == $controller) {
			Router::redirect($touri, true);
		}
	}

	/**
	 * Permet de redirigé vers une url défini avec un code de status
	 * 
	 * @param $url
	 * @param $code 
	 * @return void
	 */
	static function redirect($url=null, $code = 302) {
		// C'est une url complete ?
		if (!preg_match('#^(https?|ftp)://#i', $url)) {
			$url = self::url($url);
		}
		$session = Session::getInstance();
		$session->write('referer', self::selfURL());
		header("Status: " . $code);
		header('Location: '.$url);
	 /* On tue le script, sinon il poursuit et les evenement de type flash ne s'effectue pas */
	die('Redirection vers ' . $url . ' en cours...');
	}
	
	/**
	 * Permet de redirigé vers la page referente
	 * Peut causé des probleme de boucle infini
	 * 
	 * @return void
	 */
	static function referer($code = 302) {
		$ref = self::getReferer();
		header("Status: " . $code);
		if (isSet($_SERVER['REFERER'])) {
			header('Location: ' . $ref);
		} else {
			header('Location: '.__CW_PATH);
		}
		die('Redirection en cours...');
	}

	/**
	 * Recupere la page d'ou vient le client, si elle existe
	 * 
	 * @return mixte
	 */
	static function getReferer() {
		if (isSet($_SERVER['REFERER'])) {
			return $_SERVER['REFERER'];
		} elseif ( isSet( $_SERVER['HTTP_REFERER'] ) ) {
			return $_SERVER['HTTP_REFERER'];
		}		
		return false;
	}
	
	/**
	 * Permet de définir le statut de la page
	 * @deprecated Absolument inutile avec un controller error e404
	 */
	static function error($type) {
		switch ((int) $type) {
			case 404:
			header("Status: 404");
			header("HTTP/1.0 404 Not Found");
			break;
		}
	}
	
	/**
	 * Retourne l'url de la page courante
	 * 
	 * @param $full avec ou sans le protocole 
	 * @return string
	 */
	static function selfURL($full=true){
		if(!isset($_SERVER['REQUEST_URI'])) {
			$serverrequri = $_SERVER['PHP_SELF'];
		} else {
			$serverrequri = $_SERVER['REQUEST_URI'];
		}
		
		$s = !isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$protocol = self::strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		
		return ($full) ? $protocol."://".$_SERVER['SERVER_NAME'].$port.$serverrequri : $port.$serverrequri;   
	}
	
	static function strleft($s1, $s2) {
		return substr($s1, 0, strpos($s1, $s2));
	}
	
	/**
	 * Permet de raffraichir la page après un temps donné
	 * 
	 * @param $sec temps en secondes
	 * @param $url url vers laquel rediriger
	 */
	static function refresh($sec = 0, $url = false) {
		$sec = (int) $sec;
        if ($url)
        {
            header('Refresh: ' . $sec . ';url=' . Router::url($url));
        } else {header('Refresh: ' . $sec . ';url=' . Router::selfURL());}
		
		if ($sec == 0) { die(); }
	}
	
	/**
	 * Permet de mettre le langage dans l'url
	 * 
	 * @param string $lng lenght 2
	 */
	static function urlLanguage($lng) {
		if (!self::$multilingue) {
			return self::webroot($url);
		}
		$params = explode('/',trim(self::selfURL(false), '/'));
		if (strlen($params[0]) == 2) {
			 $params[0] = $lng;
			 $url = implode('/', $params);
		} else {
			$url = $lng . '/' . trim(implode('/', $params), '/');
		}
		return self::webroot($url);
	}
	
	static function webroot($url){
		trim($url,'/');
		return __CW_PATH.'/'.$url; 
	}
	
}