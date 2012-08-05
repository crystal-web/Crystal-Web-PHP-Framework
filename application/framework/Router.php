<?php
class Router{

	static $routes = array(); 
	static $prefixes = array(); 

	/**
	* Ajoute un prefix au Routing
	**/
	static function prefix($url,$prefix){
		self::$prefixes[$url] = $prefix; 
	}


	/**
	* Permet de parser une url
	* @param $url Url � parser
	* @return tableau contenant les param�tres
	**/
	static function parse($url,$request){
		$url = trim($url,'/'); 
		$match = FALSE;
		if(empty($url)){
			//Router::$routes[0]['url']; // DEL: 2012-02-15
			$url = 'index';				 // ADD: 2012-02-15
		}else{
			$asMatch = false; 
			foreach(Router::$routes as $v){
				if(!$asMatch && preg_match($v['redirreg'],$url,$match)){
				
					$url = $v['origin'];
					foreach($match as $k=>$v){
						$url = str_replace(':'.$k.':',$v,$url); 
					} 
					$asMatch = true; 
				}
			}
		}
		
		$params = explode('/',$url);
		if(in_array($params[0],array_keys(self::$prefixes))){
			$request->prefix = self::$prefixes[$params[0]];
			array_shift($params); 
		}
		$request->controller = $params[0];
		$request->action = isset($params[1]) ? $params[1] : 'index';
		foreach(self::$prefixes as $k=>$v){
			if(strpos($request->action,$v.'_') === 0){
				$request->prefix = $v;
				$request->action = str_replace($v.'_','',$request->action);  
			}
		}
		$request->params = $match;//array_slice($params,2);
		return true; 
	}


	/**
	* Permet de connecter une url � une action particuli�re
	**/
	static function connect($redir,$url){
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
	**/
	static function url($url=null){

	//if (preg_match('@^(?:https?://)?([^/]+)@i', $url))
	if (preg_match('#^(https?|ftp)://#i', $url))
	{
	return $url;
	}
	
	trim($url,'/'); 
		
	$url = preg_replace('#\&([A-Za-z])(?:grave|acute|circ|tilde|uml|ring|cedil)\;#', '\1', $url);
    $url = preg_replace('#\&([A-Za-z]{2})(?:lig)\;#', '\1', $url);
	//echo $url;
		/* Supprimer pour test*/
		$url = html_entity_decode($url);
		$url=preg_replace('# #', '-', $url);
		$url=stripAccents($url);
        $url = htmlentities(preg_replace('#([^a-zA-Z0-9_\/ ~%:_-])#i', '', $url), ENT_QUOTES, 'UTF-8');
		//*/
   
	//$url = preg_replace('#[^A-Za-z0-9-\/:]#', '', $url);
    $url = str_replace('--', '-', $url);
    $url = strtolower($url);
    $url = trim($url, '-');//*/
	
		foreach(self::$routes as $v){
			if(preg_match($v['originreg'],$url,$match)){
				$url = $v['redir']; 
				foreach($match as $k=>$w){
					$url = str_replace(":$k:",$w,$url); 
				}
			}
		}
		
		foreach(self::$prefixes as $k=>$v){
			if(strpos($url,$v) === 0){
				$url = str_replace($v,$k,$url); 
			}
		}
		return __CW_PATH.'/'.$url; 
	}
	
	
	static function redirect($url=null)
	{
	header('Location: '.self::url($url));
	 /* On tue le script, sinon il poursuit et les evenement de type flash n s'effectue pas */
	die();
	}

	static function webroot($url){
		trim($url,'/');
		return __CW_PATH.'/'.$url; 
	}
	
	static function referer()
	{
		if (isSet($_SERVER['REFERER']))
		{
			header('Location: ' . $_SERVER['REFERER']);
		}
		elseif ( isSet( $_SERVER['HTTP_REFERER'] ) )
		{
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}		
		else
		{
			header('Location: '.__CW_PATH);
		}
		die();
	}
	
	static function error($type)
	{
		switch ((int) $type)
		{
			case 404:
			header("HTTP/1.0 404 Not Found");
			// Chargement du controller error404
			
			break;
		}

	}
	
	
	static function selfURL($full=true){
		if(!isset($_SERVER['REQUEST_URI']))
		{
			$serverrequri = $_SERVER['PHP_SELF'];
		}
		else
		{
			$serverrequri =    $_SERVER['REQUEST_URI'];
		}
		
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$protocol = self::strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		
		return ($full) ? $protocol."://".$_SERVER['SERVER_NAME'].$port.$serverrequri : $port.$serverrequri;   
	}
	
	static function strleft($s1, $s2) {
		return substr($s1, 0, strpos($s1, $s2));
	}
	
	
	static function refresh($sec = 0)
	{
		$sec = (int) $sec;
		header('Refresh: ' . $sec . ';url=' . Router::selfURL());
		if ($sec == 0) { die; }
	}
}


