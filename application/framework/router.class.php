<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/

Class router {

/*** Variables ***/

private $mvc;

private $path;

public $controller_info = array(
	'sitemap' => false,
	'title' => 'Sans titre', // Titre du module
	'page_title' => NULL, // Titre page courante
	'breadcrumb' => false, // breadcrumb hierarchy $url => $title
	);

public $file;

public $controller;

public $action; 

public $isMobile;
/*** Methodes ***/
	
	function __construct(mvc $mvc)
	{
	$this->mvc = $mvc;
		if (isSet($_GET['rewrite']))
		{
		if (preg_match('#redirect:#', $_GET['rewrite']) && $_SESSION['user']['power_level'] == 5)
		{
		echo debug($_GET);
		echo debug($_SERVER);
		exit();
		
		}
		
		$_GET['rewrite'] = preg_replace('#redirect:#', '', $_GET['rewrite']);
		$array=explode("/", $_GET['rewrite']);
			foreach ($array as $key => $value)
			{
				if (preg_match('/_/',$value))
				{
				$tmp=explode("_", $value);
				$_GET[$tmp[0]]=$tmp[1];
				}
				else
				{
					if ($key == 0)
					{
					$_GET['module']=$value;
					}
					elseif ($key == 1)
					{
					$_GET['action']=$value;
					}
				}
			}
		unset($_GET['rewrite']);
		}
	}

	public function setPath($path)
	{
		/*** Verifie que le path est un dossier ***/
		if (is_dir($path) == false)
		{
			throw new Exception ('Invalid controller path: `' . $path . '`');
		}
	/*** Enregistre le path ***/
		$this->path = $path;
	}

	public function loader()
	{
	/*** Charge le controller ***/
	$this->getController();

		/*** Si le controller n'est pas lisible ***/
		if (is_readable($this->file) == false)
		{ // On charge la page 404 
		$this->file = $this->path.'/error404Controller.php';
		$this->controller = 'error404';
		}
		
	/*** inclu le controleur ***/
	include $this->file;

	/*** On cree un controleur et instancie ***/
	$class = $this->controller . 'Controller';
	/*** On charge le controlleur avec les paramettre mvc test($this->mvc) ***/
	$controller = new $class($this->mvc);
	
	$action = 'index';
	if (defined('__LOADER') && __LOADER == 'browser')
	{
		/*** Determine si l'argument peut etre appele comme fonction ***/
		if (is_callable(array($controller, $this->action)) == false)
		{
		$action = 'index';
		}
		else
		{
		$action = $this->action;
		}
	}
	elseif (defined('__LOADER') && __LOADER == 'ajax' && $this->controller != 'error404')
	{
	$action = 'ajax';
	}
	
	/*** Lance le processus ***/
	$controller->$action();
	
		if (method_exists($controller,'getInfo'))
		{
		$this->controller_info=$controller->getInfo();
		}
	}


	private function getController() {

	/*** Recherche le module ***/
	$route = (!isSet($_GET['module'])) ? '' : $_GET['module'];
	$action = (!isSet($_GET['action'])) ? '' : $_GET['action'];
		if (empty($route))
		{
		$route = 'index';
		}
		else
		{
		/*** Decoupe $route pour en sortir l'arboressence ***/
		$parts = explode('/', $route);
		$this->controller = $route;
			if(!empty( $action ))
			{
			$this->action = $action;
			}
		}

		if (empty($this->controller))
		{
		$this->controller = 'index';
		}

		/*** Get action ***/
		if (empty($this->action))
		{
		$this->action = 'index';
		}

	/*** Change le file path pour le module ***/
	$this->file = $this->path .'/'. $this->controller . 'Controller.php';
	}


}

?>
