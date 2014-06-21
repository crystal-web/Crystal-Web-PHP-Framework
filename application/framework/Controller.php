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

abstract class Controller{
	/*** Cree un nouveau controleur ***/
	function __construct() {
		$request = Request::getInstance();
		$plugin = Plugin::getInstance();
		$plugin->triggerEvents('load' . $request->getController());
        ControllerRegister::setController($request->getController());
        if (!ControllerRegister::isRegistered()){
            ControllerRegister::register();
        }
	}

	/**
	 * Tous les controleurs doivent contenir une methode index
	 * Il s'agit de la page principale du controlleur
	 * 
	 * @return void
	 */
	public function index() {
		$page = Page::getInstance();
		$session = Session::getInstance();
		$page->setPageTitle('Method index notfound');
		$session->setFlash('Chaque controller doit avoir une m&eacute;thode index', 'error');
	}
	
	/**
	 * Chargement d'un model
	 * 
	 * @deprecated probablement supprimé prochainement
	 * @param string $name
	 * @return Model 
	 */
	public function loadModel($name) {		
	$name = $name.'Model';
	// L'endroit ou le model est charg�
	$file = __APP_PATH . DS . 'model' . DS . $name . '.php';
		if (file_exists($file)) {
		require_once $file;
			if (!isSet($this->$name)) {
				return new $name();
			}
		} else {
			throw new Exception ('File model not found '.$file);
		}
	}
	
	/**
	 * Chargement d'un controller
	 * Try -> Catch
	 * 
	 * @exception faite un try catch
	 * @param string $controller
	 * @return Controller
	 */
	public function loadController($controller) {
		$name = (!preg_match('#Controller$#', $controller)) ? $controller.'Controller' : $controller;
		$file = __APP_PATH . DS . 'controller' . DS . $name . '.php';
		if(!file_exists($file)){
			throw new Exception ('Le controller '.$controller.' n\'existe pas dans '.$file);
		}
		require $file; 
		return new $name(); 
	}
	
	/**
	 * Nom implémenté
	 * 
	 * @param Controller $controller
	 * @return void
	 */
	public function attach(Controller $controller) {
	
	}
}