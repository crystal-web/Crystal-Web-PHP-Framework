<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
Class authController Extends Controller {

private $module=array(
	/* BOOL */
	'sitemap' => false,
	'title' => 'Authentification', // Titre du module
	'page_title' => NULL, // Titre page courante
	'breadcrumb' => false, // breadcrumb hierarchy $url => $title
	);

/*** Methode ***/

	public function getInfo(){
	return $this->module;
	}

	public function setInfo($name, $is){
	$this->module[$name]=$is;
	return $this->module;
	}
	
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
			if ($user = $auth->checkLogin($this->mvc->Request->data))
			{
			// ok, on ecris dans la session
			$this->mvc->Session->write('user',$user);
			// Met a jour la dernière activité
			$auth->lastActivity($user->idmember);
				
				// Doit-on mettre le cookie de cnnection automatique ?
				if (isSet($this->mvc->Request->data->connect) && $this->mvc->Request->data->connect == '1')
				{
					$hash_cookie = sha1(Securite::Hcrypt($this->mvc->Request->data->loginmember.magicword));
					setcookie( 'id', $user->idmember, ( time() + 60*60*24*30), '/');
					setcookie('connection_auto', $hash_cookie, ( time() + 60*60*24*30), '/');
					unset($hash_cookie);
				}
			
			$this->mvc->Session->setFlash('<strong>'.$this->mvc->Session->user('loginmember').'</strong>, vous &ecirc;tes maintenant connect&eacute;', 'success');
			Router::redirect('article');
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
	$form .= $this->mvc->Form->input('connect', 'Connection auto', array('type' => 'checkbox'));
	$form .= $this->mvc->Form->input('send', 'Connection', array('type' => 'submit', 'class' => 'btn primary'));



	$this->mvc->Template->form = $form;
	$this->mvc->Template->show('auth/login');	
}



public function BAKindex()
{
$this->setInfo('sitemap', true);
$this->setInfo('page_title', 'Connection au site');



$this->mvc->Template->link_registration = Router::url('registration');
$this->mvc->Template->link_forgotpassword = Router::url('auth/forgotpassword');

	/* Retour a la page précédente après connection */
	if (!isSet($_SESSION['pageBeforeConnect']))
	{

		$array_refere=Securite::referer();
		if (/* La requete est interne */
			preg_match("/".$_SERVER['SERVER_NAME']."/Usi", $array_refere['host']) 
			
			/* La requete n'est pas la demande */
			&& !preg_match("/auth/Usi", $_SERVER['HTTP_REFERER'])
		) {
		$_SESSION['pageBeforeConnect']=$_SERVER['HTTP_REFERER'];
		}
	}

	// Création d'un tableau des erreurs
	$errors_connection = array();

	// Validation des champs suivant les règles
	if (isSet($_POST['user']))
	{
	$user = $_POST['user']; 
	$password = $_POST['password'];
	$infos_user = Auth::checkLogin($user, $password);
	
		// Si les identifiants sont valides
		if (false !== $infos_user) {
			Auth::updateLastactivity($infos_user['id_user']);

			if ($infos_user['validemember'] == 'on')
			{
			// On enregistre les informations dans la session
			$_SESSION['user']['id']     = $infos_user['id_user'];
			$_SESSION['user']['pseudo'] = $infos_user['loginmember'];
			$_SESSION['user']['mail']  = $infos_user['mailmember'];
			$_SESSION['user']['power_level'] = $infos_user['levelmember'];

				// Mise en place des cookies de connexion automatique
				if (isSet($_POST['connection_auto']))
				{
				$hash_cookie = sha1(Securite::Hcrypt($user.magicword));

				setcookie( 'id', $_SESSION['user']['id'], ( time() + 60*60*24*30), '/');
				setcookie('connection_auto', $hash_cookie, ( time() + 60*60*24*30), '/');
				unset($hash_cookie);
				}
				
			// Affichage de la confirmation de la connexion

			/*** Affichage ***/
			$url=(isSet($_SESSION['pageBeforeConnect'])) ? $_SESSION['pageBeforeConnect'] : url('index.php?module=news');
			$this->mvc->Template->url=$url;
			$this->mvc->Template->user = $_SESSION['user']['pseudo'];
			$this->mvc->Template->show('auth/login_success');
			header("Refresh: 5;url=".$url);
			
			}
			else
			{
			

			$hash_validation  = $infos_user['hash_validation'];
			

			// On transforme la chaine en entier
			$id_user = (int) $infos_user['idmember'];
			$email = $infos_user['mailmember'];

			// Preparation du mail
				$message_mail = '<html><head></head><body>
				<p>
					Bonjour '.$infos_user['loginmember'].'.<br />
					Vous n\'avez pas encore valid&eacute; votre e-mail.
				</p>
				<p>
					Voici le lien &agrave; suivre pour valider votre compte :
				<a href="'.url('index.php?module=auth&action=validate&hash='.$infos_user['hash_validation']).'">'.url('index.php?module=auth&action=validate&hash='.$infos_user['hash_validation']).'</a><br /><br />
				Merci.
				</p>
				<hr>
				IP demandeur: '.Securite::ipX().'
				En cas d\'abus ou d\'utilisation par un tiers, n\'h&eacute;sitez; pas &agrave; nous le faire savoir.
				</body></html>';
			
			$mail_send = new Mail('Inscribed on <'.$_SERVER['SERVER_NAME'].'>',$message_mail,strtolower($email), ADMIN_MAIL);
			$mail_send->sendMailHtml() or die('Mail restriction, can not send');
			
			$this->mvc->Template->user = $infos_user['loginmember'];
			$this->mvc->Template->show('auth/index_noactive');
			}
		
		
		} else {

			$errors_connection[] = "Couple nom d'utilisateur / mot de passe inexistant.";

			// On réaffiche le formulaire de connexion
			
			/*** Variables ***/
			$this->mvc->Template->errors_connection = $errors_connection;

			/*** Affichage ***/
			$this->mvc->Template->show('auth/index');
		}

	} else {

		// On réaffiche le formulaire de connexion
		/*** Variables ***/
		$this->mvc->Template->errors_connection = $errors_connection;

		/*** Affichage ***/
		$this->mvc->Template->show('auth/index');
	}
			
}


/*********************************************/
/* Forgot password				 			 */
/*********************************************/
public function forgotpassword()
{
$this->setInfo('sitemap', true);
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
			$mail_send = new Mail('New password <'.__CW_PATH.'>', $message_mail, $info_user->mailmember, ADMIN_MAIL);
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
public function logout(){
$this->setInfo('sitemap', false);
$this->mvc->Page->setPageTitle('Confirmation de déconnexion');


	
// Suppression des cookies de connexion automatique
setcookie('id', '', 0, '/');
setcookie('connection_auto', '', 0, '/');
$this->mvc->Session->setFlash('<strong>'.$this->mvc->Session->user('loginmember').'</strong>, vous &ecirc;tes maintenant d&eacute;connect&eacute;.');

$this->mvc->Session->logout();
Router::redirect('');
}
	
	
public function subscribe()
{
$this->setInfo('sitemap', true);
$this->mvc->Page->setPageTitle('Cr&eacute;ation d\'un compte');

$Captcha = new Captcha();
$errorCount=0;

if (isSet($this->mvc->Request->data->loginmember))
{

$loginmember = strtr($this->mvc->Request->data->loginmember, 
'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
$loginmember = preg_replace('/([^.a-z0-9]+)/i', '-', $loginmember);
$this->mvc->Request->data->loginmember = trim($loginmember, '-');


$subscribe = $this->loadModel('Member');
	if ($subscribe->validates($this->mvc->Request->data,$subscribe->subscribe))
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
		$subscribe->errors['othermember'] = 'Les mot de passes doivent être identique';
		}
		// Pseudo
		if (strlen($this->mvc->Request->data->loginmember) < 5)
		{
		$errorCount++;
		$subscribe->errors['loginmember'] = 'Votre pseudo est trop court (min: 5 car. alphanumérique)';
		}
		if ($subscribe->searchMemberByLogin($this->mvc->Request->data->loginmember))
		{
		$errorCount++;
		$subscribe->errors['loginmember'] = 'Ce pseudo est déjà utilisé';
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
$message_mail = '<html>
<body>
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
</p>

</body>
</html>';
$mail_send = new Mail('['.$this->mvc->Page->getSiteTitle().'] Confirmation d\'inscription',$message_mail,$this->mvc->Request->data->mailmember, ADMIN_MAIL);
			if (!$mail_send->sendMailHtml())
			{
			$log = new Log();
			$log->error('Mail restriction, can not send', $this->mvc->Request->controller, $this->mvc->Request->action);
			}
			
// loginmember		mailmember	validemember	levelmember	groupmember	firstactivitymember	lastactivitymember	hash_validation			
			$data = new stdClass();
			$data->loginmember = $this->mvc->Request->data->loginmember;
			$data->passmember = $subscribe->genPass($this->mvc->Request->data->loginmember, $this->mvc->Request->data->passmember);
			$data->mailmember = $this->mvc->Request->data->mailmember;
			$data->levelmember = 1;
			$data->firstactivitymember = time();
			$data->lastactivitymember = time();
			$data->hash_validation = $hash_validation;
			$subscribe->save($data);
			$this->mvc->Session->setFlash('Félicitation, votre inscription c\'est bien déroulé.<br>Vérifier votre boite mail pour valider votre inscription.');
			Router::redirect();
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
	header('location: '.__CW_PATH);die();
	}
}
	

		
}

?>
