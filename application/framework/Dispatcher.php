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
    private $logPostAndGet = true;
    private $restricted;
    private $version = 'v3.1.0';
    private $branch = 'mc1.7.2';

	public function  __construct() {
        header('X-Powered-By: Crystal-Web.org/' . $this->version . ' ' . $this->branch . ' devphp.me');

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
        if (ENABLE_LOG && $this->logPostAndGet && ($Request->data OR $Request->get)) {
            $handle = fopen(__APP_PATH . DS . "cache" . DS . "logger" . DS . "log-" . date('Y-m-d') . ".txt", "a");
            fwrite ( $handle , json_encode(array('ip' => Securite::ipX(), 'time' => date('H:i'), 'query' => $Request)) . PHP_EOL );
            fclose( $handle );
        }

        /*
        * Construction de la requete
        */
        Router::parse($Request);
		$controller = $this->loadController($Request);


		$action = $Request->getAction();
		$Config = Config::getInstance();

		$Page = Page::getInstance();
        $Page->setLayout($Config->getLayout());
        if (isset($_GET['skin']) || isset($_SESSION['skin'])) {
            if (isset($_GET['skin']) && file_exists(__APP_PATH.DS.'layout'.DS.$_GET['skin'].'.phtml')) {
                $_SESSION['skin'] = $_GET['skin'];
                $session = Session::getInstance();
                $session->setFlash('Changement du skin enregistr&eacute;', 'Info');
            }
            $Page->setLayout($_SESSION['skin']);
        }
//		$Form = Form::getInstance();
		$Session = Session::getInstance();
        if (isset($_SERVER['HTTP_REFERER'] ) &&
            preg_match('#'.$_SERVER['HTTP_HOST'].'#', $_SERVER['HTTP_REFERER'], $match)
        ) {
            $Session->write('HTTP_REFERER', $_SERVER['HTTP_REFERER']);
        }
//		$Acl = AccessControlList::getInstance();
		$Plugin	= Plugin::getInstance();
		$Plugin->triggerEvents('onReady');
		
		$Template = Template::getInstance();
            $isMobile = isMobile();
            $Template->isMobile($isMobile['mobile']);
			$Template->setPath(__APP_PATH . DS . 'views');

		//try {
		//	i18n::load(Router::$language, $Request->getController());
		//} catch (Exception $e) {
			$returnValue = preg_replace('#/'.Router::$language.'/#', '/'.i18n::getLanguage().'/', Router::selfURL(true), -1);
		//}

		/*** Determine si l'argument peut etre appele comme fonction ***/
		if (false === is_callable(array($controller, $action))) {
            $action = 'index';
        }

		/*** Si c'est de l'ajax, on charge un layout vide (complet) ***/
		if( __ISAJAX) {
			header ( 'Content-Type: text/plain; charset=utf-8' );
			$Page->setLayout('empty');
		}

        /**
         * Auto login
         */
        if (isset($_COOKIE['oauth']) && !$Session->isLogged()){
            $decrypted = AesCtr::decrypt($_COOKIE['oauth'], magicword, 256);
            $decode = json_decode($decrypted);
            if (!is_null($decode)){
                $oauth = new AuthModel();
                $check = $oauth->checkPassword($decode->user, $decode->password, false /* AES */, true /* setCookie */);
                if ($check) {
                    $log = new LogModel();
                    $log->setLog('auth', 'Acc&egrave;s autoris&eacute; pour ' . $check->name . ' via Cookie ' . Securite::ipX(), $check->id);
                    $Session->write('user', $check);
                }
            }
        }

		ob_start();
		try {
			if (__DEV_MODE) {
				call_user_func_array(array($controller,$action),array($this));
			} else {
				if (preg_match('#java#Usi', $_SERVER['HTTP_USER_AGENT'])) {
					session_destroy();
				}
				@call_user_func_array(array($controller,$action),array($this));
			}
		}catch(Exception $e){
            $contenu = ob_get_clean(); /* reset dump */
            ob_start();
            $c = new errorController();
            $c->showMessage('Oups', '', 'Kernel error with <strong>' . $Request->getController() . '.'. $Request->getAction() . '</strong><br>' . sprintf("<strong>Fatal Error:</strong> %s<br><strong>line: </strong>%s<br><strong>File: </strong>%s", $e->getMessage(), $e->getLine(), $e->getFile()));
		}

		$contenu = ob_get_clean();

        /**
         * C'est un PHAR ?
         */
        if (preg_match("#^phar:\/\/#", $Page->getLayout())) {
            $layout = $Page->getLayout();
        } else { // En source alors ?
            $layout = __APP_PATH . DS . 'layout' . DS . $Page->getLayout();
        }
        
        if ($isMobile['mobile'] && file_exists($layout . '.mobi.phtml')) {
            require_once $layout . '.mobi.phtml';
        } elseif (file_exists($layout . '.phtml')) {
            require_once $layout . '.phtml';
        } else {
            throw new Exception("Dispatcher: No layout found", 1);
        }
    }

    /**
    * Permet de charger le controller en fonction de la requête utilisateur
    **/
    private function loadController(Request $Request) {
        header ( 'Content-Type: text/html; charset=utf-8' );
        
        $name = $Request->getController() . 'Controller';
        $file = __APP_PATH . DS . 'controller' . DS . $name . '.php';
        
        if (file_exists($file)) {
            require $file;
            return new $name();
        } elseif(file_exists(__APP_PATH . DS . 'controller' . DS . $Request->getController() . '.phar')) {
            require __APP_PATH . DS . 'controller' . DS . $Request->getController() . '.phar';
            return new $name();
        }
        
        $name = 'errorController';
        $Request->action = 'e404';
        $file = __APP_PATH . DS . 'controller' . DS . $name . '.php';
        require $file;
        return new $name();
    }
}