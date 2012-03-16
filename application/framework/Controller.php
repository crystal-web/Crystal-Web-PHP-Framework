<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
class Controller{

protected $mvc;

	/*** Cree un nouveau controleur ***/
	function __construct($mvc)
	{
		$this->mvc = $mvc;
	}

	
	/*** tous les controleurs doivent contenir une methode index ***/
	public function index()
	{
	$this->mvc->Page->setPageTitle('Method index notfound');
	$this->mvc->Session->setFlash('Chaque controller doit avoir une m&eacute;thode index', 'error');
	}
	
	/**
	*	
	*/
	public function loadModel($name)
	{		
	$name = $name.'Model';
	// L'endroit ou le model est chargé
	$file = __APP_PATH . DS . 'model' . DS . $name . '.php';
		if (file_exists($file))
		{
		require_once $file;
			if (!isSet($this->$name))
			{
			return new $name();
			}
		}
		elseif (__DEV_MODE)
		{
		debug('File model not found '.$file);
		}
	}
	
	public function ajax()
	{
	
	}
	
	
function loadController($controller, $action='index'){
	$name = $controller.'Controller'; 
	$file = __APP_PATH . DS . 'controller' . DS . $name . '.php';
	if(!file_exists($file)){
		die('Le controller '.$this->mvc->Request->controller.' n\'existe pas dans '.$file); 
	} 
	require $file; 
	$controller = new $name($this->mvc); 
	$controller->$action();  
}

/**
* Permet de générer une page d'erreur en cas de problème au niveau du routing (page inexistante)
**/



}
?>