<?php
/*##################################################
 *                               Dispatcher.php
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
class Dispatcher {

private $restricted;
	public function  __construct()
	{
	$this->restricted = array(
		'loadController', 'loadModel'
		);

	/**
	* Objet contenant
	*	public $url; 				// URL appellér l'utilisateur
	*	public $page = 1; 			// pour la pagination 
	*	public $prefix = false; 	// Prefixage des urls /prefix/url
	*	public $data = false; 		// Donnée post
	*/
	$Request = Request::getInstance();


	/*
	* Construction de la requete
	*/
	Router::parse($Request->url,$Request);

	
		$controller = $this->loadController($Request);
		$action = $Request->getAction();
		
		$Config = Config::getInstance();
		$Page = Page::getInstance();
		$Form = Form::getInstance();
		$Session = Session::getInstance();
		$Acl = AccessControlList::getInstance();
		$Plugin	= Plugin::getInstance();
		$Plugin->triggerEvents('onEnabled');
		
		$Template = Template::getInstance();
			$Template->setPath(__APP_PATH . DS . 'views'); 
		
		Router::moved('memovedpage', 'page/movedpostion');

		try
		{
			i18n::load(Router::$language, $Request->getController());
		}
		catch (Exception $e)
		{
			$returnValue = preg_replace('#/'.Router::$language.'/#', '/'.i18n::getLanguage().'/', Router::selfURL(true), -1);
		}


		/*** Determine si l'argument peut etre appele comme fonction ***/
		if (false === is_callable(array($controller, $action)))
		{
			$action = 'index';
		}
		
		if (false !== array_search($action, $this->restricted, true))
		{
			$action = 'index';
		}
		
		if ($Request->getController() != 'rpc' || $Request->getAction() != 'rpc')
		{
			header ( 'Content-Type: text/html; charset=utf-8' );
		}
		
		ob_start();
			try{
				call_user_func_array(array($controller,$action),array());
			}catch(Exception $e){
				$this->error('Dispatcher: ' . $e->getMessage());
			}//*/
		$contenu = ob_get_clean();

	require_once __APP_PATH . DS . 'layout' . DS . $Page->getLayout() . '.phtml';
	}


	/**
	* Permet de charger le controller en fonction de la requête utilisateur
	**/
	private function loadController($Request)
	{
		//$Request = Request::getInstance();
		$name = $Request->controller.'Controller'; 
		$file = __APP_PATH . DS . 'controller' . DS . $name . '.php';
		if(!file_exists($file)){
			$name = 'errorController';
			$Request->action = 'e404';
			$file = __APP_PATH . DS . 'controller' . DS . $name . '.php';
		}

		require $file; 
		$controller = new $name(); 
		return $controller;  
	}



	/**
	* Permet de générer une page d'erreur en cas de problème au niveau du routing (page inexistante)
	**/
	private function error($message, $type='error'){
		$Session = Session::getInstance();
		$Session->setFlash($message, $type);
	}

}
