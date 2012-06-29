<?php
/**
* @package Error
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/

Class errorController Extends Controller {

	public function index()
	{
		if ($this->mvc->Acl->isAllowed())
		{
		$this->mvc->Page->setPageTitle('Inspecteur d\'erreur');
			$errLog = new Cache('erreur_alerte');
			$alerte = $errLog->getCache();
			
			
			$this->mvc->Session->setFlash('<b>Note du développeur :</b> Aucune erreurs ne devrai jamais apparaitre.<br>Si vous rencontrez une erreur, contactez-moi sur le <a href="http://www.crystal-web.org/forum">forum</a> (DevPHP) ou par e-mail developpeur@crystal-web.org', 'warning');
			if (count($alerte) == 0)
			{
			$this->mvc->Session->setFlash('Aucune erreur détecté');
			}
			else
			{
			$this->mvc->Template->alerte = $alerte;
			$this->mvc->Template->show('error/index');
			}
		}
		else {Router::redirect();}
	}
	 
	
	public function delete()
	{
	//echo $iii;	echo $iiie; echo $iiiee;		
		if ($this->mvc->Acl->isAllowed())
		{
		$errLog = new Cache('erreur_alerte');
			if (isSet($this->mvc->Request->params['id']))
			{
			$cachedErr = $errLog->getCache();
			//debug($errLog);
			unset($cachedErr[$this->mvc->Request->params['id']]);
			$errLog->setCache($cachedErr);
			}
			else
			{
			$errLog->setCache();
			}
		Router::redirect('error');
		}
	}
	
	
	
	
	public function e403()
	{
	$message = 'Interdit<br>Le serveur HTTP a compris la requête, mais refuse de la traiter.';
	require_once __APP_PATH . DS . 'layout' . DS .'modal.phtml';die();
	}
	
	
	public function e404()
	{
	header('HTTP/1.0 404 Not Found');
	$this->mvc->Page->setPageTitle('Page introuvable');
	$this->mvc->Template->show('error/404-page-not-found');
	}



}
?>
