<?php
/**
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
Class indexController extends Controller{


	public function index()
	{
		$page = Page::getInstance();
			$page->setPageTitle("Bienvenue sur votre site");
		$template = Template::getInstance();
		
		$template->resume = "F&eacute;licitation, votre site est install&eacute;. Crystal-Web CMF est facile a prendre en main.";
		$template->show('index/index');
	}

}
