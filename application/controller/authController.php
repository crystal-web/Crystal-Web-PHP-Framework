<?php
/**
* @title Simple MVC systeme 
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/
Class authController Extends baseController {

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
$this->setInfo('sitemap', true);
$this->setInfo('page_title', 'Connection au site');

$this->mvc->template->link_registration = url('index.php?module=registration');
$this->mvc->template->link_forgotpassword = url('index.php?module=login&action=forgotpassword');

	/* Retour a la page pr�c�dente apr�s connection */
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

	// Cr�ation d'un tableau des erreurs
	$errors_connection = array();

	// Validation des champs suivant les r�gles
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
			$this->mvc->template->url=$url;
			$this->mvc->template->user = $_SESSION['user']['pseudo'];
			$this->mvc->template->show('auth/login_success');
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
			
			$this->mvc->template->user = $infos_user['loginmember'];
			$this->mvc->template->show('auth/index_noactive');
			}
		
		
		} else {

			$errors_connection[] = "Couple nom d'utilisateur / mot de passe inexistant.";

			// On r�affiche le formulaire de connexion
			
			/*** Variables ***/
			$this->mvc->template->errors_connection = $errors_connection;

			/*** Affichage ***/
			$this->mvc->template->show('auth/index');
		}

	} else {

		// On r�affiche le formulaire de connexion
		/*** Variables ***/
		$this->mvc->template->errors_connection = $errors_connection;

		/*** Affichage ***/
		$this->mvc->template->show('auth/index');
	}
			
}


	/*********************************************/
	/* Forgot password				 			 */
	/*********************************************/
	public function forgotpassword(){

	$this->setInfo('sitemap', true);
	$this->setInfo('page_title', 'Forgot Password');
	$this->mvc->template->link_registration = url('index.php?module=auth&action=subscribe');
	$this->mvc->template->link_forgotpassword = url('index.php?module=auth&action=forgotpassword');
	
		if ( (isSet($_POST['forgot'])) AND (!empty($_POST['forgot'])) )
		{
		
		$info_user = Auth::searchMemberByMail(trim($_POST['forgot']));

	
		$errors_forgot=array();
			if ($info_user != false)
			{
			// G�n�re le nouveau mots de passe
			$pass = NULL;
			$charlist = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz0123456789';
			$ps_len = strlen($charlist);
			mt_srand((double)microtime()*1000000);

				for($i = 0; $i < 6; $i++) { $pass .= $charlist[mt_rand(0, $ps_len - 1)]; }
				if (Auth::updatePassword($info_user['idmember'] , $pass, $info_user['loginmember']))
				{
				// Preparation du mail
				$message_mail = '<html><head></head><body>
				<p>
					Rappel de vos identifiants de connection.
				</p>
				<p>
					Your user name : '.$info_user['loginmember'].'<br />
					Your password : '.$pass.'
				</p>
				<hr>
				IP demandeur: '.Securite::ipX().'
				En cas d\'abus ou d\'utilisation par un tiers, n\'h&eacute;sitez; pas &agrave; nous le faire savoir.
				</body></html>';
				$mail_send = new Mail('New password <'.$_SERVER['SERVER_NAME'].'>', $message_mail, $info_user['mailmember'], ADMIN_MAIL);
				$this->mvc->template->mailStatus = $mail_send->sendMailHtml();
				$this->mvc->template->user = $info_user['loginmember'];
				$this->mvc->template->show('auth/forgotpassword_success');
				}
				else // Erreur modification password
				{
				$errors_forgot[] = "Une erreur c'est produite lors de la modification du mot de passe.<br  />
				Veuillez contacter l'administrateur du site.";
				
				$this->mvc->template->errors_forgotpassword = $errors_forgot;
				$this->mvc->template->show('auth/forgotpassword');
				}
			}
			else // Membre introuvable
			{
			$errors_forgot[] = "Votre adresse de messagerie est introuvable.";
			$this->mvc->template->errors_forgotpassword = $errors_forgot;
			$this->mvc->template->show('auth/forgotpassword');
			}
		}
		else
		{
		$this->mvc->template->show('auth/forgotpassword');
		}
		
	
	
	}
	
	
	
	
	
	/*********************************************/
	/* Deconnexon d'un membre/client 			 */
	/*********************************************/
	public function logout(){
	$this->setInfo('sitemap', false);
	$this->setInfo('page_title', 'Confirmation de d�connexion');
	
		if (isSet($_SESSION['user']['pseudo']))
		{
		$this->mvc->template->user = $_SESSION['user']['pseudo'];
		Auth::updateLastactivity($_SESSION['user']['id']);
		}
		else
		{
		header("location: ".__CW_PATH);die();
		}
		
	// Suppression de toutes les variables et destruction de la session
	$_SESSION = array();
	
	$_SESSION['user']['power_level'] = 1;
	// Suppression des cookies de connexion automatique
	setcookie('id', '', 0, '/');
	setcookie('connection_auto', '', 0, '/');
	header("Refresh: 5;url=".__CW_PATH);
	$this->mvc->template->show('auth/logout');
	}
	
	
	public function subscribe()
	{
	// Include captcha
	//include 'captcha.php';
	$this->setInfo('sitemap', true);
	$this->setInfo('page_title', 'Cr&eacute;ation d\'un compte');
	
	
	

	
	$email = (isSet($_POST['mail'])) ? $_POST['mail'] : NULL;
		$this->mvc->template->email = $email;
	$password = (isSet($_POST['password'])) ? $_POST['password'] : NULL;
		$this->mvc->template->password = $password;
	$passwordother = (isSet($_POST['otherpassword'])) ? $_POST['otherpassword'] : NULL;
	$login = (isSet($_POST['user'])) ? $_POST['user'] : NULL;
	
	$cleaner = strtr($login, 
	'����������������������������������������������������', 
	'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
	$login = preg_replace('/([^.a-z0-9]+)/i', '-', $cleaner);
		$this->mvc->template->login = $login;
	
		if (isSet($_POST['otherpassword']))
		{
		$errors_registration = array();
		$declare_coche = (isSet($_POST['declare_coche'])) ? $_POST['declare_coche'] : NULL;
		$email = (isSet($_POST['mail'])) ? $_POST['mail'] : NULL;
		$password = (isSet($_POST['password'])) ? $_POST['password'] : NULL;
		$passwordother = (isSet($_POST['otherpassword'])) ? $_POST['otherpassword'] : NULL;
		$login = (isSet($_POST['user'])) ? $_POST['user'] : NULL;
		
			if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$errors_registration[] = "Votre email est incorrect!";
			}
			
			if (strlen($password) < 6)
			{
				$errors_registration[] = "Votre mot de passe est trop court (min 6 caract&egrave;res) !";
			}
			
			if ($password != $passwordother)
			{
				$errors_registration[] = "Votre mot de passe est diff&eacute;rent de la v&eacute;rification !";
			}
			
			if ($declare_coche==null)
			{
				$errors_registration[] = "Vous devez lire et accepter les conditions g&eacute;n&eacute;rales d'utilisation";
			}
			
			$Captcha = new Captcha();
			if ($Captcha->checkCaptcha()==false)
			{
			$errors_registration[] = "Le captcha anti-robot est incorrect";
			}
			

			if (Auth::searchMemberByLogin($login) != false)
			{
			$errors_registration[] = "Ce nom d'utilisateur est d&eacute;j&agrave; utilis&eacute;.";
			}
			elseif (Auth::searchMemberByMail(strtolower($email)) != false)
			{
			$errors_registration[] = "Cette adresse e-mail est d&eacute;j&agrave; utilis&eacute;e.";
			}
			
			if (count($errors_registration))
			{
			
				$this->mvc->template->errors_registration=$errors_registration;
				$this->mvc->template->captcha_img = Captcha::generateImgTags("..");
				$this->mvc->template->captcha_hidden = Captcha::generateHiddenTags();
				$this->mvc->template->captcha_input = Captcha::generateInputTags();
				/*** Affichage ***/
				$this->mvc->template->show('auth/subscribe');
			}
			else
			{
			

				$hash_validation = md5(uniqid(rand(), true));
				// On transforme la chaine en entier
				$id_user = (int) $id_user;
				// Preparation du mail
				
				$message_mail = '<html>
				<body>
				<p>
				Bonjour '.$login.'.<br />
				Merci pour votre inscription sur Imagine Your Craft. <br />
				Voici le lien &agrave; suivre pour valider votre compte :
				<a href="'.url('index.php?module=auth&action=validate&hash='.$hash_validation).'">'.url('index.php?module=auth&action=validate&hash='.$hash_validation).'</a><br /><br />
				Merci.
				</p>
				
				<p>
				Pour rappel :<br />
				Login: '.$login.'<br />
				Mot de passe: '.$password.'<br />
				<br />
				Les r&ecirc,gles que vous avez accept�:<br />
				'.__CGU.'
				
				</p>
				
				</body>
				</html>';
				
				$mail_send = new Mail('['.SITE_NAME.'] Confirmation d\'inscription',$message_mail,strtolower($email), ADMIN_MAIL);
				$mail_send->sendMailHtml() or die('Mail restriction, can not send');
			
			
				Auth::addMember($login, $password, $email, $hash_validation);
				$this->mvc->template->show('auth/subscribe_success');
			}
		
		}
		else
		{
	
	//	$this->mvc->template->flag = $cw_flag;
		$this->mvc->template->errors_registration=NULL;
		$this->mvc->template->captcha_img = Captcha::generateImgTags("..");
		$this->mvc->template->captcha_hidden = Captcha::generateHiddenTags();
		$this->mvc->template->captcha_input = Captcha::generateInputTags();
		/*** Affichage ***/
		$this->mvc->template->show('auth/subscribe');
		
		}
	}


	public function validate()
	{
	
	$this->setInfo('sitemap', false);
	$this->setInfo('page_title', 'V&eacute;rification du compte');
	$hash = (isSet($_GET['hash'])) ? $_GET['hash'] : null;
		// On v�rifie qu'un hash est pr�sent
		if (!empty($hash)) {
		
			// valider_compte_avec_hash() est d�finit dans ~/modeles/membres.php
			if (Auth::checkHash($hash)==true)
			{
				/*** Affichage ***/
				$this->mvc->template->show('auth/subscribe_verified');
			// CheckHash return false, request is invalid
			} else {
				/*** Affichage ***/
				$this->mvc->template->show('auth/subscribe_invalid');
			}

		}
		else
		{
		header('location: http://www.crystal-web.org');die();
		}
	}
	

		
}

?>
