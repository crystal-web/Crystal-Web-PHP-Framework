<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
header("Status: 404 Not Found");
Class error404Controller Extends baseController {

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
	
	public function index() 
	{
		$this->setInfo('page_title', 'Page introuvable');
		$this->setInfo('sitemap', false);
        
		

		$info = date("d/m/Y H:i:s",time())." :
			GET:".print_r($_GET, true).
			"POST:".print_r($_POST, true).
			"SERVER:".print_r($_SERVER, true).
			"COOKIE:".(isset($_COOKIE)? print_r($_COOKIE, true) : "Undefined").
			"SESSION:".(isset($_SESSION)? print_r($_SESSION, true) : "Undefined");


$error_array['more'] = $info;

$error_array['type'] = 'Erreur 404 : Page introuvable';
$error_array['msg'] = '';
$error_array['errline'] = '';
$error_array['errfile'] = '';

// Lecture du cache
$cache_error = new Cache('erreur_alerte');
$error_cache = $cache_error->getCache();
$error_cache[time()] = $error_array;

// Ecriture du cache
$cache_error_p = new Cache('erreur_alerte', $error_cache);
$cache_error_p->setCache();
		
		$this->mvc->template->heading = 'La page que vous recherchez n\'existe pas ou plus';
		$this->mvc->template->link_contact = url('index.php?module=contact');
        $this->mvc->template->show('error404');
	}


}
?>
