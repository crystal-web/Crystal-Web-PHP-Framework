<?php
/**
* @title Espace membre
* @author Christophe BUFFET <developpeur@crystal-web.org>
* @license Creative Commons By
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description
* @acl
    member.editother
    member.approb_change_login
    member.cgu_manager

Membre
Groupe
Rank
Réglement
*/
Class memberController extends Controller {

	// Profil base
	public function index()
	{
		$request = Request::getInstance();
		$page = Page::getInstance();
		$session = Session::getInstance();
		$template = Template::getInstance();
		$form = Form::getInstance();
		
	    $member = new MemberModel();
	    $actu = new MemberActuModel();

	
	    /***************************************
	    *   Recherche le profil
	    ***************************************/
	    if (isSet($request->params['slug']))
	    {
		    $slug = clean($request->params['slug'], 'slug');
		
		    $query = array(
	            'conditions'    => array(
	                'loginmember' => $slug
	                ),
	            'join'          => array(
	                __SQL . '_MemberInfo AS Info' => 'Info.thismember = Member.idmember',
	                ),
	            );
	    } elseif ($session->isLogged() && !isSet($request->params['slug'])) {
	    	return Router::redirect('member/index/slug:' . $session->user('login'));
	    } else { return Router::redirect('auth'); }
		$respon = $member->findFirst($query);
	
	    if ( $respon )
	    {
	        $page->setBreadcrumb('member', 'Membre')
	                    ->setPageTitle('Profil de ' . clean($respon->loginmember, 'slug'));

			if ($session->isLogged())
			{
		    /***************************************
			*   Si une actu est posté
			***************************************/				
				if (isSet($request->data->actu))
				{
					$data = new stdClass();
					$data->auteur = $session->user('id');
					$data->time = time();
					$data->actu = $request->data->actu;
		
		            if ($actu->save($data)) {
		            	$session->setFlash('Actualité enregistré');
					}
					else {
						$session->setFlash('Oups...', 'warning');
					}
					
					return Router::redirect('member/index/slug:' . $session->user('login'));
		        }
				

				if ($session->user('id') == $respon->idmember)
				{
					$template->infouser = $respon;
					$template->meform = $form->input('sex', 'Sexe:', array('type' => 'select', 'option' => array('z' => 'Inconnu', 'x' => 'Masculin', 'y' => 'Féminin'), 'value' => $respon->sex)).
					    $form->input('job', 'Mon travail:', array('placeholder' => 'Plombier, y a pas de saut métier', 'value' => clean($respon->job, 'str'))).
					    $form->input('leisure', 'Mes passions:', array('placeholder' => 'La guitare, le saut à la perche et le vélo', 'value' => clean($respon->leisure, 'str'))).
					    $form->input('website', 'Mon site internet:', array('placeholder' => 'http://www.monsiteetblog.com', 'value' => clean($respon->website, 'str'))).
					    $form->input('location', 'Ma localisation:', array('placeholder' => 'France, Pyrénées', 'value' => clean($respon->location, 'str'))).
					    $form->input('birthday', 'Ma date de naissance:', array(
				                'value' => date('n-j-Y', $respon->birthday),
				    			'type'=> 'date'));
				}
				else
				{
				    $query = array(
			            'conditions'    => array(
			                'idmember' => $session->user('id')
			                ),
			            'join'          => array(
			                __SQL . '_MemberInfo AS Info' => 'Info.thismember = Member.idmember',
			                ),
			            );

					$template->infouser = $member->findFirst($query);
					$template->meform = $form->input('sex', 'Sexe:', array('type' => 'select', 'option' => array('z' => 'Inconnu', 'x' => 'Masculin', 'y' => 'Féminin'), 'value' => $this->mvc->Template->infouser->sex)).
					    $form->input('job', 'Mon travail:', array('placeholder' => 'Plombier, y a pas de saut métier', 'value' => clean($this->mvc->Template->infouser->job, 'str'))).
					    $form->input('leisure', 'Mes passions:', array('placeholder' => 'La guitare, le saut à la perche et le vélo', 'value' => clean($this->mvc->Template->infouser->leisure, 'str'))).
					    $form->input('website', 'Mon site internet:', array('placeholder' => 'http://www.monsiteetblog.com', 'value' => clean($this->mvc->Template->infouser->website, 'str'))).
					    $form->input('location', 'Ma localisation:', array('placeholder' => 'France, Pyrénées', 'value' => clean($this->mvc->Template->infouser->location, 'str'))).
					    $form->input('birthday', 'Ma date de naissance:', array(
				                'value' => date('n-j-Y', $template->infouser->birthday),
				    			'type'=> 'date'));
				}


   
			    $errors = array();
			    $dataToSave = new stdClass();
				if ($request->data)
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
						    				if (isSet($template->infouser->avatar))
							    			{
							    				if (strlen($template->infouser->avatar) > 1 AND file_exists('./media/avatar/'.$template->infouser->avatar))
							    				{
							    					@unlink('./media/avatar/'.$template->infouser->avatar);
							    					Log::setLog('Suppression de l\'avatar', 'memberController');
							    				}
							    				else
							    				{
							    					Log::setLog('Suppréssion de l\'avatar inutile, valeur = ' . $template->infouser->avatar, 'memberController');
							    				}
							    			}
			
						    				$im->width(256);
						    				$im->height(256);
						    				$im->save(clean($session->user('login'), 'slug').'.'.$im->getExt());
						    				
						    				$dataToSave->avatar = clean($session->user('login'), 'slug').'.'.$im->getExt();
						    				//$dataToSave->avatar = (isset($dataToSave->avatar)) ? $dataToSave->avatar : '';
						    			} else { $errors['avatar'] = 'Erreur lors du transfère, l\'écriture est refusé'; }
						    		} else { $errors['avatar'] = 'Extention incorrect'; }
						    	} else { $errors['avatar'] = 'Extention incorrect'; }
						    } else { $errors['avatar'] = 'Extention incorrect'; }
						}
					}
					
					
			        if (isSet($request->data->website))
			        {
			            if(!empty($request->data->website))
			            {
			                if (!isURL($request->data->website))
			                {
			              	  $errors['website'] = 'L\'adresse de votre site internet est incorrect (http:// est probablement manquant) ';
			                }
			                else
							{
			              		$dataToSave->website = $request->data->website;
							}
			            }
			        }
			        
					if (isSet($request->data->day) AND isSet($request->data->month) AND isSet($request->data->year))
					{
						if (!checkdate($request->data->month, $request->data->day, $request->data->year))
						{
							$errors['birthday'] = 'Date incorrect ';
						}
						else
						{
							$dataToSave->birthday = mktime(1,1,1,$request->data->month, $request->data->day, $request->data->year);
						}
					}
					
			
			        
			
			        if (count($errors)==0)
			        {
			
			        	$dataToSave->bio = $request->data->bio;
			        	$dataToSave->sign = $request->data->sign;
			        	$dataToSave->leisure = $request->data->leisure;
			        	$dataToSave->location = $request->data->location;
			        	$dataToSave->job = $request->data->job;
			        	$dataToSave->sex = $request->data->sex;
			        	
			            $dataToSave->thismember = $session->user('id');
						
						//cwDebug($dataToSave);die;
						$saveMember = new MemberInfoModel();
			            if ($saveMember->changeInfo($dataToSave))
			            {
			            	// var_dump($mMemberInfo->sql);
			            	//echo '<!-- ' . print($mMemberInfo->sql). ' -->';
			            //	debug($dataToSave);
			            	$session->setFlash('Votre profil à été mise à jour');
							return Router::redirect('member/index/slug:' . $session->user('login'));
			            }
			        }//*/
				}

   			 $form->setErrors($errors);
				
		    }
			
	        $query = array(
	            'conditions'    => array(
	                'auteur' => $respon->idmember,
	                ),
	            'join'          => array(
	                __SQL . '_Member AS InfoAuteur' => 'InfoAuteur.idmember = MemberActu.auteur',
	                ),
	            'limit'     => '0, 25',
	            'order'     => 'time DESC'
	            );
		    $template->actu = $actu->find($query);
			
			
	        $queryMulti = array(
	            'conditions' => array('ip' => $respon->ip)
	            );
	        $template->multi = $member->find($queryMulti);
	
		    $template->info = $respon;
		    $template->show('member/profile');
	    } else{
			$page->setBreadcrumb('member', 'Membre')
	    					->setPageTitle('Profil introuvable', 'slug');
		    $template->show('member/404');
		    return;
	    }
    }


	public function search()
	{
		$request = Request::getInstance();
		$session = Session::getInstance();
		
		if (!isSet($request->data->login))
		{
			if ($session->isLogged() && !isSet($request->params['slug'])) {
			return Router::redirect('member/index/slug:' . $session->user('login'));
			} else { return Router::redirect('auth'); }
		}
		else
		{
			return Router::redirect('member/index/slug:' . clean( $request->data->login , 'slug'));
		}
	}


	/***************************************
	*   Modification du mot de passe
	***************************************/
	public function change_password()
	{
		$session = Session::getInstance();
		$template = Template::getInstance();
		$page = Page::getInstance();
		$form = Form::getInstance();
		
	    if (!$session->isLogged())
	    {
		    $session->setFlash('Vous devez être connecté', 'warning');
		    Router::redirect('auth');
	    }
	
	    /***************************************
	    *   Création du fil arian et du titre
	    ***************************************/
	    $page->setBreadcrumb('member', 'Membre')
	                    ->setPageTitle('Changer mon mot de passe');
	
	    /***************************************
	    *   Appel du menu
	    ***************************************/
	    $this->getMenu();
	
	
	    // Var errors
	    $errors = array();
	
	    if (isSet($request->data->password))
	    {
		    $data = new stdClass();
		    $data->loginmember  =  $session->user('login');
		    $data->passmember   = isSet($request->data->password) ? $request->data->password : '';
		    $data->newpassword  = isSet($request->data->newpassword) ? $request->data->newpassword : '';
		    $data->confpassword = isSet($request->data->confpassword) ? $request->data->confpassword : '';
	
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
	    $member = new MemberModel();
	
	        if (!$member->checkLogin($data))
	        {
				$errors['password'] = 'Votre mot de passe est incorrect';
	        }
	        else
	        {
	            if ($member->changePassword($this->mvc->Session->user('id'), $data->confpassword))
	            {
	    	        $session->setFlash('Votre mot de passe a bien été modifié');
	            }
	            else
	            {
	    	        $session->setFlash("Une erreur c'est produite lors de la modification du mot de passe.<br>" . 
	    	        					"Veuillez contacter l'administrateur du site.", 'error');
	            }
	        }
	    }
	
	    $form = $form->input('password', 'Ancien mot de passe: ', array('type' => 'password')).
	            $form->input('newpassword', 'Nouveau mot de passe: ', array('type' => 'password')).
	            $form->input('confpassword', 'Confirmation mot de passe: ',  array('type' => 'password')).
	            $form->input('submit', 'Modifier mon mot de passe', array('type' => 'submit', 'class' => 'btn primary'));
	    $template->form = $form;
	    $template->show('member/change_password');
	}


	/***************************************
	*   Reaquete de changement de pseudo
	***************************************/
	public function change_login()
	{
	$session = Session::getInstance();
	$page = Page::getInstance();
	$request = Request::getInstance();
	$form = Form::getInstance();
	
	/***************************************
	*   Verifie les droits de l'utilisateur
	***************************************/
	if (!$session->isLogged())
	{
		$session->setFlash('Vous devez être connecté', 'error');
		Router::redirect('auth');
	}
	
	
	/***************************************
	*   Appel du menu
	***************************************/
	$this->getMenu();
	
	/***************************************
	*   Création du fil arian et du titre
	***************************************/
	$page->setBreadcrumb('member', 'Membre')
	                ->setPageTitle('Demander un changement de pseudo');
	
	$member = new MemberChangeLoginModel();
	
	
	$raison = $pseudo = $id = NULL;
	    /***************************************
	    *   Si on trouve une requete précédente
	    *   On va la modifier
	    ***************************************/
		$info = $member->findFirst(array('conditions' => 'idmember = '.$session->user('id')));
	    if ( $info )
	    {
		    $raison = $info->raison;
		    $pseudo = clean($info->newlogin, 'slug');
	    }
	
	
	    /***************************************
	    *   Essaye de validé les champs
	    ***************************************/
	    if (isSet($request->data->pseudo))
	    {
		$this->mvc->Request->data->pseudo = clean($request->data->pseudo, 'slug');
			$searchMember = new MemberModel();
			$resu = $searchMember->findFirst(array('conditions' => array('loginmember' => $request->data->pseudo)));
			
			if ( $resu )
			{
				$member->errors['pseudo'] = 'Le pseudo est déjà pris';
			}
			elseif ($member->validates($this->mvc->Request->data))
	        {
		        $data = new stdClass();
		            if (!empty($info->id)) { $data->id = $info->id; }
		
		        $data->newlogin = $request->data->pseudo;
		        $data->raison = $request->data->raison;
		        $data->idmember = $session->user('id');
		        $data->time = time();
	
	            if ($member->save($data))
	            {
					$session->setFlash('Votre demande à bien été enregistré.');
	            }
	        }
			
	
	        $form->setErrors($member->errors);
	
	    }
		
	    $template->form = $form->input('pseudo', 'Nouveau pseudo désiré:', array('value' => $pseudo)).
	                                $form->input('raison', 'Raison du changement de pseudo:', array(
	                                    'value' => $raison,
	                                    'type' => 'textarea',
	                                    'style' => 'margin-left: 0px;
	                                        margin-right: 0px;
	                                        width: 534px;
	                                        margin-top: 0px;
	                                        margin-bottom: 0px;
	                                        height: 165px;')).
	    $form->input('submit', 'Demander un changement de pseudo', array('type' => 'submit', 'class' => 'btn success'));
	    $template->show('member/change_login');
	}
}