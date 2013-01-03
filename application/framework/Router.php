<?php
/*##################################################
 *                                Router.php
 *                            -------------------
 *   begin                : 2012-03-08
 *   copyright            : (C) 2012 DevPHP
 *   email                : developpeur@crystal-web.org
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
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
class Router{

	static $routes = array(); 
	static $language = 'fr';
	static $multilingue = false;
	/**
	* Permet de parser une url
	* @param $url Url � parser
	* @return tableau contenant les param�tres
	**/
	static function parse($url,$request){
		$url = trim($url,'/'); 
		
		$url = explode('/', $url);
		if (strlen($url[0]) == 2)
		{
			self::$language = $url[0];
			array_shift($url);
		}
		
		$url = implode('/', $url);
		
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
		if (strlen($params[0]) == 2)
		{
			self::$language = $params[0];
			array_shift($params);
		}
		$request->controller = $params[0];
		$request->action = isset($params[1]) ? $params[1] : 'index';
		$request->params = $match;
		Log::setLog(print_r($request, true));
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
    //$url = strtolower($url);
    $url = trim($url, '-');//*/
	
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
	
	static function moved($controller, $touri)
	{
		$request = Request::getInstance();
		if ($request->getController() == $controller)
		{
			Router::redirect($touri, true);
		}
	}
	
	static function redirect($url=null, $moved = false)
	{
		if ($moved) { header("Status: 301"); }
		header('Location: '.self::url($url));
	 /* On tue le script, sinon il poursuit et les evenement de type flash ne s'effectue pas */
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

	static function getReferer()
	{
		if (isSet($_SERVER['REFERER']))
		{
			return $_SERVER['REFERER'];
		}
		elseif ( isSet( $_SERVER['HTTP_REFERER'] ) )
		{
			return $_SERVER['HTTP_REFERER'];
		}		
		return false;
	}
	
	static function error($type)
	{
		switch ((int) $type)
		{
			case 404:
			header("HTTP/1.0 404 Not Found");
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
			$serverrequri = $_SERVER['REQUEST_URI'];
		}
		
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$protocol = self::strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		
		return ($full) ? $protocol."://".$_SERVER['SERVER_NAME'].$port.$serverrequri : $port.$serverrequri;   
	}
	
	static function strleft($s1, $s2) {
		return substr($s1, 0, strpos($s1, $s2));
	}
	
	
	static function refresh($sec = 0, $url = false)
	{
		$sec = (int) $sec;
        if ($url)
        {
            header('Refresh: ' . $sec . ';url=' . Router::url($url));
        } else {header('Refresh: ' . $sec . ';url=' . Router::selfURL());}
		
		if ($sec == 0) { die; }
	}
	
	
	static function urlLanguage($lng)
	{
		if (!self::$multilingue) {
			return self::webroot($url);
		}
		$params = explode('/',trim(self::selfURL(false), '/'));
		if (strlen($params[0]) == 2)
		{
			 $params[0] = $lng;
			 $url = implode('/', $params);
		}
		else
		{
			$url = $lng . '/' . trim(implode('/', $params), '/');
		}
		
		return self::webroot($url);
	}
}


