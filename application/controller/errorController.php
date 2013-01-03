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
		$acl = AccessControlList::getInstance();
		$page = Page::getInstance();
		$session = Session::getInstance();
		$template = Template::getInstance();
		
		if (!$acl->isAllowed())
		{
			return $this->e404();
		}
		
		$page->setPageTitle('Inspecteur d\'erreur');
		$errLog = new Cache('erreur_alerte');
		$alerte = $errLog->getCache();
			
			$chmod = array();
			if (!is_writable(__SITE_PATH . DS . 'files' . DS . 'tmp'))
			{
				$chmod[] = __SITE_PATH . DS . 'files' . DS . 'tmp n\'est pas accessible en &eacute;criture !';
			}
			if (!is_writable(__SITE_PATH . DS . 'files' . DS . 'media'))
			{
				$chmod[] = __SITE_PATH . DS . 'files' . DS . 'media n\'est pas accessible en &eacute;criture !';
			}
			
			if (count($chmod))
			{
				$session->setFlash(implode($chmod, '<br>'), 'warning');
			}
			
			if (count($alerte) == 0)
			{
				$session->setFlash('Aucune erreur détecté');
			}
			else
			{
				$template->alerte = $alerte;
				$template->show('error/index');
			}

	}
	 
	
	public function delete()
	{
		$acl = AccessControlList::getInstance();
		$request = Request::getInstance();
		$page = Page::getInstance();
		$page->setLayout('empty');

		if (!$acl->isAllowed())
		{
			return $this->e404();
		}
		
		$errLog = new Cache('erreur_alerte');
			if (isSet($request->params['id']))
			{
				$cachedErr = $errLog->getCache();
				//debug($errLog);
				unset($cachedErr[$request->params['id']]);
				$errLog->setCache($cachedErr);
			}
			else
			{
				$errLog->setCache();
			}
		Router::redirect('error');
	}
	
	
	
	
	public function e403()
	{
		header('HTTP/1.0 403 Forbidden');
		$page = Page::getInstance();
		$page->setPAgeTitle('Forbidden');
		echo '<p>' . i18n::get('You don\'t have permission to access this file on this server').'</p>';
		return false;
	}
	
	
	public function e404()
	{
		header('HTTP/1.0 404 Not Found');
		$page = Page::getInstance();
		$template = Template::getInstance();
		
		$page->setPageTitle('Page introuvable');
		$template->show('error/404-page-not-found');
	}



}
?>
