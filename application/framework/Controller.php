<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
abstract class Controller{

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
		else
		{
		throw new Exception ('File model not found '.$file);
		}
	}
	
	public function ajax()
	{
	
	}
	
	
function loadController($controller, $action='index'){
	$name = $controller.'Controller'; 
	$file = __APP_PATH . DS . 'controller' . DS . $name . '.php';
	if(!file_exists($file)){
		throw new Exception ('Le controller '.$this->mvc->Request->controller.' n\'existe pas dans '.$file); 
	} 
	require $file; 
	$controller = new $name($this->mvc); 
	$controller->$action();  
}

}


abstract class PluginManager{

protected $mvc;

	/*** Cree un nouveau controleur ***/
	public function __construct($mvc)
	{
		$this->mvc = $mvc;
	}

}
?>