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


Class memberController extends Controller {

private function getMenu()
{
	if ($this->mvc->Acl->isAllowed('member', 'getList'))
    {
    $this->mvc->Page->setMenu('Membres Manager', 'Liste des membres', Router::url('member/getlist'));
    }
    if ($this->mvc->Acl->isAllowed('member', 'approb_change_login'))
    {
    $this->mvc->Page->setMenu('Membres Manager', 'Modification pseudo', Router::url('member/approb_change_login'));
    }

}



public function index()
{
/***************************************
*   Appel du menu
***************************************/
$this->getMenu('index');


    $member = loadModel('Member');
    $actu = loadModel('MemberActu');
    /***************************************
    *   Si une actu est posté
    ***************************************/
    if ($this->mvc->Session->isLogged())
    {

        if (isSet($this->mvc->Request->data->actu))
        {
        $data = new stdClass();
        $data->auteur = $this->mvc->Session->user('id');
      
        $data->time = time();
        $data->actu = $this->mvc->Request->data->actu;

            if ($actu->save($data))
            {
         	   $this->mvc->Session->setFlash('Actualité enregistré');
            }
			else
			{
       		   	$this->mvc->Session->setFlash('Oups...', 'warning');
			}

        }

    }

    /***************************************
    *   Recherche le profil
    ***************************************/

    if (isSet($this->mvc->Request->params['slug']))
    {

    $slug = clean($this->mvc->Request->params['slug'], 'extra');

    $query = array(
            'conditions'    => array(
                'loginmember' => $slug
                ),
            'join'          => array(
                __SQL . '_MemberInfo AS Info' => 'Info.thismember = Member.idmember',
                ),
            );

    } else {

    $query = array(
            'join'          => array(
                __SQL . '_MemberInfo AS Info' => 'Info.thismember = Member.idmember'
                ),
            'order'         => 'RAND()',
            'limit'         => '1'
            );

    }

	$respon = $member->findFirst($query);
    if ( $respon )
    {

        $this->mvc->Page->setBreadcrumb('member', 'Membre')
                    ->setPageTitle('Profil de ' . clean($respon->loginmember, 'slug'));

        $query = array(
            'conditions'    => array(
                'auteur' => $respon->idmember,
                ),
            'join'          => array(
                __SQL . '_Member AS InfoAuteur' => 'InfoAuteur.idmember = MemberActu.auteur'
                ),
            'limit'     => '0, 25',
            'order'     => 'time DESC'
            );
    $this->mvc->Template->actu = $actu->find($query);
    
    try {
    	$mTopic = loadModel('ForumTopic');
    	$nbTopic = $mTopic->countTopicMember($respon->idmember)->nbTopic;
    }
    catch ( Exception $e)
    {
    	$nbTopic = false;
    }
    
    try {
    	$mPost = loadModel('ForumPost');
    	$nbPost = $mPost->countPostMember($respon->idmember)->nbPost;
    }
    catch ( Exception $e)
    {
    	$nbPost = false;
    }    
	$this->mvc->Template->forumNbTopic = $nbTopic;
	$this->mvc->Template->forumNbPost = $nbPost;
	
	try {
		$mBanlist = loadModel('Banlist');
		$nBanL = $mBanlist->getBanMember($respon->loginmember); //$mBanlist->countBanMember($respon->loginmember)->nbBan;
		$nBan = count($nBanL);
	}
	catch ( Exception $e)
	{
		$nBan = false;
	}
	$this->mvc->Template->banlistNb = $nBan;
	
	try {
		$mIcon = loadModel('Iconomy');
		$nBalance = $mIcon->getBalance($respon->loginmember);
		if ($nBalance)
		{
			$nBalance = $nBalance->balance;
		}
		else
		{
			$nBalance = 0;
		}
	}
	catch ( Exception $e)
	{
		$nBalance = false;
	}
	$this->mvc->Template->iconomy = $nBalance;
	
	try {
		$mMonnaie = loadModel('MonnaieCash');
		$nCredit = $mMonnaie->getClientId($respon->idmember);
		if ($nCredit)
		{
			$nCredit = $nCredit->cash;
		}
		else
		{
			$nCredit = 0;
		}
	}
	catch ( Exception $e)
	{
		$nCredit = false;
	}
	$this->mvc->Template->credit = $nCredit;
	
	try {
		$mVote = loadModel('VoteBy');
		$nVote = $mVote->countVote($respon->idmember);
	}
	catch ( Exception $e)
	{
		$nVote = false;
	}
	$this->mvc->Template->votes = $nVote;
	
    $this->mvc->Template->info = $respon;
    $this->mvc->Template->show('member/profile');

    }else{
    $this->mvc->Session->setFlash('Profil introuvable', 'warning');
    //Router::redirect('member');
    }



}



/***************************************
*   Modification du mot de passe
***************************************/
public function change_password()
{
    if (!$this->mvc->Session->isLogged())
    {
    $this->mvc->Session->setFlash('Vous devez être connecté', 'warning');
    Router::redirect('auth');
    }

    /***************************************
    *   Création du fil arian et du titre
    ***************************************/
    $this->mvc->Page->setBreadcrumb('member', 'Membre')
                    ->setPageTitle('Changer mon mot de passe');

    /***************************************
    *   Appel du menu
    ***************************************/
    $this->getMenu();


    // Var errors
    $errors = array();

    if (isSet($this->mvc->Request->data->password))
    {
	    $data = new stdClass();
	    $data->loginmember  =  $this->mvc->Session->user('login');
	    $data->passmember   = isSet($this->mvc->Request->data->password) ? $this->mvc->Request->data->password : '';
	    $data->newpassword  = isSet($this->mvc->Request->data->newpassword) ? $this->mvc->Request->data->newpassword : '';
	    $data->confpassword = isSet($this->mvc->Request->data->confpassword) ? $this->mvc->Request->data->confpassword : '';

    if ($data->newpassword != $data->confpassword)
    {
  	  $errors['confpassword'] = 'Le nouveau mot de passe et la confirmation  sont différent';
    }

    if (empty($data->newpassword))
    {
 	   $errors['newpassword'] = 'Vous devez mettre un mot de passe';
    }

    if (empty($data->confpassword))
    {
  	  $errors['confpassword'] = 'Vous devez mettre un mot de passe';
    }

    /***************************************
    *   Vérifie que le mot de passe ancien est correct
    ***************************************/
    $member = $this->loadModel('Member');

        if (!$member->checkLogin($data))
        {
   	     $errors['password'] = 'Votre mot de passe est incorrect';
        }
        else
        {
            if ($member->changePassword($this->mvc->Session->user('id'), $data->confpassword))
            {
    	        $this->mvc->Session->setFlash('Votre mot de passe a bien été modifié');
            }
            else
            {
    	        $this->mvc->Session->setFlash("Une erreur c'est produite lors de la modification du mot de passe.<br  />
  	          Veuillez contacter l'administrateur du site.", 'error');
            }
        }



    }

    $form = $this->mvc->Form->input('password', 'Ancien mot de passe: ', array('type' => 'password')).
            $this->mvc->Form->input('newpassword', 'Nouveau mot de passe: ', array('type' => 'password')).
            $this->mvc->Form->input('confpassword', 'Confirmation mot de passe: ',  array('type' => 'password')).
            $this->mvc->Form->input('submit', 'Modifier mon mot de passe', array('type' => 'submit', 'class' => 'btn primary'));
    $this->mvc->Template->form = $form;
    $this->mvc->Template->show('member/change_password');
}


/***************************************
*   Reaquete de changement de pseudo
***************************************/
public function change_login()
{

/***************************************
*   Verifie les droits de l'utilisateur
***************************************/
if (!$this->mvc->Session->isLogged())
{
$this->mvc->Session->setFlash('Vous devez être connecté', 'error');
Router::redirect('auth');
}


/***************************************
*   Appel du menu
***************************************/
$this->getMenu();

/***************************************
*   Création du fil arian et du titre
***************************************/
$this->mvc->Page->setBreadcrumb('member', 'Membre')
                ->setPageTitle('Demander un changement de pseudo');

$member = loadModel('MemberChangeLogin');


$raison = $pseudo = $id = NULL;
    /***************************************
    *   Si on trouve une requete précédente
    *   On va la modifier
    ***************************************/
	$info = $member->findFirst(array('conditions' => 'idmember = '.$this->mvc->Session->user('id')));
    if ( $info )
    {
    $raison = $info->raison;
    $pseudo = clean($info->newlogin, 'slug');
    }


    /***************************************
    *   Essaye de validé les champs
    ***************************************/
    if (isSet($this->mvc->Request->data->pseudo))
    {
	$this->mvc->Request->data->pseudo = clean($this->mvc->Request->data->pseudo, 'slug');
		$searchMember = loadModel('Member');
		$resu = $searchMember->findFirst(array('conditions' => array('loginmember' => $this->mvc->Request->data->pseudo)));
		
		if ( $resu )
		{
		$member->errors['pseudo'] = 'Le pseudo est déjà pris';
		}
		elseif ($member->validates($this->mvc->Request->data))
        {

        $data = new stdClass();
            if (!empty($info->id)) { $data->id = $info->id; }

        $data->newlogin = $this->mvc->Request->data->pseudo;
        $data->raison = $this->mvc->Request->data->raison;
        $data->idmember = $this->mvc->Session->user('id');
        $data->time = time();

            if ($member->save($data))
            {
				$this->mvc->Session->setFlash('Votre demande à bien été enregistré.');
            }
        }
		

        $this->mvc->Form->setErrors($member->errors);

    }
	
    $this->mvc->Template->form = $this->mvc->Form->input('pseudo', 'Nouveau pseudo désiré:', array('value' => $pseudo)).
                                $this->mvc->Form->input('raison', 'Raison du changement de pseudo:', array(
                                    'value' => $raison,
                                    'type' => 'textarea',
                                    'style' => 'margin-left: 0px;
                                        margin-right: 0px;
                                        width: 534px;
                                        margin-top: 0px;
                                        margin-bottom: 0px;
                                        height: 165px;')).
    $this->mvc->Form->input('submit', 'Demander un changement de pseudo', array('type' => 'submit', 'class' => 'btn success'));
    $this->mvc->Template->show('member/change_login');


}

/**
 * 
 * Edition du profil
 */
public function edit()
{
$this->mvc->Page->setPageTitle('Editer mon profil')->setBreadcrumb('member', 'Membre');

    if (!$this->mvc->Session->isLogged())
    {
    $this->mvc->Session->setFlash('Vous devez être connecté');
    return;
    }


    
    
  
    /*
    echo '<ul>';
    foreach(Log::getLog(true) AS $k => $v)
    {
    	echo '<li>'.$v['type'] . ':' . $v['message'].'</li>';
    }
    echo '</ul>';
    //*/
    
    $mMember = loadModel('Member');
    $profilInfo = $mMember->findFirst(array(
    	'conditions' => 
    		array('idmember' => $this->mvc->Session->user('id')),
    	'join' => 
    		array(__SQL . '_MemberInfo AS MemberInfo' => 'Member.idmember = MemberInfo.thismember')
    	)
    	);

    $this->mvc->Template->profilInfo = $profilInfo;
    
    if ($profilInfo)
    {
		$this->mvc->Template->form = $this->mvc->Form->input('sex', 'Sexe:', array('options' => array('z' => 'Inconnu', 'x' => 'Masculin', 'y' => 'Féminin'), 'value' => $profilInfo->sex)).
		    $this->mvc->Form->input('job', 'Mon travail:', array('placeholder' => 'Plombier, il n\'est point de sot métier', 'value' => clean($profilInfo->job, 'str'))).
		    $this->mvc->Form->input('leisure', 'Mes passions:', array('placeholder' => 'La guitare, le saut à la perche et le vélo', 'value' => clean($profilInfo->leisure, 'str'))).
		    $this->mvc->Form->input('website', 'Mon site internet:', array('placeholder' => 'http://www.monsiteetblog.com', 'value' => clean($profilInfo->website, 'str'))).
		    $this->mvc->Form->input('location', 'Ma localisation:', array('placeholder' => 'France, Pyrénées', 'value' => clean($profilInfo->location, 'str'))).
		    $this->mvc->Form->input('birthday', 'Ma date de naissance:', array(
	                'value' => date('n-j-Y', $profilInfo->birthday),
	    			'type'=> 'date'));
    }
    else
    {
		$this->mvc->Template->form = $this->mvc->Form->input('sex', 'Sexe:', array('options' => array('z' => 'Inconnu', 'x' => 'Masculin', 'y' => 'Féminin'))).
		    $this->mvc->Form->input('job', 'Mon travail:', array('placeholder' => 'Plombier, y a pas de saut métier')).
		    $this->mvc->Form->input('leisure', 'Mes passions:', array('placeholder' => 'La guitare, le saut à la perche et le vélo')).
		    $this->mvc->Form->input('website', 'Mon site internet:', array('placeholder' => 'http://www.monsiteetblog.com')).
		    $this->mvc->Form->input('location', 'Ma localisation:', array('placeholder' => 'France, Pyrénées')).
		    $this->mvc->Form->input('birthday', 'Ma date de naissance:', array(
	    			'type'=> 'date'));
    }
    

   
    $errors = array();
    $dataToSave = new stdClass();
	if ($this->mvc->Request->data)
	{
		
	   if (isSet($_FILES['avatar']))
	   {
		    $file = $_FILES['avatar'];
			if (!empty($file['name']))
			{
		    $up = new Upload($file);
			    if ($up->prepare())
			    {
			    	if ($up->controlExtWhiteList(array('.png','.jpeg','.jpg','.gif')))
			    	{
			    		if (preg_match('#image#', $up->getMime()))
			    		{
			    			$im = new Image($up->getUploadPath());
			    			

			    			if ($im->setdir('./media/avatar'))
			    			{
			    				if (isSet($profilInfo->avatar))
				    			{
				    				if (strlen($profilInfo->avatar) > 1)
				    				{
				    					@unlink('./media/avatar/'.$profilInfo->avatar);
				    					Log::setLog('Suppression de l\'avatar', 'memberController');
				    				}
				    				else
				    				{
				    					Log::setLog('Suppréssion de l\'avatar inutile, valeur = ' . $profilInfo->avatar, 'memberController');
				    				}
				    			}
				    			
			    				$im->width(256);
			    				$im->height(256);
			    				$im->save(clean($this->mvc->Session->user('login'), 'slug').'.'.$im->getExt());
			    				
			    				$dataToSave->avatar = clean($this->mvc->Session->user('login'), 'slug').'.'.$im->getExt();
			    				$profilInfo->avatar = $dataToSave->avatar;
			    			} else { $errors['avatar'] = 'Erreur lors du transfère, l\'écriture est refusé'; }
			    			
			    	
			    		} else { $errors['avatar'] = 'Extention incorrect'; }
			    	} else { $errors['avatar'] = 'Extention incorrect'; }
			    } else { $errors['avatar'] = 'Extention incorrect'; }
			}
		}
		
		
        if (isSet($this->mvc->Request->data->website))
        {
            if(!empty($this->mvc->Request->data->website))
            {
                if (!isURL($this->mvc->Request->data->website))
                {
                $errors['website'] = 'L\'adresse de votre site internet est incorrect (http:// est probablement manquant) ';
                }
                else
              {
              		$dataToSave->website = $this->mvc->Request->data->website;
				}
            }
        }
        
		if (isSet($this->mvc->Request->data->day) AND isSet($this->mvc->Request->data->month) AND isSet($this->mvc->Request->data->year))
		{
			if (!checkdate($this->mvc->Request->data->month, $this->mvc->Request->data->day, $this->mvc->Request->data->year))
			{
				$errors['birthday'] = 'Date incorrect ';
			}
			else
			{
				$dataToSave->birthday = mktime(1,1,1,$this->mvc->Request->data->month, $this->mvc->Request->data->day, $this->mvc->Request->data->year);
			}
		}
		

        

        if (count($errors)==0)
        {
			$mMemberInfo = loadModel('MemberInfo');
        	$dataToSave->bio = $this->mvc->Request->data->bio;
        	$dataToSave->sign = $this->mvc->Request->data->sign;
        	$dataToSave->leisure = $this->mvc->Request->data->leisure;
        	$dataToSave->location = $this->mvc->Request->data->location;
        	$dataToSave->job = $this->mvc->Request->data->job;
        	$dataToSave->sex = $this->mvc->Request->data->sex;
        	
            $dataToSave->thismember = $this->mvc->Session->user('id');
            if ($mMemberInfo->changeInfo($dataToSave))
            {
            	// var_dump($mMemberInfo->sql);
            	//echo '<!-- ' . print($mMemberInfo->sql). ' -->';
            //	debug($dataToSave);
            	$this->mvc->Session->setFlash('Votre profil à été mise à jour');
            }
        }
	}

    $this->mvc->Form->setErrors($errors);
    $this->mvc->Template->Show('member/edit');
   
}


/***************************************
*   Manager des membres
***************************************/
public function manager()
{
/***************************************
*   Verifie les droits de l'utilisateur
***************************************/
if (!$this->mvc->Acl->isAllowed()) { $this->mvc->Session->setFlash('Vous n\'avez pas accès à cette page', 'error'); Router::redirect('auth'); }

/***************************************
*   Création du fil arian et du titre
***************************************/
$this->mvc->Page->setBreadcrumb('member', 'Membre')
                ->setBreadcrumb('member/manager', 'Manager')
                ->setPageTitle('GESTION DES MEMBRES');

/***************************************
*   Appel du menu
***************************************/
$this->getMenu();

$this->mvc->Template->show('member/manager');
}


/***************************************
*   Approuvé le changement de pseudo
***************************************/
public function approb_change_login()
{
/***************************************
*   Verifie les droits de l'utilisateur
***************************************/
if (!$this->mvc->Acl->isAllowed()) { $this->mvc->Session->setFlash('Vous n\'avez pas accès à cette page', 'error'); Router::redirect('auth'); }

/***************************************
*   Création du fil arian et du titre
***************************************/
$this->mvc->Page->setBreadcrumb('member', 'Membre')
                ->setBreadcrumb('member/manager', 'Manager')
                ->setPageTitle('GESTION DES MEMBRES');

/***************************************
*   Appel du menu
***************************************/
$this->getMenu();


$member = loadModel('MemberChangeLogin');

	if (isSet($this->mvc->Request->params['id']) && isSet($this->mvc->Request->params['stat']))
	{
		/* Test si la clé existe */
		$info = $member->findFirst(array('conditions' => 'id = ' . $this->mvc->Request->params['id']));
		if ( !$info )
		{
			$this->mvc->Session->setFlash('Impossible de traiter la requête, peut-être que celle-ci est déjà validé', 'error');
			return false;
		}

		
		// Charge le model
		$memberModel = loadModel('Member');
		// Recherche le membre
		$infoMember = $memberModel->findFirst(array('conditions' => 'idmember = ' . $info->idmember));
		
		// Si le retour est vide, le membre n'existe pas
		if ( !$infoMember )
		{
			$this->mvc->Session->setFlash('Impossible de traiter la requête, membre introuvable', 'error');
			return false;
		}

		switch ($this->mvc->Request->params['stat'])
		{
		//
		// On refuse
		//
		case '0':

			$p_text = '<p>Bonjour '.$infoMember->loginmember.'<br>
			Votre demande de changement de pseudo est refus&aecute;.<br>
			Peut-&ecirc;tre que celui-ci est d&eacute;j&agrave; utilis&eacute;, n\'est pas moral ou que votre demande n\'est pas justifi&eacute;<br><br>

			Bien &agrave; vous,<br>
			' . $this->mvc->Session->user('login').'<br><br>
			<hr>
			<a href="'.Router::url().'">'.Router::url().'</a>
			</p>';

			$mail = new Mail('Modification de votre pseudo', $p_text, $infoMember->mailmember, ADMIN_MAIL);
			
				if ($mail->sendMailHtml())
				{
					$this->mvc->Session->setFlash('Demande refusé', 'warning');
					$member->delete($this->mvc->Request->params['id']);
				}
				else
				{
					$this->mvc->Session->setFlash('Demande refusé', 'warning');
					$this->mvc->Session->setFlash('Impossible d\'envoyer l\'e-mail');
					$member->delete($this->mvc->Request->params['id']);
				}


		break;
		//
		// On accepte 
		//
		case '1':
		
			if ($memberModel->findFirst(array('conditions' => array('loginmember' => $info->newlogin))))
			{
				$this->mvc->Session->setFlash('Impossible de traiter la requête, le pseudo est déjà pris', 'warning');
			}
			else
			{

				$data = new stdClass();
				$data->idmember = $info->idmember;
				$data->loginmember = $info->newlogin;

					if ($memberModel->save($data))
					{
					$p_text = '<p>Bonjour '.$infoMember->loginmember.' ou devrais-je dire ' . $data->loginmember. '<br>
					Votre demande de changement de pseudo est accept&eacute;.<br><br>

					Bien &agrave; vous,<br>
					' . $this->mvc->Session->user('login').'<br><br>
					<hr>
					<a href="'.Router::url().'">'.Router::url().'</a>
					</p>';

						$mail = new Mail('Modification de votre pseudo', $p_text, $infoMember->mailmember, ADMIN_MAIL);
						
							if ($mail->sendMailHtml())
							{
							$this->mvc->Session->setFlash('Demande accepter');
							$member->delete($this->mvc->Request->params['id']);
							}
							else
							{
							$this->mvc->Session->setFlash('Demande accepter');
							$this->mvc->Session->setFlash('Impossible d\'envoyer l\'e-mail');
							$member->delete($this->mvc->Request->params['id']);
							}
							
					}
			}
		break;	
		}

	}



$this->mvc->Template->listMember = $member->find(array(
    'order' => 'time DESC',
    'join' => array(__SQL . '_Member AS Member' => 'MemberChangeLogin.idmember = Member.idmember')
    )
    );
$this->mvc->Template->show('member/approb_change_login');

}


/***************************************
*   Affichage de la liste des membres
***************************************/
public function getlist()
{
/***************************************
*   Verifie les droits de l'utilisateur
***************************************/
if (!$this->mvc->Acl->isAllowed()) { $this->mvc->Session->setFlash('Vous n\'avez pas accès à cette page', 'error'); Router::redirect('auth'); }

/***************************************
*   Création du fil arian et du titre
***************************************/
$this->mvc->Page->setBreadcrumb('member', 'Membre')
                ->setBreadcrumb('member/manager', 'Manager')
                ->setPageTitle('Membres manager');

/***************************************
*   Appel du menu
***************************************/
$this->getMenu();

$member = loadModel('Member');


$page = (int) (isSet($_GET['page'])) ? $_GET['page'] : 1;

$to = (30 *($page-1));
$query = array();
$query = array('limit' => $to.',30 ');
$nbMember = $member->count();


$this->mvc->Template->nbMember = $nbMember;
$this->mvc->Template->nbPage = ceil($nbMember / 30);
$this->mvc->Template->listMember = $member->find($query);


/*
'SELECT COUNT( * ) , ip FROM  `'.__SQL.'_Member`
GROUP BY ip HAVING COUNT( * ) > 1'
*/
$query = array(
    'fields' => 'COUNT( * ) AS db, idmember, loginmember, mailmember, firstactivitymember, lastactivitymember, ip, validemember',
    'group' => 'ip HAVING COUNT( * ) > 1'
    );
$this->mvc->Template->doublon = $member->find($query);

$this->mvc->Template->show('member/getlist');
}


/***************************************
*   Recherche des multi-compe
***************************************/
public function getmulticompte()
{

/***************************************
*   Verifie les droits de l'utilisateur
***************************************/
if (!$this->mvc->Acl->isAllowed()) { $this->mvc->Session->setFlash('Vous n\'avez pas accès à cette page', 'error'); Router::redirect('auth'); }

/***************************************
*   Création du fil arian et du titre
***************************************/
$this->mvc->Page->setBreadcrumb('member', 'Membre')
                ->setBreadcrumb('member/manager', 'Manager')
                ->setPageTitle('Membres manager');

/***************************************
*   Appel du menu
***************************************/
$this->getMenu();

$member = loadModel('Member');


$page = (int) (isSet($_GET['page'])) ? $_GET['page'] : 1;

$to = (30 *($page-1));
$query = array();


    if (isSet($this->mvc->Request->params['id']))
    {
    $find = array('conditions' => 'idmember = ' . (int) $this->mvc->Request->params['id']);
		
    	$search = $member->findFirst($find);
        if ( $search )
        {


        $query = array(
            'conditions' => array('ip' => $search->ip),
            'limit' => $to.',30 '
            );

        $this->mvc->Template->doublon = $member->find($query);
        $this->mvc->Template->show('member/getmulticompte-byid');
        } else {$this->mvc->Session->setFlash('Aucun multi-compte trouvé');}
    }
    else
    {
    /*
    'SELECT COUNT( * ) , ip FROM  `'.__SQL.'_Member`
    GROUP BY ip HAVING COUNT( * ) > 1'
    */
    $query = array(
        'fields' => 'COUNT( * ) AS db, idmember, loginmember, mailmember, firstactivitymember, lastactivitymember, ip, validemember',
        'group' => 'ip HAVING COUNT( * ) > 1',
        'limit' => $to.',30 '
        );
	$search = $member->find($query);
	$this->mvc->Template->nbPage = ceil($search[0]->db / 30);
    $this->mvc->Template->doublon = $search;
    $this->mvc->Template->show('member/getmulticompte');
    }

}


/***************************************
*   Modification des regles général d'utilisation
***************************************/
public function cgu_manager()
{
    if ($this->mvc->Acl->isAllowed())
    {
    $this->mvc->Page->setBreadcrumb('member', 'Membre')
                    ->setBreadcrumb('member/manager', 'Manager')
                    ->setPageTitle('Changement de la CGU');

    $cgu = new Cache('cgu');
    if (!$meCache = $cgu->getCache())
    {
    $meCache = new stdClass();
    $meCache->title = 'Conditions général d\'utilisation du site';
    $meCache->text = '<p>Les modérateurs de ce site s\'efforceront de supprimer ou éditer tous les messages à caractère répréhensible aussi rapidement que possible. Toutefois, il leur est impossible de passer en revue tous les messages. Vous admettez donc que tous les messages postés sur ce site expriment la vue et opinion de leurs auteurs respectifs, et non celles des modérateurs ou du webmestre (excepté des messages postés par eux-mêmes) et par conséquent qu\'ils ne peuvent pas être tenus pour responsables des discussions. </p>

<p>L\'adresse e-mail est uniquement utilisée afin de confirmer les détails de votre inscription ainsi que votre mot de passe (et aussi pour vous renvoyer votre mot de passe en cas d\'oubli). </p>

<ul>
    <li>les messages agressifs ou diffamatoires, les insultes et critiques personnelles, les grossièretés et vulgarités, et plus généralement tout message contrevenant aux lois sont interdits </li>
    <li>les messages incitant à - ou évoquant - des pratiques illégales sont interdits ;</li>
    <li>si vous diffusez des informations provenant d\'un autre site web, vérifiez auparavant si le site en question ne vous l\'interdit pas. Mentionnez l\'adresse du site en question par respect du travail de ses administrateurs !</li>
    <li>merci de poster vos messages une seule fois. Les répétitions sont désagréables et inutiles !</li>
    <li>merci de faire un effort sur la grammaire et l\'orthographe. Style SMS fortement déconseillé !</li>
    <li>aucun compte ouvert ne pourra être supprimé ! (ceci pour des raisons technique)</li>
</ul>

<p>Tout message contrevenant aux dispositions ci-dessus sera édité ou supprimé sans préavis ni justification supplémentaire dans des délais qui dépendront de la disponibilité des modérateurs. Tout abus entraînera le bannisment de votre compte, e-mail, adresse IP. <br>
Internet n\'est ni un espace anonyme, ni un espace de non-droit ! Nous nous réservons la possibilité d\'informer votre fournisseur d\'accès et/ou les autorités judiciaires de tout comportement malveillant. L\'adresse IP de chaque intervenant est enregistrée afin d\'aider à faire respecter ces conditions.</p>

<p>En vous inscrivant sur le site vous reconnaissez avoir lu dans son intégralité le présent règlement. Vous vous engagez à respecter sans réserve le présent règlement. Vous accordez aux modérateurs de ce site le droit de supprimer, déplacer ou éditer n\'importe quel sujet de discussion à tout moment.</p>

<p>Nous protégeons la vie privée de nos utilisateurs en respectant la législation en vigueur.<br>
Ainsi, vos données personnelles restent strictement confidentielles et ne seront donc pas distribuées à des tierces parties sans votre accord.</p>';
    }



    $this->mvc->Template->cgu = $meCache;
    $this->mvc->Template->show('auth/cgu_manager');
    }
    else
    {
    Router::redirect();
    }
}


/***************************************
*   Affichage des regles général d'utilisation
***************************************/
public function cgu()
{
$cgu = new Cache('cgu');

    if (!$meCache = $cgu->getCache())
    {
    $meCache = new stdClass();
    $meCache->title = 'Conditions général d\'utilisation du site';
    $meCache->text = '<p>Les modérateurs de ce site s\'efforceront de supprimer ou éditer tous les messages à caractère répréhensible aussi rapidement que possible. Toutefois, il leur est impossible de passer en revue tous les messages. Vous admettez donc que tous les messages postés sur ce site expriment la vue et opinion de leurs auteurs respectifs, et non celles des modérateurs ou du webmestre (excepté des messages postés par eux-mêmes) et par conséquent qu\'ils ne peuvent pas être tenus pour responsables des discussions. </p>

<p>L\'adresse e-mail est uniquement utilisée afin de confirmer les détails de votre inscription ainsi que votre mot de passe (et aussi pour vous renvoyer votre mot de passe en cas d\'oubli). </p>

<ul>
    <li>les messages agressifs ou diffamatoires, les insultes et critiques personnelles, les grossièretés et vulgarités, et plus généralement tout message contrevenant aux lois sont interdits </li>
    <li>les messages incitant à - ou évoquant - des pratiques illégales sont interdits ;</li>
    <li>si vous diffusez des informations provenant d\'un autre site web, vérifiez auparavant si le site en question ne vous l\'interdit pas. Mentionnez l\'adresse du site en question par respect du travail de ses administrateurs !</li>
    <li>merci de poster vos messages une seule fois. Les répétitions sont désagréables et inutiles !</li>
    <li>merci de faire un effort sur la grammaire et l\'orthographe. Style SMS fortement déconseillé !</li>
    <li>aucun compte ouvert ne pourra être supprimé ! (ceci pour des raisons technique)</li>
</ul>

<p>Tout message contrevenant aux dispositions ci-dessus sera édité ou supprimé sans préavis ni justification supplémentaire dans des délais qui dépendront de la disponibilité des modérateurs. Tout abus entraînera le bannisment de votre compte, e-mail, adresse IP. <br>
Internet n\'est ni un espace anonyme, ni un espace de non-droit ! Nous nous réservons la possibilité d\'informer votre fournisseur d\'accès et/ou les autorités judiciaires de tout comportement malveillant. L\'adresse IP de chaque intervenant est enregistrée afin d\'aider à faire respecter ces conditions.</p>

<p>En vous inscrivant sur le site vous reconnaissez avoir lu dans son intégralité le présent règlement. Vous vous engagez à respecter sans réserve le présent règlement. Vous accordez aux modérateurs de ce site le droit de supprimer, déplacer ou éditer n\'importe quel sujet de discussion à tout moment.</p>

<p>Nous protégeons la vie privée de nos utilisateurs en respectant la législation en vigueur.<br>
Ainsi, vos données personnelles restent strictement confidentielles et ne seront donc pas distribuées à des tierces parties sans votre accord.</p>';
    }

$this->mvc->Page->setPageTitle(clean($meCache->title, 'str'));
$this->mvc->Template->cgu = clean($meCache->text, 'html');
$this->mvc->Template->show('auth/cgu');
}


/***************************************
*   Edition du compte d'un membre, par le staff ou par lui meme
***************************************/
public function editother()
{
    /***************************************
    *   Verifie les droits de l'utilisateur
    ***************************************/
    if ( !$this->mvc->Acl->isAllowed() )
    {
    $this->mvc->Session->setFlash('Vous n\'avez pas accès à cette page', 'error'); Router::redirect('auth');
    }
    elseif( !isSet($this->mvc->Request->params['id']) )
    {
    $this->mvc->Session->setFlash('Le membre n\'existe pas', 'error');
    return $this->getlist();
    }

    /***************************************
    *   Recherche le membre dans la DB
    ***************************************/
    $member = loadModel('Member');
    $query = array(
        'conditions' => array('idmember' => (int) $this->mvc->Request->params['id'])
        );

    /***************************************
    *   Si il existe pas on retourne le message
    ***************************************/
    if (!$currentMember = $member->findFirst($query))
    {
    $this->mvc->Session->setFlash('Le membre n\'existe pas', 'error');
    return $this->getlist();
    }

    /***************************************
    *   Création du fil arian et du titre
    ***************************************/
    $this->mvc->Page->setBreadcrumb('member', 'Membre')
                ->setBreadcrumb('member/manager', 'Manager')
                ->setPageTitle('Edition d\'un membre');

    /***************************************
    *   Appel du menu
    ***************************************/
    $this->getMenu();

    // initialisation
    $errors = array();
    $data = new stdClass();
    $data->idmember = $currentMember->idmember;

    /***************************************
    *   Toute les autres informations
    ***************************************/
    if (isSet($this->mvc->Request->data->login))
    {
        /***************************************
        *   Demande un changement de mot de passe ?
        ***************************************/
        if (isSet($this->mvc->Request->data->onpassword))
        {
            if (!empty($this->mvc->Request->data->onpassword))
            {
                if ($this->mvc->Request->data->password != $this->mvc->Request->data->onpassword)
                {
                $errors['onpassword'] = 'Le nouveau mot de passe et la confirmation  sont différent';
                }
                else {$member->changePassword($data->idmember, $this->mvc->Request->data->onpassword);}
            }
        }

        if (!isSet($this->mvc->Request->data->login))
        {
        $errors['login'] = 'Vous devez préciser un pseudo';
        }
        else
        {
            if (empty($this->mvc->Request->data->login))
            {
            $errors['login'] = 'Vous devez préciser un pseudo';
            }
            else
            {
            $data->loginmember = $this->mvc->Request->data->login;
            }
        }

        if (!isSet($this->mvc->Request->data->valide))
        {
        $errors['valide'] = 'Veullez indiquer l\'approbation du membre';
        }
        else
        {
            if (empty($this->mvc->Request->data->valide))
            {
            $errors['valide'] = 'Veullez indiquer l\'approbation du membre';
            }
            elseif($this->mvc->Request->data->valide != 'on' AND $this->mvc->Request->data->valide != 'off')
            {
            $errors['valide'] = 'Le champ est mal formaté';
            }
            else
            {
            $data->validemember = $this->mvc->Request->data->valide;
            }
        }


        if (!isSet($this->mvc->Request->data->mail))
        {
        $errors['mail'] = 'Veullez indiquer l\'adresse e-mail';
        }
        else
        {
            if (empty($this->mvc->Request->data->mail))
            {
            $errors['mail'] = 'Veullez indiquer l\'adresse e-mail';
            }
            elseif(!filter_var ( $this->mvc->Request->data->mail, FILTER_VALIDATE_EMAIL ))
            {
            $errors['mail'] = 'Veullez indiquer une adresse e-mail correcte';
            }
            else
            {
                $mail = library ( 'mailjetable' );
                $explode = explode ( '@', strtolower ( $this->mvc->Request->data->mail ) );

                if (( bool ) array_search ( $explode [1], $mail )) {
                    $errors['mail'] = 'Veullez indiquer une adresse e-mail correcte (pas d\'e-mail jetable)';
                }
                else
                {
                $data->mailmember = $this->mvc->Request->data->mail;
                }

            }
        }


        if (count($errors) == 0)
        {
        $member->save($data);
        $this->mvc->Session->setFlash('Modification effectué');
        }
        else {$this->mvc->Form->setErrors($errors);}
    }


$form = $this->mvc->Form->input('login', 'Pseudo', array('value' => $currentMember->loginmember)).
        $this->mvc->Form->input('password', 'Mot de passe', array('type' => 'password')).
        $this->mvc->Form->input('onpassword', 'Confirmer le mot de passe',
            array(
                'type' => 'password',
                'help' => 'Remplir seulement en cas de modification'
                )
            ).
        $this->mvc->Form->input('mail', 'Mail',
            array('value' => $currentMember->mailmember)
            ).

        $this->mvc->Form->input('valide', 'Approbation',
            array(
                'value' => $currentMember->validemember,
                'type' => 'radio',
                'option' => array('off' => 'Non', 'on' => 'Oui')
                )
            ).
        $this->mvc->Form->input('submit', 'Modifier', array('type' => 'submit', 'class' => 'btn success'));



$this->mvc->Template->form = $form;
$this->mvc->Template->show('member/editother');
}


/***************************************
*   Envois d'un mail a un membre
***************************************/
public function mailto()
{
    /***************************************
    *   Verifie les droits de l'utilisateur
    ***************************************/
    if ( !$this->mvc->Acl->isAllowed() )
    {
    $this->mvc->Session->setFlash('Vous n\'avez pas accès à cette page', 'error'); Router::redirect('auth');
    }
    elseif( !isSet($this->mvc->Request->params['id']) )
    {
    $this->mvc->Session->setFlash('Le membre n\'existe pas', 'error');
    return $this->getlist();
    }

    /***************************************
    *   Création du fil arian et du titre
    ***************************************/
    $this->mvc->Page->setBreadcrumb('member', 'Membre')
                ->setPageTitle('Contacter');

    /***************************************
    *   Appel du menu
    ***************************************/
    $this->getMenu();

    /***************************************
    *   Recherche le membre dans la DB
    ***************************************/
    $member = loadModel('Member');
    $query = array(
        'conditions' => array('idmember' => (int) $this->mvc->Request->params['id'])
        );

    /***************************************
    *   Si il existe pas on retourne le message
    ***************************************/
    if (!$currentMember = $member->findFirst($query))
    {
    $this->mvc->Session->setFlash('Le membre n\'existe pas', 'error');
    return $this->getlist();
    }

    if (isSet($this->mvc->Request->data))
    {
    $p_objet = (isSet($this->mvc->Request->data->object)) ? $this->mvc->Request->data->object : '';
    $p_text = (isSet($this->mvc->Request->data->message)) ? $this->mvc->Request->data->message : '';
    $p_destinataire = (isSet($this->mvc->Request->data->to)) ? $this->mvc->Request->data->to : '';
    $p_emetteur = (isSet($this->mvc->Request->data->by)) ? $this->mvc->Request->data->by : '';

    if (!empty($p_objet) && !empty($p_text) && !empty($p_destinataire) && !empty($p_emetteur))
    {
    $errors = array();
        if(filter_var ( $p_destinataire, FILTER_VALIDATE_EMAIL ))
        {
        $errors['to'] = 'E-mail invalide';
        }
        if(filter_var ( $p_emetteur, FILTER_VALIDATE_EMAIL ))
        {
        $errors['by'] = 'E-mail invalide';
        }

    if (count($errors) == 0)
    {
    $mail = new Mail($p_objet,$p_text,$p_destinataire,$p_emetteur);
        if($mail->sendMailHtml())
        {
        $this->mvc->Session->setFlash('E-mail envoyé');
        Router::redirect('member');
        }
    }
    else {
        $this->mvc->Form->setErrors($errors);
        $this->mvc->Session->setFlash('Veuillez compléter tout les champs');
        }

    } else {
        $this->mvc->Session->setFlash('Veuillez compléter tout les champs');
        }

    }


    $editor = array();
    $editor['type'] = 'textarea';
    $editor['editor']['params']['model'] ='html';
    $this->mvc->Template->form =
        $this->mvc->Form->input('by', 'De:', array('value' => ADMIN_MAIL)).
        $this->mvc->Form->input('to', 'A:', array('value' => $currentMember->mailmember)).
        $this->mvc->Form->input('object', 'Objet:').
        $this->mvc->Form->input('message', 'Message:', $editor).
        $this->mvc->Form->input('submit', 'Envoyer', array(
            'type' => 'submit',
            'class' => 'btn success'
            ));

    $this->mvc->Template->show('member/mailto');
}


public function mailsender()
{
	
    /***************************************
    *   Verifie les droits de l'utilisateur
    ***************************************/
    if ( !$this->mvc->Acl->isAllowed() )
    {
    $this->mvc->Session->setFlash('Vous n\'avez pas accès à cette page', 'error'); Router::redirect('auth');die;
    }
    
     /***************************************
    *   Création du fil arian et du titre
    ***************************************/
    $this->mvc->Page->setBreadcrumb('member', 'Membre')
                ->setPageTitle('Newsletter');
		
		$whait = 2;
		$this->mvc->Template->whait = $whait;
		
		$nbMailParPage = 30;
		$this->mvc->Template->nbMailParPage = $nbMailParPage;
		
		
		$mMember = loadModel('Member');
		
		$nbMail = $mMember->count();
		$this->mvc->Template->nbMail = $nbMail;
		
		
		if ( $this->mvc->Request->title && $this->mvc->Request->content )
		{
			$title = clean($this->mvc->Request->title, 'str'); 	
			$content = clean($this->mvc->Request->content, 'bbcode');

			$this->mvc->Session->write('mailsender', array(
				'title' => $title,
				'content' => $content,
				'currentPage' => 0,
				'total' => $nbMail,
				'time' => time()
				));
				

			Router::redirect(Router::url('member/mailsender').'?send=1');	
		}
		
		
		if (isSet($_GET['send']))
		{
			$params = $this->mvc->Session->read('mailsender');
			$startIt = ($nbMailParPage * $params['currentPage']);
			
			$txt = (time() - $params['time']). ' secondes <br>' .$startIt . ' / ' . $params['total'] . '<ul>';
			
			
			$queryFind = array(
				'fields' => 'mailmember',
				'limit' => $startIt. ', '.$nbMailParPage
				);
			$respon = $mMember->find($queryFind);

			foreach($respon AS $k => $v):
				if (isSet($v->mailmember))
				{
					
					
					$mail_send = new Mail('['.$this->mvc->Page->getSiteTitle().'] '. $params['title'] ,$params['content'], $v->mailmember, ADMIN_MAIL);
			
					if ($mail_send->sendMailHtml())
					{
						$txt .= '<li style="color:green;">' . $v->mailmember . '</li>';
					}
					else
					{
						$txt .= '<li style="color:red;">' . $v->mailmember . '</li>';
					}
					
				}
				else
				{
					$this->mvc->Session->setFlash('Job succes in ' .(time() - $params['time']). ' secondes');
					Router::redirect('member/mailsender');
				}	
			endforeach;
			
			$txt .= '</ul>';
			
			$params['currentPage']++;
			$this->mvc->Session->write('mailsender', $params);
			
			header('Refresh: '.$whait.';url='.Router::url('member/mailsender').'?send='.time());
			
			
			
			echo $txt;
			/*
			$params['currentPage'] = $params['currentPage']+1;
			$this->mvc->Session->write('lock', $params);*/
			die;
		}
		
	
	$this->mvc->Template->show('member/mailsender');
}

}
?>
