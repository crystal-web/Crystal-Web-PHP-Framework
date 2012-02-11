<?php
/**
* @title Member Registration Controller
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
*/					
				

Class registrationController Extends baseController {

private $module=array(
	/* BOOL */
	'sitemap' => false,
	'title' => 'Inscription', // Titre du module
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

	/*** Probable liste des membres ***/
	public function index() {
	return $this->registration();
	}
	
	public function cguConditionsGenerales()
	{
		$this->setInfo('page_title', 'Condition G&eacute;n&eacute;ral');
		echo '<p align="center">
		<textarea cols="60" rows="15" style="font-weight:700; color:#5B6CBA;">'.__CGU.'</textarea>
		</p>';
	}
	
	/*********************************************/
	/* Enregistrement d'un nouveau membre/client */
	/*********************************************/
	
	public function registration()
	{

        // Include captcha
        include 'captcha.php';
	$this->setInfo('sitemap', true);
	$this->setInfo('page_title', 'Cr&eacute;ation d\'un compte');

		// Création d'un tableau des erreurs
		$errors_registration = array();

		// Validation des champs suivant les règles en utilisant les données du tableau $_POST
		if (isSet($_POST['submit_creer_client']))
		{

// Tentative d'ajout du membre dans la base de donnees
$login = (!empty($_POST['login'])) ? strtolower($_POST['login']) : NULL;
$password = (!empty($_POST['password'])) ? $_POST['password'] : NULL;

$email = (!empty($_POST['email'])) ? $_POST['email'] : NULL;
$email_confirm = (!empty($_POST['email_confirm'])) ? $_POST['email_confirm'] : NULL;
$id_civilite = (!empty($_POST['id_civilite']['sex'])) ? $_POST['id_civilite']['sex'] : NULL;

$prenom = (!empty($_POST['prenom'])) ? $_POST['prenom'] : NULL;
$nom = (!empty($_POST['nom'])) ? $_POST['nom'] : NULL;
$adresse1 = (!empty($_POST['adresse1'])) ? $_POST['adresse1'] : NULL;
$adresse2 = (!empty($_POST['adresse2'])) ? $_POST['adresse2'] : NULL;
$cp = (!empty($_POST['cp'])) ? $_POST['cp'] : NULL;
$ville = (!empty($_POST['ville'])) ? $_POST['ville'] : NULL;
$pays = (!empty($_POST['country_id'])) ? $_POST['country_id'] : NULL;

$telephone = (!empty($_POST['telephone'])) ? $_POST['telephone'] : NULL;
$fax = (!empty($_POST['fax'])) ? $_POST['fax'] : NULL;
$portable = (!empty($_POST['portable'])) ? $_POST['portable'] : NULL;
$msn = (!empty($_POST['msn'])) ? $_POST['msn'] : NULL;
$client_birthdate_day = (!empty($_POST['client_birthdate']['day'])) ? $_POST['client_birthdate']['day'] : 0;
$client_birthdate_month = (!empty($_POST['client_birthdate']['month'])) ? $_POST['client_birthdate']['month'] :0;
$client_birthdate_year = (!empty($_POST['client_birthdate']['year'])) ? $_POST['client_birthdate']['year'] : 0;
$declare_coche = (!empty($_POST['declare_coche'])) ? $_POST['declare_coche'] : NULL;
$date_birthday = strtotime($client_birthdate_day . '-' . $client_birthdate_month . '-' . $client_birthdate_year . ' 0:0:0');
		
			
			if (!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$errors_registration[] = "Votre email est incorrect!";
			}	
			if ($email != $email_confirm)
			{
				$errors_registration[] = "Votre email et votre email de confirmation sont diff&eacute;rent";
			}				
			if (strlen($password) < 6)
			{
				$errors_registration[] = "Votre mot de passe est trop court (min 6 caract&egrave;res) !";
			}
			if ($prenom==null)
			{
				$errors_registration[] = "Veuillez indiquer votre prénom";
			}
			if ($nom==null)
			{
				$errors_registration[] = "Veuillez indiquer votre nom";
			}
			if ($adresse1==null)
			{
				$errors_registration[] = "Veuillez indiquer votre adresse";
			}
			if ($cp==null)
			{
				$errors_registration[] = "Veuillez indiquer votre code postal";
			}
			if ($ville==null)
			{
				$errors_registration[] = "Veuillez indiquer votre ville";
			}
			if (!checkdate($client_birthdate_month,$client_birthdate_day,$client_birthdate_year))
			{
				$errors_registration[] = "Veuillez indiquer une date de naissance valide";
			}
			if ($declare_coche==null)
			{
				$errors_registration[] = "Vous devez lire et accepter les <a href=\" " . url('index.php?module=registration&action=condition') . " \" target=\"_blank\">conditions générales d'utilisation</a>";
			}			
			
                        
                       
			$Captcha = new Captcha();
			if ($Captcha->checkCaptcha()==false)
			{
			$errors_registration[] = "Le captcha anti-robot est incorrect";
			}			


			if (Login::searchMemberByLogin($login) != false)
			{
			$errors_registration[] = "Ce nom d'utilisateur est déjà utilisé.";
			}
			elseif (Login::searchMemberByMail(strtolower($email)) != false)
			{
			$errors_registration[] = "Cette adresse e-mail est déjà utilisée.";
			}

						
			// Si d'autres erreurs ne sont pas survenues
			if (empty($errors_registration))
			{

				/*** Traitement du formulaire ***/
				
				// Tiré de la documentation PHP sur <http://fr.php.net/uniqid>
				$hash_validation = md5(uniqid(rand(), true));
				
				
	
				// Defini dans libs member.class
				$idmember = Login::addMember($login, $password, $email, $hash_validation);

				$client = new Client();
				$id_user = $client->newClient($idmember, $id_civilite, ucfirst($prenom), strtoupper($nom), $adresse1, $adresse2, $cp, ucfirst($ville), $pays, $date_birthday, $telephone, $portable, $fax, $msn, $hash_validation);

$civilite = NULL;
switch ($id_civilite)
{
case '0';
$civilite = "Mlle.";
break;
case '1';
$civilite = "Mme.";
break;
case '2';
$civilite = "Mr.";
break;
}

// On transforme la chaine en entier
$id_user = (int) $id_user;

					// Preparation du mail
					ob_start();
					require 'themes/mail/registration.php';
					$message_mail = ob_get_contents();
					ob_end_clean();
					
					$mail_send = new Mail('['.SITE_NAME.'] Confirmation d\'inscription',$message_mail,strtolower($email), ADMIN_MAIL);
					$mail_send->sendMailHtml() or die('Mail restriction, can not send');

					/*** Variables ***/
					$this->mvc->template->user = $login;
					
					/*** Affichage ***/
					$this->mvc->template->show('client/registration_success');

				
			}
			else
			{ 

				// On affiche à nouveau le formulaire d'inscription

				$captcha = new Captcha();
				
				/*** Variables ***/
				global $cw_flag;
				$this->mvc->template->flag = $cw_flag;
				$this->mvc->template->errors_registration = $errors_registration;
				$this->mvc->template->captcha_img = Captcha::generateImgTags("..");
				$this->mvc->template->captcha_hidden = Captcha::generateHiddenTags();
				$this->mvc->template->captcha_input = Captcha::generateInputTags();
				
				/*** Affichage ***/
				$this->mvc->template->show('client/registration');
			}

		}
		else
		{

		/*** Variables ***/	
		global $cw_flag;
		$this->mvc->template->flag = $cw_flag;
		$this->mvc->template->errors_registration=NULL;
		$this->mvc->template->captcha_img = Captcha::generateImgTags("..");
		$this->mvc->template->captcha_hidden = Captcha::generateHiddenTags();
		$this->mvc->template->captcha_input = Captcha::generateInputTags();
		/*** Affichage ***/
		$this->mvc->template->show('client/registration');
		}

	}	// END registration
	
	/*********************************************/
	/* Validation d'un nouveau membre/client	 */
	/*********************************************/
	
	public function validate()
	{
	$this->setInfo('sitemap', false);
	$this->setInfo('page_title', 'V&eacute;rification du compte');
	
	// On vérifie qu'un hash est présent
	if (!empty($_GET['hash'])) {
	
		// valider_compte_avec_hash() est définit dans ~/modeles/membres.php
		if (Client::checkHash($_GET['hash'])==true)
		{

			/*** Affichage ***/
			$this->mvc->template->show('client/registration_verified');
		// CheckHash return false, request is invalid
		} else {
			/*** Affichage ***/
			$this->mvc->template->show('client/registration_invalid');
		}

	}
	elseif (isSet($_POST['hash']))
	{
	
		// valider_compte_avec_hash() est définit dans ~/modeles/membres.php
		if (Client::checkHash($_POST['hash'], $_POST['mail'])==true)
		{

			/*** Affichage ***/
			$this->mvc->template->show('client/registration_verified');
		// CheckHash return false, request is invalid
		} else {
			/*** Affichage ***/
			$this->mvc->template->show('client/registration_invalid');
		}
	}
	else {
	// No param, request is invalid
		/*** Affichage ***/
		$this->mvc->template->show('client/registration_invalid');
	}
	
	}
	
	
	public function login()
	{
	$this->setInfo('page_title', 'Connection');
	
	/* Retour a la page précédente après connection */
	if (!isSet($_SESSION['pageBeforeConnect']))
	{
		$array_refere=Securite::referer();
		if (/* La requete est interne */
		preg_match("/".$_SERVER['SERVER_NAME']."/i", $array_refere['host']) 
		/* La requete n'est pas la demande */
		&& !preg_match("/login/i", $_SERVER['HTTP_REFERER'])
		) {
		$_SESSION['pageBeforeConnect']=$_SERVER['HTTP_REFERER'];
		}
	}
	
	/* Formulaire est poste */
	if (isSet($_POST['login']))
	{
	$user = (isSet($_POST['login'])) ? $_POST['login'] : '';
	$password = (isSet($_POST['pass'])) ? $_POST['pass'] : '';
	$infos_user = Client::checkLogin($user, $password);

	/* Non actif, systeme de bannisement
	if (!empty($id_user['banned'])){
		// Login::checkBannedReason($id_user['banned'])
		$banned = unserialize($id_user['banned']);
		// $banned['time'] $banned['reason']
	
	}*/

	// Si les identifiants sont valides
	if (false !== $infos_user)
	{

	if (empty($infos_user['hash']))
	{
	// On enregistre les informations dans la session
	$_SESSION['user']['id']     = $id_user['id'];
	$_SESSION['user']['pseudo'] = $user;
	$_SESSION['user']['avatar'] = $infos_user['avatar'];
	$_SESSION['user']['mail']  = $infos_user['mail'];
	$_SESSION['user']['group']  = Client::readGroup($infos_user['user_groups']);
	$_SESSION['user']['power_level'] = $infos_user['level'];

	
		// Mise en place des cookies de connexion automatique
		if ($_POST['connection_auto'] == 'on')
		{
		$navigateur = (!empty($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$hash_cookie = sha1(Securite::Hcrypt($user.magicword.$navigateur));

		setcookie( 'id', $_SESSION['user']['id'], ( time() + 60*60*24*30), '/');
		setcookie('connection_auto', $hash_cookie, ( time() + 60*60*24*30), '/');
		}
		
	// Affichage de la confirmation de la connexion
	
	/*********************************
	*	Ou va le client
	*	Viens t'il de s'inscire ?
	*	
	*********************************/
	
	
	/*** Affichage ***/
	$url=(isSet($_SESSION['pageBeforeConnect'])) ? $_SESSION['pageBeforeConnect'] : url('index.php?module=member&action=profile');
	$this->mvc->template->url=$url;
	
	$this->mvc->template->show('client/login_success');

	header("Refresh: 5;url=".$url);
	}
	// Le hash n'est pas vide, le client n'est pas vérifier
	else
	{

// Preparation du mail
$message_mail = '<html><head><style>img {border: none;}p {margin: 5px;}</style>
</head>
<body>
<table width="739" height="406" border="0" align="center">
<tr>
<td width="733" height="113"><a href="' . __CW_PATH . '"><img src="' . __CW_PATH . '/files/mail/v1/header.jpg" width="249" height="111" /></a><img src="' . __CW_PATH . '/files/mail/v1/index.jpg" width="484" height="111" /></td>
</tr>
<tr>
<td height="287"><table width="734" border="0" align="center" bgcolor="f5f5f5" style="-webkit-border-radius: 25px;
-moz-border-radius: 25px;
border-radius: 25px; border:thin; #333333">
<tr>
<td width="724"><p>Bienvenue chez <strong>' . SITE_NAME . '</strong></p>
<p>Bonjour, <strong>' . $civilite . ' ' . ucfirst($prenom) . ' ' . strtoupper($nom) . '</strong></p>

<p >Vous venez de souscrire une offre <strong>$OFFRE</strong> et nous vous en remercions. <br/>
</p>

<p >Afin de pouvoir acc&eacute;der &agrave; l\'espace client il vous faut confirmer votre inscription en cliquant sur le lien suivant :<br/>
<a href="' . url('index.php?module=registration&amp;action=validate&amp;hash='.$infos_user['hash']) . '">' . url('index.php?module=registration&amp;action=validate&amp;hash='.$infos_user['hash']) . '</a></p>

<table style="-webkit-border-radius: 15px;
-moz-border-radius: 15px;
border-radius: 15px; border:thin; #333333" width="504" border="0" bgcolor="#CCCFFF" align="center">
<tr>
<td><p>Autre moyen de confirmer votre adresse email :</p>
<ol>
<li>Aller sur <em><strong> <a href="' . url('index.php?module=registration&amp;action=validate') . '">' . url('index.php?module=registration&amp;action=validate') . '</a></p>
</strong></em></li>
<li>Saisissez cette clé de confirmation&nbsp; :<strong>'.$infos_user['hash'].'</strong></li>
</ol></td>
</tr>
</table>


<p>&nbsp;</p>
<p>Suite &agrave; l\'activation de votre compte, vous recevrez un email r&eacute;capitulant votre facture.</p>
<p><span>Pour toutes questions vous pouvez ouvrir un ticket via votre espace client ou contacter Support@Dyraz.com<br />
Dyraz vous remercie d\'avoir choisi ses services et vous souhaite une tr&egrave;s bonne  journ&eacute;e.</span></p>
<p>
<ul>
<li>Nom d\'utilisateur : ' . $user . '</li>
<li>Mot de passe : ' . $password . '</li>
</ul>
</p>

<p><span>L\'&eacute;quipe Dyraz<br />
<a href="' . __CW_PATH . '">www.Dyraz.com </a></span></p></td>
</tr>
</table>
<p class="style1">&nbsp;</p></td>
</tr>
</table>
<p>&nbsp; </p>
<p>&nbsp;</p>
</body>
</html>
';
					
					$mail_send = new Mail('Inscribed on <'.$_SERVER['SERVER_NAME'].'>',$message_mail,strtolower($infos_user['email']), ADMIN_MAIL);
					$mail_send->sendMailHtml() or die('Mail restriction, can not send');
	$this->mvc->template->login	= $user;	
	$this->mvc->template->show('client/login_hash_not_empty');
	}
	
	
	} else {

	$errors_connection[] = "Couple nom d'utilisateur / mot de passe inexistant.";

	// On réaffiche le formulaire de connexion
	
	/*** Variables ***/
	$this->mvc->template->errors_connection = $errors_connection;
	
	/*** Affichage ***/
	$this->mvc->template->show('client/login');
	}

} else {

	// On réaffiche le formulaire de connexion
	/*** Variables ***/
	$this->mvc->template->errors_connection = $errors_connection;

	/*** Affichage ***/
	$this->mvc->template->show('client/login');
}
	}
}

?>
