<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
class Controller{

protected $mvc;
private $module=array(
	'sitemap' => false,	// Doit être dans le sitemap ?
	'title' => NULL,	// Titre du module
	'page_title' => NULL,	// Titre page courante
	'breadcrumb' => array('news' => 'News'),	// breadcrumb hierarchy $url => $title
	'isAdmin' => false,		// C'est une page admin ?
	'aside' => array(),	// Aside, est majoritairement utilisé poour l'admin
	);

	/*** Cree un nouveau controleur ***/
	function __construct($mvc) {
		$this->mvc = $mvc;

		if ( $this->mvc->Request->action == 'setinfo' or $this->mvc->Request->action == 'getinfo')
		{
		die('<html><head><title>403 Forbidden access</title></head><body><h1>Forbidden access</h1><p>You don\'t have permission to access this page.</p><a href="http://www.crystal-web.org">http://www.crystal-web.org</a></body></html>');
		}
	}

	public function getInfo(){
	return $this->module;
	}

	public function setInfo($name, $is){
	$this->module[$name]=$is;
	return $this->module;
	}
	
	/*** tous les controleurs doivent contenir une methode d'indexation ***/
	public function index()
	{
	$this->setInfo('title', 'Systeme');
	$this->setInfo('page_title', 'Method index notfound');
	$this->setInfo('sitemap', false);
	echo '<div class="MSGbox MSGalerte"><p>Chaque controller doit avoir une m&eacute;thode index</p></div>';
	}
	
	/**
	*	
	*/
	public function loadModel($name)
	{		
	// L'endroit ou le model est chargé
	$file = __APP_PATH . DS . 'model' . DS . $name . '.model.php';
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