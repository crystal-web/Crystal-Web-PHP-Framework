<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/

* Simple Blog / News systeme

*/
include __APP . '/function/tronquehtml.php';
Class indexController Extends baseController {

private $module=array(
	/* BOOL */
	'sitemap' => false,
	'title' => 'News', // Titre du module
	'page_title' => NULL, // Titre page courante
	'breadcrumb' => false, // breadcrumb hierarchy $url => $title
	);
// Configuration par défaut
private $preconfig = array(
	'postParPage' => 5,
	'com_actif' => true, 
	'pagi_actif' => true,
	'titre' => 'News',
	'titre_news' => 'News du site',
	'edito_actif' => true,
	'edito_title' => 'Bienvenue sur votre site !',
	'edito_content' => '<p>
	Cette espace est l&#39;ent&ecirc;te de votre syst&egrave;me de news.<br />
	Vous pouvez par exemple y plac&eacute; quelques chose d&#39;important, le message sera toujours, le premiere de la liste.</p>'
	);	
private $config;
/*** Methode ***/


	public function getInfo(){
	
	return $this->module;
	}

	public function setInfo($name, $is){
	$this->module[$name]=$is;
	return $this->module;
	}
	
/*** Methode ***/
// Chargement de la configuration
private function loadconfig()
{
/* PRELOAD */
	// Lecture du cache
	$config_cache = new Cache('news', $this->preconfig);
	$this->config = $config_cache->getCache();
		// Si le cache n'existe pas on le crée
		if ($this->config == false)
		{
		
		$config_cache->setCache();
		$this->config = $this->preconfig;
		}
/* END PRELOAD */	
$this->setInfo('title', $this->config['titre']);
}
	
// Listage de posts
public function index() 
{
$this->loadconfig();

$this->setInfo('sitemap', true);
$this->setInfo('page_title', $this->config['titre_news']);
// Edito et config
$this->mvc->template->com_actif = $this->config['com_actif'];
$this->mvc->template->edito_actif = $this->config['edito_actif'];
$this->mvc->template->edito_title = $this->config['edito_title'];
$this->mvc->template->edito_content = $this->config['edito_content'];
// Les news


$news = new News(DB::getInstance());
// Comptage des news
$total=$news->countNews();
//Nous allons maintenant compter le nombre de pages.
$nombreDePages=ceil($total/$this->config['postParPage']);
if ($this->config['pagi_actif'])
{
		if(isset($_GET['page'])) // Si la variable $_GET['page'] existe...
		{
		$pageActuelle=intval($_GET['page']);
		// Si la valeur de $pageActuelle (le numéro de la page) est plus grande que $nombreDePages...
			if($pageActuelle>$nombreDePages)
			{
			$pageActuelle=$nombreDePages;
			}
		}
		else // Sinon
		{
		// La page actuelle est la n°1 
		$pageActuelle=1;   
		}
	$start=($pageActuelle-1)*$this->config['postParPage']; // On calcul la première entrée à lire	
}
else
{
$start=0;
}

$this->mvc->template->news = $news->getNews($start, (int) $this->config['postParPage']);

$this->mvc->template->pagi_actif = $this->config['pagi_actif'];
$this->mvc->template->nombreDePages = $nombreDePages;
$this->mvc->template->show('news/news');
}
	
// LEcture d'un post
public function post() 
{
$id = (int) $_GET['p'];

$news = new News(DB::getInstance());
// Je récupère la news.
if($uniqueData = $news->getUniqueNews($id))
{
$this->loadconfig();


$this->setInfo('sitemap', true);
$this->setInfo('page_title', $uniqueData['titre']);

if (isSet($_GET['s'])){$this->mvc->html->setCodeScript('alert(\'Commentaire posté avec succes\nMerci \');');}
$this->mvc->template->com_actif = $this->config['com_actif'];

$this->mvc->template->nbcom =  $news->countComm($id);
$this->mvc->template->getComm = $news->getComm($id);	
$this->mvc->template->output = $uniqueData;

$this->mvc->template->show('news/post');	
}
else
{
$this->setInfo('sitemap', false);
$this->setInfo('page_title', 'Page not found');
$this->mvc->template->show('news/post404');
}

}

// Ajout d'un commentaire
public function commentpost()
{
$this->setInfo('sitemap', false);
$this->setInfo('page_title', '');
$this->loadconfig();
	if (isSet($_POST['commentaire']) && !empty($_POST['commentaire']) && $this->config['com_actif'])
	{
	$news = new News(DB::getInstance());
		if ($data = $news->getUniqueNews((int) (isSet($_GET['p'])) ? $_GET['p'] : 0))
		{
		$idNews = $data['id'];
					$content = $_POST['commentaire'];
		$website = (empty($_POST['website'])) ? NULL : $_POST['website'];
			if (is_connected() == false)
			{
			$name = (empty($_POST['name'])) ? 'Visiteur' : $_POST['name'];
			$mail = (empty($_POST['mail'])) ? NULL : $_POST['mail'];
			}
			else
			{
			$name = $_SESSION['user']['pseudo'];
			$mail = $_SESSION['user']['mail'];
			}
			$news->addComm($name, $content, $mail, $website, $idNews);
		}
	
	header('location: ' . url('index.php?module=news&action=post&p=' . $idNews . '&' . $data['titre'] . '&s'));
	}
	else
	{
	header('location: ' . url('index.php?module=news'));
	}
}

/**
Ajax request
**/

public function ajax()
{
	if (defined('__LOADER'))
	{
		if (isSet($_GET['lastid']))
		{
		$start = (int) $_GET['lastid'];
		$news = new News(DB::getInstance());
		$this->loadconfig();
		$this->mvc->template->com_actif = $this->config['com_actif'];
		$this->mvc->template->news = $news->getNextNews($start, (int) $this->config['postParPage']);
		$this->mvc->template->show('news/ajax/news');
		}
	}
}


}
?>
