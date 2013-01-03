<?php
Class logController extends Controller {

public function index()
{
	$acl = AccessControlList::getInstance();
	$request = Request::getInstance();
	$page = Page::getInstance();
	$template = Template::getInstance();
	
	if (!$acl->isAllowed()){return Router::redirect(); die;}
	
	$m = new LogModel();
	$log = $tag = array();

	
	
	if (isSet($request->data->login))
	{
		
		$login = clean($request->data->login, 'slug');
		
		$log = $m->getUidLog($login, $request->page, 30, 'this');
		$page->setPageTitle('Liste des logs pour ' . $login)
						->setBreadcrumb($request->controller, 'Log');
		
	}
	else
	{
		if ($acl->isAllowed('log', 'all'))
		{
			$tag = $m->getTag();
			$explodeUrl = explode('/', trim($request->url, '/'));
			
			if ($request->action != 'index' && $request->action != 'this')
			{
				$page->setPageTitle('Liste de logs pour ' . $request->action)
								->setBreadcrumb($request->controller, 'Log');
				$log = $m->getLog($request->page, 30, $request->action);
			}
			elseif ($request->action == 'this' && count($explodeUrl) == 3)
			{
				$log = $m->getUidLog($explodeUrl[2], $request->page, 30, $request->action);
				
				if (isSet($log['query'][0]->loginmember))
				{
				$page->setPageTitle('Liste des logs pour ' . $log['query'][0]->loginmember)
								->setBreadcrumb($request->controller, 'Log');
				}
			}
			else
			{
				$log = $m->getLog($request->page, 30, $request->action);
				$page->setPageTitle('Liste des logs')
								->setBreadcrumb($request->controller, 'Log');
			}
		}		
	}
	
	
	
	$template->tagList = $tag;
	$template->log = $log;
	$template->show('log/index');

}

}