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

class admin_md5Controller Extends baseController {

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
	$this->setInfo('sitemap', false);
	$this->setInfo('page_title', 'Controleur');
	
	$error = NULL;

	$dir = (isSet($_GET['dir'])) ? $_GET['dir'] : 'fw';
	switch ($dir)
	{
	case 'fw':$dir=__APP.'/framework';break;
	case 'lb':$dir=__APP.'/libs';break;
	case 'cn':$dir=__APP.'/controller';break;
	//case 'vs':$dir='views';break;
	case 'xm':$dir=__APP.'/XMLHTTPRequest';break;
	case 'ic':$dir='./includes';break;
	default:$dir=__APP.'/framework';break;
	}
	
		$md5 = new md5($dir);
		$md5->ScanDirectory();
		$output = $md5->getOutput();	
		if (isSet($_GET['su'])){$md5->stor();}
	$this->mvc->template->output = $output;
	$this->mvc->template->error = $error;
	$this->mvc->template->show('admin/md5');	
	}


}


?>