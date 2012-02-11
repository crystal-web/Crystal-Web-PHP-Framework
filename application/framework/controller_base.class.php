<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
class baseController{

protected $mvc;
private $module=array(
	'sitemap' => false,	// Doit être dans le sitemap ?
	'title' => NULL,	// Titre du module
	'page_title' => NULL,	// Titre page courante
	'breadcrumb' => false,	// breadcrumb hierarchy $url => $title
	'isAdmin' => false,		// C'est une page admin ?
	'aside' => array(),	// Aside, est majoritairement utilisé poour l'admin
	);

/*** Cree un nouveau controleur ***/
function __construct($mvc) {
	$this->mvc = $mvc;
	if ( $this->mvc->router->action == 'setinfo' or $this->mvc->router->action == 'getinfo')
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
}
?>