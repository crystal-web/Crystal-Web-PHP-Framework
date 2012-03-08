<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/

Class errorController Extends Controller {

private $module=array(
	/* BOOL */
	'sitemap' => false,
	'title' => 'Erreur 404', // Titre du module
	'page_title' => NULL, // Titre page courante
	'breadcrumb' => false, // breadcrumb hierarchy $url => $title
	);
	
	public function getInfo(){
	return $this->module;
	}

	public function setInfo($name, $is){
	$this->module[$name]=$is;
	return $this->module;
	}
	
	public function e403()
	{
	$message = 'Interdit<br>Le serveur HTTP a compris la requête, mais refuse de la traiter.';
	require_once __APP_PATH . DS . 'layout' . DS .'modal.phtml';die();
	}
	
	
	public function e404()
	{

	}



}
?>
