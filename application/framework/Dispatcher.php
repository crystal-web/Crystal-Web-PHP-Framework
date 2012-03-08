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

	$controller = $this->loadController();
	$action = $this->mvc->Request->action;
	
	if($this->mvc->Request->prefix){
		$action = $this->mvc->Request->prefix.'_'.$action;
	}
		
		/*** Determine si l'argument peut etre appele comme fonction ***/
		if (is_callable(array($controller, $action)) == false)
		{
		$action = 'index';
		}
	
		$this->loadPlugin();
		
		$mvc->Form = new Form($mvc);	
		$mvc->Session = new Session();
		$mvc->Acl = new AccessControlList($this->mvc->Request, $mvc->Session->user('group'));
		
		ob_start();
		call_user_func_array(array($controller,$action),array($this->mvc)); 
		$mvc->contenu = ob_get_clean();
		
		
		// Pub
		$mvc->contenu = preg_replace('#<!--PUB-([0-9]*)x([0-9]*)-->#', '<div class="center"><img src="http://placehold.it/$1x$2" alt=""></div>', $mvc->contenu);

	if ($this->mvc->Request->action == 'ajax')
	{
	die($mvc->contenu);
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
		$this->error('Le controller '.$this->mvc->Request->controller.' n\'existe pas dans '.$file); 
	} 
	require $file; 
	$controller = new $name($this->mvc); 
	return $controller;  
}


function loadPlugin()
{
$plugin = new Cache(__SQL);
$pluginArray = $plugin->getCache();
	
	if (count($pluginArray))
	{
	
		foreach($pluginArray['plugin'] AS $k=>$v)
		{
			if ($v['activer'])
			{
			
				if (file_exists('plugins'.DS.$k.DS.$v['include']))
				{
					include_once('plugins'.DS.$k.DS.$v['include']);
					
				}
			}
		}
	}

}

/**
* Permet de générer une page d'erreur en cas de problème au niveau du routing (page inexistante)
**/
function error($message){
die($message.' die');
	$controller = new Controller($this->request);
	$controller->e404($message); 
}

}