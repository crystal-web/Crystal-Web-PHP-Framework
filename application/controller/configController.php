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

Class configController extends Controller {
	
/**
 * Le but ici est de permettre de configurer, le site (systeme) afin de le rendre
 * plus performant. Ceci inclus quelques difficulté...
 */
public function index()
{
$acl = AccessControlList::getInstance();
$request = Request::getInstance();
$template = Template::getInstance();
$page = Page::getInstance();
$form = Form::getInstance();
$config = Config::getInstance();

	if (!$acl->isAllowed())
	{
		return Router::redirect();
	}

	$directoryLayout = scandir(__APP_PATH . DS . 'layout');
	$layoutList = array();
		for ($i=0; $i<count($directoryLayout); $i++)
		{
			if (
				$directoryLayout[$i] != '.' and
				$directoryLayout[$i] != '..' and
				$directoryLayout[$i] != 'empty.phtml' and
				$directoryLayout[$i] != 'alert.phtml'
				)
			{
				$name = preg_replace('#\.phtml#', '', $directoryLayout[$i]);
				$layoutList[$name] = $name;
			}
		}
	unset($directoryLayout);
	$errors = array();
	
	if ($request->data)
	{
		if (isSet($request->data->mailContact))
		{
			if (!Securite::isMail($request->data->mailContact))
			{
				$errors['mailContact'] = 'N\'est pas une adresse mail valide';
			}
		}
		if (isSet($request->data->mailSite))
		{
			if (!Securite::isMail($request->data->mailSite))
			{
				$errors['mailSite'] = 'N\'est pas une adresse mail valide';
			}
		}
	
		if (count($errors))
		{
			$form->setErrors($errors);
		} else {
			
			$config->setConfig($request->data);
			Router::refresh();
		}
		
	}

	$page->setPageTitle('Configuration du site');
	$template->layoutList = $layoutList;
	$template->config = $config->getConfig();
	$template->show('config/index');
	
}

}