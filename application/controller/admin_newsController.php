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

class admin_newsController Extends baseController {
private $config;
private $module=array(
	/* BOOL */
	'sitemap' => false,
	'title' => 'Administration', // Titre du module
	'page_title' => NULL, // Titre page courante
	'breadcrumb' => false, // breadcrumb hierarchy $url => $title
	'isAdmin' => true,
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
		echo 'false';
		$config_cache->setCache();
		$this->config = $this->preconfig;
		}
/* END PRELOAD */	
$this->setInfo('title', $this->config['titre']);
}

public function getInfo()
{
return $this->module;
}

public function setInfo($name, $is)
{
$this->module[$name]=$is;
return $this->module;
}
	
	
public function index()
{
$this->loadconfig();

$this->setInfo('page_title', 'Liste des postes');
$news = new News(DB::getInstance());
// Comptage des news
$total=$news->countnews();
//Nous allons maintenant compter le nombre de pages.
$nombreDePages=ceil($total/$this->config['postParPage']);

	// Si la variable $_GET['page'] existe...
	if(isset($_GET['page'])) 
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
// On calcul la première entrée à lire	
$start=($pageActuelle-1)*$this->config['postParPage']; 

$this->mvc->template->news = $news->getnews($start,(int) $this->config['postParPage']);
$this->mvc->template->nombreDePages = $nombreDePages;
$this->mvc->template->show('news/admin_newsList');
}



public function commentaire()
{
$this->setInfo('page_title', 'Liste des commentaires');
	$this->loadconfig();
	$_SESSION['tokken'] = (isSet($_SESSION['tokken'])) ? $_SESSION['tokken'] : rand();
	
	$news = new News(DB::getInstance());

	if ( (isSet($_GET['accept'])) && isSet($_GET['tko']) && $_GET['tko']==$_SESSION['tokken'] )
	{
	$news->valideCom((int) $_GET['accept'], '1');
	}
	
	if ( (isSet($_GET['refute'])) && isSet($_GET['tko']) && $_GET['tko']==$_SESSION['tokken'])
	{
	$news->valideCom((int) $_GET['refute'], '2');
	}	
// Comptage des news
$total=$news->countTotalComm();
//Nous allons maintenant compter le nombre de pages.
$nombreDePages=ceil($total/$this->config['postParPage']);

		if(isset($_GET['page'])) // Si la variable $_GET['page'] existe...
		{
		$pageActuelle=(int) $_GET['page'];
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
	$start=($pageActuelle-1)*30;//$this->config['postParPage']; // On calcul la première entrée à lire	
	
	
	$aff = (isSet($_GET['aff'])) ? ((int) $_GET['aff'] - 1) : 0;
	$this->mvc->template->tko = $_SESSION['tokken'];
	$this->mvc->template->commentaire = $news->getComm('all',$aff, $start, 30, 'DESC');//(int) $this->config['postParPage']);
	$this->mvc->template->nombreDePages = $nombreDePages;
	$this->mvc->template->show('news/admin_newsCommentaire');

	
}



public function del()
{
$this->setInfo('page_title', 'Suppression d\'un commentaire');

	if ( isSet($_GET['tko']) && $_GET['tko']==$_SESSION['tokken'] )
	{
	$news = new News(DB::getInstance());
	$news->delnews($_GET['cmd_s']);
	$this->mvc->template->show('news/admin_SuccesDel');
	header("Refresh: 5;url=index.php?module=admin_news");
	}
	else
	{	
	$this->mvc->template->cmd_id = $_GET['cmd_s'];
	$this->mvc->template->tko = $_SESSION['tokken'];
	$this->mvc->template->show('news/admin_Confirm');
	}
}



public function edit()
{
$this->setInfo('page_title', 'Edition d\'une poste');
	if (isSet($_GET['cmd_e']) && (!empty($_GET['cmd_e'])))
	{
	$news = new News(DB::getInstance());
		if (isSet($_POST['post']))
		{
			if($news->updnews((int) $_GET['cmd_e'], (int) $_POST['categorie'], stripcslashes($_POST['titre']), stripcslashes(html_entity_decode(stripspace($_POST['post'])))))
			{
			header("Refresh: 5;url=index.php?module=admin_news");
			$this->mvc->template->show('news/admin_news_EditPostSucces');
			}
			else
			{
			$this->mvc->template->show('news/admin_news_EditPostBad');
			}
		}
		else
		{
		$data = $news->getUniquenews((int) $_GET['cmd_e']);

			if($data)
			{
			$this->mvc->template->listCategorie = $news->getCat();
			$this->mvc->template->categorie = $data['categorieid'];
			$this->mvc->template->titre = $data['titre'];
			$this->mvc->template->content = $data['content'];
			$this->mvc->template->show('news/admin_news_EditPost');
			}
			else
			{
			$this->mvc->template->show('news/post404');
			}
		}
	}
}

	
	
public function config()
{
$this->setInfo('sitemap', false);
$this->setInfo('page_title', 'Configuration des postes');
// Chargement de la config
$this->loadconfig();
	// Si une edition est demandé
	if (isSet($_POST['edito_actif']))
	{
	$config_cache = new Cache('news');
	// Var Bool
	$com_actif = ($_POST['com_actif'] == 'on') ? true : false;
	$pagi_actif = ($_POST['pagi_actif'] == 'on') ? true : false;
	$edito_actif = ($_POST['edito_actif'] == 'on') ? true : false;
	// Var Str
	$titre = $_POST['titre'];
	$titre_news = $_POST['titre_news'];
	$edito_titre = $_POST['edito_titre'];
	$edito_contenu = $_POST['edito_contenu'];
	// Var Int
	$postParPage = ($_POST['postParPage'] >= 1) ? $_POST['postParPage'] : 5;
	// Preparation du cache
	$setCache = array(
	'postParPage' => $postParPage,
	'com_actif' => $com_actif, 
	'pagi_actif' => $pagi_actif,
	'titre' => $titre,
	'titre_news' => $titre_news,
	'edito_actif' => $edito_actif,
	'edito_title' => $edito_titre,
	'edito_content' => html_entity_decode(stripcslashes($edito_contenu))
	);
		
		if ($config_cache->setCache($setCache))
		{
		header("Refresh: 5;url=index.php?module=admin_news");
		$this->mvc->template->show('news/admin_news_ConfigSucces');
		}
		else
		{
		$this->mvc->template->show('news/admin_news_ConfigBad');
		}

	}
	else
	{
	$this->mvc->template->postParPage = $this->config['postParPage'];
	$this->mvc->template->com_actif = $this->config['com_actif'];
	$this->mvc->template->pagi_actif = $this->config['pagi_actif'];
	$this->mvc->template->titre = $this->config['titre'];
	$this->mvc->template->titre_news = $this->config['titre_news'];
	$this->mvc->template->edito_actif = $this->config['edito_actif'];
	$this->mvc->template->edito_title = $this->config['edito_title'];
	$this->mvc->template->edito_content = $this->config['edito_content'];
	$this->mvc->template->show('news/admin_news_Config');
	}
}


public function addCat()
{
$this->setInfo('page_title', 'Ajout d\'une cat&eacute;gorie');
	if (isSet($_POST['categorie']) && !empty($_POST['categorie']))
	{
	$news = new News(DB::getInstance());
		if ($news->addCat($_POST['categorie'], $_POST['description']))
		{
		header("Refresh: 5;url=index.php?module=admin_news");
		$this->mvc->template->show('news/admin_news_CatSucces');
		}
		else
		{
		$this->mvc->template->show('news/admin_news_CatBad');
		}
	}
	else
	{
	$this->mvc->template->show('news/admin_news_Cat');
	}
}


public function addpost()
{
$this->setInfo('page_title', 'R&eacute;daction d\'un poste');
$news = new News(DB::getInstance());
	if (isSet($_POST['news']))
	{
		if ($news->addnews($_SESSION['user']['id'], $_POST['categorie'], stripcslashes($_POST['titre']), stripcslashes(html_entity_decode(stripspace($_POST['news'])))))
		{		
		header("Refresh: 5;url=index.php?module=admin_news");
		$this->mvc->template->show('news/admin_news_AddPostSucces');
		}
		else
		{
		$this->mvc->template->show('news/admin_news_AddPostBad');
		}
	}
	else
	{
	$data = $news->getCat();
		if (count($data)>0)
		{
		$this->mvc->template->listCategorie = $data;
		$this->mvc->template->show('news/admin_news_AddPost');
		}
		else
		{
		$this->mvc->html->setCodeScript("alert('Avant d\'Ã©crire une poste. Ajouter une catÃ©gorie');");
		header("Refresh: 0;url=index.php?module=admin_news&action=addCat");
		}
	}
}

}
?>