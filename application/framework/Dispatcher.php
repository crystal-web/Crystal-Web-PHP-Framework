<?php

class Dispatcher {

protected $mvc;

public function  __construct($mvc)
{
$this->mvc = $mvc;
$this->mvc->layout = 'default';
/**
* Objet contenant
*	public $url; 				// URL appellér l'utilisateur
*	public $page = 1; 			// pour la pagination 
*	public $prefix = false; 	// Prefixage des urls /prefix/url
*	public $data = false; 		// Donnée post
*/
$this->mvc->Request = new Request();


/*
* Construction de la requete
*/
Router::parse($this->mvc->Request->url,$this->mvc->Request);



	
	/*** Si le loader est rcon on ne charge pas tout ***/
	if (__LOADER != 'rcon')
	{
		$controller = $this->loadController();
		$action = $this->mvc->Request->action;
		
		$this->mvc->Form = new Form($this->mvc);
		$this->mvc->Session = new Session();
		$this->mvc->Acl = new AccessControlList($this->mvc);
		$this->mvc->Plugin	= new Plugin($this->mvc);
		$this->mvc->Plugin->triggerEvents('onEnabled');
	}
	else
	{
		$this->mvc->Request->controller = 'rcon';
		$controller = $this->loadController();
		$action = $this->mvc->Request->action;
		
		$this->mvc->Session = new Session();
		$this->mvc->Acl = new AccessControlList($this->mvc);
	}	
	
	if($this->mvc->Request->prefix){
		$action = $this->mvc->Request->prefix.'_'.$action;
	}
		
	/*** Determine si l'argument peut etre appele comme fonction ***/
	if (is_callable(array($controller, $action)) == false)
	{
		$action = 'index';
	}


	ob_start();
	try
	{
		call_user_func_array(array($controller,$action),array($this->mvc));
	}catch(Exception $e){
		$this->error($e->getMessage());
	}
	$this->mvc->contenu = ob_get_clean();
	
	
	// Pub
	// $this->mvc->contenu = preg_replace('#<!--PUB-([0-9]*)x([0-9]*)-->#', '<div class="center"><img src="http://placehold.it/$1x$2" alt=""></div>', $mvc->contenu);

	if ($this->mvc->Request->action == 'ajax')
	{
		die($this->mvc->contenu);
	}
	
}


/**
* Permet de charger le controller en fonction de la requête utilisateur
**/
function loadController()
{
	$name = $this->mvc->Request->controller.'Controller'; 
	$file = __APP_PATH . DS . 'controller' . DS . $name . '.php';
	if(!file_exists($file)){
		$name = 'errorController';
		$this->mvc->Request->action = 'e404';
		$file = __APP_PATH . DS . 'controller' . DS . $name . '.php';
		//$this->error('Le controller '.$this->mvc->Request->controller.' n\'existe pas dans '.$file);
	}

		require $file; 
	

	$controller = new $name($this->mvc); 
	return $controller;  
}



/**
* Permet de générer une page d'erreur en cas de problème au niveau du routing (page inexistante)
**/
function error($message, $type='error'){
	$this->mvc->Session->setFlash($message, $type);
}

}
