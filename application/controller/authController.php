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
Class authController Extends Controller {


/*********************************************/
/* Connection d'un membre/client 			 */
/*********************************************/
public function index()
{
$this->mvc->Page->setPageTitle('Connection au site');

$auth = $this->loadModel('Member');

	// On a reçu des donnees ?
	if ($this->mvc->Request->data)
	{
		// Elles sont valide ?
		if ($auth->validates($this->mvc->Request->data))
		{
		// Nettoyage...
		$this->mvc->Request->data->loginmember = $auth->clean($this->mvc->Request->data->loginmember);
			
			// Ca correspond a un membre ?
			$user = $auth->checkLogin($this->mvc->Request->data);
			if ($user)
			{
			
				if ($user->validemember == 'off')
				{
				$this->mvc->Session->setFlash('<strong>'.$user->loginmember.'</strong>, vous n\'avez pas encore validé votre compte<br>Pour rappel, vous avez utilisé l\'adresse e-mail suivante: '.$user->mailmember, 'warning');
				}
				else
				{

				// ok, on ecris dans la session
				$this->mvc->Session->write('user',$user);
				// Met a jour la dernière activité
				$auth->lastActivity($user->idmember);
					

				$this->mvc->Plugin->triggerEvents('onMemberLogin');
				
				$this->mvc->Session->setFlash('<strong>Bonjour '.$this->mvc->Session->user('login').'</strong>, vous &ecirc;tes maintenant connect&eacute;', 'success');
				Router::redirect('');
				}
			}
			else
			{
			$this->mvc->Session->setFlash('Votre login et mot de passe semble incorrect.<br><a href="'.Router::url('auth/forgotpassword').'" class="btn info">Récupérer mon mot de passe</a> <a href="'.Router::url('auth/subscribe').'" class="btn info">S\'inscrire</a>', 'error');
			}
			
		}
		else
		{
		$this->mvc->Session->setFlash('Il y a une ou plusieurs erreurs se sont produites', 'error');
		}

	}

	$this->mvc->Form->setErrors($auth->errors);
	$form = $this->mvc->Form->input('loginmember', 'Login:');
	$form .= $this->mvc->Form->input('passmember', 'Mot de passe', array('type' => 'password'));
	$form .= $this->mvc->Form->input('connect', 'Connection auto', array('type' => 'checkbox', 'option' => array('Me connecter automatiquement')));
	$form .= $this->mvc->Form->input('send', 'Connection', array('type' => 'submit', 'class' => 'btn primary'));



	$this->mvc->Template->form = $form;
	$this->mvc->Template->show('auth/login');	
}


/*********************************************/
/* Forgot password				 			 */
/*********************************************/
public function forgotpassword()
{

$this->mvc->Page->setPageTitle('Mot de passe oublié');
$this->mvc->Template->link_registration = Router::url('auth/subscribe');
$this->mvc->Template->link_forgotpassword = Router::url('auth/forgotpassword');

	if ( isSet($this->mvc->Request->data->mailmember) )
	{
	$memberModel = $this->loadModel('Member');
	$sql = array(
	'conditions' => array( 
		'mailmember' => strtolower(trim($this->mvc->Request->data->mailmember))
		)
		
		);
	$info_user = $memberModel->findFirst($sql);

	$errors_forgot=array();
		if (!empty($info_user))
		{
		// Génére le nouveau mots de passe
		$pass = NULL;
		$charlist = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz0123456789';
		$ps_len = strlen($charlist);
		mt_srand((double)microtime()*1000000);


			// Formule pour générer un mot de passe
			for($i = 0; $i < 6; $i++) { $pass .= $charlist[mt_rand(0, $ps_len - 1)]; }
			
			//Auth::updatePassword($info_user->idmember , $pass, $info_user->loginmember)
			if ($memberModel->changePassword($info_user->idmember, $info_user->loginmember, $pass))
			{
			// Preparation du mail
			$message_mail = '<html><head></head><body>
			<p>
				Rappel de vos identifiants de connection.
			</p>
			<p>
				Your user name : '.$info_user->loginmember.'<br />
				Your password : '.$pass.'
			</p>
			<hr>
			IP demandeur: '.Securite::ipX().'
			En cas d\'abus ou d\'utilisation par un tiers, n\'h&eacute;sitez; pas &agrave; nous le faire savoir.
			</body></html>';
			$mail_send = new Mail('Nouveau mot de passe <'.__CW_PATH.'>', $message_mail, $info_user->mailmember, ADMIN_MAIL);
			$this->mvc->Template->mailStatus = $mail_send->sendMailHtml();
			$this->mvc->Template->user = $info_user->loginmember;
			$this->mvc->Template->show('auth/forgotpassword_success');
			}
			else // Erreur modification password
			{				
			$this->mvc->Session->setFlash("Une erreur c'est produite lors de la modification du mot de passe.<br  />
			Veuillez contacter l'administrateur du site.", 'error');
			$this->mvc->Template->show('auth/forgotpassword');
			}
		}
		else // Membre introuvable
		{
			$this->mvc->Session->setFlash("Votre adresse de messagerie est introuvable.", 'error');
			$this->mvc->Template->show('auth/forgotpassword');
		}
	}
	else
	{
		$this->mvc->Template->show('auth/forgotpassword');
	}

}

	
/*********************************************/
/* Deconnexon d'un membre/client 			 */
/*********************************************/
public function logout()
{
$this->mvc->Page->setPageTitle('Confirmation de déconnexion');

	$this->mvc->Plugin->triggerEvents('onMemberLogout');
// Suppression des cookies de connexion automatique
setcookie('id', '', 0, '/');
setcookie('connection_auto', '', 0, '/');
$this->mvc->Session->setFlash('<strong>Au revoir '.$this->mvc->Session->user('login').'</strong>, vous &ecirc;tes maintenant d&eacute;connect&eacute;.');

$this->mvc->Session->logout();
Router::redirect('');
}
	
	
public function subscribe()
{
$this->mvc->Page->setPageTitle('Cr&eacute;ation d\'un compte');

$Captcha = new Captcha();
$errorCount=0;

if (isSet($this->mvc->Request->data->loginmember))
{
$subscribe = $this->loadModel('Member');

// Check lenght 
$lenStart = strlen($this->mvc->Request->data->loginmember);
$this->mvc->Request->data->loginmember = clean($this->mvc->Request->data->loginmember, 'alphaNumUnder');
$lenStop = strlen($this->mvc->Request->data->loginmember);





	if ($subscribe->validates($this->mvc->Request->data, 'subscribe'))
	{
		// Anti robot
		if ($Captcha->checkCaptcha()==false)
		{
		$errorCount++;
		}
		// Mot de passe
		if ($this->mvc->Request->data->passmember != $this->mvc->Request->data->otherpassword)
		{
		$errorCount++;
		$subscribe->errors['otherpassword'] = 'Les mot de passes doivent être identique';
		}
		// Pseudo
		if (strlen($this->mvc->Request->data->loginmember) < 3)
		{
		$errorCount++;
		$subscribe->errors['loginmember'] = 'Votre pseudo est trop court (min: 5 car. alphanumérique)';
		}
		if ($subscribe->searchMemberByLogin($this->mvc->Request->data->loginmember))
		{
		$errorCount++;
		$subscribe->errors['loginmember'] = 'Ce pseudo est déjà utilisé';
		}
		
		if ($lenStop != $lenStart)
		{
		$errorCount++;
		$subscribe->errors['loginmember'] = 'Votre pseudo contient des caractères incorrect';
		}
		
		// Mail
		$this->mvc->Request->data->mailmember = strtolower($this->mvc->Request->data->mailmember);
		if ($subscribe->searchMemberByMail($this->mvc->Request->data->mailmember))
		{
		$errorCount++;
		$subscribe->errors['mailmember'] = 'Cette adresse e-mail est déjà utilisé';
		}
	
		// conditions générales d'utilisation
		if ($this->mvc->Request->data->declare_coche == '0')
		{
		$errorCount++;
		$subscribe->errors['declare_coche'] = 'Vous devez accepter les conditions générales d\'utilisation';
		}
		
		/**
		*	Si il y a pas d'erreur 
		*/
		if ($errorCount == 0)
		{


			$hash_validation = md5(uniqid(rand(), true));
// Preparation du mail			
$message_mail = '
<p>
Bonjour '.$this->mvc->Request->data->loginmember.'.<br />
Merci pour votre inscription sur '.$this->mvc->Page->getSiteTitle().'. <br />
Voici le lien &agrave; suivre pour valider votre compte :
<a href="'.Router::url('auth/validate/hash:'.$hash_validation).'">'.Router::url('auth/validate/hash:'.$hash_validation).'</a><br /><br />
Merci.
</p>

<p>
Pour rappel :<br />
Login: '.$this->mvc->Request->data->loginmember.'<br />
Mot de passe: '.$this->mvc->Request->data->passmember.'<br />
</p>';
$mail_send = new Mail('Confirmation d\'inscription',$message_mail,$this->mvc->Request->data->mailmember, ADMIN_MAIL);


// loginmember		mailmember	validemember	levelmember	groupmember	firstactivitymember	lastactivitymember	hash_validation			
			$data = new stdClass();
			$data->loginmember = $this->mvc->Request->data->loginmember;
			$data->password = md5($this->mvc->Request->data->passmember);
			$data->mailmember = $this->mvc->Request->data->mailmember;
			$data->levelmember = 1;
			$data->firstactivitymember = time();
			$data->lastactivitymember = time();
			$data->ip = Securite::ipX();
			$data->hash_validation = $hash_validation;
			

			if (!$mail_send->sendMailTemplate())
			{
				$LOGGER = loadModel('Log');
				$LOGGER->setLog('auth', 'Mail restriction, can not send ' . $this->mvc->Request->controller . ' ' . $this->mvc->Request->action);

				$data->levelmember = 2;
				$this->mvc->Session->setFlash('Félicitation, votre inscription c\'est bien déroulé.', 'warning');
			}
			else
			{
				//Log::console(true);
				$this->mvc->Session->setFlash('Félicitation, votre inscription c\'est bien déroulé.<br>Vérifier votre boite mail pour valider votre inscription.<br>ATTENTION: Si vous ne recevez pas l\'e-mail, vérifier vos e-mail indésirables.<br>Votre adresse e-mail est ' . $data->mailmember,'warning');
				//
			}

			$subscribe->save($data);
			//debug($subscribe->sql);
			Router::redirect();
		}
		else
		{
			$err = (count($subscribe->errors) > 1) ? 'les erreurs' : 'l\'erreur';
			$this->mvc->Session->setFlash('Veuillez corriger '.$err, 'error');
		}
	
	}
	
	$this->mvc->Form->setErrors($subscribe->errors);
}	


//	$this->mvc->Template->flag = $cw_flag;
	$this->mvc->Template->errors_registration=NULL;
	$this->mvc->Template->captcha_img = Captcha::generateImgTags("..");
	$this->mvc->Template->captcha_hidden = Captcha::generateHiddenTags();
	$this->mvc->Template->captcha_input = Captcha::generateInputTags();
	/*** Affichage ***/
	$this->mvc->Template->show('auth/subscribe');
	

}



public function validate()
{
$this->mvc->Page->setPageTitle('V&eacute;rification du compte');
$hash = (isSet($this->mvc->Request->params['hash'])) ? $this->mvc->Request->params['hash'] : null;
	// On vérifie qu'un hash est présent
	if (!empty($hash)) {
	$validate = $this->loadModel('Member');
	
		// valider_compte_avec_hash() est définit dans ~/modeles/membres.php
		if ($validate->checkHash($hash)==true)
		{
			/*** Affichage ***/
			$this->mvc->Session->setFlash('Votre compte est maintenant validé.<br>Vous pouvez vous connecter.');
			Router::redirect();
		} else {
			/*** Affichage ***/
			$this->mvc->Session->setFlash('Une erreur c\'est produite, peut-être que votre compte est déjà validé.', 'error');
			Router::redirect();
		}

	}
	else
	{
		header('location: '. Router::url('launcher'));die();
	}
}
	


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

$this->mvc->Page->setPageTitle($meCache->title);
$this->mvc->Template->cgu = $meCache->text;
$this->mvc->Template->show('auth/cgu');
}



public function cgu_manager()
{
	if ($this->mvc->Acl->isAllowed())
	{
	$this->mvc->Page->setPageTitle('Changement de la CGU');
	
	
$cgu = new Cache('cgu');
	if (isSet($this->mvc->Request->data->text ))
	{
	$cgu->setCache($this->mvc->Request->data);
	
	}
	



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



public function manager()
{
if ($this->mvc->Acl->isAllowed())
{
$page = (int) (isSet($_GET['page'])) ? $_GET['page'] : 1;
$member = $this->loadModel('Member');

$to = (30 *($page-1));
$req = array('limit' => $to.',30 ');
$order = isSet($this->mvc->Request->params['order']) ? $this->mvc->Request->params['order'] : 'asc';
$getOrder = strtoupper($order);
if (isSet($this->mvc->Request->params['by']))
{

	switch($this->mvc->Request->params['by']):
	case 'id': $req['order'] = 'idmember '.$getOrder;  break;
	case 'login': $req['order'] = 'loginmember '.$getOrder;  break;
	case 'mail': $req['order'] = 'mailmember '.$getOrder;  break; 
	case 'subscribe': $req['order'] = 'mailmember '.$getOrder;  break; 
	endswitch;
}

$nbMember = $member->count();

$this->mvc->Template->order			= $order;
$this->mvc->Template->nbMember		= $nbMember;
$this->mvc->Template->nbPage		= ceil($nbMember / 30);
$this->mvc->Template->memberList	= $member->find($req);
$this->mvc->Template->show('auth/manager');
}
}


}

?>
