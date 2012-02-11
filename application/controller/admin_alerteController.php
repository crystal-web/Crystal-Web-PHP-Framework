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

class admin_alerteController Extends baseController {

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

	public function index(){
	$this->setInfo('sitemap', false);
	$this->setInfo('page_title', 'Alerte système');
	
	if (isSet($_POST['poke']))
	{
		if ($_POST['poke'] == $_SESSION['poke'])
		{
		$cache_alerte = new Cache('erreur_alerte');
		$cache_alerte->delCache();
		unset($cache_alerte);
		}
	}
	else
	{
	$_SESSION['poke']=randCar(50);
	}
	

	
	// Chargement des erreurs 
	$cache_alerte = new Cache('erreur_alerte');

	// Pas d'alerte
	if (!is_array($cache_alerte->getCache())){
	$this->mvc->template->show('admin/alerte_notexist');	
	}
	// Erreur a lister 
	else
	{
	
		if (isSet($_POST['bugtracker']))
		{
		
		
ob_start();
echo '<table width="100%"><tr><th colspan="2">Erreurs archiv&eacute;es</th></tr><tr><td>Description</td><td>Date</td></tr>';	
foreach ($cache_alerte->getCache() AS $date => $data)
{
echo '<tr><td><b>Type : </b>' . $data['type'] . ' ' . $data['msg'].'<br /><b>Ligne : </b>' . $data['errline'] . ' ' . $data['errfile'].'<br />
<div style="width: 600px;height: 200px;border: 1px solid #CCC;background: #F2F2F2;padding: 6px;overflow: auto;">
<table width="100%"><tr><td><textarea rows="30" cols="60">' . $data['more'] . '
</textarea></td></tr></table></div></td><td><pre style="border: 1px solid #000; height: 9em; overflow: auto; margin: 0.5em;">' . date("d/m/Y H:i:s",$date) . '</pre></td></tr>';
}
echo '</table>';	
$repport=ob_get_contents();
ob_end_clean();

		
		$mail_send = new Mail('Report Bug form <'.$_SERVER['SERVER_NAME'].'>',$repport,'developpeur@crystal-web.org', ADMIN_MAIL);
		$mail_send->sendMailHtml();
		$this->mvc->html->setCodeScript("alert('Mail send succes');");
		}
	
	$this->mvc->template->alerte = $cache_alerte->getCache();
	$this->mvc->template->show('admin/alerte_exist');	
	

	}

	
	

	

	
	}


}


?>