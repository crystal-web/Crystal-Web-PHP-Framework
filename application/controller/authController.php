<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by/2.0/fr/
*/

if (!defined('__APP_PATH'))
{
	echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"><html><head><title>403 Forbidden</title></head><body><h1>Forbidden</h1><p>You don\'t have permission to access this file on this server.</p></body></html>'; die;
}

Class authController Extends Controller {
	
	private $passLength = 6;
	private $loginLength = 6;
	
	private $snoop = true;
	
	/**
	 * @desc
	 */
	public function index()
	{
		$this->login();
	}
	
	public function login() {
		$page = Page::getInstance();
		$session = Session::getInstance();
		$request = Request::getInstance();
		$form = Form::getInstance();
		$template = Template::getInstance();
		$plugin = Plugin::getInstance();
		
		if ($session->isLogged ()) {
			return Router::redirect ( 'member/profil' );
		}
		
		$page->setPageTitle ( i18n::get ( 'Connexion' ) )
						->setBreadcrumb('auth', i18n::get('Authentication'));
		

		
		$auth = new MemberModel();
		
		// On a reçu des donnees ?
		if ($request->data) {
			// Elles sont valide ?
			if ($auth->validates ( $request->data )) {
				// Nettoyage...
				$request->data->loginmember = clean ( $request->data->loginmember, 'slug' );
				
				// Ca correspond a un membre ?
				$user = $auth->checkLogin ( $request->data );
				
				$mBruteForce = new MemberBruteforceModel();
				if (! $user) {
					$mBruteForce->onFaild ( false );
					$session->setFlash ( i18n::get ( 'Your login or password was incorrect' ), 'warning' );
					return Router::redirect ( 'auth' );
				} elseif ($user->password != md5 ( magicword . $request->data->passmember )) {
					$mBruteForce->onFaild ( $user );
					$session->setFlash ( i18n::get ( 'Your login or password was incorrect' ), 'warning' );
					return Router::redirect ( 'auth' );
				} else {
					if (! $mBruteForce->onLogin ( $user )) {
						$session->setFlash ( i18n::get ( 'Your login or password was incorrect' ), 'warning' );
						return Router::redirect ( 'auth' );
					}
				}
				
				if ($user->warning == 100 && ( int ) $user->hasban != 'y') {
					$user->hasban = 'y';
					$auth->save ( $user );
					$session->setFlash ( i18n::get ( 'Hello, your account has banned', $user->loginmember ), 'warning' );
					return Router::redirect ( 'auth' );
				}
				elseif ($user->hasban == 'y') {
					$session->setFlash ( i18n::get ( 'Hello, your account has banned', $user->loginmember ), 'warning' );
					return Router::redirect ( 'auth' );
				}
				
				// ok, on ecris dans la session
				$session->write ( 'user', $user );
				
				if ($this->snoop)
				{
					$mSnoop = new MemberSnoopModel();
					$mSnoop->register ( $user->idmember );
				}
				
				// Met a jour la dernière activité
				$auth->lastActivity ( $user->idmember );
				
				$session->setFlash ( i18n::get ( 'Hello, you are now connected', clean($user->loginmember, 'slug') ), 'success' );
				$plugin->triggerEvents('onMemberLogin');
				
				$auth->primaryKey = 'idmember';
				$user->ip = Securite::ipX ();
				$user->lastactivitymember = time ();
				$auth->save ( $user );
				
				$session->write('user', $user);
				return Router::redirect ('member/profil');
			}
			else
			{
				$session->setFlash('Il y a une ou plusieurs erreurs se sont produites', 'error');
			}
		
		}
		
		$form->setErrors($auth->errors);
		$template->show('auth/login');
	}
	
	
	public function logout()
	{
		$session = Session::getInstance();
		$plugin = Plugin::getInstance();
		
		if (!$session->isLogged())
		{
			return $this->login();
		}
		
		$plugin->triggerEvents('onMemberLogout');
		$session->setFlash(i18n::get('You are now disconnected'));
		$session->logout();
		Router::redirect();
	}
	
	
	public function subscribe()
	{
		$page = Page::getInstance();
		$session = Session::getInstance();
		$template = Template::getInstance();
		$request = Request::getInstance();
		$acl = AccessControlList::getInstance();
		$form = Form::getInstance();
		
		$page->setPageTitle(i18n::get('Join website'))
			->setBreadcrumb('auth', i18n::get('Authentication'));
		$Captcha = new Captcha();
		
		if (!$session->token())
		{
			$meCache = new stdClass();
			$meCache->activMode = 'auto';
			$meCache->cgutitle = 'Conditions g&eacute;n&eacute;ral d\'utilisation du site';
			$meCache->cgutext = '<p>Les mod&eacute;rateurs de ce site s\'efforceront de supprimer ou &eacute;diter tous les messages à caract&egrave;re r&eacute;pr&eacute;hensible aussi rapidement que possible. Toutefois, il leur est impossible de passer en revue tous les messages. Vous admettez donc que tous les messages post&eacute;s sur ce site expriment la vue et opinion de leurs auteurs respectifs, et non celles des mod&eacute;rateurs ou du webmestre (except&eacute; des messages post&eacute;s par eux-m&ecirc;mes) et par cons&eacute;quent qu\'ils ne peuvent pas &ecirc;tre tenus pour responsables des discussions. </p>
			<p>L\'adresse e-mail est uniquement utilis&eacute;e afin de confirmer les d&eacute;tails de votre inscription ainsi que votre mot de passe (et aussi pour vous renvoyer votre mot de passe en cas d\'oubli). </p>
			<ul>
			<li>les messages agressifs ou diffamatoires, les insultes et critiques personnelles, les grossi&egrave;ret&eacute;s et vulgarit&eacute;s, et plus g&eacute;n&eacute;ralement tout message contrevenant aux lois sont interdits </li>
			<li>les messages incitant à - ou &eacute;voquant - des pratiques ill&eacute;gales sont interdits ;</li>
			<li>si vous diffusez des informations provenant d\'un autre site web, v&eacute;rifiez auparavant si le site en question ne vous l\'interdit pas. Mentionnez l\'adresse du site en question par respect du travail de ses administrateurs !</li>
			<li>merci de poster vos messages une seule fois. Les r&eacute;p&eacute;titions sont d&eacute;sagr&eacute;ables et inutiles !</li>
			<li>merci de faire un effort sur la grammaire et l\'orthographe. Style SMS fortement d&eacute;conseill&eacute; !</li>
			<li>aucun compte ouvert ne pourra &ecirc;tre supprim&eacute; ! (ceci pour des raisons technique)</li>
			</ul>
			
			<p>Tout message contrevenant aux dispositions ci-dessus sera &eacute;dit&eacute; ou supprimé&eacute;sans pr&eacute;avis ni justification suppl&eacute;mentaire dans des d&eacute;lais qui d&eacute;pendront de la disponibilit&eacute; des mod&eacute;rateurs. Tout abus entraînera le bannisment de votre compte, e-mail, adresse IP. <br>
			Internet n\'est ni un espace anonyme, ni un espace de non-droit ! Nous nous r&eacute;servons la possibilit&eacute; d\'informer votre fournisseur d\'acc&egrave;s et/ou les autorit&eacute;s judiciaires de tout comportement malveillant. L\'adresse IP de chaque intervenant est enregistr&eacute;e afin d\'aider &agrave; faire respecter ces conditions.</p>
			
			<p>En vous inscrivant sur le site vous reconnaissez avoir lu dans son int&eacute;gralit&eacute; le pr&eacute;sent r&ecirc;glement. Vous vous engagez &agrave; respecter sans r&eacute;serve le pr&eacute;sent r&egrave;glement. Vous accordez aux mod&eacute;rateurs de ce site le droit de supprimer, d&eacute;placer ou &eacute;diter n\'importe quel sujet de discussion &agrave; tout moment.</p>
			
			<p>Nous prot&eacute;geons la vie priv&eacute;e de nos utilisateurs en respectant la l&eacute;gislation en vigueur.<br>
			Ainsi, vos donn&eacute;es personnelles restent strictement confidentielles et ne seront donc pas distribu&eacute;es &agrave; des tierces parties sans votre accord.</p>';
			
			$config = new Cache('memberConfig', $meCache);
			$meCache = $meCache = $config->getCache();
			
			$page->setPageTitle( $meCache->cgutitle );
			$template->cgu = $meCache->cgutext;
			$template->show('auth/subscribe_cgu');
		}
		else
		{

			$error = array();
			if (isset($request->data->loginmember) AND 
				//$this->mvc->Request->data->passmember AND 
				//$this->mvc->Request->data->otherpassword AND 
				isset($request->data->mailmember) AND 
				isset($request->data->othermail) AND 
				Captcha::checkCaptcha() )
				
			{
				$mMember = new MemberModel();
				/*if (strlen($request->data->passmember) < $this->passLength)
				{
					$error['passmember'] = i18n::get('Your password is too short', $this->passLength);
				}
				elseif ($request->data->passmember != $request->data->otherpass)
				{
					$error['passmember'] = i18n::get('Your password is different from the verification');
				}//*/
				
				if (!Securite::isMail($request->data->mailmember))
				{
					$error['mailmember'] = i18n::get('Your address email is incorrect or blacklisted');
				}
				elseif ($request->data->mailmember != $request->data->othermail)
				{
					$error['mailmember'] = i18n::get('Your address email is different from the verification');
				}
				elseif ($mMember->searchMemberByMail($request->data->mailmember))
				{
					$error['mailmember'] = i18n::get('This address email has already used');
				}
				
				$request->data->loginmember = clean($request->data->loginmember, 'slug');
				if (strlen($request->data->loginmember) < $this->loginLength)
				{
					$error['loginmember'] = i18n::get('Your login is too short', $this->loginLength);
				}
				elseif ($mMember->searchMemberByLogin($request->data->loginmember))
				{
					$error['loginmember'] = i18n::get('This login has already used');
				}
				
				if (!count($error))
				{
					$aclModel = new AclInheritanceModel();
					$md5 = md5(uniqid('', true));
					$hash = substr($md5, 0, 8 ) . '-' . substr($md5, 8, 4) . '-' . substr($md5, 12, 4) . '-' . substr($md5, 16, 4) . '-' . substr($md5, 20, 12);
					
					$newMember = new stdClass();
					$newMember->loginmember = $request->data->loginmember;
					$newMember->password = md5(uniqid());
					$newMember->mailmember = $request->data->mailmember;
					$newMember->validemember = 'off';
					// Compte les membres, si la réponse est 0, c'est le premier membre et donc le SuperUser
					
					if ($mMember->count() == 0)
					{
						$newMember->groupmember = 'SuperUser';
					} else {
						$newMember->groupmember = $aclModel->findDefault();
					}
					
					$newMember->firstactivitymember = time();
					$newMember->lastactivitymember = time();
					$newMember->hash_validation = $hash;
					$newMember->ip = Securite::ipX();
					$newMember->warning = 0;
					$newMember->hasban = 'n';
					
					if ($mMember->save($newMember))
					{
						// Preparation du mail
						$message_mail = i18n::get('Hello', clean($newMember->loginmember, 'slug')) . PHP_EOL . PHP_EOL . 
						
						i18n::get('Thank you for your registration').'.' . PHP_EOL . 
						i18n::get('Please follow the link below to complete your registration') . '.'  . PHP_EOL .
						'[url]'.Router::url('auth/validate/slug:' . $hash).'[/url]'  . PHP_EOL . PHP_EOL;

	
						$mail_send = new Mail($newMember->mailmember, i18n::get('Welcome', clean($newMember->loginmember, 'slug')), $message_mail);
						if ($mail_send->sendMail())
						{
							$session->setFlash(i18n::get('An email has been sent to you') . '.<br>' . 
												i18n::get('Please follow the link in the email to complete your registration').'.<br>' . 
												i18n::get('Warning: If you do not receive the email, check your junk mail')
								, 'success');
							return Router::redirect();
						} else {
							$mWarning = new LogModel();
							$mWarning->setLog('email', i18n::get('Can\'t send email'), $info_user->idmember, 5);
							$session->setFlash(i18n::get('An error has occurred'). '<br>' .i18n::get('Our team has received a warning to correct the error'), 'danger');
						}
						
					}
				}
				
			}
				
			$form->setErrors($error);
			$template->show('auth/subscribe');
		}
		
		
	}
	
	
	public function forgotpassword()
	{
		$page = Page::getInstance();
		$request = Request::getInstance();
		$session = Session::getInstance();
		$template = Template::getInstance();
		
		
		$page->setPageTitle(i18n::get('Forgot password'))
						->setBreadcrumb('auth', i18n::get('Authentication'));
		
		if ( isSet($request->data->mailmember) )
		{
			$info_user = NULL;
			$mailClean = strtolower(trim($request->data->mailmember));
			if (Securite::isMail($mailClean))
			{
				$memberModel = new MemberModel();
				$sql = array(
						'conditions' => array(
								'mailmember' => $mailClean
						)
				);
				$info_user = $memberModel->findFirst($sql);
			}
			
			$errors_forgot=array();
			if (!empty($info_user))
			{
				$md5 = md5(uniqid('', true));
				$hash = substr($md5, 0, 8 ) . '-' . substr($md5, 8, 4) . '-' . substr($md5, 12, 4) . '-' . substr($md5, 16, 4) . '-' . substr($md5, 20, 12);
				$info_user->hash_validation = $hash;
				if ($memberModel->save($info_user))
				{
					// Preparation du mail
					$message_mail = i18n::get('Hello', clean($info_user->loginmember, 'slug')) . PHP_EOL  . PHP_EOL . 
					
					i18n::get('You asked a recover your password') . PHP_EOL . 
					i18n::get('Please follow the link below to set a new password')  . PHP_EOL . 
					'[url]'.Router::url('auth/recovery/slug:' . $hash).'[/url]';
					
					
					$mail_send = new Mail($info_user->mailmember, i18n::get('Forgot password'), $message_mail); 
					if ($mail_send->sendMail())
					{
						$session->setFlash(i18n::get('An email has been sent to you to reset your password').'<br>' . i18n::get('Warning: If you do not receive the email, check your junk mail'), 'success');
					} else {
						$mWarning = new LogModel();
						$mWarning->setLog('email', i18n::get('Can\'t send email'), $info_user->idmember, 5);
						$session->setFlash(i18n::get('An error has occurred'). '<br>' .i18n::get('Our team has received a warning to correct the error'), 'danger');
					}
				}
				
				return Router::redirect();
				
			}
			else // Membre introuvable
			{
				$session->setFlash(i18n::get('Email address not found'), 'error');
				$template->show('auth/forgotpassword');
			}
		}
		else
		{
			$template->show('auth/forgotpassword');
		}
	}
	
	
	public function recovery()
	{
		$request = Request::getInstance();
		$session = Session::getInstance();
		$page = Page::getInstance();
		$template = Template::getInstance();
		
		if (!isSet($request->params['slug']) OR $session->isLogged())
		{
			return $this->index();
		}
		
		$slug = clean($request->params['slug'], 'slug');
		$mMember = new MemberModel();
		$resp = $mMember->findFirst(
					array('conditions' => 
							array(
								'hash_validation' => $slug,
							)
						)
				);
		if (!$resp)
		{
			return $this->index();
		}
		
		if (isSet($request->data->passmember) AND isSet($request->data->passmember2))
		{
			if ($request->data->passmember AND $request->data->passmember2)
			{
				if ($request->data->passmember == $request->data->passmember2)
				{
					if (strlen($request->data->passmember) < $this->passLength)
					{
						$session->setFlash(i18n::get('Your password is too short', $this->passLength), 'warning');
					}
					else
					{						
						
						if ($mMember->changePassword($resp->idmember, $request->data->passmember))
						{
							$session->setFlash(i18n::get('Your new password is now registered'), 'success');
							return Router::redirect('auth');
						} else {
							$mWarning = loadModel('Log');
							$mWarning->setLog('email', i18n::get('Can\'t change password in recovery'), $info_user->idmember, 5);
							$session->setFlash(i18n::get('An error has occurred'). '<br>' .i18n::get('Our team has received a warning to correct the error'), 'danger');
						}
					}
					;
				}
				else
				{
					$session->setFlash(i18n::get('Your password and confirmation are different'), 'warning');
				}
			}
		}
		
		
		$page->setPageTitle(i18n::get('Change password'))
			->setBreadcrumb('auth', i18n::get('Authentication'));
		$template->show('auth/recovery');
		
	}

	
	public function validate()
	{
		$request = Request::getInstance();
		$session = Session::getInstance();
		$page = Page::getInstance();
		$template = Template::getInstance();

		if (!isSet($request->params['slug']) OR $session->isLogged())
		{
			return $this->index();
		}
		
		
		$mMember = new MemberModel();
		$sql = array(
				'conditions' => array(
						'hash_validation' => $request->params['slug'],
				)
		);
		$info_user = $mMember->findFirst($sql);
		if (empty($info_user) OR !$info_user)
		{
			return $this->index();
		}
		
		
		

		
		if (isSet($request->data->passmember) AND isSet($request->data->passmember2))
		{
			if ($request->data->passmember AND $request->data->passmember2)
			{
				if ($request->data->passmember == $request->data->passmember2)
				{
					if (strlen($request->data->passmember) < $this->passLength)
					{
						$session->setFlash(i18n::get('Your password is too short', $this->passLength), 'warning');
					}
					else
					{
						if ($mMember->changePassword($info_user->idmember, $request->data->passmember))
						{
							if ($info_user->validemember)
							{
								$plugin = Plugin::getInstance();
								$plugin->triggerEvents('onMemberValideRegistration', $info_user);
							}
							$session->setFlash(i18n::get('Your new password is now registered'), 'success');
							return Router::redirect('auth');
						} else {
							$mWarning = loadModel('Log');
							$mWarning->setLog('email', i18n::get('Can\'t change password in validate'), $info_user->idmember, 5);
							$session->setFlash(i18n::get('An error has occurred'). '<br>' .i18n::get('Our team has received a warning to correct the error'), 'danger');
						}
					}
						
				}
				else
				{
					$session->setFlash(i18n::get('Your password and confirmation are different'), 'warning');
				}
			}
		}
		$page->setPageTitle(i18n::get('Choose a password'))
				->setBreadcrumb('auth', i18n::get('Authentication'));
		$template->show('auth/subscribe_password');
	}
	
	
	public function cgu()
	{
		$page = Page::getInstance();
		$acl = AccessControlList::getInstance();
		$template = Template::getInstance();
		$request = Request::getInstance();
		$form = Form::getInstance();
		
		$page->setPageTitle(i18n::get('Join website'))
			->setBreadcrumb('auth', i18n::get('Authentication'));
		$Captcha = new Captcha();
	
		$meCache = new stdClass();
		$meCache->cgutitle = 'Conditions g&eacute;n&eacute;ral d\'utilisation du site';
		$meCache->cgutext = '<p>Les mod&eacute;rateurs de ce site s\'efforceront de supprimer ou &eacute;diter tous les messages à caract&egrave;re r&eacute;pr&eacute;hensible aussi rapidement que possible. Toutefois, il leur est impossible de passer en revue tous les messages. Vous admettez donc que tous les messages post&eacute;s sur ce site expriment la vue et opinion de leurs auteurs respectifs, et non celles des mod&eacute;rateurs ou du webmestre (except&eacute; des messages post&eacute;s par eux-m&ecirc;mes) et par cons&eacute;quent qu\'ils ne peuvent pas &ecirc;tre tenus pour responsables des discussions. </p>
		<p>L\'adresse e-mail est uniquement utilis&eacute;e afin de confirmer les d&eacute;tails de votre inscription ainsi que votre mot de passe (et aussi pour vous renvoyer votre mot de passe en cas d\'oubli). </p>
		<ul>
		<li>les messages agressifs ou diffamatoires, les insultes et critiques personnelles, les grossi&egrave;ret&eacute;s et vulgarit&eacute;s, et plus g&eacute;n&eacute;ralement tout message contrevenant aux lois sont interdits </li>
		<li>les messages incitant à - ou &eacute;voquant - des pratiques ill&eacute;gales sont interdits ;</li>
		<li>si vous diffusez des informations provenant d\'un autre site web, v&eacute;rifiez auparavant si le site en question ne vous l\'interdit pas. Mentionnez l\'adresse du site en question par respect du travail de ses administrateurs !</li>
		<li>merci de poster vos messages une seule fois. Les r&eacute;p&eacute;titions sont d&eacute;sagr&eacute;ables et inutiles !</li>
		<li>merci de faire un effort sur la grammaire et l\'orthographe. Style SMS fortement d&eacute;conseill&eacute; !</li>
		<li>aucun compte ouvert ne pourra &ecirc;tre supprim&eacute; ! (ceci pour des raisons technique)</li>
		</ul>
			
		<p>Tout message contrevenant aux dispositions ci-dessus sera &eacute;dit&eacute; ou supprimé&eacute;sans pr&eacute;avis ni justification suppl&eacute;mentaire dans des d&eacute;lais qui d&eacute;pendront de la disponibilit&eacute; des mod&eacute;rateurs. Tout abus entraînera le bannisment de votre compte, e-mail, adresse IP. <br>
		Internet n\'est ni un espace anonyme, ni un espace de non-droit ! Nous nous r&eacute;servons la possibilit&eacute; d\'informer votre fournisseur d\'acc&egrave;s et/ou les autorit&eacute;s judiciaires de tout comportement malveillant. L\'adresse IP de chaque intervenant est enregistr&eacute;e afin d\'aider &agrave; faire respecter ces conditions.</p>
			
		<p>En vous inscrivant sur le site vous reconnaissez avoir lu dans son int&eacute;gralit&eacute; le pr&eacute;sent r&ecirc;glement. Vous vous engagez &agrave; respecter sans r&eacute;serve le pr&eacute;sent r&egrave;glement. Vous accordez aux mod&eacute;rateurs de ce site le droit de supprimer, d&eacute;placer ou &eacute;diter n\'importe quel sujet de discussion &agrave; tout moment.</p>
			
		<p>Nous prot&eacute;geons la vie priv&eacute;e de nos utilisateurs en respectant la l&eacute;gislation en vigueur.<br>
		Ainsi, vos donn&eacute;es personnelles restent strictement confidentielles et ne seront donc pas distribu&eacute;es &agrave; des tierces parties sans votre accord.</p>';
			
		$config = new Cache('memberConfig', $meCache);
		$meCache = $config->getCache();
		
		$page->setPageTitle( clean($meCache->cgutitle, 'str') );
		$template->cgu = $meCache->cgutext;
		$template->show('auth/cgu');
		
		if ($acl->isAllowed())
		{
			if (isset($request->data->text) AND !empty($request->data->text) 
				AND isset($request->data->title) AND !empty($request->data->title) )
			{
				$meCache = new stdClass();
				$meCache->cgutitle = $request->data->title;
				$meCache->cgutext = $request->data->text;
				$config->setCache($meCache);
			}
			
		echo '<form method="post">'.$form->input('title', 'Titre: ',
				array('value' => clean($meCache->cgutitle, 'str'))).
				$form->input('text', 'Texte: ',
						array('type' => 'textarea',
								'editor' => array(
										'params' => array('model' => 'html')
								),
								'value' => clean($meCache->cgutext, 'html'))).
								$form->input('submit', 'Enregistrer ', array('type' => 'submit', 'class' => 'btn success')).
								'</form>';
		}
			

	}
}

?>