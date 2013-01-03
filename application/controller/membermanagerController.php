<?php

Class membermanagerController extends Controller {
/*	
	ACL :
		membermanager.adduser
		membermanager.edituser
		membermanager.changegroup
	Router:
		Router::connect('membermanager/edituser-:id', 'membermanager/edituser/id:([0-9]+)');


//*/


	/**
	 * Methode Google
	 * Recherche se que tu souhaite, je te l'affiche
	 */
	public function index()
	{
		$acl = AccessControlList::getInstance();
		$session = Session::getInstance();
		$page = Page::getInstance();
		$template = Template::getInstance();
		
		if (!$acl->isAllowed()) {
			$session->setFlash(i18n::get('Permission denied'), 'error');
			Router::redirect('auth');
		}
		
		$page->setPageTitle('MemberManager');
		$mMember = new MemberModel();
		// ($mMember->countMultiAccount());
		$template->lastMember = $mMember->lastMember();
		
		$groupList = $acl->getGroupList();
		unset($groupList[$session->user('group')]);
		$template->groupList = $groupList;
		$template->show('membermanager/index');
	}
	
	
	public function search()
	{
		$acl = AccessControlList::getInstance();
		$page = Page::getInstance();
		$session = Session::getInstance();
		$request = Request::getInstance();
		$template = Template::getInstance();
		
		if (!$acl->isAllowed()) {
			$session->setFlash(i18n::get('Permission denied'), 'error');
			Router::redirect('auth');
		}
			
		$page->setPageTitle(i18n::get('Search'));
		$page->setBreadcrumb('membermanager', i18n::get('Member Manager'));
		
		if (isset($request->data->login))
		{
			// On commence par charger le model
			$mMember = new MemberModel();
			// On recherche les possibilités a travers la requete
			$prepare = array(
				'conditions' => 'loginmember LIKE \'%'.$request->data->login.'%\'',
				'limit' => 50
				);
			// Plusieurs solution
			// 1. retourne false, la requete n'a rien donnée
			// 2. retourne un champ, la valeur est unique
			// 3. retourne plusieurs champs
			
			$foundList = $mMember->find($prepare);
			
			if (!$foundList)
			{
				$session->setFlash(i18n::get('Not found'));
				return $this->index();
			}
			
			$template->foundList = $foundList;
			$groupList = $acl->getGroupList();
			unset($groupList[$session->user('group')]);
			$template->groupList = $groupList;
			$template->show('membermanager/search');
		}
	}
	
	public function multiaccounts()
	{
		$acl = AccessControlList::getInstance();
		$page = Page::getInstance();
		$session = Session::getInstance();
		$request = Request::getInstance();
		$template = Template::getInstance();
		
		/***************************************
		*   Verifie les droits de l'utilisateur
		***************************************/
		if (!$acl->isAllowed()) {
			$session->setFlash(i18n::get('Permission denied'), 'error');
			Router::redirect('auth');
		}
		
		
			/***************************************
			 *   Création du fil arian et du titre
			***************************************/
		$page->setPageTitle(i18n::get('Multiaccounts'));
		$page->setBreadcrumb('membermanager', i18n::get('Member Manager'));
							
		$member = new MemberModel();
		
		
		$page = $request->page;
		
		$to = ($this->listNbParPage *($page-1));
		$query = array();
		
		
		    if (isSet($request->params['id']))
		    {
		    $find = array('conditions' => 'idmember = ' . (int) $request->params['id']);
				
		    	$search = $member->findFirst($find);

		        if ( $search )
		        {
		
		
			        $query = array(
			            'conditions' => array('ip' => $search->ip),
			            'limit' => $to.', 30'
			            );
						
					$search = $member->find($query);
					$db = (isSet($search[0]->db)) ? $search[0]->db : 0;
					$template->nbPage = ceil($db / 30);
				
			        $template->doublon = $search;
			        $template->show('membermanager/multiaccounts-byid');
					
		        } else {
		        	$session->setFlash('Aucun multi-compte trouvé');
				}
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
			        'limit' => $to.', 30'
			        );
				$search = $member->find($query);
				if (count($search) == 0)
				{
					$session->setFlash(i18n::get('No multiaccounts found'));
					
				}
				else
				{
					$db = (isSet($search[0]->db)) ? $search[0]->db : 0;
					$template->nbPage = ceil($db / 30);
				    $template->doublon = $search;
				    $template->show('membermanager/multiaccounts');
				}
		    }
	}
	
	/**
	 * @status OK
	 * @translate
	 * 		
	 */
	public function adduser() 
	{
		$acl = AccessControlList::getInstance();
		$page = Page::getInstance();
		$session = Session::getInstance();
		$request = Request::getInstance();
		$template = Template::getInstance();		
		$form = Form::getInstance();
		$config = Config::getInstance();
		
		if (!$acl->isAllowed()) {
			$session->setFlash(i18n::get('Permission denied'), 'error');
			Router::redirect('auth');
		}
		
		$page->setPageTitle(i18n::get('Add user'));
		$page->setBreadcrumb('membermanager', i18n::get('Member Manager'));
		$groupList = $acl->getGroupList();
		unset($groupList[$session->user('group')]);
		$mMember = new MemberModel();
		
		if (isSet($request->data->login))
		{
			$errors = array();
			$newMember = new stdClass();
			$newMember->firstactivitymember = time();
			$newMember->lastactivitymember = time();
			$newMember->warning = 0;
			$newMember->hasban = 'n';
			$newMember->validemember = 'on';
			$newMember->levelmember = 2;
					
			if (strlen($request->data->login) >= 6)
			{
				$rest =  ($mMember->searchMemberByLogin($request->data->login) ) ? true : false;
				if ($rest)
				{
					$errors['login'] = i18n::get('This login has already been used');
				}
				$newMember->loginmember = clean($request->data->login, 'slug');
			}
			else
			{
				$errors['login'] = i18n::get('Your login is too short', 6);
			}
			
			
			
			if (!Securite::isMail($request->data->mail))
			{
				$errors['mail'] = i18n::get('Your e-mail address is incorrect or blacklisted');
			}
			else
			{ 
				$rest =  ($mMember->searchMemberByMail($request->data->mail) ) ? 1 : 0;
				if ($rest)
				{
					$errors['mail'] = i18n::get('This e-mail address has already been used');
				}
				$newMember->mailmember = $request->data->mail;
			}
			
			
			
			
			if (empty($request->data->password))
			{
				$password = randCar(); 
				$newMember->password = md5(magicword . $password);
			}
			elseif ($request->data->password == $request->data->passwordagain)
			{
				$password = $request->data->password;
				$newMember->password = md5(magicword . $password);
			}
			else
			{
				$errors['password'] = i18n::get('Your password is different from the verification');
			}
			
			if ($acl->isAllowed('membermanager', 'changegroup'))
			{
				if (!isset($groupList[$request->data->group]))
				{
					$errors['group'] = i18n::get('You are not authorized to set this group');
				}
				else
				{
					$newMember->groupmember = $request->data->group;
				}
			}
			
			if (count($errors))
			{
				$form->setErrors($errors);
			} else {
			
				if ($mMember->save($newMember))
				{
					// Preparation du mail
					$message_mail = '<html><head></head><body>
					<h3>' . i18n::get('Hello', clean($newMember->loginmember, 'slug')) . '</h3>
					<p>
					'.$session->user('login').' ' . i18n::get('just created an account for you') . '<br>
					<br>
					'.i18n::get('Sincerely, our best regards.').'<br><br>
					<a href="' . Router::url() .'">' . Router::url() .'</a>
					</p>
					<hr>
					<p>
					'.i18n::get('Your login').': ' . $newMember->loginmember .'<br>
					'.i18n::get('Your password').': '. $password . '
					</p>
					</body></html>';
					$mail_send = new Mail(i18n::get('Welcome', clean($newMember->loginmember, 'slug')), $message_mail, $newMember->mailmember, $config->getSiteMailContact());
						
					if ($mail_send->sendMailHtml())
					{
						$session->setFlash(i18n::get('New account created'), 'success');
						Router::redirect('membermanager/adduser');
					} else {
						$mWarning = loadModel('Log');
						$mWarning->setLog('email', i18n::get('Can\'t send email'), $info_user->idmember, 5);
						$session->setFlash(i18n::get('An error has occurred'). '<br>' .i18n::get('Our team has received a warning to correct the error'), 'danger');
					}
				} else {
					$mWarning = loadModel('Log');
					$mWarning->setLog('membermanager', i18n::get('Can\'t add user'), $session->user('id'), 5);
					$session->setFlash(i18n::get('An error has occurred'). '<br>' .i18n::get('Our team has received a warning to correct the error'), 'danger');
				}
			}
		}
		
		$template->groupList = $groupList;
		$template->show("membermanager/adduser");
	}

	
	public function edituser()
	{
		$acl = AccessControlList::getInstance();
		$page = Page::getInstance();
		$session = Session::getInstance();
		$request = Request::getInstance();
		$template = Template::getInstance();		
		$form = Form::getInstance();
		
		if (!$acl->isAllowed()) {
			$session->setFlash(i18n::get('Permission denied'), 'error');
			Router::redirect('auth');
		}
		
		if (!isSet($request->params['id']))
		{
			$session->setFlash(i18n::get('not found', i18n::get('User') ));
			return Router::redirect('membermanager');
		}
		
		

		
		/***************************************
		 *   Création du fil arian et du titre
		***************************************/
		$page->setPageTitle(i18n::get('Edit user'));
		$page->setBreadcrumb('membermanager', i18n::get('Member Manager'));
	
	
	
	    /***************************************
	    *   Recherche le membre dans la DB
	    ***************************************/
		$mMember = new MemberModel();
		$mMember->primaryKey = 'idmember';
		
	    $query = array(
	    	'conditions' => 
	    		array('idmember' => (int) $request->params['id'] ),
	    	'join' => 
	    		array(__SQL . '_MemberInfo AS MemberInfo' => 'Member.idmember = MemberInfo.thismember')
	    	);
	   $profilInfo = $mMember->findFirst($query);
	
		$listGroup = false;
	    /***************************************
	    *   Si il existe pas on retourne le message
	    ***************************************/
	    if (!$profilInfo)
	    {
		    $session->setFlash(i18n::get('not found', i18n::get('User') ));
		    return $this->index();
	    }
		else
		{
			// On recherche les parents	
			$listGroup = $acl->getGroupList();
			unset($listGroup[$session->user('group')]);
			if (!isSet($listGroup[$profilInfo->groupmember]))
			{
				$session->setFlash(i18n::get('You are not allowed to modify this user'), 'warning');
				return Router::redirect('membermanager');
			}
		}
	
	    $errors = array();
	    $dataToSave = new stdClass();
		$dataToSaveInfo = new stdClass();
		
		// Defini le membre avec lequel on travail
		$dataToSave->idmember = $profilInfo->idmember;
			
			
	

		// Si l'utilisateur a le droit
		if ($acl->isAllowed('membermanager', 'changegroup'))
		{

				/*
				 * Interdiction de modifier un utilisateur de group égale
				 * un modo ne peut modifier le compte d'un modo
				 */
				if ( isSet($listGroup[$profilInfo->groupmember]) )
				{
					
					if ( isSet($request->data->groupmember) )
					{
						if (isSet($listGroup[$request->data->groupmember]))
						{
							$dataToSave->groupmember = $listGroup[$request->data->groupmember];
						}
					}
				}
		}
		$template->groupList = $listGroup;
	
	
		/**
		 * On s'occupe du login en premier
		 * Celui-ci ne doit pas etre utilisé par un autre membre
		 * Ne doit pas comporter de caractères spéciaux
		 */
		if ($request->data)
		{
			
			// Si le login est different u login courant on continue
			if (clean($request->data->loginmember, 'slug') != clean($profilInfo->loginmember, 'slug'))
			{
				// On recherche si un membre a deja le pseudo choisi
				if ($mMember->searchMemberByLogin(clean($request->data->loginmember, 'slug')))
				{
					$errors['loginmember'] = 'Ce pseudo est d&eacute;j&agrave; utilis&eacute;';
				}
				else
				{
					// On enregistre le nouveau pseudo, pour le sauvegarder
					$dataToSave->loginmember = clean($request->data->loginmember, 'slug');
					
					// Si l'avatar existe, on en modifie le nom
					if ($profilInfo->avatar != '0')
					{
						// On modifie
						if (rename('./media/avatar/' . $profilInfo->avatar, './media/avatar/' . $dataToSave->loginmember . strrchr($profilInfo->avatar, '.')))
						{
							// On sauvegarer pour la modification
							$profilInfo->avatar = $dataToSaveInfo->avatar = $dataToSave->loginmember . strrchr($profilInfo->avatar, '.');
						}
					}
				}
			}
			
			
			/**
			 * Mot de passe (ok)
			 */
			if ( isSet($request->data->passmember1) && isSet($request->data->passmember2) )
			{
				// Les deux mot de passe doivent etre identique
				if ($request->data->passmember1 != $request->data->passmember2)
				{
					$errors['passmember1'] = 'Le mot de passe et la v&eacute;rification sont diff&eacute;rents';
				}
				// Le mot de passe ne doit pas etre vide
				elseif(!empty($request->data->passmember2))
				{
					// LA longueur du mot de passe, dois etre suppérieur ou egale a 6
					if (strlen($request->data->passmember1) < 6)
					{
						$errors['passmember1'] = 'Le mot de passe est trop court';
					} else {
						// On change le mot de passe par le model
						if($mMember->changePassword($profilInfo->idmember, $request->data->passmember1))
						{
							Log::setLog('Modification du Mot de passe', 'memberController');
						} else {
							$mWarning = loadModel('Log');
							$mWarning->setLog('membermanager', i18n::get('Error: can\'t change password in membermanager/edituser'), $session->user('id'), 5);		
							Log::setLog('Echec modification du mot de passe', 'memberController');
						}
					}
				}
			}
	
			
			/**
			 * Changement de la validiter d'un membre
			 */
			if ( isSet($request->data->validemember) )
			{
				$dataToSave->validemember = ($request->data->validemember == 'on') ? 'on' : 'off';
			}
			
			if ($acl->isAllowed('membermanager', 'banned'))
			{
				if ( isSet($listGroup[$profilInfo->groupmember]) )
				{
					if ($request->data->hasban == 'n' and $profilInfo->warning >= 100)
					{
						$profilInfo->warning = 90;
						$request->data->warning = '90';
					}
					
					if (isSet($request->data->warning))
					{
						$dataToSave->hasban = ((int) $request->data->warning >= 100) ? 'y' : 'n';
						$dataToSave->warning = (int) $request->data->warning;
					}
					
					if (isSet($request->data->hasban))
					{
						Log::setLog('Hasban ' . $request->data->hasban, get_class($this));
						$dataToSave->hasban = ($request->data->hasban == 'y') ? 'y' : 'n';
					}
				}
			}
	
	
			/**
			 * Avatar (ok)
			 */
			if (count($errors) == 0)
			{
				 // Si c'est une suppréssion, aucun upload ne seratraité
				 if ($request->data->delavatar == 'yes')
				 {
				 	// Re set delavatar a no
				 	$request->data->delavatar = 'no';
					// Si l'avatar existe
					if (strlen($profilInfo->avatar) > 1)
					{
						if (file_exists('./media/avatar/'.$profilInfo->avatar))
						{
							// On supprime le fichier
							unlink('./media/avatar/'.$profilInfo->avatar);
							Log::setLog('Suppression de l\'avatar', 'memberController');
						}
					}
					// On modifie la valeur de l'avatar en la metant par defaut
					$profilInfo->avatar = $dataToSaveInfo->avatar = 0;
					
				 } else {
					// Si un avatar est uploader
					if (isSet($_FILES['avatar']))
					{
					    $file = $_FILES['avatar'];
						// Si l'avatar n'est pas vide
						if (!empty($file['name']))
						{
							// upload se charge du fichier
					    	$up = new Upload($file);
						    
						    if ($up->prepare())
						    {
						    	// on controlle l'extention
						    	if ($up->controlExtWhiteList(array('.png','.jpeg','.jpg','.gif')))
						    	{
						    		// Si le mime type, contient, image on considère que c'est bon
						    		if (preg_match('#image#', $up->getMime()))
						    		{
						    			
					    				if (isSet($profilInfo->avatar))
						    			{
						    				if (strlen($profilInfo->avatar) > 1)
						    				{
						    					unlink('./media/avatar/'.$profilInfo->avatar);
						    					Log::setLog('Suppression de l\'avatar', 'memberController');
						    				}
						    				else
						    				{
						    					Log::setLog('Suppréssion de l\'avatar inutile, valeur = ' . $profilInfo->avatar, 'memberController');
						    				}
						    			}
										
										// Deux posibilité, l'un utiliser Image.class
										// L'autre utiliser CoolPic.class
										// On commence avec CoolPic, puisqu'elle est plus recente
										// Si elle n'existe pas on utilisera Image
						    			if (!class_exists('CoolPic'))
										{
											Log::setLog('CoolPic not exist, use Image', 'memberController');
											$im = new Image($up->getUploadPath());
							    			if ($im->setdir('./media/avatar'))
							    			{
												$im->width(256);
							    				$im->height(256);
							    				$im->save(clean($request->data->loginmember, 'slug').'.'.$im->getExt());
											
												$dataToSaveInfo->avatar = clean($request->data->loginmember, 'slug').'.'.$im->getExt();
						    					$profilInfo->avatar = $dataToSaveInfo->avatar;
											}
										}
										else // Si CoolPic existe 
										{
											try
											{
												Log::setLog('CoolPic exist, use it', 'memberController');
												$im = new CoolPic();
												// On chage l'image
												// On indique que la largeur/heuteur maximum est 256
												// On sauvegarder l'image
												$im->loadImage($up->getUploadPath())
													->rate(256)
													->save(__SITE_PATH . DS . 'media' . DS . 'avatar'. DS . clean($request->data->loginmember, 'slug').'.'.$im->getExtention());
												
												$dataToSaveInfo->avatar = clean($request->data->loginmember, 'slug').'.'.$im->getExtention();
					    						$profilInfo->avatar = $dataToSaveInfo->avatar;
											} catch (exception $e) 	{ /* Erreur */ Log::setLog('Exception reçu: ' . $e->getMessage(), 'memberController'); }
										}
					    			} else { $errors['avatar'] = 'Erreur lors du transfère, l\'écriture est refusé'; }
					    		} else { $errors['avatar'] = 'Extention incorrect'; }
						    } else { $errors['avatar'] = 'Extention incorrect'; }
						}
					} // if (isSet($_FILES['avatar']))
				} // ELSE //if ($request->data->delavatar == 'yes')
			} // if (count($errors) == 0)
	
	
			/**
			 * Info
			 */
	        if (isSet($request->data->website))
	        {
	            if(!empty($request->data->website))
	            {
	                if (!isURL($request->data->website))
	                {
	                	if (!preg_match("#(http:\/\/)#", $subject))
						{
							$request->data->website = 'http://' . $request->data->website;
						}
						
						if (!isURL($request->data->website))
						{
	             			$errors['website'] = 'L\'adresse du site internet est incorrect';
						}
		                else
		                {
		              		$dataToSaveInfo->website = $request->data->website;
						}
	                }
	                else
					{
	              		$dataToSaveInfo->website = $request->data->website;
					}
	            }
	        }
		        
	
			if (isSet($request->data->day) AND isSet($request->data->month) AND isSet($request->data->year))
			{
				if (!checkdate($request->data->month, $request->data->day, $request->data->year))
				{
					$errors['birthday'] = 'Date incorrect ';
				}
				else {
					$dataToSaveInfo->birthday = mktime(1,1,1,$request->data->month, $request->data->day, $request->data->year);
				}
			}
	

			if (isset($request->data->mailmember))
			{
				if (!Securite::isMail($request->data->mailmember))
				{
					$errors['mailmember'] = i18n::get('Is not e-mail address');
				}
				else
				{
					$dataToSave->mailmember = $request->data->mailmember;
				}
			}
			
	        if (count($errors)==0)
	        {
	        	$mMember->save($dataToSave);
				
				$mMemberInfo = loadModel('MemberInfo');
	        	$dataToSaveInfo->bio = $request->data->bio;
	        	$dataToSaveInfo->sign = $request->data->sign;
	        	$dataToSaveInfo->leisure = $request->data->leisure;
	        	$dataToSaveInfo->location = $request->data->location;
	        	$dataToSaveInfo->job = $request->data->job;
	        	$dataToSaveInfo->sex = $request->data->sex;
	        	
	            $dataToSaveInfo->thismember = (int) $request->params['id'];

	            if ($mMemberInfo->changeInfo($dataToSaveInfo))
	            {
	            	//cwDebug($dataToSave);
	            	// var_dump($mMemberInfo->sql);
	            	//echo '<!-- ' . print($mMemberInfo->sql). ' -->';
	            
	            	$session->setFlash('Profil à été mise à jour');
					//return Router::redirect('membermanager/edituser/id:' . $profilInfo->idmember);
	            }
				
			//	$mMemberInfo->debug();
	        }
			
		} // if (isSet($request->data->loginmember))
		
		if ($profilInfo->hasban == 'y' or $profilInfo->warning >= 100)
		{
			$session->setFlash(clean($profilInfo->loginmember, 'slug') . ' est bannis du site', 'info');
			$profilInfo->hasban = 'y';
		}
		
		$template->profilInfo = $profilInfo;
		$form->setErrors($errors);
		
	    $template->show('membermanager/edituser');
		
	}


	/**
	 * Configuration
	 * @author DevPHP
	 */
	public function config()
	{
		$acl = AccessControlList::getInstance();
		$page = Page::getInstance();
		$session = Session::getInstance();
		$request = Request::getInstance();
		$template = Template::getInstance();
		
		if (!$acl->isAllowed()) {
			$session->setFlash(i18n::get('Permission denied'), 'error');
			Router::redirect('auth');
		}
		/***************************************
		 *   Création du fil arian et du titre
		***************************************/
		$page->setBreadcrumb('member', 'Membre')
						->setBreadcrumb('membermanager', 'Manager')
						->setPageTitle('Configuration');
	
	    $config = new Cache('memberConfig');
	    if (!$meCache = $config->getCache())
	    {
	    $meCache = new stdClass();
		$meCache->activMode = 'auto';
		
	    $meCache->cgutitle = 'Conditions général d\'utilisation du site';
	    $meCache->cgutext = '<p>Les modérateurs de ce site s\'efforceront de supprimer ou éditer tous les messages à caractère répréhensible aussi rapidement que possible. Toutefois, il leur est impossible de passer en revue tous les messages. Vous admettez donc que tous les messages postés sur ce site expriment la vue et opinion de leurs auteurs respectifs, et non celles des modérateurs ou du webmestre (excepté des messages postés par eux-mêmes) et par conséquent qu\'ils ne peuvent pas être tenus pour responsables des discussions. </p>
	
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
	
		// Mode d'activation
		if ( isSet($request->data->activMode) && isSet($request->data->cgutitle) && isSet($request->data->cgutext) )
		{
			$meCache->activMode = $request->data->activMode;
	
			$meCache->cgutitle = $request->data->cgutitle;
	
			$meCache->cgutext = $request->data->cgutext;
			
			if ($config->setCache($meCache))
			{
				$session->setFlash('Configuration enregistr&eacute;');
			}
		}
		
		
	    $template->config = $meCache;
	    $template->show('membermanager/config');

	}


	/**
	 * Envois d'un mail a un membre
	 * @note Probleme en interne le mail ne s'envois pas
	 * @author DevPHP
	 */
	public function mail()
	{
		$acl = AccessControlList::getInstance();
		$page = Page::getInstance();
		$session = Session::getInstance();
		$request = Request::getInstance();
		$template = Template::getInstance();
		
		/***************************************
		 *   Verifie les droits de l'utilisateur
		***************************************/
		if (!$acl->isAllowed()) {
			$session->setFlash(i18n::get('Permission denied'), 'error');
			Router::redirect('auth');
		}
		elseif( !isSet($request->params['id']) )
		{
			$session->setFlash('Le membre n\'existe pas', 'error');
		    return $this->index();
		}
	
	
		/***************************************
		 *   Création du fil arian et du titre
		***************************************/
		$page->setBreadcrumb('member', 'Membre')
						->setBreadcrumb('membermanager', 'Manager')
						->setPageTitle('Correspondance avec un membre');
		
	
	
		/***************************************
		*   Recherche le membre dans la DB
		***************************************/
		$member = loadModel('Member');
		$query = array(
		    'conditions' => array('idmember' => (int) $request->params['id'])
		    );
	
	    /***************************************
	    *   Si il existe pas on retourne le message
	    ***************************************/
	    if (!$currentMember = $member->findFirst($query))
	    {
		    $session->setFlash('Le membre n\'existe pas', 'error');
		    return $this->index();
	    }
	
	    if (isSet($request->data->object))
	    {
		    $p_objet = (isSet($request->data->object)) ? $request->data->object : '';
		    $p_text = (isSet($request->data->message)) ? $request->data->message : '';
		    $p_destinataire = (isSet($request->data->to)) ? $request->data->to : '';
		    $p_emetteur = (isSet($request->data->from)) ? $request->data->from : '';
	
		    if (!empty($p_objet) && !empty($p_text) && !empty($p_destinataire) && !empty($p_emetteur))
		    {
		    $errors = array();
			
		        if(!filter_var ( $p_destinataire, FILTER_VALIDATE_EMAIL ))
		        {
		        	$errors['to'] = 'E-mail invalide ('.$p_destinataire.')';
		        }
		        if(!filter_var ( $p_emetteur, FILTER_VALIDATE_EMAIL ))
		        {
					$errors['by'] = 'E-mail invalide';
		        }
		
			    if (count($errors) == 0)
			    {
			    	$mail = new Mail($p_objet,$p_text,$p_destinataire,$p_emetteur);
			        if($mail->sendMailHtml())
			        {
				        $session->setFlash('E-mail envoyé');
				        Router::redirect('membermanager');
			        }
					else {
				        $session->setFlash('Le mail n\'a pu &ecirc;tre envoy&eacute;', 'error');
					}
			    } else {
			        $form->setErrors($errors);
			        $session->setFlash('Veuillez compléter tout les champs');
			    }
		
		    } else {
				$session->setFlash('Veuillez compléter tout les champs');
			}
		} // Fin test post
	
	
		$template->member = $currentMember;
	    $template->show('membermanager/mail');
	}


	public function purge()
	{
		$acl = AccessControlList::getInstance();
		$page = Page::getInstance();
		$session = Session::getInstance();
		$request = Request::getInstance();
		$template = Template::getInstance();
		
		if (!$acl->isAllowed()) {
			$session->setFlash(i18n::get('Permission denied'), 'error');
			Router::redirect('auth');
		}
		$mMember = loadModel('Member');
		$mMember->purge();
		$session->setFlash('Purge des membres effectu&eacute;');
		return Router::redirect('membermanager');
	}


	public function rpc()
	{
		$acl = AccessControlList::getInstance();
		$page = Page::getInstance();
		$session = Session::getInstance();
		$request = Request::getInstance();
		$template = Template::getInstance();
		
		if (!$acl->isAllowed()) {
			$session->setFlash(i18n::get('Permission denied'), 'error');
			Router::redirect('auth');
		}
		$page->setLayout('empty');

		if (!isSet($request->params['cmd'])){
		 return;	
		}
		
		switch($request->params['cmd']):
		case 'findmember':
			// Is there a posted query string?
		    if(isset($request->data->queryString)) {
		        $queryString = $request->data->queryString;
		        // Is the string length greater than 0?
		        if(strlen($queryString) > 0) {                                                           
		
		        $m = loadModel('Member');
		        $resp = $m->find(array('conditions' => array('loginmember' => $queryString.'%'), 'limit' => '5'));
		        if($resp) {
		            // While there are results loop through them - fetching an Object (i like PHP5 btw!).
		            foreach($resp AS $k => $v)
		            {
		                // Format the results, im using <li> for the list, you can change it.
		                // The onClick function fills the textbox with the result.
		               // echo '</li>
					echo '<li onclick="fill(\''.clean($v->loginmember, 'slug').'\');">'.clean($v->loginmember, 'slug').'</li>';
		            }
		        } else { 
		            echo '<li>Aucune correspondance</li>';
		        }
		    } else {
		        // Dont do anything.
		    } // There is a queryString.
		
			}
		break;
		endswitch;

	}
}
 