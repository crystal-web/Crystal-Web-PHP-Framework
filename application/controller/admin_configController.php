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

class admin_configController Extends baseController {

private $module=array(
	/* BOOL */
	'sitemap' => false,
	'title' => 'Administration', // Titre du module
	'page_title' => 'Configuration', // Titre page courante
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
	
/*** Methode ***/

	public function getInfo(){
	return $this->module;
	}

	public function setInfo($name, $is){
	$this->module[$name]=$is;
	return $this->module;
	}

	public function index()
	{
	$this->setInfo('page_title', 'Configuration');
	$error = NULL;
	$valide=false;
	
	$cache_config = new Cache('gen_config', array(
		'theme' => 'crystal',
		// SEO
		'sitetitle' => 'CMS Crystal-Web',
		'category' => 'prototype',
		'language' => 'fr',
		'keyword' => 'my, best, key, words, for, the, referencing',
		'description' => 'The best site in the world begins with a description representing the entire site / blog itself, not exceeding 250 characters. My description is important for SEO. Here 189 characters only.'		
		));
		if (isSet($_POST['config']))
		{
		$cache_config->setCache(array(
		'theme' => $_POST['theme'],//,
		// SEO
		'sitetitle' => $_POST['sitetitle'],
		'category' => $_POST['category'],
		'language' => $_POST['language'],
		'keyword' => $_POST['keyword'],
		'description' => $_POST['description']		
		));
		$valide=true;
		}
		
$dirname = './themes/';
$dir = opendir($dirname); 
while($file = readdir($dir)) {
if ($file != '.' &&
	$file != '..' &&
	is_dir($dirname.$file) &&
	$file != 'admin'  &&
	$file != 'mobile' &&
	file_exists($dirname.$file.'/wrapper.tpl'))
{$list_dir[] = $file;}
}
closedir($dir);
		
		
$language['de'] = 'Allemand';
$language['en'] = 'Anglais';
$language['en-us'] = 'Am&eacute;ricain';
$language['dk'] = 'Danois';
$language['sp'] = 'Espagnol';
$language['fr'] = 'Fran&ccedil;ais';
$language['it'] = 'Italien';

	$this->mvc->template->list_dir = $list_dir;
	$this->mvc->template->language = $language;
	$this->mvc->template->valide = $valide;
	$this->mvc->template->config = $cache_config->getCache();
	$this->mvc->template->show('admin/config');	
	}


}


?>