<?php
/**
* @title Article / News
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/

Class articleController Extends Controller {

// Configuration par défaut
private $preconfig = array(
	'postParPage' => 5,
	'com_actif' => 'y', 
	'pagi_actif' => 'y',
	'titre' => 'Article',
	'titre_article' => 'Article du site',
	'edito_actif' => 'y',
	'edito_title' => 'Bienvenue sur votre site !',
	'edito_content' => '<p>
	Cette espace est l&#39;ent&ecirc;te de votre syst&egrave;me de article.<br />
	Vous pouvez par exemple y plac&eacute; quelques chose d&#39;important, le message sera toujours, le premiere de la liste.</p>'
	);	
private $config;
/*** Methode ***/
private $module = 'Article';

	
/*** Methode ***/
// Chargement de la configuration
private function loadconfig()
{
/* PRELOAD */
	// Lecture du cache
	$config_cache = new Cache('article', $this->preconfig);
	$this->config = $config_cache->getCache();
		// Si le cache n'existe pas on le crée
		if ($this->config == false)
		{
		$config_cache->setCache();
		$this->config = $this->preconfig;
		}
/* END PRELOAD */

	// Les catégories
	$articleCategorieModel = $this->loadModel('ArticleCat');
	$articleCategorieModel->type = 'article';

		foreach($articleCategorieModel->getCategorie() AS $k => $v)
		{
		$this->mvc->Page->setMenu(
				'Cat&eacute;gories',
				clean($v->categorie, 'str') . ' ('.$v->nb.')',
				Router::url('article/cat/slug:'.clean($v->categorie, 'slug').'/id:'.$v->idcategorie)
			);
		}
	// END Les catégories

}
	
/*
*  Listage de posts
*/
public function index() 
{
$this->loadconfig();

$this->mvc->Page->setBreadcrumb('article',$this->module)
				->setPageTitle($this->config['titre_article']);

// Edito et config
$this->mvc->Template->com_actif = $this->config['com_actif'];
$this->mvc->Template->edito_actif = $this->config['edito_actif'];
$this->mvc->Template->edito_title = $this->config['edito_title'];
$this->mvc->Template->edito_content = $this->config['edito_content'];
// END Edito et config



// Les article
$articleModel = $this->loadModel('Article');
	$articleModel->type = 'article';
	$articleModel->config = $this->config;
	$articleModel->page = $this->mvc->Request->page;
	
$nb_article= 0;
$article = $articleModel->getArticleList();
if ( $article )
{
$nb_article = $articleModel->findCount(array('online' =>  'y'));
}

$this->mvc->Template->article = $article;
// END Les article

	/***************************************
	*	Combien de page ?
	***************************************/
	$nb_page = ceil( $nb_article / $this->config['postParPage'] );
	$this->mvc->Template->nb_page	= $nb_page;


$this->mvc->Template->nb_article = $nb_article;;
$this->mvc->Template->pagi_actif = $this->config['pagi_actif'];
$this->mvc->Template->nombreDePages = $this->config['postParPage'];
$this->mvc->Template->isCategory=false;
$this->mvc->Template->show('article/index');
}


/*
* Liste des postes d'une categorie
*/
public function cat()
{
$this->loadconfig();
$id = isSet($this->mvc->Request->params['id']) ? $this->mvc->Request->params['id'] : 0;
$this->mvc->Template->isCategory=$id;
$this->mvc->Page->setBreadcrumb('article',$this->module)
				->setPageTitle($this->config['titre_article']);

// Edito et config
$this->mvc->Template->com_actif = $this->config['com_actif'];
$this->mvc->Template->edito_actif = $this->config['edito_actif'];
$this->mvc->Template->edito_title = $this->config['edito_title'];
$this->mvc->Template->edito_content = $this->config['edito_content'];
// END Edito et config



// Les article
$articleModel = $this->loadModel('Article');
	$articleModel->type = 'article';
	$articleModel->config = $this->config;
	$articleModel->page = $this->mvc->Request->page;
	
$article = $articleModel->getArticleList($id);
$nb_article = $articleModel->findCount(array('online' =>  'y', 'categorieid' => $id));

$this->mvc->Template->article = $article;
// END Les article

	/***************************************
	*	Combien de page ?
	***************************************/
	$nb_page = ceil( $nb_article / $this->config['postParPage'] );
	$this->mvc->Template->nb_page	= $nb_page;


$this->mvc->Template->nb_article = $nb_article;;
$this->mvc->Template->pagi_actif = $this->config['pagi_actif'];
$this->mvc->Template->nombreDePages = $this->config['postParPage'];
$this->mvc->Template->isCategory=false;
$this->mvc->Template->show('article/index');
	
}

/*
* Lecture d'un post
*/
public function post() 
{

$id = isSet($this->mvc->Request->params['id']) ? $this->mvc->Request->params['id'] : 0;


	//charge le model de article
	$articleModel = $this->loadModel('Article');
	$articleModel->type='article';
	// Je récupère la article.

	$uniqueData = $articleModel->getArticle($id);
	if( $uniqueData )
	{
	$this->loadconfig();
	$articleComModel = $this->loadModel('ArticleCommentaires');

		if ($this->mvc->Acl->isAllowed() AND $uniqueData->id_auteur == $this->mvc->Session->user('idmember'))
		{
		$this->mvc->Page->setMenu(
						'Gestion des articles',
						'Modifier cette article',
						Router::url('article/admin_addpost/id:'.$uniqueData->id)
					);
		}
	
	
		if (!empty($uniqueData->id))
		{
		$articleModel->hit($uniqueData->id);
			// Information sur la page

			$this->mvc->Page->setBreadcrumb('article',$this->module);
			$this->mvc->Page->setBreadcrumb('article/cat/slug:'.clean($uniqueData->categorie, 'slug').'/id:'.$uniqueData->categorieid,$uniqueData->categorie);
			$this->mvc->Page->setPageTitle(clean($uniqueData->titre, 'str'));

			// Template 
			$this->mvc->Template->com_actif = $this->config['com_actif'];
			$this->mvc->Template->nbcom =  $articleComModel->comCount($uniqueData->id);
			$this->mvc->Template->getComm = $articleComModel->getCom($uniqueData->id);//$articleModel->getComm($id);	
			$this->mvc->Template->output = $uniqueData;
			$this->mvc->Template->show('article/post');	
		}
		else
		{
			Router::error(404);
			$this->mvc->Page->setPageTitle('Page not found');
			$this->mvc->Template->show('article/post404');
		}
	}
}



// Ajout d'un commentaire
public function commentpost()
{
$this->loadconfig();

	if ($this->config['com_actif'] && $this->mvc->Session->token())
	{
		$article = $this->loadModel('Article');
		$article->type = 'article';
		$infoArticle = $article->getArticle($this->mvc->Request->params['id']);

		$mod = $this->loadModel('ArticleCommentaires');
		debug($this->mvc->Request->data);
		if ($mod->validates($this->mvc->Request->data))
		{
		$mod->add($this->mvc->Request->data);
		$this->mvc->Session->setFlash('Votre commentaire &agrave; bien &eacute;t&eacute; enregistr&eacute;, il appara&icirc;tra apres validation.');
		}
		else
		{
			$str=NULL;
			foreach($mod->errors AS $data)
			{
				$str .= $data.'<br>';
			}
			$this->mvc->Session->setFlash($str, 'error');
		}
	//Redirige apres le commentaire
	Router::redirect('article/post/slug:'.$infoArticle->titre.'/id:' . $this->mvc->Request->params['id']);

	}
	else
	{
	Router::error(404);
	$this->mvc->Page->setPageTitle('Page not found');
	$this->mvc->Template->show('article/post404');
	}

}



/*
*
*	Ajax request
*
*/



/**
* Liste les posts lorsque la pagination est désactivé
*
*/
public function ajax()
{
$this->loadconfig();
	$this->mvc->Template->com_actif = $this->config['com_actif'];
	// Les article
	if (isSet($_GET['lastid']))
	{
	$id = (int) $_GET['lastid'];
	$addQuery = NULL;
		if (isSet($_GET['cat']))
		{
		$_GET['cat'] = intval($_GET['cat']);
		$addQuery =' AND Categorie.idcategorie = '.$_GET['cat'];
		}
	
	$articleModel = $this->loadModel('Article');
	$meArticleSql = array(
		'fields' => "Article.id, Article.titre, Article.content, Article.date, COUNT(Commentaire.id) AS count, Cdate AS lastcomm, Member.loginmember AS auteur, Categorie.categorie, Categorie.description, Article.hit", 
		'conditions'	=> "online =  'y'
AND Article.id < " . $id . $addQuery,
		'limit'		=> '0, '.$this->config['postParPage'],
		'join'			=>	array(__SQL . '_Member AS Member' => 'Article.id_auteur = Member.idmember',
								__SQL . '_ArticleCat AS Categorie' => "Article.categorieid = Categorie.idcategorie",
								__SQL . "_ArticleCommentaires AS Commentaire" => "Article.id = Commentaire.id_Article AND Commentaire.valide = 'y'"),
		'group'			=> 'Article.id',
		'order'			=> 'Article.id DESC'
	);
	//debug($id);
	$this->mvc->Template->article = $articleModel->find($meArticleSql);
	}
	// END Les article
	
	$this->mvc->Template->show('article/ajax/article');
}



/*
*
*	Zone Admin
*
*/



/**
* Permet l'ajout de catégorie
*
*/
public function admin_addcat()
{
	if ($this->mvc->Acl->isAllowed())
	{
	$this->loadconfig();

	// Les catégories
	$articleCategorieModel = $this->loadModel('ArticleCat');
	$articleCategorieModel->type = 'article';
	
	$this->mvc->Page->setBreadcrumb('article',$this->module);
	
	$this->mvc->Page->setPageTitle('Ajout d\'un article');

	$articlecat = $this->loadModel('ArticleCat');
		if (!empty($this->mvc->Request->data))
		{	
			if ($articlecat->validates($this->mvc->Request->data))
			{
				if ($articlecat->add($this->mvc->Request->data))
				{
				$this->mvc->Session->setFlash('Cat&eacute;gorie ajout&eacute;');
				Router::redirect('article/admin_addpost');
				}//*/
			}
			else
			{
			$this->mvc->Form->setErrors($articlecat->errors);
			}
		}
	
	$this->mvc->Template->show('article/admin_addcat');

	}
	else
	{
	Router::error(404);
	$this->mvc->Page->setPageTitle('Page not found');
	$this->mvc->Template->show('article/post404');
	}
}



/**
* Permet l'ajout d'article et l'edition d'un post
*
*/
public function admin_addpost()
{
$this->loadconfig();

	if ($this->mvc->Acl->isAllowed())
	{
//id_auteur	categorieid	titre	content	date	hit	online	
	$this->mvc->Page->setBreadcrumb('article',$this->module);
	
	$this->mvc->Page->setPageTitle('R&eacute;daction d\'un article');
	$articlecat = $this->loadModel('ArticleCat');
	$articlecat->type = 'article';
	
	$categorieid=array();

		foreach ($articlecat->getCategorie('?') AS $k => $v)
		{
		$categorieid[$v->idcategorie] = $v->categorie . ' ('.$v->nb.')'; 
		}
	
	if (!count($categorieid))
	{
	$this->mvc->Session->setFlash('Vous n\'avez aucune catégorie');
	Router::redirect('article/admin_addcat');
	
	}
	
	$id = isSet($this->mvc->Request->params['id']) ? $this->mvc->Request->params['id'] : 0;

	

	$article = $this->loadModel('Article');
	$article->type = 'article';
	$monArticle = $article->getArticle($id);

		if ($id > 0)
		{
			if (
				!$this->mvc->Acl->isAllowed()
				and $this->mvc->Session->user('idmember') != $monArticle->id_auteur 
				)
			{
			
			
			die('id_auteur & isGrand=false ');
			// !$this->mvc->Acl->isAllowed()
			$this->mvc->Session->setFlash('Cette article n\'est pas le votre', 'error');
			Router::redirect('article');
			}
		}
		elseif($id > 0)
		{
		$this->mvc->Page->setPageTitle('Modification d\'un article');
		}



		if (!empty($this->mvc->Request->data))
		{
		$article = $this->loadModel('Article');
		$article->type = 'article';

				if ($article->validates($this->mvc->Request->data))
				{
				$article->id_auteur = $this->mvc->Session->user('idmember');
					if ($article->add($this->mvc->Request->data, $id))
					{
					$this->mvc->Session->setFlash('Article ajout&eacute;');
					Router::redirect('article/admin_addpost');
					}//*/
				}
				else 
				{
				$this->mvc->Form->setErrors($article->errors);
				}

		}
		else
		{
		$this->mvc->Request->data = $monArticle;
		}

		
	$formCategorie = $this->mvc->Form->input('categorieid', 'Cat&eacutegorie: ', 
		array('options' => $categorieid));
	$formOnline = $this->mvc->Form->input('online', 'En ligne: ', 
		array('options' => 
			array('y' => 'Oui', 'n' => 'Non') ) );
	$formTitre = $this->mvc->Form->input('titre', 'Titre: ');
	$formContent = $this->mvc->Form->input('content', 'Article: ', 
		array('type'=>'textarea', 'editor' => 
			array( 'params' => 
				array('model' => 'htmlfull') ) ) );

	$this->mvc->Template->form = $formCategorie.$formOnline.$formTitre.$formContent;
	$this->mvc->Template->show('article/admin_addpost');

	}
	else
	{
	Router::error(404);
	$this->mvc->Page->setPageTitle('Page not found');
	$this->mvc->Template->show('article/post404');
	}
}



/**
* Permet de supprimer un post
*/
public function admin_delpost() 
{
	//Le membre est en ligne ?
	if ($this->mvc->Acl->isAllowed())
	{
	if (!$this->mvc->Session->token()){die('A valid token is required');}
	$article = $this->loadModel('Article');
	

		if (isSet($this->mvc->Request->params['id']))
		{

			if ( $this->mvc->Acl->isAllowed()
				OR $article->isAuthor($this->mvc->Request->params['id'], $this->mvc->Session->user('idmember')) )
			{
			$article->delete($this->mvc->Request->params['id']);
			$this->mvc->Session->setFlash('Article supprimer avec succ&egrave;s');
			Router::redirect('article');
			}
			else
			{
			Router::error(404);
			$this->mvc->Page->setPageTitle('Page not found');
			$this->mvc->Template->show('article/post404');
			}
		}
		else
		{
		Router::error(404);
		$this->mvc->Page->setPageTitle('Page not found');
		$this->mvc->Template->show('article/post404');
		}
	}// END Le membre est en ligne ?
	else
	{
	Router::error(404);
	$this->mvc->Page->setPageTitle('Page not found');
	$this->mvc->Template->show('article/post404');
	}
}



/**
* Permet de lister les article pour les editer ou effacer
*
*/
public function getlist()
{
	if ($this->mvc->Acl->isAllowed())
	{
	$this->mvc->Page->setPageTitle('Gestion des articles');
	$this->loadConfig();
	// Les article
	$articleModel = $this->loadModel('Article');
	$articleModel->type = 'article';
	$this->config['postParPage'] = 30;
	$articleModel->config = $this->config;
	$articleModel->page = $this->mvc->Request->page;
	$this->mvc->Template->token = $this->mvc->Session->getToken();
	$this->mvc->Template->articleList = $articleModel->getArticleList();
	$this->mvc->Template->show('article/getlist');


// END Les article
	}
	else
	{
	Router::error(404);
	$this->mvc->Page->setPageTitle('Page not found');
	$this->mvc->Template->show('article/post404');
	}
}



/**
* Gestion des commentaires
*
*/
public function admin_comment()
{
	if ($this->mvc->Acl->isAllowed())
	{
	$this->mvc->Page->setPageTitle('Gestion des commentaires')->setBreadcrumb('article',$this->module);
	$valide = (isSet($this->mvc->Request->data->valide)) ? $this->mvc->Request->data->valide :'n';
	$comment = $this->loadModel('ArticleCommentaires');

	$slug = (isSet($this->mvc->Request->params['slug'])) ? $this->mvc->Request->params['slug'] : NULL;
	$id = (isSet($this->mvc->Request->params['id'])) ? $this->mvc->Request->params['id'] : NULL;

		if ($this->mvc->Session->token())
		{
			switch($slug)
			{
			case 'y':
			$comment->changeStatut($id, 'y');
			break;
			case 'n':
			$comment->changeStatut($id, 'n');
			break;
			case 's':
			$comment->changeStatut($id, 's');
			break;
			}
		}

	$this->mvc->Template->getComm = $comment->getAll($valide);
	$this->mvc->Template->show('article/admin_comment');
	}
	else
	{
	Router::error(404);
	$this->mvc->Page->setPageTitle('Page not found');
	$this->mvc->Template->show('article/post404');
	}
}


public function admin_config()
{
	if ($this->mvc->Acl->isAllowed())
	{
	$this->mvc->Page->setPageTitle('Configuration des articles')->setBreadcrumb('article',$this->module);
	$this->loadConfig();
	
	
	if ($this->mvc->Request->data)
	{
	// Post par page
	$this->config['postParPage']	= (int) $this->mvc->Request->data->postParPage;
	$this->config['postParPage']	= ($this->config['postParPage'] == 0 or !intval($this->config['postParPage'])) ? 5 : $this->config['postParPage'];
	
	//Pagination
	$this->config['pagi_actif']		= ($this->mvc->Request->data->pagination == 'y') ? 'y' : 'n';
	
	//Commentaire
	$this->config['com_actif']		= ($this->mvc->Request->data->commentaire == 'y') ? 'y' : 'n';	
	
	//Editoriel
	$this->config['edito_actif']	= ($this->mvc->Request->data->edito == 'y') ? 'y' : 'n';	
	$this->config['edito_title']	= (!empty($this->mvc->Request->data->edito_title)) ? $this->mvc->Request->data->edito_title : $this->config['edito_title'];
	$this->config['edito_content']	= (!empty($this->mvc->Request->data->content)) ? $this->mvc->Request->data->content : $this->config['edito_content'];
	
	$config_cache = new Cache('article');
	$config_cache->setCache($this->config);
	$this->mvc->Session->setFlash('Connfiguration des articles enregistré');
	}
	
	
	$form = $this->mvc->Form->input('postParPage', 'Article par pages: ', array(
						'value' => $this->config['postParPage'])).
			$this->mvc->Form->input('pagination', 'Activer la pagination: ', array(
						'type' => 'radio',
						'option' => array(
							'y'=> 'Oui, manuelle',
							'n'=> 'Non, automatique'),
						'value' => $this->config['pagi_actif'])).
			$this->mvc->Form->input('commentaire', 'Activer les commentaires: ', array(
						'type' => 'radio',
						'option' => array(
							'y'=> 'Oui',
							'n'=> 'Non'),
						'value' => $this->config['com_actif']));
	$editoriel =	$this->mvc->Form->input('edito', 'Activer l\'editoriel: ', array(
						'type' => 'radio',
						'option' => array(
							'y'=> 'Oui',
							'n'=> 'Non'),
						'value' => $this->config['edito_actif'])).
			$this->mvc->Form->input('edito_titre', 'Titre de l\'editoriel: ', array(
						'value' => $this->config['edito_title'])).
			$this->mvc->Form->input('content', 'Editoriel: ', 
				array(
					'type'=>'textarea',
					'value'=> $this->config['edito_content'],
					'editor' => 
					array( 'params' => 
						array('model' => 'html') ) ) );
			
	
	
	$this->mvc->Template->form = $form;
	$this->mvc->Template->editoriel = $editoriel;
	$this->mvc->Template->show('article/admin_config');
	}
	else
	{
	Router::error(404);
	$this->mvc->Page->setPageTitle('Page not found');
	$this->mvc->Template->show('article/post404');
	}
}



public function rss()
{
$this->loadconfig();

// Les article
$articleModel = $this->loadModel('Article');
	$articleModel->type = 'article';
	$articleModel->page = 1;
	
	$config['postParPage'] = 30;
	$articleModel->config = $config;	
$this->mvc->Template->article = $articleModel->getArticleList();

$this->mvc->Template->show('article/rss');
$this->mvc->Page->setLayout('empty');


}

}
?>
