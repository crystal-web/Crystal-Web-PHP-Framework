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

Class admin_iplockController Extends baseController {


private $module=array(
	/* BOOL */
	'sitemap' => false,
	'title' => 'Administration', // Titre du module
	'page_title' => 'Ip lock', // Titre page courante
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
	


	// Listage de posts
	public function index() 
	{
	$oCache = new Cache('iplock');
	
	$nombre= ($nb = file_get_contents('http://kristof.123.fr/compteur.txt')) ? $nb : 0;
	$this->mvc->template->acces = $nombre;
	$this->mvc->template->tableIp = $oCache->getCache();
	$this->mvc->template->show('admin/iplock');
	}

	public function add()
	{
	$bool = false;
	$ipx = (isSet($_POST['ipx'])) ? $_POST['ipx'] : NULL;
		if ( isSet($ipx) && !empty($ipx) )
		{
			if (Securite::isLockIp($ipx))
			{
			$bool = true;
			}
			else
			{
				if (Securite::toLockIp($ipx))
				{
				$bool = true;
				}
			}//*/
			header("Refresh: 4;url=index.php?module=admin_iplock");
		}
		$ipx = (isSet($_GET['ip']) && empty($ipx)) ? $_GET['ip'] : $ipx;
		
	$this->mvc->template->boolLock = $bool;
	$this->mvc->template->ip = $ipx;
	$this->mvc->template->show('admin/iplockPost');
	
	}

	public function pardonne()
	{
		if (isSet($_GET['ip']))
		{
			$oCache = new Cache('iplock');
			$tableIpLock = $oCache->getCache();
			unset($tableIpLock[$_GET['ip']]);
			$oCache->setCache($tableIpLock);
		}
	}
}

?>
