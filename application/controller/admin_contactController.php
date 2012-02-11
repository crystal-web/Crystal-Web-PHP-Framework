<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nc-nd/3.0/
*/
if ($_SESSION['user']['power_level'] != 5)
{
header('Location: '.url('index.php?module=login&action=index'));
exit();
}

Class admin_contactController Extends baseController
{

private $module=array(
	/* BOOL */
	'sitemap' => false,
	'title' => 'Administration', // Titre du module
	'page_title' => NULL, // Titre page courante
	'breadcrumb' => false, // breadcrumb hierarchy $url => $title
	'isAdmin' => true,
	'aside' => array (
		'Configuration' => array(
			'index.php?module=admin_config' => 'Config g&eacute;n&eacute;ral',
			),
		'Contact' => array(
			'index.php?module=admin_contact' => 'Config Contact',
			),
		'Membre' => array(
		'index.php?module=admin_member' => 'Gestion Membre',
			),
		'Plugin' => array(
			'index.php?module=admin_plugin' => 'Gestion des plugins',
			),
		'Backup' => array(
			'index.php?module=admin_backup' => 'Backup manager',
			),
		'Contrôle' => array(	
		'index.php?module=admin_md5' => 'Contrôle MD5',
			),
		'Info serveur' => array(	
		'index.php?module=admin_linfo' => 'Serveur Healt',	
			),
		),
	);
	
// Configuration par défaut
private $preconfig = array(
	'sendMailTo' => 'contact@exemple.com',
	'sendMailBy' => 'noreply@exemple.com',
	);
	
/*** Methode ***/
private $config;
// Chargement de la configuration
private function loadconfig()
{
/* PRELOAD */
	// Lecture du cache
	$config_cache = new Cache('contact', $this->preconfig);
	$this->config = $config_cache->getCache();
		// Si le cache n'existe pas on le crée
		if ($this->config == false)
		{
		$config_cache->setCache();
		$this->config = $this->preconfig;
		}
/* END PRELOAD */
}

/*** Methode ***/

	public function getInfo(){
	
	return $this->module;
	}

	public function setInfo($name, $is){
	$this->module[$name]=$is;
	return $this->module;
	}
	
	/*** Methode ***/
	
	// Listage de posts
	public function index() 
	{
	
	$this->loadconfig();
	if (isSet($_POST['sendMailTo']))
	{
	$this->config = array(
		'sendMailTo' => $_POST['sendMailTo'],
		'sendMailBy' => $_POST['sendMailBy']
		);
	$config_cache = new Cache('contact', $this->config);
	$config_cache->setCache();
	}
	
	
	$this->mvc->template->mail = $this->config;
	$this->mvc->template->show('contact/admin_contact');

	}
	
}
?>