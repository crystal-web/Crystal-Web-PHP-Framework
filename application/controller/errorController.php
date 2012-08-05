<?php
/**
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
if (!defined('__APP_PATH'))
{
	echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>'; die;
}

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
		header('HTTP/1.0 403 Forbidden');
		die('<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>');
	}
	
	
	public function e404()
	{
		header('HTTP/1.0 404 Not Found');
		$this->mvc->Page->setPageTitle('Page introuvable');
		$this->mvc->Template->show('error/404-page-not-found');
	}



}
?>
