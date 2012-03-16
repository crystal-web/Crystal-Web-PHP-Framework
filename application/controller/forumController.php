<?php
/**
* @title Connection
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description 
Autorisation requise
	modo_edit
	modo_autorespon
	modo_move
	modo_annonce
OP
¨	admin_category
	admin_sujet
*/

Class forumController extends Controller {



/***************************************
*	Les catégorises de forum
***************************************/
public function index()
{
$this->mvc->Page->setPageTitle('Liste des cat&eacute;gories')
				->setBreadcrumb('Forum', 'Forum');
/***************************************
*	Level de l'utilisateur 1 = visiteur, 2 = membre
***************************************/
$userLevel = $this->mvc->Session->user('level');
/***************************************
*	Groupe de l'utilisateur
***************************************/
$groupList = explode('|', $this->mvc->Session->user('group'));


$forum = $this->loadModel('ForumCat');
$listCat = $forum->getCategorie($userLevel, false, $groupList);

	/***************************************
	*	Test si la catégorie existe 
	***************************************/
	if (isSet($listCat[0]) && !empty($listCat[0]->cat_id))
	{
	$this->mvc->Template->listCat = $listCat;
	$this->mvc->Template->show('forum/categorie');
	}
	/***************************************
	*	Si pas, c'est qu'il n 'y a pas de catégorie
	***************************************/
	else
	{
	$this->e404('no_cat');
	}

}

/***************************************
*	Les catégorises de forum par id
***************************************/
public function cat()
{
/***************************************
*	Identifiant de la categorie
***************************************/
$id = (isSet($this->mvc->Request->params['id'])) ? $this->mvc->Request->params['id'] : 0;
/***************************************
*	Level de l'utilisateur 1 = visiteur, 2 = membre
***************************************/
$userLevel = $this->mvc->Session->user('level');
/***************************************
*	Groupe de l'utilisateur
***************************************/
$groupList = explode('|', $this->mvc->Session->user('group'));


$forum = $this->loadModel('ForumCat');
$listCat = $forum->getCategorie($userLevel, $id, $groupList);

	/***************************************
	*	Test si la catégorie existe 
	***************************************/
	if (isSet($listCat[0]))
	{
		$this->mvc->Page->setPageTitle('Liste des cat&eacute;gories')
						->setBreadcrumb('Forum', 'Forum')
						->setBreadcrumb('forum/cat/slug:'.$listCat[0]->Cname.'/id:'.$listCat[0]->Cid, $listCat[0]->Cname);


		$this->mvc->Template->listCat = $listCat;
		$this->mvc->Template->show('forum/categorie');
	}
	/***************************************
	*	Si pas on met l'erreur 404
	***************************************/
	else
	{
	$this->e404('no_cat');
	}

}

/***************************************
*	sujet
***************************************/
public function sujet()
{
/***************************************
*	Identifiant du sujet
***************************************/
$id = (isSet($this->mvc->Request->params['id'])) ? $this->mvc->Request->params['id'] : 0;
/***************************************
*	Level de l'utilisateur 1 = visiteur, 2 = membre
***************************************/
$userLevel = $this->mvc->Session->user('level');
/***************************************
*	Groupe de l'utilisateur
***************************************/
$groupList = explode('|', $this->mvc->Session->user('group'));


$forum = $this->loadModel('ForumSujet');
$listSujet = $forum->getSujet($userLevel, $id, $groupList, $this->mvc->Request->page);
//debug($forum->sql);

	/***************************************
	*	Si un sujet exist, il y a une categorie
	*	Sinon, elle n'existe pas
	***************************************/
	if (!empty($listSujet[0]->Cname))
	{
	$this->mvc->Page->setPageTitle('Liste des sujets')
					->setBreadcrumb('Forum', 'Forum')
					->setBreadcrumb('forum/cat/slug:'.$listSujet[0]->Cname.'/id:'.$listSujet[0]->Cid, $listSujet[0]->Cname)
					->setBreadcrumb('forum/sujet/slug:'.$listSujet[0]->Sname.'/id:'.$listSujet[0]->Sid, $listSujet[0]->Sname);

	/***************************************
	*	Combien de page ?
	***************************************/
	$nb_post = $listSujet[0]->nb_topic;
	$nb_page = ceil( $nb_post / $forum->sujetParPage );
	$this->mvc->Template->nb_page	= $nb_page;
	
	$this->mvc->Template->listSujet = $listSujet;
	$this->mvc->Template->show('forum/sujet');
	}
	else
	{
	$this->e404('no_sujet');
	}
}

/***************************************
*	Liste des post d'un topic
***************************************/
public function topic()
{
/***************************************
*	Identifiant du topic
***************************************/
$id = (isSet($this->mvc->Request->params['id'])) ? $this->mvc->Request->params['id'] : 0;
/***************************************
*	Level de l'utilisateur 1 = visiteur, 2 = membre
***************************************/
$userLevel = $this->mvc->Session->user('level');
/***************************************
*	Groupe de l'utilisateur
***************************************/
$groupList = explode('|', $this->mvc->Session->user('group'));


$topic = $this->loadModel('ForumTopic');
$listTopic = $topic->getListPost($userLevel,$id, $this->mvc->Request->page, 'ASC', $groupList);
//debug($topic->sql);
/***************************************
*	A-t-on une reponse de la requete ?
***************************************/
if (isSet($listTopic[0]))
{

$this->mvc->Page->setPageTitle($listTopic[0]->titre)
				->setBreadcrumb('Forum', 'Forum')
				->setBreadcrumb('forum/cat/slug:'.$listTopic[0]->Cname.'/id:'.$listTopic[0]->Cid, $listTopic[0]->Cname)
				->setBreadcrumb('forum/sujet/slug:'.$listTopic[0]->Sname.'/id:'.$listTopic[0]->Sid, $listTopic[0]->Sname);				


/***************************************
*	Nb de reponse plus un
*	Si nb = 0, alors page 1 n'existe pas alors que si
***************************************/
$nb_post = $listTopic[0]->Tnb_post+1;
$nb_page = ceil( $nb_post / 10 );


$this->mvc->Template->nb_page=$nb_page;
$this->mvc->Template->page = $this->mvc->Request->page;
$this->mvc->Template->listTopic = $listTopic;

$this->mvc->Template->captcha_img = Captcha::generateImgTags("..");
$this->mvc->Template->captcha_hidden = Captcha::generateHiddenTags();
$this->mvc->Template->captcha_input = Captcha::generateInputTags();

$this->mvc->Template->show('forum/topic');
} // if (isSet($listTopic[0]))
/***************************************
*	Pas de reponse de la requete
***************************************/
else
{
$this->e404('no_topic');
}

}



/***************************************
*	Ajout et reponse	
*
*	Repondre a un topic
***************************************/
public function respon()
{
/***************************************
*	Identifiant du topic
***************************************/
$id = (isSet($this->mvc->Request->params['id'])) ? $this->mvc->Request->params['id'] : 0;
/***************************************
*	Level de l'utilisateur 1 = visiteur, 2 = membre
***************************************/
$userLevel = $this->mvc->Session->user('level');
/***************************************
*	Groupe de l'utilisateur
***************************************/
$groupList = explode('|', $this->mvc->Session->user('group'));



$topic = $this->loadModel('ForumTopic');
$listTopic = $topic->getListPost($userLevel,$id, $this->mvc->Request->page, 'DESC', $groupList);

/***************************************
*	Test si la requete revois une reponse 
***************************************/
if (isSet($listTopic[0]))
{
	/***************************************
	*	L'utilisateur peut t'il poster ?
	***************************************/
	if ($listTopic[0]->auth_post > $this->mvc->Session->user('level') AND !in_array($listTopic[0]->groupid, $groupList) AND $groupList[0] != '*')
	{
	return $this->e404('auth_post');
	}

//debug($listTopic);
$this->mvc->Page->setPageTitle('Répondre: '.$listTopic[0]->titre)
				->setBreadcrumb('Forum', 'Forum')
				->setBreadcrumb('forum/cat/slug:'.$listTopic[0]->Cname.'/id:'.$listTopic[0]->Cid, $listTopic[0]->Cname)
				->setBreadcrumb('forum/sujet/slug:'.$listTopic[0]->Sname.'/id:'.$listTopic[0]->Sid, $listTopic[0]->Sname);				
// echo debug($topic->sql);
// echo $topic->debug($listTopic );

$nb_page = ceil( $listTopic[0]->Tnb_post / 10 );
$nb_page = ($nb_page == 0) ? 1 : $nb_page; 
$this->mvc->Template->nb_page=$nb_page;
$this->mvc->Template->page = $this->mvc->Request->page;
$this->mvc->Template->listTopic = $listTopic;


	/***************************************
	*	a-t-on un message posté ?
	***************************************/
	if (isSet($this->mvc->Request->data->message))
	{
		$post = $this->loadModel('ForumPost');
		/***************************************
		*	Si c'est un visiteur,
		*	il doit validé le captcha
		***************************************/
		$Captcha = new Captcha();
		if (!$this->mvc->Session->isLogged() && $Captcha->checkCaptcha()==false)
		{
		$this->mvc->Session->setFlash('Le Captcha anti robot est incorrect','error');
		}
		/***************************************
		*	Est-t-il valide ? 
		***************************************/
		elseif ($post->validates($this->mvc->Request->data) 
			&& $this->mvc->Session->token())
		{
		$this->mvc->Session->makeToken();

		
		
		/***************************************
		*	Traitement des données
		***************************************/
		$data = new stdClass();
		$data->topic_id		= $listTopic[0]->topicId;
		$data->auteur		= $this->mvc->Session->user('idmember');
		$data->created_time	= time();
		$data->edited_time	= 0;
		$data->message		= $this->mvc->Request->data->message;
		$data->ip			= Securite::ipX();
			/***************************************
			*	insertion + recupération de l'id
			***************************************/
			if ($post->submit($data))
			{
			$topic->updatLastPostId($listTopic[0]->topicId, $post->id);
			$sujet = $this->loadModel('ForumSujet');
			$sujet->updatLastPostId($listTopic[0]->Sid, $post->id);
			Router::redirect(
				Router::url('forum/topic/slug:'.$listTopic[0]->titre.'/id:'.$listTopic[0]->topicId).'?page='.$nb_page.'#post'.$post->id);
			}
			/***************************************
			*	En cas d'erreur imprévue
			***************************************/
			else
			{
			$this->mvc->Session->setFlash('Erreur interne, nous n\'avons pu enregistrer votre messsage.Rééssayé plus tard.', 'error');
			Router::redirect();
			}
		}
		/***************************************
		*	Le message n'est pas valide
		***************************************/
		else
		{
		$this->mvc->Form->setErrors($post->errors);
		}
	}

$this->mvc->Template->captcha_img = Captcha::generateImgTags("..");
$this->mvc->Template->captcha_hidden = Captcha::generateHiddenTags();
$this->mvc->Template->captcha_input = Captcha::generateInputTags();
$this->mvc->Template->show('forum/respon');
}
/***************************************
*	Pas de reponse de la requete
***************************************/
else
{
$this->e404('no_topic');
}

}


public function addpost()
{
/***************************************
*	Identifiant du sujet
***************************************/
$id = (isSet($this->mvc->Request->params['id'])) ? $this->mvc->Request->params['id'] : 0;
/***************************************
*	Level de l'utilisateur 1 = visiteur, 2 = membre
***************************************/
$userLevel = $this->mvc->Session->user('level');
/***************************************
*	Groupe de l'utilisateur
***************************************/
$groupList = explode('|', $this->mvc->Session->user('group'));


$sujet = $this->loadModel('ForumSujet');
$listSujet = $sujet->getSujet($userLevel, $id, $groupList);

/***************************************
*	Ce sujet existe ?
***************************************/
if (isSet($listSujet[0]))
{
	/***************************************
	*	L'utilisateur peut t'il poster ?
	***************************************/
	if ($listSujet[0]->auth_topic > $this->mvc->Session->user('level') AND !in_array($listSujet[0]->groupid, $groupList) AND $groupList[0] != '*')
	{
	return $this->e404('auth_post');
	}

//debug($listTopic);
$this->mvc->Page->setPageTitle('Nouveau sujet dans '.$listSujet[0]->Sname)
				->setBreadcrumb('Forum', 'Forum')
				->setBreadcrumb('forum/cat/slug:'.$listSujet[0]->Cname.'/id:'.$listSujet[0]->Cid, $listSujet[0]->Cname)
				->setBreadcrumb('forum/sujet/slug:'.$listSujet[0]->Sname.'/id:'.$listSujet[0]->Sid, $listSujet[0]->Sname);				

	/***************************************
	*	A-t-on un message ?
	***************************************/
	if (isSet($this->mvc->Request->data->message))
	{
		$topic = $this->loadModel('ForumTopic');
		/***************************************
		*	Si c'est un visiteur,
		*	il doit validé le captcha
		***************************************/
		$Captcha = new Captcha();
		if (!$this->mvc->Session->isLogged() && $Captcha->checkCaptcha()==false)
		{
		$this->mvc->Session->setFlash('Le Captcha anti robot est incorrect','error');
		}
		/***************************************
		*	Est-t-il valide ? 
		***************************************/
		elseif ($topic->validates($this->mvc->Request->data) 
			&& $this->mvc->Session->token())
		{
		/***************************************
		*	On change le token
		*	On evite ainsi les bots....
		***************************************/
		$this->mvc->Session->makeToken();

		/***************************************
		*	On place les variables de base dans un tampon
		***************************************/
		$data = new stdClass();
		$data->first_post_id	= 0;
		$data->last_post_id	= 0;
		$data->nb_post		= 0;
		$data->is_annonce	= 'n';
		$data->created_time	= time();
		$data->auteur		= $this->mvc->Session->user('idmember');
		$data->titre		= $this->mvc->Request->data->titre;
		$data->sous_titre	= $this->mvc->Request->data->sous_titre;
		//debug($data);
		
			/***************************************
			*	Insertion + recupération de l'id
			***************************************/
			if ($topic->addTopic($data))
			{
			/***************************************
			*	On place les variable en tampon
			***************************************/
			$post = $this->loadModel('ForumPost');
			$dataPost = new stdClass();
			$dataPost->topic_id 	= $topic->id;
			$dataPost->auteur		= $data->auteur;
			$dataPost->created_time	= time();
			$dataPost->edited_time	= 0;
			$dataPost->ip			= Securite::ipX();
			$dataPost->message		= $this->mvc->Request->data->message;			
			
				/***************************************
				*	On enregistre le données postés
				***************************************/
				if ($post->submit($dataPost))
				{
				/***************************************
				*	Met a jour le Topic
				*	On lui indique lequel avec $topic->id
				*	On lui indique quel est le premier post
				***************************************/
				$data->id				= $topic->id;
				$data->sujet_id			= $id;
				$data->first_post_id 	= $post->id;
					/***************************************
					*	On fait l'update
					***************************************/
					if ($topic->addTopic($data))
					{
					/***************************************
					*	Mise a jour du derniere topic
					*	et le post qui va avec
					***************************************/
					$sujet = $this->LoadModel('ForumSujet');
					$sujet->addTopic($id, $post->id);
					$this->mvc->Session->setFlash('Votre topic a bien été enregistré');
					Router::redirect('forum/topic/slug:'.$data->titre.'/id:'.$topic->id);
					} // if ($topic->addTopic($data))
				} // if ($post->submit($dataPost))
			} // if ($topic->addTopic($data))
		} // if ($topic->validates($this->mvc->Request->data) && $this->mvc->Session->token())
		/***************************************
		*	Le message est pas valide
		***************************************/
		else
		{
		$this->mvc->Form->setErrors($post->errors);
		}
	}

$this->mvc->Template->captcha_img = Captcha::generateImgTags("..");
$this->mvc->Template->captcha_hidden = Captcha::generateHiddenTags();
$this->mvc->Template->captcha_input = Captcha::generateInputTags();
$this->mvc->Template->show('forum/addPost');
}
else
{
$this->e404('no_sujet');
}

}


/***************************************
*	
*	Pour la modération
*
***************************************/

public function move_it()
{
if (!$this->mvc->Acl->isAllowed())
{
return $this->e404();
}

/***************************************
*	Si moveto exist on demande un deplacement
***************************************/
if (isSet($this->mvc->Request->data->moveto) && isSet($this->mvc->Request->data->topic_id))
{
/***************************************
*	Charge les infos du topic a deplacer
***************************************/
$topic = $this->loadModel('ForumTopic');
$findTopic = array(
	'conditions' => array('id' => $this->mvc->Request->data->topic_id)
	);
$topicinfo = $topic->findFirst($findTopic);

	/***************************************
	*	Si topicinfo retourne false, le topic n'existe pas
	***************************************/
	if ($topicinfo)
	{
	/***************************************
	*	On doit modifier, le nb_post, nb_topic
	*	savoir si le derniere post du sujet de notre topic est le dernier post
	***************************************/

	
	
		
		/***************************************
		*	On charge les infos du sujet du topic a deplacer
		***************************************/
		$sujet = $this->loadModel('ForumSujet');
		$findSujet = array(
			'conditions' => array(
				'id'			=> $topicinfo->sujet_id
				)
			);
		$sujetBeforeMove = $sujet->findFirst($findSujet);

		
		/***************************************
		*	On charge les infos du sujet dans lequel on déplace
		***************************************/
		$findSujetToMove = array(
			'conditions' => array(
				'id'			=> $this->mvc->Request->data->moveto
				)
			);
		$sujetToMove = $sujet->findFirst($findSujetToMove);



		
		/***************************************
		*	Si le last_post_id est different de celui de topicinfo
		*	alors ce n'est pas le derniere
		***************************************/

		// Attribue les nouvelles valeurs a l'ancien sujet
		$changeSujetBefore			= new stdClass();
		$changeSujetBefore->id		= $topicinfo->sujet_id;
		$changeSujetBefore->nb_topic= $sujetBeforeMove->nb_topic - 1;
		$changeSujetBefore->nb_post	= $sujetBeforeMove->nb_post - $topicinfo->nb_post;
		
		
		$changeSujetMove			= new stdClass();
		$changeSujetMove->id		= $sujetToMove->id;
		$changeSujetMove->nb_topic	= $sujetToMove->nb_topic + 1;
		$changeSujetMove->nb_post	= $sujetToMove->nb_post + $topicinfo->nb_post;
		
		// Enregistre les modifications
		if ($sujet->save($changeSujetBefore) && $sujet->save($changeSujetMove))
		{
		
			/***************************************
			*	On modifie le topic et lui donne la nouvelle adresse
			***************************************/
			$changeTopic			= new stdClass();
			$changeTopic->id		= $this->mvc->Request->data->topic_id;
			$changeTopic->sujet_id	= $this->mvc->Request->data->moveto;

			if ($topic->save($changeTopic))
			{
			// On attribue les nouvelles valeurs au sujet 
			// dans lequel on deplace

			
			/*
			$updSujet = new stdClass();
			$updSujet->id			= $this->mvc->Request->data->moveto;
			$updSujet->nb_topic		= +1
			$updSujet->nb_post		= $topicinfo->nb_post//*/
			
			
			//debug($topic->sql);
			$this->mvc->Session->setFlash('Topic déplacé');
			//Router::redirect('forum/topic/slug:'.$topicinfo->titre.'/id:'.$this->mvc->Request->data->topic_id);
			}
			
		}


	}
	else
	{
	$this->mvc->Session->setFlash('Erreur, topic introuvable','warning');
	Router::redirect('forum');
	}


}
else
{
$this->mvc->Session->setFlash('Impossible de déplacer, le topic n\'a pas été choisi','warning');
Router::redirect('forum');
}


}

/***************************************
*	
*	Pour l'administration
*
***************************************/

public function admin_category()
{
if (!$this->mvc->Acl->isAllowed())
{
return $this->e404();
}

if (isSet($this->mvc->Request->data->description))
{
$catgorie = $this->loadModel('ForumCat');
	if ($catgorie->validates($this->mvc->Request->data))
	{
		$catgorie->save($this->mvc->Request->data);
		$this->mvc->Session->setFlash('Catégorie ajouté');
		Router::redirect('forum/admin_category');
	}
	else
	{
	$this->mvc->Form->setErrors($catgorie->errors);
	}
}
$this->mvc->Page->setPageTitle('Création d\'une catégorie')->setBreadcrumb('forum', 'Forum');

echo '<form method="post">'.
	$this->mvc->Form->input('name', 'Titre de la catégorie: ').
	$this->mvc->Form->input('description', 'Brève description:', array('type' => 'textarea')).
	$this->mvc->Form->input('submit', 'Enregistrer', array('type'=> 'submit')).
	'</form>';
}



public function admin_sujet()
{

if (!$this->mvc->Acl->isAllowed())
{
return $this->e404();
}

$cat = $this->loadModel('ForumCat');
$catList = $cat->getCategory();

/***************************************
*	Si aucune catégorie, on redirige
***************************************/
if (!count($catList))
{
$this->mvc->Session->setFlash('Créé d\'abord une catégorie', 'warning');
Router::redirect('forum/admin_category');
}
$this->mvc->Page->setPageTitle('Création d\'un sujet')->setBreadcrumb('forum', 'Forum');

$memberGroup = $this->loadModel('MemberGroup');
$groupList = $memberGroup->find(array('order'=>'MemberGroup.id ASC'));

if ($this->mvc->Request->data)
{

	/***************************************
	*	Conversion de l'objet en tableau
	***************************************/
	$groupListCast = array();
	foreach($groupList AS $k=>$v)
	{
	$groupListCast[] = (int) $v->id;
	}

	$data = new stdClass();
	if (!empty($this->mvc->Request->data->name))
	{
	$data->cat_id = $this->mvc->Request->data->cat_id;
	$data->name = $this->mvc->Request->data->name;
	$data->description = $this->mvc->Request->data->description;
	$data->groupid = 0;
	
		switch($this->mvc->Request->data->auth_view)
		{
		case 'all':
		$data->auth_view = 1;
		$data->groupid = 0;
		break;
		case 'member':
		$data->auth_view = 2;
		$data->groupid = 0;
		break;
		default:
		$data->auth_view = 9;
		$data->groupid = ($data->groupid == 0) ? $this->mvc->Request->data->auth_view : 0;
		break;
		}
		
		switch($this->mvc->Request->data->auth_topic)
		{
		case 'all':
		$data->auth_topic = 1;
		$data->groupid = 0;
		break;
		case 'member':
		$data->auth_topic = 2;
		$data->groupid = 0;
		break;
		default:
		$data->auth_topic = 9;
		$data->groupid = ($data->groupid == 0) ? $this->mvc->Request->data->auth_topic : 0;
		break;
		}

		switch($this->mvc->Request->data->auth_post)
		{
		case 'all':
		$data->auth_post = 1;
		$data->groupid = 0;
		break;
		case 'member':
		$data->auth_post = 2;
		$data->groupid = 0;
		break;
		default:
		$data->auth_post = 9;
		$data->groupid = ($data->groupid == 0) ? $this->mvc->Request->data->auth_post : 0;
		break;
		}
		
		$sujet = $this->loadModel('ForumSujet');
		if ($sujet->save($data))
		{
		$this->mvc->Session->setFlash('Sujet enregistré');
		}
		
		
	}
}

echo '<div style="float:right;width:300px;">
Information:<br>
<ul>
<li>Lorsque vous définissez un groupe, celui-ci est maître.</li>
<li>L\'ensemble des actions sera disponible pour le groupe.</li>
<li>Si par exemple, vous définissez que les droits de lecture sont  pour une groupe,
aucune autre personne ne pourra y participé. Cela, même si l\'adresse de la page est communiqué par un tiers.</li>
<li>Imaginons un cas particulier, ou tout le monde peut créer, mais personne ne peut répondre
a l\'exception, d\'un groupe et de l\'auteur. Vous définirez alors que la création est autorisé, mais pas la réponse "qui sera défini sur un groupe"</li>
</ul>
</div>

<fieldset>
	<legend>Création d\'un sujet</legend>
<form method="post" action="">';



	echo '<div class="clearfix">
		<label for="inputcat">Catégorie: </label>
		<div class="input">
		<select id="inputcat" name="cat_id">';
		foreach ($catList AS $k=>$v)
		{
		echo'<option value="'.$v->id.'">'.$v->name.'</option>';
		}
	echo '</select></div></div>';
	
echo'
<div class="clearfix">
		<label for="inputname">Nom: </label>
<div class="input">
<input type="text" id="inputname" name="name">
</div></div>

<div class="clearfix">
		<label for="inputdescription">Description: </label>
<div class="input">
<textarea cols=40 rows=4 name="description" id="inputdescription"></textarea>
</div></div>
';


	
	


	echo '<div class="clearfix">
		<label for="inputauth_view">Droit de lire: </label>
		<div class="input">
		<select id="inputauth_view" name="auth_view">
			<option value="all">Tout le monde</option>
			<option value="member">Si membre</option>';
				if (count($groupList))
				{
				echo '<optgroup label="Groupe">';
					foreach ($groupList AS $k=>$v)
					{
					echo'<option value="'.$v->identifiant.'">'.$v->name.'</option>';
					}//*/
				echo '</optgroup>';
				}
	echo '</select></div></div>';
	
	
	echo '<div class="clearfix">
		<label for="inputauth_post">Droit de répondre: </label>
		<div class="input">
		<select id="inputauth_post" name="auth_post">
			<option value="all">Tout le monde</option>
			<option value="member">Si membre</option>';
				if (count($groupList))
				{
				echo '<optgroup label="Groupe">';
					foreach ($groupList AS $k=>$v)
					{
					echo'<option value="'.$v->identifiant.'">'.$v->name.'</option>';
					}//*/
				echo '</optgroup>';
				}
	echo '</select></div></div>';
	

	
	echo '<div class="clearfix">
		<label for="inputauth_topic">Droit de création: </label>
		<div class="input">
		<select id="inputauth_topic" name="auth_topic">
			<option value="all">Tout le monde</option>
			<option value="member">Si membre</option>';
				if (count($groupList))
				{
				echo '<optgroup label="Groupe">';
					foreach ($groupList AS $k=>$v)
					{
					echo'<option value="'.$v->identifiant.'">'.$v->name.'</option>';
					}//*/
				echo '</optgroup>';
				}
	echo '</select></div></div>
	
<input type="submit" />
</form>

</fieldset>';

}







/***************************************
*	Pas de post, pas de topic pas de forum
*	Un rien trop global
***************************************/
private function e404($refused=null)
{

switch($refused)
{
/***************************************
*	Autorisation
***************************************/
case 'auth_post':
	$this->mvc->Page->setPageTitle('Autorisation requise')
				->setBreadcrumb('Forum', 'Forum');
	echo 'Vous n\'avez pas le droit de poster ici';
break;
case 'auth_view':
	$this->mvc->Page->setPageTitle('Autorisation requise')
				->setBreadcrumb('Forum', 'Forum');
	echo 'Vous n\'avez pas le droit de lire ici';
break;
/***************************************
*	Page manquant ou requete null
***************************************/
case 'no_sujet':
	$this->mvc->Page->setPageTitle('Sujet introuvable')
				->setBreadcrumb('Forum', 'Forum');
	echo 'Impossible de trouver le sujet';
break;
case 'no_cat':
	$this->mvc->Page->setPageTitle('Catégorie introuvable')
				->setBreadcrumb('Forum', 'Forum');
	echo 'Impossible de trouver la catégorie';
break;
case 'no_topic':
	$this->mvc->Page->setPageTitle('Topic introuvable')
				->setBreadcrumb('Forum', 'Forum');
	echo 'Impossible de trouver le topic';
break;
default:
$this->mvc->Page->setPageTitle('Page introuvable')->setBreadcrumb('Forum', 'Forum');
	echo 'Page introuvable';
break;
}

$this->mvc->Template->show('forum/404');
}

}
?>