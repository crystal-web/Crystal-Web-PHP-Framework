<?php
/**
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
if (!defined('__APP_PATH')) {
	echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>'; die;
}

class Dispatcher {
private $restricted;


	public function  __construct() {
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
	Router::parse($Request);
		$controller = $this->loadController($Request);
		$action = $Request->getAction();
		$Config = Config::getInstance();
		$Page = Page::getInstance();
        if (isset($_GET['skin']) || isset($_SESSION['skin'])) {
            if (isset($_GET['skin'])) {
                $_SESSION['skin'] = $_GET['skin'];
            }
            $Page->setLayout($_SESSION['skin']);
        }
		$Session = Session::getInstance();
		$Acl = AccessControlList::getInstance();
		$Plugin	= Plugin::getInstance();
		$Plugin->triggerEvents('onEnabled');
		$Plugin->triggerEvents('onReady');
		
		$Template = Template::getInstance();
            $isMobile = isMobile();
            $Template->isMobile($isMobile['mobile']);
			$Template->setPath(__APP_PATH . DS . 'views');

		try {
			i18n::load(Router::$language, $Request->getController());
		} catch (Exception $e) {
			$returnValue = preg_replace('#/'.Router::$language.'/#', '/'.i18n::getLanguage().'/', Router::selfURL(true), -1);
		}

		/*** Determine si l'argument peut etre appele comme fonction ***/
		if (false === is_callable(array($controller, $action))) {
			$action = 'index';
		}
		
		/*
		if (false !== array_search($action, $this->restricted, true)) {
			$action = 'index';
		}// */
		
		if( __ISAJAX) {
			header ( 'Content-Type: text/plain; charset=utf-8' );
			$Page->setLayout('empty');
		}


        if (isset($_COOKIE['oauth']) && !$Session->isLogged()){
            $decrypted = AesCtr::decrypt($_COOKIE['oauth'], magicword, 256);
            $decode = json_decode($decrypted);
            if (!is_null($decode)){
                $oauth = new AuthModel();
                $check = $oauth->checkPassword($decode->user, $decode->password, false, true);
                if ($check) {
                    $log = new LogModel();
                    $log->setLog('auth', 'Acc&egrave;s autoris&eacute; pour ' . $check->user . ' via Cookie ' . Securite::ipX(), $check->id);
                    $Session->write('user', $check);
                }
            }
        }

		ob_start();
		try {
			if (__DEV_MODE) {
				call_user_func_array(array($controller,$action),array($Request, $Session, $Acl, $Page));
			} else {
				if (preg_match('#java#Usi', $_SERVER['HTTP_USER_AGENT'])) {
					session_destroy();
				}
				@call_user_func_array(array($controller,$action),array($Request, $Session, $Acl, $Page));
			}
		}catch(Exception $ex){
			if (__DEV_MODE) {
				die(sprintf("<strong>Fatal Error:</strong> %s<br><strong>line: </strong>%s<br><strong>File: </strong>%s<br><strong>Trace: </strong><pre>%s</pre>", $ex->getMessage(), $ex->getLine(), $ex->getFile(), $ex->getTraceAsString()));
			} else {
				die('Dispatcher: ' . $ex->getMessage());
			}
		}

		$contenu = ob_get_clean();
		
        if ($isMobile['mobile'] && file_exists(__APP_PATH . DS . 'layout' . DS . $Page->getLayout() . '.mobi.phtml')) {
            require_once __APP_PATH . DS . 'layout' . DS . $Page->getLayout() . '.mobi.phtml';
        } else {
            require_once __APP_PATH . DS . 'layout' . DS . $Page->getLayout() . '.phtml';
        }

		
	}

	/**
	* Permet de charger le controller en fonction de la requête utilisateur
	**/
	private function loadController($Request) {
		header ( 'Content-Type: text/html; charset=utf-8' );
		$name = $Request->getController().'Controller'; 
		$file = __APP_PATH . DS . 'controller' . DS . $name . '.php';
		if(!file_exists($file)) {
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
		die($message);
	}
}
