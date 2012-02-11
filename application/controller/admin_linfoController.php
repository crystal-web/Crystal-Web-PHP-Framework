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

class admin_linfoController Extends baseController {

private $module=array(
	/* BOOL */
	'sitemap' => false,
	'title' => 'Administration', // Titre du module
	'page_title' => NULL, // Titre page courante
	'breadcrumb' => false, // breadcrumb hierarchy $url => $title
	'isAdmin' => true,
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
	$xml_tmp = file(__CW_PATH.'/plugins/linfo/index.php?out=json');
	$chaine_xml = implode('', $xml_tmp);
	$this->mvc->template->data=json_decode($chaine_xml, true);
	$this->mvc->template->show('admin/linfo');
	}

	
	
	
	
	
}
?>