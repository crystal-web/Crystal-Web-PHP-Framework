<?php
/**
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
if (!defined('__APP_PATH')) {
	echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>'; die;
}

Class errorController Extends Controller {

	public function index()	{
		$acl = AccessControlList::getInstance();
		$page = Page::getInstance();
		$session = Session::getInstance();
		$template = Template::getInstance();
		
		if (!$acl->isAllowed()) {
			return $this->e403();
		}
		
		$page->setPageTitle('Inspecteur d\'erreur');
		$errLog = new Cache('erreur_alerte');
		$alerte = $errLog->getCache();
			
			if (count($alerte) == 0) {
				$session->setFlash('Aucune erreur détecté');
			} else {
				$template->alerte = $alerte;
				$template->show('error/index');
			}
	}
	
	public function error404() {
		$c = new Cache('e404');
		$m = $c->getCache();
		debug($m);
	}
	public function delete() {
		$acl = AccessControlList::getInstance();
		$request = Request::getInstance();
		$page = Page::getInstance();
		$page->setLayout('empty');

		if (!$acl->isAllowed()) {
			return $this->e404();
		}
		
		$errLog = new Cache('erreur_alerte');
			if (isSet($request->params['id'])) {
				$cachedErr = $errLog->getCache();
				//debug($errLog);
				unset($cachedErr[$request->params['id']]);
				$errLog->setCache($cachedErr);
			} else {
				$errLog->setCache();
			}
		Router::redirect('error');
	}

	/**
	 * Unauthorized
	 */
	public function e401() {
		header('HTTP/1.0 401 Unauthorized');
		$page = Page::getInstance();
		$template = Template::getInstance();
		
		$page->setPageTitle('Acc&egrave;s refus&eacute;');
		$template->show('error/403-forbidden');
	}


	public function e101() {
		$page = Page::getInstance();
		$template = Template::getInstance();
		
		$page->setPageTitle('Maintenance en cours');
		$template->show('error/101-maintenance');
	}

	/**
	 * Forbidden
	 */
	public function e403() {
		header('HTTP/1.0 403 Forbidden');
		$page = Page::getInstance();
		$template = Template::getInstance();
		
		$page->setPageTitle('Acc&egrave;s refus&eacute;');
		$template->show('error/403-forbidden');
	}
	
	/**
	 * Not found
	 */
	public function e404() {
		header('HTTP/1.0 404 Not Found');
		$page = Page::getInstance();
		$template = Template::getInstance();
		$request = Request::getInstance();
		
		$c = new Cache('e404');
		$m = $c->getCache();
		
		if (!isset($m[$request->getController()][$request->getAction()])) {
			$m[$request->getController()][$request->getAction()] = Router::selfURL();
			$c->setCache($m);
		}

		$page->setPageTitle('Page introuvable');
		$template->show('error/404-page-not-found');
	}
}
